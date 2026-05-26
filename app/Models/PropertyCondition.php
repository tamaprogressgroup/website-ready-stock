<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyCondition extends Model
{
    protected $table = 'm_property_condition';
    protected $primaryKey = 'property_condition_id';
    public $timestamps = false;

    protected $fillable = [
        'color',
        'create_user_id',
        'create_datetime',
        'updated_user_id',
        'updated_datetime',
        'is_active',
    ];

    public function translations(): HasMany
    {
        // Parameter: Model tujuan, foreign_key, local_key
        return $this->hasMany(PropertyConditionTrans::class, 'property_condition_id', 'property_condition_id');
    }
}
