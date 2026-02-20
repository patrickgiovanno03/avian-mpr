@extends('layouts.app')

@section('title', 'MPR | List Price')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('nama.generate') }}">
                        @csrf
                        <div class="form-group">
                            <label>Masukkan Nama (pisahkan dengan Enter)</label>
                            <textarea name="text" class="form-control" rows="8" placeholder="Nama 1&#10;Nama 2&#10;Nama 3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-avian-primary">Generate</button>
                    </form>
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
</script>
@endsection
