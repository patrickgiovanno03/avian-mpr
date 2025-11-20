@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <form id="formInput" class="col-md-12" method="post" action="{{ ($tt == null) ? route('tt.store') : route('tt.update', $tt->FormID) }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @if($tt != null && !($isDuplicate ?? null))
                @method('PUT')
            @endif
            <h1 class="p-0">Form</h1>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <div>{{ ($isDuplicate ?? null) ? 'Duplicate' : ($tt != null ? 'Edit' : 'Create') }} Tanda Terima</div>
                        <div class="btn-group">
                            <a href="{{ route('tt.index') }}" type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-angle-left mr-lg-2"></i><span class="d-none d-lg-inline">Back</span>
                            </a>
                            {{-- <button type="button" class="btn btn-sm btn-outline-secondary btn-excel">
                                <i class="fas fa-file-excel mr-2"></i>Excel
                            </button> --}}
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-preview">
                                <i class="fas fa-eye-slash mr-2"></i>Hide Preview
                            </button>
                            @if($tt != null)
                            <a target="_blank" href="{{ route('tt.previewdynamic', $tt->FormID) }}" type="button" class="btn btn-sm btn-outline-secondary btn-pdf">
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
                            src="{{ route('tt.previewdynamic', $hbacara->BAcaraID) }}" 
                            type="application/pdf" 
                            width="90%" 
                            height="900px" /> --}}
                    </div>
                    <div class="col-md-6 order-1 order-md-2" id="form-container">
                        <input type="hidden" id="toPDF" name="toPDF" value="0">
                        <div class="form-group row">
                            <label for="ttno" class="form-label col-md-3">Tanda Terima No.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="ttno" name="ttno"
                                    value="{{ ($isDuplicate ?? null) ? app(\App\Models\HTandaTerima::class)->getNewTTNo() : ($tt->TTNo ?? app(\App\Models\HTandaTerima::class)->getNewTTNo()) }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ttdate" class="form-label col-md-3">Tanggal Tanda Terima</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datepicker" id="ttdate" name="ttdate"
                                    value="{{ $tt != null ? Carbon\Carbon::parse($tt->TTDate)->format('d/m/Y') : Carbon\Carbon::now()->format('d/m/Y') }}">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="namacustomer" class="form-label col-md-3">Nama Customer</label>
                            <div class="col-md-9">
                                <select class="form-control select2-tags" id="namacustomer" name="namacustomer">
                                    <option value="">Pilih Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer }}" {{ (isset($tt) && $tt->NamaCustomer == $customer) ? 'selected' : '' }}>
                                            {{ $customer }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamatcustomer" class="form-label col-md-3">Alamat Customer</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="alamatcustomer" name="alamatcustomer">{{ $tt->AlamatCustomer ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telpcustomer" class="form-label col-md-3">Telp Customer</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="telpcustomer" name="telpcustomer"
                                    value="{{ $tt->TelpCustomer ?? '' }}">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="Notes" class="form-label col-md-3">Notes</label>
                            <div class="col-md-9">
                                <textarea rows="3" class="form-control" id="Notes" name="Notes">{{ $invoice->Notes ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ITEM LIST --}}
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <div>Form List</div>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-secondary" id="btnAddDetailMultiple" type="button">
                                <i class="fas fa-plus mr-lg-2"></i><span class="d-none d-lg-inline">Add Multiple</span>
                            </button>
                            <button class="btn btn-avian-primary" type="button" id="btnAddDetail"><i class="fas fa-plus mr-2"></i>Add Item</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="dataTable table table-striped table-hover table-bordered w-100 responsive-table" id="product-list-table">
                        <thead>
                        <tr>
                            <th width="60">No.</th>
                            <th width="150">Inv No.</th>
                            <th width="150">SJ No.</th>
                            <th>Date</th>
                            <th>Jumlah</th>
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
  .responsive-table tr:nth-child(odd) {
    background: #fff3f1 !important;
  }

  .responsive-table tr:nth-child(even) {
    background: #ffffe6 !important;
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

    $(document).on('change', 'input, select, textarea', function (e) {
        embedPreviewData();
        isChanged = true;
    });

    function embedPreviewData() { 
        let formdata = $('#formInput').serialize();
        let uri = '{{ route("tt.previewdynamic", $tt->FormID ?? 0) }}?' + formdata + "#toolbar=0&navpanes=0&scrollbar=0";
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

        // assign no
        let rowIndex = 1;
        $('#product-list-body').find('tr').each(function() {
            $(this).find('td[data-label="No."] input').val(rowIndex++);
        });

        // foreach class numeric, kalau kosong diisi 0
        $('.numeric').each(function() {
            if ($(this).val() === '') {
                $(this).val('0');
            }
        });

        $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yy',
        });
        
        $('.product-select').select2({
            placeholder: 'Pilih...',
            theme: 'bootstrap4',
            tags: true,
            ajax: {
                url: '{{ route("tt.getProducts") }}',
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
            <tr>
                <input type="hidden" name="type[]" value="create" />
                <input type="hidden" name="detailid[]" />
                <input type="hidden" name="issj[]" />
                <td data-label="No.">
                    <input type="text" class="form-control numeric" name="no[]" />
                </td>
                <td data-label="Invoice No.">
                    <input type="text" class="form-control" name="invoiceno[]" />
                </td>
                <td data-label="SJ No.">
                        <input type="text" class="form-control" name="sjno[]" />
                </td>
                <td data-label="Date">
                    <input type="text" class="form-control datepicker" name="date[]" />
                </td>
                <td data-label="Jumlah">
                    <input type="text" class="form-control numeric jumlah-input" name="jumlah[]" value="0" />
                </td>
                <td data-label="Action">
                    <button type="button" class="btn btn-sm btn-danger btn-remove-product">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;

        return row;
    }

    function reorderTabIndex() {
        const columns = ['select2-tags',
            'datepicker',
            'numeric',
            'form-control[name="invoiceno[]"]',
            'form-control[name="sjno[]"]',
            'form-control[name="date[]"]',
            'form-control[name="jumlah[]"]',
            'btn-remove-product',
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

    $(document).on('keyup change', '.jumlah-input', function (e) {
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
            if (type !== 'delete') {
                var total = parseFloat($(this).find('input[name="jumlah[]"]').inputmask('unmaskedvalue')) || 0;
                grandTotal += total;
            }
        });
        return grandTotal;
    }

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

    function getProductDetails(productName, callback) { 
        $.ajax({ 
            url: '{{ route("tt.getProductDetails") }}', 
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

    @if($invoices ?? null != null)
        @foreach($invoices as $invoice)
            addProduct();
            // set values
            var $lastRow = $('#product-list-body tr').last();
            $lastRow.find('input[name="invoiceno[]"]').val('{{ $invoice->InvoiceNo }}');
            $lastRow.find('input[name="sjno[]"]').val('{{ $invoice->SJNo }}');
            $lastRow.find('input[name="date[]"]').val('{{ Carbon\Carbon::parse($invoice->Date)->format('d/m/Y') }}');
            $lastRow.find('input[name="jumlah[]"]').val('{{ number_format($invoice->total, 0, ',', '.') }}');
            $lastRow.find('input[name="type[]"]').val('update');
        @endforeach
        console.log('{{ $invoices[0]->NamaCustomer }}');
        $('#namacustomer').html('<option value="{{ $invoices[0]->NamaCustomer }}" selected>{{ $invoices[0]->NamaCustomer }}</option>');
        $('#alamatcustomer').val(`{{ $invoices[0]->AlamatCustomer ?? '' }}`);
        $('#telpcustomer').val(`{{ $invoices[0]->TelpCustomer ?? '' }}`);
        $('#grand-total').text(getGrandTotal().toLocaleString('id-ID'));
    @elseif($tt != null)
        @foreach($tt->details as $detail)
            addProduct();
            // set values
            var $lastRow = $('#product-list-body tr').last();
            $lastRow.find('input[name="invoiceno[]"]').val('{{ $detail->InvoiceNo }}');
            $lastRow.find('input[name="sjno[]"]').val('{{ $detail->SJNo }}');
            $lastRow.find('input[name="date[]"]').val('{{ Carbon\Carbon::parse($detail->Date)->format('d/m/Y') }}');
            $lastRow.find('input[name="jumlah[]"]').val('{{ number_format($detail->Jumlah, 0, ',', '.') }}');
            $lastRow.find('input[name="detailid[]"]').val('{{ $detail->DetailID }}');
            $lastRow.find('input[name="type[]"]').val('update');
        @endforeach
        $('#grand-total').text(getGrandTotal().toLocaleString('id-ID'));
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

    embedPreviewData();
});


</script>
@endsection
