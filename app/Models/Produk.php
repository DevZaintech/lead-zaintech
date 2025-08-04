<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{

    protected $table = 'produk';
    protected $primaryKey = 'ID_PRODUK';
    // Jangan pakai timestamps otomatis Laravel
    public $timestamps = false;

    protected $fillable = [
        'ID_SUB',
        'NAMA',
        'SKU',
        'IMAGE',
        'STATUS',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT',
    ];

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class, 'ID_SUB', 'ID_SUB');
    }
}
