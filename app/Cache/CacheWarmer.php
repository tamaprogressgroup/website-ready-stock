<?php

namespace App\Cache;

use App\Models\Banner;
use App\Models\Cluster;
use App\Models\PropertyCondition;
use App\Models\PropertyType;
use App\Models\PropertyUnit;
use App\Models\Provinsi;
use App\Models\Tag;
use App\Models\Township;
use App\Redis\FlushRedis;
use App\Redis\SetRedis;

/**
 * CacheWarmer — flush + reload Redis cache from DB.
 *
 * Rules:
 *  - Back-end: never reads from Redis, always queries DB directly.
 *  - Front-end: always reads from Redis (populated here or by front-end services).
 *  - Every back-end data change calls CacheWarmer::reload() to keep Redis fresh.
 *
 * Usage:
 *   CacheWarmer::reload(CacheKey::BANNERS);
 *   CacheWarmer::reload(CacheKey::PROPERTY_UNIT, $propertyId);
 */
class CacheWarmer
{
    private const LOCALES = ['id', 'en'];

    /**
     * Flush + re-populate a single cache path from DB.
     *
     * For context-dependent paths (front-end page cache keyed by URL slug)
     * only flushes — they are repopulated automatically on the next page visit.
     *
     * @param  string   $path       One of the CacheKey::* constants
     * @param  int|null $propertyId Only needed for CacheKey::PROPERTY_UNIT
     */
    public static function reload(string $path, ?int $propertyId = null): void
    {
        match ($path) {
            CacheKey::BANNERS             => self::reloadBanners(),
            CacheKey::PROPERTY_TYPES      => self::reloadPropertyTypes(),
            CacheKey::PROPERTY_CONDITIONS => self::reloadPropertyConditions(),
            CacheKey::TAGS                => self::reloadTags(),
            CacheKey::PROVINCES           => self::reloadProvinces(),
            CacheKey::TOWNSHIPS           => self::reloadTownships(),
            CacheKey::CLUSTERS            => self::reloadClusters(),
            CacheKey::PROPERTY_UNITS      => self::reloadPropertyUnits(),
            CacheKey::PROPERTY_UNIT       => self::reloadPropertyUnit($propertyId),
            default                        => FlushRedis::flushByPath($path),
        };
    }

    /**
     * Full warm-up: flush all app keys then re-populate everything from DB.
     * Call after deployment or bulk data imports.
     */
    public static function warmUp(): void
    {
        FlushRedis::flushRedis();

        self::reloadBanners();
        self::reloadProvinces();
        self::reloadTownships();
        self::reloadClusters();
        self::reloadTags();
        self::reloadPropertyTypes();
        self::reloadPropertyConditions();
        self::reloadPropertyUnits();
    }

    // =========================================================================
    // Private loaders — each handles its own flush + DB query + Redis write
    // =========================================================================

    private static function reloadBanners(): void
    {
        FlushRedis::flushByPath(CacheKey::BANNERS);
        $data = Banner::where('is_active', 1)->get()->toArray();
        if (!empty($data)) {
            SetRedis::setRedisSimple($data, CacheKey::BANNERS);
        }
    }

    private static function reloadPropertyTypes(): void
    {
        FlushRedis::flushByPath(CacheKey::PROPERTY_TYPES);
        foreach (self::LOCALES as $lang) {
            $data = PropertyType::with(['translations' => fn($q) => $q->where('locale', $lang)])
                ->where('is_active', 1)->get()->toArray();
            if (!empty($data)) {
                SetRedis::setRedisSimple($data, CacheKey::PROPERTY_TYPES, $lang);
            }
        }
    }

    private static function reloadPropertyConditions(): void
    {
        FlushRedis::flushByPath(CacheKey::PROPERTY_CONDITIONS);
        foreach (self::LOCALES as $lang) {
            $data = PropertyCondition::with(['translations' => fn($q) => $q->where('locale', $lang)])
                ->where('is_active', 1)->get()->toArray();
            if (!empty($data)) {
                SetRedis::setRedisSimple($data, CacheKey::PROPERTY_CONDITIONS, $lang);
            }
        }
    }

    private static function reloadTags(): void
    {
        FlushRedis::flushByPath(CacheKey::TAGS);
        $data = Tag::with('translations')->get()->toArray();
        if (!empty($data)) {
            SetRedis::setRedisSimple($data, CacheKey::TAGS);
        }
    }

    private static function reloadProvinces(): void
    {
        FlushRedis::flushByPath(CacheKey::PROVINCES);
        $data = Provinsi::orderBy('provinsi_name')->get()->toArray();
        if (!empty($data)) {
            SetRedis::setRedisSimple($data, CacheKey::PROVINCES);
        }
    }

    private static function reloadTownships(): void
    {
        FlushRedis::flushByPath(CacheKey::TOWNSHIPS);
        $data = Township::orderBy('township_name')->get()->toArray();
        if (!empty($data)) {
            SetRedis::setRedisSimple($data, CacheKey::TOWNSHIPS);
        }
    }

    private static function reloadClusters(): void
    {
        FlushRedis::flushByPath(CacheKey::CLUSTERS);
        $data = Cluster::orderBy('cluster_name')->get()->toArray();
        if (!empty($data)) {
            SetRedis::setRedisSimple($data, CacheKey::CLUSTERS);
        }
    }

    private static function reloadPropertyUnits(): void
    {
        FlushRedis::flushByPath(CacheKey::PROPERTY_UNITS);
        foreach (self::LOCALES as $lang) {
            $data = PropertyUnit::with([
                'translations'                 => fn($q) => $q->where('locale', $lang),
                'interiors',
                'specs.translations'           => fn($q) => $q->where('locale', $lang),
                'facilities.translations'      => fn($q) => $q->where('locale', $lang),
                'nearbyLocations.translations' => fn($q) => $q->where('locale', $lang),
            ])->where('is_active', 1)->get()->toArray();
            if (!empty($data)) {
                SetRedis::setRedisSimple($data, CacheKey::PROPERTY_UNITS, $lang);
            }
        }
    }

    private static function reloadPropertyUnit(?int $propertyId): void
    {
        if (!$propertyId) {
            FlushRedis::flushByPath(CacheKey::PROPERTY_UNIT);
            return;
        }
        FlushRedis::flushByPath(CacheKey::PROPERTY_UNIT . ":{$propertyId}");
        foreach (self::LOCALES as $lang) {
            $unit = PropertyUnit::with([
                'translations'                 => fn($q) => $q->where('locale', $lang),
                'interiors.translations'       => fn($q) => $q->where('locale', $lang),
                'specs.translations'           => fn($q) => $q->where('locale', $lang),
                'facilities.translations'      => fn($q) => $q->where('locale', $lang),
                'nearbyLocations.translations' => fn($q) => $q->where('locale', $lang),
            ])->where('property_id', $propertyId)->first();
            if ($unit) {
                SetRedis::setRedisSimple($unit->toArray(), CacheKey::PROPERTY_UNIT . ":{$propertyId}", $lang);
            }
        }
    }
}
