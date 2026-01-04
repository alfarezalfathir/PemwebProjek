<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Tampilkan Form Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // CEK ROLE & ARAHKAN KE HALAMAN YANG SESUAI
            
            // Jika Customer, langsung suruh belanja
            if ($user->hasRole('customer')) {
                return redirect()->route('order.index')->with('success', 'Selamat datang! Silakan pesan makanan.');
            }

            // Jika Admin, Manager, atau Kasir -> Ke Dashboard
            return redirect()->route('dashboard')->with('success', 'Selamat datang di Dashboard!');
        }

        // Jika Gagal Login (Password Salah)
        return back()->with('error', 'Email atau Password salah!');
    }

    // 3. Tampilkan Form Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // 4. Proses Register (Khusus Customer)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Otomatis kasih role Customer
        $user->assignRole('customer');

        // Langsung login setelah daftar
        Auth::login($user);

        return redirect()->route('order.index')->with('success', 'Akun berhasil dibuat!');
    }

    // 5. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}