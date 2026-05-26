<?php

namespace App\Redis;

use Illuminate\Support\Facades\Redis;
use App\Services\Front\UrlParserService;

class GetRedis
{
    /**
     * Ambil data global (tidak bergantung URL) dari Redis.
     * Key format: {REDIS_KEY}:{path} atau {REDIS_KEY}:{path}:{lang}
     */
    public static function getRedisSimple(string $path, string $lang = ''): array
    {
        if (!$path) return [];

        $key = config('readystock.redis_key') . ":{$path}";
        if ($lang) $key .= ":{$lang}";

        $value = Redis::get($key);
        if (!$value) return [];

        return json_decode($value, true) ?? [];
    }

    /**
     * Ambil berdasarkan slug_township (level township).
     */
    public static function getRedisLevel1(string $path = null, string $lang = 'id'): array
    {
        if (!$path) return [];

        $urlParser = app(UrlParserService::class);
        $lastSegmen = $urlParser->getLastSegment();

        $patternWithLang = config('readystock.redis_key') . ":{$path}:{$lang}:{$lastSegmen['slug_township']}";
        $keys = Redis::keys($patternWithLang);

        if (empty($keys)) {
            $patternFallback = config('readystock.redis_key') . ":{$path}:{$lastSegmen['slug_township']}";
            $keys = Redis::keys($patternFallback);
        }

        return self::resolveKeys($keys);
    }

    /**
     * Ambil berdasarkan slug_cluster (level commercial / microsite / cluster).
     */
    public static function getRedisLevel2(string $path = null, string $lang = 'id'): array
    {
        if (!$path) return [];

        $urlParser = app(UrlParserService::class);
        $lastSegmen = $urlParser->getLastSegment();

        $patternWithLang = config('readystock.redis_key') . ":{$path}:{$lang}:{$lastSegmen['slug_cluster']}";
        $keys = Redis::keys($patternWithLang);

        if (empty($keys)) {
            $patternFallback = config('readystock.redis_key') . ":{$path}:{$lastSegmen['slug_cluster']}";
            $keys = Redis::keys($patternFallback);
        }

        return self::resolveKeys($keys);
    }

    /**
     * Ambil berdasarkan slug_unit_type (level product type / unit).
     */
    public static function getRedisLevel3(string $path = null, string $lang = 'id'): array
    {
        if (!$path) return [];

        $urlParser = app(UrlParserService::class);
        $lastSegmen = $urlParser->getLastSegment();

        $patternWithLang = config('readystock.redis_key') . ":{$path}:{$lang}:{$lastSegmen['slug_unit_type']}";
        $keys = Redis::keys($patternWithLang);

        if (empty($keys)) {
            $patternFallback = config('readystock.redis_key') . ":{$path}:{$lastSegmen['slug_unit_type']}";
            $keys = Redis::keys($patternFallback);
        }

        return self::resolveKeys($keys);
    }

    /**
     * Ambil data di luar struktur URL utama.
     */
    public static function getRedisOutsiteStructure(string $path = null, string $lang = 'id'): array
    {
        if (!$path) return [];

        $urlParser = app(UrlParserService::class);
        $lastSegmen = $urlParser->get_segment_out_sturcture();

        $patternWithLang = config('readystock.redis_key') . ":{$path}:{$lang}:{$lastSegmen['slug_township']}";
        $keys = Redis::keys($patternWithLang);

        if (empty($keys)) {
            $patternFallback = config('readystock.redis_key') . ":{$path}:{$lastSegmen['slug_township']}";
            $keys = Redis::keys($patternFallback);
        }

        return self::resolveKeys($keys);
    }

    /**
     * Ambil data group (lintas township/cluster).
     *
     * FIX: dihapus trailing colon, pakai Redis::get langsung karena key-nya exact.
     */
    public static function getRedisGroup(string $path = null, string $lang = 'id'): array
    {
        if (!$path) return [];

        $key = config('readystock.redis_key_group') . ":{$path}:{$lang}";
        $value = Redis::get($key);

        if (!$value) return [];

        return json_decode($value, true) ?? [];
    }

    /**
     * Debug: dump semua key Redis yang ada.
     */
    public static function debugRedisDump(): void
    {
        $keys = Redis::keys(config('readystock.redis_key') . ':*');

        if (empty($keys)) {
            echo "Redis terhubung, tapi tidak ada key yang cocok.\n";
            return;
        }

        echo "Total Key Ditemukan: " . count($keys) . "\n\n";

        foreach ($keys as $key) {
            $value = Redis::get($key);
            echo "KEY: $key\n";
            echo "VALUE:\n";
            var_dump(json_decode($value, true) ?? $value);
            echo "\n------------------------\n";
        }
    }

    /**
     * Helper: ambil nilai dari array of Redis keys.
     */
    private static function resolveKeys(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $value = Redis::get($key);
            if ($value) {
                $decoded = @json_decode($value, true);
                $result[] = $decoded ?? @unserialize($value) ?? $value;
            }
        }
        return $result;
    }
}
