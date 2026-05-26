<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyExtraFeatureTrans extends Model
{
    protected $table = 'm_property_unit_exstra_fitur_trans';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'property_exstra_fitur_id',
        'locale',
        'name',
    ];
}
