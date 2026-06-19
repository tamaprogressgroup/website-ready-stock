@extends('back.layout.app')

@section('content')
<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh;">

    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="mb-4">
            <h4 class="font-weight-bold mb-1" style="color: #3065A3;">SEO Halaman</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Kelola meta SEO untuk halaman Homepage dan Semua Properti.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 mb-4" style="font-size:13px;">{{ session('success') }}</div>
        @endif

        <div class="row g-3">
            @foreach($pages as $page)
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="font-weight-bold mb-1" style="color:#1a1a1a;">{{ $page['label'] }}</h6>
                                <span class="badge bg-light text-secondary" style="font-size:11px;">{{ $page['key'] }}</span>
                            </div>
                            <a href="{{ route('back.seo-pages.edit', $page['key']) }}"
                               class="btn btn-primary btn-sm px-3" style="border-radius:8px;font-size:12px;">
                                <i class="fas fa-pencil-alt me-1"></i> Edit
                            </a>
                        </div>

                        <table style="font-size:12px;width:100%;border-collapse:collapse;">
                            <tr>
                                <td class="text-muted pe-3 py-1" style="width:40%;white-space:nowrap;">Meta Title</td>
                                <td class="py-1">{!! $page['seo']?->meta_title ? e($page['seo']->meta_title) : '<span class="text-muted fst-italic">—</span>' !!}</td>
                            </tr>
                            <tr>
                                <td class="text-muted pe-3 py-1">Meta Description</td>
                                <td class="py-1">{!! ($v = Str::limit($page['seo']?->meta_description ?? '', 80)) ? e($v) : '<span class="text-muted fst-italic">—</span>' !!}</td>
                            </tr>
                            <tr>
                                <td class="text-muted pe-3 py-1">Meta Keyword</td>
                                <td class="py-1">{!! ($v = Str::limit($page['seo']?->meta_keyword ?? '', 60)) ? e($v) : '<span class="text-muted fst-italic">—</span>' !!}</td>
                            </tr>
                            <tr>
                                <td class="text-muted pe-3 py-1">OG Title</td>
                                <td class="py-1">{!! $page['seo']?->og_title ? e($page['seo']->og_title) : '<span class="text-muted fst-italic">—</span>' !!}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>
</div>
@endsection
