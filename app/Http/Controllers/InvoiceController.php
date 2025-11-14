<?php

namespace App\Http\Controllers;

use App\Models\DInvoice;
use App\Models\HInvoice;
use App\Models\MCustomer;
use App\Models\MProduct;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $params['customers'] = HInvoice::select('NamaCustomer')->whereNotNull('NamaCustomer')
            ->distinct()
            ->pluck('NamaCustomer');
        return view('invoice.index', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if ($request->id) {
            $params['invoice'] = HInvoice::find($request->id);
            $params['isDuplicate'] = true;
        } else {
            $params['invoice'] = null;
        }
        $params['customers'] = MCustomer::distinct('Nama')->where('IsEkspedisi', 0)->get()->pluck('Nama');
        $params['ekspedisi'] = MCustomer::distinct('Nama')->where('IsEkspedisi', 1)->get()->pluck('Nama');
        return view('invoice.form', $params);
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
        $invoice = new HInvoice();
        if ($request->IsInvoice != null) {
            $invoice->InvoiceNo = $request->input('invoiceno');
        } else {
            $invoice->InvoiceNo = null;
        }
        if ($request->IsSJ != null) {
            $invoice->SJNo = $request->input('sjno');
        } else {
            $invoice->SJNo = null;
        }
        $invoice->Kode = $request->input('kode');
        $invoice->NamaCustomer = $request->input('namacustomer');
        $invoice->AlamatCustomer = $request->input('alamatcustomer');
        $invoice->TelpCustomer = $request->input('telpcustomer');
        $invoice->PriceCategory = $request->input('categorycustomer');

        $customer = MCustomer::where('Nama', $request->input('namacustomer'))->first();
        if (!$customer) {
            $newCustomer = new MCustomer();
            $newCustomer->Nama = $request->input('namacustomer');
            $newCustomer->Alamat = $request->input('alamatcustomer');
            $newCustomer->Telp = $request->input('telpcustomer');
            $newCustomer->PriceCategory = $request->input('categorycustomer');
            $newCustomer->save();
        }
        $invoice->InvoiceDate = Carbon::createFromFormat('d/m/Y', $request->input('invoicedate'))->format('Y-m-d');
        $invoice->SJDate = Carbon::createFromFormat('d/m/Y', $request->input('sjdate'))->format('Y-m-d');

        $invoice->Notes = $request->input('Notes');
        $invoice->NotesSJ = $request->input('NotesSJ');

        $invoice->JatuhTempo = $request->input('JatuhTempo');
        $invoice->JatuhTempoSatuan = $request->input('JatuhTempoSatuan');
        
        $invoice->NamaEkspedisi = $request->input('NamaEkspedisi');
        $invoice->AlamatEkspedisi = $request->input('AlamatEkspedisi');
        $invoice->TelpEkspedisi = $request->input('TelpEkspedisi');
        if ($request->input('NamaEkspedisi')) {
            $ekspedisi = MCustomer::where('Nama', $request->input('NamaEkspedisi'))->first();
            if (!$ekspedisi) {
                $newEkspedisi = new MCustomer();
                $newEkspedisi->Nama = $request->input('NamaEkspedisi');
                $newEkspedisi->Alamat = $request->input('AlamatEkspedisi');
                $newEkspedisi->Telp = $request->input('TelpEkspedisi');
                $newEkspedisi->IsEkspedisi = 1;
                $newEkspedisi->save();
            }
        }
        $invoice->TTD = $request->input('TTD');
        $invoice->Discount = str_replace('.', '', $request->input('Discount') ?? '0');

        $invoice->IsKonsinyasi = $request->input('IsKonsinyasi') ? 1 : 0;
        $invoice->IsEkspedisi = $request->input('IsEkspedisi') ? 1 : 0;
        $invoice->IsKopSurat = $request->input('IsKopSurat') ? 1 : 0;
        $invoice->IsLarge = $request->input('IsLarge') ? 1 : 0;
        $invoice->IsDiscount = $request->input('IsDiscount') ? 1 : 0;
        $invoice->IsSJCustomer = $request->input('IsSJCustomer') ? 1 : 0;
        $invoice->save();

        $oldDetailID = 0;
        foreach ($request->input('product') ?? [] as $index => $productName) {
            if ($request->input('type')[$index] == 'delete') {
                if ($request->input('detailid')[$index]) {
                    $dinvoice = DInvoice::find($request->input('detailid')[$index]);
                    if ($dinvoice)
                        $dinvoice->delete();
                }
                continue;
            }
            if ($productName) {
                if ($request->input('type')[$index] == 'update' && $request->input('detailid')[$index]) {
                    $dinvoice = DInvoice::find($request->input('detailid')[$index]);
                } else {
                    if ($request->input('issj')[$index] == 1 && $oldDetailID != 0) {
                        // check if previous detail was SJ, if yes, update that instead of creating new
                        $dinvoice = DInvoice::find($oldDetailID);
                    } else {
                        $dinvoice = new DInvoice();
                    }
                }
                $dinvoice->FormID = $invoice->FormID;
                if ($request->input('issj')[$index] == 1) {
                    $dinvoice->IsSJ = 1;
                    $dinvoice->NamaSJ = $productName;
                    $dinvoice->SatuanSJ = $request->input('unit')[$index];
                    $dinvoice->QtySJ = $request->input('quantity')[$index] != null ? str_replace('.', '', $request->input('quantity')[$index]) : 0;
                } else { // detail inv
                    $dinvoice->IsSJ = 0;
                    $dinvoice->Nama = $productName;
                    $dinvoice->Harga = $request->input('price')[$index] != null ? str_replace('.', '', $request->input('price')[$index]) : 0;
                    $dinvoice->Satuan = $request->input('unit')[$index];
                    $dinvoice->Qty = $request->input('quantity')[$index] != null ? str_replace('.', '', $request->input('quantity')[$index]) : 0;
                    $dinvoice->DosLuar = $request->input('dosluar')[$index] != null ? str_replace('.', '', $request->input('dosluar')[$index]) : 0;
                    $dinvoice->Isi = $request->input('isi')[$index] != null ? str_replace('.', '', $request->input('isi')[$index]) : 0;
                }
                $dinvoice->IsHidden = $request->input('hidden')[$index] == 1 ? 1 : 0;
                $dinvoice->save();

                $oldDetailID = $dinvoice->DetailID;
            }
        }
        if ($request->toPDF == '1') {
            return redirect()
                ->route('invoice.previewdynamic', $invoice->FormID);
        }

        return redirect()
            ->route('invoice.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Invoice berhasil diupdate.',
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
        $params['invoice'] = HInvoice::find($id);
        $params['customers'] = MCustomer::distinct('Nama')->where('IsEkspedisi', 0)->get()->pluck('Nama');
        $params['ekspedisi'] = MCustomer::distinct('Nama')->where('IsEkspedisi', 1)->get()->pluck('Nama');

        return view('invoice.form', $params);
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
        $invoice = HInvoice::find($id);
        if ($request->IsInvoice != null) {
            $invoice->InvoiceNo = $request->input('invoiceno');
        } else {
            $invoice->InvoiceNo = null;
        }
        if ($request->IsSJ != null) {
            $invoice->SJNo = $request->input('sjno');
        } else {
            $invoice->SJNo = null;
        }
        $invoice->Kode = $request->input('kode');
        $invoice->NamaCustomer = $request->input('namacustomer');
        $invoice->AlamatCustomer = $request->input('alamatcustomer');
        $invoice->TelpCustomer = $request->input('telpcustomer');
        $invoice->PriceCategory = $request->input('categorycustomer');
        $invoice->InvoiceDate = Carbon::createFromFormat('d/m/Y', $request->input('invoicedate'))->format('Y-m-d');
        $invoice->SJDate = Carbon::createFromFormat('d/m/Y', $request->input('sjdate'))->format('Y-m-d');

        $invoice->Notes = $request->input('Notes');
        $invoice->NotesSJ = $request->input('NotesSJ');

        $invoice->JatuhTempo = $request->input('JatuhTempo');
        $invoice->JatuhTempoSatuan = $request->input('JatuhTempoSatuan');

        $invoice->NamaEkspedisi = $request->input('NamaEkspedisi');
        $invoice->AlamatEkspedisi = $request->input('AlamatEkspedisi');
        $invoice->TelpEkspedisi = $request->input('TelpEkspedisi');
        if ($request->input('NamaEkspedisi')) {
            $ekspedisi = MCustomer::where('Nama', $request->input('NamaEkspedisi'))->first();
            if (!$ekspedisi) {
                $newEkspedisi = new MCustomer();
                $newEkspedisi->Nama = $request->input('NamaEkspedisi');
                $newEkspedisi->Alamat = $request->input('AlamatEkspedisi');
                $newEkspedisi->Telp = $request->input('TelpEkspedisi');
                $newEkspedisi->IsEkspedisi = 1;
                $newEkspedisi->save();
            }
        }
        $invoice->TTD = $request->input('TTD');
        $invoice->Discount = str_replace('.', '', $request->input('Discount') ?? '0');

        $invoice->IsKonsinyasi = $request->input('IsKonsinyasi') ? 1 : 0;
        $invoice->IsEkspedisi = $request->input('IsEkspedisi') ? 1 : 0;
        $invoice->IsKopSurat = $request->input('IsKopSurat') ? 1 : 0;
        $invoice->IsLarge = $request->input('IsLarge') ? 1 : 0;
        $invoice->IsDiscount = $request->input('IsDiscount') ? 1 : 0;
        $invoice->IsSJCustomer = $request->input('IsSJCustomer') ? 1 : 0;
        $invoice->save();

        $oldDetailID = 0;
        foreach ($request->input('product') ?? [] as $index => $productName) {
            if ($request->input('type')[$index] == 'delete') {
                if ($request->input('detailid')[$index]) {
                    $dinvoice = DInvoice::find($request->input('detailid')[$index]);
                    if ($dinvoice)
                        $dinvoice->delete();
                }
                continue;
            }
            if ($productName) {
                if ($request->input('type')[$index] == 'update' && $request->input('detailid')[$index]) {
                    $dinvoice = DInvoice::find($request->input('detailid')[$index]);
                } else {
                    if ($request->input('issj')[$index] == 1 && $oldDetailID != 0) {
                        // check if previous detail was SJ, if yes, update that instead of creating new
                        $dinvoice = DInvoice::find($oldDetailID);
                    } else {
                        $dinvoice = new DInvoice();
                    }
                }
                $dinvoice->FormID = $invoice->FormID;
                if ($request->input('issj')[$index] == 1) {
                    $dinvoice->IsSJ = 1;
                    $dinvoice->NamaSJ = $productName;
                    $dinvoice->SatuanSJ = $request->input('unit')[$index];
                    $dinvoice->QtySJ = $request->input('quantity')[$index] != null ? str_replace('.', '', $request->input('quantity')[$index]) : 0;
                } else { // detail inv
                    $dinvoice->IsSJ = 0;
                    $dinvoice->Nama = $productName;
                    $dinvoice->Harga = $request->input('price')[$index] != null ? str_replace('.', '', $request->input('price')[$index]) : 0;
                    $dinvoice->Satuan = $request->input('unit')[$index];
                    $dinvoice->Qty = $request->input('quantity')[$index] != null ? str_replace('.', '', $request->input('quantity')[$index]) : 0;
                    $dinvoice->DosLuar = $request->input('dosluar')[$index] != null ? str_replace('.', '', $request->input('dosluar')[$index]) : 0;
                    $dinvoice->Isi = $request->input('isi')[$index] != null ? str_replace('.', '', $request->input('isi')[$index]) : 0;
                }
                $dinvoice->IsHidden = $request->input('hidden')[$index] == 1 ? 1 : 0;
                $dinvoice->save();

                $oldDetailID = $dinvoice->DetailID;
            }
        }

        return redirect()
            ->route('invoice.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Invoice berhasil diupdate.',
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
        $invoice = HInvoice::findOrFail($id);
        $invoice->details()->delete();
        $invoice->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice deleted successfully.',
        ]);
    }

    public function datatable(Request $request)
    {
        //
        $data = HInvoice::query();

        if ($request->has('customer') && $request->customer != '0') {
            $data->where('NamaCustomer', $request->customer);
        }

        if (request()->has('startdate') && request()->has('enddate') && request()->startdate != null && request()->enddate != null) {
            $data = $data->whereBetween('InvoiceDate', [
                Carbon::createFromFormat('d/m/Y', request()->startdate)->format('Y-m-d'),
                Carbon::createFromFormat('d/m/Y', request()->enddate)->format('Y-m-d'),
            ]);
        }

        $data = $data->orderBy('FormID', 'desc')->get();
        
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $edit = '<a href="' . route('invoice.edit', $row->FormID) . '" class="btn btn-avian-primary btn-sm btn-edit"><i class="fa fa-edit"></i></a>';
                $duplicate = '<a href="' . route('invoice.create', ['id' => $row->FormID]) . '" class="btn btn-avian-secondary btn-sm btn-duplicate"><i class="fa fa-copy"></i></a>';
                $print = '<a href="' . route('invoice.previewdynamic', $row->FormID) . '" target="_blank" class="btn btn-info btn-sm btn-print"><i class="fa fa-print"></i></a>';
                $download = '<a href="' . route('invoice.previewdynamic', ['id' => $row->FormID, 'download' => 1]) . '" target="_blank" class="btn btn-secondary btn-sm btn-print"><i class="fa fa-download"></i></a>';
                $copy = '<button class="btn btn-outline-secondary btn-sm btn-copy"><i class="fa fa-link"></i></button>';
                $delete = "<button data-url='" . route('invoice.destroy', $row->FormID) . "' class='btn-action btn btn-sm btn-danger btn-delete' data-id='" . $row->FormID . "' title='Delete'><i class='fa fa-trash'></i></button>";

                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">

                        '.$edit.'
                        '.$download.'

                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-avian-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="' . route('invoice.create', ['id' => $row->FormID]) . '"><i class="fa fa-copy mr-2"></i> Duplicate</a>
                                <a class="dropdown-item" href="' . route('invoice.previewdynamic', $row->FormID) . '" target="_blank"><i class="fa fa-file-pdf mr-2"></i> Open PDF</a>
                                <button class="dropdown-item btn-copy"><i class="fa fa-link mr-2"></i> Copy Link</button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item btn-delete text-danger" data-url="' . route('invoice.destroy', $row->FormID) . '"><i class="fa fa-trash mr-2"></i> Delete</button>
                            </div>
                        </div>

                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    
    public function previewdynamic(Request $request, $id, $download = 0)
    {
        if ($id == 0) {
            $invoice = null;
        } else {
            $invoice = HInvoice::findOrFail($id);
        }

        if ($request->input('IsLarge') ?? ($invoice && $invoice->IsLarge)) {
            $html = view('invoice.pdf_standard', [
                'request' => $request,
                'invoice' => $invoice,
            ])->render();

            $pdf = PDF::setOptions([
                'isRemoteEnabled' => true,
                'chroot' => public_path(),
            ])->loadHTML($html);
            $pdf->setPaper('a4', 'portrait');
        } else {
            $html = view('invoice.pdf_small', [
                'request' => $request,
                'invoice' => $invoice,
            ])->render();

            $pdf = PDF::setOptions([
                'isRemoteEnabled' => true,
                'chroot' => public_path(),
            ])->loadHTML($html);
            $pdf->setPaper('a4', 'landscape');
        }
        if ($download == 1 || $request->download == 1) {
            return $pdf->download("{$invoice?->InvoiceNo} {$invoice?->SJNo} ({$invoice?->Kode}) - {$invoice?->NamaCustomer}.pdf");
        }
        return $pdf->stream("{$invoice?->InvoiceNo} {$invoice?->SJNo} ({$invoice?->Kode}) - {$invoice?->NamaCustomer}.pdf");
    }

    public function getProducts(Request $request)
    {
        $products = MProduct::select('Name');

        $products = $products->where(function ($query) use ($request) {
            $query->where('Name', 'like', '%' . $request->search . '%')
                ->orWhere('Kode', 'like', '%' . $request->search . '%');
            })
            ->limit(10)
            ->pluck('Name');

        return response()->json([
            'status' => 'success',
            'products' => $products,
        ]);
    }

    public function getProductDetails(Request $request)
    {
        $product = MProduct::with('price')->where('Name', $request->search)->first();
        if (!$product) {
            $product = MProduct::with('price')->where('Kode', 'LIKE', '%' . $request->search . '%')->first();
        }

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }

    public function getCustomerDetails(Request $request)
    {
        $customer = MCustomer::where('Nama', $request->search)->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'customer' => $customer,
        ]);
    }
}
