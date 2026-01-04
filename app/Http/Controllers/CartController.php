<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table; // Import Model Meja
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Import DB untuk Transaksi

class CartController extends Controller
{
    // 1. TAMBAH KE KERANJANG
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // Cek apakah produk sudah ada di keranjang?
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++; // Kalau ada, tambah jumlahnya
        } else {
            // Kalau belum, masukkan data baru
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    // 2. LIHAT KERANJANG (PLUS DATA MEJA)
    public function showCart()
    {
        // Ambil meja yang statusnya 'available' (kosong) agar bisa dipilih user
        $tables = Table::where('status', 'available')->orderBy('table_number')->get();
        
        return view('cart', compact('tables'));
    }

    // 3. HAPUS ITEM DARI KERANJANG
    public function removeFromCart($id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Menu dihapus dari keranjang.');
    }

    // 4. PROSES CHECKOUT (SIMPAN KE DATABASE)
    public function checkout(Request $request)
    {
        $cart = session()->get('cart');

        // Cek jika keranjang kosong
        if(!$cart) {
            return redirect()->route('order.index')->with('error', 'Keranjang Anda kosong!');
        }

        // Validasi input
        $request->validate([
            'table_id' => 'nullable|exists:tables,id', // Boleh kosong (untuk Take Away)
            'notes' => 'nullable|string'
        ]);

        // Mulai Transaksi Database (Biar aman)
        DB::beginTransaction();

        try {
            // A. Hitung Total Harga
            $total_price = 0;
            foreach($cart as $id => $details) {
                $total_price += $details['price'] * $details['quantity'];
            }

            // B. Buat Data Order Utama
            $order = Order::create([
                'user_id' => Auth::id(),
                'table_id' => $request->table_id, // Masukkan ID Meja
                'invoice_code' => 'INV-' . strtoupper(uniqid()), // Kode unik Invoice
                'total_price' => $total_price,
                'status' => 'pending', // Status awal Pending
                'notes' => $request->notes
            ]);

            // C. Masukkan Detail Menu yang Dipesan
            foreach($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    
                    // PERBAIKAN DI SINI (price -> unit_price)
                    'unit_price' => $details['price'], 
                    
                    'subtotal' => $details['price'] * $details['quantity']
                ]);
            }

            // D. Update Status Meja jadi 'Occupied' (Jika pilih Dine In)
            if ($request->table_id) {
                $table = Table::find($request->table_id);
                $table->update(['status' => 'occupied']);
            }

            // Simpan perubahan ke database
            DB::commit();

            // Kosongkan keranjang belanja
            session()->forget('cart');

            return redirect()->route('order.index')->with('success', 'Pesanan berhasil dibuat! Mohon tunggu konfirmasi kasir.');

        } catch (\Exception $e) {
            // Kalau ada error, batalkan semua proses database
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}