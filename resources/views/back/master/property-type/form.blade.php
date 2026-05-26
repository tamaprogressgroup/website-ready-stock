@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">

    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="mb-4">
            <a href="{{ route('master.property-type.index') }}" class="text-decoration-none text-muted" style="font-size:13px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="font-weight-bold mt-2 mb-0" style="color: #3065A3;">
                {{ $item ? 'Edit Tipe Properti' : 'Tambah Tipe Properti' }}
            </h4>
        </div>

        @if(session('error'))
            <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:12px; max-width:520px;">
            <div class="card-body p-4">
                <form action="{{ $item ? route('master.property-type.update', $item->property_type_id) : route('master.property-type.store') }}" method="POST">
                    @csrf
                    @if($item) @method('PUT') @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Nama Tipe Properti <span class="text-danger">*</span></label>
                        <input type="text" name="type_name" class="form-control @error('type_name') is-invalid @enderror"
                               style="border-radius:8px; height:44px;"
                               value="{{ old('type_name', $item?->translations?->first()?->type_name) }}"
                               placeholder="cth: Rumah, Apartemen, Ruko" required>
                        @error('type_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        <a href="{{ route('master.property-type.index') }}" class="btn btn-outline-secondary px-4" style="border-radius:8px;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
