<style>
/* ===== NUMBER INPUT — hilangkan tombol increment/decrement (spinner) di semua form front ===== */
/* Murni visual, tidak mempengaruhi value/validasi — input type=number tetap berfungsi normal */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
}

/* ===== LOGO ===== */
.header-logo-dark  { display: none !important; }
.header-logo-light { display: block !important; }
html.sticky-header-active .header-logo-light { display: none !important; }
html.sticky-header-active .header-logo-dark  { display: block !important; }

/* ===== NAV COLORS ===== */
.custom-nav-link, .custom-search-text, .custom-search-icon { color: #ffffff !important; }
html.sticky-header-active .custom-nav-link,
html.sticky-header-active .custom-search-text,
html.sticky-header-active .custom-search-icon { color: #333333 !important; }

.nav-btn-home {
    background-color: #536996;
    border-radius: 25px;
    padding: 10px 24px !important;
    margin-right: 10px;
    color: #ffffff !important;
}
html.sticky-header-active .nav-btn-home {
    background-color: transparent !important;
    color: #3065A3 !important;
    padding: 10px 18px !important;
}
html.sticky-header-active #header .header-body {
    background-color: #ffffff !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
}

/* ===== PROJECT DROPDOWN ===== */
li.nav-dropdown { position: relative; display: flex; align-items: center; }
li.nav-dropdown > a { display: flex; align-items: center; gap: 6px; }
.mob-chevron { display: none; }

li.nav-dropdown > a .dd-caret {
    font-size: 10px;
    transition: transform 0.25s cubic-bezier(.4,0,.2,1);
    opacity: 0.75;
}
li.nav-dropdown:hover > a .dd-caret { transform: rotate(180deg); opacity: 1; }

.nav-dd-panel {
    opacity: 0;
    pointer-events: none;
    transform: translateY(10px);
    transition: opacity 0.22s cubic-bezier(.4,0,.2,1), transform 0.22s cubic-bezier(.4,0,.2,1);
    position: absolute;
    top: calc(100% + 10px);
    left: 50%;
    translate: -50% 0;
    width: 260px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 40px rgba(30,60,120,0.16), 0 2px 8px rgba(0,0,0,0.06);
    padding: 10px 10px 12px;
    z-index: 9999;
    list-style: none;
    margin: 0;
}
/* arrow tip */
.nav-dd-panel::before {
    content: '';
    position: absolute;
    top: -7px;
    left: 50%;
    translate: -50% 0;
    width: 14px; height: 14px;
    background: #ffffff;
    border-radius: 3px;
    transform: rotate(45deg);
    box-shadow: -2px -2px 6px rgba(30,60,120,0.07);
}
li.nav-dropdown:hover .nav-dd-panel {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0);
}

