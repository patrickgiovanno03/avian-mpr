@extends('layouts.app')

@section('title', 'MPR | Form Gaji')

@section('content')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
            <h1 class="p-0">Gaji</h1>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-end align-items-md-center">
                        <div class="mb-2 mb-md-0">{{ !$mgaji ? 'Upload Foto Slip Gaji' : 'Daftar Slip Gaji ('.Carbon\Carbon::parse($mgaji->Tanggal)->format('d/m/Y').')' }}</div>
                        <div class="btn-group">
                            <a href="{{ route('gaji.index') }}" type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-angle-left mr-lg-2"></i><span class="d-none d-lg-inline">Back</span>
                            </a>
                            @if ($mgaji)
                            {{-- <button type="button" class="btn btn-sm btn-outline-secondary btn-validate" data-gajiid="{{ $mgaji->GajiID }}">
                                <i class="fas fa-check mr-lg-2"></i><span class="d-none d-lg-inline">Send Validate</span>
                            </button> --}}
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-whatsapp-all" data-gajiid="{{ $mgaji->GajiID }}">
                                <i class="fa-brands fa-whatsapp mr-lg-2"></i><span class="d-none d-lg-inline">Blast WhatsApp</span>
                            </button>
                            <button type="button" alt="Assign Photos" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#modalAssignPhotos">
                                <i class="fas fa-money-bill-transfer mr-lg-2"></i><span class="d-none d-lg-inline">Assign Foto ke Pegawai</span>
                            </button>
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
                    <!-- GRID WRAPPER -->
                    <div class="gaji-grid">
                        @foreach($mgaji->hgaji as $gaji)
                        @if($gaji->pegawai)
                        @endif
                        
                        <div class="gaji-card">
                            
                            <!-- IMAGE -->
                            <div class="gaji-img btn-isi-data"
                            data-toggle="modal"
                            data-type="{{ $gaji->PegawaiID ? 'edit':'create' }}"
                            data-target="#modalGaji"
                            data-id="{{ $gaji->HeaderID }}"
                            @if($gaji->PegawaiID)
                                data-data="{{ json_encode($gaji) }}"
                            @endif
                            >
                                <img src="{{ asset('storage/' . $gaji->URL) }}" loading="lazy">
                            </div>

                            <!-- CONTENT -->
                            <div class="gaji-content">

                                <h5>
                                    @if($gaji->pegawai)
                                        {{ $gaji->pegawai->Nama }} 
                                        <span class="total">- {{ $gaji->getTotal() }}k</span>
                                    @else
                                        <span class="text-danger">Belum diisi</span>
                                    @endif
                                </h5>

                                <div class="gaji-actions">
                                    <!-- ROTATE -->
                                    <button 
                                        class="btn btn-info btn-sm btn-rotate"
                                        data-id="{{ $gaji->HeaderID }}">
                                        <i class="fas fa-camera-rotate"></i>
                                    </button>

                                    <!-- PDF -->
                                    @if($gaji->PegawaiID)
                                    <button 
                                        class="btn btn-success btn-sm btn-whatsapp"
                                        data-id="{{ $gaji->HeaderID }}" data-nama="{{ $gaji->pegawai->Nama }}">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </button>
                                    <a 
                                        href="{{ route('gaji.slip',$gaji->HeaderID) }}"
                                        target="_blank"
                                        class="btn btn-avian-primary btn-sm"
                                    >
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    @endif
                                    @if($gaji->URLTF)
                                    {{-- button view bukti tf pakai modal --}}
                                    <button 
                                        class="btn btn-secondary btn-sm btn-view-tf" data-headerid="{{ $gaji->HeaderID }}"
                                        data-url="{{ asset('storage/' . $gaji->URLTF) }}">
                                        <i class="fas fa-money-bill-transfer"></i>
                                    </button>
                                    @endif
                                    <button 
                                        class="btn btn-danger btn-sm btn-delete"
                                        data-id="{{ $gaji->HeaderID }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

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
    <div class="modal-dialog modal-dialog-centered modal-lg" style="min-width:90%">
        <form id="formItem" class="modal-content" method="post" action="{{ route('gaji.storeDetail', $gaji->GajiID ?? 0) }}" autocomplete="off">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="addModalLabel">Form Gaji - {{ $gaji->NamaKaryawan ?? 'Karyawan' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="row">
                    <div class="col-md-6 col-12 mb-3">
                        <div class="text-center">
                            <label class="font-weight-bold d-block mb-2">Foto Slip</label>

                            <div class="image-preview-wrapper mx-auto">
                                <img id="fotoPreview" src="" alt="Preview Foto Slip">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-3">
                        <label>Pegawai</label>
                        <select class="form-control select2 pegawai-select" id="pegawai" name="pegawai" tabindex="1">
                            @foreach($pegawaiList as $pegawai)
                                <option value="{{ $pegawai->PegawaiID }}" data-id="{{ $pegawai->PegawaiID }}" data-pokok="{{ $pegawai->GajiPokok }}" data-lembur="{{ $pegawai->GajiLembur }}">{{ $pegawai->Nama }}</option>
                            @endforeach
                        </select>
                        </div>

                        <!-- Input jumlah hari -->
                        <div class="mb-3">
                        <label>Jumlah Hari Kerja</label>
                        <input style="background-color: #E7F1DC" type="number" step="any" class="form-control" id="jumlah_hari" placeholder="Masukkan jumlah hari" name="jumlahhari" tabindex="2">
                        </div>
                        <hr>

                        <!-- Input gaji pokok & lembur -->
                        <div class="row mb-3">
                            <div class="col-md-6 col-12 mb-2 mb-md-0">
                                <label>Gaji Pokok (per hari)</label>
                                <input type="number" step="any" class="form-control" id="gaji_pokok" placeholder="Masukkan gaji pokok" name="gajipokok" tabindex="3">
                            </div>
                            <div class="col-md-6 col-12">
                                <label>Gaji Lembur (per hari)</label>
                                <input type="number" step="any" class="form-control" id="gaji_lembur" placeholder="Masukkan gaji lembur" name="gajilembur" tabindex="4">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 col-12 mb-2 mb-md-0">
                                <label>Uang Makan</label>
                                <input type="number" step="any" class="form-control" id="uangmakan" placeholder="Masukkan uang makan" name="uangmakan" tabindex="5">
                            </div>
                            <div class="col-md-6 col-12">
                                <label>Bonus</label>
                                <input type="number" step="any" class="form-control" id="bonus" placeholder="Masukkan bonus (opsional)" name="bonus" tabindex="6">
                            </div>
                        </div>

                        <!-- Tabel otomatis muncul -->
                        <div class="table-responsive mb-3">
                        <table class="table table-bordered table-striped align-middle text-center" id="tabelGaji">
                            <thead class="thead-dark">
                            <tr>
                                <th>Hari</th>
                                <th>Tanggal</th>
                                <th>Gaji Pokok</th>
                                <th>Jam Lembur</th>
                                <th>Lembur Total</th>
                                <th>Total Hari Ini</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        </div>

                        <!-- Total akhir -->
                        <div class="mb-3">
                        <label>Total Gaji</label>
                        <input type="number" step="any" class="form-control bg-light" id="total_gaji" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-avian-secondary btn-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Assign Photos -->
<div class="modal fade" id="modalAssignPhotos" tabindex="-1" aria-labelledby="modalAssignPhotosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAssignPhotosLabel">
                    Upload Bukti Transfer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span class="d-none d-sm-inline">Upload beberapa foto dan assign ke pegawai yang sudah ada di daftar slip gaji.</span>
                    <span class="d-inline d-sm-none">Upload foto dan pilih pegawai.</span>
                </div>

                <form id="formAssignPhotos" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="gajiid" value="{{ $mgaji->GajiID ?? 0 }}">

                    <div class="form-group">
                        <label class="font-weight-bold">Upload Foto Slip Gaji</label>
                        <div class="custom-file">
                            <input multiple accept="image/*" type="file" class="custom-file-input" id="assignPhotos" name="photos[]" onchange="previewAssignFiles(event)">
                            <label class="custom-file-label" for="assignPhotos">Pilih beberapa gambar...</label>
                        </div>
                        <small class="text-muted">Format: JPG, PNG, atau JPEG. Anda bisa pilih lebih dari 1 file.</small>
                    </div>

                    <div id="previewAssign" class="mb-3"></div>

                    <div id="assignmentContainer"></div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-avian-primary" id="btnSaveAssignment">
                    </i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalViewTF" tabindex="-1" role="dialog" aria-labelledby="modalViewTFLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalViewTFLabel">Lihat Bukti Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <img src="" class="img-fluid" alt="Bukti Transfer">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" id="btnDeleteTF">
                    <i class="fas fa-trash mr-2"></i>Hapus Bukti Transfer
                </button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

<div id="slip-jpg" style="min-width: 900px; min-height:1100px; max-width: 900px"></div>
<style>
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
        -webkit-tap-highlight-color: transparent;
    }

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

    /* Prevent horizontal scroll on mobile */
    body {
        overflow-x: hidden;
    }

    .container-fluid {
        overflow-x: hidden;
    }

    /* Better touch targets for mobile */
    @media (max-width: 768px) {
        button, .btn, a.btn {
            min-height: 36px;
            touch-action: manipulation;
        }

        .gaji-actions .btn {
            min-width: 36px;
            min-height: 36px;
        }

        /* Hide slip-jpg on mobile */
        #slip-jpg {
            display: none !important;
        }
    }

    .image-preview-wrapper {
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #ddd;
        box-shadow: 0px 3px 10px rgba(0,0,0,0.1);
        background: #f7f7f7;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-preview-wrapper img {
        max-width: 100%;
        object-fit: contain;
    }

    /* ===== GRID SYSTEM ===== */
    .gaji-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        padding: 10px;
    }

    /* ===== CARD (Glass Effect) ===== */
    .gaji-card {
        backdrop-filter: blur(15px) saturate(180%);
        -webkit-backdrop-filter: blur(15px) saturate(180%);
        background: rgba(255, 255, 255, 0.18);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, .25);

        overflow: hidden;
        box-shadow: 0px 8px 30px rgba(0,0,0,.15);
        transition: transform .3s ease, box-shadow .3s ease;
        cursor: pointer;
    }

    .gaji-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0px 20px 40px rgba(0,0,0,.20);
    }

    /* ===== IMAGE ===== */
    .gaji-img {
        width: 100%;
        max-height: 180px;
        overflow: hidden;
    }

    .gaji-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .5s ease;
    }

    .gaji-card:hover .gaji-img img {
        transform: scale(1.08);
    }

    /* ===== CONTENT ===== */
    .gaji-content {
        padding: 18px;
        text-align: center;
    }

    .gaji-content .total {
        color: #ecaa0f;
    }

    /* ===== BUTTONS ===== */
    .gaji-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .gaji-actions .btn {
        border-radius: 10px;
        padding: 6px 12px;
    }

    /* Assign Photos Preview */
    #previewAssign .preview-item {
        position: relative;
        display: inline-block;
        margin: 10px;
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    #previewAssign .preview-item img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .assignment-row {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .assignment-row img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #ddd;
    }

