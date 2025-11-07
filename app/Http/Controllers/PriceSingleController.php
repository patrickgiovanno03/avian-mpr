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

        switch ($request->input('weight')) {
            case '250gr':
                $pricesingle->Price250gr = $request->input('price');
                break;
            case '300gr':
                $pricesingle->Price300gr = $request->input('price');
                break;
            case '500gr':
                $pricesingle->Price500gr = $request->input('price');
                break;
            case 'Tabung':
                $pricesingle->PriceTabung = $request->input('price');
                break;
            case '250grGrosir':
                $pricesingle->Price250grGrosir = $request->input('price');
                break;
            case '300grGrosir':
                $pricesingle->Price300grGrosir = $request->input('price');
                break;
            case '500grGrosir':
                $pricesingle->Price500grGrosir = $request->input('price');
                break;
            case 'TabungGrosir':
                $pricesingle->PriceTabungGrosir = $request->input('price');
                break;
            case 'Kiloan':
                $pricesingle->PriceKiloan = $request->input('price');
                break;
        }
        $pricesingle->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Price Single updated successfully.',
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
    }

    public function datatable(Request $request)
    {
        //
        $data = MPriceSingle::where('CustomerCategory', $request->customercategory)->get();

        return datatables()->of($data)
            ->addColumn('250grInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="250gr" value="' . number_format($row->Price250gr, 0, ',', '.') . '">';
            })
            ->addColumn('300grInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="300gr" value="' . number_format($row->Price300gr, 0, ',', '.') . '">';
            })
            ->addColumn('500grInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="500gr" value="' . number_format($row->Price500gr, 0, ',', '.') . '">';
            })
            ->addColumn('TabungInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="Tabung" value="' . number_format($row->PriceTabung, 0, ',', '.') . '">';
            })
            ->addColumn('250grGrosirInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="250grGrosir" value="' . number_format($row->Price250grGrosir, 0, ',', '.') . '">';
            })
            ->addColumn('300grGrosirInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="300grGrosir" value="' . number_format($row->Price300grGrosir, 0, ',', '.') . '">';
            })
            ->addColumn('500grGrosirInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="500grGrosir" value="' . number_format($row->Price500grGrosir, 0, ',', '.') . '">';
            })
            ->addColumn('TabungGrosirInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="TabungGrosir" value="' . number_format($row->PriceTabungGrosir, 0, ',', '.') . '">';
            })
            ->addColumn('KiloanInput', function ($row) {
                return '<input type="text" class="numeric form-control form-control-sm price-input" data-priceid="' . $row->PriceID . '" data-weight="Kiloan" value="' . number_format($row->PriceKiloan, 0, ',', '.') . '">';
            })
            // ->addColumn('action', function ($row) {
            //     $edit = '<button class="btn btn-avian-secondary btn-sm btn-edit" data-data=\'' . json_encode($row) . '\'><i class="fa fa-edit"></i></button>';
            //     $delete = "<button data-url='" . route('single.destroy', $row->SingleID) . "' class='btn-action btn btn-sm btn-danger btn-delete' data-id='" . $row->SingleID . "' title='Delete'><i class='fa fa-trash'></i></button>";

            //     return '<div class="btn-group" role="group" aria-label="Action Buttons">
            //         ' . $edit . '
            //         ' . $delete . '
            //     </div>';
            // })
            ->rawColumns(['action', '250grInput', '300grInput', '500grInput', 'TabungInput', '250grGrosirInput', '300grGrosirInput', '500grGrosirInput', 'TabungGrosirInput', 'KiloanInput'])
            ->make(true);
    }
}