/* header label inside panel */
.nav-dd-panel .dd-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.2px;
    color: #aab4c8;
    text-transform: uppercase;
    padding: 6px 12px 8px;
    display: block;
}
/* each project item */
.nav-dd-panel li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    color: #2a3a5e !important;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.15s, color 0.15s, transform 0.15s;
    white-space: nowrap;
}
.nav-dd-panel li a .dd-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, #e8eef8 0%, #d0dcf4 100%);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    color: #3065A3;
    font-size: 13px;
    transition: background 0.15s;
}
.nav-dd-panel li a:hover {
    background: linear-gradient(135deg, #f0f5ff 0%, #e6eeff 100%);
    color: #3065A3 !important;
    transform: translateX(2px);
}
.nav-dd-panel li a:hover .dd-icon {
    background: linear-gradient(135deg, #3065A3 0%, #4a7fc1 100%);
    color: #fff;
}

/* ===== MOBILE NAV ===== */
@media (max-width: 991px) {
    #mainNav > li {
        display: block !important;
        width: 100%;
        border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    #mainNav > li:last-child { border-bottom: none; }

    #mainNav a.nav-link.custom-nav-link {
        color: #e8edf5 !important;
        padding: 15px 20px !important;
        margin: 0 !important;
        font-size: 13.5px !important;
        letter-spacing: 0.3px;
        display: flex !important;
        align-items: center;
        justify-content: space-between;
    }
    #mainNav a.nav-link.custom-nav-link:hover,
    #mainNav a.nav-link.custom-nav-link:active {
        background: rgba(255,255,255,0.06) !important;
        color: #ffffff !important;
    }
    #mainNav a.nav-link.nav-btn-home {
        color: #ffffff !important;
        background-color: #3065A3 !important;
        border-radius: 0 !important;
        margin: 0 !important;
        padding: 15px 20px !important;
        display: block !important;
        font-size: 13.5px !important;
        letter-spacing: 0.3px;
    }

    /* Project dropdown — hidden by default, toggle on click */
    li.nav-dropdown { display: block !important; }
    li.nav-dropdown > a.nav-link {
        display: flex !important;
        justify-content: space-between;
        align-items: center;
    }
    .mob-chevron { display: inline-block !important; }
    li.nav-dropdown > a.nav-link .mob-chevron {
        font-size: 11px;
        opacity: 0.5;
        transition: transform 0.25s ease;
        margin-left: 8px;
    }
    li.nav-dropdown.mobile-open > a.nav-link .mob-chevron {
        transform: rotate(180deg);
        opacity: 1;
    }
    li.nav-dropdown .nav-dd-panel {
        position: static !important;
        opacity: 0 !important;
        pointer-events: none !important;
        transform: none !important;
        translate: unset !important;
        left: auto !important;
        width: 100% !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        background: rgba(0,0,0,0.25) !important;
        margin: 0 !important;
        padding: 0 !important;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, opacity 0.2s ease !important;
    }
    li.nav-dropdown.mobile-open .nav-dd-panel {
        opacity: 1 !important;
        pointer-events: auto !important;
        max-height: 600px;
        padding: 6px 0 10px !important;
    }
    li.nav-dropdown .nav-dd-panel::before { display: none !important; }
    li.nav-dropdown .nav-dd-panel .dd-label {
        color: rgba(255,255,255,0.35) !important;
        padding: 8px 20px 6px !important;
        font-size: 10px;
    }
    li.nav-dropdown .nav-dd-panel li a {
        color: rgba(255,255,255,0.8) !important;
        padding: 10px 20px !important;
        border-radius: 0 !important;
        font-size: 13.5px !important;
        white-space: normal !important;
        transform: none !important;
    }
    li.nav-dropdown .nav-dd-panel li a:hover {
        background: rgba(255,255,255,0.07) !important;
        color: #ffffff !important;
        transform: none !important;
    }
    li.nav-dropdown .nav-dd-panel li a .dd-icon {
        background: rgba(255,255,255,0.1) !important;
        color: rgba(255,255,255,0.7) !important;
        width: 28px !important;
        height: 28px !important;
        font-size: 12px !important;
    }
}

