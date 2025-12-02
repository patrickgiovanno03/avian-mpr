<?php

namespace App\Http\Controllers;

use App\Models\MProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $params['satuans'] = MProduct::select('Satuan')->distinct()->get()->pluck('Satuan');
        return view('product.index', $params);
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
        $product = new MProduct();
        $product->Name = $request->input('name');
        $product->Satuan = $request->input('satuan');
        $product->Kode = $request->input('kode');
        $product->save();

        return redirect()
            ->route('product.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Product created successfully.',
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
        $product = MProduct::findOrFail($id);
        $product->Name = $request->input('name');
        $product->Satuan = $request->input('satuan');
        $product->Kode = $request->input('kode');
        $product->save();

        return redirect()
            ->route('product.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Product updated successfully.',
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
        $product = MProduct::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ]);
        }
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully.',
        ]);
    }

    public function datatable(Request $request)
    {
        //
        $data = MProduct::orderBy('ProductID')->get();
        
        return datatables()->of($data)
            ->addColumn('TipeNice', function ($row) {
                if ($row->Tipe == 1) {
                    return '<badge class="badge" style="background-color: #F6CEE2;">Paket</badge>';
                } else if ($row->Tipe == 2) {
                    return '<badge class="badge" style="background-color: #C3E9B7;">Single</badge>';
                } else {
                    return '<badge class="badge" style="background-color: #F6F5BE;">Lainnya</badge>';
                }
            })
            ->addColumn('action', function ($row) {
                $edit = '<button class="btn btn-avian-primary btn-sm btn-edit"><i class="fa fa-edit"></i></button>';
                $delete = "<button data-url='" . route('product.destroy', $row->ProductID) . "' class='btn-action btn btn-sm btn-danger btn-delete' data-id='" . $row->ItemID . "' title='Delete'><i class='fa fa-trash'></i></button>";

                return '<div class="btn-group" role="group" aria-label="Action Buttons">
                    ' . $edit . '
                    ' . $delete . '
                </div>';
            })
            ->rawColumns(['action', 'TipeNice'])
            ->make(true);
    }
}
