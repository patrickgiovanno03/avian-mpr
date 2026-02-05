<?php

namespace App\Http\Controllers;

use App\Models\MPrice;
use App\Models\MProduct;
use App\Models\MSingle;
use Illuminate\Http\Request;

class SingleController extends Controller
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
        return view('single.index', $params);
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
        $single = MSingle::find($id);
        if (!$single) {
            return redirect()
                ->route('single.index')
                ->with('result', (object)[
                    'type' => 'error',
                    'message' => 'Single item not found.',
                ]);
        }
        $single->Kode = $request->input('kode');
        $single->Category = $request->input('category');
        $single->save();
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
        $data = MSingle::orderBy('SingleID')->get();
        
        return datatables()->of($data)
            ->addColumn('Kode', function ($row) {
                return '<input type="text" class="form-control form-control-sm kode-input" data-id="' . $row->SingleID . '" value="' . $row->Kode . '"/>';
            })
            ->addColumn('Category', function ($row) {
                return '<select class="form-control form-control-sm select2 category-input" data-id="' . $row->SingleID . '">
                    <option value="A"' . ($row->Category == 'A' ? ' selected' : '') . '>A</option>
                    <option value="B"' . ($row->Category == 'B' ? ' selected' : '') . '>B</option>
                    <option value="C"' . ($row->Category == 'C' ? ' selected' : '') . '>C</option>
                    <option value="D"' . ($row->Category == 'D' ? ' selected' : '') . '>D</option>
                    <option value="E"' . ($row->Category == 'E' ? ' selected' : '') . '>E</option>
                    <option value="F"' . ($row->Category == 'F' ? ' selected' : '') . '>F</option>
                </select>';
            })
            ->rawColumns(['Category', 'Kode'])
            ->make(true);
    }

    public function sync(Request $request)
    {
        $singles = MSingle::with('pricesingle')->get();

        foreach ($singles as $single) {
            foreach ($single->pricesingle as $pricesingle) {
                // assign customer category
                switch ($pricesingle->CustomerCategory) {
                    case '1':
                        $categoryName = 'PriceKonsumen';
                        break;
                    case '2':
                        $categoryName = 'PriceSup1';
                        break;
                    case '3':
                        $categoryName = 'PriceSup2';
                        break;
                    case '4':
                        $categoryName = 'PriceDistributor';
                        break;
                }

                // 250gr
                $nama = 'Merrygold ' . $single->Name . ' 250gr';
                $price = $pricesingle['250gr'];
                $kode = $single->Kode . 'K';
                $satuan = 'toples';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 300gr
                $nama = 'Merrygold ' . $single->Name . ' 300gr';
                $price = $pricesingle['300gr'];
                $kode = $single->Kode . 'S';
                $satuan = 'toples';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 500gr
                $nama = 'Merrygold ' . $single->Name . ' 500gr';
                $price = $pricesingle['500gr'];
                $kode = $single->Kode . 'B';
                $satuan = 'toples';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 300ml
                $nama = 'Merrygold ' . $single->Name . ' Tabung 300ml';
                $price = $pricesingle['300ml'];
                $kode = $single->Kode . 'TM';
                $satuan = 'toples';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 400ml
                $nama = 'Merrygold ' . $single->Name . ' Tabung 400ml';
                $price = $pricesingle['400ml'];
                $kode = $single->Kode . 'TK';
                $satuan = 'toples';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 700ml
                $nama = 'Merrygold ' . $single->Name . ' Tabung 700ml';
                $price = $pricesingle['700ml'];
                $kode = $single->Kode . 'TB';
                $satuan = 'toples';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 250gr Grosir
                $nama = 'Merrygold ' . $single->Name . ' isi 12x250gr';
                $price = $pricesingle['250grG'];
                $kode = $single->Kode . 'K12';
                $satuan = 'dos';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 300gr Grosir
                $nama = 'Merrygold ' . $single->Name . ' isi 12x300gr';
                $price = $pricesingle['300grG'];
                $kode = $single->Kode . 'S12';
                $satuan = 'dos';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 500gr Grosir
                $nama = 'Merrygold ' . $single->Name . ' isi 12x500gr';
                $price = $pricesingle['500grG'];
                $kode = $single->Kode . 'B12';
                $satuan = 'dos';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 300ml Grosir
                $nama = 'Merrygold ' . $single->Name . ' Tabung isi 12x300ml';
                $price = $pricesingle['300mlG'];
                $kode = $single->Kode . 'TM12';
                $satuan = 'dos';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 400ml Grosir
                $nama = 'Merrygold ' . $single->Name . ' Tabung isi 12x400ml';
                $price = $pricesingle['400mlG'];
                $kode = $single->Kode . 'TK12';
                $satuan = 'dos';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // 700ml Grosir
                $nama = 'Merrygold ' . $single->Name . ' Tabung isi 12x700ml';
                $price = $pricesingle['700mlG'];
                $kode = $single->Kode . 'TB12';
                $satuan = 'dos';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);

                // Kiloan
                $nama = 'Merrygold ' . $single->Name . ' Kiloan';
                $price = $pricesingle->PriceKiloan;
                $kode = $single->Kode . 'KG';
                $satuan = 'kg';
                $this->syncInsert($nama, $price, $categoryName, $kode, $satuan);
            }
        }
    }

    public function syncInsert($nama, $price, $categoryName, $kode = '', $satuan = '')
    {
        $mproduct = MProduct::where('Name', $nama)->first();
        if ($mproduct) {
            $mprice = MPrice::where('ProductID', $mproduct->ProductID)->first();
            if ($mprice) {
                $mprice->{$categoryName} = $price;
                $mprice->save();
            } else {
                MPrice::create([
                    'ProductID' => $mproduct->ProductID,
                    $categoryName => $price,
                ]);
            }
        } else {
            $mproduct = MProduct::create([
                'Name' => $nama,
                'Satuan' => '',
                'Kode' => '',
            ]);
            MPrice::create([
                'ProductID' => $mproduct->ProductID,
                $categoryName => $price,
            ]);
        }
        $mproduct->Kode = $kode;
        $mproduct->Satuan = $satuan;
        $mproduct->save();
    }
}
