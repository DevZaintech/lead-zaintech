<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'NAMA' => 'required|string|max:255',
            'PASSWORD' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->NAMA = $request->NAMA;

        if ($request->PASSWORD) {
            $user->PASSWORD = $request->PASSWORD; // ⚠️ Plain text, tidak di-hash
        }
        $user->UPDATED_AT = now();

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
