<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table = 'lead';
    protected $primaryKey = 'LEAD_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'LEAD_ID',
        'ID_SUB',
        'ID_USER',
        'NAMA',
        'KATEGORI',
        'PERUSAHAAN',
        'KOTA',
        'kode_kota',
        'NO_TELP',
        'EMAIL',
        'STATUS',
        'REASON',
        'LEAD_SOURCE',
        'NOTE',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT',
        'CREATOR_ID'
    ];

    protected $dates = ['DELETED_AT'];

    // ===================== RELASI =====================
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_USER', 'ID_USER');
    }

    public function sub_kategori()
    {
        return $this->belongsTo(SubKategori::class, 'ID_SUB', 'ID_SUB');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kode_kota', 'id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class, 'LEAD_ID', 'LEAD_ID');
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'LEAD_ID', 'LEAD_ID');
    }

    // ===================== CUSTOM BADGE =====================
    public function getStageLabelAttribute()
    {
        switch (strtolower($this->STATUS)) {
            case 'lead':
                return 'Cold';

            case 'opportunity':
                // cek prosentase terakhir
                $opp = $this->opportunities()->latest('CREATED_AT')->first();
                if ($opp && $opp->PROSENTASE_PROSPECT > 10 && $opp->PROSENTASE_PROSPECT <= 50) {
                    return 'Warm';
                }
                // return 'Cold';

            case 'quotation':
                // cek prosentase terakhir
                $opp = $this->opportunities()->latest('CREATED_AT')->first();
                if ($opp && $opp->PROSENTASE_PROSPECT > 50) {
                    return 'Hot';
                }
                return 'Cold';

            case 'converted':
                return 'Deal';

            case 'lost':
                return 'Lost';

            case 'norespon':
                return 'No Respon';

            default:
                return ucfirst($this->STATUS);
        }
    }

    public function getStageClassAttribute()
    {
        switch (strtolower($this->stage_label)) {
            case 'cold':      return 'bg-blue-400 text-white';
            case 'warm':      return 'bg-orange-400 text-black';
            case 'hot':       return 'bg-red-500 text-white';
            case 'deal':      return 'bg-green-500 text-white';
            case 'lost':      return 'bg-gray-500 text-white';
            case 'no respon': return 'bg-yellow-400 text-black';
            default:          return 'bg-gray-400 text-white';
        }
    }
}
