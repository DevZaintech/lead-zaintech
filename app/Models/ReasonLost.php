<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ReasonLost extends Model
{

    protected $table = 'reason_lost';
    protected $primaryKey = 'ID_REASON';
    // Jangan pakai timestamps otomatis Laravel
    public $timestamps = false;

    protected $fillable = [
        'REASON',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT',
    ];

}
