@extends('back.layout.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <section class="card card-modern border-0 box-shadow-1 mb-4">
            <header class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h2 class="card-title text-color-dark font-weight-bold text-4">Sales Inquiry</h2>
                <p class="card-subtitle text-muted mb-0">Data prospek yang masuk untuk properti Anda.</p>
            </header>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0" id="datatable-leads">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Nama</th>
                                <th width="20%">Properti</th>
                                <th width="20%">Email / Telepon</th>
                                <th width="15%">Sumber</th>
                                <th width="10%">Tanggal</th>
                                <th width="10%" class="text-center d-none d-md-table-cell">Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $lead)
                                <tr>
                                    <td class="text-muted" style="font-size:12px;">{{ $lead->id }}</td>
                                    <td>
                                        <strong>{{ $lead->fullname }}</strong>
                                        @if($lead->salutation)
                                            <br><small class="text-muted">{{ $lead->salutation }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lead->property_id && isset($propertyTitles[$lead->property_id]))
                                            <span class="badge bg-light text-dark border" style="white-space:normal;text-align:left;">
                                                {{ $propertyTitles[$lead->property_id] }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $lead->email ?: '—' }}
                                        @if($lead->phone_number)
                                            <br><small class="text-muted">{{ $lead->phone_number }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $lead->sumber_informasi ?: '—' }}</td>
                                    <td>
                                        @if($lead->created_at)
                                            <span style="font-size:12px;">{{ $lead->created_at->format('d M Y') }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-center d-none d-md-table-cell">
                                        @if($lead->enquiry)
                                            <button type="button" class="btn btn-sm btn-light border"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ e($lead->enquiry) }}">
                                                <i class="fas fa-comment-alt text-primary"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fs-4 mb-2 d-block"></i>
                                        Belum ada data Sales Inquiry.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($leads->hasPages())
                    <div class="mt-3 d-flex justify-content-end">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('#datatable-leads').length && typeof $.fn.DataTable !== 'undefined') {
            $('#datatable-leads').DataTable({
                "responsive": true,
                "pageLength": 25,
                "ordering": true,
                "dom": '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                "language": {
                    "search": "",
                    "searchPlaceholder": "Cari...",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data",
                    "infoFiltered": "(disaring dari _MAX_ data)",
                    "paginate": {
                        "first": "Awal", "last": "Akhir",
                        "next": "Selanjutnya", "previous": "Sebelumnya"
                    }
                }
            });
        }

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(el) { return new bootstrap.Tooltip(el); });
    });
</script>
@endpush
