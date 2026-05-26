<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Cluster;
use App\Models\Kota;
use App\Models\PropertyCondition;
use App\Models\PropertyType;
use App\Models\PropertyUnit;
use App\Models\Provinsi;
use App\Models\Tag;
use App\Models\Township;
use App\Cache\CacheKey;
use App\Cache\CacheWarmer;
use App\Redis\GetRedis;
use App\Redis\SetRedis;
use Illuminate\Http\JsonResponse;

class RedisController extends Controller
{
    // ============================================================
    // GET — Banners aktif
    // GET /api/data/banners
    // ============================================================
    public function banners(): JsonResponse
    {
        $cached = GetRedis::getRedisSimple('banners');
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $data = Banner::where('is_active', 1)->get()->toArray();
        SetRedis::setRedisSimple($data, 'banners');

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // GET — Property Types dengan terjemahan
    // GET /api/data/property-types/{lang?}
    // ============================================================
    public function propertyTypes(string $lang = 'id'): JsonResponse
    {
        $cached = GetRedis::getRedisSimple('property_types', $lang);
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $data = PropertyType::with(['translations' => fn($q) => $q->where('locale', $lang)])
            ->where('is_active', 1)
            ->get()->toArray();

        SetRedis::setRedisSimple($data, 'property_types', $lang);

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // GET — Property Conditions dengan terjemahan
    // GET /api/data/property-conditions/{lang?}
    // ============================================================
    public function propertyConditions(string $lang = 'id'): JsonResponse
    {
        $cached = GetRedis::getRedisSimple('property_conditions', $lang);
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $data = PropertyCondition::with(['translations' => fn($q) => $q->where('locale', $lang)])
            ->where('is_active', 1)
            ->get()->toArray();

        SetRedis::setRedisSimple($data, 'property_conditions', $lang);

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // GET — Tags (semua translations disertakan)
    // GET /api/data/tags
    // ============================================================
    public function tags(): JsonResponse
    {
        $cached = GetRedis::getRedisSimple('tags');
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $data = Tag::with('translations')->get()->toArray();
        SetRedis::setRedisSimple($data, 'tags');

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // GET — Semua Provinsi
    // GET /api/data/provinces
    // ============================================================
    public function provinces(): JsonResponse
    {
        $cached = GetRedis::getRedisSimple('provinces');
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $data = Provinsi::orderBy('provinsi_name')->get()->toArray();
        SetRedis::setRedisSimple($data, 'provinces');

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // GET — Kota berdasarkan Provinsi
    // GET /api/data/cities/{provinsiId}
    // ============================================================
    public function cities(int $provinsiId): JsonResponse
    {
        $cached = GetRedis::getRedisSimple("cities:{$provinsiId}");
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $data = Kota::where('provinsi_id', $provinsiId)
            ->orderBy('nama_kota')
            ->get()->toArray();

        SetRedis::setRedisSimple($data, "cities:{$provinsiId}");

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // GET — Semua Township
    // GET /api/data/townships
    // ============================================================
    public function townships(): JsonResponse
    {
        $cached = GetRedis::getRedisSimple('townships');
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $data = Township::orderBy('township_name')->get()->toArray();
        SetRedis::setRedisSimple($data, 'townships');

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // GET — Semua Cluster
    // GET /api/data/clusters
    // ============================================================
    public function clusters(): JsonResponse
    {
        $cached = GetRedis::getRedisSimple('clusters');
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $data = Cluster::orderBy('cluster_name')->get()->toArray();
        SetRedis::setRedisSimple($data, 'clusters');

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // GET — Daftar Property Units aktif (dengan semua relasi)
    // GET /api/data/property-units/{lang?}
    // ============================================================
    public function propertyUnits(string $lang = 'id'): JsonResponse
    {
        $cached = GetRedis::getRedisSimple('property_units', $lang);
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $data = PropertyUnit::with([
            'translations'                        => fn($q) => $q->where('locale', $lang),
            'interiors',
            'specs.translations'                  => fn($q) => $q->where('locale', $lang),
            'facilities.translations'             => fn($q) => $q->where('locale', $lang),
            'nearbyLocations.translations'        => fn($q) => $q->where('locale', $lang),
        ])
        ->where('is_active', 1)
        ->get()->toArray();

        SetRedis::setRedisSimple($data, 'property_units', $lang);

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // GET — Detail satu Property Unit
    // GET /api/data/property-unit/{id}/{lang?}
    // ============================================================
    public function propertyUnit(int $id, string $lang = 'id'): JsonResponse
    {
        $cached = GetRedis::getRedisSimple("property_unit:{$id}", $lang);
        if (!empty($cached)) {
            return response()->json(['source' => 'redis', 'data' => $cached]);
        }

        $unit = PropertyUnit::with([
            'translations'                        => fn($q) => $q->where('locale', $lang),
            'interiors.translations'              => fn($q) => $q->where('locale', $lang),
            'specs.translations'                  => fn($q) => $q->where('locale', $lang),
            'facilities.translations'             => fn($q) => $q->where('locale', $lang),
            'nearbyLocations.translations'        => fn($q) => $q->where('locale', $lang),
        ])
        ->where('property_id', $id)
        ->first();

        if (!$unit) {
            return response()->json(['error' => 'Property tidak ditemukan'], 404);
        }

        $data = $unit->toArray();
        SetRedis::setRedisSimple($data, "property_unit:{$id}", $lang);

        return response()->json(['source' => 'db', 'data' => $data]);
    }

    // ============================================================
    // POST — Refresh (hapus + re-cache) data tertentu
    // POST /api/data/refresh/{type}/{lang?}
    // type: banners | property_types | property_conditions | tags |
    //       provinces | cities | townships | clusters |
    //       property_units | property_unit
    // ============================================================
    public function refresh(string $type, string $lang = 'id'): JsonResponse
    {
        $known = [
            CacheKey::BANNERS, CacheKey::PROPERTY_TYPES, CacheKey::PROPERTY_CONDITIONS,
            CacheKey::TAGS, CacheKey::PROVINCES, CacheKey::TOWNSHIPS,
            CacheKey::CLUSTERS, CacheKey::PROPERTY_UNITS,
        ];

        if (!in_array($type, $known, true)) {
            return response()->json(['error' => "Type '{$type}' tidak dikenali"], 400);
        }

        CacheWarmer::reload($type);

        return match ($type) {
            CacheKey::BANNERS             => $this->banners(),
            CacheKey::PROPERTY_TYPES      => $this->propertyTypes($lang),
            CacheKey::PROPERTY_CONDITIONS => $this->propertyConditions($lang),
            CacheKey::TAGS                => $this->tags(),
            CacheKey::PROVINCES           => $this->provinces(),
            CacheKey::TOWNSHIPS           => $this->townships(),
            CacheKey::CLUSTERS            => $this->clusters(),
            CacheKey::PROPERTY_UNITS      => $this->propertyUnits($lang),
            default                        => response()->json(['error' => "Type '{$type}' tidak dikenali"], 400),
        };
    }

    // ============================================================
    // POST — Warm up: isi semua cache dari DB sekaligus
    // POST /api/data/warm-up
    // ============================================================
    public function warmUp(): JsonResponse
    {
        CacheWarmer::warmUp();
        return response()->json(['message' => 'Semua cache berhasil di-warm up']);
    }
}
