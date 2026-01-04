@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
        <div>
            <h2 class="fw-bold text-dark mb-1">Edit Menu</h2>
            <p class="text-muted mb-0">Perbarui informasi menu, harga, atau foto produk.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate-fade-in">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                            <i class="bi bi-pencil-fill"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-dark">Formulir Perubahan</h5>
                    </div>
                </div>
                
                <div class="card-body p-4 pt-0">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Nama Menu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-bag-heart text-muted"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-start-0 ps-0" value="{{ old('name', $product->name) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Kategori</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-tags text-muted"></i></span>
                                    <select name="category_id" class="form-select bg-light border-start-0 ps-0" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Harga Jual</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold text-muted">Rp</span>
                                    <input type="number" name="price" class="form-control bg-light border-start-0 ps-0" value="{{ old('price', $product->price) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control bg-light" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Stok Saat Ini</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-box-seam text-muted"></i></span>
                                    <input type="number" name="stock" class="form-control bg-light border-start-0 ps-0" value="{{ old('stock', $product->stock) }}" required>
                                </div>
                            </div>

                            <div class="col-md-7 mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Foto Menu</label>
                                
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="rounded-3 border shadow-sm object-fit-cover" width="80" height="80">
                                            <span class="badge bg-secondary position-absolute top-0 start-0 translate-middle p-1 rounded-circle border border-light">
                                                <i class="bi bi-image"></i>
                                            </span>
                                        @else
                                            <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 80px;">
                                                <i class="bi bi-image-alt fs-4"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-grow-1">
                                        <input type="file" name="image" class="form-control bg-light" accept="image/*">
                                        <div class="form-text text-muted small ms-1">
                                            <i class="bi bi-info-circle me-1"></i>Upload foto baru untuk mengganti yang lama.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_favorite" id="fav" style="width: 3em; height: 1.5em; cursor: pointer;" {{ $product->is_favorite ? 'checked' : '' }}>
                                <label class="form-check-label ms-2 fw-bold text-dark pt-1" for="fav">Menu Favorit</label>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg rounded-pill px-5 shadow-sm text-dark hover-scale fw-bold">
                                <i class="bi bi-check-circle-fill me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Konsisten */
    .form-control:focus, .form-select:focus {
        box-shadow: none;
        border-color: #ffc107; /* Warna Kuning saat fokus (mode edit) */
        background-color: #fff !important;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
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