<?php
namespace App\Http\Controllers\Front;
use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;

class ShortLinkController extends \App\Http\Controllers\Controller {
    public function redirect(string $code): RedirectResponse {
        $link = ShortLink::where('code', $code)->firstOrFail();
        $link->increment('hits');
        $path = $link->redirect_path ?: '/';
        return redirect($path . '?key=' . rawurlencode($link->key_hash));
    }
}
