<?php

namespace App\Http\Controllers\Front;

use App\Models\PropertyUnit;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SitemapController extends BaseFrontController
{
    public function index(): Response
    {
        $lang = $this->lang;

        $units = PropertyUnit::with([
            'translations'              => fn($q) => $q->where('locale', $lang),
            'condition.translations'    => fn($q) => $q->where('locale', $lang),
            'propertyType.translations' => fn($q) => $q->where('locale', $lang),
            'kota',
            'township',
        ])
        ->where('is_active', 1)
        ->where('status_id', 1)
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->orderBy('updated_datetime', 'desc')
        ->get();

        $urls   = [];
        $seenBrowse = [];

        // Static pages
        $urls[] = [
            'loc'        => url('/'),
            'lastmod'    => now()->format('Y-m-d'),
            'changefreq' => 'daily',
            'priority'   => '1.0',
        ];
        $urls[] = [
            'loc'        => url('/all-products'),
            'lastmod'    => now()->format('Y-m-d'),
            'changefreq' => 'daily',
            'priority'   => '0.9',
        ];

        foreach ($units as $unit) {
            $condName = $unit->condition?->translations->first()?->condition_name ?? '';
            $typeName = $unit->propertyType?->translations->first()?->type_name ?? '';
            $kotaName = $unit->kota?->nama_kota ?? '';
            $twnName  = $unit->township?->township_name ?? '';
            $slug     = $unit->slug ?? '';

            if (!$condName || !$typeName || !$kotaName || !$twnName || !$slug) {
                continue;
            }

            $condSlug = Str::slug($condName);
            $typeSlug = Str::slug($typeName);
            $kotaSlug = Str::slug($kotaName);
            $twnSlug  = Str::slug($twnName);

            $rawDate = $unit->updated_datetime ?? $unit->created_datetime;
            $lastmod = $rawDate
                ? substr($rawDate, 0, 10)
                : now()->format('Y-m-d');

            // Browse: condition
            $k1 = $condSlug;
            if (!isset($seenBrowse[$k1])) {
                $seenBrowse[$k1] = true;
                $urls[] = [
                    'loc'        => url("/{$condSlug}"),
                    'lastmod'    => $lastmod,
                    'changefreq' => 'weekly',
                    'priority'   => '0.6',
                ];
            }

            // Browse: condition + type
            $k2 = "{$condSlug}/{$typeSlug}";
            if (!isset($seenBrowse[$k2])) {
                $seenBrowse[$k2] = true;
                $urls[] = [
                    'loc'        => url("/{$condSlug}/{$typeSlug}"),
                    'lastmod'    => $lastmod,
                    'changefreq' => 'weekly',
                    'priority'   => '0.6',
                ];
            }

            // Browse: condition + type + kota
            $k3 = "{$condSlug}/{$typeSlug}/{$kotaSlug}";
            if (!isset($seenBrowse[$k3])) {
                $seenBrowse[$k3] = true;
                $urls[] = [
                    'loc'        => url("/{$condSlug}/{$typeSlug}/{$kotaSlug}"),
                    'lastmod'    => $lastmod,
                    'changefreq' => 'weekly',
                    'priority'   => '0.65',
                ];
            }

            // Browse: condition + type + kota + township
            $k4 = "{$condSlug}/{$typeSlug}/{$kotaSlug}/{$twnSlug}";
            if (!isset($seenBrowse[$k4])) {
                $seenBrowse[$k4] = true;
                $urls[] = [
                    'loc'        => url("/{$condSlug}/{$typeSlug}/{$kotaSlug}/{$twnSlug}"),
                    'lastmod'    => $lastmod,
                    'changefreq' => 'weekly',
                    'priority'   => '0.7',
                ];
            }

            // Property detail
            $urls[] = [
                'loc'        => url("/{$condSlug}/{$typeSlug}/{$kotaSlug}/{$twnSlug}/{$slug}"),
                'lastmod'    => $lastmod,
                'changefreq' => 'weekly',
                'priority'   => '0.8',
            ];
        }

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
