<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyNearbyLocationTrans extends Model
{
    protected $table = 'm_property_nearby_locations_trans';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nearby_location_id',
        'locale',
        'name',
    ];
}
