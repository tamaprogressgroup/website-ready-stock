<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSeo extends Model
{
    protected $table = 'page_seo';

    protected $fillable = [
        'page_key',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'og_title',
        'og_description',
    ];
}
