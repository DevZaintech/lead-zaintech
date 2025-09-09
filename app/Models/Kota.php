<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'reg_regencies';
    protected $primaryKey = 'id';
    public $incrementing = false; // karena char/varchar bukan auto increment
    protected $keyType = 'string';

    protected $fillable = ['id', 'province_id', 'name'];

    // Relasi ke Lead
    public function leads()
    {
        return $this->hasMany(Lead::class, 'kode_kota', 'id');
    }
}