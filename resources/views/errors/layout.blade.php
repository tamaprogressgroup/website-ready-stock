<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terjadi Kesalahan') — Paradise Ready Stock</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #241F61;
            --blue:   #3065A3;
            --gold:   #F9A61A;
            --green:  #43CB83;
            --light:  #f5f7fb;
            --text:   #333;
            --muted:  #7a8599;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text);
        }

        /* ── NAVBAR ── */
        .err-nav {
            background: #fff;
            border-bottom: 1px solid #e8edf5;
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .err-nav img { height: 40px; }
        .err-nav-link {
            color: var(--blue);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: opacity .2s;
        }
        .err-nav-link:hover { opacity: .7; }

        /* ── MAIN CONTENT ── */
        .err-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 24px;
        }
        .err-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 8px 48px rgba(36,31,97,.09);
            padding: 60px 56px;
            max-width: 620px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .err-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--navy), var(--blue), var(--gold));
        }

        /* ── ERROR CODE ── */
        .err-code-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
        }
        .err-code-bg {
            font-size: 140px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -4px;
            color: transparent;
            -webkit-text-stroke: 2px #e2e8f5;
            user-select: none;
            pointer-events: none;
        }
        .err-icon-center {
            position: absolute;
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--navy), var(--blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 28px;
            box-shadow: 0 8px 24px rgba(36,31,97,.25);
        }

        /* ── TEXT ── */
        .err-badge {
            display: inline-block;
            background: #eef2ff;
            color: var(--blue);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 30px;
            margin-bottom: 14px;
        }
        .err-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
            line-height: 1.3;
        }
        .err-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.8;
            margin-bottom: 36px;
        }

        /* ── BUTTONS ── */
        .err-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-primary-err {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--navy), var(--blue));
            color: #fff;
            text-decoration: none;
            padding: 13px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(36,31,97,.25);
        }
        .btn-primary-err:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(36,31,97,.35); }
        .btn-outline-err {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: var(--blue);
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            border: 1.5px solid #c5d4ea;
            cursor: pointer;
            transition: background .2s, border-color .2s;
        }
        .btn-outline-err:hover { background: #f0f5ff; border-color: var(--blue); }

        /* ── DIVIDER ── */
        .err-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 32px 0;
            color: #c5cedd;
            font-size: 12px;
        }
        .err-divider::before, .err-divider::after {
            content: ''; flex: 1; height: 1px; background: #e8edf5;
        }

        /* ── QUICK LINKS ── */
        .err-quick-links {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .err-quick-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 20px;
            background: var(--light);
            transition: color .2s, background .2s;
        }
        .err-quick-link:hover { color: var(--blue); background: #e8f0fb; }

        /* ── FOOTER ── */
        .err-footer {
            text-align: center;
            padding: 20px 24px;
            font-size: 12px;
            color: var(--muted);
            border-top: 1px solid #e8edf5;
            background: #fff;
        }
        .err-footer span { color: var(--navy); font-weight: 600; }

        /* ── RESPONSIVE ── */
        @media (max-width: 576px) {
            .err-nav { padding: 14px 20px; }
            .err-card { padding: 40px 24px; border-radius: 16px; }
            .err-code-bg { font-size: 100px; }
            .err-icon-center { width: 58px; height: 58px; font-size: 22px; }
            .err-title { font-size: 20px; }
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="err-nav">
        <a href="{{ url('/') }}">
            <img src="{{ asset('stock-image/progress-logo-colored.png') }}" alt="Paradise Ready Stock">
        </a>
        <a href="{{ url('/') }}" class="err-nav-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
    </nav>

    {{-- Main --}}
    <main class="err-main">
        <div class="err-card">

            {{-- Error code visual --}}
            <div class="err-code-wrap">
                <div class="err-code-bg">@yield('code', '??')</div>
                <div class="err-icon-center">
                    <i class="@yield('icon', 'fas fa-exclamation')"></i>
                </div>
            </div>

            {{-- Badge --}}
            <div class="err-badge">Error @yield('code', '??')</div>

            {{-- Title & Description --}}
            <h1 class="err-title">@yield('title', 'Terjadi Kesalahan')</h1>
            <p class="err-desc">@yield('description', 'Terjadi kesalahan yang tidak terduga. Silakan coba beberapa saat lagi.')</p>

            {{-- Actions --}}
            <div class="err-actions">
                <a href="{{ url('/') }}" class="btn-primary-err">
                    <i class="fas fa-home"></i> Beranda
                </a>
                @yield('extra_action')
                <button onclick="history.back()" class="btn-outline-err">
                    <i class="fas fa-arrow-left"></i> Halaman Sebelumnya
                </button>
            </div>

            {{-- Quick links --}}
            <div class="err-divider">atau kunjungi halaman lain</div>
            <div class="err-quick-links">
                <a href="{{ url('/all-products') }}" class="err-quick-link">
                    <i class="fas fa-building"></i> Semua Properti
                </a>
                <!-- <a href="{{ url('/') }}#contact" class="err-quick-link">
                    <i class="fas fa-phone"></i> Hubungi Kami
                </a> -->
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="err-footer">
        &copy; {{ date('Y') }} <span>Paradise Ready Stock</span>. Hak cipta dilindungi.
    </footer>

</body>
</html>
