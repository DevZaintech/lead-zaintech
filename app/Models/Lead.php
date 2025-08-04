<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{

    // Nama tabel jika bukan plural default
    protected $table = 'lead';

    // Primary key bukan 'id'
    protected $primaryKey = 'LEAD_ID';
    public $incrementing = false;   // karena tipe primary key varchar
    protected $keyType = 'string';  // tipe primary key string

    // Kolom yang bisa diisi mass assignment
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
    ];

    // Laravel default timestamps diubah sesuai nama kolom
    const CREATED_AT = 'CREATED_AT';
    const UPDATED_AT = 'UPDATED_AT';

    protected $dates = ['DELETED_AT'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_USER', 'id');
    }

    // Relasi ke Sub (jika ada model Sub)
    public function sub_kategori()
    {
        return $this->belongsTo(SubKategori::class, 'ID_SUB', 'id');
    }
}
