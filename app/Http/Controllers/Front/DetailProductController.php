<?php

namespace App\Http\Controllers\Front;

use App\Models\PropertyMedia;
use App\Models\PropertyUnit;
use Illuminate\View\View;

class DetailProductController extends BaseFrontController
{
    public function showBySlug(string $condition, string $type, string $kota, string $township, string $slug): View
    {
        $unit = PropertyUnit::where('slug', $slug)->first();
        if (!$unit) {
            abort(404, 'Properti tidak ditemukan.');
        }
        return $this->index($unit->property_id);
    }

    public function index(int $id): View
    {
        $property = $this->resolveCache("property_detail:{$id}", $this->lang, function() use ($id) {
            $unit = PropertyUnit::with([
                'translations'                 => fn($q) => $q->where('locale', $this->lang),
                'interiors'                    => fn($q) => $q->where('is_active', 1)->orderBy('order'),
                'interiors.translations'       => fn($q) => $q->where('locale', $this->lang),
                'specs.translations'           => fn($q) => $q->where('locale', $this->lang),
                'facilities.translations'      => fn($q) => $q->where('locale', $this->lang),
                'nearbyLocations.translations' => fn($q) => $q->where('locale', $this->lang),
                'extraFeatures.translations'   => fn($q) => $q->where('locale', $this->lang),
                'tags',
                'kota',
                'provinsi',
                'township',
            ])
            ->where('property_id', $id)
            ->first();

            if (!$unit) return [];

            return $this->buildDetailArray($unit);
        });

        if (empty($property)) {
            abort(404, 'Properti tidak ditemukan.');
        }

        $relatedProperties = $this->resolveCache("related_properties:{$id}", $this->lang, fn() =>
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
            ->where('is_active', 1)
            ->where('status_id', 1)
            ->where('property_id', '!=', $id)
            ->limit(4)
            ->get()
            ->map(fn(PropertyUnit $u) => $this->formatCard($u))
            ->toArray()
        );

        return view('front.layout.readyStockDetailProduct', compact('property', 'relatedProperties'));
    }

    private function buildDetailArray(PropertyUnit $unit): array
    {
        $trans    = $unit->translations->first();
        $price    = (float) $unit->price;
        $diskon   = (float) $unit->diskon;
        $original = $this->formatPrice($price);
        $final    = $this->formatPrice($price, $diskon);

        $townshipName = $unit->township?->township_name ?? '';
        $kotaName     = $unit->kota?->nama_kota       ?? '';
        $location     = implode(', ', array_filter([$townshipName, $kotaName])) ?: 'Indonesia';

        // All tags — split into label badges vs full list
        $badges  = [];
        $allTags = [];
        if ($unit->relationLoaded('tags')) {
            foreach ($unit->tags as $tag) {
                $item = [
                    'text'  => $tag->name,
                    'bg'    => $tag->color_code ?: ($tag->is_label ? '#3b5998' : '#6c757d'),
                    'color' => '#ffffff',
                ];
                $allTags[] = $item;
                if ($tag->is_label) {
                    $badges[] = $item;
                }
            }
        }

        // Images from interiors — key tetap 'url', stripBaseUrl di resolveCache akan normalise host
        $images = $unit->interiors->map(fn($i) => [
            'url'     => $i->image ? 'storage/' . $i->image : 'stock-image/rekomendasi-property.jpg',
            'name'    => $i->translations->first()?->interior_name ?? 'Foto',
            'caption' => $i->translations->first()?->interior_name ?? '',
        ])->toArray();

        if (empty($images)) {
            $images = [
                ['url' => 'stock-image/rekomendasi-property.jpg', 'name' => 'Eksterior',  'caption' => 'Eksterior'],
                ['url' => 'stock-image/par1.jpg',                 'name' => 'Interior 1', 'caption' => 'Interior 1'],
                ['url' => 'stock-image/par2.jpg',                 'name' => 'Interior 2', 'caption' => 'Interior 2'],
                ['url' => 'stock-image/prc.jpg',                  'name' => 'Interior 3', 'caption' => 'Interior 3'],
            ];
        }

        // Specs
        $specs = [];
        foreach ($unit->specs as $spec) {
            $specTrans = $spec->translations->first();
            if ($specTrans) {
                $specs[] = [
                    'key'   => $specTrans->spec_key   ?? '',
                    'value' => $specTrans->spec_value ?? '',
                    'unit'  => $specTrans->satuan     ?? '',
                ];
            }
        }
        if (empty($specs)) {
            $specs = [
                ['key' => 'Luas Tanah',    'value' => $unit->land_area     ?? '-', 'unit' => 'm²'],
                ['key' => 'Luas Bangunan', 'value' => $unit->building_area ?? '-', 'unit' => 'm²'],
                ['key' => 'Carport',       'value' => $unit->carports      ?? '-', 'unit' => ''],
                ['key' => 'Listrik',       'value' => $unit->electricity   ?? '-', 'unit' => 'VA'],
            ];
        }

        // Facilities — key image_url, stripBaseUrl di resolveCache normalise host
        $facilities = $unit->facilities->map(fn($f) => [
            'icon'      => $f->icon_url ?? 'fas fa-check',
            'name'      => $f->translations->first()?->name ?? '-',
            'image_url' => $f->image ? 'storage/' . $f->image : null,
        ])->toArray();

        // Nearby locations
        $nearby = $unit->nearbyLocations->flatMap(fn($n) =>
            $n->translations->pluck('name')
        )->filter()->toArray();

        // Extra features
        $extraFeatures = $unit->relationLoaded('extraFeatures')
            ? $unit->extraFeatures->map(fn($e) => [
                'icon' => $e->icon_url ?? 'fas fa-star',
                'name' => $e->translations->first()?->name ?? '-',
            ])->toArray()
            : [];

        // Map coordinates — null means not set
        $lat = ($unit->latitude   !== null && $unit->latitude   != 0) ? $unit->latitude   : null;
        $lng = ($unit->longtidure !== null && $unit->longtidure != 0) ? $unit->longtidure : null;

        // Media (video, 360, youtube)
        $media = PropertyMedia::where('property_id', $unit->property_id)->first();

        return [
            'property_id'      => $unit->property_id,
            'has_discount'     => $diskon > 0,
            'price_display'    => $final,
            'price_original'   => $original,
            'discount_display' => $diskon > 0 ? $this->formatPrice($diskon) : null,
            'title'          => $trans?->title ?? $trans?->property_name ?? '-',
            'description'    => $trans?->description ?? '',
            'location'       => $location,
            'beds'           => $unit->bedrooms      ?? 0,
            'baths'          => $unit->bathroom      ?? 0,
            'land_area'      => $unit->land_area     ?? 0,
            'building_area'  => $unit->building_area ?? 0,
            'carports'       => $unit->carports      ?? 0,
            'electricity'    => $unit->electricity   ?? 0,
            'lat'            => $lat,
            'lng'            => $lng,
            'badges'         => $badges,
            'tags'           => $allTags,
            'images'         => $images,
            'specs'          => $specs,
            'facilities'     => $facilities,
            'nearby'         => $nearby,
            'extra_features' => $extraFeatures,
            'media'          => $media ? [
                'video_path'  => $media->filename,
                'url_360'     => $media->url_360,
                'url_youtube' => $media->url_youtube,
            ] : null,
        ];
    }
}
