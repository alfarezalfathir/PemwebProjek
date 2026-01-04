<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Tambahkan Auth untuk cek role

class DashboardController extends Controller
{
    public function index()
    {
        // Cek jika user adalah Customer, lempar ke halaman order/menu
        if (Auth::user()->hasRole('customer')) {
            return redirect()->route('order.index');
        }

        // --- LOGIKA DASHBOARD ADMIN ---

        // 1. RINGKASAN ATAS (CARD)
        // Hitung total pendapatan dari order yang sudah 'completed'
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        
        // Hitung total transaksi
        $totalOrders = Order::count();
        
        // Hitung total produk aktif
        $totalProducts = Product::where('stock', '>', 0)->count();

        // Hitung total user (Customer)
        $totalCustomers = User::role('customer')->count();

        // 2. DATA UNTUK GRAFIK (Pendapatan per Bulan di Tahun Ini)
        // Kita ambil data penjualan tahun ini, dikelompokkan per bulan
        $salesData = Order::select(
                DB::raw('SUM(total_price) as total'),
                DB::raw('MONTH(created_at) as month')
            )
            ->where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Siapkan array kosong untuk 12 bulan
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $totals = array_fill(0, 12, 0); // [0, 0, 0, ... sampai 12x]

        // Isi array totals sesuai data dari database
        foreach ($salesData as $data) {
            // $data->month indexnya mulai dari 1, array mulai dari 0. Jadi dikurangi 1.
            $totals[$data->month - 1] = $data->total;
        }

        return view('dashboard', compact('totalRevenue', 'totalOrders', 'totalProducts', 'totalCustomers', 'months', 'totals'));
    }
}