<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Slip Gaji Pegawai</title>
  <style>
    /* ====== GLOBAL STYLE ====== */
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      margin: 0; /* hilangkan margin body agar penuh */
      background: #f9fafb;
      color: #333;
      font-size: 16px;
    }

    .page-wrapper {
      width: 100%;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 20px;
      box-sizing: border-box;
    }

    .slip-container {
      width: 100%;
      max-width: 1000px; /* lebih besar agar terasa “full” tapi tetap proporsional di layar lebar */
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 30px 40px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      box-sizing: border-box;
    }

    /* ====== HEADER ====== */
    .header {
      text-align: center;
      border-bottom: 3px solid #d62828;
      padding-bottom: 10px;
      margin-bottom: 25px;
    }

    .header h2 {
      margin: 0;
      font-size: 36px;
      color: #d62828;
    }

    .header small {
      color: #555;
    }

    /* ====== INFO TABLE ====== */
    .info {
      margin-bottom: 25px;
    }

    .info table {
      width: 100%;
      border-collapse: collapse;
    }

    .info td {
      padding: 6px 0;
      vertical-align: top;
      color: #444;
    }

    .info td strong {
      color: #000;
    }

    /* ====== TABLE RINCIAN ====== */
    table.rincian {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table.rincian th, table.rincian td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }

    table.rincian th {
      background: #d62828;
      color: #fff;
      font-weight: 600;
    }

    table.rincian tr:nth-child(even) {
      background: #f8f9fa;
    }

    table.rincian tr:hover {
      background: #ffe8e8;
    }

    /* ====== TOTAL ====== */
    .totals {
      text-align: right;
      margin-top: 15px;
    }

    .totals p {
      font-size: 27px;
      margin: 3px 0;
    }

    .totals p span {
      display: inline-block;
      width: 300px%;
      font-weight: bold;
    }

    .totals p.total {
      font-size: 36px;
      font-weight: 700;
      color: #d62828;
      border-top: 2px solid #d62828;
      padding-top: 5px;
    }

    /* ====== LAMPIRAN ====== */
    .lampiran {
      margin-top: 40px;
      border-top: 2px dashed #ccc;
      padding-top: 20px;
    }

    .lampiran h3 {
      color: #d62828;
      font-size: 16px;
      margin-bottom: 10px;
    }

    .lampiran img {
      max-width: 100%;
      border: 1px solid #ddd;
      border-radius: 6px;
      margin-top: 10px;
      display: block;
    }

    /* ====== FOOTER ====== */
    .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 12px;
      color: #777;
      border-top: 1px solid #eee;
      padding-top: 10px;
    }

    /* ====== PRINT MODE ====== */
    @media print {
      body {
        background: #fff;
        margin: 0;
        padding: 0;
      }

      .page-wrapper {
        padding: 0;
      }

      .slip-container {
        box-shadow: none;
        border: none;
        border-radius: 0;
        padding: 10mm 15mm;
        width: 100%;
        max-width: none;
      }

      .header {
        border-color: #000;
      }

      .header h2 {
        color: #000;
      }

      .rincian th {
        background: #ddd !important;
        color: #000 !important;
      }

      .totals p.total {
        color: #000 !important;
        border-color: #000 !important;
      }

      .lampiran h3 {
        color: #000 !important;
      }
    }
  </style>
</head>
<body>

  <div class="page-wrapper">
    <div class="slip-container">
      <div class="header">
        <h2>Slip Gaji Pegawai</h2>
        <small>Periode: {{ $hgaji->Periode ?? '-' }}</small>
      </div>

      <div class="info">
        <table>
          <tr>
            <td><strong>Nama Pegawai:</strong> {{ $hgaji->pegawai->Nama ?? '' }}</td>
            <td><strong>Tanggal Cetak:</strong> {{ now()->format('d-m-Y') }}</td>
          </tr>
        </table>
      </div>

      <table class="rincian">
        <thead>
          <tr>
            <th>Hari Ke</th>
            <th>Gaji Pokok (Rp)</th>
            <th>Jam Lembur</th>
            <th>Gaji Lembur (Rp)</th>
            <th>Total Hari Ini (Rp)</th>
          </tr>
        </thead>
        <tbody>
          @php $totalGaji = 0; @endphp
          @foreach ($hgaji->dgaji as $index => $gaji)
            @php
              $harian = $gaji->Pokok + ($gaji->Jam * $gaji->Lembur);
              $totalGaji += $harian;
            @endphp
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ number_format($gaji->Pokok*1000, 0, ',', '.') }}</td>
              <td>{{ $gaji->Jam }}</td>
              <td>{{ number_format($gaji->Lembur*1000, 0, ',', '.') }}</td>
              <td>{{ number_format($harian*1000, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="totals">
        <p><span>Bonus:</span> Rp {{ number_format($hgaji->Bonus*1000, 0, ',', '.') }}</p>
        <p class="total"><span>Total Gaji:</span> Rp {{ number_format(($totalGaji + $hgaji->Bonus)*1000, 0, ',', '.') }}</p>
      </div>

      <!-- ====== BAGIAN LAMPIRAN ====== -->
      <div class="lampiran">
        <h3>Lampiran</h3>
        <img src="{{ asset('storage/' . $hgaji->URL) }}" alt="Lampiran Slip Gaji">
      </div>

      <div class="footer">
        {{-- <p>Slip ini dicetak otomatis oleh sistem — tidak memerlukan tanda tangan.</p>
        <p>© {{ date('Y') }} Perusahaan Anda. Semua hak dilindungi.</p> --}}
      </div>
    </div>
  </div>

</body>
</html>
