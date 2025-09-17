<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\SubKategori;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProdukImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Hapus baris header
        unset($rows[0]);

        foreach ($rows as $row) {
            // Bersihkan format harga (Rp, titik, spasi)
            $harga = preg_replace('/[^0-9]/', '', $row[4]); 
            // Pastikan tetap numeric
            $harga = is_numeric($harga) ? (int) $harga : 0;

            // Cari sub kategori
            $subKategori = SubKategori::where('NAMA', $row[2])->first();

            Produk::create([
                'ID_SUB'     => $subKategori->ID_SUB ?? null,
                'NAMA'       => $row[1],   // kolom NAME
                'SKU'        => $row[0],   // kolom SKU ID
                'HARGA'      => $harga,    // kolom HARGA sudah dibersihkan
                'IMAGE'      => null,
                'STATUS'     => 'aktif',
                'CREATED_AT' => now(),
            ]);
        }
    }
}
