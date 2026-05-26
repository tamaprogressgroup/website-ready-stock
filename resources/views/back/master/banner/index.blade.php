@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">

    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="font-weight-bold mb-0" style="color: #3065A3;">Banner</h4>
                <p class="text-muted mb-0" style="font-size: 13px;">Kelola banner halaman utama</p>
            </div>
            <a href="{{ route('master.banner.create') }}" class="btn btn-primary btn-sm px-4" style="background:#3065A3; border-color:#3065A3; border-radius:8px;">
                <i class="fas fa-plus me-1"></i> Tambah
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table mb-0 align-middle" style="font-size:13px;">
                    <thead style="background:#f0f4f8;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary fw-semibold" style="width:40px;">#</th>
                            <th class="py-3 text-secondary fw-semibold">Preview</th>
                            <th class="py-3 text-secondary fw-semibold">Posisi</th>
                            <th class="py-3 text-secondary fw-semibold d-none d-md-table-cell">Prioritas</th>
                            <th class="py-3 text-secondary fw-semibold d-none d-lg-table-cell">Target URL</th>
                            <th class="py-3 text-secondary fw-semibold">Status</th>
                            <th class="py-3 text-secondary fw-semibold d-none d-md-table-cell">Dibuat</th>
                            <th class="py-3 text-secondary fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i => $item)
                        @php
                            $positionLabels = \App\Http\Controllers\Back\Master\BannerController::POSITIONS;
                        @endphp
                        <tr class="border-top">
                            <td class="ps-4 py-3 text-muted">{{ $items->firstItem() + $i }}</td>
                            <td class="py-2">
                                <img src="{{ Storage::url($item->image_url) }}" alt="banner"
                                    style="width:120px; height:45px; object-fit:cover; border-radius:6px;">
                            </td>
                            <td class="py-3">
                                <span class="badge px-2 py-1 fw-semibold" style="background:#e8effa; color:#3065A3; border-radius:6px; font-size:11px;">
                                    {{ $positionLabels[$item->position] ?? $item->position }}
                                </span>
                            </td>
                            <td class="py-3 text-center d-none d-md-table-cell">
                                <span class="badge bg-secondary px-2 py-1" style="border-radius:6px; font-size:12px;">{{ $item->priority }}</span>
                            </td>
                            <td class="py-3 d-none d-lg-table-cell">
                                @if($item->target_url)
                                    <a href="{{ $item->target_url }}" target="_blank" class="text-primary" style="font-size:12px; word-break:break-all;">
                                        {{ Str::limit($item->target_url, 30) }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($item->is_active)
                                    <span class="badge bg-success px-3 py-1" style="border-radius:20px;">Aktif</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-1" style="border-radius:20px;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3 d-none d-md-table-cell" style="font-size:11px; color:#888;">
                                <div>{{ $item->creator?->name ?? 'Sistem' }}</div>
                                <div>{{ $item->craeted_datetime ? \Carbon\Carbon::parse($item->craeted_datetime)->format('d M Y') : '-' }}</div>
                            </td>
                            <td class="py-3">
                                <a href="{{ route('master.banner.edit', $item->id) }}" class="btn btn-sm btn-outline-primary me-1" style="border-radius:6px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('master.banner.destroy', $item->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus banner ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:6px;"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada banner.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <div class="mt-3">{{ $items->links() }}</div>
    </main>
</div>
@endsection
