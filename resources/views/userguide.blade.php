@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header font-weight-bold text-uppercase">
                    {{ __('Bantuan') }}
                </div>

                <div class="card-body">
                    <object
                        type="application/pdf"
                        data="{{url('/userguide.pdf')}}"
                        style="width: 100%;height: 77.5vh;" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection