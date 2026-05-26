<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyMedia extends Model
{
    protected $table      = 'm_property_media_video';
    protected $primaryKey = 'media_id';
    public    $timestamps = false;

    protected $fillable = [
        'property_id',
        'filename',
        'url_360',
        'url_youtube',
        'created_user_id',
        'created_datetime',
        'updated_user_id',
        'updated_datetime',
    ];

    protected $casts = [
        'created_datetime' => 'datetime',
        'updated_datetime' => 'datetime',
    ];

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class, 'property_id', 'property_id');
    }
}
