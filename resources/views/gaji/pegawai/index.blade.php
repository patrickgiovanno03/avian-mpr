@extends('layouts.app')

@section('title', 'MPR | List Pegawai')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="p-0">Gaji</h1>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <div>List Gaji Karyawan</div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                                <i class="fas fa-filter mr-lg-2"></i><span class="d-none d-lg-inline">Filter</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-reload">
                                <i class="fas fa-sync mr-lg-2"></i><span class="d-none d-lg-inline">Reload</span>
                            </button>
                            <button class="btn btn-sm btn-avian-primary btn-add" data-toggle="modal" data-target="#addModal">
                                <i class="fas fa-plus mr-2"></i>Add<span class="d-none d-lg-inline"> Karyawan</span>
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
                                <th class="text-center align-middle" data-priority="1">Nama</th>
                                <th class="text-center align-middle" data-priority="3">Gaji Pokok</th>
                                <th class="text-center align-middle">Gaji Lembur</th>
                                <th class="text-center align-middle">Rekening</th>
                                <th class="text-center align-middle">HP</th>
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
        <form id="formItem" class="modal-content" method="post" action="{{ route('pegawai.store') }}" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="gajipokok" class="col-sm-3 col-form-label">Gaji Pokok</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control numeric" id="gajipokok" name="gajipokok">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="gajilembur" class="col-sm-3 col-form-label">Gaji Lembur</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control numeric" id="gajilembur" name="gajilembur">
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="bankrek" class="col-sm-3 col-form-label">Bank Rekening</label>
                    <div class="col-sm-9">
                        <select class="form-control select2-tags" id="bankrek" name="bankrek">
                            <option value="">-- Pilih Bank --</option>
                            <option value="BCA">BCA</option>
                            <option value="MANDIRI">MANDIRI</option>
                            <option value="BNI">BNI</option>
                            <option value="BRI">BRI</option>
                            <option value="DANA">DANA</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="norek" class="col-sm-3 col-form-label">No. Rekening</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="norek" name="norek">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="anrek" class="col-sm-3 col-form-label">Nama Rekening</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="anrek" name="anrek">
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="hp" class="col-sm-3 col-form-label">Nomor HP</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="hp" name="hp">
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
    var baseurl = "{{route('pegawai.datatable')}}";
    let table = $('.dataTable').DataTable({
        processing: true,
        orderClasses: false,
        responsive: true,
        serverSide: true,
        pageLength: 25,
        ajax: baseurl + '?' + $('#filter').serialize(),
        columns: [
            {data: 'Nama', name: 'Nama', className: 'align-middle'},
            {data: 'GajiPokok', name: 'GajiPokok', className: 'text-center align-middle'},
            {data: 'GajiLembur', name: 'GajiLembur', className: 'text-center align-middle'},
            {data: 'Rekening', name: 'Rekening', className: 'text-center align-middle'},
            {data: 'HP', name: 'HP', className: 'text-center align-middle'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center align-middle'},
        ],
        order: [0, 'asc'],
        rowCallback: function (row, data, index) {
            // console.log(data);
            // $('td:eq(4)', row).html(data.TanggalForm != null ? moment(data.TanggalForm).format('DD MMM YYYY') : '-');
            // $('td:eq(5)', row).html(data.PIC != null ? data.PIC : '-');
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

    $('.btn-add').on('click', function (e) {
        e.preventDefault();

        $('#formItem')[0].reset();
        $('#addModalLabel').text('Add Pegawai');
        $('#formItem').attr('action', "{{ route('pegawai.store') }}");
        $('#formItem').attr('method', 'POST');
        $('#formItem').find('input[name="_method"]').remove();
    });

    $('.dataTable').on('click', '.btn-edit', function (e) {
        e.preventDefault();

        let data = table.row($(this).parents('tr')).data();
        $('#formItem #name').val(data.Nama);
        $('#formItem #gajipokok').val(String(data.GajiPokok ?? '').replace(/\./g, ','));
        $('#formItem #gajilembur').val(String(data.GajiLembur ?? '').replace(/\./g, ','));
        $('#formItem #bankrek').val(data.BankRek).trigger('change');
        $('#formItem #norek').val(data.NoRek);
        $('#formItem #anrek').val(data.ANRek);
        $('#addModalLabel').text('Edit Pegawai');
        $('#addModal').modal('show');
        $('#formItem').attr('action', "{{ route('pegawai.update', '@id') }}".replace('@id', data.PegawaiID));
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
