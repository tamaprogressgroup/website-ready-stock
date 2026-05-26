<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Township extends Model
{
    protected $table = 'm_township';
    protected $primaryKey = 'township_id';
    public $timestamps = false;

    protected $fillable = [
        'township_name',
        'created_datetime',
        'created_user_id',
        'image',
        'image_mobile',
    ];

    public function propertyUnits(): HasMany
    {
        return $this->hasMany(PropertyUnit::class, 'township_id', 'township_id');
    }
}
