@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">

    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="font-weight-bold mb-0" style="color: #3065A3;">Kondisi Properti</h4>
                <p class="text-muted mb-0" style="font-size: 13px;">Kelola master data kondisi properti</p>
            </div>
            <a href="{{ route('master.property-condition.create') }}" class="btn btn-primary btn-sm px-4" style="background:#3065A3; border-color:#3065A3; border-radius:8px;">
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
                <table class="table mb-0" style="font-size:14px;">
                    <thead style="background:#f0f4f8;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary fw-semibold">#</th>
                            <th class="py-3 text-secondary fw-semibold">Nama Kondisi</th>
                            <th class="py-3 text-secondary fw-semibold">Status</th>
                            <th class="py-3 text-secondary fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i => $item)
                        <tr class="border-top">
                            <td class="ps-4 py-3 text-muted">{{ $items->firstItem() + $i }}</td>
                            <td class="py-3 fw-semibold">{{ $item->translations->first()->condition_name ?? '-' }}</td>
                            <td class="py-3">
                                @if($item->is_active)
                                    <span class="badge bg-success px-3 py-1" style="border-radius:20px;">Aktif</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-1" style="border-radius:20px;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <a href="{{ route('master.property-condition.edit', $item->property_condition_id) }}" class="btn btn-sm btn-outline-primary me-1" style="border-radius:6px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('master.property-condition.destroy', $item->property_condition_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kondisi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:6px;"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data.</td></tr>
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