</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
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
function reorderTabIndex() {
        const columns = [
            'pokok',
            'jam',
            'lembur',
            'total'
        ];

        let tabIndex = 1;

        columns.forEach(selector => {
            // Loop per kolom (bukan per baris)
            $('#tabelGaji').find('.' + selector).each(function() {
                $(this).attr('tabindex', tabIndex++);
            });
        });
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
                date.setDate(date.getDate() - (9 - (i <= 2 ? i : (i+1)))); // ← ini 8 - i

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
        reorderTabIndex();
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
        var countDayLembur = 0;
        tabelBody.querySelectorAll('tr').forEach(row => {
            const jam = parseFloat(row.querySelector('.jam').value) || 0;
            if (jam > 0) {
                countDayLembur++;
            }
        });
        $('#uangmakan').val(countDayLembur * 10);
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
        dropdownParent: $('#modalGaji'), // penting kalau di modal
        placeholder: 'Pilih Pegawai...',
        theme: 'bootstrap4',
    });
});

$('.pegawai-select').on('select2:select', function (e) {
    var selected = $(this).find(':selected');

    var gajiPokok = selected.data('pokok') || 0;
    var gajiLembur = selected.data('lembur') || 0;

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
    var imgSrc = $(this).closest('.gaji-card').find('img').attr('src');
    console.log($(this).data('type'));
    $('#fotoPreview').attr('src', imgSrc);
    console.log( $(this).closest('.gaji-card').find('img').attr('src'));

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
                <td style="padding-top: 5px; padding-bottom: 5px">${index + 1}</td>
                <td style="padding-top: 5px; padding-bottom: 5px"><input type="text" name="tanggal[]" class="form-control tanggal datepicker" value="${(item.Tanggal.split('-').reverse().join('/')) || ''}"></td>
                <td style="padding-top: 5px; padding-bottom: 5px"><input type="number" step="any" name="pokok[]" class="form-control text-end pokok" value="${item.Pokok}"></td>
                <td style="padding-top: 5px; padding-bottom: 5px"><input type="number" step="any" name="jam[]" class="form-control text-end jam" value="${item.Jam}" min="0" placeholder="Jam lembur"></td>
                <td style="padding-top: 5px; padding-bottom: 5px"><input type="number" step="any" name="lembur[]" class="form-control text-end lembur"value="${Number(item.Lembur || 0).toFixed(2)}" readonly></td>
                <td style="padding-top: 5px; padding-bottom: 5px"><input type="number" step="any" class="form-control text-end total" value="${Number((item.Pokok + item.Lembur) || 0).toFixed(1) ?? 0}" readonly></td>
            </tr>`;
            $('#tabelGaji tbody').append(row);
        });
        $('.tanggal').datepicker({
            dateFormat: 'dd/mm/yy',
        });
        hitungTotal();
        reorderTabIndex();
    } else {
        // reset form
        $('.modal-title').text('Form Gaji');
        $('#pegawai').val(null).trigger('change');
    }
});

$('.btn-rotate').on('click', function() {
    var gajiId = $(this).data('id');
    var img = $(this).closest('.gaji-card').find('img');
    $.ajax({
        url: '{{ route("gaji.rotateImage") }}',
        type: 'POST',
        data: {
            headerid: gajiId
        },
        success: function(response) {
            let newSrc = img.attr('src').split('?')[0] + '?v=' + new Date().getTime();

            img.attr('src', newSrc);
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan saat memutar gambar.',
                text: xhr.responseText
            });
        }
    });
});

$('.btn-view-tf').on('click', function() {
    var url = $(this).data('url');
    var headerid = $(this).data('headerid');
    $('#btnDeleteTF').data('headerid', headerid);
    $('#modalViewTF img').attr('src', url);
    $('#modalViewTF').modal('show');
});

$('.btn-delete').on('click', function() {
    var gajiId = $(this).data('id');
    var card = $(this).closest('.gaji-card');
    Swal.fire({
        title: 'Apakah anda yakin untuk menghapus data?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#6c757d',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '{{ route("gaji.deleteDetail") }}',
                type: 'POST',
                data: {
                    headerid: gajiId
                },
                success: function(response) {
                    card.remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Data berhasil dihapus.'
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat menghapus data.',
                        text: xhr.responseText
                    });
                }
            });
        } else {
            return false;
        }
    });
});

$('.btn-validate').on('click', function() {
    var gajiId = $(this).data('gajiid');
    Swal.fire({
        title: 'Apakah anda yakin untuk mengirim data ke validasi?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#6c757d',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '{{ route("gaji.sendValidate") }}',
                type: 'POST',
                data: {
                    gajiid: gajiId
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data berhasil dikirim untuk divalidasi.'
                    })
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat mengirim data ke validasi.',
                        text: xhr.responseText
                    });
                }
            });
        } else {
            return false;
        }
    });
});

$('#modalGaji').on('shown.bs.modal', function () {
    // Disable draggable on mobile devices
    if ($(window).width() > 768) {
        $(this).find('.modal-dialog').draggable({
            handle: ".modal-header"
        });
    }
    
    $('#pegawai').next('.select2-container')
    .find('.select2-selection')
    .css('background-color', '#E7F1DC');
});

$('#modalGaji').on('change', '.tanggal', function() {
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

// Download PNG
$('.btn-whatsapp').on('click', function (e, isAlert = true) {
    var nama = $(this).data('nama');
toastr.success('Gaji ' + nama + ' berhasil dikirim.');
    console.log('isAlert:', isAlert);
    var id = $(this).data('id');
    var nama = $(this).data('nama');
    $.ajax({
                url: '{{ route("gaji.getData") }}',
                type: 'GET',
                data: {
                    headerid: id
                },
                success: function(response) {
                    console.log(response);
                    $('#slip-jpg').html(response.html);
                    $('#slip-jpg').removeClass('d-none');
                    const el = document.getElementById('slip-jpg');

                    html2canvas(el, {
                        scale: 2,               // KUNCI BIAR TAJAM
                        useCORS: true,
                        backgroundColor: '#ffffff',
                        windowWidth: el.scrollWidth,
                        windowHeight: el.scrollHeight
                    }).then(canvas => {
                        console.log(canvas);
                        // var link = document.createElement('a');
                        // link.href = canvas.toDataURL('image/png');
                        // link.download = 'slip-gaji.png';
                        // link.click();
                        $('#slip-jpg').addClass('d-none');
                        $.ajax({
                            url: '{{ route("gaji.sendWA") }}',
                            type: 'POST',
                            data: {
                                image: canvas.toDataURL('image/jpeg', 0.85),
                                hgaji: response.hgaji,
                            },
                            success: function(response) {
                                if (!isAlert) {
                                    return;
                                }
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Gambar PNG berhasil dikirim.',
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi kesalahan saat mengirim gambar.',
                                    text: xhr.responseText
                                });
                            }
                        });
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat mengambil data slip gaji.',
                        text: xhr.responseText
                    });
                }
            });
            return;
    Swal.fire({
        title: 'Apakah anda yakin untuk mengirim ke whatsapp?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#6c757d',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '{{ route("gaji.getData") }}',
                type: 'GET',
                data: {
                    headerid: id
                },
                success: function(response) {
                    console.log(response);
                    $('#slip-jpg').html(response.html);
                    $('#slip-jpg').removeClass('d-none');
                    const el = document.getElementById('slip-jpg');

                    html2canvas(el, {
                        scale: 2,               // KUNCI BIAR TAJAM
                        useCORS: true,
                        backgroundColor: '#ffffff',
                        windowWidth: el.scrollWidth,
                        windowHeight: el.scrollHeight
                    }).then(canvas => {
                        console.log(canvas);
                        // var link = document.createElement('a');
                        // link.href = canvas.toDataURL('image/png');
                        // link.download = 'slip-gaji.png';
                        // link.click();
                        $('#slip-jpg').addClass('d-none');
                        $.ajax({
                            url: '{{ route("gaji.sendWA") }}',
                            type: 'POST',
                            data: {
                                image: canvas.toDataURL('image/jpeg', 0.85),
                                hgaji: response.hgaji,
                            },
                            success: function(response) {
                                if (!isAlert) {
                                    return;
                                }
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Gambar PNG berhasil dikirim.',
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi kesalahan saat mengirim gambar.',
                                    text: xhr.responseText
                                });
                            }
                        });
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat mengambil data slip gaji.',
                        text: xhr.responseText
                    });
                }
            });
        } else {
            return false;
        }
    });
});

$('.btn-whatsapp-all').on('click', function () {
    Swal.fire({
        title: 'Apakah anda yakin untuk mengirim ke whatsapp semua karyawan?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#6c757d',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.value) {
            const buttons = $('.btn-whatsapp'); // ambil semua tombol
            let index = 0;

            Swal.fire({
                title: 'Kirim Slip Gaji',
                text: 'Proses pengiriman sedang berjalan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            function sendNext() {
                if (index < buttons.length) {
                    // trigger click TANPA alert per item
                    $(buttons[index]).trigger('click', [false]);

                    index++;

                    // delay antar kirim (WA API perlu ini)
                    setTimeout(sendNext, (Math.random() * 5000) + 10000); // delay acak antara 1-3 detik
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Selesai',
                        text: 'Semua slip gaji berhasil diproses.'
                    });
                }
            }

            sendNext();
        } else {
            return false;
        }
    });
});

// Preview files untuk modal assign photos
@if ($mgaji)
function previewAssignFiles(event) {
    const preview = document.getElementById('previewAssign');
    const container = document.getElementById('assignmentContainer');
    preview.innerHTML = '';
    container.innerHTML = '';
    
    const files = event.target.files;
    const pegawaiList = @json(
        $mgaji->hgaji
            ->map(function($g) {
                return $g->pegawai ? [
                    'id' => $g->pegawai->PegawaiID,
                    'nama' => $g->pegawai->Nama,
                    'headerid' => $g->HeaderID
                ] : null;
            })
            ->filter()
            ->values()
    );
    
    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            // Create assignment row
            const row = document.createElement('div');
            row.className = 'assignment-row';
            row.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <img src="${e.target.result}" class="img-thumbnail">
                        <p class="mt-2 mb-0"><small class="text-muted">Foto ${index + 1}</small></p>
                    </div>
                    <div class="col-md-9">
                        <label class="font-weight-bold">Pilih Pegawai untuk Foto ${index + 1}</label>
                        <select class="form-control select2-assign" name="pegawai_assign[${index}]" data-index="${index}" required>
                            <option value="">-- Pilih Pegawai --</option>
                            ${pegawaiList.map((p, i) => `
                                <option value="${p.id}" data-headerid="${p.headerid}" ${i === index ? 'selected' : ''}>
                                    ${p.nama}
                                </option>
                            `).join('')}
                        </select>
                    </div>
                </div>
            `;
            container.appendChild(row);
            
            // Initialize select2 for this new select
            $(row).find('.select2-assign').select2({
                placeholder: 'Pilih Pegawai...',
                theme: 'bootstrap4',
                dropdownParent: $('#modalAssignPhotos')
            });
        };
        reader.readAsDataURL(file);
    });
}

