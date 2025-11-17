@props(['request', 'invoice', 'page', 'large' => false])

@if ($request->IsSJ ?? $invoice->SJNo ?? '' != null)
<div class="page"> {{-- SURAT JALAN --}}
    <div class="{{ $page != 0 ? "copy" : "" }}"  @if($large ?? false) style="padding:50px;" @endif>
        <table width="100%">
        <tr>
        <td width="15%" align="center" style="opacity: {{ ($request->IsKopSurat ?? $invoice->IsKopSurat ?? 0 != 0) ? '1' : '0' }}">
            <div class="logo" style="flex: 0 0 auto;"><img src="{{ public_path('images/logo' . ($page != 0 ? '-copy' : '') . '.png') }}" alt="Logo MPR"></div>
        </td>
        <td style="opacity: {{ ($request->IsKopSurat ?? $invoice->IsKopSurat ?? 0 != 0) ? '1' : '0' }}">
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
    </table><h2 style="text-align:center; text-decoration:underline; margin-bottom:0px;">SURAT JALAN</h2>
    <div style="text-align:center;">NO: {{ $request->sjno ?? $invoice->SJNo ?? '' }}</div>

    @if(($request->IsSJCustomer ?? $invoice->IsSJCustomer ?? 0) == 0)
    <table width="100%" style="margin-top:0; font-size:13px;">
        <tr>
        <td><b>Customer :</b></td>
        <td align="right"><b>Tanggal :</b> {{ ($request->sjdate != null || $invoice->SJDate != null) ? (($request->sjdate != null) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->sjdate)->format('d F Y') : \Carbon\Carbon::createFromFormat('Y-m-d', $invoice->SJDate)->format('d F Y')) : '' }}</td>
        </tr>
        <tr>
        <td>Nama : {{ $request->namaekspedisi ?? $invoice->NamaEkspedisi ?? '' }}</td>
        <td align="right">Telepon : {{ $request->telpekspedisi ?? $invoice->TelpEkspedisi ?? '' }}</td>
        </tr>
        <tr>
        <td colspan="2">Alamat : {{ $request->alamatekspedisi ?? $invoice->AlamatEkspedisi ?? '' }}</td>
        </tr>
    </table>
    @else
    <table width="100%" style="margin-top:10px; font-size:13px;">
        <tr>
        <td><b>Customer :</b></td>
        <td align="right"><b>Tanggal :</b> {{ ($request->sjdate != null || $invoice->SJDate != null) ? (($request->sjdate != null) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->sjdate)->format('d F Y') : \Carbon\Carbon::createFromFormat('Y-m-d', $invoice->SJDate)->format('d F Y')) : '' }}</td>
        </tr>
        <tr>
        <td>Nama : {{ $request->namacustomer ?? $invoice->NamaCustomer ?? '' }}</td>
        <td align="right">Telepon : {{ $request->telpcustomer ?? $invoice->TelpCustomer ?? '' }}</td>
        </tr>
        <tr>
        <td colspan="2">Alamat : {{ $request->alamatcustomer ?? $invoice->AlamatCustomer ?? '' }}</td>
        </tr>
    </table>
    @if($request->IsEkspedisi ?? $invoice->IsEkspedisi ?? 0 != 0)
    <table width="100%" style="margin-top:0; font-size:13px;">
        <tr>
        <td><b>Kirim Untuk :</b></td>
        <td align="right">Telepon : {{ $request->telpekspedisi ?? $invoice->TelpEkspedisi ?? '' }}</td>
        </tr>
        <tr>
        <td colspan="2">Nama : {{ $request->namaekspedisi ?? $invoice->NamaEkspedisi ?? '' }}</td>
        </tr>
        <tr>
        <td colspan="2">Alamat : {{ $request->alamatekspedisi ?? $invoice->AlamatEkspedisi ?? '' }}</td>
        </tr>
    </table>
    @endif
    @endif

    <table id="invoice-items" width="100%" border="1" cellspacing="0" cellpadding="4" style="margin-top:10px; border-collapse:collapse; font-size:13px;border: 0px">
        <thead>
            <tr style="font-weight:bold; text-align:center;">
            <td colspan="2" width="15%"><small>Banyaknya</small></td>
            <td width="85%">Nama Barang</td>
            </tr>
        </thead>
        <!-- Baris kosong -->
        <tbody>
            
            @if($request->product != null)
                @php
                    $totalQty = [];
                @endphp
                @foreach($request->product ?? [] as $key => $product)
                @if((($request->issj[$key+1] ?? 0) == 1) || (($request->hidden[$key] ?? 0) == 1))
                @continue
                @endif
                @php
                    $totalQty['' . $request->unit[$key]] = ($totalQty['' . $request->unit[$key]] ?? 0) + $request->quantity[$key];
                @endphp
                <tr>
                    <td align="center">{!! $request->quantity[$key] ?? '&nbsp;' !!}</td>
                    <td align="center">{{ $request->unit[$key] }}</td>
                    <td>{{ $product }}</td>
                </tr>
                @endforeach
                @for($i = count($request->product ?? []); $i < (($large ?? false) ? 21 : 13); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
            @else
                @php
                    $totalQty = [];
                    $totalDosGabung = [];
                @endphp
                @foreach($invoice->details ?? [] as $detail)
                    @if($detail->IsHidden ?? 0 == 1)
                        @continue
                    @endif
                @php
                    if ($detail->IsSJ ?? 0 == 1) {
                        $totalQty['' . $detail->SatuanSJ] = ($totalQty['' . $detail->SatuanSJ] ?? 0) + $detail->QtySJ;
                    } else {
                        if ($detail->DosGabung != null && $detail->DosGabung != 0) {
                            if (($totalDosGabung[$detail->DosGabung] ?? null) == null) {
                                $totalDosGabung[$detail->DosGabung] = [];
                                $totalQty['dos'] = ($totalQty['dos'] ?? 0) + 1;
                            }
                            $desc = ['Nama' => $detail->Nama, 'Qty' => $detail->Qty, 'Satuan' => $detail->Satuan];
                            array_push($totalDosGabung[$detail->DosGabung], $desc);
                        } else {
                            $unit = ($detail->DosLuar == 0) ? $detail->Satuan : "dos";
                            $qty = ($detail->DosLuar == 0) ? $detail->Qty : $detail->DosLuar;
                            $totalQty['' . $unit] = ($totalQty['' . $unit] ?? 0) + $qty;
                        }
                    }
                @endphp
                {{-- Kalau digabung dos luar, maka tidak di print --}}
                @if ($detail->DosGabung != null && $detail->DosGabung != 0)
                    @continue
                @endif
                <tr>
                    @if ($detail->IsSJ ?? 0 == 1)
                        <td align="center">{{ $detail->QtySJ }}</td>
                        <td align="center">{{ $detail->SatuanSJ }}</td>
                        <td>{{ $detail->NamaSJ }}</td>
                    @else
                        <td align="center">{{ ($detail->DosLuar == 0) ? $detail->Qty : $detail->DosLuar }}</td>
                        <td align="center">{{ ($detail->DosLuar == 0) ? $detail->Satuan : "dos" }}</td>
                        <td>{{ $detail->Nama . (($detail->DosLuar == 0) ? "" : " (isi {$detail->Isi} {$detail->Satuan})") }}</td>
                    @endif
                </tr>
                @endforeach
                @foreach($totalDosGabung as $dosGabung => $items)
                    <tr>
                        <td rowspan="{{ count($items) + 1 }}" align="center">1</td>
                        <td rowspan="{{ count($items) + 1 }}" align="center">dos</td>
                        <td>
                            isi :
                        </td>
                    </tr>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            - {{ $item['Qty'] }} {{ $item['Satuan'] }} {{ $item['Nama'] }}
                        </td>
                    </tr>
                    @endforeach
                @endforeach
                @for($i = count($invoice->details ?? []); $i < (($large ?? false) ? 21 : 13); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
            @endif
            @php
                // ubah array jadi "2 dos + 5 toples"
                $totalQtyString = collect($totalQty)
                    ->map(function($qty, $unit) {
                        return "{$qty} {$unit}";
                    })
                    ->implode(' + ');
            @endphp
            <tr>
                <td colspan="2" align="center"><b>Jumlah</b> </td>
                <td><b>{{ $totalQtyString }}</b></td>
            </tr>
        </tbody>
    </table>
    
    <table width="100%" style="margin-top:15px; font-size:12px;">
        <tr>
        <td valign="top">
            <div>Kode Order</div>
            <div><b><big><big>{{ $request->kode ?? $invoice->Kode ?? "-" }}</big></big></b></div>
            
            @if (($request->invoiceno ?? $invoice->InvoiceNo ?? '' != null) && ($request->IsKopSurat ?? $invoice->IsKopSurat ?? 0 != 0))
            Lampiran<br>
            *copy invoice no : <br>
            <b><big>{{ $invoice->InvoiceNo ?? "-" }}</big></b>
            <br>
            @endif
            {!! nl2br(($request->NotesSJ ?? $invoice->NotesSJ ?? '')) !!}
        </td>
        <td align="center">
            Penerima,
            <div class="stempel" style="flex: 0 0 auto; min-height:80px"><img src="" alt="Stempel MPR"></div>
            (_____________)
        </td>
        <td align="center">
            Hormat kami,
            <div class="stempel" style="opacity: {{ ($request->IsKopSurat ?? $invoice->IsKopSurat ?? 0 != 0) ? '1' : '0' }}; flex: 0 0 auto; min-height:80px"><img src="{{ public_path('images/stempel' . ($page != 0 ? '-copy' : '') . '.png') }}" alt="Stempel MPR"></div>
            {{ ($request->IsKopSurat ?? $invoice->IsKopSurat ?? 0 != 0) ? 'Multi Prima Rasa' : ($request->TTD ?? $invoice->TTD ?? '(_____________)') }}
        </td>
        </tr>
    </table>
    </div>
</div>
@endif