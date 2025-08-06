<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class SubKategori extends Model
{

    protected $table = 'sub_kategori';
    protected $primaryKey = 'ID_SUB';
    // Jangan pakai timestamps otomatis Laravel
    public $timestamps = false;

    protected $fillable = [
        'ID_KATEGORI',
        'NAMA',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'ID_KATEGORI', 'ID_KATEGORI');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'ID_SUB', 'ID_SUB');
    }

    public function lead()
    {
        return $this->hasMany(Lead::class, 'ID_SUB', 'ID_SUB');
    }
}
