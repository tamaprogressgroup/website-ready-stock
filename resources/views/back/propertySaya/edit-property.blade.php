@extends('back.layout.app')

@section('content')
<style>
    .section-card { background:#fff; border:1px solid #e9ecef; border-radius:12px; padding:28px; margin-bottom:24px; }
    .section-heading { font-size:16px; font-weight:700; color:#1a1a1a; padding-bottom:14px; border-bottom:1px solid #f0f0f0; margin-bottom:20px; }
    .radio-card input[type="radio"] { display:none; }
    .radio-card label { border:1px solid #ced4da; border-radius:6px; padding:8px 16px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; color:#4b5563; font-size:13px; background:#fff; margin-bottom:0; }
    .radio-card input[type="radio"]:checked+label { border-color:#0d6efd; background:#f4f8ff; color:#0d6efd; font-weight:600; }
    .custom-tag { background:#e5e7eb; color:#4b5563; padding:6px 16px; border-radius:20px; font-size:13px; font-weight:500; display:inline-flex; align-items:center; gap:8px; }
    .custom-tag .remove-tag { cursor:pointer; font-size:11px; opacity:0.7; }
    .custom-tag .remove-tag:hover { opacity:1; }
    .tag-container { display:flex; flex-wrap:wrap; gap:10px; margin-top:12px; }
    .label-toggle-item { display:inline-flex; align-items:stretch; border-radius:8px; overflow:hidden; border:2px solid #dee2e6; transition:border-color 0.18s; }
    .label-toggle-item.is-on { border-color:var(--chip-color); }
    .label-toggle-badge { padding:8px 16px; color:#fff; font-weight:700; font-size:13px; white-space:nowrap; }
    .label-toggle-btn { padding:8px 18px; border:none; font-weight:700; font-size:13px; cursor:pointer; background:#f3f4f6; color:#9ca3af; transition:all 0.18s; white-space:nowrap; }
    .label-toggle-item.is-on .label-toggle-btn { background:var(--chip-color); color:#fff; }
    .label-toggle-item.is-disabled .label-toggle-btn { opacity:0.4; cursor:not-allowed; }
    .btn-remove-row { border:1px solid #ced4da; color:#dc3545; background:#fff; border-radius:4px; }
    .btn-remove-row:hover { background:#dc3545; color:#fff; }
    .btn-add-dynamic { background:#f8f9fa; border:1px solid #ced4da; color:#212529; font-weight:600; font-size:13px; padding:8px 16px; border-radius:4px; }
    .img-thumb-current { width:140px; height:90px; object-fit:cover; border-radius:8px; border:1px solid #eee; }
    .gallery-card { background:#f8f9fa; border:1px solid #e0e0e0; border-radius:8px; padding:10px; position:relative; }
    .gallery-card img { width:100%; height:80px; object-fit:cover; border-radius:6px; }
    .gallery-card .del-btn { position:absolute; top:6px; right:6px; background:#dc3545; color:#fff; border:none; border-radius:4px; padding:2px 7px; font-size:11px; cursor:pointer; }
    .facility-row { background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:12px; margin-bottom:10px; }
    .icon-preview { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; background:#f3f4f6; border-radius:6px; font-size:16px; color:#374151; }
    .fac-img-thumb { width:60px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #dee2e6; }
    .import-btn { background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff; border:none; border-radius:8px; padding:9px 20px; font-weight:600; font-size:13px; cursor:pointer; transition:opacity 0.2s; }
    .import-btn:hover { opacity:0.88; color:#fff; }
    .icon-picker-btn { display:flex; align-items:center; gap:8px; padding:6px 10px; border:1px solid #ced4da; border-radius:6px; background:#fff; color:#374151; font-size:13px; cursor:pointer; transition:border-color 0.15s; white-space:nowrap; overflow:hidden; width:100%; }
    .icon-picker-btn:hover { border-color:#0d6efd; background:#f4f8ff; }
    .icon-picker-btn i { font-size:16px; flex-shrink:0; }
    .icon-picker-btn .ip-label { overflow:hidden; text-overflow:ellipsis; font-size:11px; color:#6b7280; flex:1; min-width:0; }
    #gip { position:fixed; z-index:9999; background:#fff; border:1px solid #d1d5db; border-radius:10px; box-shadow:0 8px 30px rgba(0,0,0,0.15); width:300px; max-height:340px; flex-direction:column; overflow:hidden; }
    #gip-search { width:100%; padding:10px 12px; border:none; border-bottom:1px solid #e5e7eb; outline:none; font-size:13px; box-sizing:border-box; }
    #gip-grid { overflow-y:auto; padding:8px; display:grid; grid-template-columns:repeat(5,1fr); gap:4px; }
    .gip-item { display:flex; flex-direction:column; align-items:center; padding:8px 4px; border-radius:6px; cursor:pointer; color:#374151; transition:background 0.1s; }
    .gip-item:hover { background:#f4f8ff; color:#0d6efd; }
    .gip-item i { font-size:18px; margin-bottom:3px; }
    .gip-item .gip-name { font-size:9px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; width:100%; text-align:center; }
</style>

<div class="container-fluid py-4" style="max-width:900px;">

    <div class="mb-4 d-flex align-items-center gap-3">
        <a href="{{ route('customer.property') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <div>
            <h4 class="fw-bold mb-0" style="color:#2b4c8a;">Edit Properti</h4>
            <p class="text-muted mb-0" style="font-size:13px;">
                Status:
                @php $statusLabels = [0=>'Draft',1=>'Tayang',2=>'Tunda',3=>'Terjual']; @endphp
                <strong>{{ $statusLabels[$item->status_id] ?? '-' }}</strong>
            </p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 mb-4">
            <ul class="mb-0" style="font-size:13px;">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-3 mb-4">{{ session('error') }}</div>
    @endif

    @php
        $trans     = $item->translations->where('locale', 'id')->first();
        $mainThumb = $item->interiors->where('order', 1)->first();
        $miniThumb = $item->interiors->where('order', 2)->first();
        $gallery   = $item->interiors->where('order', '>=', 3)->sortBy('order');
        $specs     = $item->specs;
        $facilities = $item->facilities;
        $nearbyLocations = $item->nearbyLocations;
        $extraFeatures   = $item->extraFeatures;
    @endphp

    <form action="{{ route('customer.property.update', $item->property_id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Kategori --}}
        <div class="section-card">
            <div class="section-heading"><i class="fas fa-tags me-2 text-primary"></i>Kategori Properti</div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Tipe Properti</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($propertyTypes as $pt)
                    <div class="radio-card">
                        <input type="radio" name="tipe_properti"
                            value="{{ $pt->property_type_id }}"
                            id="tipe_{{ $pt->property_type_id }}"
                            {{ old('tipe_properti', $item->property_type_id) == $pt->property_type_id ? 'checked' : '' }}>
                        <label for="tipe_{{ $pt->property_type_id }}">
                            <i class="{{ $pt->icon_class ?? 'fas fa-home' }}"></i>
                            {{ $pt->translations->first()?->type_name ?? '-' }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Kondisi Properti</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($propertyConditions as $pc)
                    <div class="radio-card">
                        <input type="radio" name="property_condition"
                            value="{{ $pc->property_condition_id }}"
                            id="cond_{{ $pc->property_condition_id }}"
                            {{ old('property_condition', $item->condition_id) == $pc->property_condition_id ? 'checked' : '' }}>
                        <label for="cond_{{ $pc->property_condition_id }}">
                            <i class="{{ $pc->icon_class ?? 'fas fa-tag' }}"></i>
                            {{ $pc->translations->first()?->condition_name ?? '-' }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Provinsi</label>
                    <select name="provinsi_id" id="select-provinsi" class="form-select" style="border-radius:8px; height:44px;">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->provinsi_id }}" {{ old('provinsi_id', $item->provinsi_id) == $prov->provinsi_id ? 'selected' : '' }}>
                                {{ $prov->provinsi_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Kota / Kabupaten</label>
                    <select name="kota_id" id="select-kota" class="form-select" style="border-radius:8px; height:44px;">
                        <option value="">-- Pilih Kota / Kabupaten --</option>
                        @foreach($kotas as $k)
                            <option value="{{ $k->kota_id }}" {{ old('kota_id', $item->kota_id) == $k->kota_id ? 'selected' : '' }}>
                                {{ $k->nama_kota }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Proyek / Township</label>
                    <select name="township_id" class="form-select" style="border-radius:8px; height:44px;">
                        <option value="">-- Pilih Township (Opsional) --</option>
                        @foreach($townships as $t)
                            <option value="{{ $t->township_id }}" {{ old('township_id', $item->township_id) == $t->township_id ? 'selected' : '' }}>
                                {{ $t->township_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Cluster</label>
                    <select name="cluster_id" class="form-select" style="border-radius:8px; height:44px;">
                        <option value="">-- Pilih Cluster (Opsional) --</option>
                        @foreach($clusters as $cl)
                            <option value="{{ $cl->cluster_id }}" {{ old('cluster_id', $item->cluster_id) == $cl->cluster_id ? 'selected' : '' }}>
                                {{ $cl->cluster_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Informasi Properti --}}
        <div class="section-card">
            <div class="section-heading"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Properti</div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Judul Properti <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                    value="{{ old('title', $trans?->title) }}" placeholder="Contoh: Rumah Modern 2 Lantai di Serpong">
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Deskripsi Properti <span class="text-danger">*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="5">{{ old('description', $trans?->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- SEO Meta --}}
            <div class="mb-4 p-4 rounded border" style="background:#f8faff;">
                <h6 class="fw-bold mb-1"><i class="fas fa-search-plus me-2 text-primary"></i>SEO Meta</h6>
                <p class="text-muted mb-3" style="font-size:12px;">Opsional. Digunakan untuk tampilan di mesin pencari.</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Meta Title</label>
                    <input type="text" class="form-control" name="meta_title"
                        value="{{ old('meta_title', $trans?->meta_title) }}"
                        placeholder="Judul halaman di mesin pencari" maxlength="255">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Meta Keyword</label>
                    <input type="text" class="form-control" name="meta_keyword"
                        value="{{ old('meta_keyword', $trans?->meta_keyword) }}"
                        placeholder="Kata kunci, pisahkan dengan koma">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold" style="font-size:13px;">Meta Description</label>
                    <textarea class="form-control" name="meta_descriotion" rows="2"
                        placeholder="Deskripsi singkat untuk mesin pencari">{{ old('meta_descriotion', $trans?->meta_descriotion) }}</textarea>
                </div>
            </div>

            <div class="mb-4 p-4 rounded bg-light border">
                <h6 class="fw-bold mb-1">Tag & Label Iklan</h6>
                <p class="text-muted mb-4" style="font-size:13px;">Pilih label iklan (maksimal 2) dan tambahkan tag bebas untuk menonjolkan fitur properti.</p>

                {{-- Label Iklan: ON/OFF toggle per label, max 2 aktif --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:13px;">
                        Label Iklan <span class="text-muted fw-normal">(maks. 2 aktif)</span>
                    </label>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        @foreach($labelTags as $lt)
                        @php $isOn = in_array($lt->tag_id, $selectedLabelIds); @endphp
                        <div class="label-toggle-item {{ $isOn ? 'is-on' : '' }}"
                             id="lti-{{ $lt->tag_id }}"
                             style="--chip-color: {{ $lt->color_code }};">
                            <span class="label-toggle-badge" style="background:{{ $lt->color_code }};">{{ $lt->name }}</span>
                            <button type="button" class="label-toggle-btn">{{ $isOn ? 'ON' : 'OFF' }}</button>
                            <input type="hidden" name="label_tag_ids[]" value="{{ $lt->tag_id }}"
                                   class="label-hidden-input" {{ $isOn ? '' : 'disabled' }}>
                        </div>
                        @endforeach
                    </div>
                    <div id="label-limit-msg" class="text-danger mt-2" style="font-size:12px; display:none;">
                        Maksimal 2 label iklan yang dapat diaktifkan.
                    </div>
                </div>

                {{-- Tag Tambahan: free-form --}}
                <div>
                    <label class="form-label fw-semibold" style="font-size:13px;">
                        Tag Tambahan <span class="text-muted fw-normal">(opsional)</span>
                    </label>
                    <p class="text-muted mb-2" style="font-size:12px;">Misal: Extended Area, Hook. Tidak boleh sama dengan nama label di atas.</p>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="tag-input" placeholder="Ketik tag lalu tekan Enter atau klik Tambah">
                        <button class="btn btn-primary" type="button" id="btn-add-tag"><i class="fas fa-plus me-1"></i> Tambah</button>
                    </div>
                    <div class="tag-container" id="tag-container">
                        @foreach($customTags as $ct)
                            <span class="custom-tag">{{ $ct }} <i class="fas fa-times ms-1 remove-tag"></i><input type="hidden" name="custom_tags[]" value="{{ $ct }}"></span>
                        @endforeach
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mb-3">Dimensi Utama</h6>
            <div class="row mb-4">
                <div class="col-md-3 mb-3"><label class="form-label" style="font-size:13px;">Kamar Tidur</label>
                    <input type="number" class="form-control" name="bedrooms" value="{{ old('bedrooms', $item->bedrooms) }}" min="0"></div>
                <div class="col-md-3 mb-3"><label class="form-label" style="font-size:13px;">Kamar Mandi</label>
                    <input type="number" class="form-control" name="bathrooms" value="{{ old('bathrooms', $item->bathroom) }}" min="0"></div>
                <div class="col-md-3 mb-3"><label class="form-label" style="font-size:13px;">Luas Tanah</label>
                    <div class="input-group"><input type="number" class="form-control" name="land_area" value="{{ old('land_area', $item->land_area) }}" min="0"><span class="input-group-text">m²</span></div></div>
                <div class="col-md-3 mb-3"><label class="form-label" style="font-size:13px;">Luas Bangunan</label>
                    <div class="input-group"><input type="number" class="form-control" name="building_area" value="{{ old('building_area', $item->building_area) }}" min="0"><span class="input-group-text">m²</span></div></div>
            </div>

            <h6 class="fw-bold mb-2">Spesifikasi Detail</h6>
            <div id="container-spesifikasi">
                @forelse($specs as $spec)
                    @php $st = $spec->translations->where('locale', 'id')->first(); @endphp
                    <div class="d-flex mb-2 dynamic-row gap-2">
                        <input type="text" class="form-control py-2 w-50" name="spec_keys[]" value="{{ old('spec_keys.'.$loop->index, $st?->spec_key) }}" placeholder="Nama">
                        <input type="text" class="form-control py-2 w-50" name="spec_values[]" value="{{ old('spec_values.'.$loop->index, $st?->spec_value) }}" placeholder="Nilai">
                        <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>
                    </div>
                @empty
                    <div class="d-flex mb-2 dynamic-row gap-2">
                        <input type="text" class="form-control py-2 w-50" name="spec_keys[]" placeholder="Nama">
                        <input type="text" class="form-control py-2 w-50" name="spec_values[]" placeholder="Nilai">
                        <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="btn btn-add-dynamic mt-2 mb-4" id="btn-add-spesifikasi"><i class="fas fa-plus me-1"></i> Tambah Spesifikasi</button>

            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="fw-bold mb-0">Fasilitas Properti</h6>
                @if($importableProperties->count())
                <button type="button" class="import-btn" id="btn-open-import">
                    <i class="fas fa-file-import me-1"></i> Import dari Properti Lain
                </button>
                @endif
            </div>
            <p class="text-muted mb-3" style="font-size:12px;">Nama, ikon CSS class (mis. <code>fas fa-swimming-pool</code>), dan gambar opsional.</p>
            <div id="container-fasilitas">
                @forelse($facilities as $fac)
                    @php $ft = $fac->translations->where('locale', 'id')->first(); @endphp
                    <div class="facility-row dynamic-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label mb-1" style="font-size:12px;">Nama Fasilitas</label>
                                <input type="text" class="form-control py-2" name="facility_names[]" value="{{ old('facility_names.'.$loop->index, $ft?->name) }}" placeholder="Contoh: Kolam Renang">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1" style="font-size:12px;">Ikon</label>
                                @php $facIcon = old('facility_icons.'.$loop->index, $fac->icon_url ?: 'fas fa-check'); @endphp
                                <button type="button" class="btn icon-picker-btn">
                                    <i class="{{ $facIcon }}"></i>
                                    <span class="ip-label">{{ $facIcon }}</span>
                                </button>
                                <input type="hidden" name="facility_icons[]" value="{{ $facIcon }}" class="icon-picker-val">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1" style="font-size:12px;">Gambar (opsional)</label>
                                @if($fac->image)
                                    <img src="{{ Storage::url($fac->image) }}" class="fac-img-thumb mb-1 d-block">
                                @endif
                                <input type="file" class="form-control fac-img-input" name="facility_images[]" data-req-w="4096" data-req-h="2503" accept="image/*">
                                <div style="font-size:11px; color:#777; margin-top:3px;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i><strong>4096 × 2503</strong> px</div>
                                <div class="dim-feedback" style="font-size:11px; margin-top:1px;"></div>
                                <input type="hidden" name="facility_existing_imgs[]" value="{{ $fac->image ?? '' }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end pb-1">
                                <button type="button" class="btn btn-remove-row px-3 btn-delete w-100"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="facility-row dynamic-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label mb-1" style="font-size:12px;">Nama Fasilitas</label>
                                <input type="text" class="form-control py-2" name="facility_names[]" placeholder="Contoh: Kolam Renang">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1" style="font-size:12px;">Ikon</label>
                                <button type="button" class="btn icon-picker-btn">
                                    <i class="fas fa-check"></i>
                                    <span class="ip-label">fas fa-check</span>
                                </button>
                                <input type="hidden" name="facility_icons[]" value="fas fa-check" class="icon-picker-val">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1" style="font-size:12px;">Gambar (opsional)</label>
                                <input type="file" class="form-control fac-img-input" name="facility_images[]" data-req-w="4096" data-req-h="2503" accept="image/*">
                                <div style="font-size:11px; color:#777; margin-top:3px;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i><strong>4096 × 2503</strong> px</div>
                                <div class="dim-feedback" style="font-size:11px; margin-top:1px;"></div>
                                <input type="hidden" name="facility_existing_imgs[]" value="">
                            </div>
                            <div class="col-md-2 d-flex align-items-end pb-1">
                                <button type="button" class="btn btn-remove-row px-3 btn-delete w-100"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <button type="button" class="btn btn-add-dynamic mt-2" id="btn-add-fasilitas"><i class="fas fa-plus me-1"></i> Tambah Fasilitas</button>
        </div>

        {{-- Ekstra Fitur --}}
        <div class="section-card">
            <div class="section-heading"><i class="fas fa-star me-2 text-primary"></i>Ekstra Fitur</div>
            <p class="text-muted mb-3" style="font-size:13px;">Fitur unggulan tambahan dengan ikon CSS class dan nama.</p>
            <div id="container-extra">
                @forelse($extraFeatures as $ef)
                    @php $eft = $ef->translations->where('locale', 'id')->first(); @endphp
                    <div class="facility-row dynamic-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <label class="form-label mb-1" style="font-size:12px;">Nama Fitur</label>
                                <input type="text" class="form-control py-2" name="extra_names[]" value="{{ old('extra_names.'.$loop->index, $eft?->name) }}" placeholder="Contoh: Smart Home System">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label mb-1" style="font-size:12px;">Ikon</label>
                                @php $extraIcon = old('extra_icons.'.$loop->index, $ef->icon_url ?: 'fas fa-star'); @endphp
                                <button type="button" class="btn icon-picker-btn">
                                    <i class="{{ $extraIcon }}"></i>
                                    <span class="ip-label">{{ $extraIcon }}</span>
                                </button>
                                <input type="hidden" name="extra_icons[]" value="{{ $extraIcon }}" class="icon-picker-val">
                            </div>
                            <div class="col-md-2 d-flex align-items-end pb-1">
                                <button type="button" class="btn btn-remove-row px-3 btn-delete w-100"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="facility-row dynamic-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <label class="form-label mb-1" style="font-size:12px;">Nama Fitur</label>
                                <input type="text" class="form-control py-2" name="extra_names[]" placeholder="Contoh: Smart Home System">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label mb-1" style="font-size:12px;">Ikon</label>
                                <button type="button" class="btn icon-picker-btn">
                                    <i class="fas fa-star"></i>
                                    <span class="ip-label">fas fa-star</span>
                                </button>
                                <input type="hidden" name="extra_icons[]" value="fas fa-star" class="icon-picker-val">
                            </div>
                            <div class="col-md-2 d-flex align-items-end pb-1">
                                <button type="button" class="btn btn-remove-row px-3 btn-delete w-100"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <button type="button" class="btn btn-add-dynamic mt-2" id="btn-add-extra"><i class="fas fa-plus me-1"></i> Tambah Ekstra Fitur</button>
        </div>

        {{-- Lokasi Sekitar --}}
        <div class="section-card">
            <div class="section-heading"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Lokasi Sekitar</div>
            <p class="text-muted mb-3" style="font-size:13px;">Misal: Mall, Sekolah, Rumah Sakit, Tol terdekat.</p>
            <div id="container-nearby">
                @forelse($nearbyLocations as $nb)
                    @php $nbt = $nb->translations->where('locale', 'id')->first(); @endphp
                    <div class="d-flex mb-2 dynamic-row gap-2">
                        <input type="text" class="form-control py-2" name="nearby_names[]" value="{{ old('nearby_names.'.$loop->index, $nbt?->name) }}" placeholder="Contoh: Tol Serpong">
                        <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>
                    </div>
                @empty
                    <div class="d-flex mb-2 dynamic-row gap-2">
                        <input type="text" class="form-control py-2" name="nearby_names[]" placeholder="Contoh: Tol Serpong">
                        <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="btn btn-add-dynamic mt-2" id="btn-add-nearby"><i class="fas fa-plus me-1"></i> Tambah Lokasi Sekitar</button>
        </div>

        {{-- Koordinat --}}
        <div class="section-card">
            <div class="section-heading"><i class="fas fa-map-pin me-2 text-primary"></i>Koordinat Peta</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Latitude</label>
                    <input type="text" class="form-control" name="latitude" value="{{ old('latitude', $item->latitude) }}" placeholder="Contoh: -6.2297">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Longitude</label>
                    <input type="text" class="form-control" name="longtidure" value="{{ old('longtidure', $item->longtidure) }}" placeholder="Contoh: 106.6873">
                </div>
            </div>
        </div>

        {{-- Harga --}}
        <div class="section-card">
            <div class="section-heading"><i class="fas fa-tag me-2 text-primary"></i>Harga</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Harga Properti <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">Rp</span>
                        <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                            value="{{ old('price', number_format($item->price, 0, ',', '.')) }}" placeholder="Misal: 1.100.000.000">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Diskon</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">Rp</span>
                        <input type="text" class="form-control" name="discount"
                            value="{{ old('discount', $item->diskon > 0 ? number_format($item->diskon, 0, ',', '.') : '') }}" placeholder="Misal: 50.000.000">
                    </div>
                </div>
            </div>
        </div>

        {{-- Media --}}
        <div class="section-card">
            <div class="section-heading"><i class="fas fa-images me-2 text-primary"></i>Media & Foto Properti</div>

            <div class="row mb-4">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold" style="font-size:13px;">Main Thumbnail</label>
                    @if($mainThumb)
                        <div class="mb-2">
                            <img src="{{ Storage::url($mainThumb->image) }}" alt="main" class="img-thumb-current">
                            <p class="text-muted mt-1" style="font-size:11px;">Gambar saat ini. Pilih file baru untuk mengganti.</p>
                        </div>
                    @endif
                    <input type="file" name="main_thumbnail" class="form-control @error('main_thumbnail') is-invalid @enderror" data-req-w="4096" data-req-h="2298" accept="image/*">
                    @error('main_thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-1" style="font-size:11px; color:#777;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i>Dimensi: <strong>4096 × 2298</strong> px &nbsp;|&nbsp; Maks 10MB</div>
                    <div class="dim-feedback" style="font-size:11px; margin-top:2px;"></div>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold" style="font-size:13px;">Mini Thumbnail</label>
                    @if($miniThumb)
                        <div class="mb-2">
                            <img src="{{ Storage::url($miniThumb->image) }}" alt="mini" class="img-thumb-current">
                            <p class="text-muted mt-1" style="font-size:11px;">Gambar saat ini. Pilih file baru untuk mengganti.</p>
                        </div>
                    @endif
                    <input type="file" name="mini_thumbnail" class="form-control @error('mini_thumbnail') is-invalid @enderror" data-req-w="4096" data-req-h="2414" accept="image/*">
                    @error('mini_thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-1" style="font-size:11px; color:#777;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i>Dimensi: <strong>4096 × 2414</strong> px &nbsp;|&nbsp; Maks 10MB</div>
                    <div class="dim-feedback" style="font-size:11px; margin-top:2px;"></div>
                </div>
            </div>

            @if($gallery->isNotEmpty())
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Foto Interior Saat Ini</label>
                <p class="text-muted mb-2" style="font-size:12px;">Centang untuk menghapus foto.</p>
                <div class="row g-3">
                    @foreach($gallery as $img)
                        @php $imgTrans = $img->translations->where('locale', 'id')->first(); @endphp
                        <div class="col-md-3 col-6">
                            <div class="gallery-card">
                                <img src="{{ Storage::url($img->image) }}" alt="gallery">
                                <p class="text-muted mt-1 mb-1" style="font-size:11px;">{{ $imgTrans?->interior_name ?? '-' }}</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="delete_interior_ids[]"
                                        value="{{ $img->property_interior_id }}" id="del_{{ $img->property_interior_id }}">
                                    <label class="form-check-label text-danger" for="del_{{ $img->property_interior_id }}" style="font-size:11px;">Hapus</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Tambah Foto Interior Baru</label>
                <div id="container-interior-gallery">
                    <div class="row mb-3 dynamic-row align-items-center">
                        <div class="col-md-5">
                            <input type="file" name="interior_images[]" class="form-control gallery-input" data-req-w="4096" data-req-h="2298" accept="image/*">
                            <div style="font-size:11px; color:#777; margin-top:3px;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i><strong>4096 × 2298</strong> px</div>
                            <div class="dim-feedback" style="font-size:11px; margin-top:1px;"></div>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="interior_labels[]" placeholder="Nama area interior (cth: Ruang Tamu)">
                        </div>
                        <div class="col-md-2"><button type="button" class="btn btn-outline-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button></div>
                    </div>
                </div>
                <button type="button" class="btn btn-add-dynamic mt-2" id="btn-add-interior">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Foto Lainnya
                </button>
            </div>
        </div>

        @php $existingMedia = \App\Models\PropertyMedia::where('property_id', $item->property_id)->first(); @endphp
        <div class="bg-white p-4 rounded-lg shadow-sm border border-light mb-4">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Media Video</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">URL Virtual Tour 360°</label>
                    <input type="url" class="form-control py-2" name="url_360"
                           value="{{ old('url_360', $existingMedia?->url_360) }}" placeholder="https://...">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">URL YouTube</label>
                    <input type="url" class="form-control py-2" name="url_youtube"
                           value="{{ old('url_youtube', $existingMedia?->url_youtube) }}" placeholder="https://youtube.com/...">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Upload Video Properti</label>
                    <p class="text-muted mb-2" style="font-size:12px;">Format: mp4, mov, avi. Maks 50MB.
                        @if ($existingMedia?->filename)
                            <span class="text-success ms-2"><i class="fas fa-check-circle"></i> Video sudah ada: {{ basename($existingMedia->filename) }}</span>
                        @endif
                    </p>
                    <input type="file" class="form-control py-2" name="video_file" accept="video/mp4,video/quicktime,video/x-msvideo">
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end mb-5">
            <a href="{{ route('customer.property') }}" class="btn btn-outline-secondary px-4" style="border-radius:8px;">Batal</a>
            <button type="submit" class="btn btn-primary px-5" style="background:#2b4c8a; border-color:#2b4c8a; border-radius:8px;">
                <i class="fas fa-save me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- Icon Picker Panel --}}
<div id="gip" style="display:none;">
    <input type="text" id="gip-search" placeholder="Cari ikon..." autocomplete="off">
    <div id="gip-grid"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Provinsi → Kota AJAX
    const selectProvinsi = document.getElementById('select-provinsi');
    const selectKota     = document.getElementById('select-kota');

    function loadKota(provinsiId, selectedId, keepCurrent) {
        if (!provinsiId) {
            selectKota.innerHTML = '<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>';
            return;
        }
        if (!keepCurrent) {
            selectKota.innerHTML = '<option value="">Memuat...</option>';
        }
        fetch('/api/data/cities/' + provinsiId)
            .then(r => r.json())
            .then(res => {
                const list = res.data || [];
                selectKota.innerHTML = '<option value="">-- Pilih Kota / Kabupaten --</option>';
                list.forEach(k => {
                    const o = document.createElement('option');
                    o.value = k.kota_id;
                    o.textContent = k.nama_kota;
                    if (selectedId && k.kota_id == selectedId) o.selected = true;
                    selectKota.appendChild(o);
                });
            })
            .catch(() => {
                selectKota.innerHTML = '<option value="">Gagal memuat kota</option>';
            });
    }

    // On province change: reload cities, clear kota selection
    selectProvinsi.addEventListener('change', function() { loadKota(this.value, null, false); });

    // On page load: if province already selected, refresh city list to match (keeps server-rendered selection)
    if (selectProvinsi.value) {
        const currentKotaId = selectKota.value;
        loadKota(selectProvinsi.value, currentKotaId, false);
    }

    // Label ON/OFF toggle logic (max 2 aktif)
    const LABEL_NAMES   = @json($labelTags->pluck('name')->map(fn($n) => strtolower($n)));
    const toggleItems   = document.querySelectorAll('.label-toggle-item');
    const labelLimitMsg = document.getElementById('label-limit-msg');

    function turnLabelOn(item) {
        item.classList.add('is-on');
        item.classList.remove('is-disabled');
        item.querySelector('.label-toggle-btn').textContent = 'ON';
        item.querySelector('.label-hidden-input').disabled = false;
    }
    function turnLabelOff(item) {
        item.classList.remove('is-on');
        item.querySelector('.label-toggle-btn').textContent = 'OFF';
        item.querySelector('.label-hidden-input').disabled = true;
    }
    function updateLabelState() {
        const onCount = document.querySelectorAll('.label-toggle-item.is-on').length;
        labelLimitMsg.style.display = onCount >= 2 ? 'block' : 'none';
        toggleItems.forEach(item => {
            if (!item.classList.contains('is-on')) {
                if (onCount >= 2) item.classList.add('is-disabled');
                else item.classList.remove('is-disabled');
            }
        });
    }
    toggleItems.forEach(item => {
        item.querySelector('.label-toggle-btn').addEventListener('click', function() {
            if (item.classList.contains('is-on')) {
                turnLabelOff(item);
            } else {
                if (item.classList.contains('is-disabled')) return;
                turnLabelOn(item);
            }
            updateLabelState();
        });
    });
    updateLabelState();

    // Custom tag logic
    const tagInput     = document.getElementById('tag-input');
    const btnAddTag    = document.getElementById('btn-add-tag');
    const tagContainer = document.getElementById('tag-container');

    function addTag() {
        const val = tagInput.value.trim();
        if (!val) return;
        if (LABEL_NAMES.includes(val.toLowerCase())) {
            alert('"' + val + '" adalah nama label iklan default dan tidak dapat digunakan sebagai tag biasa.');
            return;
        }
        const existing = [...document.querySelectorAll('#tag-container input[name="custom_tags[]"]')]
            .map(i => i.value.toLowerCase());
        if (existing.includes(val.toLowerCase())) {
            alert('Tag ini sudah ditambahkan.');
            return;
        }
        const span = document.createElement('span');
        span.className = 'custom-tag';
        span.innerHTML = `${val} <i class="fas fa-times ms-1 remove-tag"></i>`;
        const hidden = document.createElement('input');
        hidden.type  = 'hidden';
        hidden.name  = 'custom_tags[]';
        hidden.value = val;
        span.appendChild(hidden);
        tagContainer.appendChild(span);
        tagInput.value = '';
    }

    btnAddTag.addEventListener('click', addTag);
    tagInput.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); addTag(); } });
    tagContainer.addEventListener('click', e => { if (e.target.classList.contains('remove-tag')) e.target.closest('.custom-tag').remove(); });

    // ---- Dynamic row builders ----
    function makeFacilityRow(name, icon, imgUrl, imgPath) {
        const ic = icon || 'fas fa-check';
        const div = document.createElement('div');
        div.className = 'facility-row dynamic-row';
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-1" style="font-size:12px;">Nama Fasilitas</label>
                    <input type="text" class="form-control py-2" name="facility_names[]" value="${name||''}" placeholder="Contoh: Kolam Renang">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1" style="font-size:12px;">Ikon</label>
                    <button type="button" class="btn icon-picker-btn">
                        <i class="${ic}"></i>
                        <span class="ip-label">${ic}</span>
                    </button>
                    <input type="hidden" name="facility_icons[]" value="${ic}" class="icon-picker-val">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1" style="font-size:12px;">Gambar (opsional)</label>
                    ${imgUrl ? `<img src="${imgUrl}" class="fac-img-thumb mb-1 d-block">` : ''}
                    <input type="file" class="form-control fac-img-input" name="facility_images[]" data-req-w="4096" data-req-h="2503" accept="image/*">
                    <div style="font-size:11px;color:#777;margin-top:3px;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i><strong>4096 × 2503</strong> px</div>
                    <div class="dim-feedback" style="font-size:11px;margin-top:1px;"></div>
                    <input type="hidden" name="facility_existing_imgs[]" value="${imgPath||''}">
                </div>
                <div class="col-md-2 d-flex align-items-end pb-1">
                    <button type="button" class="btn btn-remove-row px-3 btn-delete w-100"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        return div;
    }

    function makeExtraRow(name, icon) {
        const ic = icon || 'fas fa-star';
        const div = document.createElement('div');
        div.className = 'facility-row dynamic-row';
        div.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <label class="form-label mb-1" style="font-size:12px;">Nama Fitur</label>
                    <input type="text" class="form-control py-2" name="extra_names[]" value="${name||''}" placeholder="Contoh: Smart Home System">
                </div>
                <div class="col-md-5">
                    <label class="form-label mb-1" style="font-size:12px;">Ikon</label>
                    <button type="button" class="btn icon-picker-btn">
                        <i class="${ic}"></i>
                        <span class="ip-label">${ic}</span>
                    </button>
                    <input type="hidden" name="extra_icons[]" value="${ic}" class="icon-picker-val">
                </div>
                <div class="col-md-2 d-flex align-items-end pb-1">
                    <button type="button" class="btn btn-remove-row px-3 btn-delete w-100"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        return div;
    }

    function makeNearbyRow(name) {
        const div = document.createElement('div');
        div.className = 'd-flex mb-2 dynamic-row gap-2';
        div.innerHTML = `<input type="text" class="form-control py-2" name="nearby_names[]" value="${name||''}" placeholder="Contoh: Tol Serpong">
                         <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>`;
        return div;
    }

    function makeSpecRow(key, val) {
        const div = document.createElement('div');
        div.className = 'd-flex mb-2 dynamic-row gap-2';
        div.innerHTML = `<input type="text" class="form-control py-2 w-50" name="spec_keys[]" value="${key||''}" placeholder="Nama">
                         <input type="text" class="form-control py-2 w-50" name="spec_values[]" value="${val||''}" placeholder="Nilai">
                         <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>`;
        return div;
    }

    document.getElementById('btn-add-spesifikasi').addEventListener('click', () => document.getElementById('container-spesifikasi').appendChild(makeSpecRow()));
    document.getElementById('btn-add-fasilitas').addEventListener('click', () => document.getElementById('container-fasilitas').appendChild(makeFacilityRow()));
    document.getElementById('btn-add-extra').addEventListener('click', () => document.getElementById('container-extra').appendChild(makeExtraRow()));
    document.getElementById('btn-add-nearby').addEventListener('click', () => document.getElementById('container-nearby').appendChild(makeNearbyRow()));

    // ---- Global Icon Picker ----
    const GIP_ICONS = [
        {cls:'fas fa-check',name:'Check'},{cls:'fas fa-star',name:'Bintang'},
        {cls:'fas fa-home',name:'Rumah'},{cls:'fas fa-swimming-pool',name:'Kolam Renang'},
        {cls:'fas fa-car',name:'Garasi'},{cls:'fas fa-bolt',name:'Listrik'},
        {cls:'fas fa-wifi',name:'WiFi'},{cls:'fas fa-shield-alt',name:'Keamanan'},
        {cls:'fas fa-leaf',name:'Taman'},{cls:'fas fa-dumbbell',name:'Gym'},
        {cls:'fas fa-basketball-ball',name:'Olahraga'},{cls:'fas fa-water',name:'Air'},
        {cls:'fas fa-fire',name:'Api'},{cls:'fas fa-tv',name:'TV'},
        {cls:'fas fa-snowflake',name:'AC'},{cls:'fas fa-bed',name:'Kamar Tidur'},
        {cls:'fas fa-bath',name:'Kamar Mandi'},{cls:'fas fa-couch',name:'Ruang Tamu'},
        {cls:'fas fa-utensils',name:'Dapur'},{cls:'fas fa-parking',name:'Parkir'},
        {cls:'fas fa-bicycle',name:'Sepeda'},{cls:'fas fa-tree',name:'Pohon'},
        {cls:'fas fa-sun',name:'Teras'},{cls:'fas fa-camera',name:'CCTV'},
        {cls:'fas fa-lock',name:'Kunci'},{cls:'fas fa-key',name:'Akses'},
        {cls:'fas fa-building',name:'Gedung'},{cls:'fas fa-warehouse',name:'Gudang'},
        {cls:'fas fa-store',name:'Toko'},{cls:'fas fa-school',name:'Sekolah'},
        {cls:'fas fa-hospital',name:'RS'},{cls:'fas fa-shopping-cart',name:'Belanja'},
        {cls:'fas fa-bus',name:'Bis'},{cls:'fas fa-road',name:'Jalan'},
        {cls:'fas fa-map-marker-alt',name:'Lokasi'},{cls:'fas fa-mountain',name:'View'},
        {cls:'fas fa-spa',name:'Spa'},{cls:'fas fa-fan',name:'Ventilasi'},
        {cls:'fas fa-solar-panel',name:'Solar'},{cls:'fas fa-concierge-bell',name:'Resepsionis'},
        {cls:'fas fa-tools',name:'Maintenance'},{cls:'fas fa-hard-hat',name:'Renovasi'},
        {cls:'fas fa-plug',name:'Stop Kontak'},{cls:'fas fa-lightbulb',name:'Lampu'},
        {cls:'fas fa-paint-brush',name:'Desain'},{cls:'fas fa-ruler-combined',name:'Ukuran'},
        {cls:'fas fa-chair',name:'Furnitur'},{cls:'fas fa-door-open',name:'Pintu'},
        {cls:'fas fa-recycle',name:'Daur Ulang'},{cls:'fas fa-phone',name:'Telepon'},
        {cls:'fas fa-gas-pump',name:'Gas'},{cls:'fas fa-hot-tub',name:'Jacuzzi'},
        {cls:'fas fa-elevator',name:'Lift'},{cls:'fas fa-trash-alt',name:'Sampah'},
    ];

    const gipEl = document.getElementById('gip');
    let gipTarget = null;

    function gipRender(q) {
        const grid = document.getElementById('gip-grid');
        const f = q ? GIP_ICONS.filter(ic => ic.name.toLowerCase().includes(q) || ic.cls.includes(q)) : GIP_ICONS;
        grid.innerHTML = f.map(ic => `<div class="gip-item" data-cls="${ic.cls}"><i class="${ic.cls}"></i><span class="gip-name">${ic.name}</span></div>`).join('');
    }

    function gipOpen(btn) {
        gipTarget = btn;
        const r = btn.getBoundingClientRect();
        gipEl.style.display = 'flex';
        const panH = 340;
        let top = r.bottom + window.scrollY + 4;
        if (window.innerHeight - r.bottom < panH) top = r.top + window.scrollY - panH - 4;
        let left = r.left + window.scrollX;
        if (left + 300 > window.innerWidth) left = window.innerWidth - 310;
        gipEl.style.top  = top + 'px';
        gipEl.style.left = left + 'px';
        document.getElementById('gip-search').value = '';
        gipRender('');
        document.getElementById('gip-search').focus();
    }

    function gipClose() { gipEl.style.display = 'none'; gipTarget = null; }

    document.getElementById('gip-search').addEventListener('input', function() { gipRender(this.value.toLowerCase()); });

    document.getElementById('gip-grid').addEventListener('click', function(e) {
        const item = e.target.closest('.gip-item');
        if (!item || !gipTarget) return;
        const cls = item.dataset.cls;
        const h = gipTarget.parentElement.querySelector('.icon-picker-val');
        if (h) h.value = cls;
        gipTarget.querySelector('i').className = cls;
        gipTarget.querySelector('.ip-label').textContent = cls;
        gipClose();
    });

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.icon-picker-btn');
        if (btn) { gipOpen(btn); return; }
        if (gipEl && gipEl.style.display !== 'none' && !gipEl.contains(e.target)) gipClose();
    });

    // Delete row
    document.body.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.btn-delete');
        if (!deleteBtn) return;
        const container = deleteBtn.closest('[id^="container-"]');
        if (!container) return;
        const rows = container.querySelectorAll('.dynamic-row');
        if (rows.length > 1) deleteBtn.closest('.dynamic-row').remove();
        else deleteBtn.closest('.dynamic-row').querySelectorAll('input[type="text"], input[type="number"]').forEach(i => i.value = '');
    });

    // Import modal
    const btnOpenImport = document.getElementById('btn-open-import');
    if (btnOpenImport) {
        const importModal = new bootstrap.Modal(document.getElementById('importModal'));
        btnOpenImport.addEventListener('click', () => importModal.show());

        document.getElementById('import-search').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.import-item').forEach(item => {
                item.style.display = item.dataset.title.toLowerCase().includes(q) ? '' : 'none';
            });
        });

        document.getElementById('import-list').addEventListener('click', function(e) {
            const item = e.target.closest('.import-item');
            if (!item) return;
            fetch(`/customer/property/${item.dataset.id}/import-data`)
                .then(r => r.json())
                .then(data => { applyImport(data); importModal.hide(); })
                .catch(() => alert('Gagal mengambil data properti.'));
        });
    }

    function applyImport(data) {
        const specCont = document.getElementById('container-spesifikasi');
        specCont.innerHTML = '';
        (data.specs?.length ? data.specs : [{}]).forEach(s => specCont.appendChild(makeSpecRow(s.key||'', s.value||'')));

        const facCont = document.getElementById('container-fasilitas');
        facCont.innerHTML = '';
        (data.facilities?.length ? data.facilities : [{}]).forEach(f => facCont.appendChild(makeFacilityRow(f.name||'', f.icon_url||'', f.image_url||null, f.image_path||'')));

        const extraCont = document.getElementById('container-extra');
        extraCont.innerHTML = '';
        (data.extras?.length ? data.extras : [{}]).forEach(e => extraCont.appendChild(makeExtraRow(e.name||'', e.icon_url||'')));

        const nearbyCont = document.getElementById('container-nearby');
        nearbyCont.innerHTML = '';
        (data.nearby?.length ? data.nearby : [{}]).forEach(n => nearbyCont.appendChild(makeNearbyRow(n.name||'')));
    }

    // Dimension checker helper
    function checkDim(src, reqW, reqH, fbEl) {
        if (!fbEl || !reqW || !reqH) return;
        const tmp = new Image();
        tmp.onload = function() {
            const ok = tmp.width === reqW && tmp.height === reqH;
            fbEl.innerHTML = `Dimensi: <strong>${tmp.width} × ${tmp.height}</strong> `
                + (ok ? '<span class="text-success"><i class="fas fa-check-circle"></i> Sesuai</span>'
                      : `<span class="text-danger"><i class="fas fa-times-circle"></i> Harus ${reqW} × ${reqH}</span>`);
        };
        tmp.src = src;
    }

    // Image dimension preview for all inputs with data-req-w/h
    document.body.addEventListener('change', function(e) {
        const inp = e.target;
        if (inp.tagName !== 'INPUT' || inp.type !== 'file') return;
        const reqW = parseInt(inp.dataset.reqW || 0);
        const reqH = parseInt(inp.dataset.reqH || 0);
        if (!reqW || !reqH) return;
        const file = inp.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => {
            checkDim(ev.target.result, reqW, reqH, inp.parentElement?.querySelector('.dim-feedback'));
        };
        reader.readAsDataURL(file);
    });

    // Interior add row
    document.getElementById('btn-add-interior').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'row mb-3 dynamic-row align-items-center';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="file" name="interior_images[]" class="form-control gallery-input" data-req-w="4096" data-req-h="2298" accept="image/*">
                <div style="font-size:11px;color:#777;margin-top:3px;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i><strong>4096 × 2298</strong> px</div>
                <div class="dim-feedback" style="font-size:11px;margin-top:1px;"></div>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="interior_labels[]" placeholder="Nama area interior (cth: Ruang Tamu)">
            </div>
            <div class="col-md-2"><button type="button" class="btn btn-outline-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button></div>`;
        document.getElementById('container-interior-gallery').appendChild(row);
    });
});
</script>

{{-- Import Modal --}}
@if($importableProperties->count())
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-import me-2 text-primary"></i>Import dari Properti Lain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3" style="font-size:13px;">Pilih properti sebagai sumber. Data <strong>Spesifikasi, Fasilitas, Ekstra Fitur</strong>, dan <strong>Lokasi Sekitar</strong> akan menggantikan isian saat ini.</p>
                <input type="text" class="form-control mb-3" id="import-search" placeholder="Cari judul properti...">
                <div id="import-list">
                    @foreach($importableProperties as $ip)
                    <div class="import-item d-flex align-items-center gap-3 p-3 border rounded mb-2"
                         data-id="{{ $ip['id'] }}" data-title="{{ $ip['title'] }}"
                         style="cursor:pointer; transition:background 0.15s;"
                         onmouseover="this.style.background='#f4f8ff'" onmouseout="this.style.background=''">
                        <i class="fas fa-home text-primary fs-5"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:14px;">{{ $ip['title'] }}</div>
                            <div class="text-muted" style="font-size:12px;">ID: {{ $ip['id'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
