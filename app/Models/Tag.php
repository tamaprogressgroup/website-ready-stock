<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    protected $table = 'm_tags';
    protected $primaryKey = 'tag_id';
    public $timestamps = false;

    protected $fillable = [
        'color_code',
        'name',
        'created_at',
        'is_label',
    ];

    public function propertyUnits(): BelongsToMany
    {
        return $this->belongsToMany(PropertyUnit::class, 'm_property_tag_pivot', 'tag_id', 'property_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TagTrans::class, 'tag_id', 'tag_id');
    }
}
