<?php

namespace App\Http\Controllers\Back\Master;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Cache\CacheKey;
use App\Cache\CacheWarmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // Daftar pilihan posisi banner
    public const POSITIONS = [
        'HOMEPAGE_ATAS'     => 'Homepage Atas',
        'HOMEPAGE_TENGAH'   => 'Homepage Tengah',
        'HOMEPAGE_BAWAH'    => 'Homepage Bawah',
        'ALLPRODUCT_ATAS'   => 'All Product Atas',
        'ALLPRODUCT_TENGAH' => 'All Product Tengah',
        'ALLPRODUCT_BAWAH'  => 'All Product Bawah',
        'DETAIL_ATAS'       => 'Detail Properti Atas',
    ];

    // Dimensi wajib per posisi  [width, height]
    private const DIM = [
        'default' => [3520, 1216],
    ];

    // Posisi tanpa validasi dimensi (terima ukuran berapapun)
    private const NO_DIM = ['ALLPRODUCT_TENGAH'];

    public function index()
    {
        $items = Banner::orderBy('priority')->orderByDesc('id')->paginate(20);
        return view('back.master.banner.index', compact('items'));
    }

    public function create()
    {
        return view('back.master.banner.form', [
            'item'      => null,
            'positions' => self::POSITIONS,
        ]);
    }

    public function store(Request $request)
    {
        $pos      = $request->input('position', '');
        $noDim    = in_array($pos, self::NO_DIM);
        if (!$noDim) {
            [$w, $h] = self::DIM[$pos] ?? self::DIM['default'];
        }

        $request->validate([
            'image'      => $noDim
                ? 'required|image|mimes:jpeg,png,jpg,webp|max:10240'
                : "required|image|mimes:jpeg,png,jpg,webp|max:10240|dimensions:width={$w},height={$h}",
            'target_url' => 'nullable|url|max:500',
            'position'   => 'required|string|in:' . implode(',', array_keys(self::POSITIONS)),
            'priority'   => 'required|integer|min:1',
            'is_active'  => 'nullable|boolean',
        ], [
            'image.max'        => 'Ukuran file banner tidak boleh lebih dari 10 MB.',
            'image.dimensions' => $noDim ? '' : "Gambar banner harus berukuran tepat {$w} × {$h} piksel.",
            'position.in'      => 'Posisi banner tidak valid.',
            'priority.required'=> 'Urutan prioritas wajib diisi.',
        ]);

        Banner::create([
            'image_url'        => $request->file('image')->store('banners', 'public'),
            'target_url'       => $request->target_url,
            'position'         => $request->position,
            'priority'         => $request->priority,
            'is_active'        => $request->has('is_active') ? 1 : 0,
            'created_user_id'  => Auth::guard('admin')->id(),
            'craeted_datetime' => now(), // typo di DB diikuti
        ]);

        CacheWarmer::reload(CacheKey::BANNERS);

        return redirect()->route('master.banner.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Banner::findOrFail($id);
        return view('back.master.banner.form', [
            'item'      => $item,
            'positions' => self::POSITIONS,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pos   = $request->input('position', '');
        $noDim = in_array($pos, self::NO_DIM);
        if (!$noDim) {
            [$w, $h] = self::DIM[$pos] ?? self::DIM['default'];
        }

        $request->validate([
            'image'      => $noDim
                ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240'
                : "nullable|image|mimes:jpeg,png,jpg,webp|max:10240|dimensions:width={$w},height={$h}",
            'target_url' => 'nullable|url|max:500',
            'position'   => 'required|string|in:' . implode(',', array_keys(self::POSITIONS)),
            'priority'   => 'required|integer|min:1',
            'is_active'  => 'nullable|boolean',
        ], [
            'image.max'        => 'Ukuran file banner tidak boleh lebih dari 10 MB.',
            'image.dimensions' => $noDim ? '' : "Gambar banner harus berukuran tepat {$w} × {$h} piksel.",
            'position.in'      => 'Posisi banner tidak valid.',
        ]);

        $banner = Banner::findOrFail($id);

        $data = [
            'target_url'       => $request->target_url,
            'position'         => $request->position,
            'priority'         => $request->priority,
            'is_active'        => $request->has('is_active') ? 1 : 0,
            'updated_user_id'  => Auth::guard('admin')->id(),
            'updated_datetime' => now(),
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image_url);
            $data['image_url'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);
        CacheWarmer::reload(CacheKey::BANNERS);

        return redirect()->route('master.banner.index')
            ->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        Storage::disk('public')->delete($banner->image_url);
        $banner->delete();
        CacheWarmer::reload(CacheKey::BANNERS);

        return redirect()->route('master.banner.index')
            ->with('success', 'Banner berhasil dihapus.');
    }
}
