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
        'HARGA',
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

    /**
     * Relasi ke Opportunity lewat item_table
     */
    public function opportunities()
    {
        return $this->belongsToMany(Opportunity::class, 'item_table', 'ID_PRODUK', 'OPPORTUNITY_ID')
                    ->withPivot(['ID_ITEM', 'QTY', 'PRICE', 'TOTAL', 'CREATED_AT', 'UPDATED_AT', 'DELETED_AT']);
    }

    /**
     * Relasi langsung ke item_table
     */
    public function itemTables()
    {
        return $this->hasMany(ItemTable::class, 'ID_PRODUK', 'ID_PRODUK');
    }
}
