@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if(session()->has('result'))
        <div class="col-md-8">
            <div class="alert alert-{{ session()->get('result')->type }} alert-dismissible fade show" role="alert">
                {{ session()->get('result')->message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        @endif
        
        @if ($errors->any())
        <div class="col-md-8">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        @endif

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Ubah Password') }}</div>

                <div class="card-body">
                    @if(auth()->user()->IsAD)
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        Akun Anda terhubung dengan sistem Active Directory (AD) terpusat, mohon menghubungi Departemen IT jika Anda membutuhkan untuk mengatur ulang password Anda.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <form method="post" action="{{ route('security') }}" onsubmit="return confirm('Apakah Anda yakin?')">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="old_password" class="col-sm-4 col-form-label">Password Lama</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" id="old_password" name="old" value="{{ old('old') }}" {{auth()->user()->IsAD ? 'disabled' : 'required'}} />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_password" class="col-sm-4 col-form-label">Password Baru</label>
                            <div class="col-md-8">
                                <input type="password" class="form-control" id="new_password" name="new" value="{{ old('new') }}" {{auth()->user()->IsAD ? 'disabled' : 'required'}} />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-avian-secondary">
                                    <i class="fas fa-check"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (auth()->user()->pegawai)
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Pengaturan Notifikasi') }}</div>

                <div class="card-body">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        Prioritas untuk notifikasi yang bersifat <strong>non-private</strong> akan dikirimkan ke email kantor, jika email kantor tidak diisi makan akan dikirimkan ke email pribadi.<br />Email Pribadi dan No. Telp terhubung dengan sistem terpusat dari HRD. Apabila Anda ingin menggantinya, silahkan menghubungi HRD.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{ route('security.notifikasi') }}" onsubmit="return confirm('Apakah Anda yakin?')">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="email_kantor" class="col-sm-4 col-form-label">Email Kerja</label>
                            <div class="col-md-8">
                                <input type="email" class="form-control" id="email_kantor" name="email_kantor" value="{{ old('email_kantor') ?? auth()->user()->pegawai->EmailKantor }}" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email_pribadi" class="col-sm-4 col-form-label">Email Pribadi</label>
                            <div class="col-md-8">
                                <input type="email" class="form-control" id="email_pribadi" name="email_pribadi" value="{{ old('email_pribadi') ?? auth()->user()->pegawai->Email }}" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hp" class="col-sm-4 col-form-label">No. Telp</label>
                            <div class="col-md-8">
                                <input type="tel" class="form-control" id="hp" name="hp" value="{{ old('hp') ?? auth()->user()->pegawai->HP }}" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-avian-secondary">
                                    <i class="fas fa-check"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('.autocomplete').autocomplete({
            source: [
                "ActionScript",
                "AppleScript",
                "Asp",
                "BASIC",
                "C",
                "C++",
                "Clojure",
                "COBOL",
                "ColdFusion",
                "Erlang",
                "Fortran",
                "Groovy",
                "Haskell",
                "Java",
                "JavaScript",
                "Lisp",
                "Perl",
                "PHP",
                "Python",
                "Ruby",
                "Scala",
                "Scheme"
            ],
        });

        $('.table').dataTable({
            responsive: true,
        });
    });
</script>
@endsection
