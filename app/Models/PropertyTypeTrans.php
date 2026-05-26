<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyTypeTrans extends Model
{
    protected $table = 'm_property_type_trans';
    public $timestamps = false;

    protected $fillable = [
        'locale',
        'type_name',
        'property_type_id',
    ];
}
