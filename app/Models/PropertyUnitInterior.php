<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyUnitInterior extends Model
{
    protected $table = 'm_property_unit_interior';
    protected $primaryKey = 'property_interior_id';
    public $timestamps = false;

    protected $fillable = [
        'image',
        'is_active',
        'order',
        'property_id',
        'created_user_id',
        'created_datetime',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PropertyUnitInteriorTrans::class, 'property_interior_id', 'property_interior_id');
    }
}
