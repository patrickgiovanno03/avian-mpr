@extends('layouts.app')

@section('title', 'MPR | List Price Single')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="p-0">Price Single</h1>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <div>List Price Single</div>
                        <div class="btn-group">
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

                    <form id="filter" class="row m-2">
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
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group row">
                            <label for="customercategory" class="col-sm-3 col-form-label">Customer Category</label>
                            <div class="col-sm-9">
                                <select id="customercategory" name="customercategory" class="form-control select2">
                                    <option value="1">Konsumen</option>
                                    <option value="2">Supplier-1</option>
                                    <option value="3">Supplier-2</option>
                                    <option value="4">Distributor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </form>
                    <div class="table-responsive">
                        <table class="dataTable table table-striped table-hover table-bordered w-100">
                            <thead>
                                <tr>
                                    <th rowspan="3" style="min-width: 50px;" class="text-center align-middle">Category</th>
                                    <th colspan="6" style="min-width: 50px;" class="text-center align-middle">Eceran</th>
                                    <th colspan="6" style="min-width: 50px;" class="text-center align-middle">Grosir</th>
                                    <th rowspan="3" style="min-width: 50px;" class="text-center align-middle">Kiloan</th>
                                </tr>
                                <tr>
                                    <th colspan="3" style="min-width: 50px;" class="text-center align-middle">Bulat</th>
                                    <th colspan="3" style="min-width: 50px;" class="text-center align-middle">Tabung</th>
                                    <th colspan="3" style="min-width: 50px;" class="text-center align-middle">Bulat</th>
                                    <th colspan="3" style="min-width: 50px;" class="text-center align-middle">Tabung</th>
                                </tr>
                                <tr>
                                    <th style="min-width: 100px;" class="text-center align-middle">250gr</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">300gr</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">500gr</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">300ml</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">400ml</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">700ml</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">250gr</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">300gr</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">500gr</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">300ml</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">400ml</th>
                                    <th style="min-width: 100px;" class="text-center align-middle">700ml</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
</style>
@endsection

@section('js')
<script>
    var baseurl = "{{route('pricesingle.datatable')}}";
    let table = $('.dataTable').DataTable({
        processing: true,
        // responsive: true,
        serverSide: true,
        ajax: baseurl + '?' + $('#filter').serialize(),
        columns: [
            {data: 'Category', name: 'Category'},
            {data: '250grInput', name: '250grInput'},
            {data: '300grInput', name: '300grInput'},
            {data: '500grInput', name: '500grInput'},
            {data: '300mlInput', name: '300mlInput'},
            {data: '400mlInput', name: '400mlInput'},
            {data: '700mlInput', name: '700mlInput'},
            {data: '250grGInput', name: '250grGInput'},
            {data: '300grGInput', name: '300grGInput'},
            {data: '500grGInput', name: '500grGInput'},
            {data: '300mlGInput', name: '300mlGInput'},
            {data: '400mlGInput', name: '400mlGInput'},
            {data: '700mlGInput', name: '700mlGInput'},
            {data: 'KiloanInput', name: 'KiloanInput'},
        ],
        order: [],
        rowCallback: function (row, data, index) {
        },

    });

    table.on('draw.dt', function () {
        $('.numeric').inputmask({
            alias: 'decimal',
            groupSeparator: '.',
            radixPoint: ',',
            autoGroup: true,
        });
    });

    $('.dataTable').on('change', '.price-input', function (e) {
        e.preventDefault();
        var btn = $(this);
        const priceId = $(this).data('priceid');
        const weight = $(this).data('weight');
        const priceValue = $(this).inputmask('unmaskedvalue');

        $.ajax({
            url: "{{ route('pricesingle.update', '@id') }}".replace('@id', priceId),
            type: 'POST',
            data: {
                _method: 'PUT',
                _token: '{{ csrf_token() }}',
                weight: weight,
                price: priceValue,
                customercategory: $('#customercategory').val()
            },
            beforeSend: function () {
                btn.prop('disabled', true);
            },
            success: function (response) {
                console.log(response);
                btn.prop('disabled', false);
            },
            error: function (xhr) {
                Swal.fire(
                    'Error!',
                    xhr.responseJSON.message,
                    'error'
                );
            }
        });
    });

    const reloadData = function () {
        const filters = $('#filter').serialize();
        let url = baseurl + '?' + filters;
        table.ajax.url(url).load(null, false);
    };

    $('.select2').on('change', function (e) {
        reloadData();
    });

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

    $('#formItem').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let method = form.attr('method');

        $.ajax({
            url: url,
            type: method,
            data: form.serialize(),
            success: function (res) {
                // Swal.fire({
                //     icon: 'success',
                //     title: 'Success',
                //     text: res.message,
                //     timer: 1200,
                //     showConfirmButton: false
                // });

                $('#addModal').modal('hide');
                form[0].reset();
                reloadData();
            },
            error: function (xhr) {
                Swal.fire(
                    'Error!',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            }
        });
    });

    $('.dataTable').on('click', '.btn-edit', function (e) {
        e.preventDefault();

        const data = $(this).data('data');
        $('#formItem #name').val(data.Name);
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

    // buat baguskan tablenya
    $(document).ready(function() {
        function toggleResponsiveClass() {
            if ($(window).width() < 768) {
                $('.dataTable').addClass('table-responsive');
            } else {
                $('.dataTable').removeClass('table-responsive');
            }
        }

        // Jalankan saat halaman pertama kali dimuat
        toggleResponsiveClass();

        // Jalankan juga saat ukuran layar berubah (resize)
        $(window).resize(function() {
            toggleResponsiveClass();
        });
    });
</script>
@endsection
