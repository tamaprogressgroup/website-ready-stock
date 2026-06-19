<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <title>Terima Kasih - Paradise Ready Stock</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link id="googleFonts" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }

        .ty-bg {
            position: fixed;
            inset: 0;
            background-image: url('{{ $image }}');
            background-size: cover;
            background-position: center;
            filter: blur(18px) brightness(0.55);
            transform: scale(1.05);
            z-index: 0;
        }

        .ty-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .ty-card {
            background: #fff;
            border-radius: 20px;
            padding: 44px 40px 36px;
            max-width: 560px;
            width: 100%;
            text-align: center;
            box-shadow: 0 12px 48px rgba(0,0,0,0.25);
        }

        .ty-card h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .ty-card .ty-subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .ty-image {
            width: 100%;
            border-radius: 12px;
            object-fit: cover;
            max-height: 260px;
            margin-bottom: 20px;
        }

        .ty-gallery-text {
            font-size: 14px;
            color: #888;
            margin-bottom: 4px;
        }

        .ty-hours {
            font-size: 15px;
            font-weight: 700;
            color: #333;
            margin-bottom: 28px;
        }

        .ty-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #e8b94a;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            border: none;
            border-radius: 25px;
            padding: 12px 22px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .ty-btn:hover {
            background: #d4a535;
            color: #fff;
        }

        .ty-btn-row {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .ty-countdown-num {
            font-size: 56px;
            font-weight: 800;
            color: #43CB83;
            line-height: 1;
            margin: 6px 0;
        }
        .ty-countdown-label {
            font-size: 13px;
            color: #888;
            margin-bottom: 20px;
        }
        .ty-wa-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #43CB83;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            border: none;
            border-radius: 25px;
            padding: 14px 28px;
            text-decoration: none;
            margin-bottom: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .ty-wa-btn:hover { background: #33b870; color: #fff; }
    </style>

	@include('partials.hubspot')
</head>
<body>

<div class="ty-bg"></div>

<div class="ty-wrapper">
    <div class="ty-card">
        <h1>Terima kasih!</h1>
        <p class="ty-subtitle">Kami telah menerima pesan Anda dan kami akan segera menghubungi Anda.</p>

        @if(!empty($waUrl))
        {{-- Countdown + WA redirect --}}
        <div style="margin-bottom:24px;">
            <p style="font-size:14px;color:#555;margin-bottom:4px;">Anda akan diarahkan ke WhatsApp dalam</p>
            <div class="ty-countdown-num" id="ty-count">5</div>
            <p class="ty-countdown-label">detik</p>
            <a href="{{ $waUrl }}" class="ty-wa-btn" id="ty-wa-link">
                <i class="fab fa-whatsapp" style="font-size:20px;"></i> Buka WhatsApp Sekarang
            </a>
        </div>
        <script>
        (function(){
            var n = 5;
            var el = document.getElementById('ty-count');
            var timer = setInterval(function(){
                n--;
                if(el) el.textContent = n;
                if(n <= 0){
                    clearInterval(timer);
                    window.location.href = '{{ addslashes($waUrl) }}';
                }
            }, 1000);
        })();
        </script>
        @endif

        <img src="{{ $image }}" alt="{{ $title ?? 'Property' }}" class="ty-image">

        <p class="ty-gallery-text">Kunjungi Marketing Gallery Kami</p>
        <p class="ty-hours">Buka Setiap Hari 09:00 – 21:00</p>

        <div class="ty-btn-row">
            <a href="{{ url('/') }}" class="ty-btn">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            <a href="https://maps.google.com" target="_blank" class="ty-btn">
                <i class="fas fa-map-marker-alt"></i> Buka Lokasi di Google Maps
            </a>
        </div>
    </div>
</div>

</body>
</html>
