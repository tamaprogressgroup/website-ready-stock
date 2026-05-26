<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyConditionTrans extends Model
{
    protected $table = 'm_property_condition_trans';
    public $timestamps = false;

    protected $fillable = [
        'locale',
        'condition_name',
        'property_condition_id',
    ];
}
