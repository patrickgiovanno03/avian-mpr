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
        // Validasi input
        $request->validate([
            'text' => 'required|string'
        ]);

        // Pisahkan nama berdasarkan enter/newline
        $names = array_filter(array_map('trim', explode("\n", $request->text)));
        
        if (empty($names)) {
            return back()->with('error', 'Tidak ada nama yang diinput');
        }

        $scadFile = storage_path('app/public/bloxify/templatev1.scad');
        $generatedFiles = [];

        // Generate STL untuk setiap nama
        foreach ($names as $name) {
            $cleanName = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
            $outputFile = $cleanName . '.stl';
            $outputStl = storage_path("app/public/bloxify/names/{$outputFile}");

            // Buat direktori jika belum ada
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

            $process->setTimeout(60);
            $process->run();

            if ($process->isSuccessful() && file_exists($outputStl)) {
                $generatedFiles[] = [
                    'url' => route('nama.download', ['filename' => $outputFile]),
                    'name' => $outputFile
                ];
            }
        }

        if (empty($generatedFiles)) {
            return back()->with('error', 'Gagal generate file');
        }

        // Return view dengan daftar file untuk di-download
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
