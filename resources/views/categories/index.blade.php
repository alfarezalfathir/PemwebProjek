@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manajemen Kategori</h2>
            <p class="text-muted mb-0">Atur dan kelola kategori produk restoran Anda.</p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm hover-scale" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate-up">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold letter-spacing-1">Kategori</th>
                            <th class="text-uppercase small fw-bold letter-spacing-1">Deskripsi</th>
                            <th class="text-uppercase small fw-bold letter-spacing-1">Status</th>
                            <th class="text-end pe-4 text-uppercase small fw-bold letter-spacing-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-initial rounded-circle bg-primary bg-opacity-10 text-primary fw-bold me-3 d-flex align-items-center justify-content-center">
                                        {{ strtoupper(substr($cat->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $cat->name }}</h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">ID: #{{ $cat->id }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <span class="text-muted small text-truncate d-inline-block" style="max-width: 250px;">
                                    {{ $cat->description ?? 'Tidak ada deskripsi' }}
                                </span>
                            </td>

                            <td>
                                @if($cat->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">
                                        <i class="bi bi-x-circle-fill me-1"></i> Non-Aktif
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-light text-primary rounded-circle border shadow-sm btn-action" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal{{ $cat->id }}"
                                            title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    
                                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori {{ $cat->name }}?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger rounded-circle border shadow-sm btn-action" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('categories.update', $cat->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body pt-4">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="name" class="form-control rounded-3" id="editName{{$cat->id}}" value="{{ $cat->name }}" required>
                                                <label for="editName{{$cat->id}}">Nama Kategori</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <textarea name="description" class="form-control rounded-3" id="editDesc{{$cat->id}}" style="height: 100px">{{ $cat->description }}</textarea>
                                                <label for="editDesc{{$cat->id}}">Deskripsi (Opsional)</label>
                                            </div>
                                            <div class="form-check form-switch ps-0 ms-0 d-flex align-items-center bg-light p-3 rounded-3">
                                                <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" value="1" id="switch{{$cat->id}}" {{ $cat->is_active ? 'checked' : '' }} style="margin-left: 0 !important;">
                                                <label class="form-check-label fw-bold small" for="switch{{$cat->id}}">Status Kategori Aktif</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="py-4">
                                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                        <i class="bi bi-folder-x fs-1 text-muted opacity-50"></i>
                                    </div>
                                    <h5 class="fw-bold text-muted">Belum ada kategori</h5>
                                    <p class="text-muted small mb-4">Mulai tambahkan kategori untuk mengatur produk Anda.</p>
                                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                                        Tambah Kategori Sekarang
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Kategori</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control rounded-3" id="addName" placeholder="Contoh: Seafood" required>
                        <label for="addName">Nama Kategori</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="description" class="form-control rounded-3" id="addDesc" placeholder="Deskripsi" style="height: 100px"></textarea>
                        <label for="addDesc">Deskripsi (Opsional)</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pe-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling Avatar Inisial */
    .avatar-initial {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    /* Tombol Aksi Bulat */
    .btn-action {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }

    /* Efek Hover Baris Tabel */
    .transition-hover:hover {
        background-color: #f8f9fa;
    }

    /* Hover Scale Button */
    .hover-scale {
        transition: transform 0.2s;
    }
    .hover-scale:hover {
        transform: scale(1.02);
    }

    /* Animations */
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    .animate-up {
        animation: slideUp 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .letter-spacing-1 {
        letter-spacing: 1px;
    }
</style>
@endsection