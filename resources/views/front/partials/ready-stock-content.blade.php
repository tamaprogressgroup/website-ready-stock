<style>
.carousel-nav-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.2s, color 0.2s;
}
.carousel-nav-prev {
    background: #f0f0f0;
    border: none;
    color: #aaa;
}
.carousel-nav-next {
    background: #fff;
    border: 1px solid #ddd;
    color: #333;
}
.carousel-nav-btn:hover {
    background: #3b5998;
    border-color: #3b5998;
    color: #fff;
}
.owl-carousel .owl-item { padding: 0 6px; }
.property-card-img { border-radius: 12px; height: 210px; width: 100%; object-fit: cover; display: block; }
.property-card-price { color: #3b5998; font-weight: 700; font-size: 15px; }
.property-card-title { font-weight: 700; font-size: 13px; color: #333; line-height: 1.4; height: 40px; overflow: hidden; }
.property-card-loc { font-size: 11px; color: #999; margin-bottom: 10px; }
.property-card-specs { font-size: 12px; color: #666; }
.project-card { border-radius: 16px; min-height: 220px; background-size: cover; background-position: center; position: relative; overflow: hidden; transition: transform 0.25s; }
.project-card:hover { transform: translateY(-4px); }
.project-card-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 40%, rgba(0,0,0,0.75) 100%); }
.project-card-body { position: absolute; bottom: 0; left: 0; right: 0; padding: 20px; }
</style>

<div role="main" class="main">

    {{-- ===================== HERO ===================== --}}
    @php
        $heroBg = !empty($bannerTop['image_url'])
            ? asset('storage/' . $bannerTop['image_url'])
            : asset('stock-image/dashboard.png');
    @endphp
    <section class="section section-concept section-no-border section-dark section-angled section-angled-reverse pt-5 m-0"
             style="background-image: url('{{ $heroBg }}'); background-size: cover; background-position: center; min-height: 520px; position: relative;">
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0.15) 60%, rgba(0,0,0,0.55) 100%);"></div>

        {{-- Search Bar --}}
        <div class="container" style="position: absolute; bottom: 32px; left: 50%; transform: translateX(-50%); z-index: 10; width: 100%; max-width: 960px; padding: 0 15px;">
            <div class="card border-0" style="border-radius: 20px; background-color: rgba(225, 230, 235, 0.88); backdrop-filter: blur(6px);">
                <div class="card-body p-3">
                    {{-- Form does NOT submit normally — JS intercepts and builds SEO URL --}}
                    <form id="home-search-form" class="mb-0" onsubmit="return homeSearchSubmit(event)">
                        <div class="row gx-2 align-items-center">

                            {{-- Kondisi --}}
                            <div class="col-12 col-lg mb-2 mb-lg-0">
                                <select id="hs-condition" class="form-select border-0 font-weight-semibold text-color-dark"
                                        style="border-radius: 10px; background-color: #f8f9fa; height: 48px; font-size: 13px;">
                                    <option value="">Kondisi</option>
                                    @foreach ($propertyConditions as $cond)
                                        <option value="{{ $cond['slug'] }}">
                                            {{ $cond['translations'][0]['condition_name'] ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tipe --}}
                            <div class="col-12 col-lg mb-2 mb-lg-0">
                                <select id="hs-type" class="form-select border-0 font-weight-semibold text-color-dark"
                                        style="border-radius: 10px; background-color: #f8f9fa; height: 48px; font-size: 13px;">
                                    <option value="">Tipe Properti</option>
                                    @foreach ($propertyTypes as $type)
                                        <option value="{{ $type['slug'] }}">
                                            {{ $type['translations'][0]['type_name'] ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Lokasi (kota) --}}
                            <div class="col-12 col-lg mb-2 mb-lg-0">
                                <select id="hs-kota" class="form-select border-0 font-weight-semibold text-color-dark"
                                        style="border-radius: 10px; background-color: #f8f9fa; height: 48px; font-size: 13px;">
                                    <option value="">Lokasi</option>
                                    @foreach ($kotasWithProperties as $k)
                                        <option value="{{ $k['slug'] }}">{{ $k['nama_kota'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Proyek (township) — data-kota-slug for URL building --}}
                            <div class="col-12 col-lg mb-2 mb-lg-0">
                                <select id="hs-township" class="form-select border-0 font-weight-semibold text-color-dark"
                                        style="border-radius: 10px; background-color: #f8f9fa; height: 48px; font-size: 13px;">
                                    <option value="">Proyek</option>
                                    @foreach ($townships as $t)
                                        <option value="{{ $t['township_slug'] }}"
                                                data-kota="{{ $t['kota_slug'] }}">
                                            {{ $t['township_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Harga --}}
                            <div class="col-12 col-lg mb-2 mb-lg-0">
                                <select id="hs-price" class="form-select border-0 font-weight-semibold text-color-dark"
                                        style="border-radius: 10px; background-color: #f8f9fa; height: 48px; font-size: 13px;">
                                    <option value="">Range Harga</option>
                                    <option value="above_1b">&gt; Rp 1 M</option>
                                    <option value="below_1b">&lt; Rp 1 M</option>
                                </select>
                            </div>

                            <div class="col-12 col-lg">
                                <button type="submit" class="btn btn-primary w-100 poppins-extrabold d-flex align-items-center justify-content-center"
                                        style="background-color: #3b5998; border-color: #3b5998; border-radius: 10px; height: 48px; width: 200px !important; font-size: 13px; letter-spacing: 0.5px;">
                                    <i class="fas fa-search me-2"></i> CARI PROPERTI
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        function homeSearchSubmit(e) {
            e.preventDefault();

            var cond     = document.getElementById('hs-condition').value;
            var type     = document.getElementById('hs-type').value;
            var kota     = document.getElementById('hs-kota').value;
            var twnSel   = document.getElementById('hs-township');
            var twn      = twnSel.value;
            var twnKota  = twnSel.options[twnSel.selectedIndex]?.dataset?.kota || '';
            var price    = document.getElementById('hs-price').value;

            // If township is selected, its kota overrides the kota dropdown
            if (twn && twnKota) kota = twnKota;

            // Build SEO path from left: condition → type → kota → township
            // Each level requires the previous level to be filled
            var path = '/all-products';
            if (cond)                    path = '/' + cond;
            if (cond && type)            path = '/' + cond + '/' + type;
            if (cond && type && kota)    path = '/' + cond + '/' + type + '/' + kota;
            if (cond && type && kota && twn) path = '/' + cond + '/' + type + '/' + kota + '/' + twn;

            // Append query params (price, etc.)
            var params = new URLSearchParams();
            if (price) params.set('price', price);
            var qs = params.toString();

            window.location.href = path + (qs ? '?' + qs : '');
            return false;
        }
        </script>
    </section>

    {{-- ===================== REKOMENDASI PROPERTI ===================== --}}
    <div class="container py-5 mt-3" id="rekomendasi-properti" style="scroll-margin-top: 80px;">
        <div class="row align-items-center mb-4">
            <div class="col-8">
                <h2 class="font-weight-bold text-6 mb-0 text-color-dark poppins-semibold" style="font-size: 25px;">Rekomendasi Properti</h2>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center gap-2">
                <a href="{{ url('/all-products') }}" class="text-decoration-none poppins-semibold me-1" style="color: #3b5998; font-size: 13px;">Lihat Lainnya</a>
                <button id="btn-prev-rec" class="carousel-nav-btn carousel-nav-prev">
                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                </button>
                <button id="btn-next-rec" class="carousel-nav-btn carousel-nav-next">
                    <i class="fas fa-chevron-right" style="font-size: 11px;"></i>
                </button>
            </div>
        </div>

        @if(count($recommendations) > 0)
        <div class="owl-carousel" id="carousel-reco">
            @foreach ($recommendations as $prop)
            <div class="px-1">
                @include('front.partials.property-card', ['prop' => $prop])
            </div>
            @endforeach
        </div>
        @else
        <p class="text-color-grey text-3 text-center py-4">Belum ada properti tersedia.</p>
        @endif
    </div>

    {{-- ===================== PROPERTI BARU ===================== --}}
    <div class="container pb-5" id="properti-baru" style="scroll-margin-top: 80px;">
        <div class="row align-items-center mb-4">
            <div class="col-8">
                <h2 class="poppins-semibold text-6 mb-0 text-color-dark" style="font-size: 25px;">Properti Baru - Siap Huni</h2>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center gap-2">
                <a href="{{ url('/all-products') }}" class="text-decoration-none poppins-extrabold me-1" style="color: #1C5FA8; font-size: 13px;">Lihat Lainnya</a>
                <button id="btn-prev-new" class="carousel-nav-btn carousel-nav-prev">
                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                </button>
                <button id="btn-next-new" class="carousel-nav-btn carousel-nav-next">
                    <i class="fas fa-chevron-right" style="font-size: 11px;"></i>
                </button>
            </div>
        </div>

        @if(count($newProperties) > 0)
        <div class="owl-carousel" id="carousel-new">
            @foreach ($newProperties as $prop)
            <div class="px-1">
                @include('front.partials.property-card', ['prop' => $prop])
            </div>
            @endforeach
        </div>
        @else
        <p class="text-color-grey text-3 text-center py-4">Belum ada properti baru.</p>
        @endif

        <div class="text-center mt-5">
            <a href="{{ url('/all-products') }}" class="btn btn-primary font-weight-bold px-5 py-3"
               style="background-color: #3b5998; border-color: #3b5998; border-radius: 10px; font-size: 14px;">
                LIHAT LAINNYA
            </a>
        </div>
    </div>

    {{-- ===================== PROPERTI BY PROJECT ===================== --}}
    @if(count($townships) > 0)
    <div class="container pb-5 mb-3" id="properti-by-project" style="scroll-margin-top: 80px;">
        <h2 class="poppins-semibold text-6 mb-4" style="font-size: 25px;">Properti by Project</h2>
        <div class="row g-3">
            @foreach ($townships as $t)
            @php
                $bgImg = !empty($t['image'])
                    ? asset('storage/' . $t['image'])
                    : asset('stock-image/dashboard.png');
            @endphp
            <div class="col-lg-3 col-md-6">
                <a href="{{ url('/all-products?township=' . $t['township_id']) }}" class="text-decoration-none">
                    <div class="position-relative overflow-hidden" style="border-radius: 16px; height: 387px; background-image: url('{{ $bgImg }}'); background-size: cover; background-position: center;">
                        
                        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0) 50%); border-radius: 16px;"></div>
                        
                        <div class="position-absolute w-100 p-4 d-flex flex-column justify-content-start ">
                            <h4 class="roboto-medium" style="font-size: 19px; color: #ffffff; margin-bottom: 0;">{{ $t['township_name'] }}</h4>
                            <p class="mb-0 roboto-regular" style="font-size: 15px; color: #ffffff;">
                                {{ $t['unit_count'] }} Properti
                            </p>
                        </div>
                        
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
