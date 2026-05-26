<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFacilityTrans extends Model
{
    protected $table = 'm_property_facilities_trans';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'facility_id',
        'locale',
        'name',
    ];
}
