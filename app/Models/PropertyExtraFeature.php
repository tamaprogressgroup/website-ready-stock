<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyExtraFeature extends Model
{
    protected $table = 'm_property_unit_exstra_fitur';
    protected $primaryKey = 'property_exstra_fitur_id';
    public $timestamps = false;

    protected $fillable = [
        'property_id',
        'icon_url',
        'creaeted_user_id',
        'created_datetime',
        'updated_user_id',
        'updated_datetime',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PropertyExtraFeatureTrans::class, 'property_exstra_fitur_id', 'property_exstra_fitur_id');
    }
}
