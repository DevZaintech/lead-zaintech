<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdukExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Produk::with('subkategori.kategori')
            ->whereNull('DELETED_AT') // <- hanya ambil produk yang belum dihapus
            ->orderBy('ID_PRODUK', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    $item->SKU,
                    $item->NAMA,
                    $item->subkategori->NAMA ?? '',
                    $item->subkategori->kategori->NAMA ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'SKU ID',
            'NAME',
            'SUB CATEGORY',
            'CATEGORY',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
              ->applyFromArray([
                  'borders' => [
                      'allBorders' => [
                          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                          'color' => ['argb' => 'FF000000'],
                      ],
                  ],
                  'alignment' => [
                      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                  ],
              ]);

        // Bold header
        $sheet->getStyle('A1:'.$lastColumn.'1')->getFont()->setBold(true);

        // Auto-size kolom
        foreach (range('A', $lastColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
