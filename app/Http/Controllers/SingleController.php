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
                $price = $pricesingle->Price250gr;
                $kode = $single->Kode . 'K';
                $this->syncInsert($nama, $price, $categoryName, $kode);

                // 300gr
                $nama = 'Merrygold ' . $single->Name . ' 300gr';
                $price = $pricesingle->Price300gr;
                $this->syncInsert($nama, $price, $categoryName);

                // 500gr
                $nama = 'Merrygold ' . $single->Name . ' 500gr';
                $price = $pricesingle->Price500gr;
                $kode = $single->Kode . 'B';
                $this->syncInsert($nama, $price, $categoryName, $kode);

                // Tabung
                $nama = 'Merrygold ' . $single->Name . ' Tabung 450gr';
                $price = $pricesingle->PriceTabung;
                $this->syncInsert($nama, $price, $categoryName);

                // 250grGrosir
                $nama = 'Merrygold ' . $single->Name . ' isi 12x250gr';
                $price = $pricesingle->Price250grGrosir;
                $kode = $single->Kode . 'K12'; 
                $this->syncInsert($nama, $price, $categoryName, $kode);

                // 300grGrosir
                $nama = 'Merrygold ' . $single->Name . ' isi 12x300gr';
                $price = $pricesingle->Price300grGrosir;
                $this->syncInsert($nama, $price, $categoryName);

                // 500grGrosir
                $nama = 'Merrygold ' . $single->Name . ' isi 12x500gr';
                $price = $pricesingle->Price500grGrosir;
                $kode = $single->Kode . 'B12';
                $this->syncInsert($nama, $price, $categoryName, $kode);

                // TabungGrosir
                $nama = 'Merrygold ' . $single->Name . ' Tabung isi 12x450gr';
                $price = $pricesingle->PriceTabungGrosir;
                $this->syncInsert($nama, $price, $categoryName);

                // Kiloan
                $nama = 'Merrygold ' . $single->Name . ' Kiloan';
                $price = $pricesingle->PriceKiloan;
                $this->syncInsert($nama, $price, $categoryName);
            }
        }
    }

    public function syncInsert($nama, $price, $categoryName, $kode = '')
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
        $mproduct->save();
    }
}
