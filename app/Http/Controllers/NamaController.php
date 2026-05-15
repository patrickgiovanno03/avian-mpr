<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use ZipArchive;

class NamaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('nama.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generate(Request $request)
    {
        // Get the text array (text[normal], text[outline], etc.)
        $textArray = $request->input('text', []);
        
        // Validate that at least one style has input
        $textArray = array_filter($textArray, function($value) {
            return !empty(trim($value));
        });

        if (empty($textArray)) {
            return back()->with('error', 'Pilih minimal 1 style dan input nama');
        }

        // Mapping for text transformation per style
        $mappingTipeCapital = [ // 1 : upper, 2 : lower, 3 : random
            'outline' => 1,
            'mc' => 2,
            // 'pokemon' => 3,
        ];

        $generatedFiles = [];
        $processes = [];

        // Process each selected style
        foreach ($textArray as $style => $textInput) {
            $names = array_filter(array_map('trim', explode("\n", $textInput)));

            if (empty($names)) {
                continue;
            }

            // Apply text transformation based on style
            if ($mappingTipeCapital[$style] ?? false) {
                $tipe = $mappingTipeCapital[$style];
                $names = array_map(function($name) use ($tipe) {
                    if ($tipe === 1) {
                        return strtoupper($name);
                    } elseif ($tipe === 2) {
                        return strtolower($name);
                    } elseif ($tipe === 3) {
                        // 1 up and 1 low
                        $result = '';
                        for ($i = 0; $i < strlen($name); $i++) {
                            if ($i % 2 === 0) {
                                $result .= strtoupper($name[$i]);
                            } else {
                                $result .= strtolower($name[$i]);
                            }
                        }
                        return $result;
                    }
                    return $name;
                }, $names);
            }

            // Get the template version for this style
            $scadFile = public_path("openscad/templatev{$style}.scad");

            foreach ($names as $name) {
                $cleanName = $name;
                $outputFile = "{$cleanName}_{$style}_NAMABLOXIFY.stl";
                $outputStl = storage_path("app/public/bloxify/names/{$outputFile}");

                if (!file_exists(dirname($outputStl))) {
                    mkdir(dirname($outputStl), 0755, true);
                }

                $process = new Process([
                    '/Applications/OpenSCAD-2021.01.app/Contents/MacOS/OpenSCAD',
                    '-o',
                    $outputStl,
                    '-D',
                    "text=\"{$cleanName}\"",
                    $scadFile
                ]);

                $process->setTimeout(120);

                // 🔥 JANGAN run(), tapi start()
                $process->start();

                $processes[] = [
                    'process' => $process,
                    'outputFile' => $outputFile,
                    'outputStl' => $outputStl
                ];
            }
        }
        // dd($processes);

        // 🔥 Tunggu semua proses selesai
        foreach ($processes as $item) {

            $process = $item['process'];

            while ($process->isRunning()) {
                usleep(100000); // 0.1 detik biar CPU tidak 100%
            }

            if ($process->isSuccessful() && file_exists($item['outputStl'])) {
                $generatedFiles[] = [
                    'url' => route('nama.download', ['filename' => $item['outputFile']]),
                    'name' => $item['outputFile']
                ];
            }
        }

        if (empty($generatedFiles)) {
            return back()->with('error', 'Gagal generate file');
        }

        $selectedStyles = array_keys($textArray);
        $queryString = implode('&', array_map(function ($style) {
            return 'style[]=' . urlencode($style);
        }, $selectedStyles));

        $backUrl = route('nama.index') . ($queryString ? ('?' . $queryString) : '');

        return view('nama.download', compact('generatedFiles', 'backUrl'));
    }

    public function downloadFile($filename)
    {
        $filePath = storage_path("app/public/bloxify/names/{$filename}");
        
        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath);
    }
}
