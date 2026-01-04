@extends('layouts.app')

@section('content')
<style>
    /* Background Gradient Halus */
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .login-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .login-image {
        background: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop') center center no-repeat;
        background-size: cover;
        position: relative;
    }
    
    /* Overlay Gelap di atas gambar agar teks terbaca (opsional) */
    .login-image::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.4));
    }

    .form-section {
        padding: 50px;
    }

    /* Animasi Masuk */
    .animate-up {
        animation: slideUp 0.8s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
            <div class="card login-card animate-up border-0">
                <div class="row g-0">
                    
                    <div class="col-lg-6 d-none d-lg-block login-image">
                        <div class="d-flex align-items-end h-100 p-4">
                            <div class="text-white position-relative" style="z-index: 2;">
                                <h2 class="fw-bold mb-1">Nikmati Hidangan Spesial</h2>
                                <p class="mb-0 text-white-50">Masuk untuk mulai memesan dan merasakan kelezatan nyata.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card-body p-md-5 mx-md-4">

                            <div class="text-center mb-4">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="bi bi-fire fs-1"></i>
                                </div>
                                <h3 class="fw-bold text-dark">Selamat Datang Kembali!</h3>
                                <p class="text-muted">Silakan login ke akun RestoEnak Anda.</p>
                            </div>

                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="emailInput" placeholder="name@example.com" required>
                                    <label for="emailInput">Alamat Email</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-4">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="passInput" placeholder="Password" required>
                                    <label for="passInput">Kata Sandi</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                        <label class="form-check-label text-muted small" for="rememberMe">Ingat Saya</label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none hover-primary">Lupa Password?</a>
                                    @endif
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm fw-bold">
                                        MASUK SEKARANG <i class="bi bi-arrow-right-short ms-1"></i>
                                    </button>
                                </div>

                                <div class="text-center">
                                    <p class="mb-0 text-muted">Belum punya akun? 
                                        <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none">Daftar disini</a>
                                    </p>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection