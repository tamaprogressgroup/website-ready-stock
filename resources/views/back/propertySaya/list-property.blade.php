@extends('back.layout.app')

@section('content')
<style>
    .search-wrapper { background:#fff; border:1px solid #e0e0e0; border-radius:8px; padding:5px; display:flex; align-items:center; }
    .search-wrapper input { border:none; box-shadow:none; }
    .search-wrapper input:focus { border:none; box-shadow:none; }
    .btn-cari { background-color:#2b4c8a; color:white; border-radius:6px; padding:8px 25px; font-weight:600; border:none; }
    .btn-cari:hover { background-color:#1e3663; color:white; }
    .btn-pasang { background-color:#2b4c8a; color:white; font-weight:600; border-radius:8px; padding:10px 20px; }
    .btn-pasang:hover { background-color:#1e3663; color:white; }
    .tabs-wrapper { background:#fff; border-radius:25px; padding:5px; border:1px solid #e0e0e0; margin-bottom:25px; }
    .nav-pills .nav-link { color:#8c98a4; border-radius:20px; font-weight:600; padding:10px 0; border:none; background:transparent; cursor:pointer; }
    .nav-pills .nav-link.active { background-color:#2b4c8a; color:white; }
    .filter-pill { border:1px solid #e0e0e0; border-radius:20px; padding:8px 15px; color:#495057; font-size:14px; background:white; display:inline-flex; align-items:center; gap:8px; cursor:pointer; outline:none; white-space:nowrap; text-decoration:none; transition:all 0.15s; }
    .filter-pill:hover { border-color:#2b4c8a; color:#2b4c8a; }
    .filter-reset { border-color:#fee2e2; background:#fff5f5; color:#dc3545; }
    .filter-reset:hover { border-color:#dc3545; }
    /* Select wrapper pill */
    .filter-select-wrap { position:relative; display:inline-flex; align-items:center; gap:6px; border:1px solid #e0e0e0; border-radius:20px; padding:0 12px; background:#fff; color:#495057; font-size:14px; transition:border-color 0.15s, background 0.15s; cursor:pointer; }
    .filter-select-wrap i.icon-left { font-size:13px; color:#8c98a4; flex-shrink:0; }
    .filter-select-wrap i.icon-right { font-size:10px; color:#8c98a4; flex-shrink:0; }
    .filter-select-wrap select { border:none; background:transparent; outline:none; -webkit-appearance:none; -moz-appearance:none; appearance:none; padding:8px 0; color:inherit; font-size:inherit; font-weight:inherit; cursor:pointer; max-width:140px; }
    .filter-select-wrap:hover { border-color:#2b4c8a; }
    .filter-select-wrap.is-active { background:#eaf1fb; border-color:#2b4c8a; color:#2b4c8a; font-weight:600; }
    .filter-select-wrap.is-active i { color:#2b4c8a; }
    .card-property { background:white; border:1px solid #e0e0e0; border-radius:12px; padding:20px; margin-bottom:20px; }
    .property-img-container { position:relative; border-radius:8px; overflow:hidden; }
    .property-img { width:100%; height:200px; object-fit:cover; }
    .property-img-placeholder { width:100%; height:200px; background:#f0f4f8; display:flex; align-items:center; justify-content:center; border-radius:8px; color:#adb5bd; font-size:40px; }
    .property-title { font-size:16px; font-weight:700; color:#1a1a1a; line-height:1.4; margin:10px 0; }
    .property-price { font-size:16px; font-weight:700; color:#2b4c8a; }
    .property-features { display:flex; gap:15px; font-size:13px; color:#495057; margin-bottom:15px; flex-wrap:wrap; }
    .badge-status-draft   { background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600; }
    .badge-status-tayang  { background:#d1fae5; color:#065f46; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600; }
    .badge-status-tunda   { background:#fee2e2; color:#991b1b; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600; }
    .badge-status-terjual { background:#dbeafe; color:#1e40af; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600; }
    .btn-dot { background:white; border:1px solid #e0e0e0; border-radius:6px; width:38px; height:38px; display:inline-flex; align-items:center; justify-content:center; color:#6c757d; cursor:pointer; }
    .btn-dot.dropdown-toggle::after { display:none; }
    .section-subtitle { font-size:13px; color:#8c98a4; margin-bottom:20px; }
    .empty-state { text-align:center; padding:60px 20px; color:#adb5bd; }
</style>

<div class="container-fluid py-4" style="max-width:1200px;">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- Search + Create button --}}
    <form method="GET" action="{{ route('customer.property') }}">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="row align-items-center mb-3">
            <div class="col-md-7 mb-3 mb-md-0">
                <div class="search-wrapper">
                    <div class="px-3 text-muted"><i class="fas fa-search"></i></div>
                    <input type="text" name="search" class="form-control" placeholder="Cari properti..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-cari">Cari</button>
                </div>
            </div>
            <div class="col-md-5 d-flex justify-content-md-end align-items-center gap-2">
                <a href="{{ route('customer.property.create') }}" class="btn btn-pasang text-decoration-none">
                    <i class="fas fa-plus me-2"></i> Buat Properti
                </a>
            </div>
        </div>

        {{-- Filter row --}}
        @php
            $activeFilters = array_filter(request()->only(['search','property_type_id','cluster_id','township_id','condition_id','bedrooms','price_range']));
            $priceOptions  = ['lt500'=>'< 500 Juta','500to1b'=>'500 Jt - 1 M','1bto2b'=>'1 - 2 Miliar','2bto5b'=>'2 - 5 Miliar','gt5b'=>'> 5 Miliar'];
        @endphp
        <div class="d-flex gap-2 mb-4 flex-wrap align-items-center">

            {{-- Tipe Properti --}}
            <label class="filter-select-wrap {{ request('property_type_id') ? 'is-active' : '' }}">
                <i class="fas fa-home icon-left"></i>
                <select name="property_type_id" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    @foreach($propertyTypes as $pt)
                        <option value="{{ $pt->property_type_id }}" {{ request('property_type_id') == $pt->property_type_id ? 'selected' : '' }}>
                            {{ $pt->translations->first()?->type_name ?? '-' }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down icon-right"></i>
            </label>

            {{-- Proyek / Township --}}
            @if($townships->isNotEmpty())
                <label class="filter-select-wrap {{ request('township_id') ? 'is-active' : '' }}">
                    <i class="fas fa-map-marked-alt icon-left"></i>
                    <select name="township_id" onchange="this.form.submit()">
                        <option value="">Semua Proyek</option>
                        @foreach($townships as $twn)
                            <option value="{{ $twn->township_id }}" {{ request('township_id') == $twn->township_id ? 'selected' : '' }}>
                                {{ $twn->township_name }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down icon-right"></i>
                </label>
            @endif

            {{-- Cluster --}}
            @if($clusters->isNotEmpty())
                <label class="filter-select-wrap {{ request('cluster_id') ? 'is-active' : '' }}">
                    <i class="fas fa-layer-group icon-left"></i>
                    <select name="cluster_id" onchange="this.form.submit()">
                        <option value="">Semua Cluster</option>
                        @foreach($clusters as $cl)
                            <option value="{{ $cl->cluster_id }}" {{ request('cluster_id') == $cl->cluster_id ? 'selected' : '' }}>
                                {{ $cl->cluster_name }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down icon-right"></i>
                </label>
            @endif

            {{-- Kondisi --}}
            @if($conditions->isNotEmpty())
                <label class="filter-select-wrap {{ request('condition_id') ? 'is-active' : '' }}">
                    <i class="fas fa-clipboard-check icon-left"></i>
                    <select name="condition_id" onchange="this.form.submit()">
                        <option value="">Semua Kondisi</option>
                        @foreach($conditions as $cond)
                            <option value="{{ $cond->property_condition_id }}" {{ request('condition_id') == $cond->property_condition_id ? 'selected' : '' }}>
                                {{ $cond->translations->first()?->condition_name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down icon-right"></i>
                </label>
            @endif

            {{-- Kamar Tidur --}}
            <label class="filter-select-wrap {{ request('bedrooms') ? 'is-active' : '' }}">
                <i class="fas fa-bed icon-left"></i>
                <select name="bedrooms" onchange="this.form.submit()">
                    <option value="">Semua KT</option>
                    @foreach([1=>'1 Kamar Tidur',2=>'2 Kamar Tidur',3=>'3 Kamar Tidur',4=>'4+ Kamar Tidur'] as $num => $lbl)
                        <option value="{{ $num }}" {{ request('bedrooms') == $num ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down icon-right"></i>
            </label>

            {{-- Harga --}}
            <label class="filter-select-wrap {{ request('price_range') ? 'is-active' : '' }}">
                <i class="fas fa-tag icon-left"></i>
                <select name="price_range" onchange="this.form.submit()">
                    <option value="">Semua Harga</option>
                    @foreach($priceOptions as $val => $lbl)
                        <option value="{{ $val }}" {{ request('price_range') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down icon-right"></i>
            </label>

            {{-- Reset --}}
            @if(count($activeFilters))
                <a href="{{ route('customer.property', ['tab' => $tab]) }}" class="filter-pill filter-reset">
                    <i class="fas fa-times"></i> Reset
                    <span class="badge bg-danger rounded-pill" style="font-size:10px;">{{ count($activeFilters) }}</span>
                </a>
            @endif
        </div>
    </form>

    {{-- Tabs --}}
    <div class="tabs-wrapper">
        <ul class="nav nav-pills d-flex w-100 m-0 p-0" style="list-style:none;">
            @php
                $tabs = [
                    'draft'   => 'Draft',
                    'tayang'  => 'Tayang',
                    'tunda'   => 'Tunda',
                    'terjual' => 'Terjual',
                ];
            @endphp
            @foreach($tabs as $key => $label)
                <li class="nav-item flex-fill text-center">
                    <a class="nav-link d-block w-100 text-decoration-none {{ $tab === $key ? 'active' : '' }}"
                       href="{{ route('customer.property', array_merge(request()->except(['tab','page']), ['tab' => $key])) }}">
                        {{ $label }}
                        <span class="ms-1 badge rounded-pill {{ $tab === $key ? 'bg-white text-primary' : 'bg-secondary text-white' }}" style="font-size:11px;">{{ $counts[$key] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Property list --}}
    @if($items->isEmpty())
        <div class="empty-state">
            <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
            <h5>Belum ada properti dengan status ini.</h5>
            @if($tab === 'draft')
                <a href="{{ route('customer.property.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus me-1"></i> Buat Properti Baru
                </a>
            @endif
        </div>
    @else
        <div class="section-subtitle">
            {{ $items->total() }} properti ditemukan &nbsp;|&nbsp; Halaman {{ $items->currentPage() }} dari {{ $items->lastPage() }}
        </div>

        @foreach($items as $item)
            @php
                $trans   = $item->translations->first();
                $thumb   = $item->interiors->first();
                $typeTrans = $item->propertyType?->translations->first();
                $statusLabels = [0 => 'Draft', 1 => 'Tayang', 2 => 'Tunda', 3 => 'Terjual'];
                $statusClasses = [0 => 'badge-status-draft', 1 => 'badge-status-tayang', 2 => 'badge-status-tunda', 3 => 'badge-status-terjual'];
            @endphp
            <div class="card-property shadow-sm">
                <div class="row">
                    <div class="col-md-3 mb-3 mb-md-0">
                        @if($thumb)
                            <div class="property-img-container">
                                <img src="{{ Storage::url($thumb->image) }}" alt="thumbnail" class="property-img">
                            </div>
                        @else
                            <div class="property-img-placeholder"><i class="fas fa-image"></i></div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="{{ $statusClasses[$item->status_id] ?? 'badge-status-draft' }}">
                                    {{ $statusLabels[$item->status_id] ?? '-' }}
                                </span>
                                @if($typeTrans)
                                    <span class="badge bg-light text-secondary border" style="font-size:11px; font-weight:500;">
                                        {{ $typeTrans->type_name }}
                                    </span>
                                @endif
                                @if($item->township)
                                    <span class="badge bg-light text-secondary border" style="font-size:11px; font-weight:500;">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $item->township->township_name }}
                                    </span>
                                @endif
                                @if($item->cluster)
                                    <span class="badge bg-light text-secondary border" style="font-size:11px; font-weight:500;">
                                        <i class="fas fa-building me-1"></i>{{ $item->cluster->cluster_name }}
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="text-muted" style="font-size:11px;">
                                    {{ $item->created_datetime ? \Carbon\Carbon::parse($item->created_datetime)->format('d M Y') : '-' }}
                                </span>
                            </div>
                        </div>

                        <h3 class="property-title">{{ $trans?->title ?? '(Tanpa Judul)' }}</h3>

                        <div class="mb-2">
                            <span class="property-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            @if($item->diskon > 0)
                                <span class="text-muted ms-2" style="font-size:12px;">Diskon: Rp {{ number_format($item->diskon, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <div class="property-features">
                            <div><i class="fas fa-bed text-muted me-1"></i> {{ $item->bedrooms ?? 0 }} KT</div>
                            <div><i class="fas fa-bath text-muted me-1"></i> {{ $item->bathroom ?? 0 }} KM</div>
                            @if($item->land_area) <div>LT: {{ $item->land_area }} m²</div> @endif
                            @if($item->building_area) <div>LB: {{ $item->building_area }} m²</div> @endif
                        </div>

                        {{-- Action buttons --}}
                        <div class="d-flex gap-2 flex-wrap">
                            @if($item->status_id !== 3)
                                <a href="{{ route('customer.property.edit', $item->property_id) }}"
                                   class="btn btn-sm btn-outline-primary" style="border-radius:6px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                            @endif

                            @if($item->status_id === 0)
                                <form action="{{ route('customer.property.status', $item->property_id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status_id" value="1">
                                    <button type="submit" class="btn btn-sm btn-success" style="border-radius:6px;">
                                        <i class="fas fa-eye me-1"></i> Tayangkan
                                    </button>
                                </form>
                                <form action="{{ route('customer.property.destroy', $item->property_id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus properti ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:6px;">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            @elseif($item->status_id === 1)
                                <form action="{{ route('customer.property.status', $item->property_id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status_id" value="2">
                                    <button type="submit" class="btn btn-sm btn-warning" style="border-radius:6px;">
                                        <i class="fas fa-pause me-1"></i> Tunda
                                    </button>
                                </form>
                                <form action="{{ route('customer.property.status', $item->property_id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Tandai properti ini sebagai Terjual?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status_id" value="3">
                                    <button type="submit" class="btn btn-sm btn-secondary" style="border-radius:6px;">
                                        <i class="fas fa-handshake me-1"></i> Tandai Terjual
                                    </button>
                                </form>
                            @elseif($item->status_id === 2)
                                <form action="{{ route('customer.property.status', $item->property_id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status_id" value="1">
                                    <button type="submit" class="btn btn-sm btn-success" style="border-radius:6px;">
                                        <i class="fas fa-eye me-1"></i> Tayangkan Lagi
                                    </button>
                                </form>
                            @elseif($item->status_id === 3)
                                <span class="text-muted" style="font-size:12px;"><i class="fas fa-lock me-1"></i> Properti terjual tidak dapat diubah</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mt-3">{{ $items->links() }}</div>
    @endif
</div>
@endsection
