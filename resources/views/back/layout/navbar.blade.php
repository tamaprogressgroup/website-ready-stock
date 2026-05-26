<style>
/* Style Khusus Tombol Pasang Iklan */
.btn-pasang-iklan-back {
    background-color: #3065A3;
    color: #ffffff !important;
    border-radius: 8px;
    padding: 8px 20px !important;
    font-weight: 600;
    transition: all 0.3s ease;
}

.custom-nav-link-back, .custom-search-text-back, .custom-search-icon-back, .custom-action-icon-back {
    color: #333333 !important; 
}

/* Fix porto header overlap with our flex layout */
html #header.header-back {
    position: sticky;
    top: 0;
    z-index: 1001;
}
</style>

<header id="header" class="header-back header-effect-shrink" data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': false, 'stickyStartAt': 30, 'stickyHeaderContainerHeight': 70}">
    <div class="header-body border-bottom bg-white shadow-sm" style="position: relative;">
        <div class="header-container container-fluid px-4">
            <div class="header-row">
                
                <div class="header-column">
                    <div class="header-row align-items-center">
                        <button class="btn d-lg-none me-2 p-2" id="sidebar-navbar-btn" aria-label="Menu"
                                style="background:none; border:none; font-size:20px; color:#374151; line-height:1; flex-shrink:0;">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="header-logo">
                            <a href="/" class="text-decoration-none">
                                <h2 class="font-weight-bold text-6 mb-0 d-flex align-items-center">
                                    <img src="{{ asset('stock-image/progress-logo-colored.png') }}" alt="Progress Group" width="160px">
                                </h2>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="header-column justify-content-end">
                    <div class="header-row align-items-center">
                        <div class="header-nav header-nav-links order-2 order-lg-1">
                            <div class="header-nav-main header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-effect-2 header-nav-main-sub-effect-1">
                                <nav class="collapse">
                                    <ul class="nav nav-pills align-items-center" id="mainNav">
                                        
                                    </ul>
                                </nav>
                            </div>
                            <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                        
                        <div class="header-nav-features header-nav-features-no-border order-1 order-lg-2 ms-4 d-flex align-items-center">
                            
                          
                           

    
                          

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
