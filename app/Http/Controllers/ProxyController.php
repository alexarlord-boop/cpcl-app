<?php

namespace App\Http\Controllers;

use App\Constants;
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
        // TODO:- check if ssl avoiding is a good option here
    {

        $entity = unserialize(json_decode($request->input('samlEntity')));

        // extract xml metadata
        $xmlUrl = $entity->getResourceLocation();
        // $filePath = Constants::XML_METADATA_DIR . $entity->getType() . '.xml';
        $srcXml = $this->getXmlMetadata($request, $xmlUrl);

        if ($entity->getType() == EntityType::IDP || $entity->getType() == EntityType::IDPS) {
            $set = MetadataStrings::IDP_SET;
            $table = MetadataStrings::IDP_TABLE;
        } elseif ($entity->getType() == EntityType::SP || $entity->getType() == EntityType::SPS) {
            $set = MetadataStrings::SP_SET;
            $table = MetadataStrings::SP_TABLE;
        }

        // convert xml to php via SimpleSaml scripts
        $result = $this->parseXmlFileToPhpArray($request, $srcXml, $set);

        // insert to db
        $this->insertSamlToDatabase($request, $result, $table);

        // Redirect back with the notification
        return redirect()->route('proxy.index');
    }

    public function processOidcEntity(Request $request){
        $entity = unserialize(json_decode($request->input('oidcEntity')));

        $this->insertOidcToDatabase($request, $entity);

        return redirect()->route('proxy.index');
    }

    private function insertSamlToDatabase(Request $request, $result, $table)
    {
        // decoupling data into id-data pair
        $firstKeyValuePair = reset($result);
        $entity_id = key($result);
        $entity_data = json_encode($firstKeyValuePair);

        try {
            DB::table($table)->insert([
                'entity_id' => $entity_id,
                'entity_data' => $entity_data,
            ]);
            $request->session()->flash('success', "Inserted successfully. Entity ID: $entity_id");
        } catch (Exception $e) {
            Log::error("DB failure. Entity ID: $entity_id." . $e->getMessage());
            $request->session()->flash('error', "DB failure. " . $e->getMessage());
        }
    }

    private function insertOidcToDatabase(Request $request, EntityDTO $entity){
        try {
            OidcClient::create([
                'id' => $entity->getEntityId(),
                'secret' => $entity->getClientSecret(),
                'name' => $entity->getName(),
                'description' => $entity->getDescription(),
                'auth_source' => 'default-sp',
                'redirect_uri' => $entity->getResourceLocation(),
                'scopes' => '["openid","email","private"]',
                'is_enabled' => 1,
                'is_confidential' => 1,
                'owner' => NULL,
                'post_logout_redirect_uri' => NULL,
                'backchannel_logout_uri' => NULL,
            ]);

            $request->session()->flash('success', 'Inserted ' . $entity->getName() . ' successfully.');
        } catch (\Exception $e) {
            Log::error('DB failure: ' . $e->getMessage());
            $request->session()->flash('error', 'DB failure: ' . $e->getMessage());
        }
    }

    private function getXmlMetadata($request, $xmlUrl): string|null
    {
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
    }

    private function storeMetadataInFile($response, $filePath)
    {
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($filePath, $response, FILE_APPEND | LOCK_EX);
    }

    private function parseXmlFileToPhpArray($request, string $srcXml, $set): ?array
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
}
