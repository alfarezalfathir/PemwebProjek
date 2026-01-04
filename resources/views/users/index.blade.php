@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 animate-fade-in">
        <div class="mb-3 mb-md-0">
            <h2 class="fw-bold text-dark display-6 mb-0 ls-tight">Manajemen Pengguna</h2>
            <p class="text-muted mb-0 mt-1">Kelola akses, peran, dan data akun sistem.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 py-2 shadow-lg hover-scale fw-bold d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <div class="bg-white bg-opacity-25 rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                <i class="bi bi-plus text-white"></i>
            </div>
            Tambah User Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 bg-success bg-opacity-10 text-success" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 bg-danger bg-opacity-10 text-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-xl rounded-5 overflow-hidden animate-up bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-5 py-4 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Profil Pengguna</th>
                            <th class="py-4 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Kontak Email</th>
                            <th class="py-4 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Role & Jabatan</th>
                            <th class="py-4 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Status Bergabung</th>
                            <th class="pe-5 py-4 text-end text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="transition-hover border-bottom border-light">
                            <td class="ps-5 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-gradient rounded-circle text-white fw-bold me-3 shadow-sm d-flex align-items-center justify-content-center" 
                                         style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $user->name }}</h6>
                                        <span class="badge bg-light text-secondary border rounded-pill mt-1" style="font-size: 0.7rem;">ID: #{{ $user->id }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <span class="text-dark fw-medium d-flex align-items-center">
                                    <i class="bi bi-envelope text-muted me-2"></i> {{ $user->email }}
                                </span>
                            </td>

                            <td>
                                @php $role = $user->getRoleNames()->first() ?? 'customer'; @endphp
                                @if($role == 'superadmin')
                                    <span class="badge bg-dark bg-gradient text-white px-3 py-2 rounded-pill shadow-sm">
                                        <i class="bi bi-shield-lock-fill me-1"></i> Super Admin
                                    </span>
                                @elseif($role == 'manager')
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-briefcase-fill me-1"></i> Manager
                                    </span>
                                @elseif($role == 'cashier')
                                    <span class="badge bg-warning bg-opacity-10 text-warning-emphasis px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-cash-coin me-1"></i> Cashier
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-person-fill me-1"></i> Customer
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $user->created_at->format('d M Y') }}
                                </div>
                            </td>

                            <td class="pe-5 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-light text-primary btn-action rounded-circle shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal{{ $user->id }}"
                                            title="Edit Profil">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    @if(Auth::id() != $user->id)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus permanen user ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger btn-action rounded-circle shadow-sm" title="Hapus User">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow-lg">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Edit Profil User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body pt-4">
                                            <div class="form-floating mb-3">
                                                <input type="text" name="name" class="form-control rounded-3" id="editName{{$user->id}}" value="{{ $user->name }}" required>
                                                <label for="editName{{$user->id}}">Nama Lengkap</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="email" name="email" class="form-control rounded-3" id="editEmail{{$user->id}}" value="{{ $user->email }}" required>
                                                <label for="editEmail{{$user->id}}">Alamat Email</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="password" name="password" class="form-control rounded-3" id="editPass{{$user->id}}" placeholder="Isi jika ingin ganti">
                                                <label for="editPass{{$user->id}}">Password Baru (Opsional)</label>
                                            </div>
                                            <div class="form-floating">
                                                <select name="role" class="form-select rounded-3" id="editRole{{$user->id}}">
                                                    <option value="superadmin" {{ $user->hasRole('superadmin') ? 'selected' : '' }}>Super Admin</option>
                                                    <option value="manager" {{ $user->hasRole('manager') ? 'selected' : '' }}>Manager</option>
                                                    <option value="cashier" {{ $user->hasRole('cashier') ? 'selected' : '' }}>Cashier</option>
                                                    <option value="customer" {{ $user->hasRole('customer') ? 'selected' : '' }}>Customer</option>
                                                </select>
                                                <label for="editRole{{$user->id}}">Jabatan / Role</label>
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
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4 opacity-50">
                                    <i class="bi bi-people fs-1 d-block mb-3"></i>
                                    <h5>Belum ada data pengguna.</h5>
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

<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Register User Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control rounded-3" id="addName" placeholder="Nama" required>
                        <label for="addName">Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control rounded-3" id="addEmail" placeholder="Email" required>
                        <label for="addEmail">Alamat Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control rounded-3" id="addPass" placeholder="Password" required>
                        <label for="addPass">Password</label>
                    </div>
                    <div class="form-floating">
                        <select name="role" class="form-select rounded-3" id="addRole" required>
                            <option value="" disabled selected>-- Pilih Jabatan --</option>
                            <option value="superadmin">Super Admin (Akses Penuh)</option>
                            <option value="manager">Manager (Kelola Menu & Laporan)</option>
                            <option value="cashier">Cashier (Transaksi)</option>
                            <option value="customer">Customer (Pelanggan)</option>
                        </select>
                        <label for="addRole">Role / Jabatan</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pe-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .ls-tight { letter-spacing: -0.02em; }
    .letter-spacing-1 { letter-spacing: 1px; }
    
    .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    
    .btn-action {
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-action:hover { transform: translateY(-3px); }

    .transition-hover { transition: background-color 0.2s ease; }
    .transition-hover:hover { background-color: #f8fafc; }

    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.03); }

    .avatar-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .animate-up { animation: fadeInUp 0.5s ease-out forwards; }
    .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection