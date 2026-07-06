<?php

namespace App\Exports;

use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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

        if (isset($this->filters['sales'])) {
            $sales = $this->filters['sales'];
        
            if ($sales == 404) {
                $query->whereNull('ID_USER');
            } elseif ($sales == 200) {
                $query->whereNotNull('ID_USER');
            } elseif (!empty($sales)) {
                $query->where('ID_USER', $sales);
            }
        }

        if (!empty($this->filters['source'])) {
            $query->where('LEAD_SOURCE', $this->filters['source']);
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('KATEGORI_CUST', $this->filters['kategori']);
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
                        $op->where('PROSENTASE_PROSPECT', '>', 10)
                        ->where('PROSENTASE_PROSPECT', '<=', 50);
                    });

            } elseif ($status === 'lead') { // Cold
                $query->where(function($query) {
                    $query->where('STATUS', 'lead')
                        ->orWhereHas('opportunities', function($op) {
                            $op->where('PROSENTASE_PROSPECT', '<=', 10);
                        })
                        ->orWhereDoesntHave('opportunities'); // Termasuk lead tanpa opportunity
                })->whereNotIn('STATUS', ['norespon', 'lost']);

            } elseif ($status === 'quotation') { // Hot
                $query->where(function($q) {
                    // Semua quotation = Hot
                    $q->where('STATUS', 'quotation')
                      // Plus opportunity dengan prosentase > 50
                      ->orWhere(function($q2) {
                          $q2->where('STATUS', 'opportunity')
                             ->whereHas('opportunities', function($op) {
                                 $op->where('PROSENTASE_PROSPECT', '>', 50);
                             });
                      });
                });

            } elseif ($status === 'lost') {
                $query->where('STATUS', 'lost');

            } elseif ($status === 'converted') { // Deal
                $query->where('STATUS', 'converted');

            } elseif ($status === 'teroper') {
                $query->where('STATUS', '!=', 'norespon');

            } elseif ($status === 'norespon') {
                $query->where('STATUS', 'norespon');
            }
        }

        $lead = $query->orderBy('LEAD_ID', 'desc')->get();

        // 🔄 Tentukan STATUS manual
        $lead->transform(function ($item) {
            switch (strtolower($item->STATUS)) {
                case 'lead':
                    $item->STATUS = 'COLD';
                    break;
        
                case 'opportunity':
                    $opp = $item->opportunities()->latest('CREATED_AT')->first();
                    if ($opp && $opp->PROSENTASE_PROSPECT > 10 && $opp->PROSENTASE_PROSPECT <= 50) {
                        $item->STATUS = 'WARM';
                    }elseif ($opp && $opp->PROSENTASE_PROSPECT > 50) {
                        $item->STATUS = 'HOT';
                    } else {
                        $item->STATUS = 'COLD';
                    }
                    break;
        
                case 'quotation':
                    $opp = $item->opportunities()->latest('CREATED_AT')->first();
                    if ($opp && $opp->PROSENTASE_PROSPECT > 50) {
                        $item->STATUS = 'HOT';
                    } else {
                        $item->STATUS = 'COLD';
                    }
                    break;
        
                case 'converted':
                    $item->STATUS = 'DEAL';
                    break;
        
                case 'lost':
                    $item->STATUS = 'LOST';
                    break;
        
                case 'norespon':
                    $item->STATUS = 'NO RESPON';
                    break;
        
                default:
                    $item->STATUS = ucfirst($item->STATUS);
                    break;
            }
        
            return $item;
        });
        
             

        return view('admin.lead.export_excel', compact('lead'));
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
    
        // Border semua data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:K{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
        // Auto-size kolom
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    
        // Format kolom TELP menjadi Text (kolom F)
        $sheet->getStyle("F2:F{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_TEXT);
    
        // 🎨 Warna STATUS (kolom K)
        for ($row = 2; $row <= $lastRow; $row++) {
    
            $status = strtoupper($sheet->getCell("K{$row}")->getValue());
    
            $badgeColors = [
                'COLD'      => 'FF3B82F6',
                'WARM'      => 'FFFF7C00',
                'HOT'       => 'FFEF4444',
                'DEAL'      => 'FF22C55E',
                'LOST'      => 'FF9CA3AF',
                'NO RESPON' => 'FFFACC15',
            ];
    
            $badgeColor = $badgeColors[$status] ?? 'FF000000';
    
            $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
    
            $dot = $richText->createTextRun("● ");
            $dot->getFont()->getColor()->setARGB($badgeColor);
            $dot->getFont()->setBold(true);
    
            $text = $richText->createTextRun($status);
            $text->getFont()->getColor()->setARGB('FF000000');
    
            $sheet->getCell("K{$row}")->setValue($richText);
        }
    
        return [];
    }
}
