@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
            <h1 class="p-0">Gaji</h1>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <div>{{ !$mgaji ? 'Upload Foto Slip Gaji' : 'Daftar Slip Gaji ('.Carbon\Carbon::parse($mgaji->Tanggal)->format('d/m/Y').')' }}</div>
                        <div class="btn-group">
                            <a href="{{ route('gaji.index') }}" type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-angle-left mr-lg-2"></i><span class="d-none d-lg-inline">Back</span>
                            </a>
                            @if ($mgaji)
                            <a class="btn btn-sm btn-info" href="{{ route('gaji.show', $mgaji->GajiID) }}"><i class="fas fa-upload mr-lg-2"></i><span class="d-none d-lg-inline">Upload Slip Gaji</span></a>
                            <a class="btn btn-warning btn-sm shadow-sm" href="{{ route('gaji.slipAll', $mgaji->GajiID) }}" target="_blank">
                                <i class="fas fa-download mr-lg-2"></i><span class="d-none d-lg-inline">Download Semua Slip</span>
                            </a>
                            @endif
                            {{-- <button type="submit" class="btn btn-sm btn-avian-secondary btn-submit"><i class="fas fa-save mr-2"></i>Save</button> --}}
                        </div>
                    </div>
                </div>
                <div class="card-body row">
                @if (!$mgaji || $upload)
                <!-- ====================== UPLOAD FORM ====================== -->
                <div class="mx-auto">
                        <form id="formInput" method="post" action="{{ route('gaji.upload') }}" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                        <input type="hidden" id="gajiid" name="gajiid" value="{{ $mgaji->GajiID ?? 0 }}">
                        <div class="form-group row">
                            <label for="tanggal" class="form-label col-md-3">Tanggal Gaji</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datepicker" id="tanggal" name="tanggal" {{ $upload ? 'disabled' : '' }}
                                    value="{{ $mgaji ? Carbon\Carbon::parse($mgaji->Tanggal)->format('d/m/Y') : Carbon\Carbon::now()->format('d/m/Y') }}">
                            </div>
                        </div>
                      <div class="card shadow-lg border-0">
                        <div class="card-body text-center p-5">
                            <h2 class="fw-bold mb-4 text-danger">
                                <i class="fas fa-upload me-2"></i>Upload Slip Gaji {{  $upload ? "Tambahan" : '' }}
                            </h2>
                            <p class="text-muted mb-4">
                                Unggah foto slip gaji untuk diproses. Kamu bisa memilih lebih dari satu file sekaligus.
                            </p>


                                <div class="mb-4">
                                    <label for="photos" class="form-label fw-semibold text-secondary">Pilih Gambar Slip Gaji</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                      </div>
                                      <div class="custom-file">
                                        <input multiple accept="image/*" type="file" class="custom-file-input" id="photos" aria-describedby="inputGroupFileAddon01" name="photos[]" onchange="previewFiles(event)">
                                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                      </div>
                                    </div>
                                    {{-- <input class="form-control" type="file" id="formFileDisabled" disabled> --}}
                                    <small class="text-muted d-block mt-2">Format: JPG, PNG, atau JPEG</small>
                                </div>

                                <div id="preview" class="d-flex flex-wrap justify-content-center gap-3 mb-4"></div>

                                <button type="submit" class="btn btn-avian-primary btn-lg px-5">
                                    <i class="fas fa-upload mr-2"></i>Upload
                                </button>
                        </div>
                    </div>
                </form>
                    </div>
                    @else
                    <!-- ====================== DAFTAR SLIP GAJI ====================== -->
                      <div class="row g-4">
                          @foreach($mgaji->hgaji as $gaji)
                          <div class="col-md-6 col-xl-4">
                              <div class="card border-0 shadow-sm h-100 hover-lift">
                                  <div class="position-relative">
                                      <img src="{{ asset('storage/' . $gaji->URL) }}" class="card-img-top rounded-top" alt="Slip Gaji">
                                  </div>
                                  <div class="card-body text-center">
                                      <h5 class="fw-bold mb-1 text-muted">{{ $gaji->pegawai->Nama ?? 'Belum diisi' }}</h5>
                                      <p class="text-muted small mb-0"></p>
                                      <div class="">
                                        @if($gaji->PegawaiID != null)
                                            <button class="btn btn-light btn-sm me-2 btn-isi-data" data-toggle="modal" data-type="edit" data-target="#modalGaji" data-id="{{ $gaji->HeaderID }}" data-data="{{ json_encode($gaji) }}">
                                                <i class="fas fa-edit mr-2"></i>Edit Data
                                            </button>
                                            <a href="{{ route('gaji.slip', $gaji->HeaderID) }}" target="_blank" class="btn btn-avian-secondary btn-sm">
                                                <i class="fas fa-file-pdf mr-2"></i>Slip PDF
                                            </a>
                                        @else
                                            <button class="btn btn-light btn-sm me-2 btn-isi-data" data-toggle="modal" data-type="create" data-target="#modalGaji" data-id="{{ $gaji->HeaderID }}">
                                                <i class="fas fa-edit mr-2"></i>Isi Data
                                            </button>
                                        @endif
                                      </div>
                                  </div>
                              </div>
                          </div>
                          @endforeach
                      </div>
                      @endif
                </div>
            </div>
      </div>
    </div>
