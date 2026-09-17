<?php

namespace App\Http\Controllers\Back;

use App\Cache\CacheWarmer;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('settings_key')->get();

        return view('back.settings.index', compact('settings'));
    }

    public function update(Request $request, string $key)
    {
        $request->validate([
            'settings_value' => 'required|in:active,inactive',
        ]);

        $setting = Setting::where('settings_key', $key)->firstOrFail();
        $setting->update(['settings_value' => $request->settings_value]);

        // Full flush — setting ini mempengaruhi tampilan semua properti (termasuk
        // cache detail per-properti yang di-keyed per ID), bukan cuma satu entitas.
        CacheWarmer::warmUp();

        return redirect()->route('back.settings.index')
            ->with('success', "Pengaturan \"{$setting->settings_label}\" berhasil diubah menjadi " . ($request->settings_value === 'active' ? 'Aktif' : 'Nonaktif') . '.');
    }
}
