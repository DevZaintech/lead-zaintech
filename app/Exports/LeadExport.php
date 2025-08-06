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
                $this->filters['startDate'],
                $this->filters['endDate']
            ]);
        }

        $lead = $query->orderBy('LEAD_ID', 'desc')->get();

        return view('admin.lead.export_excel', compact('lead'));
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        // Border untuk semua data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:H{$lastRow}")->getBorders()->getAllBorders()
              ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // Auto-size column
        foreach (range('A','H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        return [];
    }
}
