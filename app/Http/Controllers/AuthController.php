<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (Auth::check()) {
            switch (Auth::user()->ROLE) {
                case 'admin':
                    return redirect()->route('dashboard.admin');
                case 'gate':
                    return redirect()->route('dashboard.gate');
                case 'sales':
                    return redirect()->route('dashboard.sales');
                default:
                    return redirect()->route('home');
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'EMAIL' => 'required|email',
            'PASSWORD' => 'required',
        ]);

        $user = User::where('EMAIL', $request->EMAIL)->first();

        // cek apakah user ada
        if ($user) {
            // kalau user nonaktif
            if ($user->DELETED_AT !== null) {
                return back()->withErrors(['EMAIL' => 'Akun Anda sudah dinonaktifkan']);
            }

            // sementara: plain text (nanti ganti pakai Hash::check)
            if ($request->PASSWORD == $user->PASSWORD) {
                Auth::login($user);

                switch ($user->ROLE) {
                    case 'admin':
                        return redirect()->route('dashboard.admin');
                    case 'gate':
                        return redirect()->route('dashboard.gate');
                    case 'sales':
                        return redirect()->route('dashboard.sales');
                    default:
                        return redirect()->route('home');
                }
            }
        }

        return back()->withErrors(['EMAIL' => 'Email atau password salah']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
