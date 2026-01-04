@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manajemen Menu</h2>
            <p class="text-muted mb-0">Kelola daftar makanan, harga, dan stok ketersediaan.</p>
        </div>
        <div>
            <a href="{{ route('products.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm hover-scale">
                <i class="bi bi-plus-lg me-2"></i>Tambah Menu Baru
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-2">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Cari nama menu atau kategori...">
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="menuTable">
                    <thead class="bg-light bg-opacity-50 text-uppercase text-secondary text-xs fw-bold">
                        <tr>
                            <th class="ps-4 py-3" width="10%">Foto</th>
                            <th class="py-3" width="25%">Nama Menu</th>
                            <th class="py-3" width="15%">Kategori</th>
                            <th class="py-3" width="15%">Harga</th>
                            <th class="py-3" width="10%">Stok</th>
                            <th class="text-end pe-4 py-3" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($products as $product)
                        <tr class="align-middle transition-hover">
                            <td class="ps-4 py-3">
                                <div class="position-relative d-inline-block">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             class="rounded-3 shadow-sm object-fit-cover border" 
                                             width="60" height="60" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="bg-secondary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-secondary border" 
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-image fs-4"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark d-block text-truncate" style="max-width: 200px;">
                                    {{ $product->name }}
                                </span>
                                <small class="text-muted text-truncate d-block" style="max-width: 200px;">
                                    {{ Str::limit($product->description, 30) }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($product->stock > 10)
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill">
                                            {{ $product->stock }}
                                        </span>
                                    </div>
                                @elseif($product->stock > 0)
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">
                                            {{ $product->stock }}
                                        </span>
                                        <small class="text-warning ms-2" style="font-size: 0.7em;">Menipis!</small>
                                    </div>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">Habis</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-warning border-0 bg-warning bg-opacity-10 text-warning hover-warning"
                                       data-bs-toggle="tooltip" title="Edit Menu">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu {{ $product->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger border-0 bg-danger bg-opacity-10 text-danger hover-danger"
                                                data-bs-toggle="tooltip" title="Hapus Menu">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                <p class="text-muted fw-bold">Belum ada menu makanan.</p>
                                <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Tambah Sekarang</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end p-3 bg-light bg-opacity-10 border-top">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('menuTable');
        const trs = table.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            
            // Loop semua baris tabel (mulai index 1 untuk skip header)
            for (let i = 1; i < trs.length; i++) {
                let tdName = trs[i].getElementsByTagName('td')[1]; // Kolom Nama
                let tdCat = trs[i].getElementsByTagName('td')[2];  // Kolom Kategori
                
                if (tdName || tdCat) {
                    let textName = tdName.textContent || tdName.innerText;
                    let textCat = tdCat.textContent || tdCat.innerText;
                    
                    if (textName.toLowerCase().indexOf(filter) > -1 || textCat.toLowerCase().indexOf(filter) > -1) {
                        trs[i].style.display = "";
                    } else {
                        trs[i].style.display = "none";
                    }
                }
            }
        });
        
        // Aktifkan Tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

<style>
    /* Custom Styling */
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.05); }

    .transition-hover:hover {
        background-color: #f8f9fa;
    }
    
    .text-xs { font-size: 0.75rem; letter-spacing: 0.5px; }

    /* Custom Button Colors */
    .hover-warning:hover { background-color: #ffc107 !important; color: #000 !important; }
    .hover-danger:hover { background-color: #dc3545 !important; color: #fff !important; }

    /* Animasi Fade In */
    .animate-fade-in { animation: fadeIn 0.6s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection