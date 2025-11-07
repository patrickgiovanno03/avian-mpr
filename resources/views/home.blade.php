@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3>Selamat datang, {{ auth()->user()->pegawai->Nama }}.</h3>
        </div>
    </div>
</div>
@endsection
