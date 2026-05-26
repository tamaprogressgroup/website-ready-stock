@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">

    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="mb-4">
            <a href="{{ route('master.cluster.index') }}" class="text-decoration-none text-muted" style="font-size:13px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="font-weight-bold mt-2 mb-0" style="color: #3065A3;">
                {{ $item ? 'Edit Cluster' : 'Tambah Cluster' }}
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
                <form action="{{ $item ? route('master.cluster.update', $item->cluster_id) : route('master.cluster.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($item) @method('PUT') @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Nama Cluster <span class="text-danger">*</span></label>
                        <input type="text" name="cluster_name"
                               class="form-control @error('cluster_name') is-invalid @enderror"
                               style="border-radius:8px; height:44px;"
                               value="{{ old('cluster_name', $item?->cluster_name) }}"
                               placeholder="cth: Green Serpong, Paramount" required>
                        @error('cluster_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Gambar Utama --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Gambar Utama {{ $item ? '<span class="text-muted fw-normal">(opsional, ganti jika perlu)</span>' : '<span class="text-danger">*</span>' }}
                        </label>
                        <p class="text-muted mb-2" style="font-size:12px;"><i class="fas fa-ruler-combined me-1"></i>Dimensi wajib: <strong>720 × 450 piksel</strong></p>
                        @if($item && $item->image)
                            <div class="mb-2">
                                <img src="{{ Storage::disk('public')->url($item->image) }}" alt="current"
                                     style="width:200px; height:125px; object-fit:cover; border-radius:8px; border:1px solid #eee;">
                                <p class="text-muted mt-1" style="font-size:11px;">Gambar saat ini.</p>
                            </div>
                        @endif
                        <input type="file" name="image" id="image"
                               class="form-control @error('image') is-invalid @enderror"
                               style="border-radius:8px;" accept="image/*"
                               onchange="previewImage(this, 'preview-image')"
                               {{ $item ? '' : 'required' }}>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="preview-image" class="mt-2" style="display:none;">
                            <img src="" alt="preview" style="width:200px; height:125px; object-fit:cover; border-radius:8px; border:2px dashed #3065A3;">
                            <p class="text-muted mt-1" style="font-size:11px;" id="dim-image"></p>
                        </div>
                    </div>

                    {{-- Gambar Mobile --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Gambar Mobile {{ $item ? '<span class="text-muted fw-normal">(opsional, ganti jika perlu)</span>' : '<span class="text-danger">*</span>' }}
                        </label>
                        <p class="text-muted mb-2" style="font-size:12px;"><i class="fas fa-ruler-combined me-1"></i>Dimensi wajib: <strong>720 × 450 piksel</strong></p>
                        @if($item && $item->image_mobile)
                            <div class="mb-2">
                                <img src="{{ Storage::disk('public')->url($item->image_mobile) }}" alt="current mobile"
                                     style="width:200px; height:125px; object-fit:cover; border-radius:8px; border:1px solid #eee;">
                                <p class="text-muted mt-1" style="font-size:11px;">Gambar mobile saat ini.</p>
                            </div>
                        @endif
                        <input type="file" name="image_mobile" id="image_mobile"
                               class="form-control @error('image_mobile') is-invalid @enderror"
                               style="border-radius:8px;" accept="image/*"
                               onchange="previewImage(this, 'preview-image-mobile')"
                               {{ $item ? '' : 'required' }}>
                        @error('image_mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="preview-image-mobile" class="mt-2" style="display:none;">
                            <img src="" alt="preview mobile" style="width:200px; height:125px; object-fit:cover; border-radius:8px; border:2px dashed #3065A3;">
                            <p class="text-muted mt-1" style="font-size:11px;" id="dim-image_mobile"></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $item?->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active" style="font-size:13px;">Aktif</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4" style="background:#3065A3; border-color:#3065A3; border-radius:8px;">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="{{ route('master.cluster.index') }}" class="btn btn-outline-secondary px-4" style="border-radius:8px;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function previewImage(input, containerId) {
    const container = document.getElementById(containerId);
    const img       = container.querySelector('img');
    const dimText   = document.getElementById('dim-' + input.id);

    if (!input.files || !input.files[0]) { container.style.display = 'none'; return; }

    const file   = input.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        img.src = e.target.result;
        container.style.display = 'block';

        const tempImg = new Image();
        tempImg.onload = function() {
            const ok = tempImg.width === 720 && tempImg.height === 450;
            dimText.innerHTML = `Dimensi: <strong>${tempImg.width} × ${tempImg.height}</strong> `
                + (ok ? '<span class="text-success"><i class="fas fa-check-circle"></i> Sesuai</span>'
                       : '<span class="text-danger"><i class="fas fa-times-circle"></i> Harus 720×450</span>');
        };
        tempImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
