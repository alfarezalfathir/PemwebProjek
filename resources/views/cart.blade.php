@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <div class="mb-4 animate-fade-in">
        <h2 class="fw-bold text-dark mb-1">🛒 Keranjang Saya</h2>
        <p class="text-muted">Periksa kembali pesanan Anda sebelum checkout.</p>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row g-4">
            
            <div class="col-lg-8 animate-up">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-bag-check me-2 text-primary"></i>Daftar Menu Dipilih</h6>
                    </div>
                    <div class="card-body p-0">
                        @php $total = 0; @endphp
                        @foreach(session('cart') as $id => $details)
                            @php 
                                $subtotal = $details['price'] * $details['quantity'];
                                $total += $subtotal;
                            @endphp
                            
                            <div class="p-3 border-bottom position-relative hover-bg-light transition-hover">
                                <div class="row align-items-center">
                                    <div class="col-3 col-md-2">
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center ratio ratio-1x1 overflow-hidden">
                                            @if(isset($details['image']) && $details['image'])
                                                <img src="{{ asset('storage/' . $details['image']) }}" class="object-fit-cover w-100 h-100" alt="{{ $details['name'] }}">
                                            @else
                                                <img src="https://loremflickr.com/100/100/food?lock={{ $id }}" class="object-fit-cover w-100 h-100">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-9 col-md-5">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $details['name'] }}</h6>
                                        <p class="text-muted small mb-0">
                                            Rp {{ number_format($details['price'], 0, ',', '.') }} x {{ $details['quantity'] }}
                                        </p>
                                    </div>

                                    <div class="col-6 col-md-3 mt-2 mt-md-0 text-md-end">
                                        <span class="text-primary fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="col-6 col-md-2 mt-2 mt-md-0 text-end">
                                        <a href="{{ route('cart.remove', $id) }}" class="btn btn-icon btn-outline-danger btn-sm rounded-circle shadow-sm" title="Hapus Item">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="card-footer bg-white p-3 text-center">
                        <a href="{{ route('order.index') }}" class="text-decoration-none fw-bold small text-primary">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Menu Lainnya
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 animate-up delay-1">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card-header bg-primary text-white py-3 rounded-top-4">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i>Checkout</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Pilih Meja (Dine In)</label>
                                <select name="table_id" class="form-select rounded-3 py-2" required>
                                    <option value="" selected disabled>-- Pilih Nomor Meja --</option>
                                    <option value="" class="fw-bold text-primary">🛍️ Take Away (Bungkus)</option>
                                    
                                    @if(isset($tables))
                                        @foreach($tables as $table)
                                            <option value="{{ $table->id }}">
                                                {{ $table->table_number }} - {{ ucfirst($table->location) }} ({{ $table->capacity }} Orang)
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Catatan (Opsional)</label>
                                <textarea name="notes" class="form-control bg-light border-0" rows="2" placeholder="Contoh: Jangan pedas, es dipisah..."></textarea>
                            </div>

                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item border-top border-dashed my-2"></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pt-0">
                                    <span class="fw-bold fs-5 text-dark">Total Bayar</span>
                                    <span class="fw-bold fs-4 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </li>
                            </ul>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill shadow fw-bold hover-scale">
                                Buat Pesanan <i class="bi bi-arrow-right-short ms-1 fs-5 align-middle"></i>
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <small class="text-muted"><i class="bi bi-shield-lock me-1"></i>Transaksi Aman</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @else
        <div class="row justify-content-center animate-up">
            <div class="col-md-6 text-center py-5">
                <div class="mb-4 position-relative">
                    <div class="bg-light rounded-circle d-inline-flex p-5">
                        <i class="bi bi-basket2 fs-1 text-muted opacity-50" style="transform: scale(2);"></i>
                    </div>
                    <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-warning text-dark border border-light">
                        0
                    </span>
                </div>
                <h3 class="fw-bold text-dark">Keranjangmu Masih Kosong</h3>
                <p class="text-muted mb-4">Perut kenyang, hati senang. Yuk isi keranjangmu dengan menu favorit!</p>
                <a href="{{ route('order.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm hover-scale">
                    <i class="bi bi-book-half me-2"></i>Lihat Buku Menu
                </a>
            </div>
        </div>
    @endif
</div>

<style>
    .btn-icon { width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; }
    .hover-bg-light:hover { background-color: #f9f9f9; }
    .border-dashed { border-top-style: dashed !important; }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.02); }
    .animate-fade-in { animation: fadeIn 0.6s ease-in-out; }
    .animate-up { animation: slideUp 0.6s ease-out; }
    .delay-1 { animation-delay: 0.1s; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection