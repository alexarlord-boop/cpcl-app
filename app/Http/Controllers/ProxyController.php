<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Parser;
use App\Services\IOUtils;
use App\Enums\Section; // Import the Section enum

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
                $this->parseAndShowHelper($fileContent);
            }
        }

        // ... Rest of your logic

        return redirect()->route('proxy.index');
    }

    private function parseAndShowHelper($fileContent)
    {
        $parser = new Parser();
        $data = $parser->parseYamlFile($fileContent);
        print_r($data);
        $parser->extractEntities($data);
        $entities = $parser->getEntities();

        $_SESSION['parsed_entities'] = serialize($entities);
    }

    // ... Rest of your controller methods
}
