<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function indexEmail()
    {
        return view('Auth.loginEmail');
    }
    public function indexUsername()
    {
        return view('Auth.loginUsername');
    }

    function loginEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email', // Mengganti 'username' dengan 'email' dan menambahkan validasi email
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi', // Mengganti pesan validasi
            'email.email' => 'Format email tidak valid', // Menambahkan pesan validasi untuk format email
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'email' => $request->email, // Menggunakan 'email' dari form input
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            $user = Auth::user();
            // dd($infologin);
            // dd($user);
            if ($user->role === 'kadiv') {
                return redirect('dashboardkadiv');
            } elseif ($user->role === 'superadmin') {
                return redirect('dashboardSuperadmin');
            } elseif ($user->role === 'pegawai') {
                return redirect('dashboardpegawai');
            } else {
                return redirect()->route('login')->withErrors('Role pengguna tidak valid')->withInput();
            }
        } else {
            return redirect()->route('login')->withErrors('Email dan password tidak sesuai')->withInput();
        }
    }

    function loginUsername(Request $request)
    {
        $request->validate([
            'username' => 'required', // Mengganti 'email' dengan 'username'
            'password' => 'required'
        ], [
            'username.required' => 'Username wajib diisi', // Mengganti pesan validasi
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'username' => $request->username, // Menggunakan 'username' dari form input
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            $user = Auth::user();
            // dd($infologin);
            // dd($user);
            if ($user->role === 'kadiv') {
                return redirect('dashboardkadiv');
            } elseif ($user->role === 'superadmin') {
                return redirect('dashboardSuperadmin');
            } elseif ($user->role === 'pegawai') {
                return redirect('dashboardpegawai');
            } else {
                return redirect()->route('login')->withErrors('Role pengguna tidak valid')->withInput();
            }
        } else {
            return redirect()->route('login')->withErrors('Username dan password tidak sesuai')->withInput();
        }
    }



    function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
