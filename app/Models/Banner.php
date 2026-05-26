<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    protected $table = 'm_banners';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'image_url',
        'target_url',
        'position',
        'is_active',
        'priority',
        'created_user_id',
        'craeted_datetime',  // typo di DB, diikuti apa adanya
        'updated_user_id',
        'updated_datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_user_id', 'id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_user_id', 'id');
    }
}
