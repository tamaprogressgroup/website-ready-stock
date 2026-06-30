<style>
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
                                               href="{{ route('front.all-products') }}?tags=Diskon,Features{{ $navKeySuffix ? '&' . ltrim($navKeySuffix, '?') : '' }}"
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

                                        {{-- Project (dropdown) --}}
                                        <li class="nav-dropdown" style="margin: 0 14px 0 3px;">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold"
                                               href="#"
                                               style="padding: 10px 18px !important;">
                                               Project
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

<script>
(function () {
    const toggle  = document.getElementById('nav-search-toggle');
    const panel   = document.getElementById('nav-search-panel');
    const input   = document.getElementById('nav-search-input');
    const goBtn   = document.getElementById('nav-search-go');
    const allUrl  = '{{ route('front.all-products') }}';

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

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        panel.classList.contains('is-open') ? closePanel() : openPanel();
    });
    goBtn.addEventListener('click', doSearch);
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') doSearch();
        if (e.key === 'Escape') closePanel();
    });
    panel.addEventListener('click', function (e) { e.stopPropagation(); });
    document.addEventListener('click', closePanel);
})();
</script>
