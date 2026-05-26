<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyUnit extends Model
{
    protected $table = 'm_property_unit';
    protected $primaryKey = 'property_id';
    public $timestamps = false;

    protected $fillable = [
        'provinsi_id', 'kota_id', 'latitude', 'longtidure', 'status_id',
        'property_type_id', 'price', 'bedrooms', 'bathroom', 'land_area',
        'building_area', 'carports', 'electricity', 'is_active',
        'created_user_id', 'created_datetime', 'updated_user_id', 'updated_datetime',
        'condition_id', 'diskon', 'township_id', 'cluster_id', 'slug', 
    ];

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kota_id', 'kota_id');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'provinsi_id');
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id', 'property_type_id');
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(PropertyCondition::class, 'condition_id', 'property_condition_id');
    }

    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class, 'cluster_id', 'cluster_id');
    }

    public function township(): BelongsTo
    {
        return $this->belongsTo(Township::class, 'township_id', 'township_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PropertyUnitTrans::class, 'property_id', 'property_id');
    }

    public function interiors(): HasMany
    {
        return $this->hasMany(PropertyUnitInterior::class, 'property_id', 'property_id');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(PropertySpec::class, 'property_id', 'property_id');
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(PropertyFacility::class, 'property_id', 'property_id');
    }

    public function nearbyLocations(): HasMany
    {
        return $this->hasMany(PropertyNearbyLocation::class, 'property_id', 'property_id');
    }

    public function extraFeatures(): HasMany
    {
        return $this->hasMany(PropertyExtraFeature::class, 'property_id', 'property_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'm_property_tag_pivot', 'property_id', 'tag_id');
    }
}
