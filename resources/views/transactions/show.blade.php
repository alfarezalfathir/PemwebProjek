@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <h2 class="fw-bold text-dark mb-0">Detail Pesanan</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 animate-up">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 opacity-75">Kode Invoice</h5>
                            <h3 class="fw-bold mb-0 text-break">{{ $order->invoice_code }}</h3>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold text-uppercase">
                                {{ $order->status }}
                            </span>
                            <div class="mt-2 small opacity-75">
                                {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="row mb-5 border-bottom pb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted text-uppercase fw-bold small">Pelanggan</h6>
                            <h5 class="fw-bold text-dark">{{ $order->user->name ?? 'User Telah Dihapus' }}</h5>
                            <p class="text-muted small mb-0">{{ $order->user->email ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted text-uppercase fw-bold small">Lokasi Makan</h6>
                            @if($order->table)
                                <h5 class="fw-bold text-dark">Meja {{ $order->table->table_number }}</h5>
                                <span class="badge bg-light text-dark border">
                                    {{ ucfirst($order->table->location) }} Area
                                </span>
                            @else
                                <h5 class="fw-bold text-success">🛍️ Take Away</h5>
                                <small class="text-muted">(Dibungkus)</small>
                            @endif
                        </div>
                    </div>

                    <h6 class="text-muted text-uppercase fw-bold small mb-3">Rincian Menu</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-borderless align-middle">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="ps-3 rounded-start">Menu</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jml</th>
                                    <th class="text-end pe-3 rounded-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded-3 me-3 object-fit-cover" width="50" height="50">
                                            @else
                                                <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                            
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">
                                                    {{ $item->product->name ?? 'Menu Telah Dihapus' }}
                                                </h6>
                                                
                                                @if(!$item->product)
                                                    <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size: 0.6rem">Produk Hilang</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end pe-3 fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            @if($order->notes)
                            <div class="alert alert-warning border-0 bg-warning bg-opacity-10 rounded-3">
                                <small class="fw-bold text-warning-emphasis"><i class="bi bi-sticky me-1"></i> Catatan Pesanan:</small>
                                <p class="mb-0 small mt-1 text-dark">{{ $order->notes }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between border-0 px-0 pb-0">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item border-top border-dashed my-3"></li>
                                <li class="list-group-item d-flex justify-content-between border-0 px-0 pt-0">
                                    <span class="fw-bold fs-5 text-dark">Total Bayar</span>
                                    <span class="fw-bold fs-4 text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @role('superadmin|manager|cashier')
                    <div class="mt-5 d-flex gap-2 justify-content-end no-print">
                        <a href="{{ route('transactions.invoice', $order->id) }}" class="btn btn-outline-dark rounded-pill px-4">
                            <i class="bi bi-printer me-2"></i> Cetak Invoice
                        </a>
                        
                        @if($order->status == 'pending')
                            <form action="{{ route('transactions.confirm', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('Konfirmasi pembayaran ini selesai?')">
                                    <i class="bi bi-check-circle-fill me-2"></i> Terima Pembayaran
                                </button>
                            </form>
                        @endif
                    </div>
                    @endrole

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed { border-top-style: dashed !important; }
    .animate-fade-in { animation: fadeIn 0.6s ease-in-out; }
    .animate-up { animation: slideUp 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    @media print {
        .no-print, header, footer, .navbar { display: none !important; }
        .card { border: none !important; shadow: none !important; }
        body { background: white !important; }
        .animate-up { transform: none !important; animation: none !important; }
    }
</style>
@endsection