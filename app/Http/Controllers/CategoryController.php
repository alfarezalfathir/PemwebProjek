<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <--- WAJIB: Jangan lupa import ini!

class CategoryController extends Controller
{
    // 1. TAMPILKAN DAFTAR KATEGORI
    public function index()
    {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    // 2. SIMPAN KATEGORI BARU
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            // --- BAGIAN INI YANG MEMPERBAIKI ERROR ---
            'slug' => Str::slug($request->name), 
            // ----------------------------------------
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    // 3. UPDATE KATEGORI
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
            // Update slug juga jika nama berubah
            'slug' => Str::slug($request->name), 
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    // 4. HAPUS KATEGORI
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Opsional: Cek apakah kategori dipakai produk sebelum hapus
        if($category->products()->count() > 0) {
            return back()->with('error', 'Gagal! Kategori ini masih memiliki produk.');
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}