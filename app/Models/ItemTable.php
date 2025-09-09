<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTable extends Model
{
    protected $table = 'item_table'; // ganti sesuai nama tabel di database
    protected $primaryKey = null; // karena pakai composite key
    public $incrementing = false; // tidak auto increment
    public $timestamps = false; // pakai CREATED_AT & UPDATED_AT manual

    protected $fillable = [
        'ID_PRODUK',
        'OPPORTUNITY_ID',
        'ID_ITEM',
        'QTY',
        'PRICE',
        'TOTAL',
        'CREATED_AT',
        'UPDATED_AT',
        'DELETED_AT',
    ];

    /**
     * Relasi ke Opportunity
     */
    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'OPPORTUNITY_ID', 'OPPORTUNITY_ID');
    }

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ID_PRODUK', 'ID_PRODUK');
    }
}
