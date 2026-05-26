@php
$typeIcons = [
    'rumah'        => 'fas fa-home',
    'apartemen'    => 'fas fa-building',
    'tanah'        => 'fas fa-map-marked-alt',
    'ruko'         => 'fas fa-store',
    'pabrik'       => 'fas fa-industry',
    'perkantoran'  => 'fas fa-briefcase',
    'ruang usaha'  => 'fas fa-door-open',
    'gudang'       => 'fas fa-warehouse',
    'kost'         => 'fas fa-bed',
    'villa'        => 'fas fa-umbrella-beach',
    'hotel'        => 'fas fa-concierge-bell',
    'kondominium'  => 'fas fa-city',
    'townhouse'    => 'fas fa-house-user',
    'kavling'      => 'fas fa-vector-square',
];
@endphp

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

/* ===== NAV ITEM — full-height hover zone ===== */
.rs-nav-li {
    position: relative;
    display: flex;
    align-items: center;
    align-self: stretch; /* fills full header row — no gap to mega panel */
    padding: 0 2px;
}

/* ===== OVAL PILL on the trigger <a> ===== */
.rs-mega-trigger {
    border-radius: 50px !important;
    border: 1.5px solid transparent !important;
    background: transparent !important;
    transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease !important;
}

/* Dark / transparent header */
.rs-nav-li:hover .rs-mega-trigger,
.rs-nav-li.rs-li-active .rs-mega-trigger {
    background: rgba(255,255,255,0.18) !important;
    border-color: rgba(255,255,255,0.55) !important;
}

/* Sticky (white) header */
html.sticky-header-active .rs-nav-li:hover .rs-mega-trigger,
html.sticky-header-active .rs-nav-li.rs-li-active .rs-mega-trigger {
    background: #eef3fb !important;
    border-color: #3065A3 !important;
    color: #3065A3 !important;
}

/* ===== MEGA PANEL ===== */
#rs-mega-panel {
    visibility: hidden;
    opacity: 0;
    pointer-events: none;
    position: fixed;
    left: 0; right: 0;
    z-index: 9998;
    background: #fff;
    box-shadow: 0 12px 40px rgba(0,0,0,0.14);
    border-top: 3px solid #3065A3;
    padding: 18px 0 14px;
    transition: opacity 0.15s ease, visibility 0.15s ease;
}
#rs-mega-panel.rs-mega-show { visibility: visible; opacity: 1; pointer-events: auto; }

/* Backdrop */
#rs-mega-backdrop {
    visibility: hidden; opacity: 0; pointer-events: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.3);
    z-index: 9997;
    transition: opacity 0.15s ease, visibility 0.15s ease;
}
#rs-mega-backdrop.rs-mega-show { visibility: visible; opacity: 1; pointer-events: auto; }

