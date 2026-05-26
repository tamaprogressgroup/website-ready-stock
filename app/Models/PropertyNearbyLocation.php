<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyNearbyLocation extends Model
{
    protected $table = 'm_property_nearby_locations';
    protected $primaryKey = 'nearby_location_id';
    public $timestamps = false;

    protected $fillable = [
        'property_id',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PropertyNearbyLocationTrans::class, 'nearby_location_id', 'nearby_location_id');
    }
}
