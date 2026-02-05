@extends('layouts.app')

@section('title', 'MPR | List Price')

@section('content')
<div class="container-fluid">
    <div class="row">
        <form method="POST" action="{{ route('nama.generate') }}">
            @csrf
            <input type="text" name="text" placeholder="Masukkan nama" required>
            <button type="submit">Generate</button>
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
</script>
@endsection
