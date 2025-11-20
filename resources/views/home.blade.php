@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3>Selamat datang, {{ auth()->user()->Nama }}.</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-12">
            <h3>Total Invoice: {{ $totalform ?? 0 }}</h3>
            {{-- <h3>Total Project: {{ $total_project ?? 0 }} <small><small><i class="fas fa-info-circle text-secondary" data-bs-toggle="tooltip" title="Projek milik {{ auth()->user()->pegawai->Nama ?? auth()->user()->Username }}"></i></small></small></h3> --}}
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $hariini ?? 0 }}</h3>

                    <p>Invoice hari ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $mingguini ?? 0 }}</h3>

                    <p>Invoice minggu ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $bulanini ?? 0 }}</h3>

                    <p>Invoice bulan ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-days"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $tahunini ?? 0 }}</h3>

                    <p>Invoice tahun ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
    </div>
</div>
@endsection
