@extends('layouts.app')

@section('title', 'MPR | Generate Nama')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">

                    <h4 class="mb-3 fw-bold">Generate Nama</h4>
                    <p class="text-muted small mb-4">
                        Masukkan beberapa nama (pisahkan dengan Enter), lalu pilih tipe style.
                    </p>

                    <form method="POST" action="{{ route('nama.generate') }}">
                        @csrf

                        <!-- Pilih Style -->
                        <div class="mb-3">
                            <label for="styleSelect" class="form-label fw-semibold">
                                Pilih Style
                            </label>
                            <select class="form-select select2" id="styleSelect" name="styleSelect" required>
                                <option value="1">Normal</option>
                                <option value="2">Outline</option>
                                <option value="3">Minecraft</option>
                                <option value="4">Mini</option>
                                <option value="5">Flexible</option>
                            </select>
                        </div>

                        <!-- Input Nama -->
                        <div class="mb-3">
                            <label for="text" class="form-label fw-semibold">
                                Daftar Nama
                            </label>
                            <textarea 
                                id="text"
                                name="text" 
                                class="form-control" 
                                rows="6" 
                                placeholder="Nama 1&#10;Nama 2&#10;Nama 3"
                                required></textarea>
                        </div>

                        <!-- Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-avian-primary btn-lg">
                                Generate
                            </button>
                        </div>
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
