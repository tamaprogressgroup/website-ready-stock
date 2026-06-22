<?php
namespace App\Http\Controllers\Back;
use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use Illuminate\Http\Request;

class ShortLinkController extends Controller {
    public function index() {
        $links = ShortLink::latest()->get();
        return view('back.short-links.index', compact('links'));
    }

    public function store(Request $request) {
        $request->validate([
            'key_hash'      => 'required|string',
            'redirect_path' => 'nullable|string|max:500',
        ]);

        $path = $request->redirect_path;
        if ($path && !str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        $link = ShortLink::create([
            'code'          => ShortLink::generateCode(),
            'key_hash'      => trim($request->key_hash),
            'redirect_path' => $path ?: null,
        ]);

        return back()->with('created_url', url('/s/' . $link->code));
    }

    public function destroy(int $id) {
        ShortLink::findOrFail($id)->delete();
        return back()->with('success', 'Short link dihapus.');
    }
}
