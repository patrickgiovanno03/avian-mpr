<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ 'MPR' }}</title>
    <style>
        @page {
            margin: 0;
        }
        @font-face {
            font-family: "Lexend";
            font-style: normal;
            font-weight: 400;
            src: url('{{ public_path('fonts/Lexend/Lexend-Regular.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: "Lexend";
            font-style: normal;
            font-weight: 500;
            src: url('{{ public_path('fonts/Lexend/Lexend-Medium.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: "Lexend";
            font-style: normal;
            font-weight: 700;
            src: url('{{ public_path('fonts/Lexend/Lexend-Bold.ttf') }}') format('truetype');
        }
        body {
            font-family: 'Lexend';
            font-size: 12pt;
            line-height: 1;
        }
        .logo img {
            max-height: 60px;
        }

        .stempel img {
            max-height: 80px;
        }

        #invoice-items thead tr td {
            border: 1.5px solid #000;
        }
        #invoice-items tbody tr td {
            border: 1.5px solid #000;
            padding: 0px 2px;
        }

        .copy {
            color: #006FBE!important;
        }

        /* Garis tabel */
        .copy table,
        .copy td,
        .copy th {
        border-color: #006FBE !important;
        }

        .copy * {
            color: #006FBE !important;
        }

        .page {
            page-break-before: always;
        }

        .page:first-child {
            page-break-before: auto;
        }
    </style>
</head>
<body>
    @if ($request->IsInvoice ?? $invoice->InvoiceNo ?? '' != null)
    <div class="page"> {{-- INVOICE --}}
        <table style="width:100%; border-collapse:collapse; margin-top:5px">
            <tr>
                <td style="width:48%; padding:20px;">
                    <x-invoice :request="$request" :invoice="$invoice" :page="0" />
                </td>
                <td><div style="width: 4%"></div></td>
                <td style="width:48%; padding:20px;">
                    <x-invoice :request="$request" :invoice="$invoice" :page="1" />
                </td>
            </tr>
        </table>
    </div>
    @endif
    
    @if ($request->IsSJ ?? $invoice->SJNo ?? '' != null)
    <div class="page"> {{-- SURAT JALAN --}}
        <table style="width:100%; border-collapse:collapse; margin-top:5px">
            <tr>
                <td style="width:48%; padding:20px; {{ ($request->IsEkspedisi ?? $invoice->IsEkspedisi ?? 0) == 1 ? 'padding-top:10px' : '' }}">
                    <x-surat-jalan :request="$request" :invoice="$invoice" :page="0" />
                </td>
                <td><div style="width: 4%"></div></td>
                <td style="width:48%; padding:20px; {{ ($request->IsEkspedisi ?? $invoice->IsEkspedisi ?? 0) == 1 ? 'padding-top:10px' : '' }}">
                    <x-surat-jalan :request="$request" :invoice="$invoice" :page="1" />
                </td>
            </tr>
        </table>
    </div>
    @endif

</body>
</html>
