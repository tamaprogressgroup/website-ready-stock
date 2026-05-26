<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationArea extends Model
{
    protected $table = 'm_location_area';
    protected $primaryKey = 'location_id';
    public $timestamps = false;

    protected $fillable = [
        'location_name',
        'provinsi_id',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'provinsi_id');
    }
}
