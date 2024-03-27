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
use FilesystemIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//use SimpleSAML\Configuration;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SimpleSAML\Metadata\MetaDataStorageSource;
use SimpleXMLElement;


class ProxyController extends Controller
{
    protected string $activeTab = 'saml';

    public function index()
    {
//        session_start();
        return $this->show();
    }

    public function parseAndShow(Request $request)
    {

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

    public function show(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $entities = Cache::get('entities');
        $rules = Cache::get('rules');
        $fileName = session('uploaded_file');
        $activeTab = $this->activeTab;
        return view('proxy.index', compact('entities', 'rules', 'fileName', 'activeTab'));
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

    public function processSamlEntity(Request $request): \Illuminate\Http\RedirectResponse
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

    public function processSamlEntities(Request $request)
    {
        try {
            $entities = unserialize(json_decode($request->input('samlEntities')));
            $filePath = Constants::METAREFRESH_PATH;
            if (file_exists($filePath)) {
                $this->updateMetarefreshConfigWithEntities($request, $filePath, $entities);
                $request->session()->flash('success', "Metadata config is updated!");
                $this->activeTab = 'oidc';
            } else {
                $request->session()->flash('error', "File does not exist at $filePath");
                $this->activeTab = 'saml';
            }
        } catch (Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return $this->show();
    }

    public function processOidcEntity(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $entity = unserialize(json_decode($request->input('oidcEntity')));
            $this->insertOidcToDatabase($request, $entity);
        } catch (Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('proxy.index');
    }

    public function processOidcEntities(Request $request)
    {
        try {
            $entities = unserialize(json_decode($request->input('oidcEntities')));
            $this->insertOidcToDatabase($request, $entities);
        } catch (Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            $this->activeTab = 'oidc';
        }

        return $this->show();
    }

    private function updateMetarefreshConfigWithEntities($request, $filePath, $entities)
    {

        // Read the existing config file
        include $filePath;

        // Array to track types for which configurations are set
        $configuredTypes = [];

        foreach ($entities as $entity) {
            $type = $entity->getType();
            $metadataUrl = $entity->getResourceLocation();

            // Check if entity exists in config, and if so, update URL
            if (isset($config['sets'][$type])) {
                $config['sets'][$type]['sources'][0]['src'] = $metadataUrl;
            } else {
                // If entity doesn't exist in config, create a new entry
                $config['sets'][$type] = [
                    'cron' => ['hourly'],
                    'sources' => [
                        [
                            'src' => $metadataUrl,
                        ],
                    ],
                    'expireAfter' => 60 * 60 * 24 * 4, // Maximum 4 days cache time.
                    'outputDir' => Constants::OUTPUT_DIR . $type,
                    'outputFormat' => 'flatfile',
                ];
            }
            // Track configured types
            $configuredTypes[] = $type;
        }

        // Remove configurations and directories for types not configured
        foreach ($config['sets'] as $setType => $setConfig) {
            if (!in_array($setType, $configuredTypes)) {
                $outputDir = $setConfig['outputDir'];
                // Check if the directory exists before attempting to delete
                if (is_dir($outputDir)) {
                    // Recursive directory deletion
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($outputDir, FilesystemIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::CHILD_FIRST
                    );
                    foreach ($files as $fileInfo) {
                        $action = ($fileInfo->isDir() ? 'rmdir' : 'unlink');
                        $action($fileInfo->getRealPath());
                    }
                    rmdir($outputDir); // Remove the main directory

                }
                unset($config['sets'][$setType]); // Remove configuration for the type
            }
        }

//        foreach ($entities as $entity) {
//            $type = $entity->getType();
//            $metadataUrl = $entity->getResourceLocation();
//
//            // Modify the $config array based on $type and $metadataUrl
//            // if no entity from current $config appeared during loop -> delete dir
//            // if entity in $entities only -> create entry in $config
//            // if in both -> update url
//            switch ($type) {
//                case EntityType::IDP:
//                case EntityType::SP:
//                case EntityType::IDPS:
//                case EntityType::SPS:
//                    $config['sets'][$type] = [
//                        'cron' => ['hourly'],
//                        'sources' => [
//                            [
//                                'src' => $metadataUrl,
//                            ],
//                        ],
//                        'expireAfter' => 60 * 60 * 24 * 4, // Maximum 4 days cache time.
//                        'outputDir' => Constants::OUTPUT_DIR . $type,
//                        'outputFormat' => 'flatfile',
//                    ];
//                    break;
//                default:
//                    // Handle default case
//            }
//        }

        $configString = var_export($config, true);

        // Format the string for readability
        $configString = '<?php $config = ' . PHP_EOL . $configString . ';' . PHP_EOL;

        // Write the formatted string to the file
        file_put_contents($filePath, $configString);
    }

    private function insertOidcToDatabase(Request $request, $entities)
    {
        try {
            DB::transaction(function () use ($entities) {
                foreach ($entities as $entity) {
                    OidcClient::updateOrCreate(
                        ['id' => $entity->getEntityId()], // Key to check if the record exists
                        [
                            'secret' => $entity->getClientSecret(),
                            'name' => $entity->getName(),
                            'description' => $entity->getDescription(),
                            'auth_source' => 'default-sp',
                            'redirect_uri' => json_encode([$entity->getResourceLocation()]),
                            'scopes' => $entity->getScopes(),
                            'is_enabled' => 1,
                            'is_confidential' => 1,
                            'owner' => null,
                            'post_logout_redirect_uri' => null,
                            'backchannel_logout_uri' => null,
                        ]
                    );
                }
            });
            $this->activeTab = 'rules';
            $request->session()->flash('success', 'Oidc Registry is updated!');
        } catch (\Exception $e) {
            $this->activeTab = 'oidc';
            Log::error('DB transaction failure: ' . $e->getMessage());
            $request->session()->flash('error', 'DB transaction failure: ' . $e->getMessage());
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

    public function deleteFile(Request $request, $filename)
    {
        try {
            $filePath = public_path('uploads/' . $filename); // Path to the file

            // Check if the file exists
            if (file_exists($filePath)) {
                // Delete the file
                unlink($filePath);
                $request->session()->flash('success', 'File deleted successfully');
            } else {
                 $request->session()->flash('error', 'File not found');
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to delete file: ' . $e->getMessage());
            // Return error response
            $request->session()->flash('error', 'Failed to delete file: ' . $e->getMessage());
        }
        $this->clearCache();
        return $this->show();
    }
}
