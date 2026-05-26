<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagTrans extends Model
{
    protected $table = 'm_tags_trans';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tag_id',
        'language_id',
        'name',
    ];
}
