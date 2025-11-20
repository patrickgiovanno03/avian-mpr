<?php

namespace App\Http\Controllers;

use App\Models\DTandaTerima;
use App\Models\HInvoice;
use App\Models\HTandaTerima;
use App\Models\MCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TandaTerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $params['customers'] = HTandaTerima::select('NamaCustomer')->whereNotNull('NamaCustomer')
            ->distinct()
            ->pluck('NamaCustomer');
        return view('tt.index', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $invoiceids = explode(",",$request->invoices);
        if (count($invoiceids) == 0 || $invoiceids[0] == "") {
            $params['invoices'] = null;
        } else {
            $params['invoices'] = HInvoice::with('details')->whereIn('FormID', $invoiceids)->get();
            // calculate total tiap invoices
            foreach ($params['invoices'] as $invoice) {
                $total = 0;
                foreach ($invoice->details as $detail) {
                    $total += $detail->Harga * $detail->Qty;
                }
                $invoice->total = $total;
            }
        }
        $params['tt'] = null;
        $params['customers'] = MCustomer::distinct('Nama')->where('IsEkspedisi', 0)->get()->pluck('Nama');
        return view('tt.form', $params);
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
        $tt = new HTandaTerima();
        $tt->TTNo = $request->ttno;
        $tt->TTDate = Carbon::createFromFormat('d/m/Y', $request->ttdate)->format('Y-m-d');
        $tt->NamaCustomer = $request->namacustomer;
        $tt->AlamatCustomer = $request->alamatcustomer;
        $tt->TelpCustomer = $request->telpcustomer;
        $tt->Notes = $request->Notes;
        $tt->save();

        foreach ($request->invoiceno ?? [] as $index => $invoiceno) {
            $detail = new DTandaTerima();
            $detail->FormID = $tt->FormID;
            $detail->InvoiceNo = $invoiceno;
            $detail->SJNo = $request->sjno[$index];
            $detail->Idx = $request->no[$index];
            $detail->Date = $request->date[$index] != null ? Carbon::createFromFormat('d/m/Y', $request->date[$index])->format('Y-m-d') : null;
            $detail->Jumlah = str_replace('.', '', $request->jumlah[$index]);
            $detail->save();
        }

        return redirect()
            ->route('tt.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Tanda Terima berhasil dibuat.',
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
        $params['tt'] = HTandaTerima::with('details')->findOrFail($id);
        $params['customers'] = MCustomer::distinct('Nama')->where('IsEkspedisi', 0)->get()->pluck('Nama');
        return view('tt.form', $params);
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
        $tt = HTandaTerima::findOrFail($id);
        $tt->TTNo = $request->ttno;
        $tt->TTDate = Carbon::createFromFormat('d/m/Y', $request->ttdate)->format('Y-m-d');
        $tt->NamaCustomer = $request->namacustomer;
        $tt->AlamatCustomer = $request->alamatcustomer;
        $tt->TelpCustomer = $request->telpcustomer;
        $tt->Notes = $request->Notes;
        $tt->save();

        foreach ($request->invoiceno ?? [] as $index => $invoiceno) {
            if ($request->input('type')[$index] == 'delete') {
                if ($request->input('detailid')[$index]) {
                    $detail = DTandaTerima::find($request->input('detailid')[$index]);
                    if ($detail)
                        $detail->delete();
                }
                continue;
            }
            
            if ($invoiceno) {
                if ($request->input('type')[$index] == 'update' && $request->input('detailid')[$index]) {
                    $detail = DTandaTerima::find($request->input('detailid')[$index]);
                } else {
                    $detail = new DTandaTerima();
                }
                $detail->FormID = $tt->FormID;
                $detail->InvoiceNo = $invoiceno;
                $detail->SJNo = $request->sjno[$index];
                $detail->Idx = $request->no[$index];
                $detail->Date = $request->date[$index] != null ? Carbon::createFromFormat('d/m/Y', $request->date[$index])->format('Y-m-d') : null;
                $detail->Jumlah = str_replace('.', '', $request->jumlah[$index]);
                $detail->save();
            }
        }

        return redirect()
            ->route('tt.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Tanda Terima berhasil dibuat.',
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
        $tt = HTandaTerima::findOrFail($id);
        $tt->details()->delete();
        $tt->delete();

        return redirect()
            ->route('tt.index')
            ->with('result', (object)[
                'type' => 'success',
                'message' => 'Tanda Terima berhasil dihapus.',
            ]);
    }

    public function datatable(Request $request)
    {
        //
        $data = HTandaTerima::query();

        if ($request->has('customer') && $request->customer != '0') {
            $data->where('NamaCustomer', $request->customer);
        }

        if (request()->has('startdate') && request()->has('enddate') && request()->startdate != null && request()->enddate != null) {
            $data = $data->whereBetween('TTDate', [
                Carbon::createFromFormat('d/m/Y', request()->startdate)->format('Y-m-d'),
                Carbon::createFromFormat('d/m/Y', request()->enddate)->format('Y-m-d'),
            ]);
        }

        $data = $data->orderBy('FormID', 'desc')->get();
        
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('Total', function ($row) {
                $total = $row->details()->sum(\DB::raw('Jumlah'));
                return number_format($total, 0, ',', '.');
            })
            ->addColumn('TTDateNice', function ($row) {
                return Carbon::createFromFormat('Y-m-d', $row->TTDate)->format('d/m/Y');
            })
            ->addColumn('JumlahLembar', function ($row) {
                $count = $row->details()->count();
                return $count;
            })
            ->addColumn('action', function ($row) {
                $edit = '<a href="' . route('tt.edit', $row->FormID) . '" class="btn btn-avian-primary btn-sm btn-edit"><i class="fa fa-edit"></i></a>';
                $download = '<a href="' . route('tt.previewdynamic', ['id' => $row->FormID, 'download' => 1]) . '" target="_blank" class="btn btn-secondary btn-sm btn-print"><i class="fa fa-download"></i></a>';

                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">

                        '.$edit.'
                        '.$download.'

                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-avian-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="' . route('tt.previewdynamic', $row->FormID) . '" target="_blank"><i class="fa fa-file-pdf mr-2"></i> Open PDF</a>
                                <button class="dropdown-item btn-copy"><i class="fa fa-link mr-2"></i> Copy Link</button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item btn-delete text-danger" data-url="' . route('tt.destroy', $row->FormID) . '"><i class="fa fa-trash mr-2"></i> Delete</button>
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
            $tt = null;
        } else {
            $tt = HTandaTerima::findOrFail($id);
        }

        if ((count($request->invoiceno ?? $tt->details ?? [])) > 15) {
            $html = view('tt.pdf_standard', [
                'request' => $request,
                'tt' => $tt,
            ])->render();

            $pdf = Pdf::setOptions([
                'isRemoteEnabled' => true,
                'chroot' => public_path(),
            ])->loadHTML($html);
            $pdf->setPaper('a4', 'portrait');
        } else {
            $html = view('tt.pdf_small', [
                'request' => $request,
                'tt' => $tt,
            ])->render();

            $pdf = PDf::setOptions([
                'isRemoteEnabled' => true,
                'chroot' => public_path(),
            ])->loadHTML($html);
            $pdf->setPaper('a4', 'landscape');
        }
        if ($download == 1 || $request->download == 1) {
            return $pdf->download("{$tt?->TTNo} - {$tt?->NamaCustomer}.pdf");
        }
        return $pdf->stream("{$tt?->TTNo} - {$tt?->NamaCustomer}.pdf");
    }

    public function getFormDetails(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type'); // 'invoice' atau 'sj'

        if ($type === 'invoice') {
            $form = HInvoice::where('InvoiceNo', $search)->first();
        } else if ($type === 'sj') {
            $form = HInvoice::where('SJNo', $search)->first();
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid type']);
        }

        if ($form) {
            return response()->json(['status' => 'success', 'form' => $form]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Form not found']);
        }
    }
}
