@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <form id="formInput" class="col-md-12" method="post" action="{{ ($invoice == null || ($isDuplicate ?? null)) ? route('invoice.store') : route('invoice.update', $invoice->FormID) }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @if($invoice != null && !($isDuplicate ?? null))
                @method('PUT')
            @endif
            <h1 class="p-0">Form</h1>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <div>{{ ($isDuplicate ?? null) ? 'Duplicate' : ($invoice != null ? 'Edit' : 'Create') }} Form</div>
                        <div class="btn-group">
                            <a href="{{ route('invoice.index') }}" type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-angle-left mr-lg-2"></i><span class="d-none d-lg-inline">Back</span>
                            </a>
                            {{-- <button type="button" class="btn btn-sm btn-outline-secondary btn-excel">
                                <i class="fas fa-file-excel mr-2"></i>Excel
                            </button> --}}
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-preview">
                                <i class="fas fa-eye-slash mr-2"></i>Hide Preview
                            </button>
                            @if($invoice != null)
                            <a target="_blank" href="{{ route('invoice.previewdynamic', $invoice->FormID) }}" type="button" class="btn btn-sm btn-outline-secondary btn-pdf">
                                <i class="fas fa-file-pdf mr-lg-2"></i><span class="d-none d-lg-inline">Save & PDF</span>
                            </a>
                            @endif
                            <button type="submit" class="btn btn-sm btn-avian-secondary btn-submit"><i class="fas fa-save mr-2"></i>Save</button>
                        </div>
                    </div>
                </div>
                <div class="card-body row">
                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br />
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="col-md-6 order-2 order-md-1" id="iframe-container">
                        <iframe
                            id="print_preview"
                            src=""
                            style="width: 90%; height: 800px; background-color: #eee;"
                        ></iframe>
                        <br>
                        <span class="text-danger">* Hasil preview mungkin tidak sepenuhnya akurat. Silakan buka file PDF untuk memastikan tampilan sebenarnya.</span>
                        {{-- <embed 
                            id="pdfPreview"
                            src="{{ route('invoice.previewdynamic', $hbacara->BAcaraID) }}" 
                            type="application/pdf" 
                            width="90%" 
                            height="900px" /> --}}
                    </div>
                    <div class="col-md-6 order-1 order-md-2" id="form-container">
                        <input type="hidden" id="toPDF" name="toPDF" value="0">
                        <div class="form-group row">
							<label for="" class="col-sm-3 col-form-label">Tipe Form</label>
							<div class="col-sm-9 d-flex flex-row flex-wrap align-items-center">
								<div class="form-check pr-4">
									<input class="form-check-input form-type-checkbox" type="checkbox" name="IsInvoice" id="IsInvoice" value="IsInvoice" {{ $invoice != null ? ($invoice->InvoiceNo == null ? "" : "checked") : "checked" }}>
									<label class="form-check-label" for="IsInvoice">
										Invoice
									</label>
								</div>
								<div class="form-check pr-4">
									<input class="form-check-input form-type-checkbox" type="checkbox" name="IsSJ" id="IsSJ" value="IsSJ"{{ $invoice != null ? ($invoice->SJNo == null ? "" : "checked") : "checked" }} >
									<label class="form-check-label" for="IsSJ">
										Surat Jalan
									</label>
								</div>
							</div>
						</div>
                        <div class="form-group row invoice-container {{ $invoice != null ? ($invoice->InvoiceNo == null ? 'd-none' : '') : '' }}">
                            <label for="invoiceno" class="form-label col-md-3">Invoice No.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="invoiceno" name="invoiceno"
                                    value="{{ ($isDuplicate ?? null) ? app(\App\Models\HInvoice::class)->getNewInvoiceNo() : ($invoice->InvoiceNo ?? app(\App\Models\HInvoice::class)->getNewInvoiceNo()) }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row sj-container {{ $invoice != null ? ($invoice->SJNo == null ? 'd-none' : '') : '' }}">
                            <label for="sjno" class="form-label col-md-3">Surat Jalan No.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="sjno" name="sjno"
                                    value="{{ ($isDuplicate ?? null) ? app(\App\Models\HInvoice::class)->getNewSJNo() : ($invoice->SJNo ?? app(\App\Models\HInvoice::class)->getNewSJNo()) }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="invoicedate" class="form-label col-md-3">Tanggal Invoice</label>
                            <div class="col-md-9">
                                <input style="background-color: #E7F1DC" type="text" class="form-control datepicker" id="invoicedate" name="invoicedate"
                                    value="{{ $invoice != null ? Carbon\Carbon::parse($invoice->InvoiceDate)->format('d/m/Y') : Carbon\Carbon::now()->format('d/m/Y') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sjdate" class="form-label col-md-3">Tanggal SJ</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datepicker" id="sjdate" name="sjdate"
                                    value="{{ $invoice != null ? Carbon\Carbon::parse($invoice->SJDate)->format('d/m/Y') : Carbon\Carbon::now()->format('d/m/Y') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="kode" class="form-label col-md-3">Kode</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="kode" name="kode"
                                    value="{{ $invoice->Kode ?? '' }}">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="namacustomer" class="form-label col-md-3">Nama Customer</label>
                            <div class="col-md-9">
                                <select style="background-color: #ffcccc" class="form-control select2-tags" id="namacustomer" name="namacustomer">
                                    <option value="">Pilih Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer }}" {{ (isset($invoice) && $invoice->NamaCustomer == $customer) ? 'selected' : '' }}>
                                            {{ $customer }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamatcustomer" class="form-label col-md-3">Alamat Customer</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="alamatcustomer" name="alamatcustomer">{{ $invoice->AlamatCustomer ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telpcustomer" class="form-label col-md-3">Telp Customer</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="telpcustomer" name="telpcustomer"
                                    value="{{ $invoice->TelpCustomer ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="categorycustomer" class="form-label col-md-3">Kategori Customer</label>
                            <div class="col-md-9">
                                <select class="form-control select2-tags" id="categorycustomer" name="categorycustomer">
                                    <option {{ (isset($invoice) && $invoice->PriceCategory == 1) ? 'selected' : '' }} value="1">Konsumen</option>
                                    <option {{ (isset($invoice) && $invoice->PriceCategory == 2) ? 'selected' : '' }} value="2">Supplier-1</option>
                                    <option {{ (isset($invoice) && $invoice->PriceCategory == 3) ? 'selected' : '' }} value="3">Supplier-2</option>
                                    <option {{ (isset($invoice) && $invoice->PriceCategory == 4) ? 'selected' : '' }} value="4">Distributor</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="text-dark text-bold" data-toggle="collapse" href="#collapse1">Options</a>
                            </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                            <ul class="list-group">
                                <li class="list-group-item border-0">
                                <div class="form-group row">
                                    <div class="col-sm-9 d-flex flex-row flex-wrap align-items-center">
                                        <div class="form-check pr-4">
                                            <input class="form-check-input form-type-checkbox" type="checkbox" name="IsKonsinyasi" id="IsKonsinyasi" value="IsKonsinyasi" {{ $invoice != null ? ((($invoice->IsKonsinyasi ?? 0) == 0) ? "" : "checked") : "" }}>
                                            <label class="form-check-label" for="IsKonsinyasi">
                                                Konsinyasi
                                            </label>
                                        </div>
                                        <div class="form-check pr-4">
                                            <input class="form-check-input form-type-checkbox" type="checkbox" name="IsEkspedisi" id="IsEkspedisi" value="IsEkspedisi" {{ $invoice != null ? ((($invoice->IsEkspedisi ?? 0) == 0) ? "" : "checked") : "" }}>
                                            <label class="form-check-label" for="IsEkspedisi">
                                                Ekspedisi
                                            </label>
                                        </div>
                                        <div class="form-check pr-4">
                                            <input class="form-check-input form-type-checkbox" type="checkbox" name="IsKopSurat" id="IsKopSurat" value="IsKopSurat" {{ $invoice != null ? ((($invoice->IsKopSurat ?? 0) == 0) ? "" : "checked") : "checked" }}>
                                            <label class="form-check-label" for="IsKopSurat">
                                                Kop Surat (SJ)
                                            </label>
                                        </div>
                                        <div class="form-check pr-4">
                                            <input class="form-check-input form-type-checkbox" type="checkbox" name="IsSJCustomer" id="IsSJCustomer" value="IsSJCustomer" {{ $invoice != null ? ((($invoice->IsSJCustomer ?? 0) == 0) ? "" : "checked") : "checked" }}>
                                            <label class="form-check-label" for="IsSJCustomer">
                                                SJ Customer
                                            </label>
                                        </div>
                                        <div class="form-check pr-4">
                                            <input class="form-check-input form-type-checkbox" type="checkbox" name="IsLarge" id="IsLarge" value="IsLarge" {{ $invoice != null ? ((($invoice->IsLarge ?? 0) == 0) ? "" : "checked") : "" }}>
                                            <label class="form-check-label" for="IsLarge">
                                                Uk. Besar
                                            </label>
                                        </div>
                                        <div class="form-check pr-4">
                                            <input class="form-check-input form-type-checkbox" type="checkbox" name="IsDiscount" id="IsDiscount" value="IsDiscount" {{ $invoice != null ? ((($invoice->IsDiscount ?? 0) == 0) ? "" : "checked") : "" }}>
                                            <label class="form-check-label" for="IsDiscount">
                                                Discount
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kode" class="form-label col-md-3">Jatuh Tempo</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input type="text" class="numeric form-control" id="JatuhTempo" name="JatuhTempo"
                                                value="{{ $invoice != null ? ($invoice->JatuhTempo ?? '0') : '0' }}">                                
                                                <div class="input-group-append">
                                                <span class="input-group-text p-0 border-0">
                                                    <div style="min-width: 100px;">
                                                        <select name="JatuhTempoSatuan" id="JatuhTempoSatuan" class="form-control select2" required>
                                                            <option {{ ($invoice != null ? ($invoice->JatuhTempoSatuan == "1") ? 'selected' : '' : '') }} value="1">Hari</option>
                                                            <option {{ ($invoice != null ? ($invoice->JatuhTempoSatuan == "2") ? 'selected' : '' : '') }} value="2">Minggu</option>
                                                            <option {{ ($invoice != null ? ($invoice->JatuhTempoSatuan == "3") ? 'selected' : '' : '') }} value="3">Bulan</option>
                                                        </select>
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ekspedisi-container {{ $invoice != null ? (($invoice->IsEkspedisi ?? 0) == 0 ? "d-none" : "") : "d-none" }}">
                                    <div class="form-group row">
                                        <label for="NamaEkspedisi" class="form-label col-md-3">Nama Ekspedisi</label>
                                        <div class="col-md-9">
                                            <select class="form-control select2-tags" id="NamaEkspedisi" name="NamaEkspedisi">
                                                <option value="">Pilih Ekspedisi</option>
                                                @foreach($ekspedisi as $expedisi)
                                                    <option value="{{ $expedisi }}" {{ (isset($invoice) && $invoice->NamaEkspedisi == $expedisi) ? 'selected' : '' }}>
                                                        {{ $expedisi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="AlamatEkspedisi" class="form-label col-md-3">Alamat Ekspedisi</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" id="AlamatEkspedisi" name="AlamatEkspedisi">{{ $invoice->AlamatEkspedisi ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="TelpEkspedisi" class="form-label col-md-3">Telp Ekspedisi</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="TelpEkspedisi" name="TelpEkspedisi"
                                                value="{{ $invoice->TelpEkspedisi ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="ttd-container {{ $invoice != null ? (($invoice->IsKopSurat ?? 0) == 0 ? "d-none" : "") : "d-none" }}">
                                    <div class="form-group row">
                                        <label for="TTD" class="form-label col-md-3">TTD Customer</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="TTD" name="TTD"
                                                value="{{ $invoice->TTD ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="discount-container {{ $invoice != null ? (($invoice->IsDiscount ?? 0) == 0 ? "d-none" : "") : "d-none" }}">
                                    <div class="form-group row">
                                        <label for="Discount" class="form-label col-md-3">Discount</label>
                                        <div class="col-md-9">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control numeric" id="Discount" name="Discount"
                                                value="{{ $invoice->Discount ?? '' }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Notes" class="form-label col-md-3">Notes</label>
                                    <div class="col-md-9">
                                        <textarea rows="3" class="form-control" id="Notes" name="Notes">{{ $invoice->Notes ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="NotesSJ" class="form-label col-md-3">Notes SJ</label>
                                    <div class="col-md-9">
                                        <textarea rows="3" class="form-control" id="NotesSJ" name="NotesSJ">{{ $invoice->NotesSJ ?? '' }}</textarea>
                                    </div>
                                </div>
                                </li>
                            </ul>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            {{-- ITEM LIST --}}
            <button class="btn btn-avian-secondary d-md-none fab-add-text" type="button" id="btnAddByTextMobile" onclick="$('#btnAddByText').click();">
                <i class="fas fa-font"></i>
            </button>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <div>Product List</div>
                        <div class="btn-group">
                            <button class="btn btn-clear btn-danger"><i class="fas fa-trash mr-lg-2"></i><span class="d-none d-lg-inline">Clear</span></button>
                            <button class="btn btn-lock btn-outline-secondary"><i class="fas fa-lock mr-lg-2"></i><span class="d-none d-lg-inline">Lock</span></button>
                            <button class="btn btn-sm btn-outline-secondary" id="btnAddDetailMultiple" type="button">
                                <i class="fas fa-plus mr-lg-2"></i><span class="d-none d-lg-inline">Add Multiple</span>
                            </button>
                            <button class="btn btn-avian-primary" type="button" id="btnAddDetail"><i class="fas fa-plus mr-2"></i>Add<span class="d-none d-lg-inline"> Item</span></button>
                            <button class="btn btn-avian-secondary" type="button" id="btnAddByText"><i class="fas fa-font mr-lg-2"></i><span class="d-none d-lg-inline">Add by Text</span></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="dataTable table table-striped table-hover table-bordered w-100 responsive-table" id="product-list-table">
                        <thead>
                        <tr>
                            <th width="500">Inv No.</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th width="100">Price</th>
                            <th width="150">Total</th>
                            <th>Dos Luar / Isi</th>
                            <th>Dos Gabung</th>
                            <th>SJ</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="product-list-body"></tbody>
                    </table>
                    </div>
                </div>
                <div class="card-footer">
                    <h5 class="text-right">Total: <span id="grand-total">0</span></h5>
                </div>
            </div>
        </form>
    </div>
</div>
<style>
    li.select2-results__option {
  white-space: nowrap;
}

/* --- Versi Responsive --- */
@media (max-width: 1200px) {
  .responsive-table thead {
    display: none; /* sembunyikan header tabel */
  }

  .responsive-table tr {
    display: block;
    margin-bottom: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 0.75rem;
  }

  .responsive-table td {
    display: block; /* biar vertikal */
    border: none;
    padding: 0.5rem 0;
  }

  .responsive-table td::before {
    content: attr(data-label);
    display: block;
    font-weight: 600;
    text-transform: capitalize;
    color: #555;
    margin-bottom: 4px; /* beri jarak antara label dan input */
  }

  .responsive-table td input,
  .responsive-table td select {
    width: 100%; /* isi penuh */
    font-size: 0.9rem;
  }

  .responsive-table td[data-label="Dos / Isi"] .input-group {
    display: flex;
    flex-direction: column;
    gap: 6px; /* jarak antar input */
    margin-bottom: 0;
  }

  .responsive-table td[data-label="Dos / Isi"] .input-group input {
    width: 100%; /* isi penuh */
  }
  .responsive-table .invoice-item-row:nth-child(odd) {
    background: #fff3f1 !important;
  }

  .responsive-table .invoice-item-row:nth-child(even) {
    background: #ffffe6 !important;
  }

}
.fab-add-text {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.25);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
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
    $('#namacustomer').next('.select2-container')
    .find('.select2-selection')
    .css('background-color', '#E7F1DC');

    // hide pdf kalo hp
    if (window.innerWidth <= 768) {
        $('#iframe-container').hide();
        $('#form-container').removeClass('col-md-6').addClass('col-md-12');
        $('.btn-toggle-preview').hide();
    }

    //if btnAddDetail not clicked then alert error
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
            showDenyButton: true,
            confirmButtonColor: '#3085d6',
            denyButtonColor: '#6c757d',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!',
            denyButtonText: `Save & PDF`
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            } else if (result.isDenied) {
                $('#toPDF').val('1');
                this.submit();
            } else {
                return false;
            }
            });
        }
    );

     $('.btn-toggle-preview').on('click', function() {
        const $formCol = $('#form-container');
        const $iframeCol = $('#iframe-container');
        const $iframe = $('#print_preview');
        const $icon = $(this).find('i');

        // Toggle visibility
        if ($iframeCol.is(':visible')) {
            // Hide iframe
            $iframeCol.hide();
            $formCol.removeClass('col-md-6').addClass('col-md-12');
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
            $(this).text('Preview').prepend($icon);
        } else {
            // Show iframe
            $iframeCol.show();
            $formCol.removeClass('col-md-12').addClass('col-md-6');
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
            $(this).text('Hide Preview').prepend($icon);

            // Optional: reload iframe
            const uri = $('#print_preview').attr('src');
            if (!uri || uri === '') {
                embedPreviewData(); // kalau kamu punya fungsi ini
            }
        }
    });

    $('.btn-pdf').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        $.ajax({
            url: $('#formInput').attr('action'),
            method: $('#formInput').attr('method'),
            data: $('#formInput').serialize(),
            success: function (response) {
                window.open(href, '_blank');
                isChanged = false;
            },
            error: function (xhr) {
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
            }
        });
    });

    $('#categorycustomer').on('change', function() {
        // kalau tidak ada product di form, jangan tanya
        if ($('.product-select').length === 0) {
            return;
        }
        Swal.fire({
            title: 'Kategori Customer diubah. Apakah Anda ingin memperbarui harga produk sesuai kategori baru?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Perbarui Harga',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $('.product-select').each(function() {
                    handleProductSelect($(this));
                });
            }
        });
    });

    $(document).on('change', 'input, select, textarea', function (e) {
        embedPreviewData();
        isChanged = true;
    });

    function embedPreviewData() { 
        let formdata = $('#formInput').serialize();
        let uri = '{{ route("invoice.previewdynamic", $invoice->FormID ?? 0) }}?' + formdata + "#toolbar=0&navpanes=0&scrollbar=0";
        $('#print_preview').attr('src', uri + '&t=' + new Date().getTime());
    }

    function addProduct(isSJ = false) {
        //
        const productRow = getProductRow(isSJ);
        $('#product-list-body').append(productRow);

        $('.numeric').inputmask({
            alias: 'decimal',
            radixPoint: ',',
            groupSeparator: '.',
        });

        // foreach class numeric, kalau kosong diisi 0
        $('.numeric').each(function() {
            if ($(this).val() === '') {
                $(this).val('0');
            }
        });
        
        $('.product-select').select2({
            placeholder: 'Pilih...',
            theme: 'bootstrap4',
            tags: true,
            ajax: {
                url: '{{ route("invoice.getProducts") }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term // search term
                    };
                },
                delay: 250,
                processResults: function (data) {
                    console.log(data);
                    // Pastikan data dari server berbentuk array [{id:..., text:...}]
                    return {
                        results: data.products.map(item => ({
                            id: item,
                            text: item
                        }))
                    };
                },
                cache: true
            }
        });

        setTimeout(() => {
            reorderTabIndex();
        }, 200);
    }

    function getProductRow(isSJ = false) {
        
        var row = `
            <tr class="${isSJ ? 'sj-item-row' : 'invoice-item-row'}">
                <input type="hidden" name="type[]" value="create" />
                <input type="hidden" name="detailid[]" />
                <input type="hidden" name="issj[]" />
                ${!isSJ ? `
                <td data-label="Product">
                    <select class="form-control select2-tags product-select" name="product[]">
                        <option value="">Pilih Product</option>
                    </select>
                </td>
                ` : `
                <td data-label="Product">
                    <input type="text" class="form-control product-input" name="product[]" />
                </td>
                `}
                <td data-label="Quantity">
                    <input type="text" class="form-control numeric quantity-input" name="quantity[]" />
                </td>
                <td data-label="Unit">
                    <input type="text" class="form-control" name="unit[]" />
                </td>
                ${!isSJ ? `
                <td data-label="Price">
                    <input type="text" class="form-control numeric price-input" name="price[]" />
                </td>
                <td data-label="Total">
                    <input type="text" class="form-control numeric" name="total[]" readonly />
                </td>
                <td data-label="Dos / Isi">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control numeric dos-luar-input" name="dosluar[]" placeholder="Dos" value="0">
                        <input type="text" class="form-control isi-input" name="isi[]" placeholder="Isi">
                    </div>
                </td>
                <td data-label="Dos Gabung">
                    <input type="text" class="form-control numeric dos-gabung-input" name="dosgabung[]" />
                </td>
                <td data-label="SJ">
                    <input type="checkbox" class="form-control sj-checkbox" name="sj[]" />
                </td>
                <td data-label="Aksi">
                    <input type="hidden" name="hidden[]" value="0" />
                    <button type="button" class="btn btn-sm btn-danger btn-remove-product">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
                ` : `
                <td class="d-none"><input type="text" class="form-control numeric price-input" name="price[]" /></td>
                <td class="d-none"><input type="text" class="form-control numeric" name="total[]" readonly /></td>
                <td class="d-none">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control numeric dos-luar-input" name="dosluar[]" placeholder="Dos" value="0">
                        <input type="text" class="form-control isi-input" name="isi[]" placeholder="Isi">
                    </div>
                </td>
                <td class="d-none">
                    <input type="text" class="form-control numeric dos-gabung-input" name="dosgabung[]" />
                </td>
                <td class="d-none"><input type="checkbox" class="form-control sj-checkbox" name="sj[]" /></td>
                <td colspan="5" class="text-center pt-3 text-secondary">-- SJ Item --</td>
                <td data-label="Aksi">
                    <input type="hidden" name="hidden[]" value="0" />
                    <button type="button" class="btn btn-sm btn-warning btn-hide-product">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
                `}
            </tr>`;

        return row;
    }

    function reorderTabIndex() {
        const columns = ['select2-tags',
        'numeric[name="quantity[]"]',
        'form-control[name="unit[]"]',
        'numeric[name="price[]"]',
        'form-control[name="total[]"]',
        'numeric[name="dosluar[]"]',
        'form-control[name="isi[]"]',
        'form-control[name="sj[]"]',
        'btn-remove-product',
        'btn-hide-product',
        'form-control[name="dosgabung[]"]'
    ];

        let tabIndex = 1;

        columns.forEach(selector => {
            // Loop per kolom (bukan per baris)
            $('#product-list-body').find('.' + selector).each(function() {
                $(this).attr('tabindex', tabIndex++);
            });
        });
    }

    $('#btnAddDetail').on('click', function() {
        addProduct();
    });
    
    $('#btnAddByText').on('click', async function() {
        const result = await Swal.fire({
            title: 'Add Products by Text',
            input: 'textarea',
            inputLabel: 'Masukkan daftar produk',
            inputPlaceholder: 'Contoh:\nProductA 2\nProductB 3\nProductC 1',
            inputAttributes: {
                'aria-label': 'Masukkan daftar produk di sini'
            },
            showCancelButton: true
        });

        if (!result.isConfirmed || !result.value) return;

        const products = result.value.split('\n').map(item => item.trim()).filter(item => item !== '');
        console.log(products);

        for (const product of products) {
            const [productName, qty] = product.split(' ').map(i => i.trim());
            const productDetails = await getProductDetailsAsync(productName); // tunggu AJAX selesai dulu

            const $productRow = $(getProductRow());
            $('#product-list-body').append($productRow);
            if (productDetails) {
                $productRow.find('select.product-select')
                    .append(new Option(productDetails.Name || productName, productDetails.Name || productName, true, true))
                    .trigger('change');

                $productRow.find('input[name="unit[]"]').val(productDetails.Satuan);

                const category = $('#categorycustomer').val();
                const price = category === '1' ? productDetails.price.PriceKonsumen
                    : category === '2' ? productDetails.price.PriceSup1
                    : category === '3' ? productDetails.price.PriceSup2
                    : productDetails.price.PriceDistributor;

                $productRow.find('input[name="price[]"]').val(price).trigger('change');

            } else {
                $productRow.find('select.product-select')
                    .append(new Option(productName, productName, true, true))
                    .trigger('change');
            }

            if (qty && !isNaN(qty)) {
                $productRow.find('input[name="quantity[]"]').val(qty).trigger('change');
            } else {
                $productRow.find('input[name="quantity[]"]').val(1).trigger('change');
            }
        }

        $('.numeric').inputmask({
            alias: 'decimal',
            radixPoint: ',',
            groupSeparator: '.',
        });

        $('.product-select').select2({
            placeholder: 'Pilih...',
            theme: 'bootstrap4',
            tags: true,
            ajax: {
                url: '{{ route("invoice.getProducts") }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.products.map(item => ({
                            id: item,
                            text: item
                        }))
                    };
                },
                cache: true
            }
        });

        reorderTabIndex();

        // move to bottom
        window.scrollTo(0, document.body.scrollHeight);
    });

    $('#btnAddDetailMultiple').on('click', function(e) {
        e.preventDefault();
        // swal fire input angka
        Swal.fire({
            title: 'Berapa banyak deskripsi yang ingin ditambahkan?',
            input: 'number',
            inputAttributes: {
                min: 1,
                max: 100,
                step: 1
            },
            showCancelButton: true,
            confirmButtonText: 'Add',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            preConfirm: (value) => {
                if (value < 1) {
                    Swal.showValidationMessage('Jumlah harus lebih dari 0');
                }
                return value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                for (let i = 0; i < result.value; i++) {
                    addProduct();
                }
            }
        });
    });

    $('#namacustomer').on('select2:select', function (e) {
        var selectedCustomer = e.params.data.text;

        // Lakukan AJAX untuk mendapatkan detail customer
        $.ajax({
            url: '{{ route("invoice.getCustomerDetails") }}',
            method: 'GET',
            data: { search: selectedCustomer },
            success: function(response) {
                if (response.status === 'success') {
                    $('#alamatcustomer').val(response.customer.Alamat);
                    $('#telpcustomer').val(response.customer.Telp);
                    $('#categorycustomer').val(response.customer.PriceCategory).trigger('change');
                    $('#JatuhTempo').val(response.customer.JatuhTempo);
                    $('#JatuhTempoSatuan').val(response.customer.JatuhTempoSatuan ?? 1).trigger('change');
                } else {
                    $('#alamatcustomer').val('');
                    $('#telpcustomer').val('');
                    $('#categorycustomer').val('');
                    $('#JatuhTempo').val('');
                    $('#JatuhTempoSatuan').val('1').trigger('change');
                }
            },
            error: function() {
                $('#alamatcustomer').val('');
                $('#telpcustomer').val('');
            }
        });
    });

    $('#NamaEkspedisi').on('select2:select', function (e) {
        var selectedEkspedisi = e.params.data.text;

        // Lakukan AJAX untuk mendapatkan detail ekspedisi
        $.ajax({
            url: '{{ route("invoice.getCustomerDetails") }}',
            method: 'GET',
            data: { search: selectedEkspedisi },
            success: function(response) {
                if (response.status === 'success') {
                    $('#AlamatEkspedisi').val(response.customer.Alamat);
                    $('#TelpEkspedisi').val(response.customer.Telp);
                } else {
                    $('#AlamatEkspedisi').val('');
                    $('#TelpEkspedisi').val('');
                }
            },
            error: function() {
                $('#AlamatEkspedisi').val('');
                $('#TelpEkspedisi').val('');
            }
        });
    });

    $('#invoicedate').on('change', function() {
        $('#sjdate').val($(this).val());
    });

    $(document).on('select2:select', '.product-select', function (e) {
        if (isLocked) {
            return;
        }
        handleProductSelect($(this));
    });

    function handleProductSelect($selectElement) {
        var selectedProduct = $selectElement.find('option:selected').text();

        getProductDetails(selectedProduct, function(productDetails) {
            if (productDetails) {
                var $row = $selectElement.closest('.card-body, tr');
                $row.find('input[name="unit[]"]').val(productDetails.Satuan);

                var category = $('#categorycustomer').val();
                if (category == '1') {
                    $row.find('input[name="price[]"]').val(productDetails.price.PriceKonsumen).trigger('change');
                } else if (category == '2') {
                    $row.find('input[name="price[]"]').val(productDetails.price.PriceSup1).trigger('change');
                } else if (category == '3') {
                    $row.find('input[name="price[]"]').val(productDetails.price.PriceSup2).trigger('change');
                } else if (category == '4') {
                    $row.find('input[name="price[]"]').val(productDetails.price.PriceDistributor).trigger('change');
                }
            }
        });
    }

    $(document).on('keyup change', '.dos-luar-input', function (e) {
        var row = $(this).closest('.card-body, tr');
        qty = parseFloat(row.find('.quantity-input').inputmask('unmaskedvalue')) || 0;
        dosLuar = parseFloat(row.find('.dos-luar-input').inputmask('unmaskedvalue')) || 1;
        isi = qty / dosLuar;
        row.find('.isi-input').val(isi);
    });

    $(document).on('keyup change', '.price-input, .quantity-input', function (e) {
        var $row = $(this).closest('.card-body, tr');
        var quantity = parseFloat($row.find('.quantity-input').inputmask('unmaskedvalue')) || 0;
        var price = parseFloat($row.find('.price-input').inputmask('unmaskedvalue')) || 0;
        var total = quantity * price;
        $row.find('input[name="total[]"]').val(total);

        // hitung grand total
        $('#grand-total').text(getGrandTotal().toLocaleString('id-ID'));
    });

    function getGrandTotal() {
        var grandTotal = 0;
        $('#product-list-body tr').each(function() {
            var type = $(this).find('input[name="type[]"]').val();
            var isHidden = $(this).find('input[name="hidden[]"]').val() == '1';
            if (type !== 'delete' && !isHidden) {
                var lineTotal = parseFloat($(this).find('input[name="total[]"]').inputmask('unmaskedvalue')) || 0;
                grandTotal += lineTotal;
            }
        });
        return grandTotal;
    }

    $(document).on('change', '.sj-checkbox', function (e) {
        // tambahkan row baru dibawah row ini
        var row = $(this).closest('.card-body, tr');
        if ($(this).is(':checked')) {
            addProduct(true);
            var newRow = $('#product-list-body tr').last();
            newRow.insertAfter(row);
            // buat backgroundnya beda warna
            newRow.css('background-color', '#e9f7ef');
            // assign data seperti data diatas ini
            newRow.find('input[name="type[]"]').val('update');
            newRow.find('input[name="issj[]"]').val('1');
            newRow.find('input[name="detailid[]"]').val(row.find('input[name="detailid[]"]').val());
            newRow.find('.product-input').val(row.find('.product-select').val());
            newRow.find('.quantity-input').val(row.find('.quantity-input').val());
            newRow.find('input[name="unit[]"]').val(row.find('input[name="unit[]"]').val());
        } else {
            var nextRow = row.next('tr');
            if (nextRow.length > 0 && nextRow.find('input[name="issj[]"]').val() == '1') {
                nextRow.find('input[name="type[]"]').val('delete');
                nextRow.hide();
            }
        }
        embedPreviewData();
    });

    $(document).on('click', '.btn-remove-product', function (e) {
        e.preventDefault();
        var row = $(this).closest('tr, .card');
        row.find('input[name="type[]"]').val('delete');
        row.hide();

        // jika ada row sj dibawahnya, hapus juga
        var $nextRow = row.next('tr');
        if ($nextRow.find('input[name="issj[]"]').val() == '1') {
            $nextRow.find('input[name="type[]"]').val('delete');
            $nextRow.hide();
        }
        embedPreviewData();
    });

    $(document).on('click', '.btn-hide-product', function (e) {
        e.preventDefault();
        var row = $(this).closest('tr, .card');
        oldVal = row.find('input[name="hidden[]"]').val();
        if (oldVal == '1') {
            // ubah jadi 0
            row.find('input[name="hidden[]"]').val('0');
            // ubah iconnya jadi fa-eye dan warna jadi warning
            $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            $(this).removeClass('btn-secondary').addClass('btn-warning');
        } else {
            row.find('input[name="hidden[]"]').val('1');
            // ubah iconnya jadi fa-eye-slash dan warna jadi secondary
            $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            $(this).removeClass('btn-warning').addClass('btn-secondary');
        }
        embedPreviewData();
    });

    function getProductDetailsAsync(productName) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '{{ route("invoice.getProductDetails") }}',
                method: 'GET',
                data: { search: productName },
                success: function(response) {
                    if (response.status === 'success') {
                        resolve(response.product);
                    } else {
                        resolve(null);
                    }
                },
                error: function() {
                    resolve(null);
                }
            });
        });
    }

    function getProductDetails(productName, callback) { 
        $.ajax({ 
            url: '{{ route("invoice.getProductDetails") }}', 
            method: 'GET', 
            data: { search: productName }, 
            success: function(response) { 
                if (response.status === 'success') { 
                    callback(response.product); 
                } else { 
                    callback(null); 
                } 
            }, 
            error: function() { 
                callback(null); 
            } 
        }); 
    }

    @if($invoice != null)
        @foreach($invoice->details as $detail)
            addProduct();
            // set values
            var $lastRow = $('#product-list-body .card-body, tr').last();
            $lastRow.find('.product-select').html('<option value="{{ $detail->Nama }}" selected>{{ $detail->Nama }}</option>').trigger('change');
            $lastRow.find('.quantity-input').val('{{ number_format($detail->Qty, 0, ",", ".") }}');
            $lastRow.find('input[name="unit[]"]').val('{{ $detail->Satuan }}');
            $lastRow.find('.price-input').val('{{ number_format($detail->Harga, 0, ",", ".") }}');
            $lastRow.find('input[name="total[]"]').val('{{ number_format($detail->Harga * $detail->Qty, 0, ",", ".") }}');
            $lastRow.find('.dos-luar-input').val('{{ $detail->DosLuar == 1 ? 1 : $detail->DosLuar }}');
            $lastRow.find('.isi-input').val('{{ $detail->DosLuar == 1 ? $detail->Qty : $detail->Isi }}');
            $lastRow.find('.dos-gabung-input').val('{{ $detail->DosGabung }}');
            $lastRow.find('input[name="detailid[]"]').val('{{ $detail->DetailID }}');
            $lastRow.find('input[name="type[]"]').val('update');
            @if($detail->IsSJ ?? 0 == 1)
                $lastRow.find('.sj-checkbox').prop('checked', true).trigger('change');
                $lastRow.next('tr').find('.product-input').val('{{ $detail->NamaSJ }}');
                $lastRow.next('tr').find('.quantity-input').val('{{ number_format($detail->QtySJ, 0, ",", ".") }}');
                $lastRow.next('tr').find('input[name="unit[]"]').val('{{ $detail->SatuanSJ }}');
            @endif
            $lastRow.next('tr').find('input[name="hidden[]"]').val('{{ $detail->IsHidden }}');
            // set hide button state
            @if($detail->IsHidden ?? 0 == 1)
                $lastRow.next('tr').find('.btn-hide-product').find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                $lastRow.next('tr').find('.btn-hide-product').removeClass('btn-warning').addClass('btn-secondary');
            @endif
        @endforeach
        $('#grand-total').text(getGrandTotal().toLocaleString('id-ID'));
    @else
        // @for($i = 0; $i < 13; $i++)
        //     addProduct();
        // @endfor
    @endif

    $('.form-type-checkbox').on('change', function() {
        const isInvoiceChecked = $('#IsInvoice').is(':checked');
        const isSJChecked = $('#IsSJ').is(':checked');

        if (isInvoiceChecked) {
            $('.invoice-container').removeClass('d-none');
        } else {
            $('.invoice-container').addClass('d-none');
        }

        if (isSJChecked) {
            $('.sj-container').removeClass('d-none');
        } else {
            $('.sj-container').addClass('d-none');
        }

        embedPreviewData();
    });

    $('#IsEkspedisi').on('change', function() {
        if ($(this).is(':checked')) {
            $('.ekspedisi-container').removeClass('d-none');
        } else {
            $('.ekspedisi-container').addClass('d-none');
            $('#IsSJCustomer').prop('checked', true).trigger('change');
        }
        embedPreviewData();
    });

    $('#IsKopSurat').on('change', function() {
        if ($(this).is(':checked')) {
            $('.ttd-container').addClass('d-none');
        } else {
            $('.ttd-container').removeClass('d-none');
        }
        embedPreviewData();
    });

    $('#IsDiscount').on('change', function() {
        if ($(this).is(':checked')) {
            $('.discount-container').removeClass('d-none');
        } else {
            $('.discount-container').addClass('d-none');
        }
        embedPreviewData();
    });

    $('#IsSJCustomer').on('change', function() {
        if ($(this).is(':checked')) {
        } else {
            $('#IsEkspedisi').prop('checked', true).trigger('change');
        }
    });

    $('.btn-lock').on('click', function(e) {
        e.preventDefault();
        isLocked = !isLocked;
        if (isLocked) {
            // Lock
            $('#product-list-body').find('select:not([name="product[]"]), input').prop('disabled', true);
            $(this).html('<i class="fas fa-unlock mr-lg-2"></i><span class="d-none d-lg-inline">Unlock</span>');
        } else {
            // Unlock
            $('#product-list-body').find('select:not([name="product[]"]), input').prop('disabled', false);
            $(this).html('<i class="fas fa-lock mr-lg-2"></i><span class="d-none d-lg-inline">Lock</span>');
        }
    });

    $('.btn-clear').on('click', function(e) {
        e.preventDefault();
        $('#product-list-body').empty();
        $('#grand-total').text('0');
        isChanged = true;
        embedPreviewData();
    });

    embedPreviewData();
});


</script>
@endsection
