<?php

namespace App\Http\Controllers;

use App\Models\MCustomerCategory;
use App\Models\MPrice;
use App\Models\MProduct;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $params['categories'] = MCustomerCategory::get();
        return view('price.index', $params);
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
        $price = MPrice::firstOrNew(['ProductID' => $id]);
        $price->ProductID = $id;
        $price->PriceKonsumen = (int) str_replace('.', '', $request->input('pricekonsumen', 0));
        $price->PriceSup1 = (int) str_replace('.', '', $request->input('pricesup1', 0));
        $price->PriceSup2 = (int) str_replace('.', '', $request->input('pricesup2', 0));
        $price->PriceDistributor = (int) str_replace('.', '', $request->input('pricedistributor', 0));
        $price->save();
        return redirect()->route('price.index')->with('success', 'Prices updated successfully.');
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
    
    public function datatable(Request $request)
    {
        //
        $data = MProduct::with('price')->orderBy('ProductID')->get();
        $categories = MCustomerCategory::get();
        
        $datatable = datatables()->of($data)
            // ->addColumn('ProductName', function ($row) {
            //     return $row->product ? $row->product->Name : '';
            // })
            ->addColumn('PriceKonsumen', function ($row) use ($categories) {
                return $row->price != null ? $row->price->PriceKonsumen : '0';
            })
            ->addColumn('PriceSup1', function ($row) use ($categories) {
                return $row->price != null ? $row->price->PriceSup1 : '0';
            })
            ->addColumn('PriceSup2', function ($row) use ($categories) {
                return $row->price != null ? $row->price->PriceSup2 : '0';
            })
            ->addColumn('PriceDistributor', function ($row) use ($categories) {
                return $row->price != null ? $row->price->PriceDistributor : '0';
            })
            ->addColumn('PriceKonsumen', function ($row) use ($categories) {
                return $row->price != null ? $row->price->PriceKonsumen : '0';
            })
            ->addColumn('action', function ($row) {
                $edit = '<button class="btn btn-avian-primary btn-sm btn-edit"><i class="fa fa-edit"></i></button>';

                return '<div class="btn-group" role="group" aria-label="Action Buttons">
                    ' . $edit . '
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
        
        return $datatable;
    }
}
