<?php

namespace App\Http\Controllers;

use App\Models\MUser;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function userguide()
    {
        return view('userguide');
    }

    public function security()
    {
        if (request()->isMethod('GET')) {
            return view('security');
        }
        else if (request()->isMethod('POST')) {
            request()->validate([
                'old' => 'required|min:3|string',
                'new' => 'required|min:3|string',
            ], [
                'old.required' => 'Password lama harus diisi.',
                'old.min' => 'Password lama minimal :min karakter',
                'new.required' => 'Password baru harus diisi.',
                'new.min' => 'Password baru minimal :min karakter',
            ]);

            if (auth()->user()->Passwd != request()->old) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('result', (object)[
                        'type' => 'danger',
                        'message' => 'Password lama Anda tidak sesuai dengan yang tercatat sistem, silahkan coba lagi.',
                    ]);
            }
            
            $muser = MUser::findOrFail(auth()->user()->UserID);
            $muser->Passwd = request()->new;
            $muser->save();

            return redirect()
                ->back()
                ->with('result', (object)[
                    'type' => 'success',
                    'message' => 'Password Anda berhasil diubah.',
                ]);
        }
        else {
            return response('Halaman tidak ditemukan', 404);
        }
    }

    public function change_email()
    {
        request()->validate([
            'email_kantor' => 'required|email',
        ], [
            'email_kantor.required' => 'Email Kantor harus diisi',
            'email_kantor.email' => 'Email Kantor tidak valid',
        ]);

        if (auth()->user()->pegawai) {
            auth()->user()->pegawai->EmailKantor = request('email_kantor');
            auth()->user()->pegawai->save();
            
            return redirect()->back()
                ->with('result', (object)[
                    'type' => 'success',
                    'message' => 'Pengaturan notifikasi berhasil disimpan.',
                ]);
        }

        return redirect()->back()
                ->with('result', (object)[
                    'type' => 'error',
                    'message' => 'Pengaturan notifikasi gagal disimpan.',
                ]);
    }
}
