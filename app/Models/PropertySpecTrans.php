<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertySpecTrans extends Model
{
    protected $table = 'm_property_specs_trans';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'property_spec_id',
        'locale',
        'spec_key',
        'spec_value',
        'satuan',
    ];
}
