@props(['request', 'invoice', 'page', 'large' => false])

@if ($request->IsInvoice ?? $invoice->InvoiceNo ?? '' != null)
<div class="page"> {{-- INVOICE --}}
    <div class="{{ $page != 0 ? "copy" : "" }}"  @if($large ?? false) style="padding:50px; @if(count($invoice->details) > 21) padding-top: 15px; padding-bottom: 15px @endif " @endif>
        <table width="100%">
        <tr>
        <td width="15%" align="center">
            <div class="logo" style="flex: 0 0 auto;"><img src="{{ public_path('images/logo' . ($page != 0 ? '-copy' : '') . '.png') }}" alt="Logo MPR"></div>
        </td>
        <td>
            <b style="color:red; font-size:16px;">Multi Prima Rasa</b><br>
            Surabaya<br>
            HP. 081332879850
        </td>
        <td align="center" valign="top" width="15%">
            @if($page == 0)
            <div style="border:2px solid red; color:red; font-weight:bold; padding:3px 10px; font-size:16px;">ASLI</div>
            @else
            <div style="border:2px solid #006FBE; color:#006FBE; font-weight:bold; padding:3px 10px; font-size:16px;">COPY</div>
            @endif
        </td>
        </tr>
    </table><h2 style="text-align:center; text-decoration:underline; margin-bottom:0px;">INVOICE</h2>
    <div style="text-align:center;">NO: {{ $request->invoiceno ?? $invoice->InvoiceNo ?? '' }}</div>

    <table width="100%" style="margin-top:10px; font-size:13px;">
        <tr>
        <td><b>Customer :</b></td>
        <td align="right"><b>Tanggal :</b> {{ $request->invoicedate != null ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->invoicedate)->format('d F Y') : \Carbon\Carbon::createFromFormat('Y-m-d', $invoice->InvoiceDate)->format('d F Y') }}</td>
        </tr>
        <tr>
        <td>Nama : {{ $request->namacustomer ?? $invoice->NamaCustomer ?? '
        ' }}</td>
        <td align="right">Telepon : {{ $request->telpcustomer ?? $invoice->TelpCustomer ?? '
        ' }}</td>
        </tr>
        <tr>
        <td colspan="2">Alamat : {{ $request->alamatcustomer ?? $invoice->AlamatCustomer ?? '
        ' }}</td>
        </tr>
    </table>

    <table id="invoice-items" width="100%" border="1" cellspacing="0" cellpadding="4" style="margin-top:10px; border-collapse:collapse; font-size:13px;border: 0px">
        <thead>
            <tr style="font-weight:bold; text-align:center;">
            <td colspan="2" width="15%"><small>Banyaknya</small></td>
            <td width="55%">Nama Barang</td>
            <td width="15%"><small>Harga Satuan (Rp)</small></td>
            <td width="15%">Jumlah (Rp)</td>
            </tr>
        </thead>
        <!-- Baris kosong -->
        <tbody @if(count($invoice->details) > 21) style="font-size: 10px" @endif>
            @if($request->product != null) 
                @php
                    $totalPrice = 0;
                @endphp
                @foreach($request->product ?? [] as $key => $product)
                @if((($request->issj[$key] ?? 0) == 1) || (($request->isinvoice[$key] ?? 1) == 0))
                @continue
                @endif
                @php
                    $totalNow = (float)str_replace(['.', ','], ['', ''], $request->quantity[$key]) * (float)str_replace(['.', ','], ['', ''], $request->price[$key]);
                    $totalPrice += $totalNow;
                @endphp
                <tr>
                    <td align="center">{!! $request->quantity[$key] ?? '&nbsp;' !!}</td>
                    <td align="center">{{ $request->unit[$key] }}</td>
                    <td>{{ $product }}</td>
                    <td align="center">{{ ($request->price[$key]) }}</td>
                    <td align="center">{{ ($request->price[$key] != null ? number_format($totalNow, 0, ',', '.') : '') }}</td>
                </tr>
                @endforeach
                @for($i = count($request->product ?? []); $i < (($large ?? false) ? 21 : 13); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
            @else
                @php
                    $totalPrice = 0;
                @endphp
                @foreach($invoice->details ?? [] as $detail)
                @if (($detail->IsInvoice ?? 0) == 0)
                    @continue
                @endif
                @php
                    $totalPrice += $detail->Qty * $detail->Harga;
                @endphp
                <tr>
                    <td align="center">{{ $detail->Qty }}</td>
                    <td align="center">{{ $detail->Satuan }}</td>
                    <td>{{ $detail->Nama }}</td>
                    <td align="center">{{ number_format($detail->Harga, 0, ',', '.') }}</td>
                    <td align="center">{{ number_format($detail->Qty * $detail->Harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @for($i = count($invoice->details ?? []); $i < (($large ?? false) ? 21 : 13); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
            @endif
            <tr>
                <td colspan="3" rowspan="3" style="color: red; border:0; white-space: pre-line; line-height: 0.4;">{!! nl2br(e($request->Notes ?? $invoice->Notes ?? '')) !!}</td>
                <td style="text-align:right; padding-right:12%; border: 0px">{{ ($request->IsDiscount ?? $invoice->IsDiscount ?? 0 != 0) ? 'Subtotal' : 'Total' }}</td>
                <td align="center"><b>{{ number_format($totalPrice, 0, ',', '.') }}</b></td>
            </tr>
            @if($request->IsDiscount ?? $invoice->IsDiscount ?? 0 != 0)
                <tr>
                    <td style="text-align:right; padding-right:12%; border: 0px">Disc {{ $request->Discount ?? $invoice->Discount ?? 0 }}%</td>
                    <td align="center"><b>{{ number_format($totalPrice * ($request->Discount ?? $invoice->Discount ?? 0) / 100, 0, ',', '.') }}</b></td>
                </tr>
                <tr>
                    <td style="text-align:right; padding-right:12%; border: 0px">Total</td>
                    <td align="center"><b>{{ number_format($totalPrice - ($totalPrice * ($request->Discount ?? $invoice->Discount ?? 0) / 100), 0, ',', '.') }}</b></td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <table width="100%" style="margin-top:15px; font-size:12px;">
        <tr>
        <td valign="top">
            @if($request->IsKonsinyasi ?? $invoice->IsKonsinyasi ?? 0 != 0)
                <div><b style="color:red;">KONSINYASI</b></div>
            @else
                @if($request->JatuhTempo ?? $invoice->JatuhTempo ?? 0 != 0)
                    @if($request->JatuhTempoSatuan ?? $invoice->JatuhTempoSatuan == 1)
                        <div>Jatuh Tempo Tanggal : <b style="color:red;">{{ $request->invoicedate != null ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->invoicedate)->addDays($request->JatuhTempo ?? $invoice->JatuhTempo)->format('d F Y') : \Carbon\Carbon::createFromFormat('Y-m-d', $invoice->InvoiceDate)->addDays($request->JatuhTempo ?? $invoice->JatuhTempo)->format('d F Y') }}</b></div>
                    @elseif($request->JatuhTempoSatuan ?? $invoice->JatuhTempoSatuan == 2)
                        <div>Jatuh Tempo Tanggal : <b style="color:red;">{{ $request->invoicedate != null ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->invoicedate)->addWeeks($request->JatuhTempo ?? $invoice->JatuhTempo)->format('d F Y') : \Carbon\Carbon::createFromFormat('Y-m-d', $invoice->InvoiceDate)->addWeeks($request->JatuhTempo ?? $invoice->JatuhTempo)->format('d F Y') }}</b></div>
                    @elseif($request->JatuhTempoSatuan ?? $invoice->JatuhTempoSatuan == 3)
                        <div>Jatuh Tempo Tanggal : <b style="color:red;">{{ $request->invoicedate != null ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->invoicedate)->addMonths($request->JatuhTempo ?? $invoice->JatuhTempo)->format('d F Y') : \Carbon\Carbon::createFromFormat('Y-m-d', $invoice->InvoiceDate)->addMonths($request->JatuhTempo ?? $invoice->JatuhTempo)->format('d F Y') }}</b></div>
                    @endif
                @else
                    <div>Jatuh Tempo Tanggal : <b style="color:red;">{{ $request->invoicedate != null ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->invoicedate)->format('d F Y') : \Carbon\Carbon::createFromFormat('Y-m-d', $invoice->InvoiceDate)->format('d F Y') }}</b></div>
                @endif
            @endif
            <div style="margin-top:5px;"><b>Pembayaran harap transfer ke :</b></div>
            <div>BCA. 6720353111<br>MERYWATY</div>
            <div style="margin-top:8px;">
            @if ($request->sjno ?? $invoice->SJNo ?? '' != null) Ref. SJ : <b><big>{{ $request->sjno ?? $invoice->SJNo ?? '' }}</big></b> @endif
            <br>
            Kode Order : <b><big><big>{{ $request->kode ?? $invoice->Kode ?? "-" }}</big></big></b>
            </div>
        </td>
        <td align="center">
            Hormat kami,
            <div class="stempel" style="flex: 0 0 auto;"><img src="{{ public_path('images/stempel' . ($page != 0 ? '-copy' : '') . '.png') }}" alt="Stempel MPR"></div>
            Multi Prima Rasa
        </td>
        </tr>
    </table>
    </div>
</div>
@endif