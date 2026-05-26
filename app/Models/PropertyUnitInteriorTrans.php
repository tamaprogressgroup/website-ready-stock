<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyUnitInteriorTrans extends Model
{
    protected $table = 'm_property_unit_interior_trans';
    public $timestamps = false;

    protected $fillable = [
        'property_interior_id',
        'locale',
        'interior_name',
        'created_user_id',
        'created_datetime',
        'updated_user_id',
        'updated_datetime',
    ];
}
