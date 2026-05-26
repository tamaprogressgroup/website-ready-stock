<?php

namespace App\Http\Controllers\Back\Master;

use App\Http\Controllers\Controller;
use App\Models\PropertyCondition;
use App\Models\PropertyConditionTrans;
use App\Cache\CacheKey;
use App\Cache\CacheWarmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyConditionController extends Controller
{
    public function index()
    {
        $items = PropertyCondition::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->orderByDesc('property_condition_id')
            ->paginate(20);

        return view('back.master.property-condition.index', compact('items'));
    }

    public function create()
    {
        return view('back.master.property-condition.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'condition_name' => 'required|string|max:100',
            'is_active'      => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $cond = PropertyCondition::create([
                'is_active'        => $request->boolean('is_active', true) ? 1 : 0,
                'create_datetime'  => now(),
            ]);

            PropertyConditionTrans::create(['property_condition_id' => $cond->property_condition_id, 'locale' => 'id', 'condition_name' => $request->condition_name]);
            PropertyConditionTrans::create(['property_condition_id' => $cond->property_condition_id, 'locale' => 'en', 'condition_name' => $request->condition_name]);

            DB::commit();
            CacheWarmer::reload(CacheKey::PROPERTY_CONDITIONS);

            return redirect()->route('master.property-condition.index')
                ->with('success', 'Kondisi properti berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = PropertyCondition::with(['translations' => fn($q) => $q->where('locale', 'id')])
            ->findOrFail($id);

        return view('back.master.property-condition.form', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'condition_name' => 'required|string|max:100',
            'is_active'      => 'nullable|boolean',
        ]);

        $cond = PropertyCondition::findOrFail($id);

        DB::beginTransaction();
        try {
            $cond->update(['is_active' => $request->boolean('is_active', true) ? 1 : 0]);

            foreach (['id', 'en'] as $locale) {
                PropertyConditionTrans::updateOrCreate(
                    ['property_condition_id' => $id, 'locale' => $locale],
                    ['condition_name' => $request->condition_name]
                );
            }

            DB::commit();
            CacheWarmer::reload(CacheKey::PROPERTY_CONDITIONS);

            return redirect()->route('master.property-condition.index')
                ->with('success', 'Kondisi properti berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        PropertyCondition::findOrFail($id)->delete();
        CacheWarmer::reload(CacheKey::PROPERTY_CONDITIONS);

        return redirect()->route('master.property-condition.index')
            ->with('success', 'Kondisi properti berhasil dihapus.');
    }
}
