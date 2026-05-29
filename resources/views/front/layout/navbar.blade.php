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
</style>

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

                                        {{-- Rekomendasi Properti --}}
                                        <li style="display:flex;align-items:center;">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold"
                                               href="{{ url('/') }}#rekomendasi-properti"
                                               style="padding: 10px 18px !important; margin: 0 3px;">
                                               Rekomendasi Properti
                                            </a>
                                        </li>

                                        {{-- Properti Baru --}}
                                        <li style="display:flex;align-items:center;">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold"
                                               href="{{ url('/') }}#properti-baru"
                                               style="padding: 10px 18px !important; margin: 0 3px;">
                                               Properti Baru
                                            </a>
                                        </li>

                                        {{-- Project --}}
                                        <li style="display:flex;align-items:center;">
                                            <a class="nav-link font-weight-semibold custom-nav-link poppins-semibold"
                                               href="{{ url('/') }}#properti-by-project"
                                               style="padding: 10px 18px !important; margin: 0 14px 0 3px;">
                                               Project
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
