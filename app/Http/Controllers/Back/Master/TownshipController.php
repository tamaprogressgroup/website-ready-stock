<?php

namespace App\Http\Controllers\Back\Master;

use App\Http\Controllers\Controller;
use App\Models\Township;
use App\Cache\CacheKey;
use App\Cache\CacheWarmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TownshipController extends Controller
{
    public function index()
    {
        $items = Township::orderByDesc('township_id')->paginate(20);
        return view('back.master.township.index', compact('items'));
    }

    public function create()
    {
        return view('back.master.township.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'township_name' => 'required|string|max:150',
            'image'         => 'required|image|mimes:jpeg,png,jpg,webp|dimensions:width=720,height=450',
            'image_mobile'  => 'required|image|mimes:jpeg,png,jpg,webp|dimensions:width=720,height=450',
        ], [
            'image.dimensions'        => 'Gambar utama harus berukuran tepat 720 × 450 piksel.',
            'image_mobile.dimensions' => 'Gambar mobile harus berukuran tepat 720 × 450 piksel.',
        ]);

        Township::create([
            'township_name'    => $request->township_name,
            'image'            => $request->file('image')->store('townships', 'public'),
            'image_mobile'     => $request->file('image_mobile')->store('townships/mobile', 'public'),
            'created_datetime' => now(),
        ]);

        CacheWarmer::reload(CacheKey::TOWNSHIPS);

        return redirect()->route('master.township.index')
            ->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Township::findOrFail($id);
        return view('back.master.township.form', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'township_name' => 'required|string|max:150',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|dimensions:width=720,height=450',
            'image_mobile'  => 'nullable|image|mimes:jpeg,png,jpg,webp|dimensions:width=720,height=450',
        ], [
            'image.dimensions'        => 'Gambar utama harus berukuran tepat 720 × 450 piksel.',
            'image_mobile.dimensions' => 'Gambar mobile harus berukuran tepat 720 × 450 piksel.',
        ]);

        $township = Township::findOrFail($id);

        $data = ['township_name' => $request->township_name];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($township->image);
            $data['image'] = $request->file('image')->store('townships', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            Storage::disk('public')->delete($township->image_mobile);
            $data['image_mobile'] = $request->file('image_mobile')->store('townships/mobile', 'public');
        }

        $township->update($data);
        CacheWarmer::reload(CacheKey::TOWNSHIPS);

        return redirect()->route('master.township.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $township = Township::findOrFail($id);
        Storage::disk('public')->delete(array_filter([$township->image, $township->image_mobile]));
        $township->delete();
        CacheWarmer::reload(CacheKey::TOWNSHIPS);

        return redirect()->route('master.township.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }
}
