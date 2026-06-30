<?php

namespace App\Http\Controllers\Back\Master;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Cache\CacheKey;
use App\Cache\CacheWarmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClusterController extends Controller
{
    public function index()
    {
        $items = Cluster::orderByDesc('cluster_id')->paginate(20);
        return view('back.master.cluster.index', compact('items'));
    }

    public function create()
    {
        return view('back.master.cluster.form', ['item' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cluster_name' => 'required|string|max:150',
            'is_active'    => 'nullable|boolean',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|dimensions:width=720,height=450',
            'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|dimensions:width=720,height=450',
        ], [
            'image.dimensions'        => 'Gambar utama harus berukuran tepat 720 x 450 piksel.',
            'image_mobile.dimensions' => 'Gambar mobile harus berukuran tepat 720 x 450 piksel.',
        ]);

        Cluster::create([
            'cluster_name'     => $request->cluster_name,
            'is_active'        => $request->boolean('is_active', true) ? 1 : 0,
            'image'            => $request->hasFile('image') ? $request->file('image')->store('clusters', 'public') : null,
            'image_mobile'     => $request->hasFile('image_mobile') ? $request->file('image_mobile')->store('clusters/mobile', 'public') : null,
            'created_datetime' => now(),
        ]);

        CacheWarmer::reload(CacheKey::CLUSTERS);

        return redirect()->route('master.cluster.index')
            ->with('success', 'Cluster berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Cluster::findOrFail($id);
        return view('back.master.cluster.form', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cluster_name' => 'required|string|max:150',
            'is_active'    => 'nullable|boolean',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|dimensions:width=720,height=450',
            'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|dimensions:width=720,height=450',
        ], [
            'image.dimensions'        => 'Gambar utama harus berukuran tepat 720 x 450 piksel.',
            'image_mobile.dimensions' => 'Gambar mobile harus berukuran tepat 720 x 450 piksel.',
        ]);

        $cluster = Cluster::findOrFail($id);

        $data = [
            'cluster_name' => $request->cluster_name,
            'is_active'    => $request->boolean('is_active', true) ? 1 : 0,
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($cluster->image);
            $data['image'] = $request->file('image')->store('clusters', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            Storage::disk('public')->delete($cluster->image_mobile);
            $data['image_mobile'] = $request->file('image_mobile')->store('clusters/mobile', 'public');
        }

        $cluster->update($data);
        CacheWarmer::reload(CacheKey::CLUSTERS);

        return redirect()->route('master.cluster.index')
            ->with('success', 'Cluster berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $cluster = Cluster::findOrFail($id);
        Storage::disk('public')->delete(array_filter([$cluster->image, $cluster->image_mobile]));
        $cluster->delete();
        CacheWarmer::reload(CacheKey::CLUSTERS);

        return redirect()->route('master.cluster.index')
            ->with('success', 'Cluster berhasil dihapus.');
    }
}
