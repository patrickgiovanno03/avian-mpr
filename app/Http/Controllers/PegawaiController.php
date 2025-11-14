<?php

namespace App\Http\Controllers;

use App\Models\MPegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('gaji.pegawai.index');
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
        $pegawai = new MPegawai();
        $pegawai->Nama = $request->input('name');
        $pegawai->GajiPokok = str_replace(',', '.', str_replace('.', '', $request->input('gajipokok')));
        $pegawai->GajiLembur = str_replace(',', '.', str_replace('.', '', $request->input('gajilembur')));
        $pegawai->save();

        return redirect()
            ->route('pegawai.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Pegawai created successfully.',
            ]);
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
        $pegawai = MPegawai::findOrFail($id);
        $pegawai->Nama = $request->input('name');
        $pegawai->GajiPokok = str_replace(',', '.', str_replace('.', '', $request->input('gajipokok')));
        $pegawai->GajiLembur = str_replace(',', '.', str_replace('.', '', $request->input('gajilembur')));
        $pegawai->save();

        return redirect()
            ->route('pegawai.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Pegawai updated successfully.',
            ]);
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
        $pegawai = MPegawai::findOrFail($id);
        $pegawai->IsDel = 1;
        $pegawai->save();

        return redirect()
            ->route('pegawai.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Pegawai deleted successfully.',
            ]);
    }

    public function datatable(Request $request)
    {
        $data = MPegawai::where('IsDel', 0)->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $edit = '<button class="btn btn-avian-primary btn-sm btn-edit"><i class="fa fa-edit"></i></button>';
                $delete = "<button data-url='" . route('pegawai.destroy', $row->PegawaiID) . "' class='btn-action btn btn-sm btn-danger btn-delete' data-id='" . $row->ItemID . "' title='Delete'><i class='fa fa-trash'></i></button>";

                return '<div class="btn-group" role="group" aria-label="Action Buttons">
                    ' . $edit . '
                    ' . $delete . '
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getPegawai(Request $request)
    {
        $pegawais = MPegawai::query();

        $pegawais = $pegawais->where(function ($query) use ($request) {
            $query->where('Nama', 'like', '%' . $request->search . '%');
            })
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'pegawais' => $pegawais,
        ]);
    }
}
