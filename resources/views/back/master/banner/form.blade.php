@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">

    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="mb-4">
            <a href="{{ route('master.banner.index') }}" class="text-decoration-none text-muted" style="font-size:13px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="font-weight-bold mt-2 mb-0" style="color: #3065A3;">
                {{ $item ? 'Edit Banner' : 'Tambah Banner' }}
            </h4>
        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 mb-4">
                <ul class="mb-0 ps-3" style="font-size:13px;">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:12px; max-width:600px;">
            <div class="card-body p-4">
                <form action="{{ $item ? route('master.banner.update', $item->id) : route('master.banner.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($item) @method('PUT') @endif

                    {{-- Gambar Banner --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Gambar Banner {!! $item ? '<span class="text-muted fw-normal">(kosongkan jika tidak ingin mengganti)</span>' : '<span class="text-danger">*</span>' !!}
                        </label>
                        <div class="mb-2 p-3 rounded-3" style="background:#f0f4f8; font-size:12px; border:1px dashed #bcd0e8;">
                            <i class="fas fa-ruler-combined me-1" style="color:#3065A3;"></i>
                            Dimensi wajib: <strong>3520 × 1216 piksel</strong> &nbsp;|&nbsp;
                            <i class="fas fa-weight-hanging me-1" style="color:#3065A3;"></i>
                            Maks: <strong>10 MB</strong> &nbsp;|&nbsp;
                            Format: JPG, PNG, WebP
                        </div>
                        @if($item && $item->image_url)
                            <div class="mb-2">
                                <img src="{{ Storage::disk('public')->url($item->image_url) }}" alt="current"
                                     style="width:260px; height:97px; object-fit:cover; border-radius:8px; border:1px solid #eee;">
                                <p class="text-muted mt-1" style="font-size:11px;">Gambar saat ini.</p>
                            </div>
                        @endif
                        <input type="file" name="image" id="banner-image"
                               class="form-control @error('image') is-invalid @enderror"
                               style="border-radius:8px;" accept="image/*"
                               onchange="previewBanner(this)"
                               {{ $item ? '' : 'required' }}>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="banner-preview" class="mt-2" style="display:none;">
                            <img id="banner-preview-img" src="" alt="preview"
                                 style="width:260px; height:97px; object-fit:cover; border-radius:8px; border:2px dashed #3065A3;">
                            <p class="mt-1 mb-0" style="font-size:11px;" id="banner-dim-text"></p>
                        </div>
                    </div>

                    {{-- Posisi (dropdown) --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Posisi Banner <span class="text-danger">*</span></label>
                        <select name="position" class="form-select @error('position') is-invalid @enderror"
                                style="border-radius:8px; height:44px;" required>
                            <option value="">-- Pilih Posisi --</option>
                            @foreach($positions as $key => $label)
                                <option value="{{ $key }}" {{ old('position', $item?->position) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Prioritas --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Prioritas / Urutan <span class="text-danger">*</span></label>
                        <p class="text-muted mb-2" style="font-size:12px;">Angka lebih kecil tampil lebih dulu. cth: 1 = paling atas.</p>
                        <input type="number" name="priority" min="1"
                               class="form-control @error('priority') is-invalid @enderror"
                               style="border-radius:8px; height:44px; width:120px;"
                               value="{{ old('priority', $item?->priority ?? 1) }}" required>
                        @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Target URL --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Target URL <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="url" name="target_url"
                               class="form-control @error('target_url') is-invalid @enderror"
                               style="border-radius:8px; height:44px;"
                               value="{{ old('target_url', $item?->target_url) }}"
                               placeholder="https://...">
                        @error('target_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $item?->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active" style="font-size:13px;">Aktif</label>
                        </div>
                    </div>

                    {{-- Info created/updated --}}
                    @if($item)
                    <div class="mb-4 p-3 rounded-3" style="background:#f8f9fa; font-size:12px; color:#888; border:1px solid #eee;">
                        <div><i class="fas fa-user-plus me-1"></i> Dibuat oleh: <strong>{{ $item->creator?->name ?? 'Sistem' }}</strong></div>
                        <div class="mt-1"><i class="fas fa-clock me-1"></i> Waktu buat: <strong>{{ $item->craeted_datetime ? \Carbon\Carbon::parse($item->craeted_datetime)->format('d M Y, H:i') : '-' }}</strong></div>
                        @if($item->updater)
                        <div class="mt-1"><i class="fas fa-user-edit me-1"></i> Terakhir diubah: <strong>{{ $item->updater->name }}</strong>
                            @if($item->updated_datetime) pada {{ \Carbon\Carbon::parse($item->updated_datetime)->format('d M Y, H:i') }} @endif
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4" style="background:#3065A3; border-color:#3065A3; border-radius:8px;">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="{{ route('master.banner.index') }}" class="btn btn-outline-secondary px-4" style="border-radius:8px;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function previewBanner(input) {
    const preview = document.getElementById('banner-preview');
    const img     = document.getElementById('banner-preview-img');
    const dimText = document.getElementById('banner-dim-text');

    if (!input.files || !input.files[0]) { preview.style.display = 'none'; return; }

    const file = input.files[0];

    if (file.size > 10 * 1024 * 1024) {
        dimText.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> File terlalu besar (maks 10 MB).</span>';
        preview.style.display = 'block';
        img.src = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        img.src = e.target.result;
        preview.style.display = 'block';

        const tempImg = new Image();
        tempImg.onload = function() {
            const ok = tempImg.width === 3520 && tempImg.height === 1216;
            dimText.innerHTML = `Dimensi: <strong>${tempImg.width} × ${tempImg.height}</strong>&nbsp;`
                + (ok
                    ? '<span class="text-success"><i class="fas fa-check-circle"></i> Sesuai</span>'
                    : '<span class="text-danger"><i class="fas fa-times-circle"></i> Harus tepat 3520 × 1216</span>');
        };
        tempImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
