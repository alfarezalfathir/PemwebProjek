@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    
    <div class="row g-4 mb-5 animate-fade-in">
        <div class="col-lg-6">
            <h2 class="fw-bold text-dark display-6 mb-1 ls-tight">Riwayat Transaksi</h2>
            <p class="text-muted mb-0">Laporan lengkap penjualan dan status pesanan.</p>
        </div>
        <div class="col-lg-6">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden position-relative">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                            <div>
                                <small class="text-white-50 text-uppercase fw-bold letter-spacing-1">Total Pendapatan</small>
                                <h4 class="fw-bold mb-0">Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <i class="bi bi-wallet2 position-absolute top-50 end-0 translate-middle-y me-n3 opacity-10" style="font-size: 5rem;"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                                <i class="bi bi-cart-check-fill fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase fw-bold letter-spacing-1">Total Pesanan</small>
                                <h4 class="fw-bold text-dark mb-0">{{ $orders->count() }} Transaksi</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 animate-up" style="z-index: 10;">
        <div class="card-body p-2">
            <form action="{{ route('transactions.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="date" name="start_date" class="form-control border-0 bg-light rounded-3" id="startDate" value="{{ request('start_date') }}">
                        <label for="startDate">Dari Tanggal</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="date" name="end_date" class="form-control border-0 bg-light rounded-3" id="endDate" value="{{ request('end_date') }}">
                        <label for="endDate">Sampai Tanggal</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select name="status" class="form-select border-0 bg-light rounded-3" id="filterStatus">
                            <option value="all">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Menunggu Pembayaran</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Selesai / Lunas</option>
                        </select>
                        <label for="filterStatus">Status Pesanan</label>
                    </div>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary h-100 rounded-3 fw-bold shadow-sm">
                        <i class="bi bi-funnel-fill me-2"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-xl rounded-4 overflow-hidden animate-up delay-1">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Invoice</th>
                            <th class="py-3 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Customer</th>
                            <th class="py-3 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Tipe Pesanan</th>
                            <th class="py-3 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Total</th>
                            <th class="py-3 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Status</th>
                            <th class="py-3 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Waktu</th>
                            <th class="text-end pe-4 py-3 text-uppercase text-secondary small fw-bold letter-spacing-1 border-0">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="transition-hover border-bottom border-light">
                            <td class="ps-4 py-3">
                                <span class="d-flex align-items-center fw-bold text-dark font-monospace">
                                    <i class="bi bi-hash text-muted me-1"></i>{{ substr($order->invoice_code, -6) }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-initial rounded-circle bg-light text-primary fw-bold me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ $order->user->name ?? 'Guest' }}</h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $order->items_count ?? $order->items->count() }} Menu</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($order->table)
                                    <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill">
                                        <i class="bi bi-display me-1 text-primary"></i> Meja {{ $order->table->table_number }}
                                    </span>
                                @else
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                        <i class="bi bi-bag-fill me-1"></i> Take Away
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="fw-bold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </td>

                            <td>
                                @if($order->status == 'pending')
                                    <div class="d-flex align-items-center">
                                        <span class="badge-dot bg-warning animate-pulse me-2"></span>
                                        <span class="fw-bold text-warning-emphasis small">Menunggu</span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span class="fw-bold text-success small">Selesai</span>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <small class="text-muted fw-medium">
                                    {{ $order->created_at ? $order->created_at->format('d M, H:i') : '-' }}
                                </small>
                            </td>

                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('transactions.show', $order->id) }}" class="btn btn-sm btn-light text-primary btn-action rounded-circle shadow-sm" title="Lihat Invoice">
                                        <i class="bi bi-receipt"></i>
                                    </a>

                                    @role('superadmin')
                                    <form action="{{ route('transactions.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus permanen transaksi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger btn-action rounded-circle shadow-sm" title="Hapus Riwayat">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4 opacity-50">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <h6>Tidak ada data transaksi ditemukan.</h6>
                                    <p class="small">Coba ubah filter tanggal atau status.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-top bg-light bg-opacity-50">
                {{ $orders->appends(request()->all())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    .ls-tight { letter-spacing: -0.02em; }
    .letter-spacing-1 { letter-spacing: 0.5px; }
    
    .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01); }
    
    .badge-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
    
    .btn-action {
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-action:hover { transform: translateY(-3px) scale(1.05); }

    .transition-hover { transition: background-color 0.2s ease; }
    .transition-hover:hover { background-color: #f8fafc; }

    .animate-pulse { animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(0.8); } 100% { opacity: 1; transform: scale(1); } }

    .animate-up { animation: fadeInUp 0.6s ease-out forwards; }
    .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
    .delay-1 { animation-delay: 0.1s; }

    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection