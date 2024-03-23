<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Enums\EntityProtocol;
use App\Enums\EntityType;
use App\Enums\MetadataStrings;
use App\Models\OidcClient;
use App\Services\EntityDTO;
use App\Services\IOUtils;
use App\Services\Parser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//use SimpleSAML\Configuration;
use Illuminate\Support\Facades\Storage;
use SimpleSAML\Metadata\MetaDataStorageSource;
use SimpleXMLElement;


class ProxyController extends Controller
{
    public function index()
    {
        session_start();
        return $this->show();
    }

    public function parseAndShow(Request $request)
    {

        //$uploadDir = 'uploads/';

        if ($request->isMethod('post')) {
            $file = $request->file('file');

            // passing new file
            if ($file) {

                $fileType = $file->getClientOriginalExtension();
                $fileName = $file->getClientOriginalName();
                if (strtolower($fileType) !== 'yaml') {
                    return redirect()->back()->with('error', 'Only config files (.yaml) are allowed.');
                }

                $fileToParse = Constants::UPLOAD_DIR . $fileName;
                session(['pathFileToParse' => $fileToParse]);
                $file->move(Constants::UPLOAD_DIR, $fileName);
            }

            // getting from uploads
            if ($request->has('uploadedFile')) {
                $fileToParse = $request->input('uploadedFile');
                session(['pathFileToParse' => $fileToParse]);
            }


            $fileToParse = session('pathFileToParse');
            if ($fileToParse) {
                $fileName = explode("/", $fileToParse)[1];

                session(['uploaded_file' => $fileName]);
                $io = new IOUtils();
                $fileContent = $io->readFile($fileToParse);
                $this->getEntitiesFromYaml($fileContent);
                $entities = Cache::get('entities');
                $rules = Cache::get('rules');

                Cache::put('rules', $rules, Constants::ENTITY_CACHE_LIVE);
                return view('proxy.index', compact('entities', 'rules', 'fileName'));
            }
        }

        $this->show();
    }

    public function show()
    {
        $entities = Cache::get('entities');
        $rules = Cache::get('rules');
        $fileName = session('uploaded_file');
        return view('proxy.index', compact('entities', 'rules', 'fileName'));
    }

    private function getEntitiesFromYaml($fileContent)
    {
        $parser = new Parser();
        $yamlData = $parser->parseYamlFile($fileContent);
        $rules = $yamlData['rules'] ?? null;
        $parser->extractEntities($yamlData);
        Cache::put('entities', $parser->getEntities(), Constants::ENTITY_CACHE_LIVE);
        Cache::put('rules', $rules, Constants::ENTITY_CACHE_LIVE);
    }

    public function processSamlEntity(Request $request)
    {

        try {
            $entity = unserialize(json_decode($request->input('samlEntity')));

            // update the config: module_metarefresh.php
            // Read a file
            $filePath = Constants::METAREFRESH_PATH;


            // Check if the file exists
            if (file_exists($filePath)) {

                // Process the file contents...
                $this->updateMetarefreshConfigWithEntity($filePath, $entity);

            } else {
                // File does not exist
                $request->session()->flash('error', "File does not exist at $filePath");
            }


        } catch (Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        // Redirect back with the notification
        return redirect()->route('proxy.index');
    }

    public function processOidcEntity(Request $request)
    {
        $entity = unserialize(json_decode($request->input('oidcEntity')));

        $this->insertOidcToDatabase($request, $entity);

        return redirect()->route('proxy.index');
    }

    function generateConfigSection($type, $metadataUrl)
    {
        // Define the base configuration for the section
        // Return the generated configuration section
        return array(
            'cron' => ['hourly'],
            'sources' => [
                [
                    'src' => $metadataUrl,
                ],
            ],
            'expireAfter' => 60 * 60 * 24 * 4, // Maximum 4 days cache time.
            'outputDir' => 'metadata/' . $type,
            'outputFormat' => 'flatfile',
        );
    }

    private function updateMetarefreshConfigWithEntity($filePath, EntityDTO $entity)
    {

        // Read the existing config file

        include $filePath;

        $type = $entity->getType();
        $metadataUrl = $entity->getResourceLocation();

        // Modify the $config array based on $type and $metadataUrl
        switch ($type) {
            case EntityType::IDP:
            case EntityType::SP:
            case EntityType::IDPS:
            case EntityType::SPS:
                $config['sets'][$type] = [
                    'cron' => ['hourly'],
                    'sources' => [
                        [
                            'src' => $metadataUrl,
                        ],
                    ],
                    'expireAfter' => 60 * 60 * 24 * 4, // Maximum 4 days cache time.
                    'outputDir' => 'metadata/' . $type,
                    'outputFormat' => 'flatfile',
                ];
                break;
            default:
                // Handle default case
        }

        $configString = var_export($config, true);

        // Format the string for readability
        $configString = '<?php $config = ' . PHP_EOL . $configString . ';' . PHP_EOL;

        // Write the formatted string to the file
        file_put_contents($filePath, $configString);
    }

    private function insertSamlToDatabase(Request $request, $result, $table)
    {
        // decoupling data into id-data pair
        $firstKeyValuePair = reset($result);
        $entity_id = key($result);
        $entity_data = json_encode($firstKeyValuePair);

        try {
            DB::table($table)->updateOrInsert(
                ['entity_id' => $entity_id], // Key to check if the record exists
                ['entity_data' => $entity_data] // Data to insert or update
            );
            $request->session()->flash('success', 'Inserted or updated ' . $entity_id . ' successfully.');
        } catch (Exception $e) {
            Log::error("DB failure. Entity ID: $entity_id." . $e->getMessage());
            $request->session()->flash('error', "DB failure. " . $e->getMessage());
        }
    }

    private function insertOidcToDatabase(Request $request, EntityDTO $entity)
    {
        try {
            OidcClient::updateOrCreate(
                ['id' => $entity->getEntityId()], // Key to check if the record exists
                [
                    'secret' => $entity->getClientSecret(),
                    'name' => $entity->getName(),
                    'description' => $entity->getDescription(),
                    'auth_source' => 'default-sp',
                    'redirect_uri' => json_encode([$entity->getResourceLocation()]),
                    'scopes' => '["openid","email","private"]',
                    'is_enabled' => 1,
                    'is_confidential' => 1,
                    'owner' => NULL,
                    'post_logout_redirect_uri' => NULL,
                    'backchannel_logout_uri' => NULL,
                ]
            );

            $request->session()->flash('success', 'Inserted or updated ' . $entity->getName() . ' successfully.');
        } catch (\Exception $e) {
            Log::error('DB failure: ' . $e->getMessage());
            $request->session()->flash('error', 'DB failure: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function getXmlMetadata($request, $xmlUrl): string|null
    {
        try {
            $ch = curl_init($xmlUrl);
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Ignore SSL verification (use with caution)
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                // Handle the error
                $request->session()->flash('error', curl_error($ch));
                curl_close($ch);
                return null;
            } else {
//            $this->storeMetadataInFile($response, $filePath);
                curl_close($ch);
                return $response;
            }
        } catch (Exception $e) {
            throw new Exception(message: $e->getMessage());
        }
    }

    private function storeMetadataInFile($response, $filePath)
    {
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($filePath, $response, FILE_APPEND | LOCK_EX);
    }

    private function parseXmlToPhpArray($request, string $srcXml, $set): ?array
    {
        try {
            return MetaDataStorageSource::getSource(['type' => 'xml', 'xml' => $srcXml])
                ->getMetadataSet($set);
        } catch (\Exception $e) {
            Log::error("Error parsing XML file: " . $e->getMessage() . " " . $e->getCode());
            $request->session()->flash("error", "Error parsing XML file: " . $e->getMessage() . " " . $e->getCode());
            return null;
        }
    }


    public function clearCache()
    {
        Cache::forget('entities');
        Cache::forget('rules');
        session()->forget('uploaded_file');
        session()->forget('pathFileToParse');

        return $this->show();
    }

    public function checkAll(Request $request)
    {
        try {
            $oidcClients = OIDCClient::all();
            $idpEntries = DB::table(MetadataStrings::IDP_TABLE)->get();
            $spEntries = DB::table(MetadataStrings::SP_TABLE)->get();

            return view("proxy.all", compact('oidcClients', 'idpEntries', 'spEntries'));
        } catch (Exception $e) {
            $request->session()->flash('error', 'Error: ' . $e->getMessage());
            return redirect()->route('proxy.index');
        }
    }

    public function deleteEntry(Request $request, $protocol, $type, $id)
    {
        try {
            $id = base64_decode($id);
            if ($protocol === EntityProtocol::OIDC) {
                // Delete OIDC entry
                OidcClient::destroy($id);

            } elseif ($protocol === EntityProtocol::SAML) {
                // Delete SAML entry
                switch ($type) {
                    case EntityType::IDP:
                    case EntityType::IDPS:
                        DB::table(MetadataStrings::IDP_TABLE)->where('entity_id', '=', $id)->delete();
                        break;
                    case EntityType::SP:
                    case EntityType::SPS:
                        DB::table(MetadataStrings::SP_TABLE)->where('entity_id', '=', $id)->delete();
                        break;
                    default:
                        // Handle unknown entity type
                        break;
                }
            }
            $request->session()->flash('success', "Deleted $id");
        } catch (Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route("check.all");
    }
}
