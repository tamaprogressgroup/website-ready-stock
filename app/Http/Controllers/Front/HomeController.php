<?php

namespace App\Http\Controllers\Front;

use App\Models\Banner;
use App\Models\Kota;
use App\Models\PageSeo;
use App\Models\PropertyCondition;
use App\Models\PropertyType;
use App\Models\PropertyUnit;
use App\Models\Township;
use App\Redis\GetRedis;
use App\Services\EmbedKeyService;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends BaseFrontController
{
    public function index(): View
    {
        $bannerTop = $this->resolveCache('banners_homepage_atas', '', fn() =>
            Banner::where('position', 'HOMEPAGE_ATAS')
                ->where('is_active', 1)
                ->orderBy('priority')
                ->first()?->toArray() ?? []
        );

        $propertyTypes = $this->resolveCache('property_types', $this->lang, fn() =>
            PropertyType::with(['translations' => fn($q) => $q->where('locale', $this->lang)])
                ->where('is_active', 1)->get()
                ->map(fn($t) => array_merge($t->toArray(), [
                    'slug' => Str::slug($t->translations->first()?->type_name ?? ''),
                ]))
                ->toArray()
        );

        $propertyConditions = $this->resolveCache('property_conditions', $this->lang, fn() =>
            PropertyCondition::with(['translations' => fn($q) => $q->where('locale', $this->lang)])
                ->where('is_active', 1)->get()
                ->map(fn($c) => array_merge($c->toArray(), [
                    'slug' => Str::slug($c->translations->first()?->condition_name ?? ''),
                ]))
                ->toArray()
        );

        // Kotas that actually have active listed properties (for the location dropdown)
        $kotasWithProperties = $this->resolveCache('kotas_with_properties', '', fn() =>
            Kota::whereIn('kota_id',
                PropertyUnit::where('is_active', 1)->where('status_id', 1)
                    ->whereNotNull('kota_id')->distinct()->pluck('kota_id')
            )
            ->orderBy('nama_kota')
            ->get()
            ->map(fn($k) => [
                'kota_id'   => $k->kota_id,
                'nama_kota' => $k->nama_kota,
                'slug'      => Str::slug($k->nama_kota),
            ])
            ->toArray()
        );

        // Townships with their dominant kota slug (needed to build SEO URL from home search)
        $townships = $this->resolveCache('townships_project', '', function () {
            // Get dominant kota per township from existing property data
            $twnKotaMap = PropertyUnit::selectRaw('township_id, kota_id, COUNT(*) as cnt')
                ->whereNotNull('township_id')->whereNotNull('kota_id')
                ->where('is_active', 1)
                ->groupBy('township_id', 'kota_id')
                ->get()
                ->groupBy('township_id')
                ->map(fn($rows) => $rows->sortByDesc('cnt')->first()?->kota_id);

            $kotaIds = $twnKotaMap->values()->filter()->unique()->toArray();
            $kotas   = Kota::whereIn('kota_id', $kotaIds)->get()->keyBy('kota_id');

            return Township::select('township_id', 'township_name', 'image')
                ->withCount(['propertyUnits as unit_count' => fn($q) =>
                    $q->where('status_id', 1)->where('is_active', 1)
                ])
                ->orderBy('township_name')
                ->get()
                ->map(function ($t) use ($twnKotaMap, $kotas) {
                    $kotaId   = $twnKotaMap->get($t->township_id);
                    $kotaName = $kotaId ? ($kotas->get($kotaId)?->nama_kota ?? '') : '';
                    return [
                        'township_id'   => $t->township_id,
                        'township_name' => $t->township_name,
                        'township_slug' => Str::slug($t->township_name),
                        'image'         => $t->image,
                        'unit_count'    => (int) $t->unit_count,
                        'kota_slug'     => Str::slug($kotaName),
                    ];
                })
                ->values()
                ->toArray();
        });

        $recommendations = $this->resolveCache('recommendations', $this->lang, fn() =>
            PropertyUnit::with([
                'translations'              => fn($q) => $q->where('locale', $this->lang),
                'interiors'                 => fn($q) => $q->where('order', 1)->where('is_active', 1),
                'kota',
                'provinsi',
                'township',
                'condition.translations'    => fn($q) => $q->where('locale', $this->lang),
                'propertyType.translations' => fn($q) => $q->where('locale', $this->lang),
                'tags'                      => fn($q) => $q->where('is_label', 1),
            ])
            ->where('is_active', 1)->where('status_id', 1)
            ->orderByDesc('created_datetime')->limit(8)->get()
            ->map(fn(PropertyUnit $u) => $this->formatCard($u))->toArray()
        );

        $newProperties = $this->resolveCache('new_properties', $this->lang, fn() =>
            PropertyUnit::with([
                'translations'              => fn($q) => $q->where('locale', $this->lang),
                'interiors'                 => fn($q) => $q->where('order', 1)->where('is_active', 1),
                'kota',
                'provinsi',
                'township',
                'condition.translations'    => fn($q) => $q->where('locale', $this->lang),
                'propertyType.translations' => fn($q) => $q->where('locale', $this->lang),
                'tags'                      => fn($q) => $q->where('is_label', 1),
            ])
            ->where('is_active', 1)->where('status_id', 1)
            ->latest('created_datetime')->limit(8)->get()
            ->map(fn(PropertyUnit $u) => $this->formatCard($u))->toArray()
        );

        $pageSeoArr = GetRedis::getRedisSimple('page_seo:home');
        $pageSeo    = $pageSeoArr
            ? (object) $pageSeoArr
            : PageSeo::where('page_key', 'home')->first();

        $keyData = EmbedKeyService::resolve();

        return view('front.layout.readyStock', compact(
            'bannerTop', 'propertyTypes', 'propertyConditions',
            'kotasWithProperties', 'townships', 'recommendations', 'newProperties',
            'keyData', 'pageSeo'
        ));
    }
}
