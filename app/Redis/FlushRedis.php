<?php

namespace App\Redis;

use Illuminate\Support\Facades\Redis;

class FlushRedis
{
    /**
     * Flush semua key Redis milik aplikasi ini (berdasarkan REDIS_KEY prefix).
     */
    public static function flushRedis(): void
    {
        $redisPrefix = config('readystock.redis_key');
        if (empty($redisPrefix)) return;

        self::deleteByPattern("{$redisPrefix}:*");
    }

    /**
     * Flush semua key Redis group (berdasarkan REDIS_KEY_GROUP prefix).
     */
    public static function flushRedisGroup(): void
    {
        $redisPrefix = config('readystock.redis_key_group');
        if (empty($redisPrefix)) return;

        self::deleteByPattern("{$redisPrefix}:*");
    }

    /**
     * Flush key Redis berdasarkan awalan path.
     * Contoh: flushByPath('banners')       → hapus '{REDIS_KEY}:banners*'
     * Contoh: flushByPath('property_unit') → hapus '{REDIS_KEY}:property_unit*'
     */
    public static function flushByPath(string $path): void
    {
        $redisPrefix = config('readystock.redis_key');
        if (empty($redisPrefix)) return;

        self::deleteByPattern("{$redisPrefix}:{$path}*");
    }

    /**
     * Flush key Redis group berdasarkan awalan path.
     */
    public static function flushGroupByPath(string $path): void
    {
        $redisPrefix = config('readystock.redis_key_group');
        if (empty($redisPrefix)) return;

        self::deleteByPattern("{$redisPrefix}:{$path}*");
    }

    /**
     * Hapus key berdasarkan pattern.
     *
     * PENTING: Redis::keys() mengembalikan key DENGAN Laravel prefix (mis. 'laravel_database_'),
     * sedangkan Redis::del() juga otomatis menambahkan prefix.
     * Jadi kita harus strip prefix dari key sebelum memanggil del() agar tidak double-prefix.
     */
    private static function deleteByPattern(string $pattern): void
    {
        $keys = Redis::keys($pattern);
        if (empty($keys)) return;

        // Ambil prefix Laravel Redis (mis. 'laravel_database_')
        $laravelPrefix = config('database.redis.options.prefix', '');

        foreach ($keys as $key) {
            // Strip Laravel prefix agar del() tidak double-prefix
            $cleanKey = $laravelPrefix && str_starts_with($key, $laravelPrefix)
                ? substr($key, strlen($laravelPrefix))
                : $key;
            Redis::del($cleanKey);
        }
    }
}

