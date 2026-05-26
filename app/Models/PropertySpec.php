<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertySpec extends Model
{
    protected $table = 'm_property_specs';
    protected $primaryKey = 'property_spec_id';
    public $timestamps = false;

    protected $fillable = [
        'property_id',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PropertySpecTrans::class, 'property_spec_id', 'property_spec_id');
    }
}
