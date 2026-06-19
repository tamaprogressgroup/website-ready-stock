<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\PageSeo;
use App\Redis\GetRedis;
use App\Redis\SetRedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PageSeoController extends Controller
{
    private const PAGES = [
        'home'         => 'Homepage (Beranda)',
        'all_products' => 'Semua Properti (All Products)',
    ];

    public function index()
    {
        $pages = collect(self::PAGES)->map(fn($label, $key) => [
            'key'   => $key,
            'label' => $label,
            'seo'   => PageSeo::where('page_key', $key)->first(),
        ])->values();

        return view('back.page-seo.index', compact('pages'));
    }

    public function edit(string $pageKey)
    {
        abort_unless(isset(self::PAGES[$pageKey]), 404);

        $seo   = PageSeo::where('page_key', $pageKey)->first();
        $label = self::PAGES[$pageKey];

        return view('back.page-seo.form', compact('seo', 'pageKey', 'label'));
    }

    public function update(Request $request, string $pageKey)
    {
        abort_unless(isset(self::PAGES[$pageKey]), 404);

        $data = $request->validate([
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keyword'     => 'nullable|string|max:500',
            'og_title'         => 'nullable|string|max:255',
            'og_description'   => 'nullable|string|max:500',
        ]);

        $seo = PageSeo::updateOrCreate(['page_key' => $pageKey], $data);

        // Flush then reload Redis
        $cacheKey = 'page_seo:' . $pageKey;
        Redis::del(config('readystock.redis_key') . ':' . $cacheKey);
        SetRedis::setRedisSimple($seo->toArray(), $cacheKey);

        return redirect()->route('back.seo-pages.index')
            ->with('success', 'SEO halaman "' . self::PAGES[$pageKey] . '" berhasil diperbarui.');
    }
}