// Save assignment
$('#btnSaveAssignment').on('click', function() {
    const form = $('#formAssignPhotos')[0];
    const formData = new FormData(form);
    
    // Validate all selects have values
    let isValid = true;
    $('.select2-assign').each(function() {
        if (!$(this).val()) {
            isValid = false;
            $(this).next('.select2-container').find('.select2-selection').css('border-color', 'red');
        } else {
            $(this).next('.select2-container').find('.select2-selection').css('border-color', '');
        }
    });
    
    if (!isValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Silakan pilih pegawai untuk semua foto yang diupload.'
        });
        return;
    }
    
    // Add header IDs
    $('.select2-assign').each(function(index) {
        const headerID = $(this).find(':selected').data('headerid');
        formData.append('header_ids[' + index + ']', headerID);
    });
    
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: 'Foto akan di-assign ke pegawai yang dipilih.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '{{ route("gaji.assignPhotos") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Foto berhasil di-assign ke pegawai.',
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan',
                        text: xhr.responseJSON?.message || 'Gagal menyimpan data.'
                    });
                }
            });
        }
    });
});
@endif

// Reset modal when closed
$('#modalAssignPhotos').on('hidden.bs.modal', function() {
    $('#formAssignPhotos')[0].reset();
    $('#previewAssign').html('');
    $('#assignmentContainer').html('');
    $('#assignPhotos').next('.custom-file-label').text('Pilih beberapa gambar...');
});

// Update file label
$('#assignPhotos').on('change', function() {
    const fileCount = this.files.length;
    const label = fileCount > 1 ? fileCount + ' file dipilih' : (fileCount === 1 ? this.files[0].name : 'Pilih beberapa gambar...');
    $(this).next('.custom-file-label').text(label);
});


$('#btnDeleteTF').on('click', function() {
    var headerid = $(this).data('headerid');
    Swal.fire({
        title: 'Apakah anda yakin untuk menghapus bukti transfer?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#6c757d',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '{{ route("gaji.deleteTF") }}',
                type: 'POST',
                data: {
                    headerid: headerid
                },
                success: function(response) {
                    $('#modalViewTF').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Bukti transfer berhasil dihapus.'
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat menghapus bukti transfer.',
                        text: xhr.responseText
                    });
                }
            });
        } else {
            return false;
        }
    });
});

</script>
@endsection
