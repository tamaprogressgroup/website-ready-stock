<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmbedShortLinkController extends Controller
{
    public function resolve(Request $request): JsonResponse
    {
        $key  = trim($request->input('key', ''));
        $path = $request->input('path') ?: null;

        if ($path && !str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        if (empty($key)) {
            return response()->json(['error' => 'key required'], 400);
        }

        $link = ShortLink::where('key_hash', $key)
            ->where('redirect_path', $path)
            ->first();

        if (!$link) {
            $link = ShortLink::create([
                'code'          => ShortLink::generateCode(),
                'key_hash'      => $key,
                'redirect_path' => $path,
            ]);
        }

        return response()->json(['url' => url('/s/' . $link->code)]);
    }
}
