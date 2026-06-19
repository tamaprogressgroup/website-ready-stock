<aside id="backend-sidebar" class="bg-white shadow-sm border-end">
    <div class="p-4 text-center border-bottom">
        <h5 class="mb-0 font-weight-bold" style="color: #3065A3; letter-spacing: 0.5px;">Progress Group</h5>
    </div>

    <div class="p-3">
        <p class="text-muted text-uppercase font-weight-bold mb-2 px-3" style="font-size: 11px; letter-spacing: 1px;">Menu Utama</p>
        <ul class="sidebar-menu list-unstyled m-0">
            <li class="mb-1">
                <a href="{{ route('customer.property') }}" class="text-decoration-none d-flex align-items-center p-3 rounded-lg {{ request()->routeIs('customer.property*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" style="transition: all 0.3s ease; border-radius: 8px;">
                    <i class="fas fa-home me-3 {{ request()->routeIs('customer.property*') ? 'text-white' : 'text-primary' }}" style="font-size: 1.1rem; width: 20px; text-align: center;"></i>
                    <span class="font-weight-semibold" style="font-size: 14px;">Property Saya</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('customer.leads') }}" class="text-decoration-none d-flex align-items-center p-3 rounded-lg {{ request()->routeIs('customer.leads') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" style="transition: all 0.3s ease; border-radius: 8px;">
                    <i class="fas fa-users me-3 {{ request()->routeIs('customer.leads') ? 'text-white' : 'text-primary' }}" style="font-size: 1.1rem; width: 20px; text-align: center;"></i>
                    <span class="font-weight-semibold" style="font-size: 14px;">Sales Inquiry</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Master Data -->
    <div class="p-3 border-top">
        <p class="text-muted text-uppercase font-weight-bold mb-2 px-3" style="font-size: 11px; letter-spacing: 1px;">Master Data</p>
        <ul class="sidebar-menu list-unstyled m-0">
            <li class="mb-1">
                <a href="{{ route('master.property-type.index') }}" class="text-decoration-none d-flex align-items-center p-3 rounded-lg {{ request()->routeIs('master.property-type.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" style="transition: all 0.3s ease; border-radius: 8px;">
                    <i class="fas fa-tags me-3 {{ request()->routeIs('master.property-type.*') ? 'text-white' : 'text-primary' }}" style="font-size: 1rem; width: 20px; text-align: center;"></i>
                    <span class="font-weight-semibold" style="font-size: 14px;">Tipe Properti</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('master.property-condition.index') }}" class="text-decoration-none d-flex align-items-center p-3 rounded-lg {{ request()->routeIs('master.property-condition.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" style="transition: all 0.3s ease; border-radius: 8px;">
                    <i class="fas fa-clipboard-check me-3 {{ request()->routeIs('master.property-condition.*') ? 'text-white' : 'text-primary' }}" style="font-size: 1rem; width: 20px; text-align: center;"></i>
                    <span class="font-weight-semibold" style="font-size: 14px;">Kondisi Properti</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('master.cluster.index') }}" class="text-decoration-none d-flex align-items-center p-3 rounded-lg {{ request()->routeIs('master.cluster.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" style="transition: all 0.3s ease; border-radius: 8px;">
                    <i class="fas fa-layer-group me-3 {{ request()->routeIs('master.cluster.*') ? 'text-white' : 'text-primary' }}" style="font-size: 1rem; width: 20px; text-align: center;"></i>
                    <span class="font-weight-semibold" style="font-size: 14px;">Cluster</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('master.township.index') }}" class="text-decoration-none d-flex align-items-center p-3 rounded-lg {{ request()->routeIs('master.township.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" style="transition: all 0.3s ease; border-radius: 8px;">
                    <i class="fas fa-map-marked-alt me-3 {{ request()->routeIs('master.township.*') ? 'text-white' : 'text-primary' }}" style="font-size: 1rem; width: 20px; text-align: center;"></i>
                    <span class="font-weight-semibold" style="font-size: 14px;">Proyek</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('master.banner.index') }}" class="text-decoration-none d-flex align-items-center p-3 rounded-lg {{ request()->routeIs('master.banner.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" style="transition: all 0.3s ease; border-radius: 8px;">
                    <i class="fas fa-image me-3 {{ request()->routeIs('master.banner.*') ? 'text-white' : 'text-primary' }}" style="font-size: 1rem; width: 20px; text-align: center;"></i>
                    <span class="font-weight-semibold" style="font-size: 14px;">Banner</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Pengaturan -->
    <div class="p-3 border-top">
        <p class="text-muted text-uppercase font-weight-bold mb-2 px-3" style="font-size: 11px; letter-spacing: 1px;">Pengaturan</p>
        <ul class="sidebar-menu list-unstyled m-0">
            <li class="mb-1">
                <a href="{{ route('admin-user.index') }}" class="text-decoration-none d-flex align-items-center p-3 rounded-lg {{ request()->routeIs('admin-user.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" style="transition: all 0.3s ease; border-radius: 8px;">
                    <i class="fas fa-user-cog me-3 {{ request()->routeIs('admin-user.*') ? 'text-white' : 'text-primary' }}" style="font-size: 1rem; width: 20px; text-align: center;"></i>
                    <span class="font-weight-semibold" style="font-size: 14px;">Manajemen User</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('back.seo-pages.index') }}" class="text-decoration-none d-flex align-items-center p-3 rounded-lg {{ request()->routeIs('back.seo-pages.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" style="transition: all 0.3s ease; border-radius: 8px;">
                    <i class="fas fa-search me-3 {{ request()->routeIs('back.seo-pages.*') ? 'text-white' : 'text-primary' }}" style="font-size: 1rem; width: 20px; text-align: center;"></i>
                    <span class="font-weight-semibold" style="font-size: 14px;">SEO Halaman</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Logout -->
    <div class="p-3 border-top mt-auto">
        <form action="{{ route('back.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn w-100 d-flex align-items-center p-3 text-secondary hover-bg-light" style="background:none; border:none; border-radius: 8px; transition: all 0.3s ease; cursor:pointer;">
                <i class="fas fa-sign-out-alt me-3 text-danger" style="font-size: 1rem; width: 20px; text-align: center;"></i>
                <span class="font-weight-semibold" style="font-size: 14px;">Keluar</span>
            </button>
        </form>
    </div>

    <style>
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
            color: #3065A3 !important;
            transform: translateX(4px);
        }
        .hover-bg-light:hover i {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }
        .sidebar-menu li a.bg-primary {
            pointer-events: none;
        }
    </style>
</aside>