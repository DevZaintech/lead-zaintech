<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    // Nama tabel
    protected $table = 'quotation';

    // Primary key
    protected $primaryKey = 'QUO_ID';
    public $incrementing = false; // karena varchar
    protected $keyType = 'string';

    // Laravel tidak otomatis mengelola timestamps
    public $timestamps = false;

    // Kolom yang bisa diisi
    protected $fillable = [
        'QUO_ID',
        'OPPORTUNITY_ID',
        'SNK',
        'VALID_DATE',
        'STATUS',
        'NOTE',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT'
    ];

    // Relasi ke Opportunity (FK OPPORTUNITY_ID)
    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'OPPORTUNITY_ID', 'OPPORTUNITY_ID');
    }
}