/* ===== NAV SEARCH PANEL ===== */
.nav-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.nav-search-panel {
    opacity: 0;
    pointer-events: none;
    transform: translateY(10px);
    transition: opacity 0.22s cubic-bezier(.4,0,.2,1), transform 0.22s cubic-bezier(.4,0,.2,1);
    position: absolute;
    top: calc(100% + 14px);
    right: 0;
    width: 340px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 40px rgba(30,60,120,0.16), 0 2px 8px rgba(0,0,0,0.06);
    padding: 16px;
    z-index: 9999;
}
.nav-search-panel::before {
    content: '';
    position: absolute;
    top: -7px;
    right: 28px;
    width: 14px; height: 14px;
    background: #fff;
    border-radius: 3px;
    transform: rotate(45deg);
    box-shadow: -2px -2px 6px rgba(30,60,120,0.07);
}
.nav-search-panel.is-open {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0);
}
.nav-search-inner {
    display: flex;
    align-items: center;
    gap: 0;
    background: #f4f6fb;
    border-radius: 10px;
    padding: 4px 4px 4px 14px;
    border: 1.5px solid #e0e7f3;
    transition: border-color 0.15s;
}
.nav-search-inner:focus-within {
    border-color: #3065A3;
    background: #fff;
}
.nav-search-inner input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-size: 14px;
    color: #2a3a5e;
    font-family: inherit;
    padding: 6px 0;
}
.nav-search-inner input::placeholder { color: #aab4c8; }
.nav-search-btn {
    width: 36px; height: 36px;
    border-radius: 8px;
    background: linear-gradient(135deg, #3065A3 0%, #4a7fc1 100%);
    border: none;
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    cursor: pointer;
    transition: opacity 0.15s;
    flex-shrink: 0;
}
.nav-search-btn:hover { opacity: 0.85; }
.nav-search-hint {
    font-size: 11px;
    color: #aab4c8;
    margin-top: 9px;
    text-align: center;
    letter-spacing: 0.3px;
}
</style>

@php
    $navKeySuffix  = request('key') ? '?key=' . rawurlencode(request('key')) : '';
    $navTownships  = \App\Models\Township::orderBy('township_name')->get();
@endphp

{{-- ===== HEADER ===== --}}
<header id="header" class="header-transparent header-effect-shrink"
    data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': true, 'stickyStartAt': 30, 'stickyHeaderContainerHeight': 70}">

    <div class="header-body border-top-0 bg-transparent box-shadow-none">
        <div class="header-container container">
            <div class="header-row">

                <div class="header-column">
                    <div class="header-row">
                        <div class="header-logo">
                            <a href="{{ url('/') }}{{ $navKeySuffix }}" class="text-decoration-none">
                                <h2 class="font-weight-bold text-6 mb-0 d-flex align-items-center">
                                    <img src="{{ asset('stock-image/progress-logo.png') }}"         alt="Progress Group" width="180px" class="header-logo-light">
                                    <img src="{{ asset('stock-image/progress-logo-colored.png') }}" alt="Progress Group" width="180px" class="header-logo-dark">
                                </h2>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="header-column justify-content-end">
                    <div class="header-row align-items-center">

                        <div class="header-nav header-nav-links header-nav-dropdowns-dark header-nav-light-text order-2 order-lg-1">
                            <div class="header-nav-main header-nav-main-mobile-dark header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-effect-2 header-nav-main-sub-effect-1">
                                <nav class="collapse">
                                    <ul class="nav nav-pills" style="align-items:stretch;" id="mainNav">

                                        {{-- Home --}}
                                        <li style="display:flex;align-items:center;">
                                            <a class="nav-link font-weight-bold nav-btn-home poppins-semibold"
                                               href="{{ url('/') }}{{ $navKeySuffix }}">Home</a>
                                        </li>

                                        {{-- Rekomendasi Properti --}}
                                        <li style="display:flex;align-items:center;">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold"
                                               href="{{ route('front.all-products') }}?tags=Features{{ $navKeySuffix ? '&' . ltrim($navKeySuffix, '?') : '' }}"
                                               style="padding: 10px 18px !important; margin: 0 3px;">
                                               Rekomendasi Properti
                                            </a>
                                        </li>

                                        {{-- Properti Baru --}}
                                        <li style="display:flex;align-items:center;">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold"
                                               href="{{ route('front.all-products') }}?tags=Properti+Baru{{ $navKeySuffix ? '&' . ltrim($navKeySuffix, '?') : '' }}"
                                               style="padding: 10px 18px !important; margin: 0 3px;">
                                               Properti Baru
                                            </a>
                                        </li>

                                        @if($navSewaEnabled ?? false)
                                        {{-- Dijual --}}
                                        <li style="display:flex;align-items:center;">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold"
                                               href="{{ route('front.all-products') }}?listing_type=jual{{ $navKeySuffix ? '&' . ltrim($navKeySuffix, '?') : '' }}"
                                               style="padding: 10px 18px !important; margin: 0 3px;">
                                               Dijual
                                            </a>
                                        </li>

                                        {{-- Disewa --}}
                                        <li style="display:flex;align-items:center;">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold"
                                               href="{{ route('front.all-products') }}?listing_type=sewa{{ $navKeySuffix ? '&' . ltrim($navKeySuffix, '?') : '' }}"
                                               style="padding: 10px 18px !important; margin: 0 3px;">
                                               Disewa
                                            </a>
                                        </li>
                                        @endif

                                        {{-- Project (dropdown) --}}
                                        <li class="nav-dropdown" style="margin: 0 14px 0 3px;">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold"
                                               href="#"
                                               style="padding: 10px 18px !important;">
                                               Project
                                               <i class="fas fa-chevron-down mob-chevron"></i>
                                            </a>
                                            @if($navTownships->isNotEmpty())
                                            <ul class="nav-dd-panel poppins-semibold">
                                                <span class="dd-label">Pilih Project</span>
                                                @foreach($navTownships as $twn)
                                                <li>
                                                    <a href="{{ route('front.all-products') }}?twp={{ $twn->township_id }}{{ $navKeySuffix ? '&' . ltrim($navKeySuffix, '?') : '' }}">
                                                        <span class="dd-icon"><i class="fas fa-city"></i></span>
                                                        {{ $twn->township_name }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>

                                    </ul>
                                </nav>
                            </div>
                            <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>

                        <div class="header-nav-features header-nav-features-light header-nav-features-no-border order-1 order-lg-2 ms-4 d-flex align-items-center">
                            <div class="nav-search-wrap">
                                <button id="nav-search-toggle" type="button"
                                    class="btn p-0 border-0 bg-transparent text-decoration-none d-flex align-items-center"
                                    style="gap:8px;">
                                    <i class="fas fa-search custom-search-icon" style="font-size:16px;"></i>
                                    <span class="font-weight-bold custom-search-text poppins-semibold" style="font-size:15px;">Search</span>
                                </button>
                                <div class="nav-search-panel" id="nav-search-panel">
                                    <div class="nav-search-inner">
                                        <input type="text" id="nav-search-input" placeholder="Cari properti..." autocomplete="off">
                                        <button class="nav-search-btn" id="nav-search-go" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="nav-search-hint">Tekan Enter atau klik <i class="fas fa-search"></i> untuk mencari</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</header>

{{-- Mobile Search Modal --}}
<div id="mob-search-overlay"
     style="display:none;position:fixed;inset:0;z-index:20000;background:rgba(10,18,40,0.72);backdrop-filter:blur(4px);align-items:flex-start;justify-content:center;padding-top:80px;"
     onclick="if(event.target===this)closeMobSearch()">
    <div style="width:calc(100% - 32px);max-width:400px;background:#fff;border-radius:16px;padding:20px;box-shadow:0 16px 48px rgba(0,0,0,0.25);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <span style="font-size:15px;font-weight:700;color:#2a3a5e;font-family:inherit;">Cari Properti</span>
            <button onclick="closeMobSearch()" style="background:none;border:none;color:#aab4c8;font-size:20px;cursor:pointer;line-height:1;padding:0;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div style="display:flex;align-items:center;gap:0;background:#f4f6fb;border-radius:10px;padding:4px 4px 4px 14px;border:1.5px solid #e0e7f3;">
            <input type="text" id="mob-search-input" placeholder="Nama properti, lokasi..."
                   autocomplete="off"
                   style="flex:1;border:none;background:transparent;outline:none;font-size:15px;color:#2a3a5e;font-family:inherit;padding:8px 0;">
            <button id="mob-search-go" type="button"
                    style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,#3065A3 0%,#4a7fc1 100%);border:none;color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px;cursor:pointer;flex-shrink:0;">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div style="font-size:11px;color:#aab4c8;margin-top:10px;text-align:center;">Tekan Enter atau ketuk <i class="fas fa-search"></i> untuk mencari</div>
    </div>
</div>

<script>
(function () {
    const toggle   = document.getElementById('nav-search-toggle');
    const panel    = document.getElementById('nav-search-panel');
    const input    = document.getElementById('nav-search-input');
    const goBtn    = document.getElementById('nav-search-go');
    const mobOver  = document.getElementById('mob-search-overlay');
    const mobInput = document.getElementById('mob-search-input');
    const mobGo    = document.getElementById('mob-search-go');
    const allUrl   = '{{ route('front.all-products') }}';

    function isMobile() { return window.innerWidth <= 991; }

    // ── Desktop panel ──────────────────────────────────────────────
    function openPanel() {
        panel.classList.add('is-open');
        setTimeout(function () { input.focus(); }, 50);
    }
    function closePanel() {
        panel.classList.remove('is-open');
        input.value = '';
    }
    function doSearch() {
        var q = input.value.trim();
        if (!q) return;
        window.location.href = allUrl + '?q=' + encodeURIComponent(q);
    }

    // ── Mobile modal ───────────────────────────────────────────────
    window.closeMobSearch = function () {
        mobOver.style.display = 'none';
        mobInput.value = '';
    };
    function openMobSearch() {
        mobOver.style.display = 'flex';
        setTimeout(function () { mobInput.focus(); }, 80);
    }
    function doMobSearch() {
        var q = mobInput.value.trim();
        if (!q) return;
        window.location.href = allUrl + '?q=' + encodeURIComponent(q);
    }

    // ── Toggle: route to mobile or desktop ────────────────────────
    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        if (isMobile()) {
            openMobSearch();
        } else {
            panel.classList.contains('is-open') ? closePanel() : openPanel();
        }
    });

    goBtn.addEventListener('click', doSearch);
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') doSearch();
        if (e.key === 'Escape') closePanel();
    });
    panel.addEventListener('click', function (e) { e.stopPropagation(); });
    document.addEventListener('click', closePanel);

    mobGo.addEventListener('click', doMobSearch);
    mobInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') doMobSearch();
        if (e.key === 'Escape') closeMobSearch();
    });
})();

// Mobile: toggle Project dropdown on click
(function () {
    var navDd = document.querySelector('li.nav-dropdown');
    if (!navDd) return;
    var ddToggle = navDd.querySelector(':scope > a');
    if (!ddToggle) return;

    ddToggle.addEventListener('click', function (e) {
        if (window.innerWidth > 991) return; // desktop: let CSS :hover handle it
        e.preventDefault();
        navDd.classList.toggle('mobile-open');
    });
})();
</script>
