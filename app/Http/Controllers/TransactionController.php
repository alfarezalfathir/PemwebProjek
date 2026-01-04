<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 1. DAFTAR RIWAYAT TRANSAKSI
    public function index(Request $request)
    {
        // Mulai query
        $query = Order::with(['user', 'table'])->latest();

        // A. Filter Search (Invoice / Nama User)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_code', 'like', "%$search%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%$search%");
                  });
            });
        }

        // B. Filter Tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

        // C. Filter Status
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Pagination 10 item per halaman
        $orders = $query->paginate(10);

        return view('transactions.index', compact('orders'));
    }

    // 2. DETAIL TRANSAKSI
    public function show($id)
    {
        // Ambil data order beserta detail item & produknya
        $order = Order::with(['items.product', 'user', 'table'])->findOrFail($id);
        
        return view('transactions.show', compact('order'));
    }

    // 3. KONFIRMASI PEMBAYARAN
    public function confirmPayment($id)
    {
        $order = Order::findOrFail($id);
        
        // Ubah status jadi 'completed' (selesai)
        $order->update(['status' => 'completed']);

        // Kosongkan meja lagi (jika ada meja)
        if ($order->table_id && $order->table) {
            $order->table->update(['status' => 'available']);
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi! Pesanan selesai.');
    }

    // 4. DOWNLOAD / CETAK INVOICE
    public function downloadInvoice($id)
    {
        $order = Order::with(['items.product', 'user', 'table'])->findOrFail($id);
        
        // Kita gunakan view detail yang sama, tapi nanti di-print lewat browser
        // Atau bisa buat view khusus 'transactions.print' jika mau tampilan beda
        return view('transactions.show', compact('order'));
    }

    // 5. DELETE (Hapus Riwayat Transaksi)
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // Hapus semua item di dalam order ini dulu (biar bersih)
        $order->items()->delete();
        
        // Kembalikan status meja jadi 'available' jika order dihapus
        if ($order->table_id && $order->table) {
            $order->table->update(['status' => 'available']);
        }

        // Hapus data order utama
        $order->delete();

        return back()->with('success', 'Riwayat transaksi berhasil dihapus!');
    }
}