@extends('layouts.app')

@section('title', 'MPR | '.($invoice == null ? 'Create' : 'Edit') .' Invoice')

@section('content')
<style>
    .modern-card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    .modern-card:hover {
        box-shadow: 0 6px 30px rgba(0,0,0,0.12);
    }
    .modern-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 20px 30px;
        border: none;
    }
    .section-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .section-header-blue {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .section-header-green {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    .modern-form-label {
        font-weight: 600;
        color: #2d3748;
        font-size: 0.95rem;
        margin-bottom: 8px;
    }
    .modern-input {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 12px 16px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    .modern-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .modern-checkbox {
        transform: scale(1.2);
        margin-right: 8px;
    }
    .checkbox-group {
        background: #f7fafc;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 10px;
    }
    .section-divider {
        height: 3px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        margin: 25px 0;
    }
    .btn-modern {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .btn-modern-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .btn-modern-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }
    .btn-modern-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 25px;
    }
    .info-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .collapsible-section {
        background: #f7fafc;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
    .collapsible-header {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .collapsible-header:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .form-card-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }
    .section-title i {
        margin-right: 10px;
        color: #667eea;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <form id="formInput" class="col-md-12" method="post" action="{{ ($invoice == null || ($isDuplicate ?? null)) ? route('invoice.store') : route('invoice.update', $invoice->FormID) }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @if($invoice != null && !($isDuplicate ?? null))
                @method('PUT')
            @endif
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title mb-0">
                    <i class="fas fa-file-invoice mr-2"></i>
                    {{ ($isDuplicate ?? null) ? 'Duplicate Invoice' : ($invoice != null ? 'Edit Invoice' : 'Create Invoice') }}
                </h1>
                <span class="info-badge">
                    <i class="fas fa-calendar-alt mr-2"></i>{{ Carbon\Carbon::now()->format('d M Y') }}
                </span>
            </div>
            
            <div class="card modern-card">
                <div class="card-header modern-card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-edit mr-3" style="font-size: 1.5rem;"></i>
                            <span style="font-size: 1.2rem; font-weight: 600;">Invoice Form</span>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('invoice.index') }}" type="button" class="btn btn-sm btn-light">
                                <i class="fas fa-angle-left mr-lg-2"></i><span class="d-none d-lg-inline">Back</span>
                            </a>
                            <button type="button" class="btn btn-sm btn-light btn-toggle-preview">
                                <i class="fas fa-eye-slash mr-2"></i><span class="d-none d-lg-inline">Hide Preview</span>
                            </button>
                            @if($invoice != null)
                            @endif
                            <button type="submit" class="btn btn-sm btn-modern btn-modern-success btn-submit">
                                <i class="fas fa-save mr-2"></i>Save
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body row" style="padding: 30px;">
                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 5px solid #f5576c;">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 5px solid #f5576c;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br />
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    
                    <div class="col-md-6 order-2 order-md-1" id="iframe-container">
                        <div style="position: sticky; top: 20px;">
                            <div class="card modern-card">
                                <div class="card-header section-header-blue">
                                    <i class="fas fa-file-pdf mr-2"></i>Live Preview
                                </div>
                                <div class="card-body p-0">
                                    <iframe
                                        id="print_preview"
                                        src=""
                                        style="width: 100%; height: 750px; border: none; border-radius: 0 0 15px 15px;"
                                    ></iframe>
                                </div>
                                <div class="card-footer text-center" style="background: #fff3cd; border-radius: 0 0 15px 15px;">
                                    <small class="text-warning"><i class="fas fa-info-circle mr-2"></i>Preview mungkin tidak sepenuhnya akurat. Buka PDF untuk tampilan final.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 order-1 order-md-2" id="form-container">
                        <input type="hidden" id="toPDF" name="toPDF" value="0">
                        <input type="hidden" id="toHome" name="toHome" value="0">
                        
                        <!-- Form Type Section -->
                        <div class="form-card-section">
                            <div class="section-title">
                                <i class="fas fa-file-alt"></i>Tipe Form
                            </div>
                            <div class="checkbox-group">
                                <div class="form-check form-check-inline mr-4">
                                    <input class="form-check-input modern-checkbox form-type-checkbox" type="checkbox" name="IsInvoice" id="IsInvoice" value="IsInvoice" {{ $invoice != null ? ($invoice->InvoiceNo == null ? "" : "checked") : "checked" }}>
                                    <label class="form-check-label" for="IsInvoice">
                                        <i class="fas fa-file-invoice text-primary mr-1"></i><strong>Invoice</strong>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input modern-checkbox form-type-checkbox" type="checkbox" name="IsSJ" id="IsSJ" value="IsSJ"{{ $invoice != null ? ($invoice->SJNo == null ? "" : "checked") : "checked" }} >
                                    <label class="form-check-label" for="IsSJ">
                                        <i class="fas fa-truck text-success mr-1"></i><strong>Surat Jalan</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Invoice & SJ Numbers -->
                        <div class="form-card-section invoice-container {{ $invoice != null ? ($invoice->InvoiceNo == null ? 'd-none' : '') : '' }}">
                            <div class="form-group row align-items-center">
                                <label for="invoiceno" class="modern-form-label col-md-4 mb-0">
                                    <i class="fas fa-hashtag text-primary mr-2"></i>Invoice No.
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control modern-input readonly-color" id="invoiceno" name="invoiceno"
                                        value="{{ ($isDuplicate ?? null) ? app(\App\Models\HInvoice::class)->getNewInvoiceNo() : ($invoice->InvoiceNo ?? app(\App\Models\HInvoice::class)->getNewInvoiceNo()) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-card-section sj-container {{ $invoice != null ? ($invoice->SJNo == null ? 'd-none' : '') : '' }}">
                            <div class="form-group row align-items-center">
                                <label for="sjno" class="modern-form-label col-md-4 mb-0">
                                    <i class="fas fa-truck text-success mr-2"></i>Surat Jalan No.
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control modern-input readonly-color" id="sjno" name="sjno"
                                        value="{{ ($isDuplicate ?? null) ? app(\App\Models\HInvoice::class)->getNewSJNo() : ($invoice->SJNo ?? app(\App\Models\HInvoice::class)->getNewSJNo()) }}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date & Code Section -->
                        <div class="form-card-section">
                            <div class="section-title">
                                <i class="fas fa-calendar-check"></i>Tanggal & Kode
                            </div>
                            <div class="form-group row align-items-center mb-3">
                                <label for="invoicedate" class="modern-form-label col-md-4 mb-0">
                                    <i class="fas fa-calendar-alt text-info mr-2"></i>Tanggal Invoice
                                </label>
                                <div class="col-md-8">
                                    <input style="background-color: #e3f2fd" type="text" class="form-control modern-input datepicker" id="invoicedate" name="invoicedate"
                                        value="{{ $invoice != null ? Carbon\Carbon::parse($invoice->InvoiceDate)->format('d/m/Y') : Carbon\Carbon::now()->format('d/m/Y') }}" tabindex="1">
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-3">
                                <label for="sjdate" class="modern-form-label col-md-4 mb-0">
                                    <i class="fas fa-calendar-day text-warning mr-2"></i>Tanggal SJ
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control modern-input datepicker" id="sjdate" name="sjdate"
                                        value="{{ $invoice != null ? Carbon\Carbon::parse($invoice->SJDate)->format('d/m/Y') : Carbon\Carbon::now()->format('d/m/Y') }}">
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-0">
                                <label for="kode" class="modern-form-label col-md-4 mb-0">
                                    <i class="fas fa-barcode text-secondary mr-2"></i>Kode
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control modern-input" id="kode" name="kode"
                                        value="{{ $invoice->Kode ?? '' }}" tabindex="3">
                                </div>
                            </div>
                        </div>
                        
                        <div class="section-divider"></div>
                        
                        <!-- Customer Information -->
                        <div class="form-card-section">
                            <div class="section-title">
                                <i class="fas fa-user-tie"></i>Informasi Customer
                            </div>
                            <div class="form-group row align-items-center mb-3">
                                <label for="namacustomer" class="modern-form-label col-md-4 mb-0">
                                    <i class="fas fa-user text-danger mr-2"></i>Nama Customer
                                </label>
                                <div class="col-md-8">
                                    <select style="background-color: #ffe6e6" class="form-control modern-input select2-tags" id="namacustomer" name="namacustomer" tabindex="2">
                                    <select style="background-color: #ffe6e6" class="form-control modern-input select2-tags" id="namacustomer" name="namacustomer" tabindex="2">
                                        <option value="">Pilih Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer }}" {{ (isset($invoice) && $invoice->NamaCustomer == $customer) ? 'selected' : '' }}>
                                                {{ $customer }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-3">
                                <label for="alamatcustomer" class="modern-form-label col-md-4 mb-0">
                                    <i class="fas fa-map-marker-alt text-danger mr-2"></i>Alamat
                                </label>
                                <div class="col-md-8">
                                    <textarea class="form-control modern-input" rows="3" id="alamatcustomer" name="alamatcustomer">{{ $invoice->AlamatCustomer ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-3">
                                <label for="telpcustomer" class="modern-form-label col-md-4 mb-0">
                                    <i class="fas fa-phone text-success mr-2"></i>Telepon
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control modern-input" id="telpcustomer" name="telpcustomer"
                                        value="{{ $invoice->TelpCustomer ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row align-items-center mb-0">
                                <label for="categorycustomer" class="modern-form-label col-md-4 mb-0">
                                    <i class="fas fa-tags text-info mr-2"></i>Kategori
                                </label>
                                <div class="col-md-8">
                                    <select class="form-control modern-input select2-tags" id="categorycustomer" name="categorycustomer">
                                        <option {{ (isset($invoice) && $invoice->PriceCategory == 1) ? 'selected' : '' }} value="1">Konsumen</option>
                                        <option {{ (isset($invoice) && $invoice->PriceCategory == 2) ? 'selected' : '' }} value="2">Supplier-1</option>
                                        <option {{ (isset($invoice) && $invoice->PriceCategory == 3) ? 'selected' : '' }} value="3">Supplier-2</option>
                                        <option {{ (isset($invoice) && $invoice->PriceCategory == 4) ? 'selected' : '' }} value="4">Distributor</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section-divider"></div>
                        
                        <!-- Options Section (Collapsible) -->
                        <div class="collapsible-section">
                            <div class="collapsible-header" data-toggle="collapse" href="#collapse1">
                                <div>
                                    <i class="fas fa-cog mr-2"></i>
                                    <strong>Advanced Options</strong>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div id="collapse1" class="collapse mt-3">
                            <div id="collapse1" class="collapse mt-3">
                                <div class="form-card-section mb-3">
                                    <div class="checkbox-group">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input modern-checkbox form-type-checkbox" type="checkbox" name="IsKonsinyasi" id="IsKonsinyasi" value="IsKonsinyasi" {{ $invoice != null ? ((($invoice->IsKonsinyasi ?? 0) == 0) ? "" : "checked") : "" }}>
                                                    <label class="form-check-label" for="IsKonsinyasi">
                                                        <i class="fas fa-handshake text-warning mr-1"></i>Konsinyasi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input modern-checkbox form-type-checkbox" type="checkbox" name="IsEkspedisi" id="IsEkspedisi" value="IsEkspedisi" {{ $invoice != null ? ((($invoice->IsEkspedisi ?? 0) == 0) ? "" : "checked") : "" }}>
                                                    <label class="form-check-label" for="IsEkspedisi">
                                                        <i class="fas fa-shipping-fast text-info mr-1"></i>Ekspedisi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input modern-checkbox form-type-checkbox" type="checkbox" name="IsReseller" id="IsReseller" value="IsReseller" {{ $invoice != null ? ((($invoice->IsReseller ?? 0) == 0) ? "" : "checked") : "checked" }}>
                                                    <label class="form-check-label" for="IsReseller">
                                                        <i class="fas fa-store text-success mr-1"></i>Reseller
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input modern-checkbox form-type-checkbox" type="checkbox" name="IsKopSurat" id="IsKopSurat" value="IsKopSurat" {{ $invoice != null ? ((($invoice->IsKopSurat ?? 0) == 0) ? "" : "checked") : "checked" }}>
                                                    <label class="form-check-label" for="IsKopSurat">
                                                        <i class="fas fa-file-alt text-primary mr-1"></i>Kop Surat (SJ)
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input modern-checkbox form-type-checkbox" type="checkbox" name="IsSJCustomer" id="IsSJCustomer" value="IsSJCustomer" {{ $invoice != null ? ((($invoice->IsSJCustomer ?? 0) == 0) ? "" : "checked") : "checked" }}>
                                                    <label class="form-check-label" for="IsSJCustomer">
                                                        <i class="fas fa-user-check text-secondary mr-1"></i>SJ Customer
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input modern-checkbox form-type-checkbox" type="checkbox" name="IsLarge" id="IsLarge" value="IsLarge" {{ $invoice != null ? ((($invoice->IsLarge ?? 0) == 0) ? "" : "checked") : "" }}>
                                                    <label class="form-check-label" for="IsLarge">
                                                        <i class="fas fa-expand-arrows-alt text-danger mr-1"></i>Uk. Besar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input modern-checkbox form-type-checkbox" type="checkbox" name="IsDiscount" id="IsDiscount" value="IsDiscount" {{ $invoice != null ? ((($invoice->IsDiscount ?? 0) == 0) ? "" : "checked") : "" }}>
                                                    <label class="form-check-label" for="IsDiscount">
                                                        <i class="fas fa-percentage text-warning mr-1"></i>Discount
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                                <div class="form-card-section mb-3">
                                    <div class="form-group row align-items-center mb-0">
                                        <label for="JatuhTempo" class="modern-form-label col-md-4 mb-0">
                                            <i class="fas fa-clock text-warning mr-2"></i>Jatuh Tempo
                                        </label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="numeric form-control modern-input" id="JatuhTempo" name="JatuhTempo"
                                                    value="{{ $invoice != null ? ($invoice->JatuhTempo ?? '0') : '0' }}">                                
                                                <div class="input-group-append">
                                                    <select name="JatuhTempoSatuan" id="JatuhTempoSatuan" class="form-control modern-input" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" required>
                                                        <option {{ ($invoice != null ? ($invoice->JatuhTempoSatuan == "1") ? 'selected' : '' : '') }} value="1">Hari</option>
                                                        <option {{ ($invoice != null ? ($invoice->JatuhTempoSatuan == "2") ? 'selected' : '' : '') }} value="2">Minggu</option>
                                                        <option {{ ($invoice != null ? ($invoice->JatuhTempoSatuan == "3") ? 'selected' : '' : '') }} value="3">Bulan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ekspedisi-container {{ $invoice != null ? (($invoice->IsEkspedisi ?? 0) == 0 ? "d-none" : "") : "d-none" }}">
                                    <div class="form-card-section mb-3">
                                        <div class="section-title" style="font-size: 1rem;">
                                            <i class="fas fa-shipping-fast"></i>Informasi Ekspedisi
                                        </div>
                                        <div class="form-group row align-items-center mb-2">
                                            <label for="NamaEkspedisi" class="modern-form-label col-md-4 mb-0">Nama</label>
                                            <div class="col-md-8">
                                                <select class="form-control modern-input select2-tags" id="NamaEkspedisi" name="NamaEkspedisi">
                                                    <option value="">Pilih Ekspedisi</option>
                                                    @foreach($ekspedisi as $expedisi)
                                                        <option value="{{ $expedisi }}" {{ (isset($invoice) && $invoice->NamaEkspedisi == $expedisi) ? 'selected' : '' }}>
                                                            {{ $expedisi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center mb-2">
                                            <label for="AlamatEkspedisi" class="modern-form-label col-md-4 mb-0">Alamat</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control modern-input" rows="2" id="AlamatEkspedisi" name="AlamatEkspedisi">{{ $invoice->AlamatEkspedisi ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center mb-0">
                                            <label for="TelpEkspedisi" class="modern-form-label col-md-4 mb-0">Telepon</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control modern-input" id="TelpEkspedisi" name="TelpEkspedisi"
                                                    value="{{ $invoice->TelpEkspedisi ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ttd-container {{ $invoice != null ? (($invoice->IsKopSurat ?? 0) == 0 ? "d-none" : "") : "d-none" }}">
                                    <div class="form-card-section mb-3">
                                        <div class="form-group row align-items-center mb-0">
                                            <label for="TTD" class="modern-form-label col-md-4 mb-0">
                                                <i class="fas fa-signature text-primary mr-2"></i>TTD Customer
                                            </label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control modern-input" id="TTD" name="TTD"
                                                    value="{{ $invoice->TTD ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="discount-container {{ $invoice != null ? (($invoice->IsDiscount ?? 0) == 0 ? "d-none" : "") : "d-none" }}">
                                    <div class="form-card-section mb-3">
                                        <div class="form-group row align-items-center mb-0">
                                            <label for="Discount" class="modern-form-label col-md-4 mb-0">
                                                <i class="fas fa-percentage text-success mr-2"></i>Discount
                                            </label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control modern-input numeric" id="Discount" name="Discount"
                                                    value="{{ $invoice->Discount ?? '' }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" style="border-radius: 0 10px 10px 0;">%</span>
                                                    </div>
                                                    <button class="btn btn-modern btn-modern-success btn-discount ml-2"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-card-section mb-3">
                                    <div class="form-group row align-items-center mb-3">
                                        <label for="Notes" class="modern-form-label col-md-4 mb-0">
                                            <i class="fas fa-sticky-note text-warning mr-2"></i>Notes
                                        </label>
                                        <div class="col-md-8">
                                            <textarea rows="3" class="form-control modern-input" id="Notes" name="Notes">{{ $invoice->Notes ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center mb-0">
                                        <label for="NotesSJ" class="modern-form-label col-md-4 mb-0">
                                            <i class="fas fa-comment-alt text-info mr-2"></i>Notes SJ
                                        </label>
                                        <div class="col-md-8">
                                            <textarea rows="3" class="form-control modern-input" id="NotesSJ" name="NotesSJ">{{ $invoice->NotesSJ ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            {{-- ITEM LIST --}}
            <button class="btn btn-modern btn-modern-primary d-md-none fab-add-text-left" type="button" id="btnAddByTextMobile" onclick="$('#btnAddByText').click();">
                <i class="fas fa-font"></i>
            </button>
            <button class="btn btn-modern btn-modern-success d-md-none fab-add-text" type="button" id="btnAddByTextMobile" onclick="$('.btn-submit').click();">
                <i class="fas fa-save"></i>
            </button>
            
            <div class="card modern-card">
                <div class="card-header section-header-green">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-box-open mr-3" style="font-size: 1.5rem;"></i>
                            <span style="font-size: 1.2rem; font-weight: 600;">Product List</span>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-modern btn-modern-danger btn-clear">
                                <i class="fas fa-trash mr-lg-2"></i><span class="d-none d-lg-inline">Clear</span>
                            </button>
                            <button class="btn btn-sm btn-light btn-lock">
                                <i class="fas fa-lock mr-lg-2"></i><span class="d-none d-lg-inline">Lock</span>
                            </button>
                            <button class="btn btn-sm btn-light" id="btnAddDetailMultiple" type="button">
                                <i class="fas fa-plus mr-lg-2"></i><span class="d-none d-lg-inline">Add Multiple</span>
                            </button>
                            <button class="btn btn-sm btn-modern btn-modern-primary" type="button" id="btnAddDetail">
                                <i class="fas fa-plus mr-2"></i>Add<span class="d-none d-lg-inline"> Item</span>
                            </button>
                            <button class="btn btn-sm btn-modern btn-modern-success" type="button" id="btnAddByText">
                                <i class="fas fa-font mr-lg-2"></i><span class="d-none d-lg-inline">Add by Text</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding: 25px;">
                    <div class="table-responsive">
                    <table class="dataTable table table-hover table-bordered w-100 responsive-table" id="product-list-table" style="border-radius: 10px; overflow: hidden;">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th width="500"><i class="fas fa-barcode mr-2"></i>Inv No.</th>
                            <th><i class="fas fa-sort-numeric-up mr-2"></i>Quantity</th>
                            <th><i class="fas fa-cube mr-2"></i>Unit</th>
                            <th width="100"><i class="fas fa-dollar-sign mr-2"></i>Price</th>
                            <th width="150"><i class="fas fa-calculator mr-2"></i>Total</th>
                            <th><i class="fas fa-boxes mr-2"></i>Dos Luar / Isi</th>
                            <th><i class="fas fa-layer-group mr-2"></i>Dos Gabung</th>
                            <th>Inv</th>
                            <th>SJ</th>
                            <th><i class="fas fa-cog mr-2"></i>Action</th>
                        </tr>
                        </thead>
                        <tbody id="product-list-body"></tbody>
                    </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center" style="background: #f7fafc; border-radius: 0 0 15px 15px; padding: 20px 25px;">
                    <button onclick="window.scrollTo({top: 1000, behavior: 'smooth'}); return false" class="btn btn-modern btn-modern-primary">
                        <i class="fas fa-arrow-up mr-2"></i>Scroll Up
                    </button>
                    <h4 class="text-right mb-0">
                        <span class="text-muted">Total:</span> 
                        <span class="info-badge" style="font-size: 1.2rem; padding: 8px 20px;" id="grand-total">0</span>
                    </h4>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- modal discount --}}
<div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header section-header-green" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="discountModalLabel">
                    <i class="fas fa-percentage mr-2"></i>Detail Discount
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <div id="spreadsheet"></div>
            </div>
            <div class="modal-footer" style="background: #f7fafc; border-radius: 0 0 15px 15px;">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button class="btn btn-modern btn-modern-danger" id="resetDiscountDetails">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
                <button type="button" class="btn btn-modern btn-modern-success" id="saveDiscountDetails">
                    <i class="fas fa-save mr-2"></i>Save Details
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    li.select2-results__option {
        white-space: nowrap;
    }
    
    /* Animasi smooth */
    * {
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    
    /* Enhanced Input Focus Effects */
    .modern-input:focus,
    .select2-container--default .select2-selection--single:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        transform: translateY(-1px);
    }
    
    /* Table Enhancements */
    #product-list-table tbody tr {
        transition: all 0.3s ease;
    }
    
    #product-list-table tbody tr:hover {
        background-color: #f7fafc;
        transform: translateX(5px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    #product-list-table thead th {
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        border: none !important;
        padding: 15px !important;
    }
    
    #product-list-table tbody td {
        vertical-align: middle;
        padding: 12px !important;
    }
    
    /* Collapsible Animation */
    .collapsible-header i:last-child {
        transition: transform 0.3s ease;
    }
    
    .collapsible-header[aria-expanded="true"] i:last-child {
        transform: rotate(180deg);
    }
    
    /* Loading Animation for Preview */
    #print_preview {
        border: none;
        transition: opacity 0.3s ease;
    }
    
    /* Button Hover Enhancements */
    .btn-group .btn {
        transition: all 0.3s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-2px);
    }
    
    /* Checkbox Modern Style */
    .modern-checkbox:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    /* Alert Styling */
    .alert {
        border-radius: 12px !important;
        border-left-width: 5px !important;
        animation: slideInDown 0.5s ease;
    }
    
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Card Animations */
    .form-card-section {
        animation: fadeInUp 0.5s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Select2 Customization */
    .select2-container--default .select2-selection--single {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        height: calc(2.25rem + 2px);
        padding: 6px 12px;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #667eea;
    }
    
    /* FAB Button Pulse Effect */
    .fab-add-text, .fab-add-text-left {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        }
        50% {
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.5);
        }
    }
    
    /* Readonly Input Styling */
    .readonly-color {
        background-color: #f0f4f8 !important;
        cursor: not-allowed;
    }

/* --- Versi Responsive --- */
@media (max-width: 1200px) {
  .responsive-table thead {
    display: none; /* sembunyikan header tabel */
  }

  .responsive-table tr {
    display: block;
    margin-bottom: 1.5rem;
    border: none;
    border-radius: 12px;
    background: white;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    padding: 1rem;
  }

  .responsive-table td {
    display: block;
    border: none;
    padding: 0.75rem 0;
  }

  .responsive-table td::before {
    content: attr(data-label);
    display: block;
    font-weight: 700;
    text-transform: capitalize;
    color: #667eea;
    margin-bottom: 6px;
    font-size: 0.85rem;
  }

  .responsive-table td input,
  .responsive-table td select {
    width: 100%;
    font-size: 0.95rem;
    border-radius: 8px;
  }

  .responsive-table td[data-label="Dos / Isi"] .input-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 0;
  }

  .responsive-table td[data-label="Dos / Isi"] .input-group input {
    width: 100%;
  }
  
  .responsive-table .invoice-item-row:nth-child(odd) {
    background: linear-gradient(135deg, #fff3f1 0%, #ffe6e6 100%) !important;
  }

  .responsive-table .invoice-item-row:nth-child(even) {
    background: linear-gradient(135deg, #ffffe6 0%, #ffffcc 100%) !important;
  }
  
  /* Mobile adaptations */
  .page-title {
    font-size: 1.5rem !important;
  }
  
  .modern-card-header {
    padding: 15px 20px !important;
  }
  
  .form-card-section {
    padding: 15px !important;
  }
  
  .section-title {
    font-size: 1rem !important;
  }
  
  .btn-group {
    flex-wrap: wrap !important;
  }
  
  .btn-group .btn {
    margin-bottom: 5px !important;
  }

}

@media (max-width: 768px) {
  .info-badge {
    font-size: 0.75rem;
    padding: 3px 10px;
  }
  
  .modern-form-label {
    font-size: 0.85rem;
  }
  
  .modern-input {
    font-size: 0.85rem;
    padding: 10px 12px;
  }
}
.fab-add-text {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    border: none;
}
.fab-add-text:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
}
.fab-add-text-left {
    position: fixed;
    bottom: 20px;
    left: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    border: none;
}
.fab-add-text-left:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
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
                <td data-label="Inv">
                    <input type="checkbox" class="form-control inv-checkbox" name="inv[]" checked />
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
                <td colspan="6" class="text-center pt-3 text-secondary">-- SJ Item --</td>
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
