<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\Parser;
use App\Services\IOUtils;
use App\Enums\Section;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Yaml\Yaml;

class ProxyController extends Controller
{
    public function index()
    {
        session_start();
        $_SESSION['new_file'] = null;
        $_SESSION['uploaded_file'] = null;
        $uploadDir = 'uploads/';

        return view('proxy.index', [
            'uploadDir' => $uploadDir,
            'sections' => Section::toArray(), // Get sections from the Section enum
        ]);
    }

    public function parseAndShow(Request $request)
    {
        $fileToParse = null;
        $uploadDir = 'uploads/';
        $yamlContent = null;

        if ($request->isMethod('post')) {
            $file = $request->file('file');

            if ($file) {
                $fileType = $file->getClientOriginalExtension();
                if (strtolower($fileType) !== 'yaml') {
                    return redirect()->back()->with('error', 'Only config files (.yaml) are allowed.');
                }

                $fileToParse = $uploadDir . $file->getClientOriginalName();
                $file->move($uploadDir, $fileToParse);

                $_SESSION['new_file'] = $file->getClientOriginalName();
                $_SESSION['uploaded_file'] = $file->getClientOriginalName();
            }

            if ($request->has('uploadedFile')) {
                $fileToParse = $request->input('uploadedFile');
                $_SESSION['uploaded_file'] = explode('/', $fileToParse)[1];
            }


            if ($fileToParse) {
                $io = new IOUtils();
                $fileContent = $io->readFile($fileToParse);
                $entities = $this->getEntitiesFromYaml($fileContent);
                // Parse YAML file
                //$yamlContent = Yaml::parseFile($fileToParse);
                $fileName = explode("/",$fileToParse)[1];
//                return Redirect::route('proxy.index')->with('yamlContent', $yamlContent)->with('fileName', explode("/",$fileToParse)[1]);
                return view('proxy.index', compact('entities', 'fileName'));
            }
        }

        // ... Rest of your logic
//        return Redirect::route('proxy.index')->with('yamlContent', $yamlContent);
        return view('proxy.index', compact($yamlContent));
    }

    private function getEntitiesFromYaml($fileContent): array
    {
        $parser = new Parser();
        $yamlData = $parser->parseYamlFile($fileContent);
        $parser->extractEntities($yamlData);
        return $parser->getEntities();
//        return Entity::all();
    }

    // ... Rest of your controller methods
}
