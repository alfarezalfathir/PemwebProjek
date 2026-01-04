<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil 4 produk acak (inRandomOrder) untuk ditampilkan di gallery depan
        // Pastikan tabel products sudah ada isinya (dari seeder)
        $featured_menus = Product::with('category')->inRandomOrder()->limit(4)->get();
        
        return view('welcome', compact('featured_menus'));
    }
}