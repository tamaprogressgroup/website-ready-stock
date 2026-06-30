<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
	<title>{{ $pageSeo?->meta_title ?? 'Semua Properti - Paradise Ready Stock' }}</title>
	<meta name="keywords" content="{{ $pageSeo?->meta_keyword ?? '' }}">
	<meta name="description" content="{{ $pageSeo?->meta_description ?? '' }}">
	<meta name="author" content="paradise.co.id">
	<meta property="og:title" content="{{ $pageSeo?->og_title ?? $pageSeo?->meta_title ?? '' }}">
	<meta property="og:description" content="{{ $pageSeo?->og_description ?? $pageSeo?->meta_description ?? '' }}">
	<meta property="og:type" content="website">

	<link id="googleFonts" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/theme.css') }}">
	<link rel="stylesheet" href="{{ asset('css/theme-elements.css') }}">
	<link rel="stylesheet" href="{{ asset('css/theme-blog.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/circle-flip-slideshow/css/component.css') }}">
	<link id="skinCSS" rel="stylesheet" href="{{ asset('css/skins/default.css') }}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        /* ===== FILTER / SORT MODALS ===== */
        .filter-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 9998;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .filter-modal-overlay.show { display: flex; }
        .filter-panel {
            background: #fff;
            width: 100%;
            max-width: 520px;
            border-radius: 18px;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.18);
            animation: popIn 0.22s ease;
        }
        @keyframes popIn { from { transform: scale(0.95); opacity:0; } to { transform: scale(1); opacity:1; } }
        .filter-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px 14px;
            border-bottom: 1px solid #f0f0f0;
            flex-shrink: 0;
        }
        .filter-panel-header h5 { font-size: 16px; font-weight: 700; margin: 0; }
        .filter-close-btn { background: none; border: none; font-size: 20px; color: #666; cursor: pointer; line-height: 1; padding: 0; }
        .filter-panel-content { flex: 1; overflow-y: auto; }
        .filter-section { padding: 16px 20px 0; }
        .filter-section-title { font-size: 13px; font-weight: 700; color: #1a1a1a; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.4px; }
        .filter-footer {
            flex-shrink: 0;
            background: #fff;
            border-top: 1px solid #eee;
            padding: 14px 20px;
            display: flex;
            gap: 12px;
        }
        /* Range input pair */
        .range-pair { display: flex; align-items: center; gap: 8px; }
        .range-pair .form-control { font-size: 13px; }
        .range-sep { color: #aaa; font-size: 13px; font-weight: 600; flex-shrink: 0; }
        .range-unit { font-size: 12px; color: #777; flex-shrink: 0; }

        /* Active filter chips */
        .active-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eef2ff;
            color: #3b5998;
            border: 1px solid #c7d2fe;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }
        .active-filter-chip:hover { background: #e0e7ff; }
        .active-filter-chip .chip-remove { font-size: 11px; opacity: 0.7; }

        /* Pill chips for filter options */
        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1.5px solid #dee2e6;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            background: #fff;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .filter-chip:hover { border-color: #3b5998; color: #3b5998; }
        .filter-chip.selected { background: #3b5998; color: #fff; border-color: #3b5998; }
        .filter-chip.selected i { color: #fff; }

        /* Sort modal */
        .sort-panel {
            background: #fff;
            width: 100%;
            max-width: 340px;
            border-radius: 18px;
            overflow: hidden;
            padding-bottom: 8px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.18);
            animation: popIn 0.22s ease;
        }
        .sort-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #222;
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.1s;
        }
        .sort-option:last-child { border-bottom: none; }
        .sort-option:hover { background: #f8f9fa; }
        .sort-option input[type="radio"] { display: none; }
        .sort-radio-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #ccc;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sort-option.active .sort-radio-dot {
            border-color: #3b5998;
            background: #3b5998;
        }
        .sort-option.active .sort-radio-dot::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fff;
        }

        /* Action bar buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            transition: all 0.15s;
        }
        .action-btn:hover { border-color: #3b5998; color: #3b5998; }
        .action-btn.has-filter { border-color: #3b5998; color: #3b5998; background: #eef2ff; }

        /* Tag autocomplete multi-select */
        .tag-input-wrap {
            border: 1px solid #dee2e6; border-radius: 8px; padding: 6px 8px;
            display: flex; flex-wrap: wrap; gap: 6px; align-items: center;
            background: #fff; cursor: text; min-height: 42px;
        }
        .tag-input-wrap:focus-within { border-color: #3b5998; box-shadow: 0 0 0 2px rgba(59,89,152,0.1); }
        .tag-chip {
            display: inline-flex; align-items: center; gap: 4px;
            background: #3b5998; color: #fff; border-radius: 20px;
            padding: 2px 10px 2px 10px; font-size: 12px; font-weight: 600; white-space: nowrap;
        }
        .tag-chip .rm { cursor: pointer; opacity: 0.8; font-size: 11px; margin-left: 2px; }
        .tag-chip .rm:hover { opacity: 1; }
        .tag-text-input { border: none; outline: none; font-size: 13px; flex: 1; min-width: 100px; background: transparent; }
        .tag-dropdown {
            position: absolute; z-index: 9999; background: #fff;
            border: 1px solid #dee2e6; border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-height: 200px; overflow-y: auto;
            width: 100%;
        }
        .tag-dropdown-item {
            padding: 9px 14px; font-size: 13px; cursor: pointer; transition: background 0.12s;
        }
        .tag-dropdown-item:hover, .tag-dropdown-item.focused { background: #eef2ff; color: #3b5998; }

        /* ===== MAP OVERLAY ===== */
        .map-overlay { position: fixed; inset: 0; z-index: 9999; display: none; flex-direction: column; background: #e8e8e8; }
        .map-overlay.show { display: flex; }
        .map-top-bar { background: #fff; border-bottom: 1px solid #ddd; padding: 10px 18px; display: flex; align-items: center; gap: 14px; flex-shrink: 0; }
        .map-back-btn { display: inline-flex; align-items: center; gap: 7px; background: none; border: 1.5px solid #3b5998; color: #3b5998; border-radius: 8px; padding: 6px 14px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s; }
        .map-back-btn:hover { background: #3b5998; color: #fff; }
        .map-count { font-size: 13px; color: #666; }
        .map-no-results { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); background: #fff; padding: 24px 32px; border-radius: 14px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.12); }
        #leaflet-map { flex: 1; }
        .leaflet-popup-content { margin: 12px 14px; }
        .map-popup-img { width: 100%; height: 110px; object-fit: cover; border-radius: 8px; display: block; margin-bottom: 8px; }
        .map-popup-price { font-size: 14px; font-weight: 700; color: #3b5998; margin-bottom: 3px; }
        .map-popup-title { font-size: 12px; font-weight: 600; color: #222; margin-bottom: 8px; line-height: 1.4; }
        .map-popup-btn { display: block; background: #3b5998; color: #fff; text-align: center; padding: 7px; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; }
        .map-popup-btn:hover { background: #2d4a7a; color: #fff; }
    </style>
    @include('partials.google_tag')
	@include('partials.facebook_pixel')
</head>

<body data-plugin-page-transition>
     @include('partials.google_tag_iframe')
<div class="body">

    @if(request('embed') === '1')
        @include('front.partials.embed-navbar')
    @else
        @include('front.layout.navbar')
    @endif
    <style>
        .header-logo-light { display: none !important; }
        .header-logo-dark  { display: block !important; }
        .nav-btn-home { background-color: transparent !important; color: #3065A3 !important; padding: 10px 18px !important; }
        .custom-nav-link,
        .custom-search-text,
        .custom-search-icon,
        .custom-action-icon { color: #333333 !important; }
        #header .header-body { background-color: #ffffff !important; box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important; }
    </style>

    @php
        $embedSuffix = request('embed') === '1'
            ? '?embed=1' . (request('key') ? '&key=' . rawurlencode(request('key')) : '')
            : '';
    @endphp
    <div role="main" class="main" style="padding-top: {{ request('embed') === '1' ? '0' : '100px' }};">
    <div class="container py-4 mt-3">

        @php
            $hasSlugFilter  = !empty($urlSlugs['condition']) || !empty($urlSlugs['type'])
                           || !empty($urlSlugs['kota'])      || !empty($urlSlugs['township']);
            $hasQueryFilter = request()->hasAny(['price_min','price_max','lt_min','lt_max','lb_min','lb_max','tags','twp','q']);
            $hasFilter      = $hasQueryFilter || $hasSlugFilter;
            $sortLabels = [
                'newest'     => 'Terbaru',
                'price_asc'  => 'Harga Terendah',
                'price_desc' => 'Harga Tertinggi',
                'lt_desc'    => 'Luas Tanah Terluas',
                'lb_desc'    => 'Luas Bangunan Terluas',
            ];
            $activeSortLabel = $sortLabels[$sort] ?? 'Terbaru';
        @endphp

        {{-- Top Search & Action Row --}}
        <div class="row align-items-center mb-3">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <form action="{{ url($browseBase) }}" method="get" class="d-flex w-100" id="search-form">
                    @foreach(request()->only(['sort','price_min','price_max','lt_min','lt_max','lb_min','lb_max','tags','twp']) as $k => $v)
                        @if($v !== '' && $v !== null)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach
                    <input type="text" class="form-control text-3 h-auto py-2" placeholder="Cari properti..." name="q"
                        style="border-radius: 8px 0 0 8px; border: 1px solid #ccc;"
                        value="{{ request('q') }}">
                    <button class="btn btn-primary px-4 font-weight-bold" type="submit"
                        style="border-radius: 0 8px 8px 0; background-color: #3b5998; border-color: #3b5998;">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </form>
            </div>
            <div class="col-lg-6 d-flex justify-content-lg-end justify-content-start gap-2 flex-wrap">
                <button class="action-btn {{ $sort !== 'newest' ? 'has-filter' : '' }}" id="btn-open-sort">
                    <i class="fas fa-sort-amount-down"></i> Urutkan
                    @if($sort !== 'newest')
                        <span class="badge rounded-pill ms-1" style="background:#3b5998; color:#fff; font-size:10px;">{{ $activeSortLabel }}</span>
                    @endif
                </button>
                <button class="action-btn {{ $hasFilter ? 'has-filter' : '' }}" id="btn-open-filter">
                    <i class="fas fa-sliders-h"></i> Filter
                    @if($hasFilter)
                        <span class="badge rounded-pill ms-1" style="background:#3b5998; color:#fff; font-size:10px;">Aktif</span>
                    @endif
                </button>
                <button class="action-btn {{ count($mapMarkers) > 0 ? 'has-filter' : '' }}" id="btn-map-view">
                    <i class="fas fa-map-marked-alt"></i> Map View
                    @if(count($mapMarkers) > 0)
                        <span class="badge rounded-pill ms-1" style="background:#3b5998; color:#fff; font-size:10px;">{{ count($mapMarkers) }}</span>
                    @endif
                </button>
            </div>
        </div>

        {{-- Active Filter Chips — slug-based (URL path) + query-param extras --}}
        @if($hasSlugFilter || $hasQueryFilter || $sort !== 'newest')
        <div class="d-flex flex-wrap gap-2 mb-3">
            {{-- Sort --}}
            @if($sort !== 'newest')
                <span class="active-filter-chip" onclick="clearParam('sort')">
                    <i class="fas fa-sort-amount-down" style="font-size:11px;"></i> {{ $activeSortLabel }}
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            {{-- URL-path slug chips (hierarchical — removing one also removes deeper levels) --}}
            @if(!empty($urlSlugs['condition']))
                <span class="active-filter-chip" onclick="clearSlug('condition')" title="Hapus filter kondisi">
                    <i class="fas fa-tag" style="font-size:11px;"></i> {{ $slugNames['condition'] ?? $urlSlugs['condition'] }}
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            @if(!empty($urlSlugs['type']))
                <span class="active-filter-chip" onclick="clearSlug('type')" title="Hapus filter tipe">
                    <i class="fas fa-home" style="font-size:11px;"></i> {{ $slugNames['type'] ?? $urlSlugs['type'] }}
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            @if(!empty($urlSlugs['kota']))
                <span class="active-filter-chip" onclick="clearSlug('kota')" title="Hapus filter kota">
                    <i class="fas fa-map-marker-alt" style="font-size:11px;"></i> {{ $slugNames['kota'] ?? $urlSlugs['kota'] }}
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            @if(!empty($urlSlugs['township']))
                <span class="active-filter-chip" onclick="clearSlug('township')" title="Hapus filter township">
                    <i class="fas fa-city" style="font-size:11px;"></i> {{ $slugNames['township'] ?? $urlSlugs['township'] }}
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            {{-- Query-param chips --}}
            @if(request('price_min') || request('price_max'))
                <span class="active-filter-chip" onclick="clearParams('price_min','price_max')">
                    <i class="fas fa-tag" style="font-size:11px;"></i>
                    Harga: {{ request('price_min') ? 'Rp '.number_format(request('price_min')).' Jt' : 'min' }} — {{ request('price_max') ? 'Rp '.number_format(request('price_max')).' Jt' : 'max' }}
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            @if(request('lt_min') || request('lt_max'))
                <span class="active-filter-chip" onclick="clearParams('lt_min','lt_max')">
                    LT: {{ request('lt_min','0') }}–{{ request('lt_max','∞') }} m²
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            @if(request('lb_min') || request('lb_max'))
                <span class="active-filter-chip" onclick="clearParams('lb_min','lb_max')">
                    LB: {{ request('lb_min','0') }}–{{ request('lb_max','∞') }} m²
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            @if(request('twp'))
                @php $twpName = $townships->firstWhere('township_id', request('twp'))?->township_name ?? request('twp'); @endphp
                <span class="active-filter-chip" onclick="clearParam('twp')">
                    <i class="fas fa-city" style="font-size:11px;"></i> {{ $twpName }}
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            @if(request('tags'))
                @foreach(array_filter(explode(',', request('tags'))) as $activeTag)
                <span class="active-filter-chip" onclick="removeTagParam('{{ addslashes($activeTag) }}')">
                    <i class="fas fa-tag" style="font-size:11px;"></i> {{ $activeTag }}
                    <i class="fas fa-times chip-remove"></i>
                </span>
                @endforeach
            @endif
            @if(request('q'))
                <span class="active-filter-chip" onclick="clearParam('q')">
                    <i class="fas fa-search" style="font-size:11px;"></i> "{{ request('q') }}"
                    <i class="fas fa-times chip-remove"></i>
                </span>
            @endif
            <span class="active-filter-chip" style="background:#fff0f0; color:#dc3545; border-color:#f5c6cb;" onclick="clearAllFilters()" title="Hapus semua filter">
                <i class="fas fa-times" style="font-size:11px;"></i> Reset Semua
            </span>
        </div>
        @endif

        {{-- Title & Count --}}
        <div class="row mb-3">
            <div class="col-12">
                <p class="text-3 text-color-grey poppins-regular" style="font-size: 14px;">
                    @if($totalCount > 0)
                        Menampilkan {{ ($page - 1) * 16 + 1 }}–{{ min($page * 16, $totalCount) }} dari {{ number_format($totalCount) }} properti
                    @else
                        Tidak ada properti ditemukan
                    @endif
                </p>
            </div>
        </div>

        {{-- Property Grid 1 (first 8) --}}
        <div class="row">
            @forelse (array_slice($properties, 0, 8) as $prop)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border border-color-grey-1 bg-white h-100" style="border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); cursor:pointer;" onclick="window.location='{{ $prop['detail_url'] }}{{ $embedSuffix }}'">
                    <div class="position-relative p-2">
                        <div class="position-absolute top-0 left-0 pt-3 ms-3 z-index-1">
                            @foreach ($prop['badges'] as $badge)
                                <span class="badge font-weight-semibold px-2 py-1 me-1" style="background-color: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; border-radius: 4px; font-size: 10px;">{{ $badge['text'] }}</span>
                            @endforeach
                        </div>
                        <img src="{{ url($prop['image']) }}" class="img-fluid" alt="{{ $prop['title'] }}" style="border-radius: 8px; height: 180px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="card-body px-3 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="font-weight-bold text-4 mb-0" style="color: #3b5998;">{{ $prop['price'] }}</h4>
                            <a href="{{ $prop['detail_url'] }}{{ $embedSuffix }}" onclick="event.stopPropagation()"><i class="fas fa-arrow-right" style="color: #3b5998; font-size: 14px;"></i></a>
                        </div>
                        <h5 class="font-weight-semibold text-3 mb-1 mt-2" style="line-height: 1.3; color: #333; height: 38px; overflow: hidden; font-size: 14px;">{{ $prop['title'] }}</h5>
                        <p class="mb-2" style="font-size: 11px; color: #888;">{{ $prop['location'] }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 11px; color: #666; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                            <div class="d-flex align-items-center"><i class="fas fa-bed me-1" style="color: #a0a0a0;"></i><span class="font-weight-bold text-color-dark">{{ $prop['beds'] }}</span></div>
                            <div class="d-flex align-items-center"><i class="fas fa-bath me-1" style="color: #a0a0a0;"></i><span class="font-weight-bold text-color-dark">{{ $prop['baths'] }}</span></div>
                            <div>LT <span class="font-weight-bold text-color-dark ms-1">{{ $prop['lt'] }}m²</span></div>
                            <div>LB <span class="font-weight-bold text-color-dark ms-1">{{ $prop['lb'] }}m²</span></div>
                        </div>
                        @php
                            $keyWaPhone = (request('embed') !== '1' && isset($keyData) && is_array($keyData) && !empty($keyData['no_hp']))
                                ? \App\Services\EmbedKeyService::normalizePhone($keyData['no_hp'])
                                : '';
                            $keyWaText  = rawurlencode('Halo, Saya ingin informasi lengkap tentang ' . ($prop['title'] ?? '') . ', Mohon kirimkan detailnya.');
                        @endphp
                        @if($keyWaPhone)
                            <a href="https://api.whatsapp.com/send/?phone={{ $keyWaPhone }}&text={{ $keyWaText }}&type=phone_number&app_absent=0"
                               target="_blank"
                               onclick="event.stopPropagation()"
                               class="btn w-100 font-weight-bold py-2 text-color-light" style="background-color: #61c97d; border-radius: 8px; border: none; font-size: 13px;">
                                <i class="fab fa-whatsapp me-2 text-4"></i> WhatsApp
                            </a>
                        @elseif(request('embed') !== '1')
                            <a href="#"
                               data-phone="{{ $prop['wa_phone'] ?? '' }}"
                               data-title="{{ $prop['title'] }}"
                               data-id="{{ $prop['property_id'] ?? '' }}"
                               data-url="{{ $prop['detail_url'] ?? '' }}"
                               onclick="event.preventDefault(); event.stopPropagation(); openWaModal(this.dataset.phone, this.dataset.title, this.dataset.id, this.dataset.url)"
                               class="btn w-100 font-weight-bold py-2 text-color-light" style="background-color: #61c97d; border-radius: 8px; border: none; font-size: 13px;">
                                <i class="fab fa-whatsapp me-2 text-4"></i> WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-home fa-3x text-muted mb-3"></i>
                <p class="text-color-grey text-4">Belum ada properti tersedia.</p>
            </div>
            @endforelse
        </div>

        {{-- Banner Promo --}}
        @if ($banner)
        <div class="row my-4">
            <div class="col-12">
                <div style="border-radius: 12px; overflow: hidden; min-height: 150px;">
                    @php $bannerSrc = asset('storage/' . $banner['image_url']); @endphp
                    @if (!empty($banner['target_url']))
                        <a href="{{ $banner['target_url'] }}"><img src="{{ $bannerSrc }}" alt="Promo Banner" class="w-100" style="object-fit: cover;"></a>
                    @else
                        <img src="{{ $bannerSrc }}" alt="Promo Banner" class="w-100" style="object-fit: cover;">
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Property Grid 2 (next 8) --}}
        @if (count($properties) > 8)
        <div class="row">
            @foreach (array_slice($properties, 8) as $prop)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border border-color-grey-1 bg-white h-100" style="border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); cursor:pointer;" onclick="window.location='{{ $prop['detail_url'] }}{{ $embedSuffix }}'">
                    <div class="position-relative p-2">
                        <div class="position-absolute top-0 left-0 pt-3 ms-3 z-index-1">
                            @foreach ($prop['badges'] as $badge)
                                <span class="badge font-weight-semibold px-2 py-1 me-1" style="background-color: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; border-radius: 4px; font-size: 10px;">{{ $badge['text'] }}</span>
                            @endforeach
                        </div>
                        <img src="{{ url($prop['image']) }}" class="img-fluid" alt="{{ $prop['title'] }}" style="border-radius: 8px; height: 180px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="card-body px-3 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="font-weight-bold text-4 mb-0" style="color: #3b5998;">{{ $prop['price'] }}</h4>
                            <a href="{{ $prop['detail_url'] }}{{ $embedSuffix }}" onclick="event.stopPropagation()"><i class="fas fa-arrow-right" style="color: #3b5998; font-size: 14px;"></i></a>
                        </div>
                        <h5 class="font-weight-semibold text-3 mb-1 mt-2" style="line-height: 1.3; color: #333; height: 38px; overflow: hidden; font-size: 14px;">{{ $prop['title'] }}</h5>
                        <p class="mb-2" style="font-size: 11px; color: #888;">{{ $prop['location'] }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 11px; color: #666; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                            <div class="d-flex align-items-center"><i class="fas fa-bed me-1" style="color: #a0a0a0;"></i><span class="font-weight-bold text-color-dark">{{ $prop['beds'] }}</span></div>
                            <div class="d-flex align-items-center"><i class="fas fa-bath me-1" style="color: #a0a0a0;"></i><span class="font-weight-bold text-color-dark">{{ $prop['baths'] }}</span></div>
                            <div>LT <span class="font-weight-bold text-color-dark ms-1">{{ $prop['lt'] }}m²</span></div>
                            <div>LB <span class="font-weight-bold text-color-dark ms-1">{{ $prop['lb'] }}m²</span></div>
                        </div>
                        @php
                            $keyWaPhone = (request('embed') !== '1' && isset($keyData) && is_array($keyData) && !empty($keyData['no_hp']))
                                ? \App\Services\EmbedKeyService::normalizePhone($keyData['no_hp'])
                                : '';
                            $keyWaText  = rawurlencode('Halo, Saya ingin informasi lengkap tentang ' . ($prop['title'] ?? '') . ', Mohon kirimkan detailnya.');
                        @endphp
                        @if($keyWaPhone)
                            <a href="https://api.whatsapp.com/send/?phone={{ $keyWaPhone }}&text={{ $keyWaText }}&type=phone_number&app_absent=0"
                               target="_blank"
                               onclick="event.stopPropagation()"
                               class="btn w-100 font-weight-bold py-2 text-color-light" style="background-color: #61c97d; border-radius: 8px; border: none; font-size: 13px;">
                                <i class="fab fa-whatsapp me-2 text-4"></i> WhatsApp
                            </a>
                        @elseif(request('embed') !== '1')
                            <a href="#"
                               data-phone="{{ $prop['wa_phone'] ?? '' }}"
                               data-title="{{ $prop['title'] }}"
                               data-id="{{ $prop['property_id'] ?? '' }}"
                               data-url="{{ $prop['detail_url'] ?? '' }}"
                               onclick="event.preventDefault(); event.stopPropagation(); openWaModal(this.dataset.phone, this.dataset.title, this.dataset.id, this.dataset.url)"
                               class="btn w-100 font-weight-bold py-2 text-color-light" style="background-color: #61c97d; border-radius: 8px; border: none; font-size: 13px;">
                                <i class="fab fa-whatsapp me-2 text-4"></i> WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Pagination --}}
        @if ($totalPages > 1)
        <div class="row mt-4 mb-5">
            <div class="col-12 d-flex justify-content-end align-items-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0 align-items-center">
                        @php
                            $pageParams = array_filter(request()->only(['sort','price_min','price_max','lt_min','lt_max','lb_min','lb_max','tags','twp','q','embed','key']), fn($v) => $v !== '' && $v !== null);
                            $pageUrl = fn(int $p) => url($browseBase . '?' . http_build_query(array_merge($pageParams, ['page' => $p])));
                        @endphp
                        @if ($page > 1)
                        <li class="page-item">
                            <a class="page-link text-color-dark border-0 bg-transparent font-weight-semibold"
                               href="{{ $pageUrl($page - 1) }}">Prev</a>
                        </li>
                        @endif
                        @for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++)
                        <li class="page-item {{ $i === $page ? 'active' : '' }}">
                            <a class="page-link font-weight-bold"
                               href="{{ $pageUrl($i) }}"
                               style="border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; margin: 0 3px; {{ $i === $page ? 'background-color: #3b5998; color: #fff; border-color: #3b5998;' : 'background-color: transparent; color: #333; border: 1px solid #ccc;' }}">
                                {{ $i }}
                            </a>
                        </li>
                        @endfor
                        @if ($page < $totalPages)
                        <li class="page-item">
                            <a class="page-link text-color-dark border-0 bg-transparent font-weight-semibold"
                               href="{{ $pageUrl($page + 1) }}">Next</a>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif

    </div>
    </div>

    @if(request('embed') !== '1')
        @include('front.layout.footer')
    @endif

</div>

{{-- ===== MAP OVERLAY ===== --}}
<div class="map-overlay" id="map-overlay">
    <div class="map-top-bar">
        <button class="map-back-btn" id="map-close-btn">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </button>
        <span class="map-count">
            @if(count($mapMarkers) > 0)
                {{ count($mapMarkers) }} properti ditampilkan di peta
            @else
                Tidak ada properti dengan koordinat
            @endif
        </span>
    </div>
    <div id="leaflet-map"></div>
    @if(count($mapMarkers) === 0)
    <div class="map-no-results">
        <i class="fas fa-map-marked-alt fa-2x text-muted mb-3 d-block"></i>
        <p class="mb-0 text-muted" style="font-size:14px;">Tidak ada properti dengan koordinat lokasi<br>yang sesuai filter saat ini.</p>
    </div>
    @endif
</div>

{{-- ===== SORT MODAL ===== --}}
<div class="filter-modal-overlay" id="sort-overlay">
    <div class="filter-panel sort-panel" style="max-width:340px;">
        <div class="filter-panel-header">
            <h5>Urutkan</h5>
            <button class="filter-close-btn" id="btn-close-sort">&times;</button>
        </div>
        @php
            $sortOptions = [
                'newest'     => 'Terbaru',
                'price_asc'  => 'Harga Terendah',
                'price_desc' => 'Harga Tertinggi',
                'lt_desc'    => 'Luas Tanah Terluas',
                'lb_desc'    => 'Luas Bangunan Terluas',
            ];
        @endphp
        @foreach($sortOptions as $val => $label)
        <label class="sort-option {{ $sort === $val ? 'active' : '' }}" data-sort="{{ $val }}">
            <span>{{ $label }}</span>
            <span class="sort-radio-dot"></span>
        </label>
        @endforeach
    </div>
</div>

{{-- ===== FILTER MODAL ===== --}}
<div class="filter-modal-overlay" id="filter-overlay">
    <div class="filter-panel">
        <div class="filter-panel-header">
            <h5>Filter</h5>
            <button class="filter-close-btn" id="btn-close-filter">&times;</button>
        </div>

        <div class="filter-panel-content">

            {{-- Project / Township --}}
            @if($townships->isNotEmpty())
            <div class="filter-section">
                <div class="filter-section-title">Project</div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($townships as $twn)
                    <button type="button"
                            class="filter-chip {{ request('twp') == $twn->township_id ? 'selected' : '' }}"
                            data-group="twp"
                            data-value="{{ $twn->township_id }}"
                            data-label="{{ $twn->township_name }}">
                        <i class="fas fa-city" style="font-size:13px;"></i> {{ $twn->township_name }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Kata Kunci --}}
            <div class="filter-section {{ $townships->isNotEmpty() ? 'mt-3' : '' }}">
                <div class="filter-section-title">Kata Kunci</div>
                <input type="text" id="filter-keyword" class="form-control" style="font-size:13px;"
                    placeholder="Cari nama, lokasi..." value="{{ request('q') }}">
            </div>

            {{-- Kondisi Properti --}}
            <div class="filter-section mt-3">
                <div class="filter-section-title">Kondisi</div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($propertyConditions as $cond)
                    @php $cSlug = \Illuminate\Support\Str::slug($cond['translations'][0]['condition_name'] ?? ''); @endphp
                    <button type="button"
                            class="filter-chip {{ ($urlSlugs['condition'] ?? '') === $cSlug ? 'selected' : '' }}"
                            data-group="slug_condition"
                            data-value="{{ $cSlug }}"
                            data-label="{{ $cond['translations'][0]['condition_name'] ?? '-' }}">
                        <i class="fas fa-tag" style="font-size:13px;"></i>
                        {{ $cond['translations'][0]['condition_name'] ?? '-' }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Tipe Properti --}}
            <div class="filter-section mt-3">
                <div class="filter-section-title">Tipe Properti</div>
                <div class="d-flex flex-wrap gap-2" id="chips-property-type">
                    @foreach($propertyTypes as $type)
                    @php $tSlug = \Illuminate\Support\Str::slug($type['translations'][0]['type_name'] ?? ''); @endphp
                    <button type="button"
                            class="filter-chip {{ ($urlSlugs['type'] ?? '') === $tSlug ? 'selected' : '' }}"
                            data-group="slug_type"
                            data-value="{{ $tSlug }}"
                            data-label="{{ $type['translations'][0]['type_name'] ?? '-' }}">
                        <i class="{{ $type['icon_class'] ?? 'fas fa-home' }}" style="font-size:13px;"></i>
                        {{ $type['translations'][0]['type_name'] ?? '-' }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Harga --}}
            <div class="filter-section mt-3">
                <div class="filter-section-title">Harga (Juta Rupiah)</div>
                <div class="range-pair">
                    <input type="number" id="filter-price-min" class="form-control" placeholder="Min" min="0"
                        value="{{ request('price_min') }}">
                    <span class="range-sep">–</span>
                    <input type="number" id="filter-price-max" class="form-control" placeholder="Max" min="0"
                        value="{{ request('price_max') }}">
                    <span class="range-unit">Juta</span>
                </div>
                <div style="font-size:11px; color:#aaa; margin-top:5px;">Contoh: 500 = Rp 500 Juta &nbsp;|&nbsp; 2000 = Rp 2 Miliar</div>
            </div>

            {{-- Luas Tanah --}}
            <div class="filter-section mt-3">
                <div class="filter-section-title">Luas Tanah (m²)</div>
                <div class="range-pair">
                    <input type="number" id="filter-lt-min" class="form-control" placeholder="Min" min="0"
                        value="{{ request('lt_min') }}">
                    <span class="range-sep">–</span>
                    <input type="number" id="filter-lt-max" class="form-control" placeholder="Max" min="0"
                        value="{{ request('lt_max') }}">
                    <span class="range-unit">m²</span>
                </div>
            </div>

            {{-- Luas Bangunan --}}
            <div class="filter-section mt-3">
                <div class="filter-section-title">Luas Bangunan (m²)</div>
                <div class="range-pair">
                    <input type="number" id="filter-lb-min" class="form-control" placeholder="Min" min="0"
                        value="{{ request('lb_min') }}">
                    <span class="range-sep">–</span>
                    <input type="number" id="filter-lb-max" class="form-control" placeholder="Max" min="0"
                        value="{{ request('lb_max') }}">
                    <span class="range-unit">m²</span>
                </div>
            </div>

            {{-- Fitur Tag (multi autocomplete) --}}
            <div class="filter-section mt-3 mb-4">
                <div class="filter-section-title">Fitur Tag</div>
                <div style="position:relative;" id="tag-ac-wrap">
                    <div class="tag-input-wrap" id="tag-input-box">
                        {{-- existing selected tags rendered as chips --}}
                        @foreach(array_filter(explode(',', request('tags', ''))) as $st)
                        <span class="tag-chip" data-tag="{{ $st }}">{{ $st }}<span class="rm" data-tag="{{ $st }}">×</span></span>
                        @endforeach
                        <input type="text" class="tag-text-input" id="tag-ac-input" placeholder="Ketik nama tag..." autocomplete="off">
                    </div>
                    <div class="tag-dropdown" id="tag-dropdown" style="display:none;"></div>
                </div>
            </div>

        </div>{{-- /filter-panel-content --}}

        <div class="filter-footer">
            <button type="button" class="btn btn-outline-secondary w-50 py-2 fw-semibold" id="btn-reset-filter">Reset Filter</button>
            <button type="button" class="btn btn-primary w-50 py-2 fw-bold" id="btn-apply-filter"
                    style="background:#3b5998; border-color:#3b5998;">
                Tampilkan ({{ number_format($totalCount) }})
            </button>
        </div>
    </div>
</div>

{{-- Hidden form for sort submission (preserves current filter params) --}}
<form id="filter-form" action="" method="get" style="display:none;">
    <input type="hidden" name="q"         id="ff-q"         value="{{ request('q') }}">
    <input type="hidden" name="sort"      id="ff-sort"      value="{{ $sort }}">
    <input type="hidden" name="price_min" id="ff-price-min" value="{{ request('price_min') }}">
    <input type="hidden" name="price_max" id="ff-price-max" value="{{ request('price_max') }}">
    <input type="hidden" name="lt_min"    id="ff-lt-min"    value="{{ request('lt_min') }}">
    <input type="hidden" name="lt_max"    id="ff-lt-max"    value="{{ request('lt_max') }}">
    <input type="hidden" name="lb_min"    id="ff-lb-min"    value="{{ request('lb_min') }}">
    <input type="hidden" name="lb_max"    id="ff-lb-max"    value="{{ request('lb_max') }}">
    <input type="hidden" name="tags"      id="ff-tags"      value="{{ request('tags') }}">
    <input type="hidden" name="twp"       id="ff-twp"       value="{{ request('twp') }}">
</form>
@include('partials.hubspot')
<script src="{{ asset('vendor/plugins/js/plugins.min.js') }}"></script>
<script src="{{ asset('js/theme.js') }}"></script>
<script src="{{ asset('js/views/view.home.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/theme.init.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
(function() {
    // ── Current state from server ──────────────────────────────────────────
    const urlSlugs = {
        condition: '{{ $urlSlugs['condition'] ?? '' }}',
        type:      '{{ $urlSlugs['type']      ?? '' }}',
        kota:      '{{ $urlSlugs['kota']      ?? '' }}',
        township:  '{{ $urlSlugs['township']  ?? '' }}',
    };
    let selections = {
        slug_condition: urlSlugs.condition,
        slug_type:      urlSlugs.type,
        sort:           '{{ $sort }}',
        twp:            '{{ request('twp') }}',
    };

    // ── Build browse URL from slug segments + query params ─────────────────
    function buildBrowseUrl(cond, type, extraParams) {
        // Keep kota/township only if condition AND type are unchanged
        const keepPath = (cond === urlSlugs.condition && type === urlSlugs.type);
        const kota     = keepPath ? urlSlugs.kota     : '';
        const township = keepPath ? urlSlugs.township : '';

        let path = '/all-products';
        if (cond)                    path = '/' + cond;
        if (cond && type)            path = '/' + cond + '/' + type;
        if (cond && type && kota)    path = '/' + cond + '/' + type + '/' + kota;
        if (cond && type && kota && township) path = '/' + cond + '/' + type + '/' + kota + '/' + township;

        const url = new URL(window.location.origin + path);
        for (const [k, v] of Object.entries(extraParams || {})) {
            if (v) url.searchParams.set(k, v);
        }
        return url.toString();
    }

    // ── Overlay helpers ────────────────────────────────────────────────────
    function openOverlay(id)  { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; }
    function closeOverlay(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }

    document.getElementById('btn-open-sort').addEventListener('click',    () => openOverlay('sort-overlay'));
    document.getElementById('btn-open-filter').addEventListener('click',  () => openOverlay('filter-overlay'));
    document.getElementById('btn-close-sort').addEventListener('click',   () => closeOverlay('sort-overlay'));
    document.getElementById('btn-close-filter').addEventListener('click', () => closeOverlay('filter-overlay'));
    document.getElementById('sort-overlay').addEventListener('click',   function(e) { if (e.target === this) closeOverlay('sort-overlay'); });
    document.getElementById('filter-overlay').addEventListener('click', function(e) { if (e.target === this) closeOverlay('filter-overlay'); });

    // ── Sort options ───────────────────────────────────────────────────────
    document.querySelectorAll('.sort-option').forEach(opt => {
        opt.addEventListener('click', function() {
            document.querySelectorAll('.sort-option').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            selections.sort = this.dataset.sort;
            const form = document.getElementById('filter-form');
            form.action = buildBrowseUrl(urlSlugs.condition, urlSlugs.type, {});
            document.getElementById('ff-sort').value      = selections.sort;
            document.getElementById('ff-price-min').value = document.getElementById('filter-price-min')?.value || '';
            document.getElementById('ff-price-max').value = document.getElementById('filter-price-max')?.value || '';
            document.getElementById('ff-lt-min').value    = document.getElementById('filter-lt-min')?.value    || '';
            document.getElementById('ff-lt-max').value    = document.getElementById('filter-lt-max')?.value    || '';
            document.getElementById('ff-lb-min').value    = document.getElementById('filter-lb-min')?.value    || '';
            document.getElementById('ff-lb-max').value    = document.getElementById('filter-lb-max')?.value    || '';
            document.getElementById('ff-tags').value      = Array.from(document.querySelectorAll('#tag-input-box .tag-chip')).map(c => c.dataset.tag).filter(Boolean).join(',');
            document.getElementById('ff-twp').value       = selections.twp || '';
            document.getElementById('ff-q').value         = document.getElementById('filter-keyword')?.value   || '';
            form.submit();
        });
    });

    // ── Filter chips (toggle, single-select per group) ─────────────────────
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            const group = this.dataset.group;
            const val   = this.dataset.value;
            const wasSelected = this.classList.contains('selected');
            document.querySelectorAll(`.filter-chip[data-group="${group}"]`).forEach(c => c.classList.remove('selected'));
            if (!wasSelected) { this.classList.add('selected'); selections[group] = val; }
            else              { selections[group] = ''; }
        });
    });

    // ── Apply filter ───────────────────────────────────────────────────────
    document.getElementById('btn-apply-filter').addEventListener('click', function() {
        document.querySelectorAll('.filter-chip.selected').forEach(chip => {
            selections[chip.dataset.group] = chip.dataset.value;
        });

        const newCond = selections.slug_condition || '';
        const newType = selections.slug_type      || '';

        const queryParams = {};
        if (selections.sort && selections.sort !== 'newest') queryParams.sort = selections.sort;

        const priceMin = document.getElementById('filter-price-min').value.trim();
        const priceMax = document.getElementById('filter-price-max').value.trim();
        const ltMin    = document.getElementById('filter-lt-min').value.trim();
        const ltMax    = document.getElementById('filter-lt-max').value.trim();
        const lbMin    = document.getElementById('filter-lb-min').value.trim();
        const lbMax    = document.getElementById('filter-lb-max').value.trim();
        const keyword  = document.getElementById('filter-keyword').value.trim();

        if (priceMin) queryParams.price_min = priceMin;
        if (priceMax) queryParams.price_max = priceMax;
        if (ltMin)    queryParams.lt_min    = ltMin;
        if (ltMax)    queryParams.lt_max    = ltMax;
        if (lbMin)    queryParams.lb_min    = lbMin;
        if (lbMax)    queryParams.lb_max    = lbMax;
        const tagChips = Array.from(document.querySelectorAll('#tag-input-box .tag-chip')).map(c => c.dataset.tag).filter(Boolean);
        if (tagChips.length)     queryParams.tags = tagChips.join(',');
        if (selections.twp)      queryParams.twp  = selections.twp;
        if (keyword)             queryParams.q    = keyword;

        window.location.href = buildBrowseUrl(newCond, newType, queryParams);
    });

    // ── Reset filter ───────────────────────────────────────────────────────
    document.getElementById('btn-reset-filter').addEventListener('click', function() {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('selected'));
        ['filter-price-min','filter-price-max','filter-lt-min','filter-lt-max',
         'filter-lb-min','filter-lb-max','filter-keyword'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        selections.slug_condition = '';
        selections.slug_type      = '';
        selections.twp            = '';
        selectedTags = [];
        renderTagChips();
        window.location.href = '/all-products';
    });
})();

// ── Helpers accessible globally ────────────────────────────────────────────
function clearParam(param) {
    const url = new URL(window.location.href);
    url.searchParams.delete(param);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
function clearParams(...params) {
    const url = new URL(window.location.href);
    params.forEach(p => url.searchParams.delete(p));
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

function clearSlug(segment) {
    const cond = '{{ $urlSlugs['condition'] ?? '' }}';
    const type = '{{ $urlSlugs['type']      ?? '' }}';
    const kota = '{{ $urlSlugs['kota']      ?? '' }}';
    // Keep existing query params (minus page)
    const params = {};
    new URLSearchParams(window.location.search).forEach((v, k) => { if (k !== 'page' && v) params[k] = v; });
    const qs = Object.keys(params).length ? '?' + new URLSearchParams(params).toString() : '';

    let path;
    if      (segment === 'condition') path = '/all-products';
    else if (segment === 'type')      path = cond  ? '/' + cond : '/all-products';
    else if (segment === 'kota')      path = (cond && type) ? '/' + cond + '/' + type : '/all-products';
    else if (segment === 'township')  path = (cond && type && kota) ? '/' + cond + '/' + type + '/' + kota : '/all-products';
    else path = '/all-products';

    window.location.href = path + qs;
}

function clearAllFilters() {
    window.location.href = '{{ url('/all-products') }}';
}

function removeTagParam(tagName) {
    const url = new URL(window.location.href);
    const current = url.searchParams.get('tags') || '';
    const updated = current.split(',').map(t => t.trim()).filter(t => t && t !== tagName);
    if (updated.length) url.searchParams.set('tags', updated.join(','));
    else url.searchParams.delete('tags');
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
</script>

{{-- Tag autocomplete JS --}}
@php $initialSelectedTags = array_values(array_filter(explode(',', request('tags', '')))); @endphp
<script>
(function () {
    const allTags     = @json($availableTags);
    const inputEl     = document.getElementById('tag-ac-input');
    const dropdownEl  = document.getElementById('tag-dropdown');
    const inputBoxEl  = document.getElementById('tag-input-box');

    let localSelected = @json($initialSelectedTags);

    function renderChips() {
        inputBoxEl.querySelectorAll('.tag-chip').forEach(c => c.remove());
        localSelected.forEach(function (tag) {
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.dataset.tag = tag;
            chip.innerHTML = tag + '<span class="rm" data-tag="' + escHtml(tag) + '">×</span>';
            chip.querySelector('.rm').addEventListener('click', function (e) {
                e.stopPropagation();
                removeTag(this.dataset.tag);
            });
            inputBoxEl.insertBefore(chip, inputEl);
        });
    }

    function addTag(tag) {
        tag = tag.trim();
        if (!tag || localSelected.includes(tag)) return;
        localSelected.push(tag);
        renderChips();
        inputEl.value = '';
        hideDropdown();
    }

    function removeTag(tag) {
        localSelected = localSelected.filter(t => t !== tag);
        renderChips();
    }

    function showDropdown(q) {
        const filtered = allTags.filter(t => t.toLowerCase().includes(q.toLowerCase()) && !localSelected.includes(t));
        if (!filtered.length) { hideDropdown(); return; }
        dropdownEl.innerHTML = filtered.slice(0, 20).map(function (t) {
            return '<div class="tag-dropdown-item" data-tag="' + escHtml(t) + '">' + escHtml(t) + '</div>';
        }).join('');
        dropdownEl.querySelectorAll('.tag-dropdown-item').forEach(function (item) {
            item.addEventListener('mousedown', function (e) {
                e.preventDefault();
                addTag(this.dataset.tag);
            });
        });
        dropdownEl.style.display = 'block';
    }

    function hideDropdown() { dropdownEl.style.display = 'none'; }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    inputEl.addEventListener('input', function () {
        this.value.trim() ? showDropdown(this.value.trim()) : hideDropdown();
    });
    inputEl.addEventListener('keydown', function (e) {
        if ((e.key === 'Enter' || e.key === ',') && this.value.trim()) {
            e.preventDefault();
            addTag(this.value.trim());
        }
        if (e.key === 'Backspace' && !this.value && localSelected.length) {
            removeTag(localSelected[localSelected.length - 1]);
        }
    });
    inputEl.addEventListener('blur', function () { setTimeout(hideDropdown, 150); });
    inputBoxEl.addEventListener('click', function () { inputEl.focus(); });

    // Remove chips added server-side (re-render with JS to attach events)
    renderChips();

    // Used by reset button
    window.renderTagChips = function () { localSelected = []; renderChips(); };
})();
</script>

{{-- ===== MAP VIEW JS ===== --}}
<script>
(function() {
    const mapMarkers = @json($mapMarkers);
    let mapInitialized = false;
    let leafletMap    = null;

    document.getElementById('btn-map-view').addEventListener('click', function() {
        document.getElementById('map-overlay').classList.add('show');
        document.body.style.overflow = 'hidden';
        initMap();
    });

    document.getElementById('map-close-btn').addEventListener('click', function() {
        document.getElementById('map-overlay').classList.remove('show');
        document.body.style.overflow = '';
    });

    function initMap() {
        if (mapInitialized) {
            // Invalidate size in case overlay was hidden when initialized
            if (leafletMap) leafletMap.invalidateSize();
            return;
        }
        mapInitialized = true;

        leafletMap = L.map('leaflet-map', { zoomControl: true });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(leafletMap);

        if (mapMarkers.length === 0) {
            leafletMap.setView([-6.2, 106.816667], 9);
            return;
        }

        const customIcon = L.divIcon({
            className: '',
            html: '<div style="background:#3b5998;width:14px;height:14px;border-radius:50%;border:2.5px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,0.4);"></div>',
            iconSize: [14, 14],
            iconAnchor: [7, 7],
            popupAnchor: [0, -10]
        });

        const lMarkers = mapMarkers.map(function(m) {
            const popup = `
                <div style="min-width:210px; max-width:230px;">
                    <img class="map-popup-img" src="${m.image}" alt="${m.title}" onerror="this.src='/stock-image/rekomendasi-property.jpg'">
                    <div class="map-popup-price">${m.price}</div>
                    <div class="map-popup-title">${m.title}</div>
                    <a href="${m.url}" class="map-popup-btn">Lihat Detail →</a>
                </div>
            `;
            return L.marker([m.lat, m.lng], { icon: customIcon })
                    .addTo(leafletMap)
                    .bindPopup(popup, { maxWidth: 240 });
        });

        const group = L.featureGroup(lMarkers);
        leafletMap.fitBounds(group.getBounds().pad(0.15));

        // Invalidate after animation (overlay fade-in can delay size calculation)
        setTimeout(function() { leafletMap.invalidateSize(); }, 300);
    }
})();
</script>
@include('front.partials.whatsapp-modal')
</body>
</html>
