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
        $request->validate([
            'text' => 'required|string'
        ]);

        $names = array_filter(array_map('trim', explode("\n", $request->text)));

        if (empty($names)) {
            return back()->with('error', 'Tidak ada nama yang diinput');
        }

        $scadFile = storage_path('app/public/bloxify/templatev'.($request->styleSelect ?? 1).'.scad');
        $generatedFiles = [];
        $processes = [];

        foreach ($names as $name) {

            // $cleanName = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
            $cleanName = $name;
            $outputFile = $cleanName . '.stl';
            $outputStl = storage_path("app/public/bloxify/names/{$outputFile}");

            if (!file_exists(dirname($outputStl))) {
                mkdir(dirname($outputStl), 0755, true);
            }

            $process = new Process([
                'C:\Program Files\OpenSCAD\openscad.exe',
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

        return view('nama.download', compact('generatedFiles'));
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
