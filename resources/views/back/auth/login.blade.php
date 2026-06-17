<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <title>Login - Paradise Ready Stock</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        body { background-color: #f0f4f8; }
        .login-card {
            max-width: 420px;
            margin: 80px auto;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(48, 101, 163, 0.12);
        }
        .login-header {
            background: linear-gradient(135deg, #3065A3, #3b5998);
            border-radius: 16px 16px 0 0;
            padding: 36px 32px 28px;
            text-align: center;
        }
        .login-body { padding: 32px; }
        .btn-login {
            background: #3065A3;
            border: none;
            border-radius: 8px;
            height: 48px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
        }
        .btn-login:hover { background: #3b5998; }
        .form-control:focus { border-color: #3065A3; box-shadow: 0 0 0 0.2rem rgba(48,101,163,.2); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card login-card border-0">
            <div class="login-header">
                <h4 class="text-white font-weight-bold mb-1">Progress Group</h4>
                <p class="text-white mb-0" style="opacity:0.8; font-size:14px;">Paradise Ready Stock — Back Office</p>
            </div>
            <div class="login-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('back.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size:13px;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control border-0 bg-light @error('email') is-invalid @enderror"
                               style="border-radius:8px; height:48px;"
                               placeholder="admin@readystock.id" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size:13px;">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password"
                                   class="form-control border-0 bg-light @error('password') is-invalid @enderror"
                                   style="border-radius:8px; height:48px; padding-right:48px;"
                                   placeholder="••••••••" required>
                            <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent pe-3"
                                    onclick="togglePass()" tabindex="-1">
                                <i class="fas fa-eye text-muted" id="eye-icon" style="font-size:14px;"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input me-2">
                        <!-- <label for="remember" class="form-check-label text-secondary" style="font-size:13px;">Ingat saya</label> -->
                    </div>
                    <button type="submit" class="btn btn-login text-white w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </button>

                    {{-- <div class="d-flex align-items-center my-3">
                        <hr class="flex-grow-1" style="border-color:#e0e0e0;">
                        <span class="mx-3 text-muted" style="font-size:12px;">atau</span>
                        <hr class="flex-grow-1" style="border-color:#e0e0e0;">
                    </div>

                    <a href="{{ route('back.register') }}" class="btn w-100 fw-semibold" style="border:2px solid #3065A3; color:#3065A3; border-radius:8px; height:48px; font-size:15px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-user-plus me-2"></i> Daftar Akun Baru
                    </a> --}}
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
