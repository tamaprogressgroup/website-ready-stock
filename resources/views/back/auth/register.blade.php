<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <title>Daftar Akun - Paradise Ready Stock</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        body { background-color: #f0f4f8; }
        .register-card {
            max-width: 460px;
            margin: 60px auto;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(48, 101, 163, 0.12);
        }
        .register-header {
            background: linear-gradient(135deg, #3065A3, #3b5998);
            border-radius: 16px 16px 0 0;
            padding: 32px 32px 24px;
            text-align: center;
        }
        .register-body { padding: 32px; }
        .btn-register {
            background: #3065A3;
            border: none;
            border-radius: 8px;
            height: 48px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
        }
        .btn-register:hover { background: #3b5998; }
        .form-control:focus { border-color: #3065A3; box-shadow: 0 0 0 0.2rem rgba(48,101,163,.2); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card register-card border-0">
            <div class="register-header">
                <h4 class="text-white font-weight-bold mb-1">Daftar Akun</h4>
                <p class="text-white mb-0" style="opacity:0.8; font-size:14px;">Paradise Ready Stock — Back Office</p>
            </div>
            <div class="register-body">

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4">
                        <ul class="mb-0 ps-3" style="font-size:13px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="alert rounded-3 mb-4 d-flex align-items-start gap-2" style="background:#fff8e1; border:1px solid #ffe082; font-size:13px;">
                    <i class="fas fa-info-circle mt-1" style="color:#f59e0b;"></i>
                    <span>Setelah mendaftar, akun kamu perlu <strong>diaktifkan oleh admin</strong> sebelum bisa login.</span>
                </div>

                <form action="{{ route('back.register.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size:13px;">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control border-0 bg-light @error('name') is-invalid @enderror"
                               style="border-radius:8px; height:48px;"
                               placeholder="cth: Budi Santoso" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size:13px;">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control border-0 bg-light @error('email') is-invalid @enderror"
                               style="border-radius:8px; height:48px;"
                               placeholder="email@perusahaan.id" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size:13px;">Password <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password"
                                   class="form-control border-0 bg-light @error('password') is-invalid @enderror"
                                   style="border-radius:8px; height:48px; padding-right:48px;"
                                   placeholder="Min. 8 karakter" required>
                            <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent pe-3"
                                    onclick="togglePass('password')" tabindex="-1">
                                <i class="fas fa-eye text-muted" id="eye-password" style="font-size:14px;"></i>
                            </button>
                        </div>
                        @error('password')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary" style="font-size:13px;">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control border-0 bg-light"
                                   style="border-radius:8px; height:48px; padding-right:48px;"
                                   placeholder="Ulangi password" required>
                            <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent pe-3"
                                    onclick="togglePass('password_confirmation')" tabindex="-1">
                                <i class="fas fa-eye text-muted" id="eye-password_confirmation" style="font-size:14px;"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register text-white w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </button>

                    <p class="text-center mb-0" style="font-size:13px; color:#888;">
                        Sudah punya akun?
                        <a href="{{ route('back.login') }}" style="color:#3065A3; font-weight:600; text-decoration:none;">Masuk di sini</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function togglePass(fieldId) {
            const input = document.getElementById(fieldId);
            const icon  = document.getElementById('eye-' + fieldId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
