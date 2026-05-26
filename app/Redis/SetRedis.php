<?php

namespace App\Redis;

use Illuminate\Support\Facades\Redis;
use App\Services\Front\UrlParserService;

class SetRedis
{
    private const DEFAULT_TTL = 3600;

    /**
     * Simpan data global (tidak bergantung URL) ke Redis.
     * Key format: {REDIS_KEY}:{path} atau {REDIS_KEY}:{path}:{lang}
     */
    public static function setRedisSimple(array $data, string $path, string $lang = '', int $ttl = self::DEFAULT_TTL): void
    {
        if (empty($data)) return;

        $key = config('readystock.redis_key') . ":{$path}";
        if ($lang) $key .= ":{$lang}";

        Redis::setex($key, $ttl, json_encode($data));
    }

    /**
     * Simpan berdasarkan slug_township (level township).
     * Key format: {REDIS_KEY}:{path}:{lang}:{slug_township}
     */
    public static function setRedisLevel1(array $data, string $path, string $lang = 'id', int $ttl = self::DEFAULT_TTL): void
    {
        if (empty($data)) return;

        $urlParser = app(UrlParserService::class);
        $lastSegmen = $urlParser->getLastSegment();

        if (empty($lastSegmen['slug_township'])) return;

        $key = config('readystock.redis_key') . ":{$path}:{$lang}:{$lastSegmen['slug_township']}";
        Redis::setex($key, $ttl, json_encode($data));
    }

    /**
     * Simpan berdasarkan slug_cluster (level commercial / microsite / cluster).
     * Key format: {REDIS_KEY}:{path}:{lang}:{slug_cluster}
     *
     * FIX: sebelumnya cek slug_township tapi key menggunakan slug_cluster.
     */
    public static function setRedisLevel2(array $data, string $path, string $lang = 'id', int $ttl = self::DEFAULT_TTL): void
    {
        if (empty($data)) return;

        $urlParser = app(UrlParserService::class);
        $lastSegmen = $urlParser->getLastSegment();

        if (empty($lastSegmen['slug_cluster'])) return;

        $key = config('readystock.redis_key') . ":{$path}:{$lang}:{$lastSegmen['slug_cluster']}";
        Redis::setex($key, $ttl, json_encode($data));
    }

    /**
     * Simpan berdasarkan slug_unit_type (level product type / unit).
     * Key format: {REDIS_KEY}:{path}:{lang}:{slug_unit_type}
     */
    public static function setRedisLevel3(array $data, string $path, string $lang = 'id', int $ttl = self::DEFAULT_TTL): void
    {
        if (empty($data)) return;

        $urlParser = app(UrlParserService::class);
        $lastSegmen = $urlParser->getLastSegment();

        if (empty($lastSegmen['slug_unit_type'])) return;

        $key = config('readystock.redis_key') . ":{$path}:{$lang}:{$lastSegmen['slug_unit_type']}";
        Redis::setex($key, $ttl, json_encode($data));
    }

    /**
     * Simpan data di luar struktur URL utama (berdasarkan slug_township dari segment khusus).
     */
    public static function setRedisOutsiteStructure(array $data, string $path, string $lang = 'id', int $ttl = self::DEFAULT_TTL): void
    {
        if (empty($data)) return;

        $urlParser = app(UrlParserService::class);
        $lastSegmen = $urlParser->get_segment_out_sturcture();

        if (empty($lastSegmen['slug_township'])) return;

        $key = config('readystock.redis_key') . ":{$path}:{$lang}:{$lastSegmen['slug_township']}";
        Redis::setex($key, $ttl, json_encode($data));
    }

    /**
     * Simpan data group (lintas township/cluster).
     * Key format: {REDIS_KEY_GROUP}:{path}:{lang}
     *
     * FIX: dihapus trailing colon agar konsisten dengan getRedisGroup.
     */
    public static function setRedisGroup(array $data, string $path, string $lang = 'id', int $ttl = self::DEFAULT_TTL): void
    {
        if (empty($data)) return;

        $key = config('readystock.redis_key_group') . ":{$path}:{$lang}";
        Redis::setex($key, $ttl, json_encode($data));
    }
}
