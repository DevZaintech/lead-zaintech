<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    // Nama tabel (jika tidak sesuai konvensi Laravel)
    protected $table = 'opportunity';

    // Primary key
    protected $primaryKey = 'OPPORTUNITY_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    // Nonaktifkan timestamps otomatis (created_at & updated_at)
    public $timestamps = false;

    // Daftar kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'OPPORTUNITY_ID',
        'LEAD_ID',
        'NILAI_PROSPECT',
        'PROSENTASE_PROSPECT',
        'NOTE',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT',
    ];

    /**
     * Relasi ke model Lead (foreign key: LEAD_ID)
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'LEAD_ID', 'LEAD_ID');
    }

    /**
     * Relasi ke Produk lewat item_table
     */
    public function products()
    {
        return $this->belongsToMany(Produk::class, 'item_table', 'OPPORTUNITY_ID', 'ID_PRODUK')
                    ->withPivot(['ID_ITEM', 'QTY', 'PRICE', 'TOTAL', 'CREATED_AT', 'UPDATED_AT', 'DELETED_AT']);
    }

    /**
     * Relasi langsung ke item_table
     */
    public function itemTables()
    {
        return $this->hasMany(ItemTable::class, 'OPPORTUNITY_ID', 'OPPORTUNITY_ID');
    }

     // Relasi: 1 Opportunity punya banyak FollowUp
    public function followUps()
    {
        return $this->hasMany(FollowUp::class, 'OPPORTUNITY_ID', 'OPPORTUNITY_ID');
    }
}
