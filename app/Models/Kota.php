<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'm_kota';
    protected $primaryKey = 'kota_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_kota',
        'provinsi_id',
    ];
}
