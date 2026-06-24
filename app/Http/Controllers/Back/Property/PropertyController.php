<?php

namespace App\Http\Controllers\Back\Property;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\Kota;
use App\Models\PropertyCondition;
use App\Models\PropertyExtraFeature;
use App\Models\PropertyExtraFeatureTrans;
use App\Models\PropertyFacility;
use App\Models\PropertyFacilityTrans;
use App\Models\PropertyNearbyLocation;
use App\Models\PropertyNearbyLocationTrans;
use App\Models\PropertySpec;
use App\Models\PropertySpecTrans;
use App\Models\PropertyType;
use App\Models\PropertyUnit;
use App\Models\PropertyUnitInterior;
use App\Models\Provinsi;
use App\Models\PropertyUnitInteriorTrans;
use App\Models\PropertyUnitTrans;
use App\Models\Tag;
use App\Models\Township;
use App\Cache\CacheKey;
use App\Cache\CacheWarmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    private const STATUS_MAP = ['draft' => 0, 'tayang' => 1, 'tunda' => 2, 'terjual' => 3];
    private const STATUS_LABELS = [0 => 'Draft', 1 => 'Tayang', 2 => 'Tunda', 3 => 'Terjual'];

    public function index(Request $request)
    {
        $tab      = $request->get('tab', 'draft');
        $statusId = self::STATUS_MAP[$tab] ?? 0;
        $userId   = Auth::guard('admin')->id();

        // Base query shared for both counts and items (all filters except status)
        $base = PropertyUnit::where('created_user_id', $userId);

        if ($request->filled('search')) {
            $s = $request->search;
            $base->whereHas('translations', fn($q) =>
                $q->where('locale', 'id')->where('title', 'like', "%$s%")
            );
        }
        if ($request->filled('property_type_id')) {
            $base->where('property_type_id', $request->property_type_id);
        }
        if ($request->filled('cluster_id')) {
            $base->where('cluster_id', $request->cluster_id);
        }
        if ($request->filled('township_id')) {
            $base->where('township_id', $request->township_id);
        }
        if ($request->filled('condition_id')) {
            $base->where('condition_id', $request->condition_id);
        }
        if ($request->filled('bedrooms')) {
            $br = (int) $request->bedrooms;
            $br >= 4 ? $base->where('bedrooms', '>=', 4) : $base->where('bedrooms', $br);
        }
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'lt500':   $base->where('price', '<', 500000000); break;
                case '500to1b': $base->whereBetween('price', [500000000, 999999999]); break;
                case '1bto2b':  $base->whereBetween('price', [1000000000, 1999999999]); break;
                case '2bto5b':  $base->whereBetween('price', [2000000000, 4999999999]); break;
                case 'gt5b':    $base->where('price', '>=', 5000000000); break;
            }
        }

        // Counts per tab — reflect current active filters
        $counts = [];
        foreach (self::STATUS_MAP as $key => $sid) {
            $counts[$key] = (clone $base)->where('status_id', $sid)->count();
        }

        // Items for active tab
        $items = (clone $base)->with([
            'translations'              => fn($q) => $q->where('locale', 'id'),
            'interiors'                 => fn($q) => $q->where('order', 1)->where('is_active', 1),
            'propertyType.translations' => fn($q) => $q->where('locale', 'id'),
            'cluster',
            'township',
        ])
        ->where('status_id', $statusId)
        ->orderByDesc('created_datetime')
        ->paginate(10)
        ->withQueryString();

        $propertyTypes = PropertyType::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->get();
        $clusters = Cluster::orderBy('cluster_name')->get();

        $ownedTownshipIds = PropertyUnit::where('created_user_id', $userId)
            ->whereNotNull('township_id')->distinct()->pluck('township_id');
        $townships = Township::whereIn('township_id', $ownedTownshipIds)
            ->orderBy('township_name')->get();

        $conditions = PropertyCondition::with(['translations' => fn($q) => $q->where('locale', 'id')])->get();

        return view('back.propertySaya.list-property', compact('items', 'counts', 'tab', 'propertyTypes', 'clusters', 'townships', 'conditions'));
    }

    public function create()
    {
        $this->ensureDefaultLabelTags();

        $property_type = PropertyType::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->where('is_active', 1)->get()->toArray();
        $propertyConditions = PropertyCondition::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->where('is_active', 1)->get()->toArray();
        $townships  = Township::orderBy('township_name')->get();
        $clusters   = Cluster::where('is_active', 1)->orderBy('cluster_name')->get();
        $labelTags  = Tag::where('is_label', 1)->orderBy('tag_id')->get();
        $provinces  = Provinsi::orderBy('provinsi_name')->get();

        $userId = Auth::guard('admin')->id();
        $importableProperties = PropertyUnit::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->where('created_user_id', $userId)
            ->orderByDesc('created_datetime')
            ->get()
            ->map(fn($u) => [
                'id'    => $u->property_id,
                'title' => $u->translations->first()?->title ?? 'Properti #' . $u->property_id,
            ]);

        return view('back.propertySaya.create-property', compact(
            'property_type', 'propertyConditions', 'townships', 'clusters',
            'labelTags', 'provinces', 'importableProperties'
        ));
    }

    public function store(Request $request)
    {
        $labelTagIds = Tag::where('is_label', 1)->pluck('tag_id')->toArray();
        $labelNames  = Tag::where('is_label', 1)->pluck('name')->toArray();

        $request->validate([
            'tipe_properti'           => 'required',
            'property_condition'      => 'required',
            'township_id'             => 'nullable|integer',
            'cluster_id'              => 'nullable|integer',
            'provinsi_id'             => 'nullable|integer',
            'kota_id'                 => 'nullable|integer',
            'latitude'                => 'nullable|numeric',
            'longtidure'              => 'nullable|numeric',
            'title'                   => 'required|string|max:255',
            'description'             => 'required|string',
            'label_tag_ids'           => 'nullable|array|max:2',
            'label_tag_ids.*'         => ['integer', Rule::in($labelTagIds)],
            'custom_tags'             => 'nullable|array',
            'custom_tags.*'           => [
                'nullable', 'string', 'max:100',
                function ($attr, $val, $fail) use ($labelNames) {
                    if (in_array(strtolower($val), array_map('strtolower', $labelNames))) {
                        $fail("Tag \"{$val}\" adalah nama label iklan default.");
                    }
                },
            ],
            'bedrooms'                => 'nullable|integer|min:0',
            'bathrooms'               => 'nullable|integer|min:0',
            'land_area'               => 'nullable|numeric|min:0',
            'building_area'           => 'nullable|numeric|min:0',
            'spec_keys'               => 'nullable|array',
            'spec_values'             => 'nullable|array',
            'facility_names'             => 'nullable|array',
            'facility_icons'             => 'nullable|array',
            'facility_images'            => 'nullable|array',
            'facility_images.*'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240|dimensions:width=4096,height=2503',
            'facility_existing_imgs'     => 'nullable|array',
            'facility_icon_images'       => 'nullable|array',
            'facility_icon_images.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nearby_names'               => 'nullable|array',
            'nearby_names.*'             => 'nullable|string|max:255',
            'extra_icons'                => 'nullable|array',
            'extra_names'             => 'nullable|array',
            'extra_names.*'           => 'nullable|string|max:255',
            'no_hp'                   => 'nullable|string|max:20',
            'price'                   => 'required|string',
            'discount'                => 'nullable|string',
            'main_thumbnail'          => 'required|image|mimes:jpeg,png,jpg,webp|max:10240|dimensions:width=4096,height=2298',
            'mini_thumbnail'          => 'required|image|mimes:jpeg,png,jpg,webp|max:10240|dimensions:width=4096,height=2298',
            'interior_images'         => 'nullable|array',
            'interior_images.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'interior_labels'         => 'nullable|array',
            'url_360'                 => 'nullable|url|max:500',
            'url_youtube'             => 'nullable|url|max:500',
            'video_file'              => 'nullable|file|mimes:mp4,mov,avi|max:51200',
            'extra_icon_images'       => 'nullable|array',
            'extra_icon_images.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_title'              => 'nullable|string|max:255',
            'meta_keyword'            => 'nullable|string|max:500',
            'meta_descriotion'        => 'nullable|string|max:500',
        ]);

        $cleanPrice    = (int) str_replace('.', '', $request->price);
        $cleanDiscount = $request->filled('discount') ? (int) str_replace('.', '', $request->discount) : 0;

        $mainPath = $request->file('main_thumbnail')->store('properties/main', 'public');
        $miniPath = $request->file('mini_thumbnail')->store('properties/mini', 'public');

        $galleryData = [];
        if ($request->hasFile('interior_images')) {
            foreach ($request->file('interior_images') as $idx => $img) {
                if ($img && $img->isValid()) {
                    $galleryData[] = [
                        'path'  => $img->store('properties/interiors', 'public'),
                        'label' => $request->interior_labels[$idx] ?? 'Lainnya',
                    ];
                }
            }
        }

        $specData = [];
        foreach ($request->spec_keys ?? [] as $i => $key) {
            $val = $request->spec_values[$i] ?? '';
            if (trim($key) && trim($val)) {
                $specData[] = ['key' => $key, 'value' => $val];
            }
        }

        $userId = Auth::guard('admin')->id();

        DB::beginTransaction();
        try {
            $unit = PropertyUnit::create([
                'property_type_id' => $request->tipe_properti,
                'condition_id'     => $request->property_condition,
                'township_id'      => $request->township_id ?: null,
                'cluster_id'       => $request->cluster_id ?: null,
                'provinsi_id'      => $request->provinsi_id ?: null,
                'kota_id'          => $request->kota_id ?: null,
                'latitude'         => $request->latitude ?: null,
                'longtidure'       => $request->longtidure ?: null,
                'bedrooms'         => $request->bedrooms ?? 0,
                'bathroom'         => $request->bathrooms ?? 0,
                'land_area'        => $request->land_area ?? 0,
                'building_area'    => $request->building_area ?? 0,
                'price'            => $cleanPrice,
                'diskon'           => $cleanDiscount,
                'no_hp'            => $request->no_hp ?: null,
                'status_id'        => 0,
                'is_active'        => 1,
                'created_user_id'  => $userId,
                'created_datetime' => now(),
                'slug'             => $this->generateUniqueSlug($request->title),
            ]);

            $transData = [
                'property_name'  => $request->title,
                'title'          => $request->title,
                'description'    => $request->description,
                'meta_title'     => $request->meta_title     ?: null,
                'meta_keyword'   => $request->meta_keyword   ?: null,
                'meta_descriotion' => $request->meta_descriotion ?: null,
            ];
            PropertyUnitTrans::create(array_merge($transData, ['property_id' => $unit->property_id, 'locale' => 'id']));
            PropertyUnitTrans::create(array_merge($transData, ['property_id' => $unit->property_id, 'locale' => 'en']));

            $this->createInterior($unit->property_id, $mainPath, 1, 'Main Thumbnail');
            $this->createInterior($unit->property_id, $miniPath, 2, 'Mini Thumbnail');
            foreach ($galleryData as $idx => $g) {
                $this->createInterior($unit->property_id, $g['path'], $idx + 3, $g['label']);
            }

            foreach ($specData as $spec) {
                $s = PropertySpec::create(['property_id' => $unit->property_id]);
                $specTrans = ['property_spec_id' => $s->property_spec_id, 'spec_key' => $spec['key'], 'spec_value' => $spec['value']];
                PropertySpecTrans::create(array_merge($specTrans, ['locale' => 'id']));
                PropertySpecTrans::create(array_merge($specTrans, ['locale' => 'en']));
            }

            $facilityFiles     = $request->file('facility_images', []);
            $facilityIconFiles = $request->file('facility_icon_images', []);
            foreach ($request->facility_names ?? [] as $i => $name) {
                $name = trim($name);
                if (!$name) continue;
                $iconUrl     = trim($request->facility_icons[$i] ?? '');
                $imagePath   = null;
                $iconImgPath = null;
                $imgFile     = $facilityFiles[$i] ?? null;
                if ($imgFile && $imgFile->isValid()) {
                    $imagePath = $imgFile->store('properties/facilities', 'public');
                } elseif ($existing = trim($request->facility_existing_imgs[$i] ?? '')) {
                    $imagePath = $this->copyStorageFile($existing, 'properties/facilities');
                }
                $iconImgFile = $facilityIconFiles[$i] ?? null;
                if ($iconImgFile && $iconImgFile->isValid()) {
                    $iconImgPath = $iconImgFile->store('properties/icons', 'public');
                }
                $f = PropertyFacility::create([
                    'property_id' => $unit->property_id, 'icon_url' => $iconUrl ?: null,
                    'icon_image' => $iconImgPath,
                    'image' => $imagePath, 'created_user_id' => $userId, 'created_datetime' => now(),
                ]);
                PropertyFacilityTrans::create(['facility_id' => $f->facility_id, 'locale' => 'id', 'name' => $name]);
                PropertyFacilityTrans::create(['facility_id' => $f->facility_id, 'locale' => 'en', 'name' => $name]);
            }

            foreach ($request->nearby_names ?? [] as $nearbyName) {
                $nearbyName = trim($nearbyName);
                if (!$nearbyName) continue;
                $n = PropertyNearbyLocation::create(['property_id' => $unit->property_id]);
                PropertyNearbyLocationTrans::create(['nearby_location_id' => $n->nearby_location_id, 'locale' => 'id', 'name' => $nearbyName]);
                PropertyNearbyLocationTrans::create(['nearby_location_id' => $n->nearby_location_id, 'locale' => 'en', 'name' => $nearbyName]);
            }

            foreach ($request->extra_names ?? [] as $i => $extraName) {
                $extraName = trim($extraName);
                if (!$extraName) continue;
                $iconUrl = trim($request->extra_icons[$i] ?? '');
                $iconImagePath = null;
                if ($request->hasFile("extra_icon_images.$i")) {
                    $iconImagePath = $request->file("extra_icon_images.$i")->store('properties/icons', 'public');
                }
                $e = PropertyExtraFeature::create([
                    'property_id' => $unit->property_id, 'icon_url' => $iconUrl ?: null,
                    'icon_image' => $iconImagePath,
                    'creaeted_user_id' => $userId, 'created_datetime' => now(),
                ]);
                PropertyExtraFeatureTrans::create(['property_exstra_fitur_id' => $e->property_exstra_fitur_id, 'locale' => 'id', 'name' => $extraName]);
                PropertyExtraFeatureTrans::create(['property_exstra_fitur_id' => $e->property_exstra_fitur_id, 'locale' => 'en', 'name' => $extraName]);
            }

            foreach ($request->label_tag_ids ?? [] as $tagId) {
                DB::table('m_property_tag_pivot')->insertOrIgnore(['property_id' => $unit->property_id, 'tag_id' => (int) $tagId]);
            }
            foreach (array_filter(array_map('trim', $request->custom_tags ?? [])) as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName], ['color_code' => '#888888', 'is_label' => 0, 'created_at' => now()]);
                DB::table('m_property_tag_pivot')->insertOrIgnore(['property_id' => $unit->property_id, 'tag_id' => $tag->tag_id]);
            }

            // PropertyMedia (video)
            $videoPath = null;
            if ($request->hasFile('video_file')) {
                $videoPath = $request->file('video_file')->store('properties/videos', 'public');
            }
            if ($videoPath || $request->filled('url_360') || $request->filled('url_youtube')) {
                \App\Models\PropertyMedia::create([
                    'property_id'      => $unit->property_id,
                    'filename'         => $videoPath,
                    'url_360'          => $request->url_360,
                    'url_youtube'      => $request->url_youtube,
                    'created_user_id'  => $userId,
                    'created_datetime' => now(),
                    'updated_user_id'  => $userId,
                    'updated_datetime' => now(),
                ]);
            }

            DB::commit();
            $this->flushCaches($unit->property_id);
            return redirect()->route('customer.property', ['tab' => 'draft'])->with('success', 'Properti berhasil disimpan sebagai Draft.');

        } catch (\Exception $e) {
            DB::rollBack();
            Storage::disk('public')->delete($mainPath);
            Storage::disk('public')->delete($miniPath);
            foreach ($galleryData as $g) Storage::disk('public')->delete($g['path']);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan properti: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $userId = Auth::guard('admin')->id();
        $item = PropertyUnit::with([
            'translations',
            'interiors.translations',
            'specs.translations',
            'facilities.translations',
            'nearbyLocations.translations',
            'extraFeatures.translations',
            'tags',
        ])->where('created_user_id', $userId)->findOrFail($id);

        if ($item->status_id === 3) {
            return redirect()->route('customer.property')->with('error', 'Properti yang sudah terjual tidak dapat diedit.');
        }

        $this->ensureDefaultLabelTags();

        $propertyTypes = PropertyType::with(['translations' => fn($q) => $q->where('locale', 'id')])->where('is_active', 1)->get();
        $propertyConditions = PropertyCondition::with(['translations' => fn($q) => $q->where('locale', 'id')])->where('is_active', 1)->get();
        $townships = Township::orderBy('township_name')->get();
        $clusters  = Cluster::where('is_active', 1)->orderBy('cluster_name')->get();
        $labelTags = Tag::where('is_label', 1)->orderBy('tag_id')->get();
        $provinces = Provinsi::orderBy('provinsi_name')->get();
        $kotas     = $item->provinsi_id
            ? Kota::where('provinsi_id', $item->provinsi_id)->orderBy('nama_kota')->get()
            : collect();

        $selectedLabelIds = old('label_tag_ids', $item->tags->where('is_label', 1)->pluck('tag_id')->toArray());
        $customTags = old('custom_tags', $item->tags->where('is_label', 0)->pluck('name')->toArray());

        $importableProperties = PropertyUnit::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->where('created_user_id', $userId)
            ->where('property_id', '!=', $id)
            ->orderByDesc('created_datetime')
            ->get()
            ->map(fn($u) => [
                'id'    => $u->property_id,
                'title' => $u->translations->first()?->title ?? 'Properti #' . $u->property_id,
            ]);

        return view('back.propertySaya.edit-property', compact(
            'item', 'propertyTypes', 'propertyConditions', 'townships', 'clusters',
            'labelTags', 'selectedLabelIds', 'customTags', 'provinces', 'kotas',
            'importableProperties'
        ));
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::guard('admin')->id();
        $unit   = PropertyUnit::where('created_user_id', $userId)->findOrFail($id);

        if ($unit->status_id === 3) {
            return redirect()->route('customer.property')->with('error', 'Properti yang sudah terjual tidak dapat diedit.');
        }

        $labelTagIds = Tag::where('is_label', 1)->pluck('tag_id')->toArray();
        $labelNames  = Tag::where('is_label', 1)->pluck('name')->toArray();

        $request->validate([
            'tipe_properti'           => 'required',
            'property_condition'      => 'required',
            'township_id'             => 'nullable|integer',
            'cluster_id'              => 'nullable|integer',
            'provinsi_id'             => 'nullable|integer',
            'kota_id'                 => 'nullable|integer',
            'latitude'                => 'nullable|numeric',
            'longtidure'              => 'nullable|numeric',
            'title'                   => 'required|string|max:255',
            'description'             => 'required|string',
            'label_tag_ids'           => 'nullable|array|max:2',
            'label_tag_ids.*'         => ['integer', Rule::in($labelTagIds)],
            'custom_tags'             => 'nullable|array',
            'custom_tags.*'           => [
                'nullable', 'string', 'max:100',
                function ($attr, $val, $fail) use ($labelNames) {
                    if (in_array(strtolower($val), array_map('strtolower', $labelNames))) {
                        $fail("Tag \"{$val}\" adalah nama label iklan default.");
                    }
                },
            ],
            'bedrooms'                => 'nullable|integer|min:0',
            'bathrooms'               => 'nullable|integer|min:0',
            'land_area'               => 'nullable|numeric|min:0',
            'building_area'           => 'nullable|numeric|min:0',
            'spec_keys'               => 'nullable|array',
            'spec_values'             => 'nullable|array',
            'facility_names'                  => 'nullable|array',
            'facility_icons'                  => 'nullable|array',
            'facility_images'                 => 'nullable|array',
            'facility_images.*'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'facility_existing_imgs'          => 'nullable|array',
            'facility_icon_images'            => 'nullable|array',
            'facility_icon_images.*'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'facility_icon_image_existings'   => 'nullable|array',
            'delete_facility_icon_images'     => 'nullable|array',
            'nearby_names'                    => 'nullable|array',
            'nearby_names.*'                  => 'nullable|string|max:255',
            'extra_icons'                     => 'nullable|array',
            'extra_names'             => 'nullable|array',
            'extra_names.*'           => 'nullable|string|max:255',
            'no_hp'                   => 'nullable|string|max:20',
            'price'                   => 'required|string',
            'discount'                => 'nullable|string',
            'main_thumbnail'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240|dimensions:width=4096,height=2298',
            'mini_thumbnail'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240|dimensions:width=4096,height=2298',
            'interior_images'         => 'nullable|array',
            'interior_images.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'interior_labels'         => 'nullable|array',
            'delete_interior_ids'          => 'nullable|array',
            'url_360'                      => 'nullable|url|max:500',
            'url_youtube'                  => 'nullable|url|max:500',
            'video_file'                   => 'nullable|file|mimes:mp4,mov,avi|max:51200',
            'delete_video'                 => 'nullable|boolean',
            'extra_icon_images'            => 'nullable|array',
            'extra_icon_images.*'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'extra_icon_image_existings'   => 'nullable|array',
            'delete_icon_images'           => 'nullable|array',
            'meta_title'              => 'nullable|string|max:255',
            'meta_keyword'            => 'nullable|string|max:500',
            'meta_descriotion'        => 'nullable|string|max:500',
        ]);

        $cleanPrice    = (int) str_replace('.', '', $request->price);
        $cleanDiscount = $request->filled('discount') ? (int) str_replace('.', '', $request->discount) : 0;

        DB::beginTransaction();
        try {
            $updateData = [
                'property_type_id' => $request->tipe_properti,
                'condition_id'     => $request->property_condition,
                'township_id'      => $request->township_id ?: null,
                'cluster_id'       => $request->cluster_id ?: null,
                'provinsi_id'      => $request->provinsi_id ?: null,
                'kota_id'          => $request->kota_id ?: null,
                'latitude'         => $request->latitude ?: null,
                'longtidure'       => $request->longtidure ?: null,
                'bedrooms'         => $request->bedrooms ?? 0,
                'bathroom'         => $request->bathrooms ?? 0,
                'land_area'        => $request->land_area ?? 0,
                'building_area'    => $request->building_area ?? 0,
                'no_hp'            => $request->no_hp ?: null,
                'price'            => $cleanPrice,
                'diskon'           => $cleanDiscount,
                'updated_user_id'  => $userId,
                'updated_datetime' => now(),
            ];
            if (empty($unit->slug)) {
                $updateData['slug'] = $this->generateUniqueSlug($request->title, $unit->property_id);
            }
            $unit->update($updateData);

            $transData = [
                'property_name'    => $request->title,
                'title'            => $request->title,
                'description'      => $request->description,
                'meta_title'       => $request->meta_title     ?: null,
                'meta_keyword'     => $request->meta_keyword   ?: null,
                'meta_descriotion' => $request->meta_descriotion ?: null,
            ];
            $unit->translations()->updateOrCreate(['locale' => 'id'], $transData);
            $unit->translations()->updateOrCreate(['locale' => 'en'], $transData);

            if ($request->hasFile('main_thumbnail')) {
                $main = $unit->interiors()->where('order', 1)->first();
                if ($main) {
                    Storage::disk('public')->delete($main->image);
                    $main->update(['image' => $request->file('main_thumbnail')->store('properties/main', 'public')]);
                    foreach (['id','en'] as $loc) { $main->translations()->updateOrCreate(['locale' => $loc], ['interior_name' => 'Main Thumbnail', 'created_datetime' => now()]); }
                } else {
                    $this->createInterior($unit->property_id, $request->file('main_thumbnail')->store('properties/main', 'public'), 1, 'Main Thumbnail');
                }
            }
            if ($request->hasFile('mini_thumbnail')) {
                $mini = $unit->interiors()->where('order', 2)->first();
                if ($mini) {
                    Storage::disk('public')->delete($mini->image);
                    $mini->update(['image' => $request->file('mini_thumbnail')->store('properties/mini', 'public')]);
                    foreach (['id','en'] as $loc) { $mini->translations()->updateOrCreate(['locale' => $loc], ['interior_name' => 'Mini Thumbnail', 'created_datetime' => now()]); }
                } else {
                    $this->createInterior($unit->property_id, $request->file('mini_thumbnail')->store('properties/mini', 'public'), 2, 'Mini Thumbnail');
                }
            }

            foreach ($request->delete_interior_ids ?? [] as $intId) {
                $interior = PropertyUnitInterior::where('property_interior_id', $intId)->where('property_id', $unit->property_id)->first();
                if ($interior) { Storage::disk('public')->delete($interior->image); $interior->translations()->delete(); $interior->delete(); }
            }

            $maxOrder = $unit->interiors()->where('order', '>=', 3)->max('order') ?? 2;
            if ($request->hasFile('interior_images')) {
                foreach ($request->file('interior_images') as $idx => $img) {
                    if ($img && $img->isValid()) {
                        $maxOrder++;
                        $this->createInterior($unit->property_id, $img->store('properties/interiors', 'public'), $maxOrder, $request->interior_labels[$idx] ?? 'Lainnya');
                    }
                }
            }

            foreach ($unit->specs as $spec) { $spec->translations()->delete(); $spec->delete(); }
            foreach ($request->spec_keys ?? [] as $i => $key) {
                $val = $request->spec_values[$i] ?? '';
                if (trim($key) && trim($val)) {
                    $s = PropertySpec::create(['property_id' => $unit->property_id]);
                    $st = ['property_spec_id' => $s->property_spec_id, 'spec_key' => $key, 'spec_value' => $val];
                    PropertySpecTrans::create(array_merge($st, ['locale' => 'id']));
                    PropertySpecTrans::create(array_merge($st, ['locale' => 'en']));
                }
            }

            $keepFacIconImages = array_filter($request->facility_icon_image_existings ?? []);
            foreach ($unit->facilities as $fac) {
                if ($fac->image) Storage::disk('public')->delete($fac->image);
                if ($fac->icon_image && !in_array($fac->icon_image, $keepFacIconImages)) {
                    Storage::disk('public')->delete($fac->icon_image);
                }
                $fac->translations()->delete(); $fac->delete();
            }
            $facilityFiles     = $request->file('facility_images', []);
            $facilityIconFiles = $request->file('facility_icon_images', []);
            foreach ($request->facility_names ?? [] as $i => $name) {
                $name = trim($name);
                if (!$name) continue;
                $iconUrl     = trim($request->facility_icons[$i] ?? '');
                $imagePath   = null;
                $iconImgPath = null;
                $imgFile     = $facilityFiles[$i] ?? null;
                if ($imgFile && $imgFile->isValid()) {
                    $imagePath = $imgFile->store('properties/facilities', 'public');
                } elseif ($existing = trim($request->facility_existing_imgs[$i] ?? '')) {
                    $imagePath = $this->copyStorageFile($existing, 'properties/facilities');
                }
                $existingIconImg = $request->facility_icon_image_existings[$i] ?? null;
                $iconImgPath = $existingIconImg ?: null;
                $iconImgFile = $facilityIconFiles[$i] ?? null;
                if ($iconImgFile && $iconImgFile->isValid()) {
                    if ($existingIconImg) Storage::disk('public')->delete($existingIconImg);
                    $iconImgPath = $iconImgFile->store('properties/icons', 'public');
                } elseif ($request->input("delete_facility_icon_images.$i") == '1') {
                    $iconImgPath = null;
                }
                $f = PropertyFacility::create([
                    'property_id' => $unit->property_id, 'icon_url' => $iconUrl ?: null,
                    'icon_image' => $iconImgPath,
                    'image' => $imagePath, 'created_user_id' => $userId, 'created_datetime' => now(),
                ]);
                PropertyFacilityTrans::create(['facility_id' => $f->facility_id, 'locale' => 'id', 'name' => $name]);
                PropertyFacilityTrans::create(['facility_id' => $f->facility_id, 'locale' => 'en', 'name' => $name]);
            }

            foreach ($unit->nearbyLocations as $nb) { $nb->translations()->delete(); $nb->delete(); }
            foreach ($request->nearby_names ?? [] as $nearbyName) {
                $nearbyName = trim($nearbyName);
                if (!$nearbyName) continue;
                $n = PropertyNearbyLocation::create(['property_id' => $unit->property_id]);
                PropertyNearbyLocationTrans::create(['nearby_location_id' => $n->nearby_location_id, 'locale' => 'id', 'name' => $nearbyName]);
                PropertyNearbyLocationTrans::create(['nearby_location_id' => $n->nearby_location_id, 'locale' => 'en', 'name' => $nearbyName]);
            }

            $keepIconImages = array_filter($request->extra_icon_image_existings ?? []);
            foreach ($unit->extraFeatures as $ef) {
                if ($ef->icon_image && !in_array($ef->icon_image, $keepIconImages)) {
                    Storage::disk('public')->delete($ef->icon_image);
                }
                $ef->translations()->delete();
                $ef->delete();
            }
            foreach ($request->extra_names ?? [] as $i => $extraName) {
                $extraName = trim($extraName);
                if (!$extraName) continue;
                $iconUrl = trim($request->extra_icons[$i] ?? '');
                $existingIconImg = $request->extra_icon_image_existings[$i] ?? null;
                $iconImagePath = $existingIconImg ?: null;
                if ($request->hasFile("extra_icon_images.$i")) {
                    if ($existingIconImg) Storage::disk('public')->delete($existingIconImg);
                    $iconImagePath = $request->file("extra_icon_images.$i")->store('properties/icons', 'public');
                } elseif ($request->input("delete_icon_images.$i") == '1') {
                    $iconImagePath = null;
                }
                $e = PropertyExtraFeature::create([
                    'property_id' => $unit->property_id, 'icon_url' => $iconUrl ?: null,
                    'icon_image' => $iconImagePath,
                    'creaeted_user_id' => $userId, 'created_datetime' => now(),
                ]);
                PropertyExtraFeatureTrans::create(['property_exstra_fitur_id' => $e->property_exstra_fitur_id, 'locale' => 'id', 'name' => $extraName]);
                PropertyExtraFeatureTrans::create(['property_exstra_fitur_id' => $e->property_exstra_fitur_id, 'locale' => 'en', 'name' => $extraName]);
            }

            DB::table('m_property_tag_pivot')->where('property_id', $unit->property_id)->delete();
            foreach ($request->label_tag_ids ?? [] as $tagId) {
                DB::table('m_property_tag_pivot')->insertOrIgnore(['property_id' => $unit->property_id, 'tag_id' => (int) $tagId]);
            }
            foreach (array_filter(array_map('trim', $request->custom_tags ?? [])) as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName], ['color_code' => '#888888', 'is_label' => 0, 'created_at' => now()]);
                DB::table('m_property_tag_pivot')->insertOrIgnore(['property_id' => $unit->property_id, 'tag_id' => $tag->tag_id]);
            }

            // PropertyMedia (video) — upsert existing record
            $mediaRecord = \App\Models\PropertyMedia::where('property_id', $unit->property_id)->first();
            $videoPath   = $mediaRecord?->filename;
            if ($request->boolean('delete_video') && $videoPath) {
                Storage::disk('public')->delete($videoPath);
                $videoPath = null;
            }
            if ($request->hasFile('video_file')) {
                if ($videoPath) Storage::disk('public')->delete($videoPath);
                $videoPath = $request->file('video_file')->store('properties/videos', 'public');
            }
            \App\Models\PropertyMedia::updateOrCreate(
                ['property_id' => $unit->property_id],
                [
                    'filename'         => $videoPath,
                    'url_360'          => $request->url_360,
                    'url_youtube'      => $request->url_youtube,
                    'updated_user_id'  => $userId,
                    'updated_datetime' => now(),
                ]
            );

            DB::commit();
            $this->flushCaches($unit->property_id);
            $tab = array_flip(self::STATUS_MAP)[$unit->status_id] ?? 'draft';
            return redirect()->route('customer.property', ['tab' => $tab])->with('success', 'Properti berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui properti: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $userId = Auth::guard('admin')->id();
        $unit   = PropertyUnit::with(['interiors.translations', 'specs.translations', 'facilities.translations', 'nearbyLocations.translations', 'extraFeatures.translations'])
            ->where('created_user_id', $userId)->findOrFail($id);

        $tab = array_flip(self::STATUS_MAP)[$unit->status_id] ?? 'draft';

        DB::beginTransaction();
        try {
            foreach ($unit->interiors as $interior) { Storage::disk('public')->delete($interior->image); $interior->translations()->delete(); $interior->delete(); }
            foreach ($unit->specs as $spec) { $spec->translations()->delete(); $spec->delete(); }
            foreach ($unit->facilities as $fac) { if ($fac->image) Storage::disk('public')->delete($fac->image); $fac->translations()->delete(); $fac->delete(); }
            foreach ($unit->nearbyLocations as $nb) { $nb->translations()->delete(); $nb->delete(); }
            foreach ($unit->extraFeatures as $ef) { $ef->translations()->delete(); $ef->delete(); }
            DB::table('m_property_tag_pivot')->where('property_id', $unit->property_id)->delete();
            $unit->translations()->delete();
            $unit->delete();
            DB::commit();
            $this->flushCaches();
            return redirect()->route('customer.property', ['tab' => $tab])->with('success', 'Properti berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus properti: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $userId   = Auth::guard('admin')->id();
        $unit     = PropertyUnit::where('created_user_id', $userId)->findOrFail($id);
        $statusId = (int) $request->status_id;

        if ($unit->status_id === 3) return redirect()->back()->with('error', 'Properti yang sudah terjual tidak dapat diubah statusnya.');
        if (!array_key_exists($statusId, self::STATUS_LABELS)) return redirect()->back()->with('error', 'Status tidak valid.');

        $unit->update(['status_id' => $statusId, 'updated_user_id' => $userId, 'updated_datetime' => now()]);
        $this->flushCaches($unit->property_id);

        $tab = array_flip(self::STATUS_MAP)[$statusId] ?? 'draft';
        return redirect()->route('customer.property', ['tab' => $tab])->with('success', 'Status properti berhasil diubah menjadi ' . self::STATUS_LABELS[$statusId] . '.');
    }

    public function importableList(Request $request)
    {
        $userId   = Auth::guard('admin')->id();
        $exceptId = (int) $request->except_id;
        $list = PropertyUnit::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->where('created_user_id', $userId)
            ->when($exceptId, fn($q) => $q->where('property_id', '!=', $exceptId))
            ->orderByDesc('created_datetime')
            ->get()
            ->map(fn($u) => [
                'id'    => $u->property_id,
                'title' => $u->translations->first()?->title ?? 'Properti #' . $u->property_id,
            ]);
        return response()->json(['data' => $list]);
    }

    public function importData($id)
    {
        $userId = Auth::guard('admin')->id();
        $unit = PropertyUnit::with([
            'specs.translations',
            'facilities.translations',
            'nearbyLocations.translations',
            'extraFeatures.translations',
        ])->where('created_user_id', $userId)->findOrFail($id);

        return response()->json([
            'specs' => $unit->specs->map(fn($s) => [
                'key'   => $s->translations->where('locale', 'id')->first()?->spec_key ?? '',
                'value' => $s->translations->where('locale', 'id')->first()?->spec_value ?? '',
            ]),
            'facilities' => $unit->facilities->map(fn($f) => [
                'name'       => $f->translations->where('locale', 'id')->first()?->name ?? '',
                'icon_url'   => $f->icon_url ?? '',
                'icon_image' => $f->icon_image ? Storage::disk('public')->url($f->icon_image) : null,
                'image_url'  => $f->image ? Storage::disk('public')->url($f->image) : null,
                'image_path' => $f->image ?? '',
            ]),
            'nearby' => $unit->nearbyLocations->map(fn($n) => [
                'name' => $n->translations->where('locale', 'id')->first()?->name ?? '',
            ]),
            'extras' => $unit->extraFeatures->map(fn($e) => [
                'name'       => $e->translations->where('locale', 'id')->first()?->name ?? '',
                'icon_url'   => $e->icon_url ?? '',
                'icon_image' => $e->icon_image ? Storage::disk('public')->url($e->icon_image) : null,
            ]),
        ]);
    }

    public function backfillSlugs()
    {
        $units = PropertyUnit::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->where(fn($q) => $q->whereNull('slug')->orWhere('slug', ''))
            ->get();

        $count = 0;
        foreach ($units as $unit) {
            $title = $unit->translations->first()?->property_name
                  ?? $unit->translations->first()?->title
                  ?? 'properti';
            $unit->update(['slug' => $this->generateUniqueSlug($title, $unit->property_id)]);
            $count++;
        }

        return response()->json(['message' => "Berhasil mengisi slug untuk {$count} properti."]);
    }

    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $base = Str::slug($title) ?: 'properti';
        $slug  = $base;
        $n     = 1;

        while (true) {
            $query = PropertyUnit::where('slug', $slug);
            if ($excludeId) {
                $query->where('property_id', '!=', $excludeId);
            }
            if (!$query->exists()) break;
            $n++;
            $slug = $base . '-' . $n;
        }

        return $slug;
    }

    private function createInterior(int $propertyId, string $path, int $order, string $label): void
    {
        $interior = PropertyUnitInterior::create([
            'property_id' => $propertyId, 'image' => $path,
            'order' => $order, 'is_active' => 1, 'created_datetime' => now(),
        ]);
        $it = ['property_interior_id' => $interior->property_interior_id, 'interior_name' => $label, 'created_datetime' => now()];
        PropertyUnitInteriorTrans::create(array_merge($it, ['locale' => 'id']));
        PropertyUnitInteriorTrans::create(array_merge($it, ['locale' => 'en']));
    }

    private function copyStorageFile(string $sourcePath, string $targetDir): ?string
    {
        if (!$sourcePath || !Storage::disk('public')->exists($sourcePath)) return null;
        $ext  = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $dest = $targetDir . '/copy-' . time() . '-' . uniqid() . '.' . $ext;
        Storage::disk('public')->copy($sourcePath, $dest);
        return $dest;
    }

    private function flushCaches(?int $propertyId = null): void
    {
        CacheWarmer::reload(CacheKey::PROPERTY_UNITS);
        CacheWarmer::reload(CacheKey::PROPERTY_UNIT, $propertyId);
        CacheWarmer::reload(CacheKey::RECOMMENDATIONS);
        CacheWarmer::reload(CacheKey::NEW_PROPERTIES);
        CacheWarmer::reload(CacheKey::PROPERTY_DETAIL);
        CacheWarmer::reload(CacheKey::RELATED_PROPERTIES);
        CacheWarmer::reload(CacheKey::TOWNSHIPS_PROJECT);
        CacheWarmer::reload(CacheKey::ALL_PRODUCTS);
        CacheWarmer::reload(CacheKey::PROPERTY_COUNT);
    }

    private function ensureDefaultLabelTags(): void
    {
        try {
            DB::statement('ALTER TABLE m_tags ADD COLUMN is_label TINYINT(1) NOT NULL DEFAULT 0');
        } catch (\Exception $e) {
            // Column already exists
        }
        $defaults = [
            ['name' => 'Diskon',        'color_code' => '#ee5c5b'],
            ['name' => 'Features',      'color_code' => '#f97316'],
            ['name' => 'Properti Baru', 'color_code' => '#1e3a8a'],
        ];
        foreach ($defaults as $d) {
            $tag = Tag::firstOrCreate(['name' => $d['name']], ['color_code' => $d['color_code'], 'is_label' => 1, 'created_at' => now()]);
            if (!$tag->is_label) $tag->update(['is_label' => 1, 'color_code' => $d['color_code']]);
        }
    }
}
