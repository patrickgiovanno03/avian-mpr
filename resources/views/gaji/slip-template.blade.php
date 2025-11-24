<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Slip Gaji Pegawai</title>
  <style>
    /* ====== GLOBAL STYLE ====== */
    body {
      font-family: Arial, sans-serif;
      margin: 0; /* hilangkan margin body agar penuh */
      color: #333;
      font-size: 16px;
    }

    .page-wrapper {
      width: 100%;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      box-sizing: border-box;
    }

    .slip-container {
      width: 100%;
      max-width: 1000px; /* lebih besar agar terasa “full” tapi tetap proporsional di layar lebar */
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
      font-size: 54px;
      color: #d62828;
    }

    .header small {
      color: #555;
    }

    /* ====== INFO TABLE ====== */
    .info {
      margin-bottom: 25px;
      font-size: 24px;
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
      margin-bottom: 10px;
      font-size: 24px;
    }

    table.rincian th, table.rincian td {
      border: 2px solid #df7979;
      padding: 5px;
      text-align: center;
    }

    table.rincian th {
      background: #d62828;
      border: 2px solid #d62828!important;
      color: #fff;
      font-weight: bold;
    }

    table.rincian tfoot td {
      font-weight: bold;
      background: #e2e2e2;
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
      margin-top: 10px;
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
    }

    .lampiran h3 {
      color: #d62828;
      font-size: 24px;
      margin-bottom: 10px;
    }

    .lampiran img {
      max-width: 100%;
      max-height: 550px;
      border: 1px solid #ddd;
      border-radius: 6px;
      margin-top: 10px;
      display: block;
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
      </div>

      <div class="info">
        <table>
          <tr>
            <td><strong>Nama Pegawai:</strong> {{ $hgaji->pegawai->Nama ?? '' }}</td>
            <td style="text-align: right;"><strong>Tanggal:</strong> {{ \Carbon\Carbon::createFromFormat('Y-m-d', $hgaji->mgaji->Tanggal)->format('d F Y') }}</td>
          </tr>
        </table>
      </div>

      <table class="rincian">
        <thead>
          <tr>
            <th>Hari Ke</th>
            <th>Tanggal</th>
            <th>Gaji Pokok (Rp)</th>
            <th>Jam Lembur</th>
            <th>Gaji Lembur Total (Rp)</th>
            <th>Total Hari Ini (Rp)</th>
          </tr>
        </thead>
        <tbody>
          @php $totalGaji = 0; $totalPokok = 0; $totalJamLembur = 0; $totalLembur = 0; @endphp
          @foreach ($hgaji->dgaji as $index => $gaji)
            @php
              $harian = $gaji->Pokok + ($gaji->Lembur);
              $totalGaji += $harian;
              $totalPokok += $gaji->Pokok;
              $totalJamLembur += $gaji->Jam;
              $totalLembur += $gaji->Lembur;
            @endphp
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $gaji->Tanggal)->format('d/m/Y') }}</td>
              <td>{{ number_format($gaji->Pokok*1000, 0, ',', '.') }}</td>
              <td>{{ $gaji->Jam }}</td>
              <td>{{ number_format($gaji->Lembur*1000, 0, ',', '.') }}</td>
              <td>{{ number_format($harian*1000, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
            <tr>
              <td colspan="2">Total</td>
              <td>{{ number_format($totalPokok*1000, 0, ',', '.') }}</td>
              <td>{{ $totalJamLembur }}</td>
              <td>{{ number_format($totalLembur*1000, 0, ',', '.') }}</td>
              <td>{{ number_format($totalGaji*1000, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
      </table>

      <div class="totals">
        <p><strong>Uang Makan:</strong> Rp {{ number_format($hgaji->UangMakan*1000, 0, ',', '.') }}</p>
        <p><strong>Bonus:</strong> Rp {{ number_format($hgaji->Bonus*1000, 0, ',', '.') }}</p>
        <p class="total"><strong>Total Gaji:</strong> Rp {{ number_format(($totalGaji + $hgaji->Bonus + $hgaji->UangMakan)*1000, 0, ',', '.') }}</p>
      </div>

      <!-- ====== BAGIAN LAMPIRAN ====== -->
      <div class="lampiran">
        <h3>Lampiran</h3>
        <img src="{{ $base64Image }}" style="width: 100%;">
      </div>
    </div>
  </div>

</body>
</html>
