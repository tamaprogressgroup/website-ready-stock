<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $table = 'm_cluster';
    protected $primaryKey = 'cluster_id';
    public $timestamps = false;

    protected $fillable = [
        'cluster_name',
        'is_active',
        'created_user_id',
        'created_datetime',
        'image',
        'image_mobile',
    ];
}
