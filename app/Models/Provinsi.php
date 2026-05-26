<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'm_provinsi';
    protected $primaryKey = 'provinsi_id';
    public $timestamps = false;

    protected $fillable = [
        'provinsi_name',
    ];
}
