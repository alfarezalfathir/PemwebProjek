@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manajemen Meja</h2>
            <p class="text-muted mb-0">Atur denah, kapasitas, dan lokasi meja restoran.</p>
        </div>
        <button class="btn btn-primary rounded-pill shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addTableModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Meja
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4">
        @forelse($tables as $table)
        <div class="col-sm-6 col-md-4 col-lg-3 animate-up">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden hover-scale">
                
                <div class="position-absolute top-0 start-0 w-100" style="height: 6px; background: {{ $table->status == 'available' ? '#198754' : '#dc3545' }}"></div>
                
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        @if($table->location == 'indoor')
                            <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex p-3">
                                <i class="bi bi-house-door-fill fs-1"></i>
                            </div>
                        @else
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3">
                                <i class="bi bi-tree-fill fs-1"></i>
                            </div>
                        @endif
                    </div>

                    <h4 class="fw-bold text-dark mb-1">{{ $table->table_number }}</h4>
                    
                    <span class="badge {{ $table->location == 'indoor' ? 'bg-info' : 'bg-success' }} bg-opacity-10 text-dark border mb-3">
                        {{ ucfirst($table->location) }} Area
                    </span>
                    
                    <p class="text-muted small mb-4">
                        <i class="bi bi-people-fill me-1"></i> Kapasitas: <strong>{{ $table->capacity }} Orang</strong>
                    </p>

                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-outline-warning rounded-circle shadow-sm btn-action" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal{{ $table->id }}"
                                title="Edit Meja">
                            <i class="bi bi-pencil-fill"></i>
                        </button>

                        <form action="{{ route('tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus meja ini?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger rounded-circle shadow-sm btn-action" title="Hapus Meja">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal{{ $table->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Meja {{ $table->table_number }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('tables.update', $table->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body pt-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Nomor Meja</label>
                                <input type="text" name="table_number" class="form-control rounded-3" value="{{ $table->table_number }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Kapasitas (Orang)</label>
                                <input type="number" name="capacity" class="form-control rounded-3" value="{{ $table->capacity }}" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Lokasi</label>
                                <select name="location" class="form-select rounded-3">
                                    <option value="indoor" {{ $table->location == 'indoor' ? 'selected' : '' }}>Indoor (Dalam Ruangan)</option>
                                    <option value="outdoor" {{ $table->location == 'outdoor' ? 'selected' : '' }}>Outdoor (Luar Ruangan)</option>
                                </select>
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
        <div class="col-12 text-center py-5">
            <div class="mb-3">
                <i class="bi bi-layout-text-window-reverse fs-1 text-muted opacity-25"></i>
            </div>
            <h5 class="fw-bold text-muted">Belum ada data meja.</h5>
            <p class="text-muted small">Silakan tambahkan meja baru untuk memulai.</p>
        </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="addTableModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Meja Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tables.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nomor Meja</label>
                        <input type="text" name="table_number" class="form-control rounded-3" placeholder="Contoh: M-01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Kapasitas (Orang)</label>
                        <input type="number" name="capacity" class="form-control rounded-3" value="4" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Lokasi</label>
                        <select name="location" class="form-select rounded-3">
                            <option value="indoor">Indoor (Dalam Ruangan)</option>
                            <option value="outdoor">Outdoor (Luar Ruangan)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pe-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Meja</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .hover-scale { transition: transform 0.2s, box-shadow 0.2s; }
    .hover-scale:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    
    .btn-action { width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; }

    .animate-fade-in { animation: fadeIn 0.6s ease-in-out; }
    .animate-up { animation: slideUp 0.6s ease-out; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection