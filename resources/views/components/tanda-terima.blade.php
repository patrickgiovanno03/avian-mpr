@props(['request', 'tt', 'page', 'large' => false])

<div class="page"> {{-- INVOICE --}}
    <div class="{{ $page != 0 ? "copy" : "" }}"  @if($large ?? false) style="padding:50px;" @endif>
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
    </table><h2 style="text-align:center; text-decoration:underline; margin-bottom:0px;">TANDA TERIMA</h2>
    <div style="text-align:center;">NO: {{ $request->ttno ?? $tt->TTNo ?? '' }}</div>

    <table width="100%" style="margin-top:10px; font-size:13px;">
        <tr>
            <td><b>Customer :</b></td>
            <td align="right"><b>Tanggal :</b> {{ $request->ttdate != null ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->ttdate)->format('d F Y') : \Carbon\Carbon::createFromFormat('Y-m-d', $tt->TTDate)->format('d F Y') }}</td>
            </tr>
            <tr>
            <td>Nama : {{ $request->namacustomer ?? $tt->NamaCustomer ?? '
            ' }}</td>
            <td align="right">Telepon : {{ $request->telpcustomer ?? $tt->TelpCustomer ?? '
            ' }}</td>
            </tr>
            <tr>
            <td colspan="2">Alamat : {{ $request->alamatcustomer ?? $tt->AlamatCustomer ?? '
            ' }}</td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td colspan="2"><b>Banyaknya : </b>{{ count($request->invoiceno ?? $tt->details ?? []) ?? 0 }} lembar set</td>
        </tr>
    </table>

    <table id="invoice-items" width="100%" border="1" cellspacing="0" cellpadding="4" style="margin-top:10px; border-collapse:collapse; font-size:13px;border: 0px">
        <thead>
            <tr style="font-weight:bold; text-align:center;">
            <td width="40%">No. Nota/Surat Jalan</td>
            <td width="35%">Tanggal</td>
            <td width="25%">Jumlah (Rp)</td>
            </tr>
        </thead>
        <!-- Baris kosong -->
        <tbody>
            @if($request->invoiceno != null) 
                @php
                    $totalPrice = 0;
                @endphp
                @foreach($request->invoiceno ?? [] as $index => $invoiceno)
                @php
                    $totalPrice += (int)str_replace(['.', ','], ['', ''], $request->jumlah[$index]);
                @endphp
                <tr>
                    <td align="center">{{ $request->no[$index].($invoiceno != null ? "/".$invoiceno : '').($request->sjno[$index] != null ? "/".$request->sjno[$index] : '') }}</td>
                    <td align="center">{{ $request->date[$index] != null ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->date[$index])->format('d/m/Y') : '' }}</td>
                    <td align="center">{{ $request->jumlah[$index] ?? 0 }}</td>
                </tr>
                @endforeach
                @for($i = count($request->invoiceno ?? []); $i < (($large ?? false) ? 21 : 13); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
            @else
                @php
                    $totalPrice = 0;
                @endphp
                @foreach($tt->details ?? [] as $detail)
                @php
                    $totalPrice += $detail->Jumlah;
                @endphp
                <tr>
                    <td align="center">{{ $detail->Idx.($detail->InvoiceNo != null ? "/".$detail->InvoiceNo : '').($detail->SJNo != null ? "/".$detail->SJNo : '') }}</td>
                    <td align="center">{{ $detail->Date != null ? \Carbon\Carbon::createFromFormat('Y-m-d', $detail->Date)->format('d/m/Y') : '' }}</td>
                    <td align="center">{{ number_format($detail->Jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @for($i = count($tt->details ?? []); $i < (($large ?? false) ? 21 : 13); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endfor
            @endif
            <tr>
                <td rowspan="3" style="color: red; border:0; white-space: pre-line; line-height: 0.4;">{!! nl2br(e($request->Notes ?? $tt->Notes ?? '')) !!}</td>
                <td style="text-align:right; padding-right:12%; border: 0px">Total</td>
                <td align="center"><b>{{ number_format($totalPrice, 0, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>
    
    <table width="100%" style="margin-top:15px; font-size:12px;">
        <tr>
        <td valign="top">
            <div style="margin-top:5px;"><b>Pembayaran harap transfer ke :</b></div>
            <div>BCA. 6720353111<br>MERYWATY</div>
        </td>
        <td align="center">
            Penerima,
            <div class="stempel" style="flex: 0 0 auto; min-height:80px"><img src="" alt="Stempel MPR"></div>
            (_____________)
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