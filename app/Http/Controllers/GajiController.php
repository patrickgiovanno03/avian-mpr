<?php

namespace App\Http\Controllers;

use App\Models\DGaji;
use App\Models\HGaji;
use App\Models\MGaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Spatie\Browsershot\Browsershot;
use ZipArchive;
use File;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('gaji.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $params['mgaji'] = null;
        return view('gaji.form', $params);
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
        $params['mgaji'] = MGaji::with('hgaji')->findOrFail($id);
        return view('gaji.form', $params);
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

    public function upload(Request $request)
    {
        //
        $mgaji = new MGaji();
        $mgaji->Tanggal = now();
        $mgaji->save();

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('gaji/'.$mgaji->GajiID, 'public');
            $hgaji = new HGaji();
            $hgaji->GajiID = $mgaji->GajiID;
            $hgaji->URL = $path;
            $hgaji->save();
        }
        // dd($request->all());
        return redirect()->route('gaji.edit', $mgaji->GajiID)->with('success', 'Gaji uploaded successfully.');
    }

    public function storeDetail(Request $request, $id)
    {
        //
        $hgaji = HGaji::findOrFail($id);
        $hgaji->Bonus = $request->input('bonus', 0);
        $hgaji->PegawaiID = $request->input('pegawai');
        $hgaji->save();
        DGaji::where('HeaderID', $hgaji->HeaderID)->delete();
        foreach ($request->input('pokok') as $index => $pokok) {
            //
            $dgaji = new DGaji();
            $dgaji->HeaderID = $hgaji->HeaderID;
            $dgaji->Pokok = $pokok;
            $dgaji->Jam = $request->input('jam')[$index];
            $dgaji->Lembur = $request->input('lembur')[$index];
            $dgaji->save();
        }

        return redirect()->route('gaji.edit', $hgaji->GajiID)->with('success', 'Gaji details saved successfully.');
    }

    public function slip($id)
    {
        //
        $params['hgaji'] = HGaji::with('pegawai', 'dgaji')->findOrFail($id);
        $html = view('gaji.slip-template', $params)->render();

        $fileName = 'slip_gaji_' . ($params['hgaji']->pegawai->Nama ?? '') . '.jpg';
        $path = storage_path("app/public/slip-gaji/{$fileName}");

        // Generate file sementara
        Browsershot::html($html)
            ->windowSize(1000, 1400)
            ->save($path);

        // Kembalikan file untuk di-download
        return Response::download($path)->deleteFileAfterSend(true);
    }
    
public function slipAll($gajiId)
{
    // Ambil semua data HGaji dengan GajiID yang sama
    $hgajis = HGaji::with('pegawai', 'dgaji')
                ->where('GajiID', $gajiId)
                ->get();

    if ($hgajis->isEmpty()) {
        return back()->with('error', 'Data gaji tidak ditemukan.');
    }

    // Buat folder sementara
    $folderPath = storage_path('app/public/slip-gaji-temp');
    if (!File::exists($folderPath)) {
        File::makeDirectory($folderPath, 0755, true);
    }

    $zipFileName = 'slip_gaji_semua_' . $gajiId . '.zip';
    $zipPath = storage_path("app/public/slip-gaji/{$zipFileName}");

    $zip = new ZipArchive;
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

        foreach ($hgajis as $hgaji) {
            $params['hgaji'] = $hgaji;
            $html = view('gaji.slip-template', $params)->render();

            $fileName = 'slip_gaji_' . ($hgaji->pegawai->Nama ?? 'pegawai') . '.jpg';
            $filePath = $folderPath . '/' . $fileName;

            // Generate slip gaji dalam format JPG
            Browsershot::html($html)
                ->windowSize(1000, 1400)
                ->save($filePath);

            // Tambahkan ke ZIP
            $zip->addFile($filePath, $fileName);
        }

        $zip->close();
    }

    // Hapus file sementara (opsional)
    File::deleteDirectory($folderPath);

    // Kembalikan file ZIP untuk di-download
    return Response::download($zipPath)->deleteFileAfterSend(true);
}
}
