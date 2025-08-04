<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{

    protected $table = 'kategori';
    protected $primaryKey = 'ID_KATEGORI';
    // Jangan pakai timestamps otomatis Laravel
    public $timestamps = false;

    protected $fillable = [
        'NAMA',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT',
    ];

    public function subKategori()
    {
        return $this->hasMany(SubKategori::class, 'ID_KATEGORI', 'ID_KATEGORI');
    }
}
