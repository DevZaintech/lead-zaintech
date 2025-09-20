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
        $query = Lead::with(['user', 'opportunities'])->whereNull('DELETED_AT');

        // 🔍 Filter umum
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

        // 🎯 Filter STATUS asli
        if (!empty($this->filters['status'])) {
            $status = strtolower($this->filters['status']); // normalisasi biar konsisten

            if ($status === 'opportunity') { // Warm
                $query->where('STATUS', 'opportunity')
                    ->whereHas('opportunities', function($op) {
                        $op->where('PROSENTASE_PROSPECT', '>=', 50);
                    });

            } elseif ($status === 'lead') { // Cold
                $query->where(function($query) {
                    $query->where('STATUS', 'lead')
                        ->orWhereHas('opportunities', function($op) {
                            $op->where('PROSENTASE_PROSPECT', '<', 50);
                        })
                        ->orWhereDoesntHave('opportunities'); // Termasuk lead tanpa opportunity
                })->where('STATUS', '!=', 'norespon');

            } elseif ($status === 'quotation') { // Hot
                $query->where('STATUS', 'quotation');

            } elseif ($status === 'lost') {
                $query->where('STATUS', 'lost');

            } elseif ($status === 'converted') { // Deal
                $query->where('STATUS', 'converted');

            } elseif ($status === 'norespon') {
                $query->where('STATUS', 'norespon');
            }
        }

        $lead = $query->orderBy('LEAD_ID', 'desc')->get();

        // 🔄 Tentukan STATUS manual
        $lead->transform(function ($item) {
            $status = strtolower($item->STATUS);

            if ($status === 'lead') {
                $item->STATUS = 'COLD';
            } elseif ($status === 'opportunity') {
                $opp = $item->opportunities()->latest('CREATED_AT')->first();
                if ($opp && $opp->PROSENTASE_PROSPECT >= 50) {
                    $item->STATUS = 'WARM';
                } else {
                    $item->STATUS = 'COLD';
                }
            } elseif ($status === 'quotation') {
                $item->STATUS = 'HOT';
            } elseif ($status === 'converted') {
                $item->STATUS = 'DEAL';
            } elseif ($status === 'lost') {
                $item->STATUS = 'LOST';
            } elseif ($status === 'norespon') {
                $item->STATUS = 'NO RESPON';
            } else {
                $item->STATUS = strtoupper($status);
            }

            return $item;
        });

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

        // Auto-size kolom
        foreach (range('A','I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 🎨 Warna STATUS (kolom I)
        for ($row = 2; $row <= $lastRow; $row++) {
            $status = strtoupper($sheet->getCell("I$row")->getValue());

            $badgeColors = [
                'COLD'      => 'FF3B82F6',  // biru
                'WARM'      => 'FFFF7C00',  // oranye
                'HOT'       => 'FFEF4444',  // merah
                'DEAL'      => 'FF22C55E',  // hijau
                'LOST'      => 'FF9CA3AF',  // abu
                'NO RESPON' => 'FFFACC15',  // kuning
            ];

            $badgeColor = $badgeColors[$status] ?? 'FF000000';

            // ● badge + teks
            $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $dot = $richText->createTextRun("● ");
            $dot->getFont()->getColor()->setARGB($badgeColor);
            $dot->getFont()->setBold(true);

            $text = $richText->createTextRun($status);
            $text->getFont()->getColor()->setARGB('FF000000');

            $sheet->getCell("I$row")->setValue($richText);
        }

        return [];
    }
}
