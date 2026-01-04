@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="row mb-4 animate-fade-in">
        <div class="col-lg-8">
            <h1 class="fw-bold display-5 mb-2">Mau Makan Apa Hari Ini? 😋</h1>
            <p class="text-muted fs-5">Temukan hidangan favoritmu dan pesan sekarang.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('cart.show') }}" class="btn btn-warning rounded-pill px-4 py-2 fw-bold shadow-sm hover-scale position-relative">
                <i class="bi bi-cart-fill me-2"></i> Lihat Keranjang
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <div class="row justify-content-center mb-4 animate-up">
        <div class="col-md-8">
            <form action="{{ route('order.index') }}" method="GET">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                
                <div class="input-group shadow-lg rounded-pill overflow-hidden bg-body search-container">
                    <span class="input-group-text bg-transparent border-0 ps-4"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-0 py-3 ps-2 bg-transparent" placeholder="Cari Nasi Goreng, Minuman, dll..." value="{{ request('search') }}">
                    <button class="btn btn-primary px-5 fw-bold" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-5 animate-up delay-1">
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-center flex-wrap category-scroll">
                <a href="{{ route('order.index') }}" 
                   class="btn rounded-pill px-4 fw-bold shadow-sm {{ !request('category') || request('category') == 'all' ? 'btn-primary' : 'btn-light text-dark' }}">
                    Semua
                </a>

                @foreach($categories as $cat)
                    <a href="{{ route('order.index', ['category' => $cat->name]) }}" 
                       class="btn rounded-pill px-4 fw-bold shadow-sm {{ request('category') == $cat->name ? 'btn-primary' : 'btn-light text-dark' }}">
                       {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-6 col-md-4 col-lg-3 animate-up delay-2">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card transition-hover">
                
                <div class="position-relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}" style="height: 200px;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
                            <div class="text-center">
                                <i class="bi bi-image fs-1 mb-2"></i>
                                <p class="small mb-0">No Image</p>
                            </div>
                        </div>
                    @endif
                    
                    <div class="position-absolute top-0 start-0 w-100 p-3 d-flex justify-content-between">
                        <span class="badge bg-dark bg-opacity-75 backdrop-blur rounded-pill">
                            {{ $product->category->name ?? 'Umum' }}
                        </span>
                        @if($product->stock < 5)
                            <span class="badge bg-danger text-white shadow-sm rounded-pill animate-pulse">
                                Sisa: {{ $product->stock }}
                            </span>
                        @else
                            <span class="badge bg-white text-dark shadow-sm rounded-pill">
                                Stok: {{ $product->stock }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body d-flex flex-column p-4">
                    <h5 class="card-title fw-bold mb-1 text-truncate">{{ $product->name }}</h5>
                    <p class="card-text text-muted small mb-3 flex-grow-1 line-clamp-2">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk menu ini.' }}
                    </p>
                    
                    <div class="d-flex align-items-end justify-content-between mt-auto">
                        <div>
                            <small class="text-muted d-block mb-1">Harga</small>
                            <h5 class="fw-bold text-primary mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                        </div>
                        <a href="{{ route('order.add', $product->id) }}" class="btn btn-primary rounded-circle shadow-sm btn-add-cart">
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="py-5 opacity-50">
                <i class="bi bi-search fs-1 d-block mb-3"></i>
                <h3>Yah, menu tidak ditemukan.</h3>
                <p>Coba pilih kategori lain atau cari kata kunci berbeda.</p>
                <a href="{{ route('order.index') }}" class="btn btn-outline-primary rounded-pill px-4 mt-2">Lihat Semua Menu</a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    /* Styling Dasar */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.05); }

    .transition-hover { transition: transform 0.2s, box-shadow 0.2s; }
    .transition-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }

    .backdrop-blur { backdrop-filter: blur(5px); }
    
    .btn-add-cart {
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
    }
    .btn-add-cart:hover { transform: rotate(90deg); }

    .animate-up { animation: fadeInUp 0.6s ease-out forwards; }
    .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }

    .animate-pulse { animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }

    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* Category Scroll (Opsional kalau kategori banyak, bisa digeser horizontal di HP) */
    .category-scroll {
        overflow-x: auto;
        white-space: nowrap;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        padding-bottom: 5px;
    }
    .category-scroll::-webkit-scrollbar {
        display: none;
    }

    /* --- PERBAIKAN DARK MODE --- */
    
    [data-bs-theme="dark"] .product-card {
        background-color: #2b3035 !important;
        border: 1px solid #373b3e;
        color: #f8f9fa;
    }

    [data-bs-theme="dark"] .bg-light {
        background-color: #212529 !important;
        color: #adb5bd !important;
    }

    /* Perbaikan Tombol Kategori di Dark Mode */
    [data-bs-theme="dark"] .btn-light {
        background-color: #2b3035;
        border: 1px solid #495057;
        color: #e9ecef !important;
    }
    [data-bs-theme="dark"] .btn-light:hover {
        background-color: #343a40;
    }

    [data-bs-theme="dark"] .text-muted {
        color: #adb5bd !important;
    }

    [data-bs-theme="dark"] .search-container {
        background-color: #2b3035 !important;
        border: 1px solid #373b3e;
    }
    [data-bs-theme="dark"] .form-control {
        color: #fff;
    }
    [data-bs-theme="dark"] .form-control::placeholder {
        color: #adb5bd;
    }
</style>
@endsection