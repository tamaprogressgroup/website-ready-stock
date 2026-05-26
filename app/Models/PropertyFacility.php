<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyFacility extends Model
{
    protected $table = 'm_property_facilities';
    protected $primaryKey = 'facility_id';
    public $timestamps = false;

    protected $fillable = [
        'icon_url',
        'property_id',
        'image',
        'created_user_id',
        'created_datetime',
        'updated_user_id',
        'updated_datetime',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PropertyFacilityTrans::class, 'facility_id', 'facility_id');
    }
}
