<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PropertyUnit;

class Lead extends Model
{
    protected $table = 'm_leads';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'salutation',
        'fullname',
        'email',
        'phone_number',
        'enquiry',
        'township_id',
        'cluster_id',
        'property_id',
        'created_at',
        'params',
        'hutk',
        'sumber_informasi',
        'datang_dengan_siapa',
        'kirim_brosur',
        'berminat_cari',
        'rencana_beli',
        'jumlah_kamar',
        'hubspot_submit',
        'remote_addr',
        'contact_form_id',
        'url_form',
        'url_origin',
    ];

    protected $casts = [
        'kirim_brosur'    => 'boolean',
        'hubspot_submit'  => 'boolean',
        'created_at'      => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(PropertyUnit::class, 'property_id', 'property_id');
    }
}
