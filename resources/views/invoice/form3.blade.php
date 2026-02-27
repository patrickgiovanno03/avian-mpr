@extends('layouts.app')

@section('title', 'MPR | '.($invoice == null ? 'Create' : 'Edit') .' Invoice')

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
                            {{-- <a target="_blank" href="{{ route('invoice.previewdynamic', $invoice->FormID) }}" type="button" class="btn btn-sm btn-outline-secondary btn-pdf">
                                <i class="fas fa-file-pdf mr-lg-2"></i><span class="d-none d-lg-inline">Save & PDF</span>
                            </a>
                            <a target="_blank" href="{{ route('invoice.previewdynamic', ['id' => $invoice->FormID, 'download' => 1]) }}" type="button" class="btn btn-sm btn-outline-secondary btn-pdf">
                                <i class="fas fa-file-download mr-lg-2"></i><span class="d-none d-lg-inline">Save & Download</span>
                            </a> --}}
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
                    <div class="col-md-6 order-2 order-md-1 pr-4" id="iframe-container">
                        <div style="position: sticky; top: 20px;">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-file-pdf mr-2"></i>Live Preview
                                </div>
                                <div class="card-body p-0">
                                    <iframe
                                        id="print_preview"
                                        src=""
                                        style="width: 100%; height: 750px; border: none;"
                                    ></iframe>
                                </div>
                                <div class="card-footer text-center" style="background: #fff3cd;">
                                    <small class="text-warning"><i class="fas fa-info-circle mr-2"></i>Preview mungkin tidak sepenuhnya akurat. Buka PDF untuk tampilan final.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 order-1 order-md-2" id="form-container">
                        <input type="hidden" id="toPDF" name="toPDF" value="0">
                        <input type="hidden" id="toHome" name="toHome" value="0">
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
                                <input type="text" class="form-control readonly-color" id="invoiceno" name="invoiceno"
                                    value="{{ ($isDuplicate ?? null) ? app(\App\Models\HInvoice::class)->getNewInvoiceNo() : ($invoice->InvoiceNo ?? app(\App\Models\HInvoice::class)->getNewInvoiceNo()) }}">
                            </div>
                        </div>
                        <div class="form-group row sj-container {{ $invoice != null ? ($invoice->SJNo == null ? 'd-none' : '') : '' }}">
                            <label for="sjno" class="form-label col-md-3">Surat Jalan No.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control readonly-color" id="sjno" name="sjno"
                                    value="{{ ($isDuplicate ?? null) ? app(\App\Models\HInvoice::class)->getNewSJNo() : ($invoice->SJNo ?? app(\App\Models\HInvoice::class)->getNewSJNo()) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="invoicedate" class="form-label col-md-3">Tanggal Invoice</label>
                            <div class="col-md-9">
                                <input style="background-color: #E7F1DC" type="text" class="form-control datepicker" id="invoicedate" name="invoicedate"
                                    value="{{ $invoice != null ? Carbon\Carbon::parse($invoice->InvoiceDate)->format('d/m/Y') : Carbon\Carbon::now()->format('d/m/Y') }}" tabindex="1">
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
                                    value="{{ $invoice->Kode ?? '' }}" tabindex="3">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="namacustomer" class="form-label col-md-3">Nama Customer</label>
                            <div class="col-md-9">
                                <select style="background-color: #ffcccc" class="form-control select2-tags" id="namacustomer" name="namacustomer" tabindex="2">
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
                        <div>
                        <div class="collapsible-section">
                            <div class="collapsible-header" data-toggle="collapse" href="#collapse1">
                                <div>
                                    <i class="fas fa-cog mr-2"></i>
                                    <strong>Advanced Options</strong>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse mt-3">
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
                                            <input class="form-check-input form-type-checkbox" type="checkbox" name="IsReseller" id="IsReseller" value="IsReseller" {{ $invoice != null ? ((($invoice->IsReseller ?? 0) == 0) ? "" : "checked") : "checked" }}>
                                            <label class="form-check-label" for="IsReseller">
                                                Reseller
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
                                        <span class="text-muted">Isi -1 untuk tidak menampilkan jatuh tempo</span>
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
                                                <button class="btn btn-sm btn-info btn-discount ml-2"><i class="fas fa-plus"></i></button>
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
            <button class="btn btn-avian-secondary d-md-none fab-add-text-left" type="button" id="btnAddByTextMobile" onclick="$('#btnAddByText').click();">
                <i class="fas fa-font"></i>
            </button>
            <button class="btn btn-avian-secondary d-md-none fab-add-text" type="button" id="btnAddByTextMobile" onclick="$('.btn-submit').click();">
                <i class="fas fa-save"></i>
            </button>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div class="font-weight-bold">Product List</div>
                        <div class="btn-group">
                            <!-- Desktop Buttons -->
                            <button class="btn btn-toggle-dos btn-outline-info d-none d-lg-inline-flex" type="button"><i class="fas fa-eye-slash mr-lg-2 pt-1"></i><span class="d-none d-lg-inline">Hide Dos</span></button>
                            <button class="btn btn-clear btn-danger d-none d-lg-inline-flex"><i class="fas fa-trash mr-lg-2 pt-1"></i><span class="d-none d-lg-inline">Clear</span></button>
                            <button class="btn btn-lock btn-outline-secondary d-none d-lg-inline-flex"><i class="fas fa-lock mr-lg-2 pt-1"></i><span class="d-none d-lg-inline">Lock</span></button>
                            <button class="btn btn-sm btn-outline-secondary d-none d-lg-inline-flex" id="btnAddDetailMultiple" type="button">
                                <i class="fas fa-plus mr-lg-2 pt-2"></i><span class="d-none d-lg-inline pt-1">Add Multiple</span>
                            </button>
                            <button class="btn btn-avian-primary d-none d-lg-inline-flex" type="button" id="btnAddDetail"><i class="fas fa-plus mr-2 pt-1"></i>Add<span class="d-none d-lg-inline"> Item</span></button>
                            <button class="btn btn-avian-secondary d-none d-lg-inline-flex" type="button" id="btnAddByText"><i class="fas fa-font mr-lg-2 pt-1"></i><span class="d-none d-lg-inline">Add by Text</span></button>
                            
                            <!-- Mobile Buttons -->
                            <button class="btn btn-sm btn-avian-primary d-lg-none" type="button" id="btnAddDetailMobile"><i class="fas fa-plus"></i></button>
                            <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" data-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item btn-toggle-dos-mobile" type="button"><i class="fas fa-eye-slash mr-2"></i>Hide Dos</button>
                                <button class="dropdown-item" type="button" id="btnAddByTextMobileMenu"><i class="fas fa-font mr-2"></i>Add by Text</button>
                                <button class="dropdown-item" type="button" id="btnAddDetailMultipleMobile"><i class="fas fa-plus mr-2"></i>Add Multiple</button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item btn-lock-mobile" type="button"><i class="fas fa-lock mr-2"></i>Lock</button>
                                <button class="dropdown-item btn-clear-mobile text-danger" type="button"><i class="fas fa-trash mr-2"></i>Clear All</button>
                            </div>
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
                            <th class="dos-column">Dos Luar / Isi</th>
                            <th class="dos-column">Dos Gabung</th>
                            <th class="dos-column">Inv</th>
                            <th class="dos-column">SJ</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="product-list-body"></tbody>
                    </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end align-items-center">
                    {{-- button untuk langsung naik ke atas page --}}
                    <button onclick="window.scrollTo({top: 1000, behavior: 'smooth'}); return false" class="btn btn-sm btn-secondary mr-2"><i class="fas fa-arrow-up"></i></button>
                    <h5 class="text-right">Total: <span id="grand-total">0</span></h5>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- modal discount --}}
