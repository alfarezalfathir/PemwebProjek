<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. READ (Lihat Semua User)
    public function index()
    {
        // Ambil semua user, urutkan dari yang terbaru
        // Kita gunakan 'with' roles supaya query lebih cepat (Eager Loading)
        $users = User::with('roles')->latest()->get();
        return view('users.index', compact('users'));
    }

    // 2. CREATE (Simpan User Baru)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:superadmin,manager,cashier,customer'
        ]);

        // Buat User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
        ]);

        // Tetapkan Role (Spatie)
        $user->assignRole($request->role);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    // 3. UPDATE (Edit User)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id, // Abaikan email milik sendiri saat cek unik
            'role' => 'required|in:superadmin,manager,cashier,customer'
        ]);

        // Update data dasar
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Kalau password diisi, update passwordnya. Kalau kosong, biarkan password lama.
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Update Role (Sync = Hapus role lama, pasang role baru)
        $user->syncRoles($request->role);

        return back()->with('success', 'Data user diperbarui!');
    }

    // 4. DELETE (Hapus User)
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Mencegah Admin menghapus dirinya sendiri
        if ($user->id == Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}