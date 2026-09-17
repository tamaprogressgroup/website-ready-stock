@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">

    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="mb-4">
            <h4 class="font-weight-bold mb-1" style="color: #3065A3;">Settings</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Kelola pengaturan fitur di website.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 mb-4" style="font-size:13px;">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:12px; max-width:640px;">
            <div class="card-body p-4">
                @forelse($settings as $setting)
                <div class="d-flex align-items-center justify-content-between {{ !$loop->last ? 'pb-3 mb-3 border-bottom' : '' }}">
                    <div>
                        <h6 class="font-weight-bold mb-1" style="color:#1a1a1a;">{{ $setting->settings_label ?? $setting->settings_key }}</h6>
                        <span class="text-muted" style="font-size:12px;">
                            Key: <code>{{ $setting->settings_key }}</code>
                            — Status saat ini:
                            <strong class="{{ $setting->settings_value === 'active' ? 'text-success' : 'text-danger' }}">
                                {{ $setting->settings_value === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </strong>
                        </span>
                    </div>
                    <form action="{{ route('back.settings.update', $setting->settings_key) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="settings_value" value="{{ $setting->settings_value === 'active' ? 'inactive' : 'active' }}">
                        <button type="submit" class="settings-toggle {{ $setting->settings_value === 'active' ? 'is-on' : '' }}" aria-label="Toggle {{ $setting->settings_key }}">
                            <span class="settings-toggle-knob"></span>
                        </button>
                    </form>
                </div>
                @empty
                    <p class="text-muted mb-0" style="font-size:13px;">Belum ada pengaturan.</p>
                @endforelse
            </div>
        </div>
    </main>
</div>

<style>
    .settings-toggle { position:relative; width:46px; height:26px; border-radius:20px; background:#d1d5db; border:none; padding:0; cursor:pointer; transition:background 0.2s; flex-shrink:0; }
    .settings-toggle.is-on { background:#198754; }
    .settings-toggle-knob { position:absolute; top:3px; left:3px; width:20px; height:20px; border-radius:50%; background:#fff; box-shadow:0 1px 2px rgba(0,0,0,0.2); transition:left 0.2s; }
    .settings-toggle.is-on .settings-toggle-knob { left:23px; }
</style>
@endsection
