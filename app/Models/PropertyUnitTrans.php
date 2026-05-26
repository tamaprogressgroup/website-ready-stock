<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyUnitTrans extends Model
{
    protected $table = 'm_property_unit_trans';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'locale',
        'property_name',
        'title',
        'description',
        'property_id',
        'meta_title',
        'meta_keyword',
        'meta_descriotion',
    ];
}
