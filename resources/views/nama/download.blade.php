@extends('layouts.app')

@section('title', 'MPR | Download Files')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Files Ready for Download</h3>
                </div>
                <div class="card-body">
                    <p class="text-success mb-3">{{ count($generatedFiles) }} file(s) berhasil di-generate</p>
                    
                    <div class="mb-3">
                        <button id="downloadAllBtn" class="btn btn-primary">Download Semua File</button>
                        <a href="{{ route('nama.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>

                    <div class="list-group">
                        @foreach($generatedFiles as $file)
                        <a href="{{ $file['url'] }}" class="list-group-item list-group-item-action download-link">
                            <i class="fas fa-download mr-2"></i> {{ $file['name'] }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.getElementById('downloadAllBtn').addEventListener('click', function() {
    const links = document.querySelectorAll('.download-link');
    let delay = 0;
    
    links.forEach((link, index) => {
        setTimeout(() => {
            const a = document.createElement('a');
            a.href = link.href;
            a.download = '';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }, delay);
        delay += 500; // delay 500ms antar download
    });
});
</script>
@endsection
