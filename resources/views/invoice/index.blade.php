@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="p-0">Invoice</h1>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <div>List Invoice</div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                                <i class="fas fa-filter mr-lg-2"></i><span class="d-none d-lg-inline">Filter</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-reload">
                                <i class="fas fa-sync mr-lg-2"></i><span class="d-none d-lg-inline">Reload</span>
                            </button>
                            <a href="{{ route('invoice.create') }}" class="btn btn-sm btn-avian-primary">
                                <i class="fas fa-plus mr-2"></i>New<span class="d-none d-lg-inline"> Invoice</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-md-0">
                    @if (session()->has('result'))
                    <div class="alert alert-{{ session()->get('result')->type }} m-2" role="alert">
                        {{ session()->get('result')->message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <form id="filter" class="collapse row m-2">
                        {{-- <div class="col-md-6 col-sm-12">
                            <div class="form-group row">
                                <label for="gudang" class="col-sm-3 col-form-label">Gudang</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2" id="gudang" name="gudang">
                                        <option value="0">ALL</option>
                                        @foreach ($gudangs as $gudang)
                                            <option value="{{ $gudang->KodeNAV }}">{{ $gudang->KodeNAV }} ({{ $gudang->Nama }}) </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group row">
                                <label for="status" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <select id="status" name="status" class="form-control select2">
                                        <option value="0">All</option>
                                        <option value="1">Belum ada Berita Acara</option>
                                        <option value="2">Sudah ada Berita Acara</option>
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                        
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group row">
                                <label for="startdate" class="col-sm-3 col-form-label">Tanggal</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input value={{ \Carbon\Carbon::now()->startOf('month')->format('d/m/Y') }} type="text" id="startdate" name="startdate" aria-label="Tanggal Mulai" class="form-control datepicker p-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">s/d</span>
                                        </div>
                                        <input value={{ \Carbon\Carbon::now()->endOf('month')->format('d/m/Y') }} type="text" id="enddate" name="enddate" aria-label="Tanggal Mulai" class="form-control datepicker p-3">
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="show_all" name="show_all">
                                        <label class="form-check-label" for="show_all">
                                            Tampilkan Semua
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group row">
                                <label for="customer" class="col-sm-3 col-form-label">Customer</label>
                                <div class="col-sm-9">
                                    <select id="customer" name="customer" class="form-control select2">
                                        <option value="0">ALL</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer }}">{{ $customer }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-6 col-sm-12">
                            <div class="form-group row">
                                <label for="status" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <select id="status" name="status" class="form-control select2">
                                        <option value="0">All</option>
                                        <option value="1">Belum Dilaksanakan</option>
                                        <option value="2">Pelaksanaan</option>
                                        <option value="3">Ditangguhkan</option>
                                        <option value="4">Selesai</option>
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                    </form>
                    <table class="dataTable table table-striped table-hover table-bordered w-100">
                        <thead>
                            <tr>
                                <th class="text-center align-middle" data-priority="1">Invoice No.</th>
                                <th class="text-center align-middle" data-priority="2">Surat Jalan No.</th>
                                <th class="text-center align-middle">Kode</th>
                                <th class="text-center align-middle">Customer</th>
                                <th class="text-center align-middle">Tanggal</th>
                                <th class="text-center align-middle" data-priority="3">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@section('css')
<style>
    #printModal .modal-xl {
        max-width: 95%;
    }
    div.dataTables_filter, div.dataTables_length {
        padding: 2% 2% 0% 2%;
    }
    div.dataTables_info, div.dataTables_paginate {
        padding: 0% 2% 1% 2%;
    }
    table.dataTable tbody td {
        padding: 4px 16px !important; atas-bawah 12px, kiri-kanan 16px
        vertical-align: middle;
    }

    input[type="checkbox"]:not(#show_all) {
        width: 20px;
        height: 20px;
    }

    @media (max-width: 768px) {
        table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child {
            padding-left: 30px !important; /* geser teks biar gak ketimpa tombol + */
        }
    }
</style>
@endsection

@section('js')
<script>
    var baseurl = "{{route('invoice.datatable')}}";
    let table = $('.dataTable').DataTable({
        processing: true,
        responsive: true,
        pageLength: 100,
        serverSide: true,
        ajax: baseurl + '?' + $('#filter').serialize(),
        columns: [
            {data: 'InvoiceNo', name: 'InvoiceNo', className: 'text-center'},
            {data: 'SJNo', name: 'SJNo', className: 'text-center'},
            {data: 'Kode', name: 'Kode', className: 'text-center'},
            {data: 'NamaCustomer', name: 'NamaCustomer', className: 'text-left'},
            {data: 'InvoiceDate', name: 'InvoiceDate', className: 'text-center'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
        ],
        order: [],
        rowCallback: function (row, data, index) {
            $('td:eq(4)', row).html(moment(data.InvoiceDate).format('DD/MM/YYYY'));
        },

    });

    const reloadData = function () {
        const filters = $('#filter').serialize();
        let url = baseurl + '?' + filters;
        table.ajax.url(url).load(null, false);
    };

    $('.btn-reload').on('click', function (e) {
        e.preventDefault();

        reloadData();
    });

    $('#filter select, #filter input').on('change', function (e) {
        reloadData();
    });

    $('#show_all').on('change', function (e) {
        $('#filter #startdate, #filter #enddate').prop('disabled', $(this).is(':checked'));

        reloadData();
    });

    $('.dataTable').on('click', '.btn-delete', function (e) {
        e.preventDefault();

        const action = $(this).data('url');
        Swal.fire({
            title: 'Apakah anda yakin untuk menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#6c757d',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: 'Mohon tunggu...',
                html: 'Sedang memproses',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        url: action,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Item berhasil dihapus.',
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            reloadData();
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.message,
                                'error'
                            );
                        }
                    });
                }
                });
            }
        });
    });

    $('.dataTable').on('click', '.btn-copy', function (e) {
        e.preventDefault();

        const rowData = table.row($(this).closest('tr')).data();
        const invoiceNo = rowData.InvoiceNo;
        const sjNo = rowData.SJNo;
        const namaCustomer = rowData.NamaCustomer;
        const kodeCustomer = rowData.Kode;
        const link = window.location.origin + '/invoice/previewdynamic/' + rowData.FormID;
        const textToCopy = `Invoice No.: ${invoiceNo}\nSurat Jalan No.: ${sjNo}\nCustomer: ${namaCustomer} (${kodeCustomer})\nLink: ${link}`;
        navigator.clipboard.writeText(textToCopy).then(function() {
            Swal.fire({
                title: 'Copied!',
                text: 'Invoice details have been copied to clipboard.',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false
            });
        }, function(err) {
            Swal.fire(
                'Error!',
                'Could not copy text: ' + err,
                'error'
            );
        });
    });
</script>
@endsection
