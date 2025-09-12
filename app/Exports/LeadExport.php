<?php

namespace App\Exports;

use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadExport implements FromView, WithStyles
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = Lead::with('user')->whereNull('DELETED_AT');

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('LEAD_ID', 'like', "%{$search}%")
                ->orWhere('NAMA', 'like', "%{$search}%")
                ->orWhere('NO_TELP', 'like', "%{$search}%")
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('NAMA', 'like', "%{$search}%");
                });
            });
        }

        if (!empty($this->filters['sales'])) {
            $query->where('ID_USER', $this->filters['sales']);
        }

        if (!empty($this->filters['source'])) {
            $query->where('LEAD_SOURCE', $this->filters['source']);
        }

        if (!empty($this->filters['startDate']) && !empty($this->filters['endDate'])) {
            $query->whereBetween('CREATED_AT', [
                $this->filters['startDate'] . ' 00:00:00',
                $this->filters['endDate'] . ' 23:59:59'
            ]);
        }

        // FILTER STATUS
        if (!empty($this->filters['status'])) {
            $statusMapping = [
                'COLD'       => 'lead',
                'WARM'       => 'opportunity',
                'HOT'        => 'quotation',
                'LOST'       => 'lost',
                'DEAL'       => 'converted',
                'NO RESPON'  => 'norespon',
            ];

            $statusValue = $statusMapping[$this->filters['status']] ?? null;
            if ($statusValue) {
                $query->where('STATUS', $statusValue);
            }
        }

        $lead = $query->orderBy('LEAD_ID', 'desc')->get();

        return view('admin.lead.export_excel', compact('lead'));
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
    
        // Border semua data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:I{$lastRow}")->getBorders()->getAllBorders()
              ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
        // Auto-size column
        foreach (range('A','I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    
        // WARNA STATUS (kolom I)
        for ($row = 2; $row <= $lastRow; $row++) {
            $status = strtoupper($sheet->getCell("I$row")->getValue());
        
            $badgeColors = [
                'COLD' => 'FF3B82F6',       // biru
                'DEAL' => 'FF22C55E',       // hijau
                'HOT' => 'FFEF4444',        // merah
                'WARM' => 'FFFF7C00',       // oranye
                'LOST' => 'FF9CA3AF',       // abu
                'NO RESPON' => 'FFFACC15',  // kuning
            ];
        
            $badgeColor = $badgeColors[$status] ?? 'FF000000';
        
            // Buat badge simbol
            $sheet->setCellValue("I$row", "● $status");
        
            // Warna badge (lingkaran)
            $sheet->getStyle("I$row")->getFont()->getColor()->setARGB('FF000000'); // teks hitam
        
            // ⚠️ Tapi PhpSpreadsheet tidak bisa beri warna berbeda ke karakter tertentu di satu sel
            // Jadi lingkaran dan teks akan sama warna. Untuk beda warna perlu RichText
            $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $badge = $richText->createTextRun("● ");
            $badge->getFont()->getColor()->setARGB($badgeColor);
            $badge->getFont()->setBold(true);
        
            $text = $richText->createTextRun($status);
            $text->getFont()->getColor()->setARGB('FF000000'); // teks hitam
            $sheet->getCell("I$row")->setValue($richText);
        }
        
    
        return [];
    }
    
}