</div>
<!-- Modal Form Gaji -->
<div class="modal fade modal-draggable" id="modalGaji" data-backdrop="false" data-keyboard="false" aria-labelledby="modalGajiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <form id="formItem" class="modal-content" method="post" action="{{ route('gaji.storeDetail', $gaji->GajiID ?? 0) }}" autocomplete="off">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="addModalLabel">Form Gaji - {{ $gaji->NamaKaryawan ?? 'Karyawan' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                          <th>Tanggal</th>
                          <th>Gaji Pokok</th>
                          <th>Jam Lembur</th>
                          <th>Gaji Lembur Total</th>
                          <th>Total Hari Ini</th>
                      </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
                  </div>

                <!-- Input bonus -->
                <div class="mb-3">
                  <label>Uang Makan</label>
                  <input type="number" step="any" class="form-control" id="uangmakan" placeholder="Masukkan uang makan" name="uangmakan">
                </div>
                <div class="mb-3">
                  <label>Bonus</label>
                  <input type="number" step="any" class="form-control" id="bonus" placeholder="Masukkan bonus (opsional)" name="bonus">
                </div>

                <!-- Total akhir -->
                <div class="mb-3">
                  <label>Total Gaji</label>
                  <input type="number" step="any" class="form-control bg-light" id="total_gaji" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-avian-secondary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<style>
    /* Upload dashed input hover */
    input[type="file"]:hover {
        border-color: #6610f2;
    }

    /* Overlay tombol di atas gambar */
    .card:hover .overlay {
        opacity: 1;
    }

    /* Preview container */
    #preview img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    
    body.modal-open {
        overflow: auto !important;     /* supaya halaman bisa scroll */
        padding-right: 0 !important;
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

function previewFiles(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    const files = event.target.files;
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
$(document).ready(function () {
    $('.select2').css('width', '100%');

    $('#formInput').on('submit', function(e) {
        e.preventDefault();

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

const inputGajiPokok = document.getElementById(`gaji_pokok`);
const inputGajiLembur = document.getElementById(`gaji_lembur`);
const inputJumlahHari = document.getElementById(`jumlah_hari`);
const inputBonus = document.getElementById(`bonus`);
const inputMakan = document.getElementById(`uangmakan`);
const tabelBody = document.querySelector(`#tabelGaji tbody`);
const inputTotal = document.getElementById(`total_gaji`);

// Hitung total keseluruhan
function hitungTotal() {
    let total = 0;
    tabelBody.querySelectorAll('tr').forEach(row => {
        const pokok = parseFloat(row.querySelector('.pokok').value) || 0;
        const lembur = parseFloat(row.querySelector('.lembur').value) || 0;
        const subtotal = pokok + lembur;
        row.querySelector('.total').value = subtotal.toFixed(1);
        total += subtotal;
    });

    const bonus = parseFloat(inputBonus.value) || 0;
    const makan = parseFloat(inputMakan.value) || 0;
    inputTotal.value = (total + bonus + makan).toFixed(1);
}
document.addEventListener('DOMContentLoaded', function () {
    // Generate tabel otomatis saat jumlah hari berubah
    @if ($mgaji == null)
    return;
    @endif
    inputJumlahHari.addEventListener('input', function() {
        const jml = parseInt(this.value) || 0;
        tabelBody.innerHTML = '';
        if (jml > 0) {
            for (let i = 1; i <= jml; i++) {

                // Hitung tanggal dengan JS
                const date = new Date("{{ \Carbon\Carbon::parse($mgaji->Tanggal??'')->format('Y-m-d') }}");
                date.setDate(date.getDate() - (8 - (i <= 2 ? i : (i+1)))); // ← ini 8 - i

                const formatted = date.toLocaleDateString('id-ID'); // format dd/mm/yyyy

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${i}</td>
                    <td><input type="text" name="tanggal[]" class="form-control tanggal datepicker" value="${formatted}"></td>
                    <td><input type="number" step="any" name="pokok[]" class="form-control text-end pokok" value="${inputGajiPokok.value || 0}"></td>
                    <td><input type="number" step="any" name="jam[]" class="form-control text-end jam" value="0" min="0" placeholder="Jam lembur"></td>
                    <td><input type="number" step="any" name="lembur[]" class="form-control text-end lembur" value="0" readonly></td>
                    <td><input type="number" step="any" class="form-control text-end total" readonly></td>
                `;
                tabelBody.appendChild(row);
            }
        }

        // Inisialisasi datepicker untuk input tanggal
        $('.tanggal').datepicker({
            dateFormat: 'dd/mm/yy',
        });
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
    inputMakan.addEventListener('input', hitungTotal);
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
    var type = $(this).data('type');

    $('#formItem')[0].reset();
    $('#tabelGaji tbody').empty();
    $('#total_gaji').val('');
    $('#formItem').attr('action', '{{ route("gaji.storeDetail", ":id") }}'.replace(':id', gajiId));
    if (type == 'edit') {
        // isi dengan data
        var dataGaji = $(this).data('data');
        console.log(dataGaji);
        $('.modal-title').text('Form Gaji - ' + (dataGaji.pegawai ? dataGaji.pegawai.Nama : 'Karyawan'));
        $('#pegawai').append(new Option(dataGaji.pegawai.Nama, dataGaji.PegawaiID, true, true)).trigger('change');
        $('#gaji_pokok').val(dataGaji.dgaji.length > 0 ? dataGaji.dgaji[0].Pokok : 0);
        $('#gaji_lembur').val(dataGaji.pegawai ? dataGaji.pegawai.GajiLembur : 0);
        $('#jumlah_hari').val(dataGaji.dgaji.length);
        $('#bonus').val(dataGaji.Bonus);
        $('#uangmakan').val(dataGaji.UangMakan);

        hitungTotal();

        // generate tabel
        dataGaji.dgaji.forEach(function(item, index) {
            var row = `<tr>
                <td>${index + 1}</td>
                <td><input type="text" name="tanggal[]" class="form-control tanggal datepicker" value="${(item.Tanggal.split('-').reverse().join('/')) || ''}"></td>
                <td><input type="number" step="any" name="pokok[]" class="form-control text-end pokok" value="${item.Pokok}"></td>
                <td><input type="number" step="any" name="jam[]" class="form-control text-end jam" value="${item.Jam}" min="0" placeholder="Jam lembur"></td>
                <td><input type="number" step="any" name="lembur[]" class="form-control text-end lembur" value="${item.Lembur.toFixed(2)}" readonly></td>
                <td><input type="number" step="any" class="form-control text-end total" value="${(item.Pokok + item.Lembur).toFixed(1)}" readonly></td>
            </tr>`;
            $('#tabelGaji tbody').append(row);
        });
        $('.tanggal').datepicker({
            dateFormat: 'dd/mm/yy',
        });
        hitungTotal();
    }
});

$('#modalGaji').on('shown.bs.modal', function () {
    $(this).find('.modal-dialog').draggable({
        handle: ".modal-header"
    });

    $('.tanggal').on('change', function() {
        // ganti input tanggal di baris berikutnya saja
        const rows = $('#tabelGaji tbody tr');;
        let thisRow = $(this);
        var passedIndex = -1;
        rows.each(function(index) {
            if ($(this).find('.tanggal')[0] === thisRow[0] || passedIndex != -1) {
                if (index + 1 < rows.length) {
                    const nextRow = $(rows[index + 1]);
                    const currentDate = new Date(thisRow.val().split('/').reverse().join('-'));
                    currentDate.setDate(currentDate.getDate() + 1 + (passedIndex != -1 ? (index - passedIndex) : 0));
                    const formatted = currentDate.toLocaleDateString('id-ID');
                    nextRow.find('.tanggal').val(formatted);
                    passedIndex = (passedIndex != -1) ? passedIndex : index;
                }
            }
        });
    });
});



</script>
@endsection
