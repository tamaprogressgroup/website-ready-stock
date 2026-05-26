<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Paradise Ready Stock</title>

    <!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

	<!-- Web Fonts  -->
	<link id="googleFonts"
  href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100;300;400;500;700&family=Lato:wght@100;300;400;700;900&family=Open+Sans:wght@300;400;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=Rubik:wght@300;400;500;700;900&family=Shadows+Into+Light&display=swap"
  rel="stylesheet" type="text/css">







	<!-- Theme CSS -->
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/theme.css') }}">
	<link rel="stylesheet" href="{{ asset('css/theme-elements.css') }}">
	<link rel="stylesheet" href="{{ asset('css/theme-blog.css') }}">
	{{-- <link rel="stylesheet" href="{{ asset('css/theme-shop.css') }}"> --}}



	<!-- Current Page CSS -->
	<link rel="stylesheet" href="{{ asset('vendor/circle-flip-slideshow/css/component.css') }}">

	<!-- Skin CSS -->
	<link id="skinCSS" rel="stylesheet" href="{{ asset('css/skins/default.css') }}">

	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Poppins', sans-serif;
        }

        /* Fix: theme global svg sizing inflating pagination arrows */
        .pagination .page-link svg { width: 0.6rem; height: 0.6rem; }

        /* Sidebar Wrapper */
        #backend-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #backend-sidebar {
            width: 260px;
            min-width: 260px;
            background-color: #f8fbfd;
            border-right: 1px solid #e5e7eb;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        /* Main Content Styling */
        #backend-content {
            flex-grow: 1;
            flex-shrink: 1;
            padding: 30px;
            width: calc(100% - 260px);
            max-width: calc(100% - 260px);
            min-width: 0;
        }

        /* Sidebar Menu Links */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            margin: 4px 15px;
            transition: all 0.2s ease-in-out;
        }

        .sidebar-menu li a .menu-icon {
            width: 24px;
            font-size: 16px;
            margin-right: 12px;
            text-align: center;
        }

        .sidebar-menu li a:hover {
            background-color: #e5edf5;
            color: #2563eb;
        }

        .sidebar-menu li a.active {
            background-color: #eaf1fb;
            color: #1d4ed8;
            font-weight: 600;
        }

        /* Submenu Styling */
        .sidebar-submenu {
            list-style: none;
            padding-left: 45px;
            margin: 0;
        }

        .sidebar-submenu li a {
            padding: 8px 10px;
            margin: 2px 0;
            font-size: 13px;
            font-weight: 400;
        }

        .sidebar-submenu li a.active {
            background-color: transparent;
            color: #1d4ed8;
            font-weight: 600;
        }

        /* Red Dot Notification */
        .red-dot {
            height: 8px;
            width: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            display: inline-block;
        }

        /* ===== RESPONSIVE ===== */
        .sidebar-toggle-btn {
            display: none;
            background: none;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            padding: 6px 10px;
            cursor: pointer;
            color: #374151;
            font-size: 18px;
            line-height: 1;
            margin-right: 12px;
            transition: all 0.15s;
        }
        .sidebar-toggle-btn:hover { background: #f3f4f6; border-color: #9ca3af; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 999;
            cursor: pointer;
        }
        .sidebar-overlay.show { display: block; }

        @media (max-width: 991px) {
            .sidebar-toggle-btn { display: inline-flex; align-items: center; justify-content: center; }

            #backend-sidebar {
                position: fixed !important;
                left: -280px !important;
                top: 0 !important;
                height: 100vh !important;
                z-index: 1000 !important;
                width: 260px !important;
                min-width: 260px !important;
                transition: left 0.28s ease;
                box-shadow: 4px 0 24px rgba(0,0,0,0.12);
            }
            #backend-sidebar.open {
                left: 0 !important;
            }

            #backend-content {
                width: 100% !important;
                max-width: 100% !important;
                padding: 20px 14px;
            }
        }

        @media (max-width: 575px) {
            #backend-content { padding: 14px 8px; }
        }

        /* ===== STEP WIZARD: horizontal strip on mobile ===== */
        @media (max-width: 991px) {
            .sidebar-widget { padding: 8px 0 !important; }
            .sidebar-title { font-size: 12px !important; padding: 6px 16px 6px !important; }
            .step-list { display: flex !important; overflow-x: auto; scrollbar-width: none; }
            .step-list::-webkit-scrollbar { display: none; }
            .step-list li { flex-shrink: 0; padding: 10px 14px !important; font-size: 12px !important; white-space: nowrap; }
            .step-list li.active::after { right: auto !important; bottom: 0; top: auto !important; height: 3px !important; width: 100% !important; left: 0; }
            .step-number { width: 20px !important; height: 20px !important; margin-right: 8px !important; font-size: 11px !important; }
        }

        /* ===== TABLE responsive tweaks ===== */
        @media (max-width: 767px) {
            .table th, .table td { white-space: nowrap; }
            .card-property { padding: 14px !important; }
            .property-img { height: 160px !important; }
            .property-img-placeholder { height: 160px !important; }
        }
    </style>
</head>
<body>

    @include('back.layout.navbar')

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div id="backend-wrapper">
        @include('back.layout.sidebar')

        <main id="backend-content">
            {{-- Mobile toggle inside content for easy access --}}
            <div class="d-flex align-items-center mb-3 d-lg-none">
                <button class="sidebar-toggle-btn me-2" id="sidebar-toggle-btn-2" aria-label="Menu" style="display:inline-flex !important;">
                    <i class="fas fa-bars"></i>
                </button>
                <span style="font-size:13px; color:#6b7280;">Menu</span>
            </div>
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('vendor/plugins/js/plugins.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/theme.init.js') }}"></script>

    <script>
    (function() {
        const sidebar  = document.getElementById('backend-sidebar');
        const overlay  = document.getElementById('sidebar-overlay');
        const btns     = [
            document.getElementById('sidebar-toggle-btn-2'),
            document.getElementById('sidebar-navbar-btn'),
        ].filter(Boolean);

        function openSidebar()  { sidebar.classList.add('open');  overlay.classList.add('show');  document.body.style.overflow = 'hidden'; }
        function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('show'); document.body.style.overflow = ''; }

        btns.forEach(btn => btn.addEventListener('click', openSidebar));
        overlay.addEventListener('click', closeSidebar);

        // Close when a link inside sidebar is clicked (navigation)
        sidebar.querySelectorAll('a').forEach(a => a.addEventListener('click', function() {
            if (window.innerWidth < 992) closeSidebar();
        }));
    })();
    </script>

</body>
</html>