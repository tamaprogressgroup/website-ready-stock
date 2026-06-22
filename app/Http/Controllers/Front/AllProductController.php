<?php

namespace App\Http\Controllers\Front;

use App\Models\Banner;
use App\Models\Kota;
use App\Models\LocationArea;
use App\Models\PageSeo;
use App\Models\PropertyCondition;
use App\Models\PropertyType;
use App\Models\PropertyUnit;
use App\Models\Township;
use App\Redis\GetRedis;
use App\Services\EmbedKeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AllProductController extends BaseFrontController
{
    private const PER_PAGE = 16;

    /** GET /all-products — no slug segments */
    public function index(Request $request): View
    {
        return $this->buildProductView($request);
    }

    /** GET /{condition}[/{type}[/{kota}[/{township}]]] */
    public function browse(
        Request $request,
        string  $condition,
        ?string $type     = null,
        ?string $kota     = null,
        ?string $township = null,
    ): View {
        $lang = $this->lang;

        // Resolve condition slug → model
        $condModel = PropertyCondition::with(['translations' => fn($q) => $q->where('locale', $lang)])
            ->get()
            ->first(fn($c) => Str::slug($c->translations->first()?->condition_name ?? '') === $condition);
        if (!$condModel) abort(404, 'Kondisi properti tidak ditemukan.');

        // Resolve type slug → model
        $typeModel = null;
        if ($type) {
            $typeModel = PropertyType::with(['translations' => fn($q) => $q->where('locale', $lang)])
                ->where('is_active', 1)->get()
                ->first(fn($t) => Str::slug($t->translations->first()?->type_name ?? '') === $type);
            if (!$typeModel) abort(404, 'Tipe properti tidak ditemukan.');
        }

        // Resolve kota slug → Kota model, fallback to LocationArea (→ province-level filter)
        $kotaModel = null;
        $areaModel = null;
        if ($kota) {
            $kotaModel = Kota::all()->first(fn($k) => Str::slug($k->nama_kota) === $kota);
            if (!$kotaModel) {
                $areaModel = LocationArea::all()->first(fn($a) => Str::slug($a->location_name) === $kota);
                if (!$areaModel) abort(404, 'Kota tidak ditemukan.');
            }
        }

        // Resolve township slug → model
        $twnModel = null;
        if ($township) {
            $twnModel = Township::all()->first(fn($t) => Str::slug($t->township_name) === $township);
            if (!$twnModel) abort(404, 'Township tidak ditemukan.');
        }

        return $this->buildProductView(
            $request,
            ['condition' => $condition, 'type' => $type, 'kota' => $kota, 'township' => $township],
            [
                'condition' => $condModel->translations->first()?->condition_name ?? $condition,
                'type'      => $typeModel?->translations->first()?->type_name,
                'kota'      => $kotaModel?->nama_kota ?? $areaModel?->location_name,
                'township'  => $twnModel?->township_name,
            ],
            $condModel->property_condition_id,
            $typeModel?->property_type_id,
            $kotaModel?->kota_id,
            $twnModel?->township_id,
            $areaModel?->provinsi_id,
        );
    }

    // ─── Core view builder ─────────────────────────────────────────────────

    private function buildProductView(
        Request $request,
        array   $urlSlugs      = [],
        array   $slugNames     = [],
        ?int    $conditionId   = null,
        ?int    $typeId        = null,
        ?int    $kotaId        = null,
        ?int    $townshipId    = null,
        ?int    $areaProvinceId = null,
    ): View {
        $lang = $this->lang;

        $browseBase = $this->buildBrowseBase($urlSlugs);

        $propertyTypes = $this->resolveCache('property_types', $lang, fn() =>
            PropertyType::with(['translations' => fn($q) => $q->where('locale', $lang)])
                ->where('is_active', 1)->get()
                ->map(fn($t) => array_merge($t->toArray(), [
                    'slug' => Str::slug($t->translations->first()?->type_name ?? ''),
                ]))
                ->toArray()
        );

        $propertyConditions = $this->resolveCache('property_conditions', $lang, fn() =>
            PropertyCondition::with(['translations' => fn($q) => $q->where('locale', $lang)])
                ->where('is_active', 1)->get()
                ->map(fn($c) => array_merge($c->toArray(), [
                    'slug' => Str::slug($c->translations->first()?->condition_name ?? ''),
                ]))
                ->toArray()
        );

        $banner = $this->resolveCache('banners_allproduct_tengah', '', fn() =>
            Banner::where('position', 'ALLPRODUCT_TENGAH')
                ->where('is_active', 1)->orderBy('priority')
                ->first()?->toArray() ?? []
        );

        $query = PropertyUnit::with([
            'translations'              => fn($q) => $q->where('locale', $lang),
            'interiors'                 => fn($q) => $q->where('order', 1)->where('is_active', 1),
            'kota',
            'provinsi',
            'township',
            'condition.translations'    => fn($q) => $q->where('locale', $lang),
            'propertyType.translations' => fn($q) => $q->where('locale', $lang),
            'tags'                      => fn($q) => $q->where('is_label', 1),
        ])
        ->where('is_active', 1)
        ->where('status_id', 1);

        // URL-path slug filters (take priority)
        if ($conditionId)    $query->where('condition_id',     $conditionId);
        if ($typeId)         $query->where('property_type_id', $typeId);
        if ($kotaId)         $query->where('kota_id',          $kotaId);
        if ($townshipId)     $query->where('township_id',      $townshipId);
        if ($areaProvinceId) $query->where('provinsi_id',      $areaProvinceId);

        // Query-param filters (only active when no slug override)
        if (!$conditionId && $request->filled('condition_id')) {
            $query->where('condition_id', $request->condition_id);
        }
        if (!$typeId && $request->filled('property_type')) {
            $query->where('property_type_id', $request->property_type);
        }
        if (!$kotaId && $request->filled('location')) {
            $query->where('provinsi_id', $request->location);
        }
        if (!$townshipId && $request->filled('township')) {
            $query->where('township_id', $request->township);
        }

        // Free-text search — title, name, description, kota, provinsi, township
        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($or) use ($term, $lang) {
                $or->whereHas('translations', fn($tq) =>
                    $tq->where('locale', $lang)->where(fn($sq) => $sq
                        ->where('title',         'like', "%{$term}%")
                        ->orWhere('property_name','like', "%{$term}%")
                        ->orWhere('description',  'like', "%{$term}%")
                    )
                )
                ->orWhereHas('kota',     fn($q) => $q->where('nama_kota',     'like', "%{$term}%"))
                ->orWhereHas('provinsi', fn($q) => $q->where('provinsi_name', 'like', "%{$term}%"))
                ->orWhereHas('township', fn($q) => $q->where('township_name', 'like', "%{$term}%"));
            });
        }

        // Price range (inputs in Juta, multiply ×1 000 000)
        if ($request->filled('price_min')) { $query->where('price', '>=', (float)$request->price_min * 1_000_000); }
        if ($request->filled('price_max')) { $query->where('price', '<=', (float)$request->price_max * 1_000_000); }
        // Area range
        if ($request->filled('lt_min'))    { $query->where('land_area',     '>=', (int)$request->lt_min); }
        if ($request->filled('lt_max'))    { $query->where('land_area',     '<=', (int)$request->lt_max); }
        if ($request->filled('lb_min'))    { $query->where('building_area', '>=', (int)$request->lb_min); }
        if ($request->filled('lb_max'))    { $query->where('building_area', '<=', (int)$request->lb_max); }
        // Tag / furnish filter
        if ($request->filled('tag'))       { $query->whereHas('tags', fn($q) => $q->where('name', $request->tag)); }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price',         'asc'),
            'price_desc' => $query->orderBy('price',         'desc'),
            'lt_desc'    => $query->orderBy('land_area',     'desc'),
            'lb_desc'    => $query->orderBy('building_area', 'desc'),
            default      => $query->orderByDesc('created_datetime'),
        };

        $page       = max(1, (int) $request->input('page', 1));
        $totalCount = (clone $query)->count();
        $mapQuery   = clone $query; // clone before skip/take

        $properties = $query
            ->skip(($page - 1) * self::PER_PAGE)
            ->take(self::PER_PAGE)
            ->get()
            ->map(fn(PropertyUnit $u) => $this->formatCard($u))
            ->toArray();

        $totalPages = (int) ceil($totalCount / self::PER_PAGE);

        // Map markers — all matching properties that have valid coordinates
        $mapMarkers = $mapQuery
            ->whereNotNull('latitude')
            ->whereNotNull('longtidure')
            ->where('latitude',   '!=', 0)
            ->where('longtidure', '!=', 0)
            ->limit(500)
            ->get()
            ->map(function (PropertyUnit $u) {
                $card = $this->formatCard($u);
                return [
                    'lat'   => (float) $u->latitude,
                    'lng'   => (float) $u->longtidure,
                    'title' => $card['title'],
                    'price' => $card['price'],
                    'image' => url($card['image']),
                    'url'   => url($card['detail_url']),
                ];
            })
            ->toArray();

        $pageSeoArr = GetRedis::getRedisSimple('page_seo:all_products');
        $pageSeo    = $pageSeoArr
            ? (object) $pageSeoArr
            : PageSeo::where('page_key', 'all_products')->first();

        $keyData = EmbedKeyService::resolve();

        return view('front.layout.readyStockAllProduct', compact(
            'propertyTypes', 'propertyConditions', 'banner',
            'properties', 'totalCount', 'page', 'totalPages', 'sort',
            'urlSlugs', 'slugNames', 'browseBase', 'mapMarkers', 'keyData', 'pageSeo'
        ));
    }

    private function buildBrowseBase(array $slugs): string
    {
        $cond = $slugs['condition'] ?? null;
        $type = $slugs['type']      ?? null;
        $kota = $slugs['kota']      ?? null;
        $twn  = $slugs['township']  ?? null;

        if (!$cond)           return '/all-products';
        if (!$type)           return "/{$cond}";
        if (!$kota)           return "/{$cond}/{$type}";
        if (!$twn)            return "/{$cond}/{$type}/{$kota}";
        return "/{$cond}/{$type}/{$kota}/{$twn}";
    }
}
