@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">
    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="font-weight-bold mb-0" style="color: #3065A3;">Manajemen User</h4>
                <p class="text-muted mb-0" style="font-size: 13px;">Kelola akun admin back-office</p>
            </div>
            <a href="{{ route('admin-user.create') }}" class="btn btn-primary btn-sm px-4" style="background:#3065A3; border-color:#3065A3; border-radius:8px;">
                <i class="fas fa-user-plus me-1"></i> Tambah User
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
                <table class="table mb-0 align-middle" style="font-size:14px;">
                    <thead style="background:#f0f4f8;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary fw-semibold">#</th>
                            <th class="py-3 text-secondary fw-semibold">Nama</th>
                            <th class="py-3 text-secondary fw-semibold d-none d-md-table-cell">Email</th>
                            <th class="py-3 text-secondary fw-semibold">Role</th>
                            <th class="py-3 text-secondary fw-semibold">Status</th>
                            <th class="py-3 text-secondary fw-semibold d-none d-md-table-cell">Bergabung</th>
                            <th class="py-3 text-secondary fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i => $user)
                        <tr class="border-top">
                            <td class="ps-4 py-3 text-muted">{{ $users->firstItem() + $i }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold text-white"
                                         style="width:34px; height:34px; background:#3065A3; font-size:13px; flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $user->name }}</span>
                                    @if(Auth::guard('admin')->id() === $user->id)
                                        <span class="badge ms-2" style="background:#e8effa; color:#3065A3; border-radius:20px; font-size:10px;">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 text-muted d-none d-md-table-cell">{{ $user->email }}</td>
                            <td class="py-3">
                                @php
                                    $roleColors = ['superadmin' => '#7c3aed', 'admin' => '#3065A3', 'staff' => '#059669'];
                                    $roleColor  = $roleColors[$user->role] ?? '#888';
                                @endphp
                                <span class="badge px-3 py-1" style="background:{{ $roleColor }}20; color:{{ $roleColor }}; border-radius:20px; font-size:12px; font-weight:600;">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($user->is_active)
                                    <span class="badge bg-success px-3 py-1" style="border-radius:20px;">Aktif</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-1" style="border-radius:20px;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3 text-muted d-none d-md-table-cell" style="font-size:12px;">
                                {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3">
                                <a href="{{ route('admin-user.edit', $user->id) }}" class="btn btn-sm btn-outline-primary me-1" style="border-radius:6px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(Auth::guard('admin')->id() !== $user->id)
                                <form action="{{ route('admin-user.destroy', $user->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus user {{ addslashes($user->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:6px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada user.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <div class="mt-3">{{ $users->links() }}</div>
    </main>
</div>
@endsection
