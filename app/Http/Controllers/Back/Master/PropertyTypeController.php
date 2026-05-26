<?php

namespace App\Http\Controllers\Back\Master;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use App\Models\PropertyTypeTrans;
use App\Cache\CacheKey;
use App\Cache\CacheWarmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyTypeController extends Controller
{
    public function index()
    {
        $items = PropertyType::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->orderByDesc('property_type_id')
            ->paginate(20);

        return view('back.master.property-type.index', compact('items'));
    }

    public function create()
    {
        return view('back.master.property-type.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $type = PropertyType::create([
                'is_active'       => $request->boolean('is_active', true) ? 1 : 0,
                'create_datetime' => now(),
            ]);

            PropertyTypeTrans::create(['property_type_id' => $type->property_type_id, 'locale' => 'id', 'type_name' => $request->type_name]);
            PropertyTypeTrans::create(['property_type_id' => $type->property_type_id, 'locale' => 'en', 'type_name' => $request->type_name]);

            DB::commit();
            CacheWarmer::reload(CacheKey::PROPERTY_TYPES);

            return redirect()->route('master.property-type.index')
                ->with('success', 'Tipe properti berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = PropertyType::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->findOrFail($id);

        return view('back.master.property-type.form', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type_name' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $type = PropertyType::findOrFail($id);

        DB::beginTransaction();
        try {
            $type->update(['is_active' => $request->boolean('is_active', true) ? 1 : 0]);

            foreach (['id', 'en'] as $locale) {
                PropertyTypeTrans::updateOrCreate(
                    ['property_type_id' => $id, 'locale' => $locale],
                    ['type_name' => $request->type_name]
                );
            }

            DB::commit();
            CacheWarmer::reload(CacheKey::PROPERTY_TYPES);

            return redirect()->route('master.property-type.index')
                ->with('success', 'Tipe properti berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        PropertyType::findOrFail($id)->delete();
        CacheWarmer::reload(CacheKey::PROPERTY_TYPES);

        return redirect()->route('master.property-type.index')
            ->with('success', 'Tipe properti berhasil dihapus.');
    }
}
