<?php

namespace App\Http\Controllers;

use App\Models\DGaji;
use App\Models\HGaji;
use App\Models\MGaji;
use App\Models\MPegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Spatie\Browsershot\Browsershot;
use ZipArchive;
use File;
use Intervention\Image\Facades\Image;

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
        $params['upload'] = false;
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
        $params['mgaji'] = MGaji::with('hgaji')->findOrFail($id);
        $params['upload'] = true;
        return view('gaji.form', $params);
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
        $params['mgaji'] = MGaji::with('hgaji', 'hgaji.dgaji')->findOrFail($id);
        $params['pegawaiList'] = MPegawai::all();
        $params['upload'] = false;
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
        $gaji = MGaji::findOrFail($id);
        foreach ($gaji->hgaji as $hgaji) {
            if (File::exists(storage_path('app/public/' . $hgaji->URL))) {
                File::delete(storage_path('app/public/' . $hgaji->URL));
            }
            foreach ($hgaji->dgaji as $dgaji) {
                $dgaji->delete();
            }
            $hgaji->delete();
        }
        $gaji->delete();

        return response()->json(['success' => true, 'message' => 'Gaji deleted successfully.']);
    }

    public function datatable(Request $request)
    {
        //
        $data = MGaji::with('hgaji');
        
        if (request()->has('startdate') && request()->has('enddate') && request()->startdate != null && request()->enddate != null) {
            $data = $data->whereBetween('Tanggal', [
                Carbon::createFromFormat('d/m/Y', request()->startdate)->format('Y-m-d'),
                Carbon::createFromFormat('d/m/Y', request()->enddate)->format('Y-m-d'),
            ]);
        }

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('JumlahRp', function ($row) {
                // return number_format($row->hgaji->sum(function($hgaji) {
                //     return $hgaji->dgaji->sum(function($dgaji) {
                //         return ($dgaji->Pokok + ($dgaji->Lembur));
                //     }) + $hgaji->Bonus + $hgaji->UangMakan;
                // })*1000, 0, ',', '.');
                return number_format($row->hgaji->sum(function($hgaji) {
                    return $hgaji->getTotal();
                })*1000, 0, ',', '.');
            })
            ->addColumn('JumlahKaryawan', function ($row) {
                return $row->hgaji->count();
            })
            ->addColumn('action', function ($row) {
                $edit = '<a href="' . route('gaji.edit', $row->GajiID) . '" class="btn btn-avian-primary btn-sm btn-edit"><i class="fa fa-edit"></i></a>';
                $delete = "<button data-url='" . route('gaji.destroy', $row->GajiID) . "' class='btn-action btn btn-sm btn-danger btn-delete' data-id='" . $row->GajiID . "' title='Delete'><i class='fa fa-trash'></i></button>";

                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        '.$edit.'
                        '.$delete.'
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function upload(Request $request)
    {
        //
        if ($request->has('gajiid') && $request->input('gajiid') != 0) {
            $mgaji = MGaji::findOrFail($request->input('gajiid'));
        } else {
            $mgaji = new MGaji();
            $mgaji->Tanggal = Carbon::createFromFormat('d/m/Y', $request->input('tanggal'))->format('Y-m-d');
        }
        $mgaji->save();

        foreach ($request->file('photos') ?? [] as $photo) {
            $publicHtml = config('app.public_html');
            
            $folder = $publicHtml . '/gaji/' . $mgaji->GajiID;
            // $folder = public_path('storage/gaji/' . $mgaji->GajiID);

            if (!file_exists($folder)) {
                mkdir($folder, 0775, true);
            }
            
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->move($folder, $filename);
            
            // Simpan ke DB relative path
            $hgaji = new HGaji();
            $hgaji->GajiID = $mgaji->GajiID;
            $hgaji->URL = 'gaji/' . $mgaji->GajiID . '/' . $filename;
            $hgaji->save();
        }
        // dd($request->all());
        return redirect()->route('gaji.edit', $mgaji->GajiID)->with('success', 'Gaji uploaded successfully.');
    }

    public function storeDetail(Request $request, $id)
    {
        //
        // dd($request->all());
        $hgaji = HGaji::findOrFail($id);
        $hgaji->Bonus = $request->input('bonus', 0);
        $hgaji->UangMakan = $request->input('uangmakan', 0);
        $hgaji->PegawaiID = $request->input('pegawai');
        $hgaji->save();
        DGaji::where('HeaderID', $hgaji->HeaderID)->delete();
        foreach ($request->input('pokok') ?? [] as $index => $pokok) {
            //
            $dgaji = new DGaji();
            $dgaji->HeaderID = $hgaji->HeaderID;
            $dgaji->Pokok = $pokok;
            $dgaji->PokokLembur = $request->input('gajilembur');
            $dgaji->Jam = $request->input('jam')[$index];
            $dgaji->Lembur = $request->input('lembur')[$index];
            $dgaji->Tanggal = $request->input('tanggal')[$index] != null ? (Carbon::createFromFormat('d/m/Y', $request->input('tanggal')[$index])->format('Y-m-d') ?? null) : null;
            $dgaji->save();
        }

        return redirect()->route('gaji.edit', $hgaji->GajiID)->with('success', 'Gaji details saved successfully.');
    }

    public function deleteDetail(Request $request)
    {
        //
        $headerid = $request->input('headerid');
        $hgaji = HGaji::findOrFail($headerid);
        DGaji::where('HeaderID', $hgaji->HeaderID)->delete();
        $hgaji->delete();

        return response()->json(['success' => true, 'message' => 'Gaji detail deleted successfully.']);
    }

    public function slip($id)
    {
        //
        $params['hgaji'] = HGaji::with('pegawai', 'dgaji')->findOrFail($id);
        $path = config('app.public_html') . '/' . $params['hgaji']->URL;
        // ambil orientasi
        [$w, $h] = getimagesize($path);
        $isPortrait = $h > $w;

        // load image
        $image = Image::make($path);

        // kalau portrait → rotate sementara (TIDAK disimpan!)
        if ($isPortrait) {
            $image->rotate(90);
        }

        // encode ke base64 untuk langsung dipakai di view
        $base64Image = $image->encode('data-url')->encoded;
        $params['base64Image'] = $base64Image;
        $pdf = Pdf::loadView('gaji.slip-template', $params);
        $pdf->setPaper([0, 0, 900, 1100]); // lebar A4 (595.28pt) x tinggi custom (2000pt)

        return $pdf->stream('slip_gaji_' . ($params['hgaji']->pegawai->Nama ?? '') . '.pdf');
    }
        
    public function slipAll($gajiId)
    {
        // Ambil semua data HGaji dengan GajiID yang sama
        $hgajis = HGaji::with('pegawai', 'dgaji', 'mgaji')
            ->where('GajiID', $gajiId)
            ->get();

        if ($hgajis->isEmpty()) {
            return back()->with('error', 'Data gaji tidak ditemukan.');
        }

        // Gabungkan semua view slip menjadi satu HTML panjang
        $html = '';
        foreach ($hgajis as $index => $hgaji) {
            $params['hgaji'] = $hgaji;
            $path = config('app.public_html') . '/' . $params['hgaji']->URL;
            // ambil orientasi
            [$w, $h] = getimagesize($path);
            $isPortrait = $h > $w;

            // load image
            $image = Image::make($path);

            // kalau portrait → rotate sementara (TIDAK disimpan!)
            if ($isPortrait) {
                $image->rotate(90);
            }

            // encode ke base64 untuk langsung dipakai di view
            $base64Image = $image->encode('data-url')->encoded;
            $params['base64Image'] = $base64Image;

            // Render view slip untuk setiap pegawai
            $html .= view('gaji.slip-template', $params)->render();

            // Tambahkan pemisah halaman di antara slip
            if ($index < $hgajis->count() - 1) {
                // $html .= '<div style="page-break-after: always;"></div>';
            }
        }

        // Generate satu PDF dari semua halaman slip
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper([0, 0, 900, 1100]); // ukuran sama seperti slip()

        $fileName = 'slip_gaji_semua_' . $gajiId . '.pdf';

        // Langsung tampilkan / download
        return $pdf->stream($fileName);
        // Jika ingin langsung download, ganti dengan:
        // return $pdf->download($fileName);
    }
    
    public function rotateImage(Request $request)
    {
        //
        $hgaji = HGaji::findOrFail($request->input('headerid'));
        $path = config('app.public_html') . '/' . $hgaji->URL;

        // load image
        $image = Image::make($path);

        // rotate 90 derajat
        $image->rotate(90);

        // simpan kembali
        $image->save($path);

        return response()->json(['success' => true, 'message' => 'Image rotated successfully.']);
    }
}
