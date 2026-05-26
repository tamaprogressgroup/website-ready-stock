<?php

namespace App\Http\ViewComposers;

use App\Models\Kota;
use App\Models\LocationArea;
use App\Models\PropertyCondition;
use App\Models\PropertyType;
use App\Models\Provinsi;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NavbarComposer
{
    public function compose(View $view): void
    {
        // Conditions with slug for SEO URL
        $navConditions = PropertyCondition::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->where('is_active', 1)
            ->get()
            ->map(fn($c) => [
                'id'   => $c->property_condition_id,
                'name' => $c->translations->first()?->condition_name ?? '-',
                'slug' => Str::slug($c->translations->first()?->condition_name ?? ''),
            ])
            ->values()
            ->toArray();

        // Property types with slug
        $navPropertyTypes = PropertyType::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->where('is_active', 1)
            ->get()
            ->map(fn($pt) => [
                'id'   => $pt->property_type_id,
                'name' => $pt->translations->first()?->type_name ?? '-',
                'slug' => Str::slug($pt->translations->first()?->type_name ?? ''),
            ])
            ->values()
            ->toArray();

        // Provinces that have at least one LocationArea
        $provinceIds = LocationArea::distinct()->pluck('provinsi_id');
        $navProvinces = Provinsi::whereIn('provinsi_id', $provinceIds)
            ->orderBy('provinsi_name')
            ->get()
            ->map(fn($p) => ['id' => $p->provinsi_id, 'name' => $p->provinsi_name])
            ->values()
            ->toArray();

        // Location areas grouped by provinsi_id
        $navAreasByProvince = LocationArea::orderBy('location_name')
            ->get()
            ->groupBy('provinsi_id')
            ->map(fn($group) => $group->map(fn($a) => [
                'id'   => $a->location_id,
                'name' => $a->location_name,
                'slug' => Str::slug($a->location_name),
            ])->values())
            ->toArray();

        // ALL kota per province with slug (not filtered by active properties)
        $navKotaByProvince = Kota::orderBy('nama_kota')
            ->get()
            ->groupBy('provinsi_id')
            ->map(fn($group) => $group->map(fn($k) => [
                'id'   => $k->kota_id,
                'name' => $k->nama_kota,
                'slug' => Str::slug($k->nama_kota),
            ])->values())
            ->toArray();

        $view->with(compact(
            'navConditions',
            'navPropertyTypes',
            'navProvinces',
            'navAreasByProvince',
            'navKotaByProvince',
        ));
    }
}
