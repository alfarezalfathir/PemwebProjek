@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
        <div>
            <h2 class="fw-bold text-dark mb-1">Buat Menu Baru</h2>
            <p class="text-muted mb-0">Tambahkan hidangan lezat ke dalam daftar menu restoran.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate-fade-in">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Formulir Produk</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Nama Menu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-bag-heart text-muted"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-start-0 ps-0" placeholder="Contoh: Nasi Goreng Spesial" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Kategori</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-tags text-muted"></i></span>
                                    <select name="category_id" class="form-select bg-light border-start-0 ps-0" required>
                                        <option value="" disabled selected>Pilih Kategori...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Harga Jual</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold text-muted">Rp</span>
                                    <input type="number" name="price" class="form-control bg-light border-start-0 ps-0" placeholder="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control bg-light" rows="3" placeholder="Jelaskan rasa, bahan utama, atau keunikan menu ini..."></textarea>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-5 mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Stok Awal</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-box-seam text-muted"></i></span>
                                    <input type="number" name="stock" class="form-control bg-light border-start-0 ps-0" value="10" required>
                                </div>
                            </div>

                            <div class="col-md-7 mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Foto Menu</label>
                                <input type="file" name="image" class="form-control bg-light" accept="image/*">
                                <div class="form-text text-muted small"><i class="bi bi-info-circle me-1"></i>Format: JPG, PNG, JPEG. Max: 2MB.</div>
                            </div>
                        </div>

                        <hr class="my-4 border-light">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_favorite" id="fav" style="width: 3em; height: 1.5em; cursor: pointer;">
                                <label class="form-check-label ms-2 fw-bold text-dark pt-1" for="fav">Jadikan Menu Favorit?</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm hover-scale">
                                <i class="bi bi-save me-2"></i>Simpan Menu
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Tambahan */
    .form-control:focus, .form-select:focus {
        box-shadow: none;
        border-color: #0d6efd;
        background-color: #fff !important; /* Putih saat diketik */
    }
    
    .input-group-text {
        background-color: #f8f9fa; /* Abu-abu sangat muda */
    }

    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.02); }

    .animate-fade-in { animation: fadeIn 0.6s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection