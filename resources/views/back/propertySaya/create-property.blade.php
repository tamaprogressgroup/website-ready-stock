@extends('back.layout.app')

@section('content')
<style>
    .bg-light-blue { background-color: #f8fbff; }
    .cursor-pointer { cursor: pointer; }
    .sidebar-widget { background: #fff; border-radius: 8px; padding: 25px 0; }
    .sidebar-title { font-size: 14px; font-weight: 800; color: #212529; padding: 0 25px 15px; text-transform: uppercase; letter-spacing: 0.5px; }
    .step-list { list-style: none; padding: 0; margin: 0; }
    .step-list li { padding: 14px 25px; font-size: 14px; font-weight: 600; color: #6c757d; display: flex; align-items: center; position: relative; cursor: pointer; transition: all 0.2s; }
    .step-list li:hover { background-color: #f8f9fa; }
    .step-list li.active { background-color: #f4f8ff; color: #1a56db; }
    .step-list li.active::after { content: ''; position: absolute; right: 0; top: 0; height: 100%; width: 4px; background-color: #1a56db; }
    .step-number { width: 26px; height: 26px; border-radius: 50%; background: #f3f4f6; color: #4b5563; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 12px; font-weight: bold; transition: all 0.2s; }
    .step-list li.active .step-number { background: #1a56db; color: #fff; }
    .radio-card input[type="radio"] { display: none; }
    .radio-card label { border: 1px solid #ced4da; border-radius: 6px; padding: 8px 16px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; color: #4b5563; font-size: 13px; transition: all 0.2s; background-color: #fff; margin-bottom: 0; }
    .radio-card input[type="radio"]:checked+label { border-color: #0d6efd; background-color: #f4f8ff; color: #0d6efd; font-weight: 600; }
    .badge-wajib { background-color: #e6f7f0; color: #00a651; font-size: 10px; padding: 4px 8px; border-radius: 4px; font-weight: 600; margin-left: 6px; }
    .tag-container { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
    .custom-tag { background-color: #e5e7eb; color: #4b5563; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; }
    .custom-tag .remove-tag { cursor: pointer; font-size: 11px; opacity: 0.7; }
    .label-toggle-item { display:inline-flex; align-items:stretch; border-radius:8px; overflow:hidden; border:2px solid #dee2e6; transition:border-color 0.18s; }
    .label-toggle-item.is-on { border-color:var(--chip-color); }
    .label-toggle-badge { padding:8px 16px; color:#fff; font-weight:700; font-size:13px; white-space:nowrap; }
    .label-toggle-btn { padding:8px 18px; border:none; font-weight:700; font-size:13px; cursor:pointer; background:#f3f4f6; color:#9ca3af; transition:all 0.18s; white-space:nowrap; }
    .label-toggle-item.is-on .label-toggle-btn { background:var(--chip-color); color:#fff; }
    .label-toggle-item.is-disabled .label-toggle-btn { opacity:0.4; cursor:not-allowed; }
    .btn-remove-row { border-radius: 4px; border: 1px solid #ced4da; color: #dc3545; background: #fff; }
    .btn-remove-row:hover { background-color: #dc3545; color: #fff; border-color: #dc3545; }
    .btn-add-dynamic { background-color: #f8f9fa; border: 1px solid #ced4da; color: #212529; font-weight: 600; font-size: 13px; padding: 8px 16px; border-radius: 4px; }
    .btn-add-dynamic:hover { background-color: #e2e6ea; }
    .upload-box-cover { border: 2px dashed #0d6efd; background-color: #f4f8ff; border-radius: 8px; text-align: center; padding: 40px 20px; }
    .wizard-step { animation: fadeIn 0.3s ease-in-out; }
    .facility-row { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 10px; }
    .icon-preview { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; background:#f3f4f6; border-radius:6px; font-size:16px; color:#374151; }
    .fac-img-thumb { width:60px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #dee2e6; }
    .import-btn { background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff; border:none; border-radius:8px; padding:9px 20px; font-weight:600; font-size:13px; cursor:pointer; transition:opacity 0.2s; }
    .import-btn:hover { opacity:0.88; color:#fff; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
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

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-3 pe-lg-4 mb-4">
            <div class="sidebar-widget border border-light shadow-sm mb-4">
                <div class="sidebar-title">Tahapan Pasang Iklan</div>
                <ul class="step-list" id="wizard-sidebar">
                    <li class="active" data-step="1"><span class="step-number">1</span> Kategori</li>
                    <li data-step="2"><span class="step-number">2</span> Info & Spesifikasi</li>
                    <li data-step="3"><span class="step-number">3</span> Fasilitas & Lokasi</li>
                    <li data-step="4"><span class="step-number">4</span> Harga & Media</li>
                </ul>
            </div>
            <a href="{{ route('customer.property') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="col-lg-9">
            @if($errors->any())
                <div class="alert alert-danger shadow-sm border-0 mb-4 rounded">
                    <div class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i> Mohon periksa kembali:</div>
                    <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger rounded mb-4">{{ session('error') }}</div>
            @endif

            <form action="{{ route('customer.property.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- STEP 1: Kategori --}}
                <div id="step-content-1" class="wizard-step bg-white p-4 p-lg-5 rounded-lg shadow-sm border border-light">
                    <h3 class="fw-bold text-dark mb-4 border-bottom pb-3">1. Kategori Properti</h3>

                    <div class="mb-4">
                        <label class="form-label text-dark fw-semibold mb-3">Tipe Properti <span class="badge-wajib">Wajib</span></label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($property_type as $key => $type)
                            <div class="radio-card">
                                <input type="radio" name="tipe_properti" value="{{ $type['property_type_id'] ?? $key }}" id="tipe_{{ $key }}"
                                    {{ old('tipe_properti', $loop->first ? ($type['property_type_id'] ?? $key) : '') == ($type['property_type_id'] ?? $key) ? 'checked' : '' }}>
                                <label for="tipe_{{ $key }}">
                                    <i class="{{ $type['icon_class'] ?? 'fas fa-home' }}"></i>
                                    {{ $type['translations'][0]['type_name'] ?? '-' }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-dark fw-semibold mb-3">Kondisi Properti <span class="badge-wajib">Wajib</span></label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($propertyConditions as $key => $condition)
                            <div class="radio-card">
                                <input type="radio" name="property_condition" id="cond_{{ $key }}" value="{{ $condition['property_condition_id'] }}"
                                    {{ old('property_condition', $loop->first ? $condition['property_condition_id'] : '') == $condition['property_condition_id'] ? 'checked' : '' }}>
                                <label for="cond_{{ $key }}">
                                    <i class="{{ $condition['icon_class'] ?? 'fas fa-tag' }}"></i>
                                    {{ $condition['translations'][0]['condition_name'] ?? '-' }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-semibold">Provinsi <span class="badge-wajib">Wajib</span></label>
                            <select name="provinsi_id" id="select-provinsi" class="form-select" style="border-radius:8px; height:44px;">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->provinsi_id }}" {{ old('provinsi_id') == $prov->provinsi_id ? 'selected' : '' }}>{{ $prov->provinsi_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-semibold">Kota / Kabupaten <span class="badge-wajib">Wajib</span></label>
                            <select name="kota_id" id="select-kota" class="form-select" style="border-radius:8px; height:44px;">
                                <option value="">-- Pilih Provinsi Terlebih Dahulu --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-semibold">Proyek / Township</label>
                            <select name="township_id" class="form-select" style="border-radius:8px; height:44px;">
                                <option value="">-- Pilih Township (Opsional) --</option>
                                @foreach($townships as $township)
                                    <option value="{{ $township->township_id }}" {{ old('township_id') == $township->township_id ? 'selected' : '' }}>{{ $township->township_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-semibold">Cluster</label>
                            <select name="cluster_id" class="form-select" style="border-radius:8px; height:44px;">
                                <option value="">-- Pilih Cluster (Opsional) --</option>
                                @foreach($clusters as $cluster)
                                    <option value="{{ $cluster->cluster_id }}" {{ old('cluster_id') == $cluster->cluster_id ? 'selected' : '' }}>{{ $cluster->cluster_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-primary px-4 py-2 fw-semibold btn-next" data-next="2">
                            Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                {{-- STEP 2: Info & Spesifikasi --}}
                <div id="step-content-2" class="wizard-step d-none bg-white p-4 p-lg-5 rounded-lg shadow-sm border border-light">
                    <h3 class="fw-bold text-dark mb-4 border-bottom pb-3">2. Info & Spesifikasi</h3>

                    <div class="mb-4">
                        <label class="form-label text-dark fw-semibold">Judul Properti <span class="badge-wajib">Wajib</span></label>
                        <input type="text" class="form-control py-2" name="title" value="{{ old('title') }}" placeholder="Contoh: Rumah Modern 2 Lantai di Serpong">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-dark fw-semibold">No. HP / WhatsApp Agen</label>
                        <p class="text-muted mb-2" style="font-size:12px;">Nomor ini digunakan untuk tombol WhatsApp di halaman properti. Format: 08xxx atau 628xxx.</p>
                        <input type="tel" class="form-control py-2" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08172856666 atau 628172856666">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-dark fw-semibold">Deskripsi Properti <span class="badge-wajib">Wajib</span></label>
                        <textarea class="form-control" name="description" rows="5" placeholder="Ceritakan detail menarik tentang properti ini...">{{ old('description') }}</textarea>
                    </div>

                    {{-- SEO Meta --}}
                    <div class="mb-4 p-4 rounded border" style="background:#f8faff;">
                        <h5 class="fw-bold text-dark mb-1"><i class="fas fa-search-plus me-2 text-primary"></i>SEO Meta</h5>
                        <p class="text-muted mb-4" style="font-size:12px;">Opsional. Digunakan untuk pengaturan tampilan di mesin pencari.</p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px;">Meta Title</label>
                            <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}"
                                placeholder="Judul halaman di mesin pencari (maks. 60 karakter)" maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:13px;">Meta Keyword</label>
                            <input type="text" class="form-control" name="meta_keyword" value="{{ old('meta_keyword') }}"
                                placeholder="Kata kunci, pisahkan dengan koma">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" style="font-size:13px;">Meta Description</label>
                            <textarea class="form-control" name="meta_descriotion" rows="2"
                                placeholder="Deskripsi singkat untuk mesin pencari (maks. 160 karakter)">{{ old('meta_descriotion') }}</textarea>
                        </div>
                    </div>

                    <div class="mb-5 p-4 rounded bg-light border">
                        <h5 class="fw-bold text-dark mb-1">Tag & Label Iklan</h5>
                        <p class="text-muted mb-4" style="font-size:13px;">Pilih label iklan (maksimal 2) dan tambahkan tag bebas untuk menonjolkan fitur properti.</p>
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="font-size:13px;">Label Iklan <span class="text-muted fw-normal">(maks. 2 aktif)</span></label>
                            <div class="d-flex flex-wrap gap-3 mt-2">
                                @foreach($labelTags as $lt)
                                @php $isOn = in_array($lt->tag_id, old('label_tag_ids', [])); @endphp
                                <div class="label-toggle-item {{ $isOn ? 'is-on' : '' }}" id="lti-{{ $lt->tag_id }}" style="--chip-color: {{ $lt->color_code }};">
                                    <span class="label-toggle-badge" style="background:{{ $lt->color_code }};">{{ $lt->name }}</span>
                                    <button type="button" class="label-toggle-btn">{{ $isOn ? 'ON' : 'OFF' }}</button>
                                    <input type="hidden" name="label_tag_ids[]" value="{{ $lt->tag_id }}" class="label-hidden-input" {{ $isOn ? '' : 'disabled' }}>
                                </div>
                                @endforeach
                            </div>
                            <div id="label-limit-msg" class="text-danger mt-2" style="font-size:12px; display:none;">Maksimal 2 label iklan yang dapat diaktifkan.</div>
                        </div>
                        <div>
                            <label class="form-label fw-semibold" style="font-size:13px;">Tag Tambahan <span class="text-muted fw-normal">(opsional)</span></label>
                            <p class="text-muted mb-2" style="font-size:12px;">Misal: Extended Area, Hook. Tidak boleh sama dengan nama label di atas.</p>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="tag-input" placeholder="Ketik tag lalu tekan Enter atau klik Tambah">
                                <button class="btn btn-primary" type="button" id="btn-add-tag"><i class="fas fa-plus me-1"></i> Tambah</button>
                            </div>
                            <div class="tag-container" id="tag-container">
                                @foreach(old('custom_tags', []) as $ct)
                                    <span class="custom-tag">{{ $ct }} <i class="fas fa-times ms-1 remove-tag"></i><input type="hidden" name="custom_tags[]" value="{{ $ct }}"></span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-3">Dimensi Utama</h5>
                    <div class="row mb-5">
                        <div class="col-md-3 mb-3"><label class="form-label">Kamar Tidur</label><input type="number" class="form-control" name="bedrooms" value="{{ old('bedrooms') }}" placeholder="0" min="0"></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Kamar Mandi</label><input type="number" class="form-control" name="bathrooms" value="{{ old('bathrooms') }}" placeholder="0" min="0"></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Luas Tanah</label>
                            <div class="input-group"><input type="number" class="form-control" name="land_area" value="{{ old('land_area') }}" placeholder="0" min="0"><span class="input-group-text">m²</span></div></div>
                        <div class="col-md-3 mb-3"><label class="form-label">Luas Bangunan</label>
                            <div class="input-group"><input type="number" class="form-control" name="building_area" value="{{ old('building_area') }}" placeholder="0" min="0"><span class="input-group-text">m²</span></div></div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="fw-bold text-dark mb-0">Spesifikasi Detail <span class="text-muted fw-normal ms-2" style="font-size:13px;">(Opsional)</span></h5>
                    </div>
                    <div id="container-spesifikasi">
                        @php $oldSpecKeys = old('spec_keys', ['']); $oldSpecValues = old('spec_values', ['']); @endphp
                        @foreach($oldSpecKeys as $i => $k)
                        <div class="d-flex mb-2 dynamic-input-group dynamic-row gap-2">
                            <input type="text" class="form-control py-2 w-50" name="spec_keys[]" value="{{ $k }}" placeholder="Nama Spesifikasi">
                            <input type="text" class="form-control py-2 w-50" name="spec_values[]" value="{{ $oldSpecValues[$i] ?? '' }}" placeholder="Nilai">
                            <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-add-dynamic mt-2" id="btn-add-spesifikasi"><i class="fas fa-plus me-1"></i> Tambah Spesifikasi</button>

                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                        <button type="button" class="btn btn-light border px-4 py-2 fw-semibold btn-prev" data-prev="1"><i class="fas fa-arrow-left me-2"></i> Kembali</button>
                        <button type="button" class="btn btn-primary px-4 py-2 fw-semibold btn-next" data-next="3">Selanjutnya <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>

                {{-- STEP 3: Fasilitas & Lokasi --}}
                <div id="step-content-3" class="wizard-step d-none bg-white p-4 p-lg-5 rounded-lg shadow-sm border border-light">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                        <h3 class="fw-bold text-dark mb-0">3. Fasilitas & Lokasi</h3>
                        @if($importableProperties->count())
                        <button type="button" class="import-btn" id="btn-open-import">
                            <i class="fas fa-file-import me-1"></i> Import dari Properti Lain
                        </button>
                        @endif
                    </div>

                    {{-- Fasilitas --}}
                    <h5 class="fw-bold text-dark mb-1">Fasilitas Properti</h5>
                    <p class="text-muted mb-3" style="font-size:13px;">Nama fasilitas, ikon CSS class (mis. <code>fas fa-swimming-pool</code>), dan gambar opsional.</p>
                    <div id="container-fasilitas">
                        @foreach(old('facility_names', ['']) as $fi => $fn)
                        <div class="facility-row dynamic-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <label class="form-label mb-1" style="font-size:12px;">Nama Fasilitas</label>
                                    <input type="text" class="form-control py-2" name="facility_names[]" value="{{ $fn }}" placeholder="Contoh: Kolam Renang">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label mb-1" style="font-size:12px;">Ikon</label>
                                    @php $facIcon = old('facility_icons.'.$fi, 'fas fa-check'); @endphp
                                    <button type="button" class="btn icon-picker-btn">
                                        <i class="{{ $facIcon }}"></i>
                                        <span class="ip-label">{{ $facIcon }}</span>
                                    </button>
                                    <input type="hidden" name="facility_icons[]" value="{{ $facIcon }}" class="icon-picker-val">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label mb-1" style="font-size:12px;">Gambar (opsional)</label>
                                    <input type="file" class="form-control" name="facility_images[]" accept="image/*">
                                    <input type="hidden" name="facility_existing_imgs[]" value="">
                                </div>
                                <div class="col-md-2 d-flex align-items-end pb-1">
                                    <button type="button" class="btn btn-remove-row px-3 btn-delete w-100"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-add-dynamic mt-2 mb-5" id="btn-add-fasilitas"><i class="fas fa-plus me-1"></i> Tambah Fasilitas</button>

                    {{-- Ekstra Fitur --}}
                    <h5 class="fw-bold text-dark mb-1">Ekstra Fitur</h5>
                    <p class="text-muted mb-3" style="font-size:13px;">Fitur unggulan tambahan dengan ikon CSS class dan nama.</p>
                    <div id="container-extra">
                        @foreach(old('extra_names', ['']) as $ei => $en)
                        <div class="facility-row dynamic-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-5">
                                    <label class="form-label mb-1" style="font-size:12px;">Nama Fitur</label>
                                    <input type="text" class="form-control py-2" name="extra_names[]" value="{{ $en }}" placeholder="Contoh: Smart Home System">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label mb-1" style="font-size:12px;">Ikon</label>
                                    @php $extraIcon = old('extra_icons.'.$ei, 'fas fa-star'); @endphp
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
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-add-dynamic mt-2 mb-5" id="btn-add-extra"><i class="fas fa-plus me-1"></i> Tambah Ekstra Fitur</button>

                    {{-- Lokasi Sekitar --}}
                    <h5 class="fw-bold text-dark mb-1">Lokasi Sekitar</h5>
                    <p class="text-muted mb-3" style="font-size:13px;">Misal: Mall, Sekolah, Rumah Sakit, Tol terdekat.</p>
                    <div id="container-nearby">
                        @foreach(old('nearby_names', ['']) as $nn)
                        <div class="d-flex mb-2 dynamic-row gap-2">
                            <input type="text" class="form-control py-2" name="nearby_names[]" value="{{ $nn }}" placeholder="Contoh: Tol Serpong, SMAN 1 Tangerang">
                            <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-add-dynamic mt-2 mb-5" id="btn-add-nearby"><i class="fas fa-plus me-1"></i> Tambah Lokasi Sekitar</button>

                    {{-- Koordinat --}}
                    <h5 class="fw-bold text-dark mb-3">Koordinat Peta <span class="text-muted fw-normal ms-2" style="font-size:13px;">(Opsional)</span></h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" class="form-control" name="latitude" value="{{ old('latitude') }}" placeholder="Contoh: -6.2297">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" class="form-control" name="longtidure" value="{{ old('longtidure') }}" placeholder="Contoh: 106.6873">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                        <button type="button" class="btn btn-light border px-4 py-2 fw-semibold btn-prev" data-prev="2"><i class="fas fa-arrow-left me-2"></i> Kembali</button>
                        <button type="button" class="btn btn-primary px-4 py-2 fw-semibold btn-next" data-next="4">Selanjutnya <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>

                {{-- STEP 4: Harga & Media --}}
                <div id="step-content-4" class="wizard-step d-none bg-white p-4 p-lg-5 rounded-lg shadow-sm border border-light">
                    <h3 class="fw-bold text-dark mb-4 border-bottom pb-3">4. Harga & Media</h3>

                    <div class="row mb-5">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Harga Properti <span class="badge-wajib">Wajib</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" class="form-control py-2" name="price" value="{{ old('price') }}" placeholder="Misal: 1.100.000.000">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Diskon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" class="form-control py-2" name="discount" value="{{ old('discount') }}" placeholder="Misal: 50.000.000">
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">Media & Foto Properti</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Main Thumbnail (Utama) <span class="badge-wajib">Wajib</span></label>
                            <p class="text-muted mb-2" style="font-size:12px;">Muncul paling besar di hasil pencarian.</p>
                            <label for="upload-main" class="w-100 cursor-pointer m-0">
                                <div class="upload-box-cover py-4" id="preview-container-main">
                                    <div class="upload-placeholder">
                                        <i class="fas fa-image fs-2 text-primary mb-2"></i>
                                        <h6 class="fw-bold text-dark mb-1">Upload Main Cover</h6>
                                        <span class="text-primary fw-semibold" style="font-size:13px;">Klik untuk pilih file</span>
                                    </div>
                                    <img src="" class="img-fluid d-none rounded" style="max-height:200px;" id="img-preview-main">
                                </div>
                            </label>
                            <input type="file" id="upload-main" name="main_thumbnail" class="d-none preview-input" data-target="main" data-req-w="4096" data-req-h="2298" accept="image/*">
                            <div class="mt-1 ps-1" style="font-size:11px; color:#777;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i>Dimensi: <strong>4096 × 2298</strong> px &nbsp;|&nbsp; Maks 10MB</div>
                            <div id="dim-feedback-main" style="font-size:11px; margin-top:2px;"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Mini Thumbnail <span class="badge-wajib">Wajib</span></label>
                            <p class="text-muted mb-2" style="font-size:12px;">Preview kecil di halaman listing.</p>
                            <label for="upload-mini" class="w-100 cursor-pointer m-0">
                                <div class="upload-box-cover py-4 border-secondary" id="preview-container-mini" style="background-color:#fcfcfc; border-color:#6c757d !important;">
                                    <div class="upload-placeholder">
                                        <i class="fas fa-th fs-2 text-secondary mb-2"></i>
                                        <h6 class="fw-bold text-dark mb-1">Upload Mini Photo</h6>
                                        <span class="text-secondary fw-semibold" style="font-size:13px;">Klik untuk pilih file</span>
                                    </div>
                                    <img src="" class="img-fluid d-none rounded" style="max-height:200px;" id="img-preview-mini">
                                </div>
                            </label>
                            <input type="file" id="upload-mini" name="mini_thumbnail" class="d-none preview-input" data-target="mini" data-req-w="4096" data-req-h="2414" accept="image/*">
                            <div class="mt-1 ps-1" style="font-size:11px; color:#777;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i>Dimensi: <strong>4096 × 2414</strong> px &nbsp;|&nbsp; Maks 10MB</div>
                            <div id="dim-feedback-mini" style="font-size:11px; margin-top:2px;"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Interior & Ruangan</label>
                        <div id="container-interior-gallery">
                            <div class="row mb-3 dynamic-row border-bottom pb-3 align-items-center">
                                <div class="col-md-2">
                                    <div class="border rounded bg-light d-flex align-items-center justify-content-center" style="width:80px;height:80px;overflow:hidden;">
                                        <i class="fas fa-camera text-muted gallery-icon"></i>
                                        <img src="" class="img-cover d-none gallery-preview" style="width:100%;height:100%;object-fit:cover;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" name="interior_images[]" class="form-control-file gallery-input" data-req-w="4096" data-req-h="2298" accept="image/*">
                                    <div style="font-size:11px; color:#777; margin-top:3px;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i><strong>4096 × 2298</strong> px</div>
                                    <div class="dim-feedback" style="font-size:11px; margin-top:1px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="interior_labels[]" placeholder="Nama area interior (cth: Ruang Tamu)">
                                </div>
                                <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-add-dynamic mt-2 w-100" id="btn-add-interior"><i class="fas fa-plus-circle me-1"></i> Tambah Foto Ruangan</button>
                    </div>

                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2 mt-2">Media Video</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">URL Virtual Tour 360°</label>
                            <input type="url" class="form-control py-2" name="url_360" value="{{ old('url_360') }}" placeholder="https://...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">URL YouTube</label>
                            <input type="url" class="form-control py-2" name="url_youtube" value="{{ old('url_youtube') }}" placeholder="https://youtube.com/...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Upload Video Properti</label>
                            <p class="text-muted mb-2" style="font-size:12px;">Format: mp4, mov, avi. Maks 50MB.</p>
                            <input type="file" class="form-control py-2" name="video_file" accept="video/mp4,video/quicktime,video/x-msvideo">
                        </div>
                    </div>

                    <div class="alert alert-info rounded-3" style="font-size:13px;">
                        <i class="fas fa-info-circle me-1"></i>
                        Properti akan disimpan sebagai <strong>Draft</strong>. Tayangkan dari halaman daftar setelah selesai.
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-light border px-4 py-2 fw-semibold btn-prev" data-prev="3"><i class="fas fa-arrow-left me-2"></i> Kembali</button>
                        <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow-sm" style="font-size:15px;">
                            <i class="fas fa-save me-2"></i> Simpan sebagai Draft
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

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
                    <div class="import-item d-flex align-items-center gap-3 p-3 border rounded mb-2 cursor-pointer"
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

{{-- Icon Picker Panel --}}
<div id="gip" style="display:none;">
    <input type="text" id="gip-search" placeholder="Cari ikon..." autocomplete="off">
    <div id="gip-grid"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wizard navigation
    const sidebarItems = document.querySelectorAll('#wizard-sidebar li');
    const stepContents = document.querySelectorAll('.wizard-step');
    const nextBtns     = document.querySelectorAll('.btn-next');
    const prevBtns     = document.querySelectorAll('.btn-prev');

    @if($errors->any()) window.scrollTo({ top: 0, behavior: 'smooth' }); @endif

    function goToStep(n) {
        sidebarItems.forEach(i => i.classList.remove('active'));
        stepContents.forEach(c => c.classList.add('d-none'));
        document.querySelector(`#wizard-sidebar li[data-step="${n}"]`).classList.add('active');
        document.getElementById(`step-content-${n}`).classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    sidebarItems.forEach(i => i.addEventListener('click', () => goToStep(i.dataset.step)));
    nextBtns.forEach(b  => b.addEventListener('click',  () => goToStep(b.dataset.next)));
    prevBtns.forEach(b  => b.addEventListener('click',  () => goToStep(b.dataset.prev)));

    // Provinsi → Kota AJAX
    const selectProvinsi = document.getElementById('select-provinsi');
    const selectKota     = document.getElementById('select-kota');
    const oldKotaId      = '{{ old('kota_id') }}';

    function loadKota(provinsiId, selectedId) {
        if (!provinsiId) { selectKota.innerHTML = '<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>'; return; }
        selectKota.innerHTML = '<option value="">Memuat...</option>';
        fetch('/api/data/cities/' + provinsiId)
            .then(r => r.json())
            .then(res => {
                const list = res.data || [];
                selectKota.innerHTML = '<option value="">-- Pilih Kota / Kabupaten --</option>';
                list.forEach(k => {
                    const o = document.createElement('option');
                    o.value = k.kota_id; o.textContent = k.nama_kota;
                    if (selectedId && k.kota_id == selectedId) o.selected = true;
                    selectKota.appendChild(o);
                });
            }).catch(() => { selectKota.innerHTML = '<option value="">Gagal memuat kota</option>'; });
    }
    selectProvinsi.addEventListener('change', function() { loadKota(this.value, null); });
    if (selectProvinsi.value) loadKota(selectProvinsi.value, oldKotaId);

    // Label ON/OFF
    const LABEL_NAMES   = @json($labelTags->pluck('name')->map(fn($n) => strtolower($n)));
    const toggleItems   = document.querySelectorAll('.label-toggle-item');
    const labelLimitMsg = document.getElementById('label-limit-msg');

    function turnLabelOn(item)  { item.classList.add('is-on'); item.classList.remove('is-disabled'); item.querySelector('.label-toggle-btn').textContent = 'ON';  item.querySelector('.label-hidden-input').disabled = false; }
    function turnLabelOff(item) { item.classList.remove('is-on'); item.querySelector('.label-toggle-btn').textContent = 'OFF'; item.querySelector('.label-hidden-input').disabled = true; }
    function updateLabelState() {
        const onCount = document.querySelectorAll('.label-toggle-item.is-on').length;
        labelLimitMsg.style.display = onCount >= 2 ? 'block' : 'none';
        toggleItems.forEach(item => { if (!item.classList.contains('is-on')) { onCount >= 2 ? item.classList.add('is-disabled') : item.classList.remove('is-disabled'); } });
    }
    toggleItems.forEach(item => {
        item.querySelector('.label-toggle-btn').addEventListener('click', function() {
            if (item.classList.contains('is-on')) turnLabelOff(item);
            else { if (item.classList.contains('is-disabled')) return; turnLabelOn(item); }
            updateLabelState();
        });
    });
    updateLabelState();

    // Custom tags
    const tagInput     = document.getElementById('tag-input');
    const btnAddTag    = document.getElementById('btn-add-tag');
    const tagContainer = document.getElementById('tag-container');

    function addTag() {
        const val = tagInput.value.trim();
        if (!val) return;
        if (LABEL_NAMES.includes(val.toLowerCase())) { alert('"' + val + '" adalah nama label iklan default.'); return; }
        const existing = [...document.querySelectorAll('#tag-container input[name="custom_tags[]"]')].map(i => i.value.toLowerCase());
        if (existing.includes(val.toLowerCase())) { alert('Tag ini sudah ditambahkan.'); return; }
        const span = document.createElement('span');
        span.className = 'custom-tag';
        span.innerHTML = `${val} <i class="fas fa-times ms-1 remove-tag"></i>`;
        const hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'custom_tags[]'; hidden.value = val;
        span.appendChild(hidden); tagContainer.appendChild(span); tagInput.value = '';
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
        div.innerHTML = `<input type="text" class="form-control py-2" name="nearby_names[]" value="${name||''}" placeholder="Contoh: Tol Serpong, SMAN 1 Tangerang">
                         <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>`;
        return div;
    }

    function makeSpecRow(key, val) {
        const div = document.createElement('div');
        div.className = 'd-flex mb-2 dynamic-input-group dynamic-row gap-2';
        div.innerHTML = `<input type="text" class="form-control py-2 w-50" name="spec_keys[]" value="${key||''}" placeholder="Nama Spesifikasi">
                         <input type="text" class="form-control py-2 w-50" name="spec_values[]" value="${val||''}" placeholder="Nilai">
                         <button type="button" class="btn btn-remove-row px-3 btn-delete"><i class="fas fa-trash"></i></button>`;
        return div;
    }

    document.getElementById('btn-add-fasilitas').addEventListener('click', () => { document.getElementById('container-fasilitas').appendChild(makeFacilityRow()); });
    document.getElementById('btn-add-extra').addEventListener('click', () => { document.getElementById('container-extra').appendChild(makeExtraRow()); });
    document.getElementById('btn-add-nearby').addEventListener('click', () => { document.getElementById('container-nearby').appendChild(makeNearbyRow()); });
    document.getElementById('btn-add-spesifikasi').addEventListener('click', () => { document.getElementById('container-spesifikasi').appendChild(makeSpecRow()); });

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

    // Image previews (thumbnail + gallery + facility)
    document.body.addEventListener('change', function(e) {
        if (e.target.classList.contains('preview-input')) {
            const target = e.target.dataset.target;
            const reqW = parseInt(e.target.dataset.reqW || 0);
            const reqH = parseInt(e.target.dataset.reqH || 0);
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = ev => {
                    const img = document.getElementById(`img-preview-${target}`);
                    const ph  = document.querySelector(`#preview-container-${target} .upload-placeholder`);
                    img.src = ev.target.result; img.classList.remove('d-none');
                    if (ph) ph.classList.add('d-none');
                    checkDim(ev.target.result, reqW, reqH, document.getElementById(`dim-feedback-${target}`));
                };
                reader.readAsDataURL(file);
            }
        }
        if (e.target.classList.contains('gallery-input')) {
            const file = e.target.files[0];
            if (file) {
                const row = e.target.closest('.dynamic-row');
                const reqW = parseInt(e.target.dataset.reqW || 0);
                const reqH = parseInt(e.target.dataset.reqH || 0);
                const reader = new FileReader();
                reader.onload = ev => {
                    const img = row.querySelector('.gallery-preview');
                    const ico = row.querySelector('.gallery-icon');
                    if (img) { img.src = ev.target.result; img.classList.remove('d-none'); }
                    if (ico) ico.classList.add('d-none');
                    checkDim(ev.target.result, reqW, reqH, e.target.parentElement?.querySelector('.dim-feedback'));
                };
                reader.readAsDataURL(file);
            }
        }
        if (e.target.classList.contains('fac-img-input')) {
            const file = e.target.files[0];
            if (file) {
                const reqW = parseInt(e.target.dataset.reqW || 0);
                const reqH = parseInt(e.target.dataset.reqH || 0);
                const reader = new FileReader();
                reader.onload = ev => {
                    checkDim(ev.target.result, reqW, reqH, e.target.parentElement?.querySelector('.dim-feedback'));
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // Add interior row
    document.getElementById('btn-add-interior').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'row mb-3 dynamic-row border-bottom pb-3 align-items-center';
        row.innerHTML = `
            <div class="col-md-2"><div class="border rounded bg-light d-flex align-items-center justify-content-center" style="width:80px;height:80px;overflow:hidden;"><i class="fas fa-camera text-muted gallery-icon"></i><img src="" class="img-cover d-none gallery-preview" style="width:100%;height:100%;object-fit:cover;"></div></div>
            <div class="col-md-4"><input type="file" name="interior_images[]" class="form-control-file gallery-input" data-req-w="4096" data-req-h="2298" accept="image/*"><div style="font-size:11px;color:#777;margin-top:3px;"><i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i><strong>4096 × 2298</strong> px</div><div class="dim-feedback" style="font-size:11px;margin-top:1px;"></div></div>
            <div class="col-md-4"><input type="text" class="form-control" name="interior_labels[]" placeholder="Nama area interior (cth: Ruang Tamu)"></div>
            <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button></div>`;
        document.getElementById('container-interior-gallery').appendChild(row);
    });

    // ---- Import Modal ----
    const btnOpenImport = document.getElementById('btn-open-import');
    if (btnOpenImport) {
        const importModal = new bootstrap.Modal(document.getElementById('importModal'));
        btnOpenImport.addEventListener('click', () => importModal.show());

        // Search filter
        document.getElementById('import-search').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.import-item').forEach(item => {
                item.style.display = item.dataset.title.toLowerCase().includes(q) ? '' : 'none';
            });
        });

        // Click import item
        document.getElementById('import-list').addEventListener('click', function(e) {
            const item = e.target.closest('.import-item');
            if (!item) return;
            const id = item.dataset.id;
            fetch(`/customer/property/${id}/import-data`)
                .then(r => r.json())
                .then(data => {
                    applyImport(data);
                    importModal.hide();
                })
                .catch(() => alert('Gagal mengambil data properti.'));
        });
    }

    function applyImport(data) {
        // Replace specs
        const specCont = document.getElementById('container-spesifikasi');
        specCont.innerHTML = '';
        const specs = data.specs || [];
        (specs.length ? specs : [{}]).forEach(s => specCont.appendChild(makeSpecRow(s.key||'', s.value||'')));

        // Replace facilities
        const facCont = document.getElementById('container-fasilitas');
        facCont.innerHTML = '';
        const facs = data.facilities || [];
        (facs.length ? facs : [{}]).forEach(f => facCont.appendChild(makeFacilityRow(f.name||'', f.icon_url||'', f.image_url||null, f.image_path||'')));

        // Replace extra features
        const extraCont = document.getElementById('container-extra');
        extraCont.innerHTML = '';
        const extras = data.extras || [];
        (extras.length ? extras : [{}]).forEach(e => extraCont.appendChild(makeExtraRow(e.name||'', e.icon_url||'')));

        // Replace nearby
        const nearbyCont = document.getElementById('container-nearby');
        nearbyCont.innerHTML = '';
        const nearby = data.nearby || [];
        (nearby.length ? nearby : [{}]).forEach(n => nearbyCont.appendChild(makeNearbyRow(n.name||'')));
    }
});
</script>
@endsection
