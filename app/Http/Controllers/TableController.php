<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    // 1. TAMPILKAN SEMUA MEJA
    public function index()
    {
        // Ambil data meja, urutkan dari nomor meja terkecil (M-01, M-02, dst)
        $tables = Table::orderBy('table_number', 'asc')->get();
        return view('tables.index', compact('tables'));
    }

    // 2. SIMPAN MEJA BARU
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'table_number' => 'required|unique:tables,table_number', // Gak boleh ada nomor kembar
            'capacity' => 'required|integer|min:1', // Minimal muat 1 orang
            'location' => 'required|in:indoor,outdoor' // Cuma boleh pilih Indoor/Outdoor
        ]);

        // Masukkan ke database
        Table::create([
            'table_number' => $request->table_number,
            'capacity' => $request->capacity,
            'location' => $request->location,
            'status' => 'available' // Default statusnya kosong
        ]);

        return back()->with('success', 'Meja berhasil ditambahkan!');
    }

    // 3. UPDATE MEJA
    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);

        $request->validate([
            // Validasi unik, TAPI kecualikan diri sendiri (biar gak error kalau cuma edit kapasitas)
            'table_number' => 'required|unique:tables,table_number,'.$id, 
            'capacity' => 'required|integer|min:1',
            'location' => 'required|in:indoor,outdoor'
        ]);

        $table->update($request->all());

        return back()->with('success', 'Data meja berhasil diperbarui!');
    }

    // 4. HAPUS MEJA
    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        
        return back()->with('success', 'Meja berhasil dihapus!');
    }
}