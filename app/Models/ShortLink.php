<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShortLink extends Model {
    protected $fillable = ['code', 'key_hash', 'redirect_path'];

    public static function generateCode(): string {
        do { $code = Str::random(8); } while (static::where('code', $code)->exists());
        return $code;
    }
}
