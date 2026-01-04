<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category; // Pastikan Model Category di-import
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. HALAMAN DAFTAR MENU (KATALOG)
    public function index(Request $request)
    {
        // A. Ambil Kategori untuk Filter
        $categories = Category::where('is_active', true)->get();

        // B. Query Produk (Hanya yang stoknya ada)
        $query = Product::with('category')->where('stock', '>', 0);

        // C. Logika Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // D. Logika Filter Kategori
        if ($request->has('category') && $request->category != 'all') {
            $categoryName = $request->category;
            $query->whereHas('category', function($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        // E. Ambil Data (Pagination 8 item per halaman)
        $products = $query->paginate(8);

        // --- PERBAIKAN DI SINI (Folder 'orders') ---
        return view('orders.index', compact('products', 'categories'));
    }

    // 2. TAMBAH KE KERANJANG
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Menu berhasil masuk keranjang!');
    }

    // 3. LIHAT KERANJANG
    public function showCart()
    {
        // --- PERBAIKAN DI SINI (Folder 'orders') ---
        return view('orders.cart');
    }

    // 4. HAPUS ITEM DARI KERANJANG
    public function removeFromCart($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Menu dihapus dari keranjang.');
    }

    // 5. PROSES CHECKOUT (DATABASE TRANSACTION)
    public function checkout()
    {
        $cart = session()->get('cart');

        if(!$cart) {
            return redirect()->back()->with('error', 'Keranjang kamu kosong!');
        }

        try {
            DB::transaction(function () use ($cart) {
                // A. Buat Order Utama
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'invoice_code' => 'INV-' . time(),
                    'total_price' => 0, 
                    'status' => 'pending',
                ]);

                $totalTagihan = 0;

                // B. Masukkan Detail Item
                foreach($cart as $id => $details) {
                    $subtotal = $details['price'] * $details['quantity'];
                    $totalTagihan += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'quantity' => $details['quantity'],
                        'unit_price' => $details['price'],
                        'subtotal' => $subtotal
                    ]);

                    // C. Kurangi Stok
                    $product = Product::find($id);
                    // Cek stok validasi
                    if($product->stock < $details['quantity']) {
                        throw new \Exception("Stok " . $product->name . " habis/kurang.");
                    }
                    $product->decrement('stock', $details['quantity']);
                }

                // D. Update Total Harga
                $order->update(['total_price' => $totalTagihan]);
                
                // (Opsional) Jika ada Tabel Payment, tambahkan di sini
                // Payment::create([...]); 
            });

            // Hapus session keranjang
            session()->forget('cart');

            return redirect()->route('dashboard')->with('success', 'Pesanan Berhasil! Silakan tunggu pesanan Anda.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}