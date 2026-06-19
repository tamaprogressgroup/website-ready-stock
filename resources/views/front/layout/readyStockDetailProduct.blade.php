<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
	<title>{{ $property['meta_title'] ?? ($property['title'] ?? 'Detail Properti') . ' - Paradise Ready Stock' }}</title>
	<meta name="keywords" content="{{ $property['meta_keyword'] ?? '' }}">
	<meta name="description" content="{{ $property['meta_description'] ?? Str::limit($property['description'] ?? '', 160) }}">
	<meta name="author" content="paradise.co.id">
	<meta property="og:title" content="{{ $property['meta_title'] ?? $property['title'] ?? '' }}">
	<meta property="og:description" content="{{ $property['meta_description'] ?? Str::limit($property['description'] ?? '', 160) }}">
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
	<link rel="stylesheet" href="{{ asset('vendor/magnific-popup/magnific-popup.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/owl.carousel/assets/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/owl.carousel/assets/owl.theme.default.min.css') }}">
	<style>
		.price-box { border-left: 4px solid #f0ad4e; padding-left: 15px; }
		.spec-table td { padding: 8px 0; color: #555; border-bottom: 1px solid #eee; }
		.spec-table td:first-child { width: 30%; color: #888; font-size: 13px; }
		.check-list { padding-left: 0; }
		.check-list li { list-style: none; position: relative; padding-left: 26px; margin-bottom: 10px; color: #555; font-size: 13px; }
		.check-list li::before { content: '\f058'; font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; left: 0; top: 1px; color: #61c97d; }
		/* Gallery */
		.gallery-img-wrap { cursor: pointer; border-radius: 12px; overflow: hidden; }
		.gallery-img-wrap img { transition: transform 0.3s ease; display: block; }
		.gallery-img-wrap:hover img { transform: scale(1.04); }
		.gallery-lihat-semua { cursor: pointer; border-radius: 12px; overflow: hidden; position: relative; }
		.gallery-lihat-semua .ls-overlay {
			position: absolute; inset: 0;
			background: rgba(0,0,0,0.5);
			display: flex; align-items: center; justify-content: center;
			transition: background 0.2s;
		}
		.gallery-lihat-semua:hover .ls-overlay { background: rgba(0,0,0,0.65); }
		/* Extra Features */
		.extra-feature-item {
			display: flex; align-items: center; gap: 9px;
			background: #f7f8fa; border-radius: 8px;
			padding: 9px 14px; font-size: 13px; color: #444;
		}
		.extra-feature-item i { font-size: 15px; color: #3b5998; width: 18px; text-align: center; flex-shrink: 0; }
		/* Facilities */
		.facility-icon-item { display: flex; align-items: center; gap: 9px; font-size: 13px; color: #555; }
		.facility-icon-item i { font-size: 15px; color: #3b5998; width: 18px; text-align: center; flex-shrink: 0; }
		/* Facility Slider – full-width single slide */
		.facility-slider {
			position: relative;
			border-radius: 16px;
			overflow: hidden;
			box-shadow: 0 4px 24px rgba(0,0,0,0.12);
		}
		.facility-slider .owl-stage-outer { display: block; }
		.facility-slider .owl-nav { margin: 0 !important; }
		.facility-slider .owl-nav button {
			position: absolute; top: 50%; transform: translateY(-50%);
			background: rgba(255,255,255,0.88) !important;
			width: 42px; height: 42px; border-radius: 50% !important;
			box-shadow: 0 2px 12px rgba(0,0,0,0.22) !important;
			display: flex !important; align-items: center; justify-content: center;
			font-size: 15px; color: #333 !important;
			margin: 0 !important; z-index: 10;
			transition: background 0.2s, box-shadow 0.2s;
		}
		.facility-slider .owl-nav button:hover {
			background: rgba(255,255,255,1) !important;
			box-shadow: 0 4px 16px rgba(0,0,0,0.28) !important;
		}
		.facility-slider .owl-nav button.owl-prev { left: 16px; }
		.facility-slider .owl-nav button.owl-next { right: 16px; }
		.facility-slider .owl-nav button span { display: none; }
		/* Dots overlaid inside image */
		.facility-slider .owl-dots {
			position: absolute;
			bottom: 16px; left: 0; right: 0;
			text-align: center; margin: 0 !important;
		}
		.facility-slider .owl-dots .owl-dot { display: inline-block; }
		.facility-slider .owl-dots .owl-dot span {
			display: block;
			width: 8px; height: 8px;
			background: rgba(255,255,255,0.55) !important;
			border-radius: 50% !important;
			margin: 0 4px !important;
			transition: all 0.25s ease;
		}
		.facility-slider .owl-dots .owl-dot.active span {
			background: rgba(255,255,255,1) !important;
			width: 22px !important;
			border-radius: 4px !important;
		}
		.facility-slide-item { position: relative; }
		.facility-slide-item img { width: 100%; height: 400px; object-fit: cover; display: block; }
		.facility-slide-caption {
			position: absolute; bottom: 0; left: 0; right: 0;
			background: linear-gradient(transparent, rgba(0,0,0,0.6));
			color: #fff; font-size: 14px; font-weight: 600;
			padding: 32px 18px 50px;
		}
		/* Sidebar */
		.promo-card { border-radius: 16px; position: sticky; top: 100px; box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
		/* Related cards */
		.related-card { border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: box-shadow 0.2s; }
		.related-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,0.1); }
	</style>
	@include('partials.google_tag')
	@include('partials.facebook_pixel')
</head>

<body data-plugin-page-transition>
	@include('partials.google_tag_iframe')
<div class="body">

	@if(request('embed'))
		@include('front.partials.embed-navbar')
	@else
		@include('front.layout.navbar')
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
	@endif

	@php
		$embedSuffix = request('embed')
			? '?embed=1' . (request('key') ? '&key=' . rawurlencode(request('key')) : '')
			: '';
	@endphp
	<div role="main" class="main" style="padding-top: {{ request('embed') ? '0' : '100px' }};">
		<div class="container py-4 mt-3">

			@php
				$allImages       = array_values($property['images'] ?? []);
				$subImages       = array_slice($allImages, 1, 3);
				$facilityImgs    = array_values(array_filter($property['facilities'] ?? [], fn($f) => !empty($f['image_url'])));
				$facilityImgCnt  = count($facilityImgs);
				$lat             = $property['lat'] ?? null;
				$lng             = $property['lng'] ?? null;
				$hasCoords       = $lat !== null && $lng !== null;
				$totalImgCount   = count($allImages) + $facilityImgCnt;
				$media           = $property['media'] ?? null;
				$hasVideo        = !empty($media['video_path']);
				$has360          = !empty($media['url_360']);
				$hasYoutube      = !empty($media['url_youtube']);
				// Extract YouTube video ID
				$youtubeId = null;
				if ($hasYoutube) {
					preg_match('/(?:v=|\/embed\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $media['url_youtube'], $ytMatch);
					$youtubeId = $ytMatch[1] ?? null;
				}
			@endphp

			{{-- ============================================================
			     IMAGE GALLERY GRID
			     ============================================================ --}}
			<style>
			/* Desktop: large main + 3 stacked on the right */
			.gallery-main-wrap { height: 400px; }
			.gallery-sub-col {
				display: flex;
				flex-direction: column;
				gap: 8px;
				height: 400px;
			}
			.gallery-sub-item { flex: 1; min-height: 0; overflow: hidden; }

			/* Mobile: main image full-width tall, sub images horizontal row below */
			@media (max-width: 991px) {
				/* Remove Bootstrap g-2 row gap between main and sub on mobile */
				.detail-gallery-row { --bs-gutter-y: 0; }
				.gallery-main-col { padding-bottom: 4px; }
				.gallery-main-wrap {
					height: 230px;
					border-radius: 10px 10px 0 0 !important;
				}
				.gallery-sub-col {
					flex-direction: row;
					height: 100px;
					gap: 4px;
				}
				.gallery-sub-item {
					flex: 1;
					width: 0;
					height: 100%;
					border-radius: 0;
				}
				.gallery-sub-item:first-child { border-radius: 0 0 0 10px; }
				.gallery-sub-item:last-child  { border-radius: 0 0 10px 0; }
				.ls-overlay span { font-size: 11px !important; }
				.ls-overlay span i { margin-right: 4px !important; font-size: 11px; }
			}
			</style>

			<div class="row g-2 mb-4 detail-gallery-row" style="position: relative;">
				{{-- Main image --}}
				<div class="col-lg-8 col-12 gallery-main-col">
					<div class="gallery-img-wrap gallery-main-wrap" data-gallery-index="0">
						<img src="{{ url($allImages[0]['url'] ?? 'stock-image/rekomendasi-property.jpg') }}"
							class="w-100 h-100" style="object-fit: cover;"
							alt="{{ $allImages[0]['name'] ?? 'Main Image' }}">
					</div>
				</div>

				{{-- Sub images: stacked on desktop, horizontal row on mobile --}}
				<div class="col-lg-4 col-12 gallery-sub-col">
					@if (isset($subImages[0]))
					<div class="gallery-img-wrap gallery-sub-item" data-gallery-index="1">
						<img src="{{ url($subImages[0]['url']) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $subImages[0]['name'] }}">
					</div>
					@else
					<div class="gallery-sub-item" style="background: #eee;"></div>
					@endif

					@if (isset($subImages[1]))
					<div class="gallery-img-wrap gallery-sub-item" data-gallery-index="2">
						<img src="{{ url($subImages[1]['url']) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $subImages[1]['name'] }}">
					</div>
					@else
					<div class="gallery-sub-item" style="background: #e8e8e8;"></div>
					@endif

					{{-- Last slot: Lihat Semua --}}
					@if (isset($subImages[2]))
					<div class="gallery-lihat-semua gallery-sub-item" id="open-gallery-btn">
						<img src="{{ url($subImages[2]['url']) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $subImages[2]['name'] }}">
						<div class="ls-overlay">
							<span class="text-white font-weight-bold">
								<i class="fas fa-images me-1"></i>Lihat Semua
							</span>
						</div>
					</div>
					@else
					<div class="gallery-sub-item d-flex align-items-center justify-content-center"
						id="open-gallery-btn"
						style="background: #f0f0f0; cursor: pointer;">
						<span class="font-weight-semibold" style="color: #666; font-size: 13px;">
							<i class="fas fa-images me-1"></i>Lihat Semua
						</span>
					</div>
					@endif
				</div>
			</div>

			{{-- ============================================================
			     FULL-SCREEN GALLERY OVERLAY
			     ============================================================ --}}
			<style>
			.go-overlay { position: fixed; inset: 0; background: #fff; z-index: 10000; display: none; flex-direction: column; overflow: hidden; }
			.go-overlay.show { display: flex; }
			.go-header { display: flex; align-items: center; gap: 14px; padding: 14px 20px; border-bottom: 1px solid #e8e8e8; flex-shrink: 0; }
			.go-back-btn { background: none; border: none; padding: 4px 8px; cursor: pointer; color: #333; font-size: 20px; line-height: 1; display: flex; align-items: center; }
			.go-header-info h6 { font-size: 14px; font-weight: 700; color: #1a1a1a; margin: 0 0 2px; }
			.go-header-info span { font-size: 12px; color: #3b5998; font-weight: 600; }
			.go-body { display: flex; flex: 1; overflow: hidden; }
			/* ---- Sidebar (desktop: vertical, mobile: horizontal tab strip) ---- */
			.go-sidebar { width: 160px; flex-shrink: 0; border-right: 1px solid #ebebeb; overflow-y: auto; padding: 16px 12px; background: #fafafa; }
			.go-cat-card, .go-cat-card-icon { border-radius: 8px; overflow: hidden; margin-bottom: 12px; cursor: pointer; border: 2px solid transparent; transition: border-color 0.15s; position: relative; }
			.go-cat-card.active, .go-cat-card:hover,
			.go-cat-card-icon.active, .go-cat-card-icon:hover { border-color: #3b5998; }
			.go-cat-card img { width: 100%; height: 80px; object-fit: cover; display: block; }
			.go-cat-badge { position: absolute; top: 5px; left: 5px; background: rgba(0,0,0,0.6); color: #fff; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 1px 6px; }
			.go-cat-name { font-size: 11px; font-weight: 600; color: #333; padding: 5px 6px; background: #fff; text-align: center; }
			.go-cat-card-icon .go-cat-icon-bg { width: 100%; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 26px; }
			/* ---- Main scroll area ---- */
			.go-main { flex: 1; overflow-y: auto; padding: 20px 24px; scroll-behavior: smooth; }
			.go-section-title { font-size: 16px; font-weight: 700; color: #1a1a1a; margin-bottom: 16px; }
			/* ---- Panels — always visible, stacked ---- */
			.go-panel { padding-bottom: 36px; }
			.go-panel + .go-panel { border-top: 1px solid #ebebeb; padding-top: 28px; }
			/* ---- Photo grid ---- */
			.go-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
			.go-grid-item { position: relative; border-radius: 8px; overflow: hidden; cursor: pointer; background: #f0f0f0; }
			.go-grid-item img { width: 100%; height: 200px; object-fit: cover; display: block; transition: transform 0.25s; }
			.go-grid-item:hover img { transform: scale(1.03); }
			.go-grid-caption { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.55)); color: #fff; font-size: 11px; font-weight: 600; padding: 18px 10px 7px; }
			@media (min-width: 768px) { .go-grid { grid-template-columns: repeat(3, 1fr); } }
			/* ---- Media embed ---- */
			.go-embed-wrap { border-radius: 12px; overflow: hidden; background: #000; }
			.go-embed-wrap iframe, .go-embed-wrap video { width: 100%; display: block; border: none; }
			.go-media-desc { font-size: 13px; color: #666; margin-top: 12px; text-align: center; }
			/* ---- Lightbox ---- */
			.go-lightbox { position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 10001; display: none; align-items: center; justify-content: center; }
			.go-lightbox.show { display: flex; }
			.go-lightbox img { max-width: 90vw; max-height: 90vh; object-fit: contain; border-radius: 6px; }
			.go-lb-close { position: absolute; top: 18px; right: 22px; background: none; border: none; color: #fff; font-size: 30px; cursor: pointer; line-height: 1; }
			.go-lb-nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.15); border: none; color: #fff; font-size: 22px; width: 48px; height: 48px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.15s; }
			.go-lb-nav:hover { background: rgba(255,255,255,0.3); }
			.go-lb-prev { left: 16px; }
			.go-lb-next { right: 16px; }
			.go-lb-caption { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); color: #fff; font-size: 13px; font-weight: 600; background: rgba(0,0,0,0.4); padding: 5px 16px; border-radius: 20px; white-space: nowrap; }
			/* ---- Responsive: mobile sidebar becomes horizontal tab strip ---- */
			@media (max-width: 767px) {
				.go-header { padding: 10px 14px; }
				.go-header-info h6 { font-size: 13px; }
				.go-body { flex-direction: column; }
				.go-sidebar {
					width: 100%; height: auto; flex-shrink: 0;
					border-right: none; border-bottom: 2px solid #ebebeb;
					display: flex; flex-direction: row;
					overflow-x: auto; overflow-y: hidden;
					padding: 10px 10px 0; gap: 8px;
					scroll-snap-type: x mandatory;
					-webkit-overflow-scrolling: touch;
				}
				.go-sidebar::-webkit-scrollbar { height: 3px; }
				.go-sidebar::-webkit-scrollbar-thumb { background: #ccc; border-radius: 2px; }
				.go-cat-card, .go-cat-card-icon {
					flex: 0 0 80px; margin-bottom: 0;
					scroll-snap-align: start;
				}
				.go-cat-card img, .go-cat-card-icon .go-cat-icon-bg { height: 60px; }
				.go-main { padding: 16px 14px; }
				.go-grid { grid-template-columns: repeat(2, 1fr); }
				.go-grid-item img { height: 140px; }
				.go-embed-wrap iframe, .go-embed-wrap video { height: 240px !important; }
			}
			</style>

			{{-- Gallery Overlay --}}
			<div class="go-overlay" id="go-overlay">
				<div class="go-header">
					<button class="go-back-btn" id="go-close">
						<i class="fas fa-arrow-left"></i>
					</button>
					<div class="go-header-info">
						<h6>{{ $property['title'] }}</h6>
						<span>{{ $property['price_display'] }}</span>
					</div>
				</div>
				<div class="go-body">
					{{-- Sidebar --}}
					<div class="go-sidebar" id="go-sidebar">
						@if (count($allImages) > 0)
						<div class="go-cat-card active" data-cat="interior">
							<img src="{{ url($allImages[0]['url']) }}" alt="Interior">
							<div class="go-cat-badge">{{ count($allImages) }}</div>
							<div class="go-cat-name">Interior</div>
						</div>
						@endif
						@if ($facilityImgCnt > 0)
						<div class="go-cat-card" data-cat="fasilitas">
							<img src="{{ url($facilityImgs[0]['image_url']) }}" alt="Fasilitas">
							<div class="go-cat-badge">{{ $facilityImgCnt }}</div>
							<div class="go-cat-name">Fasilitas Lainnya</div>
						</div>
						@endif
						@if ($hasYoutube)
						<div class="go-cat-card-icon" data-cat="youtube">
							<div class="go-cat-icon-bg" style="background:#ff0000;">
								<i class="fab fa-youtube" style="color:#fff;"></i>
							</div>
							<div class="go-cat-name">YouTube</div>
						</div>
						@endif
						@if ($has360)
						<div class="go-cat-card-icon" data-cat="tour360">
							<div class="go-cat-icon-bg" style="background:#0d6efd;">
								<i class="fas fa-street-view" style="color:#fff;"></i>
							</div>
							<div class="go-cat-name">Tour 360°</div>
						</div>
						@endif
						@if ($hasVideo)
						<div class="go-cat-card-icon" data-cat="video">
							<div class="go-cat-icon-bg" style="background:#198754;">
								<i class="fas fa-film" style="color:#fff;"></i>
							</div>
							<div class="go-cat-name">Video</div>
						</div>
						@endif
					</div>

					{{-- Main photo area --}}
					<div class="go-main" id="go-main">
						{{-- Interior panel --}}
						<div class="go-panel" id="panel-interior" data-cat="interior">
							<div class="go-section-title">Interior</div>
							<div class="go-grid">
								@foreach ($allImages as $i => $img)
								<div class="go-grid-item" data-lb-src="{{ url($img['url']) }}" data-lb-cap="{{ $img['caption'] ?? $img['name'] ?? '' }}" data-lb-group="interior" data-lb-idx="{{ $i }}">
									<img src="{{ url($img['url']) }}" alt="{{ $img['name'] ?? '' }}" loading="lazy">
									@if (!empty($img['caption']))
									<div class="go-grid-caption">{{ $img['caption'] }}</div>
									@endif
								</div>
								@endforeach
							</div>
						</div>

						{{-- Fasilitas panel --}}
						@if ($facilityImgCnt > 0)
						<div class="go-panel" id="panel-fasilitas" data-cat="fasilitas">
							<div class="go-section-title">Fasilitas Lainnya</div>
							<div class="go-grid">
								@foreach ($facilityImgs as $i => $fac)
								<div class="go-grid-item" data-lb-src="{{ url($fac['image_url']) }}" data-lb-cap="{{ $fac['name'] ?? '' }}" data-lb-group="fasilitas" data-lb-idx="{{ $i }}">
									<img src="{{ url($fac['image_url']) }}" alt="{{ $fac['name'] ?? '' }}" loading="lazy">
									@if (!empty($fac['name']))
									<div class="go-grid-caption">{{ $fac['name'] }}</div>
									@endif
								</div>
								@endforeach
							</div>
						</div>
						@endif

						{{-- YouTube panel --}}
						@if ($hasYoutube)
						<div class="go-panel" id="panel-youtube" data-cat="youtube">
							<div class="go-section-title"><i class="fab fa-youtube me-2" style="color:#ff0000;"></i>Video YouTube</div>
							<div class="go-embed-wrap">
								@if ($youtubeId)
								<iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0"
									height="480" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
								@else
								<div style="padding:32px; text-align:center; color:#fff;">
									<a href="{{ $media['url_youtube'] }}" target="_blank" class="btn btn-danger">
										<i class="fab fa-youtube me-2"></i>Buka di YouTube
									</a>
								</div>
								@endif
							</div>
							<p class="go-media-desc">Video properti di YouTube</p>
						</div>
						@endif

						{{-- Tour 360° panel --}}
						@if ($has360)
						<div class="go-panel" id="panel-tour360" data-cat="tour360">
							<div class="go-section-title"><i class="fas fa-street-view me-2" style="color:#0d6efd;"></i>Virtual Tour 360°</div>
							<div class="go-embed-wrap">
								<iframe src="{{ $media['url_360'] }}" height="500" allowfullscreen
									allow="accelerometer; gyroscope; vr"></iframe>
							</div>
							<p class="go-media-desc">Jelajahi properti secara virtual 360°</p>
						</div>
						@endif

						{{-- Video file panel --}}
						@if ($hasVideo)
						<div class="go-panel" id="panel-video" data-cat="video">
							<div class="go-section-title"><i class="fas fa-film me-2" style="color:#198754;"></i>Video Properti</div>
							<div class="go-embed-wrap">
								<video controls style="max-height:520px;" preload="metadata">
									<source src="{{ asset('storage/' . $media['video_path']) }}" type="video/mp4">
									Browser Anda tidak mendukung pemutaran video.
								</video>
							</div>
							<p class="go-media-desc">Video langsung properti ini</p>
						</div>
						@endif
					</div>
				</div>
			</div>

			{{-- Lightbox --}}
			<div class="go-lightbox" id="go-lightbox">
				<button class="go-lb-close" id="go-lb-close">&times;</button>
				<button class="go-lb-nav go-lb-prev" id="go-lb-prev"><i class="fas fa-chevron-left"></i></button>
				<img src="" alt="" id="go-lb-img">
				<button class="go-lb-nav go-lb-next" id="go-lb-next"><i class="fas fa-chevron-right"></i></button>
				<div class="go-lb-caption" id="go-lb-cap"></div>
			</div>

			<div class="row">
				{{-- ============================================================
				     MAIN CONTENT (LEFT)
				     ============================================================ --}}
				<div class="col-lg-8 pe-lg-5">

					{{-- 1. TAGS --}}
					@if (!empty($property['tags']))
					<div class="d-flex flex-wrap gap-2 mb-4">
						@foreach ($property['tags'] as $tag)
							<span class="badge text-white px-3 py-1"
								style="background-color: {{ $tag['bg'] }}; font-size: 11px; border-radius: 20px; font-weight: 600; letter-spacing: 0.3px;">
								{{ $tag['text'] }}
							</span>
						@endforeach
					</div>
					@endif

					{{-- 2. PRICE + TITLE --}}
					<div class="mb-4">
						<div class="price-box mb-3">
							<div class="text-color-grey text-2 mb-1 poppins-semibold" style="font-size: 14px;" >Harga</div>
							<div class="d-flex align-items-center flex-wrap gap-2">
								<h2 class="poppins-bold mb-0" style="color: #1C5FA8; font-size: 32px;">{{ $property['price_display'] }}</h2>
								@if ($property['has_discount'])
									<span class="text-decoration-line-through text-muted text-3 poppins-regular" style="font-size: 12px; ">{{ $property['price_original'] }}</span>
									<span style="display:inline-flex;align-items:center;gap:6px;background:#fff;color:#444;border:1px solid #e0e0e0;border-radius:20px;padding:5px 14px;font-size:13px;white-space:nowrap;box-shadow:0 1px 4px rgba(0,0,0,0.06);">
										<i class="fas fa-tag" style="color:#e53935;font-size:8px;"></i>
										Diskon {{ $property['discount_display'] }}
									</span>
								@endif
							</div>
						</div>
						<h3 class="poppins-semibold text-color-dark mb-1" style="font-size: 24px; line-height: 1.3;">{{ $property['title'] }}</h3>
						<p class="text-color-grey text-3 mb-0 poppins-regular" style="font-size: 14px;">
							<i class="fas fa-map-marker-alt me-1" style="color: #aaa; "></i>{{ $property['location'] }}
						</p>
					</div>

					{{-- 3. KEY STATS --}}
					<div class="d-flex flex-wrap gap-5 mb-4 pb-4 border-bottom border-color-grey-1">
    
						<div class="d-flex flex-column gap-2">
							<div class="d-flex align-items-center gap-2">
								<i class="fas fa-bed text-5 text-color-dark"></i>
								<div class="text-5 poppins-medium" style="line-height: 1; font-size: 24px;">{{ $property['beds'] }}</div>
							</div>
							<div class="text-3 poppins-regular" style="font-size: 14px;" >Kamar Tidur</div>
						</div>

						<div class="d-flex flex-column gap-2">
							<div class="d-flex align-items-center gap-2">
								<i class="fas fa-bath text-5 text-color-dark"></i>
								<div class="text-5 poppins-medium" style="line-height: 1; font-size: 24px;">{{ $property['baths'] }}</div>
							</div>
							<div class="text-3 poppins-regular" style="font-size: 14px;" >Kamar Mandi</div>
						</div>

						<div class="d-flex flex-column gap-2 justify-content-between">
							<div class="text-5 poppins-medium" style="line-height: 1; font-size: 24px;">{{ $property['land_area'] }}m²</div>
							<div class="text-3 poppins-regular" style="font-size: 14px;" >Luas Tanah</div>
						</div>

						<div class="d-flex flex-column gap-2 justify-content-between">
							<div class="text-5 poppins-medium" style="line-height: 1; font-size: 24px;">{{ $property['building_area'] }}m²</div>
							<div class="text-3 poppins-regular" style="font-size: 14px;" >Luas Bangunan</div>
						</div>

					</div>

					{{-- 4. DESKRIPSI --}}
					@if ($property['description'])
					<div class="mb-4 pb-4 border-bottom border-color-grey-1">
						<h4 class="poppins-semibold text-4 mb-3" style="font-size: 24px;" >Deskripsi</h4>
						<p class="poppins-regular text-3" style="line-height: 20.4px; font-size:14px; ">{{ $property['description'] }}</p>
					</div>
					@endif

					{{-- 5. EKSTRA FITUR --}}
					@if (!empty($property['extra_features']))
					<div class="mb-4 pb-4 border-bottom border-color-grey-1">
						<h4 class="poppins-semibold text-4 mb-3" style="font-size: 24px;">Ekstra Fitur</h4>
						<div class="d-flex flex-wrap gap-2">
							@foreach ($property['extra_features'] as $ef)
							<div class="extra-feature-item">
								<i class="{{ $ef['icon'] }}"></i>
								<span class="poppins-semibold" style="font-size: 14px;" >{{ $ef['name'] }}</span>
							</div>
							@endforeach
						</div>
					</div>
					@endif

					{{-- 6. SPESIFIKASI --}}
					@if (!empty($property['specs']))
					<div class="mb-4 pb-4 border-bottom border-color-grey-1">
						<h4 class="poppins-semibold text-4 mb-3" style="font-size: 24px;">Spesifikasi</h4>
						<table class="table table-borderless spec-table text-3 mb-0">
							<tbody>
								@foreach ($property['specs'] as $spec)
								<tr>
									<td class="poppins-regular" style="font-size: 14px;" >{{ $spec['key'] }}</td>
									<td class="poppins-medium" style="font-size: 14px;" >
										: {{ $spec['value'] }}{{ $spec['unit'] ? ' ' . $spec['unit'] : '' }}
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					@endif

					{{-- 7. FASILITAS --}}
					@if (!empty($property['facilities']))
					<div class="mb-4 pb-4 border-bottom border-color-grey-1">
						<h4 class="poppins-semibold text-4 mb-3" style="font-size:24px" >Fasilitas</h4>

						{{-- Facility image slider --}}
						@if ($facilityImgCnt > 0)
						<div class="facility-slider owl-carousel mb-4">
							@foreach ($facilityImgs as $fi)
							<div>
								<div class="facility-slide-item">
									<img src="{{ url($fi['image_url']) }}" alt="{{ $fi['name'] }}">
									@if ($fi['name'] !== '-')
									<div class="facility-slide-caption">{{ $fi['name'] }}</div>
									@endif
								</div>
							</div>
							@endforeach
						</div>
						@endif

						{{-- Facility icon list --}}
						<div class="row">
							@foreach ($property['facilities'] as $fac)
							<div class="col-md-4 mb-3">
								<div class="facility-icon-item">
									@if (Str::startsWith($fac['icon'], ['fas ', 'fab ', 'far ', 'fal ', 'fad ']))
										<i class="{{ $fac['icon'] }}"></i>
									@elseif (Str::startsWith($fac['icon'], 'flaticon'))
										<i class="{{ $fac['icon'] }}"></i>
									@else
										<i class="fas fa-check-circle"></i>
									@endif
									<span class="poppins-regular" style="font-size:14px;" >{{ $fac['name'] }}</span>
								</div>
							</div>
							@endforeach
						</div>
					</div>
					@endif

					{{-- 8. LOKASI SEKITAR --}}
					@if (!empty($property['nearby']))
					<div class="mb-4 pb-4 border-bottom border-color-grey-1">
						<h4 class="poppins-semibold text-4 mb-3" style="font-size:24px;" >Lokasi Sekitar</h4>
						<div class="row">
							@php $half = (int) ceil(count($property['nearby']) / 2); @endphp
							<div class="col-md-6">
								<ul class="check-list mb-0">
									@foreach (array_slice($property['nearby'], 0, $half) as $loc)
										<li>{{ $loc }}</li>
									@endforeach
								</ul>
							</div>
							<div class="col-md-6">
								<ul class="check-list mb-0">
									@foreach (array_slice($property['nearby'], $half) as $loc)
										<li>{{ $loc }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
					@endif

					{{-- 9. LOKASI PROPERTI (MAP) --}}
					<div class="mb-5">
						<h4 class="poppins-semibold text-4 mb-3" style="font-size:24px;" >Lokasi Properti</h4>
						<div style="width: 100%; height: 350px; border-radius: 12px; overflow: hidden; background-color: #eee;">
							@if ($hasCoords)
								<iframe
									src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
									width="100%" height="100%" style="border:0;"
									allowfullscreen="" loading="lazy"
									referrerpolicy="no-referrer-when-downgrade">
								</iframe>
							@else
								<div class="d-flex flex-column align-items-center justify-content-center h-100" style="color: #bbb;">
									<i class="fas fa-map-marker-alt" style="font-size: 40px; margin-bottom: 12px;"></i>
									<p class="text-3 mb-0">Koordinat lokasi belum tersedia</p>
								</div>
							@endif
						</div>
					</div>

				</div>{{-- end col-lg-8 --}}

				{{-- ============================================================
				     SIDEBAR (RIGHT)
				     ============================================================ --}}
				@if(!request('embed'))
				<div class="col-lg-4">
					<div class="card border border-color-grey-1 promo-card">
						<div class="card-body p-4">

							<h4 class="poppins-semibold text-center mb-4 text-5" style="color: #1C5FA8;">Dapatkan Promo Sekarang</h4>
							<form action="{{ route('front.lead.store') }}" method="POST">
								@csrf
								<input type="hidden" name="property_id" value="{{ $property['property_id'] }}">
								<div class="mb-3">
									<select name="salutation" class="form-select text-3 py-2" style="background-color: #f8f9fa; border: none; border-radius: 8px; color: #555;">
										<option value="">Title</option>
										<option value="Bapak">Bapak</option>
										<option value="Ibu">Ibu</option>
									</select>
								</div>
								<div class="mb-3">
									<input type="text" name="fullname" class="form-control text-3 py-2" placeholder="Nama" style="background-color: #f8f9fa; border: none; border-radius: 8px;">
								</div>
								<div class="mb-3">
									<input name="phone_number" type="number" pattern="[\d\s\+\-\(\)]{6,20}" class="form-control text-3 py-2" placeholder="No. Telepon" style="background-color: #f8f9fa; border: none; border-radius: 8px;">
								</div>
								<div class="mb-4">
									<input type="email" name="email" class="form-control text-3 py-2" placeholder="Email" style="background-color: #f8f9fa; border: none; border-radius: 8px;">
								</div>
								@if(!request('embed'))
								<button type="submit"
								   class="btn w-100 font-weight-bold py-2 text-color-light d-flex align-items-center justify-content-center"
								   style="background-color: #61c97d; border-radius: 8px; border: none; font-size: 14px;">
								   <i class="fab fa-whatsapp me-2 text-4"></i> WhatsApp
								</button>
								@endif
							</form>
						</div>
					</div>
				</div>
				@endif
			</div>{{-- end row --}}

			{{-- ============================================================
			     PENCARIAN TERKAIT
			     ============================================================ --}}
			@if (!empty($relatedProperties))
			<div class="row mt-5 pt-4 border-top border-color-grey-1">
				<div class="col-12 mb-4">
					<h3 class="font-weight-bold text-5 text-color-dark">Pencarian Terkait</h3>
				</div>
				@foreach ($relatedProperties as $prop)
				<div class="col-lg-3 col-md-6 mb-4">
					<div class="card related-card border border-color-grey-1 bg-white h-100" style="cursor:pointer;" onclick="window.location='{{ $prop['detail_url'] }}{{ $embedSuffix }}'">
						<div class="position-relative p-2">
							<div class="position-absolute top-0 left-0 pt-3 ms-3 z-index-1">
								@foreach ($prop['badges'] as $badge)
									<span class="badge font-weight-semibold px-2 py-1 me-1"
										style="background-color: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; border-radius: 4px; font-size: 10px;">
										{{ $badge['text'] }}
									</span>
								@endforeach
							</div>
							<img src="{{ url($prop['image']) }}" class="img-fluid" alt="{{ $prop['title'] }}"
								style="border-radius: 8px; height: 180px; width: 100%; object-fit: cover;">
						</div>
						<div class="card-body px-3 py-2">
							<div class="d-flex justify-content-between align-items-center mb-1">
								<h4 class="font-weight-bold text-4 mb-0" style="color: #3b5998;">{{ $prop['price'] }}</h4>
								<a href="{{ $prop['detail_url'] }}{{ $embedSuffix }}" onclick="event.stopPropagation()">
									<i class="fas fa-arrow-right" style="color: #3b5998; font-size: 14px;"></i>
								</a>
							</div>
							<h5 class="font-weight-semibold text-3 mb-1 mt-2" style="line-height: 1.3; color: #333; height: 38px; overflow: hidden; font-size: 14px;">{{ $prop['title'] }}</h5>
							<p class="mb-2" style="font-size: 11px; color: #888;">{{ $prop['location'] }}</p>
							<div class="d-flex justify-content-between align-items-center mb-3"
								style="font-size: 11px; color: #666; padding-bottom: 10px; border-bottom: 1px solid #eee;">
								<div class="d-flex align-items-center">
									<i class="fas fa-bed me-1" style="color: #a0a0a0;"></i>
									<span class="font-weight-bold text-color-dark">{{ $prop['beds'] }}</span>
								</div>
								<div class="d-flex align-items-center">
									<i class="fas fa-bath me-1" style="color: #a0a0a0;"></i>
									<span class="font-weight-bold text-color-dark">{{ $prop['baths'] }}</span>
								</div>
								<div>LT <span class="font-weight-bold text-color-dark ms-1">{{ $prop['lt'] }}m²</span></div>
								<div>LB <span class="font-weight-bold text-color-dark ms-1">{{ $prop['lb'] }}m²</span></div>
							</div>
							@if(!request('embed'))
							<a href="#"
							   data-phone="{{ $prop['wa_phone'] ?? '' }}"
							   data-title="{{ $prop['title'] }}"
							   data-id="{{ $prop['property_id'] ?? '' }}"
							   data-url="{{ $prop['detail_url'] ?? '' }}"
							   onclick="event.preventDefault(); event.stopPropagation(); openWaModal(this.dataset.phone, this.dataset.title, this.dataset.id, this.dataset.url)"
							   class="btn w-100 font-weight-bold py-2 text-color-light"
							   style="background-color: #61c97d; border-radius: 8px; border: none; font-size: 13px;">
							   <i class="fab fa-whatsapp me-2 text-4"></i> WhatsApp
							</a>
							@endif
						</div>
					</div>
				</div>
				@endforeach
			</div>
			@endif

		</div>
	</div>

	@if(!request('embed'))
		@include('front.layout.footer')
	@endif

</div>
@include('partials.hubspot')


<script src="{{ asset('vendor/plugins/js/plugins.min.js') }}"></script>
<script src="{{ asset('js/theme.js') }}"></script>
<script src="{{ asset('js/views/view.home.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/theme.init.js') }}"></script>
<script src="{{ asset('vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('vendor/owl.carousel/owl.carousel.min.js') }}"></script>

<script>
(function($) {

    /* =============================================
       GALLERY OVERLAY
       ============================================= */
    function openGalleryOverlay() {
        document.getElementById('go-overlay').classList.add('show');
        document.body.style.overflow = 'hidden';
        // Reset to top and activate first category
        var gm = document.getElementById('go-main');
        if (gm) gm.scrollTop = 0;
        var firstCard = document.querySelector('.go-cat-card, .go-cat-card-icon');
        if (firstCard) setActiveCat(firstCard.dataset.cat);
    }
    function closeGalleryOverlay() {
        document.getElementById('go-overlay').classList.remove('show');
        document.body.style.overflow = '';
    }

    // Open on thumbnail click or "Lihat Semua"
    $('.gallery-img-wrap').on('click', function() { openGalleryOverlay(); });
    $('#open-gallery-btn').on('click', function() { openGalleryOverlay(); });
    document.getElementById('go-close').addEventListener('click', closeGalleryOverlay);

    /* ---- Sidebar: click → smooth-scroll to panel ---- */
    var goMain = document.getElementById('go-main');

    function setActiveCat(cat) {
        document.querySelectorAll('.go-cat-card, .go-cat-card-icon').forEach(function(c) {
            c.classList.toggle('active', c.dataset.cat === cat);
        });
        // On mobile, scroll the tab strip so the active card is visible
        var activeCard = document.querySelector('.go-cat-card[data-cat="' + cat + '"], .go-cat-card-icon[data-cat="' + cat + '"]');
        if (activeCard && window.innerWidth < 768) {
            activeCard.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }

    document.querySelectorAll('.go-cat-card, .go-cat-card-icon').forEach(function(card) {
        card.addEventListener('click', function() {
            var cat = this.dataset.cat;
            var panel = document.getElementById('panel-' + cat);
            if (!panel) return;
            setActiveCat(cat);
            goMain.scrollTo({ top: panel.offsetTop - 8, behavior: 'smooth' });
        });
    });

    /* ---- Scroll → auto-highlight sidebar ---- */
    var scrollTimer = null;
    goMain.addEventListener('scroll', function() {
        if (scrollTimer) return; // throttle to ~60fps
        scrollTimer = requestAnimationFrame(function() {
            scrollTimer = null;
            var scrollTop = goMain.scrollTop;
            var panels = document.querySelectorAll('.go-panel');
            var activeCat = null;
            panels.forEach(function(panel) {
                if (panel.offsetTop - 32 <= scrollTop) {
                    activeCat = panel.dataset.cat;
                }
            });
            if (activeCat) setActiveCat(activeCat);
        });
    });

    /* =============================================
       LIGHTBOX
       ============================================= */
    var lbItems = [];
    var lbIdx   = 0;

    function buildLbItems(group) {
        lbItems = [];
        document.querySelectorAll('.go-grid-item[data-lb-group="' + group + '"]').forEach(function(el) {
            lbItems.push({ src: el.dataset.lbSrc, cap: el.dataset.lbCap });
        });
    }
    function showLb(idx) {
        lbIdx = Math.max(0, Math.min(idx, lbItems.length - 1));
        document.getElementById('go-lb-img').src = lbItems[lbIdx].src;
        document.getElementById('go-lb-cap').textContent = lbItems[lbIdx].cap;
        document.getElementById('go-lightbox').classList.add('show');
    }
    function closeLb() { document.getElementById('go-lightbox').classList.remove('show'); }

    document.querySelectorAll('.go-grid-item').forEach(function(el) {
        el.addEventListener('click', function() {
            buildLbItems(this.dataset.lbGroup);
            showLb(parseInt(this.dataset.lbIdx) || 0);
        });
    });
    document.getElementById('go-lb-close').addEventListener('click', closeLb);
    document.getElementById('go-lb-prev').addEventListener('click', function() { showLb(lbIdx - 1); });
    document.getElementById('go-lb-next').addEventListener('click', function() { showLb(lbIdx + 1); });
    document.getElementById('go-lightbox').addEventListener('click', function(e) { if (e.target === this) closeLb(); });

    /* =============================================
       FACILITY SLIDER – Owl Carousel
       ============================================= */
    var facilityImgCnt = {{ $facilityImgCnt }};
    if (facilityImgCnt > 0) {
        $('.facility-slider').owlCarousel({
            items:    1,
            loop:     facilityImgCnt > 1,
            margin:   0,
            nav:      true,
            dots:     facilityImgCnt > 1,
            autoplay: facilityImgCnt > 1,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            smartSpeed: 500,
            navText: [
                '<i class="fas fa-chevron-left"></i>',
                '<i class="fas fa-chevron-right"></i>'
            ]
        });
    }

})(jQuery);
</script>

@include('front.partials.whatsapp-modal')
</body>
</html>
