<?php

namespace App\Http\Controllers;

use App\Models\MCustomer;
use App\Models\MCustomerCategory;
use Illuminate\Http\Request;

class CustomerController extends Controller
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
        return view('customer.index', $params);
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
        $customer = new MCustomer();
        $customer->Nama = $request->nama;
        $customer->Alamat = $request->alamat;
        $customer->Telp = $request->telp;
        $customer->JatuhTempo = $request->JatuhTempo;
        $customer->JatuhTempoSatuan = $request->JatuhTempoSatuan;
        $customer->PriceCategory = $request->pricecategory;
        $customer->IsEkspedisi = $request->isEkspedisi ? 1 : 0;
        $customer->IsKonsinyasi = $request->isKonsinyasi ? 1 : 0;
        $customer->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer created successfully'
        ]);

        // return redirect()
        //     ->route('customer.index')
        //     ->with('result', (object)[
        //         'type' => 'success',
        //         'message' => 'Customer created successfully.',
        //     ]);
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
        $customer = MCustomer::findOrFail($id);
        $customer->Nama = $request->nama;
        $customer->Alamat = $request->alamat;
        $customer->Telp = $request->telp;
        $customer->JatuhTempo = $request->JatuhTempo;
        $customer->JatuhTempoSatuan = $request->JatuhTempoSatuan;
        $customer->PriceCategory = $request->pricecategory;
        $customer->IsEkspedisi = $request->isEkspedisi ? 1 : 0;
        $customer->IsKonsinyasi = $request->isKonsinyasi ? 1 : 0;
        $customer->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully'
        ]);

        // return redirect()
        //     ->route('customer.index')
        //     ->with('result', (object)[
        //         'type' => 'success',
        //         'message' => 'Customer updated successfully.',
        //     ]);
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
        $data = MCustomer::with('category')->orderBy('CustomerID');

        if ($request->has('pricecategoryfilter') && $request->pricecategoryfilter != 0) {
            $data->where('PriceCategory', $request->pricecategoryfilter);
        }

        if ($request->has('type') && $request->type != 0) {
            $data->where('IsEkspedisi', $request->type == 2 ? 1 : 0);
        }
        
        return datatables()->of($data)
            ->addColumn('TipeNice', function ($row) {
                if ($row->IsEkspedisi == 1) {
                    return '<badge class="badge badge-dark">Ekspedisi</badge>';
                } else {
                    return '<badge class="badge badge-info">Customer</badge>';
                }
            })
            ->addColumn('CategoryNice', function ($row) {
                $output = '';
                if ($row->category && $row->IsEkspedisi != 1) {
                    if ($row->PriceCategory == 1) {
                        $output .= '<badge class="badge badge-success">Konsumen</badge>';
                    } else if ($row->PriceCategory == 2) {
                        $output .= '<badge class="badge badge-danger">Supplier-1</badge>';
                    } else if ($row->PriceCategory == 3) {
                        $output .= '<badge class="badge badge-warning">Supplier-2</badge>';
                    } else if ($row->PriceCategory == 4) {
                        $output .= '<badge class="badge badge-secondary">Distributor</badge>';
                    }
                } else if ($row->IsEkspedisi == 1) {
                    $output .= '<badge class="badge badge-dark">Ekspedisi</badge>';
                } else {
                    $output .= '-';
                }
                if ($row->IsKonsinyasi == 1) {
                    $output .= ' <badge class="badge badge-primary">Konsinyasi</badge>';
                }
                return $output;
            })
            ->addColumn('action', function ($row) {
                $edit = '<button class="btn btn-avian-primary btn-sm btn-edit"><i class="fa fa-edit"></i></button>';
                $delete = "<button data-url='" . route('customer.destroy', $row->CustomerID) . "' class='btn-action btn btn-sm btn-danger btn-delete' data-id='" . $row->CustomerID . "' title='Delete'><i class='fa fa-trash'></i></button>";

                return '<div class="btn-group" role="group" aria-label="Action Buttons">
                    ' . $edit . '
                    ' . $delete . '
                </div>';
            })
            ->rawColumns(['action', 'TipeNice', 'CategoryNice'])
            ->make(true);
    }
}
