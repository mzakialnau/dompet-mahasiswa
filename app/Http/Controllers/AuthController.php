<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:4|max:30|unique:users,username|alpha_dash',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.unique' => 'Username sudah dipakai, pilih yang lain.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, atau underscore.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => $validated['password'],
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $user->username);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // === FITUR PROFILE ===
    public function profile()
    {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'required|string|min:4|max:30|alpha_dash|unique:users,username,' . $user->id,
        ], [
            'username.unique' => 'Username sudah digunakan akun lain.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, atau underscore.',
        ]);

        $user->update(['username' => $validated['username']]);

        return back()->with('success', 'Username berhasil diperbarui!');
    }

    public function destroyAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password konfirmasi salah! Akun gagal dihapus.']);
        }

        Auth::logout();
        $user->delete(); // Otomatis menghapus semua transaksi milik user ini (cascade)

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Akun dan seluruh data transaksimu telah dihapus permanen.');
    }
}