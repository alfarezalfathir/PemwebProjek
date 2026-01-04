@extends('layouts.app')

@section('content')
<div class="p-5 mb-4 bg-dark text-white rounded-3 position-relative overflow-hidden" 
     style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; background-position: center;">
    
    <div class="container-fluid py-5 position-relative text-center">
        <h1 class="display-3 fw-bold">Selamat Datang di RestoEnak</h1>
        <p class="col-md-8 fs-4 mx-auto mb-4">Rasakan kenikmatan masakan bintang lima dengan harga mahasiswa. Bahan segar, rasa autentik, dan pelayanan terbaik.</p>
        
        <div class="d-flex justify-content-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4 fw-bold">
                    <i class="bi bi-speedometer2 me-2"></i>Ke Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 fw-bold">
                    <i class="bi bi-cart-fill me-2"></i>Pesan Sekarang
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 fw-bold">
                    Daftar Member
                </a>
            @endauth
        </div>
    </div>
</div>

<div class="container py-5" id="about">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="https://images.unsplash.com/photo-1556910103-1c02745a30bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" 
                 class="img-fluid rounded-3 shadow-lg" alt="About Us">
        </div>
        <div class="col-md-6 ps-md-5">
            <h6 class="text-primary fw-bold text-uppercase">Tentang Kami</h6>
            <h2 class="fw-bold mb-4">Lebih dari Sekadar Makanan</h2>
            <p class="text-muted">Didirikan pada tahun 2026, RestoEnak berkomitmen untuk menghadirkan pengalaman kuliner yang tak terlupakan. Kami bekerja sama dengan petani lokal untuk memastikan setiap bahan yang kami gunakan adalah yang terbaik.</p>
            
            <ul class="list-unstyled mt-4">
                <li class="mb-3 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i> 
                    <span>100% Bahan Halal & Segar</span>
                </li>
                <li class="mb-3 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i> 
                    <span>Koki Berpengalaman 10 Tahun</span>
                </li>
                <li class="mb-3 d-flex align-items-center">
                    <i class="bi bi-wifi text-success fs-4 me-3"></i> 
                    <span>Tempat Nyaman & Free WiFi</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="text-center mb-5">
        <h6 class="text-primary fw-bold text-uppercase">Pilihan Terbaik</h6>
        <h2 class="fw-bold">Menu Favorit Pelanggan</h2>
    </div>

    <div class="row g-4">
        @forelse($featured_menus as $menu)
        <div class="col-md-3 col-sm-6">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="bg-light d-flex align-items-center justify-content-center text-secondary" style="height: 200px;">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top h-100 object-fit-cover" alt="{{ $menu->name }}">
                    @else
                        <div class="text-center">
                            <i class="bi bi-cup-hot fs-1"></i>
                            <p class="small m-0">No Image</p>
                        </div>
                    @endif
                </div>
                
                <div class="card-body">
                    <span class="badge bg-warning text-dark mb-2">{{ $menu->category->name ?? 'Umum' }}</span>
                    <h5 class="card-title fw-bold text-truncate">{{ $menu->name }}</h5>
                    <p class="card-text text-muted small text-truncate">{{ $menu->description }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-3">
                        <span class="text-primary fw-bold fs-5">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        <button class="btn btn-sm btn-outline-primary rounded-circle">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">Belum ada data menu. Jalankan Seeder dulu!</p>
        </div>
        @endforelse
    </div>
    
    <div class="text-center mt-5">
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-outline-dark px-4 rounded-pill">Lihat Menu Lengkap <i class="bi bi-arrow-right ms-2"></i></a>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-dark px-4 rounded-pill">Login untuk Pesan <i class="bi bi-arrow-right ms-2"></i></a>
        @endauth
    </div>
</div>

<style>
    .hover-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>
@endsection