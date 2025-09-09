<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $table = 'follow_up'; // nama tabel
    protected $primaryKey = 'ID_FOLLOW'; // primary key

    public $timestamps = false; // disable auto timestamp Laravel

    protected $fillable = [
        'OPPORTUNITY_ID',
        'TGL_FOLLOW',
        'RESPON',
        'KETERANGAN',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT',
    ];

    // Relasi ke Opportunity
    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'OPPORTUNITY_ID', 'OPPORTUNITY_ID');
    }
}