<div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="discountModalLabel">Detail Discount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="spreadsheet"></div>
                <span class="text-danger">Gunakan (.) untuk koma</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-danger" id="resetDiscountDetails">Reset</button>
                <button type="button" class="btn btn-primary" id="saveDiscountDetails">Save Details</button>
            </div>
        </div>
    </div>
</div>
<style>
    li.select2-results__option {
  white-space: nowrap;
}

/* --- Versi Responsive --- */
@media (max-width: 1200px) {
  .responsive-table thead {
    display: none;
  }

  .responsive-table tr {
    display: block;
    margin-bottom: 1rem;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    padding: 0;
    overflow: hidden;
    position: relative;
  }

  /* Subtle top accent - minimal and clean */
  .responsive-table tr::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: #e0e0e0;
  }

  .responsive-table .invoice-item-row::before {
    background: #2196F3;
  }

  .responsive-table .sj-item-row::before {
    background: #00BCD4;
  }

  .responsive-table td {
    display: block;
    border: none;
    padding: 0.875rem 1rem;
    position: relative;
  }

  .responsive-table td:not(:last-child) {
    border-bottom: 1px solid #f5f5f5;
  }

  /* Clean label styling */
  .responsive-table td::before {
    content: attr(data-label);
    display: block;
    font-weight: 500;
    font-size: 0.7rem;
    text-transform: uppercase;
    color: #999;
    margin-bottom: 0.5rem;
    letter-spacing: 0.5px;
  }

  /* Minimal color accent for product */
  .responsive-table td[data-label="Product"]::before {
    color: #2196F3;
  }

  /* Clean action area */
  .responsive-table td[data-label="Aksi"] {
    background: #fafafa;
    padding: 0.75rem 1rem;
    text-align: center;
    border-top: 1px solid #f0f0f0;
  }

  .responsive-table td[data-label="Aksi"]::before {
    display: none;
  }

  /* Clean input styling */
  .responsive-table td input,
  .responsive-table td select {
    width: 100%;
    font-size: 1rem;
    padding: 0.625rem 0.75rem;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    transition: border-color 0.2s;
    background: #fff;
  }

  .responsive-table td input:focus,
  .responsive-table td select:focus {
    border-color: #2196F3;
    outline: none;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.08);
  }

  /* Subtle emphasis on key fields */
  .responsive-table td[data-label="Quantity"] input,
  .responsive-table td[data-label="Price"] input {
    font-weight: 500;
  }

  .responsive-table td[data-label="Total"] input {
    background: #f5f5f5;
    font-weight: 600;
    color: #333;
    border-color: #e0e0e0;
  }

  /* Clean DOS fields layout */
  .responsive-table td[data-label="Dos / Isi"] .input-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    margin-bottom: 0;
  }

  .responsive-table td[data-label="Dos / Isi"] .input-group input {
    width: 100%;
  }

  /* Remove background colors - keep it clean */
  .responsive-table .invoice-item-row,
  .responsive-table .sj-item-row {
    background: #fff;
  }

  /* Minimal DOS Fields styling */
  .responsive-table td.dos-column {
    background: #fafafa;
  }

  .responsive-table td.dos-column::before {
    color: #999;
  }

  /* Clean checkbox styling */
  .responsive-table td input[type="checkbox"] {
    width: 28px;
    height: 28px;
    cursor: pointer;
    accent-color: #2196F3;
  }

  /* Clean button styling */
  .responsive-table td button {
    min-width: 44px;
    min-height: 44px;
    font-size: 1rem;
    border-radius: 6px;
  }

  /* Minimal SJ Label */
  .responsive-table .sj-label {
    background: #00BCD4;
    color: white;
    padding: 0.75rem 1rem;
    font-weight: 500;
    text-align: center;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
  }

  .responsive-table .sj-label::before {
    display: none;
  }

  /* Clean toggle button */
  .responsive-table .btn-toggle-input {
    min-width: 44px;
    padding: 0.625rem;
    background: #fafafa;
    border-color: #e0e0e0;
  }

  /* Clean select2 styling */
  .responsive-table .select2-container {
    min-height: 44px;
  }

  .responsive-table .select2-selection {
    min-height: 44px !important;
    padding: 0.5rem !important;
    border-radius: 6px !important;
    border-color: #e0e0e0 !important;
  }

}
.fab-add-text {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.fab-add-text:active {
    transform: scale(0.95);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.fab-add-text-left {
    position: fixed;
    bottom: 20px;
    left: 20px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.fab-add-text-left:active {
    transform: scale(0.95);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

/* Mobile Optimizations */
@media (max-width: 768px) {
    /* Clean header */
    .card-header {
        padding: 0.875rem 1rem;
        background: #fafafa;
        border-bottom: 1px solid #e8e8e8;
    }

    /* Cleaner spacing */
    .form-group {
        margin-bottom: 1rem;
    }

    /* Consistent button sizing */
    .btn {
        min-height: 44px;
        padding: 0.625rem 1rem;
        font-size: 0.9rem;
    }

    .btn-sm {
        min-height: 38px;
        padding: 0.5rem 0.875rem;
        font-size: 0.85rem;
    }

    /* Clean footer */
    .card-footer {
        padding: 1rem;
        background: #fafafa;
        border-top: 1px solid #e8e8e8;
    }

    .card-footer h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: #333;
    }

    /* Clean dropdown */
    .dropdown-menu {
        font-size: 0.9rem;
        border: 1px solid #e0e0e0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .dropdown-item {
        padding: 0.75rem 1rem;
        transition: background-color 0.15s;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        background-color: #f5f5f5;
    }

    .dropdown-divider {
        margin: 0.5rem 0;
        border-color: #e8e8e8;
    }

    /* Clean form inputs */
    .form-control {
        font-size: 1rem;
        border-color: #e0e0e0;
    }

    .form-control:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.08);
    }

    /* Clean select2 */
    .select2-container--bootstrap4 .select2-selection {
        min-height: 44px;
        border-color: #e0e0e0;
    }

    /* Cleaner collapsible */
    .collapsible-section {
        padding: 0;
    }

    .collapsible-header {
        background: #fafafa;
        border: 1px solid #e8e8e8;
        box-shadow: none;
    }

    /* Adjust FAB positions */
    .fab-add-text {
        bottom: 80px;
        right: 16px;
    }

    .fab-add-text-left {
        bottom: 80px;
        left: 16px;
    }

    /* Clean card */
    .card {
        border: 1px solid #e8e8e8;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        border-radius: 8px;
    }

    /* Clean table-responsive */
    .table-responsive {
        border: none;
    }
}
    .collapsible-section {
        border-radius: 8px;
        padding: 1rem 0;
        margin-top: 1rem;
    }
    
    .collapsible-header {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #fafafa;
        border-radius: 8px;
        border: 1px solid #e8e8e8;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: background-color 0.2s;
    }
    
    .collapsible-header:hover {
        background-color: #f5f5f5;
    }
    
    /* Clean toggle button styling */
    .btn-toggle-input {
        border-left: none !important;
        transition: background-color 0.2s;
    }
    
    .btn-toggle-input:hover {
        background-color: #f5f5f5 !important;
    }
    
    .btn-toggle-input:active {
        transform: scale(0.98);
    }

    /* Mobile touch feedback */
    @media (max-width: 768px) {
        .btn:active {
            transform: scale(0.98);
        }

        /* Better form control focus */
        .form-control:focus {
            border-width: 1px;
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
var total = 0;

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
            showConfirmButton: false,
            showCancelButton: false,
            allowOutsideClick: false,
            html: `
                <div class="d-grid gap-2">
                    <button id="btnSave" class="swal2-confirm swal2-styled" style="background:#3085d6"><i class="fas fa-save pr-2"></i>Save</button>
                    <button id="btnSaveHome" class="swal2-confirm swal2-styled" style="background:#28a745"><i class="fas fa-save pr-2"></i>Home</button>
                    <button id="btnSavePDF" class="swal2-confirm swal2-styled" style="background:#d33"><i class="fas fa-save pr-2"></i>PDF</i>
                    <button id="btnCancel" class="swal2-cancel swal2-styled">Cancel</button>
                </div>
            `,
            didOpen: () => {
                const popup = Swal.getPopup();

                popup.querySelector('#btnSave').onclick = () => {
                    Swal.close();
                    this.submit();
                };

                popup.querySelector('#btnSaveHome').onclick = () => {
                    $('#toHome').val('1');
                    Swal.close();
                    this.submit();
                };

                popup.querySelector('#btnSavePDF').onclick = () => {
                    Swal.close();
                    showPdfOption.call(this);
                };

                popup.querySelector('#btnCancel').onclick = () => {
                    Swal.close();
                };
            }
        });
function showPdfOption() {
    Swal.fire({
        title: 'Pilih aksi PDF',
        icon: 'question',
        showConfirmButton: false,
        showCancelButton: false,
        html: `
            <div class="d-grid gap-2">
                <button id="btnView" class="swal2-confirm swal2-styled" style="background:#17a2b8">View</button>
                <button id="btnDownload" class="swal2-confirm swal2-styled" style="background:#f1c40f">Download</button>
                <button id="btnBack" class="swal2-cancel swal2-styled">Cancel</button>
            </div>
        `,
        didOpen: () => {
            const popup = Swal.getPopup();

            popup.querySelector('#btnView').onclick = () => {
                $('#toPDF').val('view');
                Swal.close();
                this.submit();
            };

            popup.querySelector('#btnDownload').onclick = () => {
                $('#toPDF').val('download');
                Swal.close();
                this.submit();
            };

            popup.querySelector('#btnBack').onclick = () => {
                Swal.close();
                // kembali ke modal pertama
                Swal.fire(arguments.callee.caller);
            };
        }
    });
}

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

    let sheet = null;
    $('.btn-discount').on('click', function(e) {
        e.preventDefault();
        $('#discountModal').modal('show');

        if (!sheet) {
            sheet = jspreadsheet(document.getElementById('spreadsheet'), {
                worksheets: [{
                    minDimensions: [2,3],
                    data: [
                        ["Description", "Formula"],
                        ["Subtotal", total],
                    ],
                    columns: [
                        { type: 'text', title: 'A', width: 200 },
                        { type: 'number', title: 'B', width: 120 },
                    ],
                    style: {
                        'A1': 'font-weight: bold',
                        'B1': 'font-weight: bold',
                    },
                }]
            });
        }

        loadDiscountDetails();
    });

    $('#saveDiscountDetails').on('click', function() {
        $.ajax({
            url: '{{ route("invoice.storeDiscount") }}',
            method: 'POST',
            data: {
                formid: '{{ $invoice->FormID ?? 0 }}',
                spreadsheetData: sheet[0].getData(),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#discountModal').modal('hide');
                Swal.fire('Berhasil!', 'Detail discount telah disimpan.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan detail discount.', 'error');
            }
        });
    })

    $('#resetDiscountDetails').on('click', function() {
        resetSheet();
    });

    function loadDiscountDetails() {
        $.ajax({
            url: '{{ route("invoice.getDiscount") }}',
            method: 'GET',
            data: {
                formid: '{{ $invoice->FormID ?? 0 }}',
            },
            success: function(response) {
                if (response.status !== 'success') return;

                // reset row
                resetSheet();

                let records = [];
                for (const data of response.discounts) {
                    if (data.Idx >= 3) {
                        sheet[0].insertRow();
                    }
                    records.push({
                        x: 0,
                        y: data.Idx,
                        value: data.Description,
                    }, {
                        x: 1,
                        y: data.Idx,
                        value: data.Formula,
                    });
                }
                // add rows if needed
                sheet[0].setValue(records);
            },
            error: function(xhr) {
                Swal.fire('Gagal!', 'Terjadi kesalahan saat memuat detail discount.', 'error');
            }
        });
    }

    function resetSheet() {
        jspreadsheet.destroy(document.getElementById('spreadsheet'));
        
        sheet = jspreadsheet(document.getElementById('spreadsheet'), {
            worksheets: [{
                minDimensions: [2,3],
                data: [
                    ["Description", "Formula"],
                    ["Subtotal", total],
                ],
                columns: [
                    { type: 'text', title: 'A', width: 200 },
                    { type: 'number', title: 'B', width: 120 },
                ],
                style: {
                    'A1': 'font-weight: bold',
                    'B1': 'font-weight: bold',
                },
            }]
        });
    }

    $('.btn-toggle-dos').on('click', function(e) {
        e.preventDefault();
        toggleDetails();
    });

    // Mobile toggle dos handler
    $(document).on('click', '.btn-toggle-dos-mobile', function(e) {
        e.preventDefault();
        toggleDetails();
        // Update mobile button text
        const isVisible = $('.dos-column').first().is(':visible');
        if (isVisible) {
            $(this).html('<i class="fas fa-eye mr-2"></i>Show Dos');
        } else {
            $(this).html('<i class="fas fa-eye-slash mr-2"></i>Hide Dos');
        }
    });

    // Mobile button handlers
    $('#btnAddDetailMobile').on('click', function() {
        $('#btnAddDetail').click();
    });

    $('#btnAddByTextMobileMenu').on('click', function() {
        $('#btnAddByText').click();
    });

    $('#btnAddDetailMultipleMobile').on('click', function() {
        $('#btnAddDetailMultiple').click();
    });

    $(document).on('click', '.btn-lock-mobile', function(e) {
        e.preventDefault();
        const $lockBtn = $('.btn-lock');
        $lockBtn.click();
        // Update mobile button text based on lock state
        setTimeout(() => {
            if (isLocked) {
                $(this).html('<i class="fas fa-unlock mr-2"></i>Unlock');
            } else {
                $(this).html('<i class="fas fa-lock mr-2"></i>Lock');
            }
        }, 100);
    });

    $(document).on('click', '.btn-clear-mobile', function(e) {
        e.preventDefault();
        $('.btn-clear').click();
    });

    function toggleDetails() {
        const $icon = $('.btn-toggle-dos').find('i');
        const $text = $('.btn-toggle-dos').find('span');
        
        if ($('.dos-column').first().is(':visible')) {
            // Hide dos columns
            $('.dos-column').hide();
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
            $text.text('Show Details');
            // Update colspan untuk SJ label (6 - 2 dos columns = 4)
            $('.sj-label').attr('colspan', '4');
        } else {
            // Show dos columns
            $('.dos-column').show();
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
            $text.text('Hide Details');
            // Restore colspan untuk SJ label (original = 6)
            $('.sj-label').attr('colspan', '6');
        }
    }

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
                <input type="hidden" name="isinvoice[]" value="1" />
                ${!isSJ ? `
                <td data-label="Product">
                    <div class="input-group">
                        <select class="form-control select2-tags product-select" name="product[]" style="display: block;">
                            <option value="">Pilih Product</option>
                        </select>
                        <input type="text" class="form-control product-input-manual" name="product_manual[]" style="display: none;" placeholder="Ketik nama product...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary btn-toggle-input" type="button" title="Toggle antara Select dan Input">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                    </div>
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
                <td class="dos-column" data-label="Dos / Isi">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control numeric dos-luar-input" name="dosluar[]" placeholder="Dos" value="0">
                        <input type="text" class="form-control isi-input" name="isi[]" placeholder="Isi">
                    </div>
                </td>
                <td class="dos-column" data-label="Dos Gabung">
                    <input type="text" class="form-control numeric dos-gabung-input" name="dosgabung[]" />
                </td>
                <td class="dos-column" data-label="Inv">
                    <input type="checkbox" class="form-control inv-checkbox" name="inv[]" checked />
                </td>
                <td class="dos-column" data-label="SJ">
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
                <td class="dos-column d-none">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control numeric dos-luar-input" name="dosluar[]" placeholder="Dos" value="0">
                        <input type="text" class="form-control isi-input" name="isi[]" placeholder="Isi">
                    </div>
                </td>
                <td class="dos-column d-none">
                    <input type="text" class="form-control numeric dos-gabung-input" name="dosgabung[]" />
                </td>
                <td class="d-none"><input type="checkbox" class="form-control sj-checkbox" name="sj[]" /></td>
                <td colspan="6" class="sj-label text-center pt-3 text-secondary">-- SJ Item --</td>
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
                    $('#IsKonsinyasi').prop('checked', response.customer.IsKonsinyasi == 1);
                } else {
                    $('#alamatcustomer').val('');
                    $('#telpcustomer').val('');
                    $('#categorycustomer').val('');
                    $('#JatuhTempo').val('');
                    $('#JatuhTempoSatuan').val('1').trigger('change');
                    $('#IsKonsinyasi').prop('checked', false);
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
    
    $(document).on('blur', '.product-input-manual', function (e) {
        if (isLocked) {
            return;
        }
        var productName = $(this).val();
        if (productName) {
            var $row = $(this).closest('tr, .card-body');
            getProductDetails(productName, function(productDetails) {
                if (productDetails) {
                    $row.find('input[name="unit[]"]').val(productDetails.Satuan);
                    var category = $('#categorycustomer').val();
                    var price = category === '1' ? productDetails.price.PriceKonsumen
                        : category === '2' ? productDetails.price.PriceSup1
                        : category === '3' ? productDetails.price.PriceSup2
                        : productDetails.price.PriceDistributor;
                    $row.find('input[name="price[]"]').val(price).trigger('change');
                }
            });
        }
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
        total = quantity * price;
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

    $(document).on('change', '.inv-checkbox', function (e) {
        var row = $(this).closest('tr, .card');
        if ($(this).is(':checked')) {
            row.find('input[name="isinvoice[]"]').val('1');
        } else {
            row.find('input[name="isinvoice[]"]').val('0');
        }
        embedPreviewData();
    });

    $(document).on('click', '.btn-toggle-input', function (e) {
        e.preventDefault();
        var $container = $(this).closest('td').find('.input-group');
        var $select = $container.find('.product-select');
        var $input = $container.find('.product-input-manual');
        var $select2Container = $select.next('.select2-container');
        
        if ($select.is(':visible')) {
            // Switch to manual input
            var currentValue = $select.val();
            $input.val(currentValue);
            $select.hide();
            $select2Container.hide(); // Hide select2 container juga
            $input.show().focus();
            $(this).html('<i class="fas fa-list"></i>');
            $(this).attr('title', 'Kembali ke Select');
            
            // Disable name attribute pada select, enable pada input
            $select.attr('name', 'product_disabled[]');
            $input.attr('name', 'product[]');
        } else {
            // Switch to select2
            var currentValue = $input.val();
            if (currentValue) {
                // Add option if not exists
                if ($select.find("option[value='" + currentValue + "']").length === 0) {
                    $select.append(new Option(currentValue, currentValue, true, true));
                } else {
                    $select.val(currentValue);
                }
                $select.trigger('change');
            }
            $input.hide();
            $select.show();
            $select2Container.show(); // Show select2 container kembali
            $(this).html('<i class="fas fa-exchange-alt"></i>');
            $(this).attr('title', 'Toggle antara Select dan Input');
            
            // Enable name attribute pada select, disable pada input
            $select.attr('name', 'product[]');
            $input.attr('name', 'product_manual[]');
        }
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

        // update grand total
        $('#grand-total').text(getGrandTotal().toLocaleString('id-ID'));
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
            $lastRow.find('.quantity-input').val('{{ floor($detail->Qty) != $detail->Qty ? number_format($detail->Qty, 1, ",", ".") : number_format($detail->Qty, 0, ",", ".") }}');
            $lastRow.find('input[name="unit[]"]').val('{{ $detail->Satuan }}');
            $lastRow.find('.price-input').val('{{ number_format($detail->Harga, 0, ",", ".") }}');
            $lastRow.find('input[name="total[]"]').val('{{ number_format($detail->Harga * $detail->Qty, 0, ",", ".") }}');
            $lastRow.find('.dos-luar-input').val('{{ $detail->DosLuar == 1 ? 1 : $detail->DosLuar }}');
            $lastRow.find('.isi-input').val('{{ $detail->DosLuar == 1 ? $detail->Qty : $detail->Isi }}');
            $lastRow.find('.dos-gabung-input').val('{{ $detail->DosGabung }}');
            $lastRow.find('input[name="detailid[]"]').val('{{ $detail->DetailID }}');
            $lastRow.find('input[name="type[]"]').val('update');
            $lastRow.find('.inv-checkbox').prop('checked', {{ $detail->IsInvoice ?? 1 == 1 ? 'true' : 'false' }});
            $lastRow.find('input[name="isinvoice[]"]').val('{{ $detail->IsInvoice ?? 1 == 1 ? 1 : 0 }}');
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
        total = getGrandTotal();
        $('#grand-total').text(total.toLocaleString('id-ID'));
        toggleDetails();
    @else
        // @for($i = 0; $i < 13; $i++)
        //     addProduct();
        // @endfor
    @endif
    
    $('.form-type-checkbox').on('change', function() {
        let isInvoiceChecked = $('#IsInvoice').is(':checked');
        let isSJChecked = $('#IsSJ').is(':checked');

        // Jika keduanya false → hidupkan checkbox satunya otomatis
        if (!isInvoiceChecked && !isSJChecked) {
            // Tentukan mana yang diklik
            if ($(this).attr('id') === 'IsInvoice') {
                // User baru saja uncheck Invoice → hidupkan SJ
                $('#IsSJ').prop('checked', true);
                isSJChecked = true;
            } else {
                // User baru saja uncheck SJ → hidupkan Invoice
                $('#IsInvoice').prop('checked', true);
                isInvoiceChecked = true;
            }
        }

        // Tampilkan/hilangkan container
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
        // loop klik button delete semua
        $('#product-list-body tr').each(function() {
            $(this).find('.btn-remove-product').trigger('click');
        });
        isChanged = true;
        embedPreviewData();
    });

    embedPreviewData();
});


</script>
@endsection
