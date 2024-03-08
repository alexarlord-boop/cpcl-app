<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Entity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\Parser;
use App\Services\IOUtils;
use App\Enums\Section;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Yaml\Yaml;

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
                $fileName = explode("/",$fileToParse)[1];

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

    public function show() {
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
        // Process your data here

        // Send a notification
        $request->session()->flash('success', 'Data processed successfully!');

        // Redirect back with the notification
        return redirect()->route('proxy.index');
    }

    public function clearCache(Request $request)
    {
        Cache::forget('entities');
        Cache::forget('rules');
        session()->forget('uploaded_file');
        session()->forget('pathFileToParse');

        return $this->show();
    }
}
