<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table = 'lead';
    protected $primaryKey = 'LEAD_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // <<< ini penting

    protected $fillable = [
        'LEAD_ID',
        'ID_SUB',
        'ID_USER',
        'NAMA',
        'PERUSAHAAN',
        'NO_TELP',
        'EMAIL',
        'STATUS',
        'LEAD_SOURCE',
        'NOTE',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT',
        'CREATOR_ID'
    ];

    protected $dates = ['DELETED_AT'];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_USER', 'ID_USER');
    }

    public function sub_kategori()
    {
        return $this->belongsTo(SubKategori::class, 'ID_SUB', 'ID_SUB');
    }
}
