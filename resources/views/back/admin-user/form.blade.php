@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">
    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="mb-4">
            <a href="{{ route('admin-user.index') }}" class="text-decoration-none text-muted" style="font-size:13px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="font-weight-bold mt-2 mb-0" style="color: #3065A3;">
                {{ $user ? 'Edit User' : 'Tambah User Baru' }}
            </h4>
        </div>

        @if(session('error'))
            <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:12px; max-width:560px;">
            <div class="card-body p-4">
                <form action="{{ $user ? route('admin-user.update', $user->id) : route('admin-user.store') }}" method="POST">
                    @csrf
                    @if($user) @method('PUT') @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               style="border-radius:8px; height:44px;"
                               value="{{ old('name', $user?->name) }}"
                               placeholder="cth: Budi Santoso" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               style="border-radius:8px; height:44px;"
                               value="{{ old('email', $user?->email) }}"
                               placeholder="user@readystock.id" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Password
                            @if($user)
                                <span class="text-muted fw-normal">(kosongkan jika tidak ingin mengubah)</span>
                            @else
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   style="border-radius:8px; height:44px; padding-right:44px;"
                                   placeholder="Min. 8 karakter"
                                   {{ $user ? '' : 'required' }}>
                            <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent pe-3"
                                    onclick="togglePass('password')" tabindex="-1">
                                <i class="fas fa-eye text-muted" id="eye-password" style="font-size:14px;"></i>
                            </button>
                        </div>
                        @error('password')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">
                            Konfirmasi Password
                            @if(!$user)<span class="text-danger">*</span>@endif
                        </label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control"
                                   style="border-radius:8px; height:44px; padding-right:44px;"
                                   placeholder="Ulangi password"
                                   {{ $user ? '' : 'required' }}>
                            <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent pe-3"
                                    onclick="togglePass('password_confirmation')" tabindex="-1">
                                <i class="fas fa-eye text-muted" id="eye-password_confirmation" style="font-size:14px;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror"
                                style="border-radius:8px; height:44px;" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="superadmin" {{ old('role', $user?->role) === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            <option value="admin"      {{ old('role', $user?->role) === 'admin'      ? 'selected' : '' }}>Admin</option>
                            <option value="staff"      {{ old('role', $user?->role) === 'staff'      ? 'selected' : '' }}>Staff</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $user?->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active" style="font-size:13px;">Akun Aktif</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4" style="background:#3065A3; border-color:#3065A3; border-radius:8px;">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="{{ route('admin-user.index') }}" class="btn btn-outline-secondary px-4" style="border-radius:8px;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function togglePass(fieldId) {
    const input = document.getElementById(fieldId);
    const icon  = document.getElementById('eye-' + fieldId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
