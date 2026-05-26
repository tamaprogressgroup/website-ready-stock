<?php

namespace App\Http\Controllers\Back\PasangProperty;

use App\Http\Controllers\Controller;
use App\Models\PropertyCondition;
use App\Models\PropertyConditionTrans;
use App\Models\PropertyFacility;
use App\Models\PropertyFacilityTrans;
use App\Models\PropertySpec;
use App\Models\PropertySpecTrans;
use App\Models\PropertyType;
use App\Models\PropertyUnit;
use App\Models\PropertyUnitInterior;
use App\Models\PropertyUnitInteriorTrans;
use App\Models\PropertyUnitTrans;
use App\Models\Tag;
use App\Cache\CacheKey;
use App\Cache\CacheWarmer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PasangPropertyController extends Controller
{
    public function __construct()
    {
        
    }
    public function index() : View
    {
        $propertyConditions = PropertyCondition::with(['translations' => function($query) {
            $query->where('locale', 'id');
        }])
        ->where('is_active', 1)
        ->get()->toArray();

        $property_type = PropertyType::with(['translations' => function($query) {
            $query->where('locale', 'id');
        }])
        ->where('is_active', 1)
        ->get()->toArray();
        
        return view('back/propertySaya/pasang-property', compact('propertyConditions', 'property_type'));
    }

    public function store(Request $request)

    {

        // ==========================================

        // 1. VALIDASI DATA

        // ==========================================

        $validated = $request->validate([

            // Step 1: Kategori

            'tipe_properti'      => 'required',

            'property_condition' => 'required',

           

            // Step 2: Spesifikasi & Fasilitas

            'title'              => 'required|string|max:255',

            'description'        => 'required|string',

            'highlight_tags'     => 'nullable|array',

            'normal_tags'        => 'nullable|array',

            'bedrooms'           => 'nullable|integer|min:0',

            'bathrooms'          => 'nullable|integer|min:0',

            'land_area'          => 'nullable|numeric|min:0',

            'building_area'      => 'nullable|numeric|min:0',

           

            // Input Dinamis

            'spec_keys'          => 'nullable|array',

            'spec_values'        => 'nullable|array',

            'fasilitas'          => 'nullable|array',

           

            // Step 3: Harga & Media

            'price'              => 'required|string', // String dulu karena mungkin ada titik (1.100.000)

            'discount'           => 'nullable|string', // Pastikan HTML diubah jadi name="discount"

           

            // Foto Wajib

            'main_thumbnail'     => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB

            'mini_thumbnail'     => 'required|image|mimes:jpeg,png,jpg,webp|max:1024', // Max 1MB

           

            // Galeri Interior Dinamis

            'interior_images'    => 'nullable|array',

            'interior_images.*'  => 'image|mimes:jpeg,png,jpg,webp|max:2048', // Tiap file max 2MB

            'interior_labels'    => 'nullable|array',

        ]);



        // ==========================================

        // 2. UPLOAD FOTO WAJIB (Main & Mini Thumbnail)

        // ==========================================

        $mainThumbnailPath = null;

        if ($request->hasFile('main_thumbnail')) {

            $mainThumbnailPath = $request->file('main_thumbnail')->store('properties/main', 'public');

        }



        $miniThumbnailPath = null;

        if ($request->hasFile('mini_thumbnail')) {

            $miniThumbnailPath = $request->file('mini_thumbnail')->store('properties/mini', 'public');

        }



        // ==========================================

        // 3. SIAPKAN ARRAY UNTUK DATABASE

        // ==========================================



        // A. Array Data Utama (Tabel Properties)

        // Hapus titik pada input harga (misal "1.100.000" jadi "1100000")

        $cleanPrice = (int) str_replace('.', '', $request->price);

        $cleanDiscount = $request->filled('discount') ? (int) str_replace('.', '', $request->discount) : 0;



        $propertyData = [

            'property_type_id'      => $request->tipe_properti,

            'property_condition_id' => $request->property_condition,

            'title'                 => $request->title,

            'description'           => $request->description,

            'bedrooms'              => $request->bedrooms ?? 0,

            'bathrooms'             => $request->bathrooms ?? 0,

            'land_area'             => $request->land_area ?? 0,

            'building_area'         => $request->building_area ?? 0,

            'price'                 => $cleanPrice,

            'discount'              => $cleanDiscount,

            'main_thumbnail'        => $mainThumbnailPath,

            'mini_thumbnail'        => $miniThumbnailPath,

            // Tag bisa disimpan sebagai JSON di kolom tabel (opsional)

            'highlight_tags'        => json_encode($request->highlight_tags ?? []),

            'normal_tags'           => json_encode($request->normal_tags ?? []),

            'created_at'            => now(),

            'updated_at'            => now(),

        ];



        // B. Array Spesifikasi Detail (Tabel Relasi: property_specifications)

        $specificationsData = [];

        if ($request->has('spec_keys') && $request->has('spec_values')) {

            foreach ($request->spec_keys as $index => $key) {

                $value = $request->spec_values[$index] ?? null;

                // Hanya masukkan jika key dan value tidak kosong

                if (!empty(trim($key)) && !empty(trim($value))) {

                    $specificationsData[] = [

                        'spec_name'  => $key,

                        'spec_value' => $value,

                    ];

                }

            }

        }



        // C. Array Fasilitas (Tabel Relasi: property_facilities)

        $facilitiesData = [];

        if ($request->has('fasilitas')) {

            foreach ($request->fasilitas as $fasilitas) {

                if (!empty(trim($fasilitas))) {

                    $facilitiesData[] = [

                        'facility_name' => $fasilitas,

                    ];

                }

            }

        }



        // D. Array Galeri Interior Dinamis (Tabel Relasi: property_galleries)

        $interiorGalleryData = [];

        if ($request->hasFile('interior_images')) {

            foreach ($request->file('interior_images') as $index => $image) {

                if ($image->isValid()) {

                    // Upload ke folder

                    $path = $image->store('properties/interiors', 'public');

                   

                    // Ambil label yang sesuai dengan index foto

                    $label = $request->interior_labels[$index] ?? 'Lainnya';



                    $interiorGalleryData[] = [

                        'image_path'    => $path,

                        'interior_area' => $label, // Menyimpan keterangan (Dapur, Ruang Tamu, dll)

                    ];

                }

            }

        }



        // ==========================================

        // 4. PROSES INSERT KE DATABASE

        // ==========================================

        $uploadedPaths = array_filter([
            $mainThumbnailPath,
            $miniThumbnailPath,
            ...array_column($interiorGalleryData, 'image_path'),
        ]);

        DB::beginTransaction();

        try {
            // A. Insert m_property_unit
            $unit = PropertyUnit::create([
                'property_type_id'  => $request->tipe_properti,
                'condition_id'      => $request->property_condition,
                'bedrooms'          => $request->bedrooms ?? 0,
                'bathroom'          => $request->bathrooms ?? 0,
                'land_area'         => $request->land_area ?? 0,
                'building_area'     => $request->building_area ?? 0,
                'price'             => $cleanPrice,
                'diskon'            => $cleanDiscount,
                'is_active'         => 1,
                'created_datetime'  => now(),
            ]);

            // B. Insert m_property_unit_trans
            PropertyUnitTrans::create([
                'property_id'   => $unit->property_id,
                'locale'        => 'id',
                'property_name' => $request->title,
                'title'         => $request->title,
                'description'   => $request->description,
            ]);

            // C. Main thumbnail (order=1)
            if ($mainThumbnailPath) {
                $interior = PropertyUnitInterior::create([
                    'property_id'      => $unit->property_id,
                    'image'            => $mainThumbnailPath,
                    'order'            => 1,
                    'is_active'        => 1,
                    'created_datetime' => now(),
                ]);
                PropertyUnitInteriorTrans::create([
                    'property_interior_id' => $interior->property_interior_id,
                    'locale'               => 'id',
                    'interior_name'        => 'Main Thumbnail',
                    'created_datetime'     => now(),
                ]);
            }

            // D. Mini thumbnail (order=2)
            if ($miniThumbnailPath) {
                $interior = PropertyUnitInterior::create([
                    'property_id'      => $unit->property_id,
                    'image'            => $miniThumbnailPath,
                    'order'            => 2,
                    'is_active'        => 1,
                    'created_datetime' => now(),
                ]);
                PropertyUnitInteriorTrans::create([
                    'property_interior_id' => $interior->property_interior_id,
                    'locale'               => 'id',
                    'interior_name'        => 'Mini Thumbnail',
                    'created_datetime'     => now(),
                ]);
            }

            // E. Interior gallery (order=3+)
            foreach ($interiorGalleryData as $idx => $gallery) {
                $interior = PropertyUnitInterior::create([
                    'property_id'      => $unit->property_id,
                    'image'            => $gallery['image_path'],
                    'order'            => $idx + 3,
                    'is_active'        => 1,
                    'created_datetime' => now(),
                ]);
                PropertyUnitInteriorTrans::create([
                    'property_interior_id' => $interior->property_interior_id,
                    'locale'               => 'id',
                    'interior_name'        => $gallery['interior_area'],
                    'created_datetime'     => now(),
                ]);
            }

            // F. Specifications
            foreach ($specificationsData as $spec) {
                $specRecord = PropertySpec::create(['property_id' => $unit->property_id]);
                PropertySpecTrans::create([
                    'property_spec_id' => $specRecord->property_spec_id,
                    'locale'           => 'id',
                    'spec_key'         => $spec['spec_name'],
                    'spec_value'       => $spec['spec_value'],
                ]);
            }

            // G. Facilities
            foreach ($facilitiesData as $facility) {
                $facRecord = PropertyFacility::create(['property_id' => $unit->property_id]);
                PropertyFacilityTrans::create([
                    'facility_id' => $facRecord->facility_id,
                    'locale'      => 'id',
                    'name'        => $facility['facility_name'],
                ]);
            }

            // H. Tags (highlight + normal)
            $allTags = array_merge(
                array_map(fn($t) => ['name' => $t, 'color_code' => '#ff6b35'], $request->highlight_tags ?? []),
                array_map(fn($t) => ['name' => $t, 'color_code' => '#888888'], $request->normal_tags ?? []),
            );
            foreach ($allTags as $tagData) {
                $tag = Tag::firstOrCreate(
                    ['name' => $tagData['name']],
                    ['color_code' => $tagData['color_code'], 'created_at' => now()]
                );
                DB::table('m_property_tag_pivot')->insertOrIgnore([
                    'property_id' => $unit->property_id,
                    'tag_id'      => $tag->tag_id,
                ]);
            }

            DB::commit();

            CacheWarmer::reload(CacheKey::PROPERTY_UNITS);
            CacheWarmer::reload(CacheKey::PROPERTY_UNIT, $unit->property_id);
            CacheWarmer::reload(CacheKey::RECOMMENDATIONS);
            CacheWarmer::reload(CacheKey::NEW_PROPERTIES);
            CacheWarmer::reload(CacheKey::ALL_PRODUCTS);
            CacheWarmer::reload(CacheKey::PROPERTY_COUNT);

            return redirect()->route('customer.property')
                ->with('success', 'Properti berhasil diterbitkan!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file yang sudah terupload jika transaksi gagal
            foreach ($uploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan properti: ' . $e->getMessage());
        }
    }
}