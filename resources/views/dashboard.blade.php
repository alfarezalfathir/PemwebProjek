@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
        <div>
            <h2 class="fw-bold text-dark display-6 mb-0 ls-tight">Dashboard Overview</h2>
            <p class="text-muted mb-0">Ringkasan performa restoran Anda hari ini.</p>
        </div>
        <div class="text-end text-muted small">
            <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d F Y') }}
        </div>
    </div>

    <div class="row g-4 mb-5 animate-up">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-primary text-white position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 text-uppercase fw-bold small mb-1 ls-tight">Total Pendapatan</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase fw-bold small mb-1 ls-tight">Total Transaksi</p>
                            <h3 class="fw-bold text-dark mb-0">{{ $totalOrders }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-cart-check-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase fw-bold small mb-1 ls-tight">Menu Aktif</p>
                            <h3 class="fw-bold text-dark mb-0">{{ $totalProducts }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase fw-bold small mb-1 ls-tight">Pelanggan</p>
                            <h3 class="fw-bold text-dark mb-0">{{ $totalCustomers }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row animate-up delay-1">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Grafik Penjualan Bulanan (Tahun {{ date('Y') }})</h5>
                    <span class="badge bg-light text-primary border rounded-pill px-3">Live Data</span>
                </div>
                <div class="card-body p-4">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Ambil data dari Controller
    const labels = @json($months);
    const data = @json($totals);

    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Bikin Gradient warna
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(13, 110, 253, 0.5)'); 
    gradient.addColorStop(1, 'rgba(13, 110, 253, 0.0)'); 

    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: data,
                backgroundColor: gradient,
                borderColor: '#0d6efd',
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#0d6efd',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>

<style>
    .ls-tight { letter-spacing: -0.02em; }
    .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
    .animate-up { animation: fadeInUp 0.6s ease-out forwards; }
    .delay-1 { animation-delay: 0.2s; }

    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* Fix Chart height Mobile */
    canvas {
        width: 100% !important;
        max-height: 400px;
    }
</style>
@endsection