<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyType extends Model
{
    protected $table = 'm_property_type';
    protected $primaryKey = 'property_type_id';
    public $timestamps = false;

    protected $fillable = [
        'is_active',
        'create_user_id',
        'create_datetime',
        'created_datetime',
    ];

    public function translations(): HasMany
    {
        // Parameter: Model tujuan, foreign_key, local_key
        return $this->hasMany(PropertyTypeTrans::class, 'property_type_id', 'property_type_id');
    }
}
