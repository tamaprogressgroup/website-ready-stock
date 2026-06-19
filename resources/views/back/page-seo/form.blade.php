@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">

    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="mb-4">
            <a href="{{ route('back.seo-pages.index') }}" class="text-decoration-none text-muted" style="font-size:13px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="font-weight-bold mt-2 mb-0" style="color: #3065A3;">SEO — {{ $label }}</h4>
        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 mb-4">
                <ul class="mb-0 ps-3" style="font-size:13px;">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:12px; max-width:680px;">
            <div class="card-body p-4">
                <form action="{{ route('back.seo-pages.update', $pageKey) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Meta Title --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Meta Title <span class="text-muted fw-normal">(maks 255 karakter)</span>
                        </label>
                        <input type="text" name="meta_title" maxlength="255"
                               value="{{ old('meta_title', $seo?->meta_title) }}"
                               class="form-control @error('meta_title') is-invalid @enderror"
                               placeholder="Judul halaman untuk mesin pencari"
                               style="border-radius:8px;font-size:13px;">
                        <div class="form-text" style="font-size:11px;">Muncul di tab browser dan hasil pencarian Google.</div>
                        @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Meta Description --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Meta Description <span class="text-muted fw-normal">(maks 500 karakter)</span>
                        </label>
                        <textarea name="meta_description" maxlength="500" rows="3"
                                  class="form-control @error('meta_description') is-invalid @enderror"
                                  placeholder="Deskripsi singkat halaman untuk mesin pencari"
                                  style="border-radius:8px;font-size:13px;resize:vertical;">{{ old('meta_description', $seo?->meta_description) }}</textarea>
                        <div class="form-text" style="font-size:11px;">Ideal 120–160 karakter. Muncul di snippet Google.</div>
                        @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Meta Keyword --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Meta Keyword <span class="text-muted fw-normal">(pisahkan dengan koma)</span>
                        </label>
                        <input type="text" name="meta_keyword" maxlength="500"
                               value="{{ old('meta_keyword', $seo?->meta_keyword) }}"
                               class="form-control @error('meta_keyword') is-invalid @enderror"
                               placeholder="properti, rumah, jakarta, ready stock"
                               style="border-radius:8px;font-size:13px;">
                        @error('meta_keyword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">
                    <p class="fw-semibold mb-3" style="font-size:13px;color:#3065A3;">Open Graph (Berbagi di Media Sosial)</p>

                    {{-- OG Title --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">OG Title</label>
                        <input type="text" name="og_title" maxlength="255"
                               value="{{ old('og_title', $seo?->og_title) }}"
                               class="form-control @error('og_title') is-invalid @enderror"
                               placeholder="Judul saat dibagikan di WhatsApp / Facebook"
                               style="border-radius:8px;font-size:13px;">
                        <div class="form-text" style="font-size:11px;">Jika kosong, akan menggunakan Meta Title.</div>
                        @error('og_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- OG Description --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">OG Description</label>
                        <textarea name="og_description" maxlength="500" rows="3"
                                  class="form-control @error('og_description') is-invalid @enderror"
                                  placeholder="Deskripsi saat dibagikan di media sosial"
                                  style="border-radius:8px;font-size:13px;resize:vertical;">{{ old('og_description', $seo?->og_description) }}</textarea>
                        <div class="form-text" style="font-size:11px;">Jika kosong, akan menggunakan Meta Description.</div>
                        @error('og_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4" style="border-radius:8px;font-size:13px;">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="{{ route('back.seo-pages.index') }}" class="btn btn-light px-4" style="border-radius:8px;font-size:13px;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
