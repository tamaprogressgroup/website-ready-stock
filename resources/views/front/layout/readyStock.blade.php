<!DOCTYPE html>
<html lang="en">

<head>

	<!-- Basic -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>{{ $pageSeo?->meta_title ?? 'Paradise Ready Stock' }}</title>

	<meta name="title" content="{{ $pageSeo?->meta_title ?? '' }}" />
	<meta name="keywords" content="{{ $pageSeo?->meta_keyword ?? '' }}" />
	<meta name="description" content="{{ $pageSeo?->meta_description ?? '' }}">
	<meta name="author" content="paradise.co.id">
	<meta property="og:title" content="{{ $pageSeo?->og_title ?? $pageSeo?->meta_title ?? '' }}">
	<meta property="og:description" content="{{ $pageSeo?->og_description ?? $pageSeo?->meta_description ?? '' }}">
	<meta property="og:type" content="website">


	<!-- Favicon -->
	<link rel="shortcut icon" href="" type="image/x-icon" />
	<link rel="apple-touch-icon" href="img/apple-touch-icon.png">

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

	<!-- Web Fonts  -->
	<link id="googleFonts"
  href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100;300;400;500;700&family=Lato:wght@100;300;400;700;900&family=Open+Sans:wght@300;400;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik:wght@300;400;500;700;900&family=Shadows+Into+Light&display=swap"
  rel="stylesheet" type="text/css">







	<!-- Theme CSS -->
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/theme.css') }}">
	<link rel="stylesheet" href="{{ asset('css/theme-elements.css') }}">
	<link rel="stylesheet" href="{{ asset('css/theme-blog.css') }}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
	{{-- <link rel="stylesheet" href="{{ asset('css/theme-shop.css') }}"> --}}



	<!-- Current Page CSS -->
	<link rel="stylesheet" href="{{ asset('vendor/circle-flip-slideshow/css/component.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/owl.carousel/assets/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/owl.carousel/assets/owl.theme.default.min.css') }}">

	<!-- Skin CSS -->
	<link id="skinCSS" rel="stylesheet" href="{{ asset('css/skins/default.css') }}">

	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

	@include('partials.google_tag')
	@include('partials.facebook_pixel')

</head>

<body data-plugin-page-transition>
 @include('partials.google_tag_iframe')

	<div class="body">

		@include('front.layout.navbar')

		@include('front.partials.ready-stock-content')

		@include('front.layout.footer')

	</div>
	@include('partials.hubspot')


	<!-- Vendor -->
	<script src="{{ asset('vendor/plugins/js/plugins.min.js') }}"></script>

	<!-- Theme Base, Components and Settings -->
	<script src="{{ asset('js/theme.js') }}"></script>

	<!-- Current Page Views -->
	<script src="{{ asset('js/views/view.home.js') }}"></script>

	<!-- Theme Custom -->
	<script src="{{ asset('js/custom.js') }}"></script>

	<!-- Theme Initialization Files -->
	<script src="{{ asset('js/theme.init.js') }}"></script>

	<!-- Carousel Init -->
	<script>
	(function ($) {
		var carouselOptions = {
			items: 4,
			loop: false,
			nav: false,
			dots: false,
			margin: 16,
			responsive: {
				0:   { items: 1 },
				576: { items: 2 },
				992: { items: 3 },
				1200:{ items: 4 }
			}
		};

		var $reco = $('#carousel-reco');
		var $newP = $('#carousel-new');

		if ($reco.length) {
			$reco.owlCarousel(carouselOptions);
			$('#btn-prev-rec').on('click', function () { $reco.trigger('prev.owl.carousel'); });
			$('#btn-next-rec').on('click', function () { $reco.trigger('next.owl.carousel'); });
		}

		if ($newP.length) {
			$newP.owlCarousel(carouselOptions);
			$('#btn-prev-new').on('click', function () { $newP.trigger('prev.owl.carousel'); });
			$('#btn-next-new').on('click', function () { $newP.trigger('next.owl.carousel'); });
		}
	}(jQuery));
	</script>



</body>

</html>