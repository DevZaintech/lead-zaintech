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
            // Excel format: SKU ID | NAME | SUB CATEGORY | CATEGORY
            $subKategori = SubKategori::where('NAMA', $row[2])->first();
            // dd($subKategori->ID_SUB);

            Produk::create([
                'ID_SUB'     => $subKategori->ID_SUB,
                'NAMA'       => $row[1],   // kolom NAME
                'SKU'        => $row[0],   // kolom SKU ID
                'IMAGE'      => null,
                'STATUS'     => 'aktif',
                'CREATED_AT' => now(),
            ]);
        }
    }
}
