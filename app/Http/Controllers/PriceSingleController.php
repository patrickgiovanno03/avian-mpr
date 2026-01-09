<?php

namespace App\Http\Controllers;

use App\Models\MPriceSingle;
use App\Models\MSingle;
use Illuminate\Http\Request;

class PriceSingleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $params['singles'] = MSingle::get();
        $params['singlecategories'] = ['A', 'B', 'C', 'D', 'E', 'F'];
        return view('pricesingle.index', $params);
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
        $pricesingle = MPriceSingle::find($id);
        if (!$pricesingle) {
            return redirect()
                ->route('pricesingle.index')
                ->with('result', (object)[
                    'type' => 'danger',
                    'message' => 'Price Single not found.',
                ]);
        }

        $pricesingle[$request->input('weight')] = $request->input('price');
        $pricesingle->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Price Single updated successfully'
        ]);
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Price Single updated successfully.',
        // ]);
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
        $data = MPriceSingle::where('CustomerCategory', $request->customercategory)->get();

        return datatables()->of($data)
            ->addColumn('250grInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="250gr" value="' . number_format($row['250gr'], 0, ',', '.') . '">';
            })
            ->addColumn('300grInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="300gr" value="' . number_format($row['300gr'], 0, ',', '.') . '">';
            })
            ->addColumn('500grInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="500gr" value="' . number_format($row['500gr'], 0, ',', '.') . '">';
            })
            ->addColumn('300mlInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="300ml" value="' . number_format($row['300ml'], 0, ',', '.') . '">';
            })
            ->addColumn('400mlInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="400ml" value="' . number_format($row['400ml'], 0, ',', '.') . '">';
            })
            ->addColumn('700mlInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="700ml" value="' . number_format($row['700ml'], 0, ',', '.') . '">';
            })
            ->addColumn('250grGInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="250grG" value="' . number_format($row['250grG'], 0, ',', '.') . '">';
            })
            ->addColumn('300grGInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="300grG" value="' . number_format($row['300grG'], 0, ',', '.') . '">';
            })
            ->addColumn('500grGInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="500grG" value="' . number_format($row['500grG'], 0, ',', '.') . '">';
            })
            ->addColumn('300mlGInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="300mlG" value="' . number_format($row['300mlG'], 0, ',', '.') . '">';
            })
            ->addColumn('400mlGInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="400mlG" value="' . number_format($row['400mlG'], 0, ',', '.') . '">';
            })
            ->addColumn('700mlGInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="700mlG" value="' . number_format($row['700mlG'], 0, ',', '.') . '">';
            })
            ->addColumn('KiloanInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="Kiloan" value="' . number_format($row->Kiloan, 0, ',', '.') . '">';
            })
            // ->addColumn('action', function ($row) {
            //     $edit = '<button class="btn btn-avian-secondary btn-sm btn-edit" data-data=\'' . json_encode($row) . '\'><i class="fa fa-edit"></i></button>';
            //     $delete = "<button data-url='" . route('single.destroy', $row->SingleID) . "' class='btn-action btn btn-sm btn-danger btn-delete' data-id='" . $row->SingleID . "' title='Delete'><i class='fa fa-trash'></i></button>";

            //     return '<div class="btn-group" role="group" aria-label="Action Buttons">
            //         ' . $edit . '
            //         ' . $delete . '
            //     </div>';
            // })
            ->rawColumns(['action', 
            '250grInput', 
            '300grInput', 
            '500grInput',
            '300mlInput',
            '400mlInput',
            '700mlInput',
            '250grGInput', 
            '300grGInput', 
            '500grGInput',
            '300mlGInput',
            '400mlGInput',
            '700mlGInput',
            'KiloanInput'])
            ->make(true);
    }
}
