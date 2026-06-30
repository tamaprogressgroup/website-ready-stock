<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PropertyUnit;
use App\Redis\GetRedis;
use App\Redis\SetRedis;
use Illuminate\Support\Str;

abstract class BaseFrontController extends Controller
{
    protected string $lang = 'id';

    /**
     * Ambil data dari Redis. Kalau kosong, jalankan $callback (query DB),
     * simpan ke Redis, lalu kembalikan datanya.
     * Data yang disimpan selalu di-strip dari base URL agar host-agnostic.
     */
    protected function resolveCache(string $path, string $lang, callable $callback): array
    {
        $cached = GetRedis::getRedisSimple($path, $lang);
        if (!empty($cached)) return $cached;

        $data = $callback();
        if (!empty($data)) {
            SetRedis::setRedisSimple($this->stripBaseUrl($data), $path, $lang);
        }
        return $data ?? [];
    }

    /**
     * Hapus base URL (APP_URL) dari semua nilai string dalam array secara rekursif.
     * Tujuannya agar URL gambar di Redis selalu berupa relative path,
     * sehingga bisa diakses dari host manapun (localhost, IP, domain).
     */
    private function stripBaseUrl(array $data): array
    {
        $base = rtrim(config('app.url'), '/');
        array_walk_recursive($data, function (&$val) use ($base) {
            if (is_string($val) && str_starts_with($val, $base . '/')) {
                $val = ltrim(substr($val, strlen($base)), '/');
            }
        });
        return $data;
    }

    /**
     * Format satu PropertyUnit Eloquent object menjadi array kartu properti
     * yang siap dipakai di view.
     */
    protected function formatCard(PropertyUnit $unit): array
    {
        $trans    = $unit->translations->first();
        $interior = $unit->interiors->first();
        $price    = (float) $unit->price;
        $diskon   = (float) $unit->diskon;

        $clusterName  = $unit->cluster?->cluster_name  ?? '';
        $townshipName = $unit->township?->township_name ?? '';
        $kotaName     = $unit->kota?->nama_kota       ?? '';
        $location     = implode(', ', array_filter([$clusterName, $townshipName, $kotaName])) ?: 'Indonesia';

        $interior = $unit->interiors->firstWhere('order', 1) ?? $unit->interiors->first();

        // Store relative path only — full URL resolved in view via url() so host stays dynamic
        $image = ($interior && $interior->image)
            ? 'storage/' . $interior->image
            : 'stock-image/rekomendasi-property.jpg';

        // Badges from is_label=1 tags attached to this property
        $badges = [];
        if ($unit->relationLoaded('tags')) {
            foreach ($unit->tags->where('is_label', 1) as $tag) {
                $badges[] = [
                    'text'  => $tag->name,
                    'bg'    => $tag->color_code ?: '#3b5998',
                    'color' => '#ffffff',
                ];
            }
        }

        return [
            'property_id' => $unit->property_id,
            'detail_url'  => $this->buildDetailUrl($unit),
            'wa_url'      => $this->buildWaUrl($unit),
            'wa_phone'    => $this->buildWaPhone($unit),
            'badges'      => $badges,
            'image'       => $image,
            'price'       => $this->formatPrice($price, $diskon),
            'price_raw'   => $price,
            'diskon_raw'  => $diskon,
            'title'       => $trans?->title ?? $trans?->property_name ?? '-',
            'location'    => $location,
            'beds'        => $unit->bedrooms      ?? 0,
            'baths'       => $unit->bathroom      ?? 0,
            'lt'          => $unit->land_area     ?? 0,
            'lb'          => $unit->building_area ?? 0,
        ];
    }

    protected function buildWaPhone(PropertyUnit $unit): string
    {
        $phone = preg_replace('/\D/', '', $unit->no_hp ?? '');
        if (!$phone) return '';
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }

    protected function buildWaUrl(PropertyUnit $unit): string
    {
        $phone = $this->buildWaPhone($unit);
        if (!$phone) return '#';
        $trans = $unit->translations->first();
        $title = $trans?->title ?? $trans?->property_name ?? 'Properti Kami';
        $text  = 'Halo Saya ingin informasi lengkap tentang ' . $title . ', Mohon kirimkan detailnya';
        return 'https://api.whatsapp.com/send/?phone=' . $phone
            . '&text=' . rawurlencode($text)
            . '&type=phone_number&app_absent=0';
    }

    private function buildDetailUrl(PropertyUnit $unit): string
    {
        $condSlug = $unit->relationLoaded('condition') && $unit->condition
            ? Str::slug($unit->condition->translations->first()?->condition_name ?? '')
            : '';
        $typeSlug = $unit->relationLoaded('propertyType') && $unit->propertyType
            ? Str::slug($unit->propertyType->translations->first()?->type_name ?? '')
            : '';
        $kotaSlug = $unit->kota
            ? Str::slug($unit->kota->nama_kota ?? '')
            : '';
        $twnSlug = $unit->relationLoaded('township') && $unit->township
            ? Str::slug($unit->township->township_name ?? '')
            : '';
        $pSlug = $unit->slug ?? '';

        if ($condSlug && $typeSlug && $kotaSlug && $twnSlug && $pSlug) {
            return route('front.detail.seo', [
                'condition' => $condSlug,
                'type'      => $typeSlug,
                'kota'      => $kotaSlug,
                'township'  => $twnSlug,
                'slug'      => $pSlug,
            ], false);
        }

        return route('front.detail', $unit->property_id, false);
    }

    protected function formatPrice(float $price, float $diskon = 0): string
    {
        $net = max(0.0, $price - $diskon);

        if ($net >= 1_000_000_000) {
            $val = $net / 1_000_000_000;
            $fmt = fmod($val, 1) === 0.0 ? number_format($val, 0, ',', '.') : number_format($val, 1, ',', '.');
            return "Rp {$fmt} Miliar";
        }

        if ($net >= 1_000_000) {
            $val = $net / 1_000_000;
            $fmt = fmod($val, 1) === 0.0 ? number_format($val, 0, ',', '.') : number_format($val, 1, ',', '.');
            return "Rp {$fmt} Juta";
        }

        return 'Rp ' . number_format($net, 0, ',', '.');
    }
}