/* ===== CONDITION PILLS ===== */
.rs-cond-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
    flex-wrap: wrap;
}
.rs-cond-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #9ca3af;
    white-space: nowrap;
}
.rs-cond-pill {
    display: inline-flex;
    align-items: center;
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    border: 1.5px solid #e5e7eb;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
    background: transparent;
}
.rs-cond-pill:hover { border-color: #3065A3; color: #3065A3; }
.rs-cond-pill.rs-cond-active { background: #3065A3; border-color: #3065A3; color: #fff; }

/* ===== INNER COLUMNS ===== */
.rs-mega-inner { display: flex; gap: 0; min-height: 240px; max-height: 400px; }

.rs-mega-col-types     { width: 185px; flex-shrink: 0; padding-right: 16px; border-right: 1px solid #e5e7eb; overflow-y: auto; }
.rs-mega-col-provinces { width: 190px; flex-shrink: 0; padding: 0 16px; border-right: 1px solid #e5e7eb; overflow-y: auto; }
.rs-mega-col-kota      { width: 185px; flex-shrink: 0; padding: 0 16px; border-right: 1px solid #e5e7eb; overflow-y: auto; }
.rs-mega-col-areas     { flex: 1; padding: 0 16px; overflow-y: auto; }

.rs-mega-col-header {
    font-size: 11px; font-weight: 700;
    letter-spacing: 0.07em; text-transform: uppercase;
    color: #6b7280;
    margin-bottom: 8px; padding-bottom: 8px;
    border-bottom: 1px solid #f3f4f6;
}

/* Type items */
.rs-mega-type-item {
    display: flex; align-items: center;
    padding: 7px 10px; border-radius: 7px;
    color: #374151; text-decoration: none !important;
    font-size: 13.5px; cursor: pointer; white-space: nowrap;
    transition: background 0.13s, color 0.13s;
}
.rs-mega-type-item .mega-type-icon {
    width: 22px; font-size: 14px; margin-right: 9px;
    color: #3065A3; text-align: center; flex-shrink: 0;
}
.rs-mega-type-item:hover, .rs-mega-type-item.rs-active { background: #eef3fb; color: #1d4ed8; }
.rs-mega-type-item:hover .mega-type-icon, .rs-mega-type-item.rs-active .mega-type-icon { color: #1d4ed8; }

/* Province items */
.rs-mega-prov-item {
    display: flex; align-items: center;
    padding: 6px 8px; border-radius: 5px;
    color: #374151; text-decoration: none !important;
    font-size: 13.5px; cursor: pointer; white-space: nowrap;
    transition: background 0.13s, color 0.13s;
}
.rs-mega-prov-item .prov-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #d1d5db; flex-shrink: 0; margin-right: 9px;
    transition: background 0.13s;
}
.rs-mega-prov-item:hover, .rs-mega-prov-item.rs-active { background: #f0f5ff; color: #1d4ed8; font-weight: 600; }
.rs-mega-prov-item:hover .prov-dot, .rs-mega-prov-item.rs-active .prov-dot { background: #3065A3; }

/* Location items */
.rs-mega-loc-item {
    display: block; padding: 5px 2px;
    color: #374151; text-decoration: none !important;
    font-size: 13.5px; white-space: nowrap;
    transition: color 0.13s;
}
.rs-mega-loc-item:hover { color: #1d4ed8; text-decoration: underline !important; }

.rs-mega-placeholder { color: #9ca3af; font-size: 12.5px; font-style: italic; padding: 4px 2px; }

/* CTA footer */
.rs-mega-footer { margin-top: 12px; padding-top: 12px; border-top: 1px solid #f3f4f6; text-align: center; }
.rs-mega-cta { color: #3065A3; font-weight: 700; font-size: 14px; text-decoration: none !important; transition: color 0.13s; }
.rs-mega-cta:hover { color: #1d4ed8; text-decoration: underline !important; }

@media (max-width: 991px) { #rs-mega-panel, #rs-mega-backdrop { display: none !important; } }

.rs-mega-col-types::-webkit-scrollbar, .rs-mega-col-provinces::-webkit-scrollbar,
.rs-mega-col-kota::-webkit-scrollbar,  .rs-mega-col-areas::-webkit-scrollbar { width: 4px; }
.rs-mega-col-types::-webkit-scrollbar-thumb, .rs-mega-col-provinces::-webkit-scrollbar-thumb,
.rs-mega-col-kota::-webkit-scrollbar-thumb,  .rs-mega-col-areas::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 2px; }
</style>

{{-- ===== MEGA PANEL ===== --}}
<div id="rs-mega-backdrop"></div>
<div id="rs-mega-panel" role="dialog" aria-label="Navigasi Properti">
    <div class="container">

        {{-- Condition pills (first one pre-active) --}}
        <div class="rs-cond-row">
            <span class="rs-cond-label">Kondisi:</span>
            @foreach($navConditions as $i => $cond)
                <span class="rs-cond-pill {{ $i === 0 ? 'rs-cond-active' : '' }}"
                      data-cond-slug="{{ $cond['slug'] }}"
                      data-cond-name="{{ $cond['name'] }}">
                    {{ $cond['name'] }}
                </span>
            @endforeach
        </div>

        <div class="rs-mega-inner">

            {{-- Column 1: Tipe Properti --}}
            <div class="rs-mega-col-types">
                <div class="rs-mega-col-header">Tipe Properti</div>
                @foreach($navPropertyTypes as $i => $pt)
                    @php $icon = $typeIcons[strtolower($pt['name'])] ?? 'fas fa-home'; @endphp
                    <a href="#"
                       class="rs-mega-type-item {{ $i === 0 ? 'rs-active' : '' }}"
                       data-type-id="{{ $pt['id'] }}"
                       data-type-slug="{{ $pt['slug'] }}"
                       data-type-name="{{ $pt['name'] }}">
                        <i class="{{ $icon }} mega-type-icon"></i>
                        {{ $pt['name'] }}
                    </a>
                @endforeach
            </div>

            {{-- Column 2: Provinsi --}}
            <div class="rs-mega-col-provinces">
                <div class="rs-mega-col-header">Provinsi</div>
                @foreach($navProvinces as $prov)
                    <a href="#"
                       class="rs-mega-prov-item"
                       data-province-id="{{ $prov['id'] }}"
                       data-province-name="{{ $prov['name'] }}">
                        <span class="prov-dot"></span>
                        {{ $prov['name'] }}
                    </a>
                @endforeach
            </div>

            {{-- Column 3: Kota --}}
            <div class="rs-mega-col-kota">
                <div class="rs-mega-col-header">Kota</div>
                <div id="rs-kota-list">
                    <span class="rs-mega-placeholder">Arahkan ke Provinsi</span>
                </div>
            </div>

            {{-- Column 4: Area Pilihan --}}
            <div class="rs-mega-col-areas">
                <div class="rs-mega-col-header">Area Pilihan</div>
                <div id="rs-area-list">
                    <span class="rs-mega-placeholder">Arahkan ke Provinsi</span>
                </div>
            </div>

        </div>

        <div class="rs-mega-footer">
            <a href="#" id="rs-mega-cta" class="rs-mega-cta">
                Lihat Semua Properti <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>

{{-- ===== HEADER ===== --}}
<header id="header" class="header-transparent header-effect-shrink"
    data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': true, 'stickyStartAt': 30, 'stickyHeaderContainerHeight': 70}">

    <div class="header-body border-top-0 bg-transparent box-shadow-none">
        <div class="header-container container">
            <div class="header-row">

                <div class="header-column">
                    <div class="header-row">
                        <div class="header-logo">
                            <a href="{{ url('/') }}" class="text-decoration-none">
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
                                               href="{{ url('/') }}">Home</a>
                                        </li>

                                        {{-- Tipe Properti --}}
                                        <li class="rs-nav-li">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold rs-mega-trigger"
                                               href="{{ url('/all-products') }}"
                                               style="padding: 10px 18px !important; margin: 0 3px; pointer-events:none;">
                                               Tipe Properti
                                            </a>
                                        </li>

                                        {{-- Proyek --}}
                                        <li class="rs-nav-li">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold rs-mega-trigger"
                                               href="{{ url('/all-products') }}"
                                               style="padding: 10px 18px !important; margin: 0 3px; pointer-events:none;">
                                               Proyek
                                            </a>
                                        </li>

                                        {{-- Lokasi --}}
                                        <li class="rs-nav-li">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold rs-mega-trigger"
                                               href="{{ url('/all-products') }}"
                                               style="padding: 10px 18px !important; margin: 0 14px 0 3px; pointer-events:none;">
                                               Lokasi
                                            </a>
                                        </li>

                                    </ul>
                                </nav>
                            </div>
                            <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>

                        <div class="header-nav-features header-nav-features-light header-nav-features-no-border order-1 order-lg-2 ms-4 d-flex align-items-center">
                            <div class="header-nav-feature header-nav-features-search d-inline-flex">
                                <a href="#" class="header-nav-features-toggle text-decoration-none d-flex align-items-center" data-focus="headerSearch">
                                    <i class="fas fa-search header-nav-top-icon custom-search-icon" style="font-size: 16px; margin-right: 8px;"></i>
                                    <span class="font-weight-bold custom-search-text poppins-semibold" style="font-size: 15px;">Search</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</header>

{{-- ===== MEGA MENU SCRIPT ===== --}}
<script>
(function () {
    if (window.innerWidth < 992) return;

    var panel    = document.getElementById('rs-mega-panel');
    var backdrop = document.getElementById('rs-mega-backdrop');
    var navLis   = document.querySelectorAll('.rs-nav-li');
    var kotaList = document.getElementById('rs-kota-list');
    var areaList = document.getElementById('rs-area-list');
    var ctaLink  = document.getElementById('rs-mega-cta');
    var timer    = null;
    var visible  = false;

    // Server-side data passed to JS
    var kotaData = @json($navKotaByProvince);   // { provinsiId: [{id, name, slug}] }
    var areaData = @json($navAreasByProvince);   // { provinsiId: [{id, name, slug}] }
    var condData = @json($navConditions);        // [{id, name, slug}]
    var typeData = @json($navPropertyTypes);     // [{id, name, slug}]

    // Defaults: first condition slug, first type slug (user said default = first item)
    var activeCondSlug = condData.length ? condData[0].slug : 'properti-baru';
    var activeCondName = condData.length ? condData[0].name : 'Properti Baru';
    var activeTypeSlug = typeData.length ? typeData[0].slug : 'rumah';
    var activeTypeName = typeData.length ? typeData[0].name : 'Rumah';
    var activeProvId   = null;
    var activeProvName = '';

    // ── URL helpers ─────────────────────────────────────────────────────────

    function buildKotaUrl(kotaSlug) {
        return '/' + activeCondSlug + '/' + activeTypeSlug + '/' + kotaSlug + '/';
    }

    function buildAreaUrl(areaSlug) {
        // Area maps to province-level; use area slug in kota position (controller handles fallback)
        return '/' + activeCondSlug + '/' + activeTypeSlug + '/' + areaSlug + '/';
    }

    function buildCtaUrl() {
        if (activeProvId) return '/' + activeCondSlug + '/' + activeTypeSlug + '?location=' + activeProvId;
        return '/' + activeCondSlug + '/' + activeTypeSlug;
    }

    // ── Panel position / show / hide ─────────────────────────────────────────

    function positionPanel() {
        var hdr = document.getElementById('header');
        if (hdr) panel.style.top = (hdr.getBoundingClientRect().bottom - 2) + 'px';
    }

    function showPanel(li) {
        clearTimeout(timer);
        navLis.forEach(function (el) { el.classList.remove('rs-li-active'); });
        if (li) li.classList.add('rs-li-active');
        if (!visible) {
            positionPanel();
            panel.classList.add('rs-mega-show');
            backdrop.classList.add('rs-mega-show');
            visible = true;
        }
    }

    function hidePanel(immediate) {
        function doHide() {
            panel.classList.remove('rs-mega-show');
            backdrop.classList.remove('rs-mega-show');
            navLis.forEach(function (el) { el.classList.remove('rs-li-active'); });
            visible = false;
        }
        if (immediate) { clearTimeout(timer); doHide(); }
        else            { timer = setTimeout(doHide, 300); }
    }

    // ── Nav <li> hover (full-height zone) ────────────────────────────────────

    navLis.forEach(function (li) {
        li.addEventListener('mouseenter', function () { showPanel(li); });
        li.addEventListener('mouseleave', function () { hidePanel(false); });
    });

    panel.addEventListener('mouseenter', function () { clearTimeout(timer); });
    panel.addEventListener('mouseleave', function () { hidePanel(false); });
    backdrop.addEventListener('click',   function () { hidePanel(true); });
    window.addEventListener('resize',    function () { if (visible) positionPanel(); });

    // ── Condition pills ──────────────────────────────────────────────────────

    document.querySelectorAll('.rs-cond-pill').forEach(function (pill) {
        pill.addEventListener('click', function () {
            document.querySelectorAll('.rs-cond-pill').forEach(function (p) { p.classList.remove('rs-cond-active'); });
            pill.classList.add('rs-cond-active');
            activeCondSlug = pill.dataset.condSlug;
            activeCondName = pill.dataset.condName;
            rerenderKotaAndArea();
            renderCta();
        });
    });

    // ── Type hover ───────────────────────────────────────────────────────────

    document.querySelectorAll('.rs-mega-type-item').forEach(function (item) {
        item.addEventListener('click', function (e) { e.preventDefault(); window.location.href = buildCtaUrl(); });
        item.addEventListener('mouseenter', function () {
            document.querySelectorAll('.rs-mega-type-item').forEach(function (i) { i.classList.remove('rs-active'); });
            item.classList.add('rs-active');
            activeTypeSlug = item.dataset.typeSlug;
            activeTypeName = item.dataset.typeName;
            rerenderKotaAndArea();
            renderCta();
        });
    });

    // ── Province hover ───────────────────────────────────────────────────────

    document.querySelectorAll('.rs-mega-prov-item').forEach(function (item) {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = '/' + activeCondSlug + '/' + activeTypeSlug + '?location=' + item.dataset.provinceId;
        });
        item.addEventListener('mouseenter', function () {
            document.querySelectorAll('.rs-mega-prov-item').forEach(function (i) { i.classList.remove('rs-active'); });
            item.classList.add('rs-active');
            activeProvId   = item.dataset.provinceId;
            activeProvName = item.dataset.provinceName;
            renderKota(activeProvId);
            renderArea(activeProvId);
            renderCta();
        });
    });

    // ── Kota column ──────────────────────────────────────────────────────────

    function renderKota(provinceId) {
        var items = provinceId ? (kotaData[provinceId] || []) : [];
        if (!items.length) {
            kotaList.innerHTML = '<span class="rs-mega-placeholder">Tidak ada kota</span>';
            return;
        }
        kotaList.innerHTML = items.map(function (k) {
            return '<a href="' + buildKotaUrl(k.slug) + '" class="rs-mega-loc-item">' + k.name + '</a>';
        }).join('');
    }

    // ── Area column ──────────────────────────────────────────────────────────

    function renderArea(provinceId) {
        var items = provinceId ? (areaData[provinceId] || []) : [];
        if (!items.length) {
            areaList.innerHTML = '<span class="rs-mega-placeholder">Tidak ada area</span>';
            return;
        }
        areaList.innerHTML = items.map(function (a) {
            return '<a href="' + buildAreaUrl(a.slug) + '" class="rs-mega-loc-item">' + a.name + '</a>';
        }).join('');
    }

    function rerenderKotaAndArea() {
        if (activeProvId) { renderKota(activeProvId); renderArea(activeProvId); }
    }

    // ── CTA footer ───────────────────────────────────────────────────────────

    function renderCta() {
        var label = 'Semua ' + activeTypeName;
        if (activeProvName) label += ' di ' + activeProvName;
        ctaLink.href      = buildCtaUrl();
        ctaLink.innerHTML = label + ' <i class="fas fa-arrow-right ms-1"></i>';
    }

    // Initialise CTA
    renderCta();

})();
</script>
