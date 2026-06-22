@extends('back.layout.app')

@section('content')

<div class="row">
    <div class="col-lg-12">

        {{-- Create Short Link Card --}}
        <section class="card card-modern border-0 box-shadow-1 mb-4" style="border-radius: 12px;">
            <header class="card-header bg-white border-bottom-0 pt-4 pb-0" style="border-radius: 12px 12px 0 0;">
                <h2 class="card-title text-color-dark font-weight-bold text-4">Buat Short Link</h2>
                <p class="card-subtitle text-muted mb-0">Buat URL pendek dari key hash yang panjang.</p>
            </header>
            <div class="card-body pt-4">

                @if(session('created_url'))
                <div class="alert alert-success d-flex align-items-center gap-3" style="border-radius: 8px;" role="alert">
                    <i class="fas fa-check-circle text-success" style="font-size: 1.3rem; flex-shrink: 0;"></i>
                    <div class="flex-grow-1">
                        <strong>Short link berhasil dibuat!</strong>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <input type="text" id="created-url-input" class="form-control" readonly
                                   value="{{ session('created_url') }}"
                                   style="font-size: 13px; background: #f8f9fa; border-radius: 6px;">
                            <button type="button" onclick="copyCreatedUrl()" class="btn btn-sm font-weight-bold px-3"
                                    style="background-color: #3065A3; color: #fff; border-radius: 6px; white-space: nowrap; border: none;">
                                <i class="fas fa-copy me-1"></i> Salin
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-info" style="border-radius: 8px;" role="alert">
                    <i class="fas fa-info-circle me-2"></i>{{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger" style="border-radius: 8px;" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('customer.short-links.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label font-weight-semibold" style="font-size: 13px; color: #333;">
                                Key Hash <span class="text-danger">*</span>
                            </label>
                            <textarea name="key_hash" rows="3"
                                      class="form-control @error('key_hash') is-invalid @enderror"
                                      placeholder="Tempel key hash panjang di sini..."
                                      style="font-size: 13px; border-radius: 8px; font-family: monospace; resize: vertical;">{{ old('key_hash') }}</textarea>
                            <div class="form-text text-muted" style="font-size: 11px;">Paste key hash dari URL ?key=... di sini</div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label font-weight-semibold" style="font-size: 13px; color: #333;">
                                Property Path <span class="text-muted fw-normal">(opsional)</span>
                            </label>
                            <input type="text" name="redirect_path"
                                   class="form-control @error('redirect_path') is-invalid @enderror"
                                   placeholder="/properti-baru/rumah/tangerang-selatan/paradise-serpong-city-2/nama-rumah"
                                   value="{{ old('redirect_path') }}"
                                   style="font-size: 13px; border-radius: 8px;">
                            <div class="form-text text-muted" style="font-size: 11px;">Kosongkan untuk link ke halaman utama</div>
                        </div>
                    </div>
                    <button type="submit" class="btn font-weight-bold px-4 py-2"
                            style="background-color: #3065A3; color: #fff; border-radius: 8px; border: none; font-size: 14px;">
                        <i class="fas fa-link me-2"></i> Generate
                    </button>
                </form>
            </div>
        </section>

        {{-- Short Links Table --}}
        <section class="card card-modern border-0 box-shadow-1" style="border-radius: 12px;">
            <header class="card-header bg-white border-bottom-0 pt-4 pb-0" style="border-radius: 12px 12px 0 0;">
                <h2 class="card-title text-color-dark font-weight-bold text-4">Daftar Short Link</h2>
                <p class="card-subtitle text-muted mb-0">{{ $links->count() }} short link tersedia</p>
            </header>
            <div class="card-body">
                @if($links->isEmpty())
                <div class="text-center py-5" style="color: #aaa;">
                    <i class="fas fa-link" style="font-size: 2.5rem; margin-bottom: 12px; display: block; opacity: 0.4;"></i>
                    <p class="mb-0" style="font-size: 14px;">Belum ada short link. Buat yang pertama di atas.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 8%;">Code</th>
                                <th style="width: 22%;">Short URL</th>
                                <th style="width: 28%;">Redirect Path</th>
                                <th style="width: 8%; text-align: center;">Hits</th>
                                <th style="width: 14%;">Dibuat</th>
                                <th style="width: 20%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($links as $link)
                            <tr>
                                <td>
                                    <code style="font-size: 12px; background: #f0f4ff; color: #3065A3; padding: 2px 6px; border-radius: 4px;">{{ $link->code }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ url('/s/' . $link->code) }}" target="_blank"
                                           style="font-size: 12px; color: #3065A3; word-break: break-all; text-decoration: none;"
                                           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                            {{ url('/s/' . $link->code) }}
                                        </a>
                                        <button type="button"
                                                onclick="copyToClipboard('{{ url('/s/' . $link->code) }}', this)"
                                                class="btn btn-sm"
                                                style="background: #f0f4ff; border: 1px solid #c8d8f0; color: #3065A3; border-radius: 5px; padding: 2px 8px; font-size: 11px; white-space: nowrap; flex-shrink: 0;">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    @if($link->redirect_path)
                                        <span style="font-size: 12px; color: #555; word-break: break-all;">{{ $link->redirect_path }}</span>
                                    @else
                                        <span class="badge" style="background: #e8f5e9; color: #388e3c; font-size: 11px; border-radius: 6px; padding: 3px 8px;">Homepage</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge" style="background: #fff3e0; color: #e65100; font-size: 12px; border-radius: 6px; padding: 4px 10px; font-weight: 600;">
                                        {{ number_format($link->hits) }}
                                    </span>
                                </td>
                                <td style="font-size: 12px; color: #888;">
                                    {{ $link->created_at ? $link->created_at->format('d M Y H:i') : '—' }}
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('customer.short-links.destroy', $link->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus short link /s/{{ $link->code }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm font-weight-semibold"
                                                style="background: #fff0f0; color: #dc3545; border: 1px solid #f5c6cb; border-radius: 6px; font-size: 12px;">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </section>

    </div>
</div>

<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(function() {
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.background = '#e8f5e9';
        btn.style.color = '#388e3c';
        btn.style.borderColor = '#a5d6a7';
        setTimeout(function() {
            btn.innerHTML = orig;
            btn.style.background = '#f0f4ff';
            btn.style.color = '#3065A3';
            btn.style.borderColor = '#c8d8f0';
        }, 1800);
    }).catch(function() {
        var ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
    });
}

function copyCreatedUrl() {
    var input = document.getElementById('created-url-input');
    copyToClipboard(input.value, document.querySelector('.alert-success button'));
}
</script>

@endsection
