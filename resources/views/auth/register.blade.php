@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .register-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .register-image {
        /* Gambar berbeda: Tema Ingredients/Memasak */
        background: url('https://images.unsplash.com/photo-1556910103-1c02745a30bf?q=80&w=2070&auto=format&fit=crop') center center no-repeat;
        background-size: cover;
        position: relative;
    }
    
    .register-image::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.5));
    }

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
            <div class="card register-card animate-up border-0">
                <div class="row g-0">
                    
                    <div class="col-lg-6 d-none d-lg-block register-image">
                        <div class="d-flex align-items-end h-100 p-5">
                            <div class="text-white position-relative" style="z-index: 2;">
                                <h2 class="fw-bold mb-2">Bergabung Bersama Kami</h2>
                                <p class="mb-0 text-white-50">Daftarkan diri Anda untuk menikmati kemudahan memesan makanan lezat tanpa antri.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card-body p-md-5 mx-md-4">

                            <div class="text-center mb-4">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
                                    <i class="bi bi-person-plus-fill fs-1"></i>
                                </div>
                                <h3 class="fw-bold text-dark">Buat Akun Baru</h3>
                                <p class="text-muted">Lengkapi data diri Anda di bawah ini.</p>
                            </div>

                            <form action="{{ route('register') }}" method="POST">
                                @csrf

                                <div class="form-floating mb-3">
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="nameInput" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                                    <label for="nameInput">Nama Lengkap</label>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="emailInput" placeholder="name@example.com" value="{{ old('email') }}" required>
                                            <label for="emailInput">Alamat Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" id="phoneInput" placeholder="0812..." value="{{ old('phone_number') }}" required>
                                            <label for="phoneInput">Nomor HP</label>
                                        </div>
                                    </div>
                                </div>
                                @error('email') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                                @error('phone_number') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                                <div class="form-floating mb-3">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="passInput" placeholder="Password" required>
                                    <label for="passInput">Kata Sandi</label>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-floating mb-4">
                                    <input type="password" name="password_confirmation" class="form-control" id="confPassInput" placeholder="Ulangi Password" required>
                                    <label for="confPassInput">Ulangi Kata Sandi</label>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                    <label class="form-check-label small text-muted" for="termsCheck">
                                        Saya setuju dengan <a href="#" class="text-decoration-none fw-bold">Syarat & Ketentuan</a> RestoEnak.
                                    </label>
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm fw-bold">
                                        DAFTAR SEKARANG <i class="bi bi-rocket-takeoff ms-1"></i>
                                    </button>
                                </div>

                                <div class="text-center">
                                    <p class="mb-0 text-muted">Sudah punya akun? 
                                        <a href="{{ route('login') }}" class="fw-bold text-success text-decoration-none">Masuk disini</a>
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