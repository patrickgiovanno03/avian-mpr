@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @if (!$mgaji )
        <form id="formInput" class="col-md-12" method="post" action="{{ route('gaji.upload') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <h1 class="p-0">Form</h1>
            
            <input type="file" name="photos[]" multiple accept="image/*">
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        @else
            <a class="btn btn-info" href="{{ route('gaji.slipAll', $mgaji->GajiID) }}" target="blank" >Download All</a>
        <div class="row">
            @foreach($mgaji->hgaji as $gaji)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <img src="{{ asset('storage/' . $gaji->URL) }}" class="card-img-top" alt="">
                        <div class="card-body text-center">
                            <button class="btn btn-sm btn-primary btn-isi-data" data-toggle="modal" data-target="#modalGaji" data-id="{{ $gaji->HeaderID }}">
                                Isi Data Gaji
                            </button>
                            @if($gaji->PegawaiID != null)
                            <a href="{{ route('gaji.slip', $gaji->HeaderID) }}" target="blank" class="btn btn-avian-secondary btn-sm btn-pdf">
                                Slip Gaji
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            </div>

        @endif
    </div>
</div>
<!-- Modal Form Gaji -->
<div class="modal fade" id="modalGaji" tabindex="-1" aria-labelledby="modalGajiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <form id="formItem" class="modal-content" method="post" action="{{ route('gaji.storeDetail', $gaji->GajiID ?? 0) }}" autocomplete="off">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalGajiLabel">Form Gaji - {{ $gaji->NamaKaryawan ?? 'Karyawan' }}</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          @csrf

          <div class="mb-3">
            <label>Pegawai</label>
            <select class="form-control select2 pegawai-select" id="pegawai" name="pegawai">
                
            </select>
          </div>

          <!-- Input gaji pokok & lembur -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label>Gaji Pokok (per hari)</label>
              <input type="number" step="any" class="form-control" id="gaji_pokok" placeholder="Masukkan gaji pokok" name="gajipokok">
            </div>
            <div class="col-md-6">
              <label>Gaji Lembur (per hari)</label>
              <input type="number" step="any" class="form-control" id="gaji_lembur" placeholder="Masukkan gaji lembur" name="gajilembur">
            </div>
          </div>

          <!-- Input jumlah hari -->
          <div class="mb-3">
            <label>Jumlah Hari Kerja</label>
            <input type="number" step="any" class="form-control" id="jumlah_hari" placeholder="Masukkan jumlah hari" name="jumlahhari">
          </div>

          <!-- Tabel otomatis muncul -->
          <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle text-center" id="tabelGaji">
                <thead class="table-light">
                <tr>
                    <th>Hari</th>
                    <th>Gaji Pokok</th>
                    <th>Jam Lembur</th>
                    <th>Gaji Lembur (per jam)</th>
                    <th>Total Hari Ini</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>

          <!-- Input bonus -->
          <div class="mb-3">
            <label>Bonus Gaji</label>
            <input type="number" step="any" class="form-control" id="bonus" placeholder="Masukkan bonus (opsional)" name="bonus">
          </div>

          <!-- Total akhir -->
          <div class="mb-3">
            <label>Total Gaji</label>
            <input type="number" step="any" class="form-control bg-light" id="total_gaji" readonly>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
        </form>
    </div>
  </div>
</div>
<style>
    li.select2-results__option {
  white-space: nowrap;
}
@media (max-width: 768px) {
  table.dataTable thead {
    display: none;
  }

  table.dataTable, 
  table.dataTable tbody, 
  table.dataTable tr, 
  table.dataTable td {
    display: block;
    width: 100%;
  }

  table.dataTable tr {
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
  }

  table.dataTable td {
    text-align: right;
    position: relative;
    padding-left: 50%;
  }

  table.dataTable td::before {
    content: attr(data-label);
    position: absolute;
    left: 10px;
    top: 10px;
    width: 45%;
    white-space: nowrap;
    text-align: left;
    font-weight: bold;
  }
  table.dataTable th, table.dataTable td {
  white-space: nowrap;
  vertical-align: middle;
}

@media (max-width: 768px) {
  table.dataTable th, table.dataTable td {
    white-space: normal;
  }
}
@media (max-width: 768px) {
  .table-responsive {
    width: 100%;
    overflow-x: auto;
  }

  .table {
    width: 100% !important;
    margin: 0 !important;
  }

  .card {
    width: 100% !important;
    margin: 0 auto 10px auto !important;
    box-sizing: border-box;
  }

  .card-body {
    padding: 10px;
  }

  .form-control {
    width: 100%;
  }
}

}

</style>
@endsection

@section('js')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var isChanged = false;
var isLocked = false;

$(document).ready(function () {
    $('.select2').css('width', '100%');

    $('#formInput').on('submit', function(e) {
        e.preventDefault();

        // cek kalau semua asset sudah dipilih
        if(false){
            Swal.fire({
                icon: 'error',
                title: 'Anda belum menambahkan data detail',
            });
            return false;

        }
        Swal.fire({
            title: 'Apakah anda yakin untuk menyimpan data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                this.submit();
            } else {
                return false;
            }
            });
        }
    );

});

document.addEventListener('DOMContentLoaded', function () {

    const inputGajiPokok = document.getElementById(`gaji_pokok`);
    const inputGajiLembur = document.getElementById(`gaji_lembur`);
    const inputJumlahHari = document.getElementById(`jumlah_hari`);
    const inputBonus = document.getElementById(`bonus`);
    const tabelBody = document.querySelector(`#tabelGaji tbody`);
    const inputTotal = document.getElementById(`total_gaji`);

    // Hitung total keseluruhan
    function hitungTotal() {
        let total = 0;
        tabelBody.querySelectorAll('tr').forEach(row => {
            const pokok = parseFloat(row.querySelector('.pokok').value) || 0;
            const lembur = parseFloat(row.querySelector('.lembur').value) || 0;
            const subtotal = pokok + lembur;
            row.querySelector('.total').value = subtotal.toFixed(2);
            total += subtotal;
        });

        const bonus = parseFloat(inputBonus.value) || 0;
        inputTotal.value = (total + bonus).toFixed(2);
    }

    // Generate tabel otomatis saat jumlah hari berubah
    inputJumlahHari.addEventListener('input', function() {
        const jml = parseInt(this.value) || 0;
        tabelBody.innerHTML = '';
        if (jml > 0) {
            for (let i = 1; i <= jml; i++) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>Hari ${i}</td>
                    <td><input type="number" step="any" name="pokok[]" class="form-control text-end pokok" value="${inputGajiPokok.value || 0}"></td>
                    <td><input type="number" step="any" name="jam[]" class="form-control text-end jam" value="0" min="0" placeholder="Jam lembur"></td>
                    <td><input type="number" step="any" name="lembur[]" class="form-control text-end lembur" value="0" readonly></td>
                    <td><input type="number" step="any" class="form-control text-end total" readonly></td>
                `;
                tabelBody.appendChild(row);
            }
        }
        hitungTotal();
    });

    // Saat jam lembur diganti → update kolom gaji lembur & total
    tabelBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('jam')) {
            const row = e.target.closest('tr');
            const jam = parseFloat(e.target.value) || 0;
            const rateLembur = parseFloat(inputGajiLembur.value) || 0;
            const totalLembur = jam * rateLembur;
            row.querySelector('.lembur').value = totalLembur.toFixed(2);
        }
        hitungTotal();
    });

    // Regenerate tabel kalau gaji pokok/lembur per jam berubah
    inputGajiPokok.addEventListener('input', () => inputJumlahHari.dispatchEvent(new Event('input')));
    inputGajiLembur.addEventListener('input', () => inputJumlahHari.dispatchEvent(new Event('input')));
    inputBonus.addEventListener('input', hitungTotal);
});
$('#modalGaji').on('shown.bs.modal', function () {
    $(this).find('.pegawai-select').select2({
        dropdownParent: $(this), // penting untuk modal
        placeholder: 'Pilih Pegawai...',
        theme: 'bootstrap4',
        tags: true,
        ajax: {
            url: '{{ route("pegawai.getPegawai") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { search: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.pegawais.map(function (item) {
                        return { 
                            id: item.PegawaiID, text: item.Nama, pokok: item.GajiPokok, lembur: item.GajiLembur
                         };
                    })
                };
            },
            cache: true
        }
    });
});

$('.pegawai-select').on('select2:select', function (e) {
    var data = e.params.data;
    var gajiPokok = data.pokok || 0;
    var gajiLembur = data.lembur || 0;

    $('#gaji_pokok').val(gajiPokok);
    $('#gaji_lembur').val(gajiLembur);
    hitungTotal();
    inputGajiPokok.addEventListener('input', () => inputJumlahHari.dispatchEvent(new Event('input')));
    inputGajiLembur.addEventListener('input', () => inputJumlahHari.dispatchEvent(new Event('input')));
    inputBonus.addEventListener('input', hitungTotal);
});

$('.btn-isi-data').on('click', function() {
    var gajiId = $(this).data('id');
    // reset form
    $('#formItem')[0].reset();
    $('#tabelGaji tbody').empty();
    $('#total_gaji').val('');
    $('#formItem').attr('action', '{{ route("gaji.storeDetail", ":id") }}'.replace(':id', gajiId));
});


</script>
@endsection
