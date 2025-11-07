@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="p-0">Price</h1>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <div>List Price</div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-reload">
                                <i class="fas fa-sync mr-2"></i>Reload
                            </button>
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
                        </div>
                        
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
                        </div> --}}
                        {{-- <div class="col-lg-6 col-md-12">
                            <div class="form-group row">
                                <label for="department" class="col-sm-3 col-form-label">Departemen</label>
                                <div class="col-sm-9">
                                    <select id="department" name="department" class="form-control select2">
                                        <option value="0">ALL</option>
                                        @foreach($listdepartment as $department)
                                        <option value="{{ $department->Dept }}">{{ $department->Dept }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> --}}
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
                                <th class="text-center align-middle" data-priority="1">Produk</th>
                                <th class="text-center align-middle" data-priority="1000">Harga Konsumen</th>
                                <th class="text-center align-middle">Harga Supplier-1</th>
                                <th class="text-center align-middle">Harga Supplier-2</th>
                                <th class="text-center align-middle">Harga Distributor</th>
                                <th class="text-center align-middle" data-priority="2">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" data-backdrop="static" data-keyboard="false" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="formItem" class="modal-content" method="post" action="{{ route('price.store') }}" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Edit Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" name="name" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pricekonsumen" class="col-sm-3 col-form-label">Harga Konsumen</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control numeric" id="pricekonsumen" name="pricekonsumen">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pricesup1" class="col-sm-3 col-form-label">Harga Supplier-1</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control numeric" id="pricesup1" name="pricesup1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pricesup2" class="col-sm-3 col-form-label">Harga Supplier-2</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control numeric" id="pricesup2" name="pricesup2">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pricedistributor" class="col-sm-3 col-form-label">Harga Distributor</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control numeric" id="pricedistributor" name="pricedistributor">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-avian-secondary">Simpan</button>
            </div>
        </form>
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
    var baseurl = "{{route('price.datatable')}}";
    let table = $('.dataTable').DataTable({
        processing: true,
        responsive: true,
        pageLength: 100,
        serverSide: true,
        ajax: baseurl + '?' + $('#filter').serialize(),
        columns: [
            {data: 'Name', name: 'Name'},
            {data: 'PriceKonsumen', name: 'PriceKonsumen', className: 'text-center'},
            {data: 'PriceSup1', name: 'PriceSup1', className: 'text-center'},
            {data: 'PriceSup2', name: 'PriceSup2', className: 'text-center'},
            {data: 'PriceDistributor', name: 'PriceDistributor', className: 'text-center'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
        ],
        order: [],
        rowCallback: function (row, data, index) {
            $('td:eq(1)', row).html(data.PriceKonsumen != null ? parseFloat(data.PriceKonsumen).toLocaleString() : '0');
            $('td:eq(2)', row).html(data.PriceSup1 != null ? parseFloat(data.PriceSup1).toLocaleString() : '0');
            $('td:eq(3)', row).html(data.PriceSup2 != null ? parseFloat(data.PriceSup2).toLocaleString() : '0');
            $('td:eq(4)', row).html(data.PriceDistributor != null ? parseFloat(data.PriceDistributor).toLocaleString() : '0');
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

    $('.dataTable').on('click', '.btn-edit', function (e) {
        e.preventDefault();

        let data = table.row($(this).parents('tr')).data();
        $('#formItem')[0].reset();
        $('#formItem').find('input[name="_method"]').remove();
        $('#name').val(data.Name);
        $('#pricekonsumen').val(data.PriceKonsumen);
        $('#pricesup1').val(data.PriceSup1);
        $('#pricesup2').val(data.PriceSup2);
        $('#pricedistributor').val(data.PriceDistributor);

        $('#addModalLabel').text('Edit Product');
        $('#addModal').modal('show');
        $('#formItem').attr('action', "{{ route('price.update', '@id') }}".replace('@id', data.ProductID));
        $('#formItem').attr('method', 'POST');
        $('#formItem').find('input[name="_method"]').remove();
        $('#formItem').append('<input type="hidden" name="_method" value="PUT">');
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
</script>
@endsection
