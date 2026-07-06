<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\Produk;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Kota;
use App\Models\ItemTable;
use App\Models\FollowUp;
use App\Models\ReasonLost;
use App\Imports\ProdukImport;
use App\Exports\ProdukExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeadExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SPVController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date')
            ? Carbon::parse($request->get('start_date'))->startOfDay()
            : now()->startOfMonth();
    
        $endDate = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : now()->endOfDay();
    
        $salesId = $request->get('sales_id');
        $source  = $request->get('source'); // default Web
    
        // Ambil daftar sales dari seluruh lead
        $sales = User::where('ROLE', 'sales')
                    ->whereIn('ID_USER', Lead::pluck('ID_USER'))
                    ->get();
    
        $query = Lead::whereBetween('CREATED_AT', [$startDate, $endDate]);
    
        if ($salesId == 404) {
            $query->whereNull('ID_USER');
        } elseif ($salesId == 200) {
            $query->whereNotNull('ID_USER');
        } elseif (!empty($salesId)) {
            $query->where('ID_USER', $salesId);
        }
    
        if ($source) {
            $query->where('LEAD_SOURCE', $source);
        }
    
        $total      = (clone $query)->count();
        $opportunity= (clone $query)->where('STATUS', 'opportunity')->count();
        $quotation  = (clone $query)->where('STATUS', 'quotation')->count();
        $converted  = (clone $query)->where('STATUS', 'converted')->count();
        $lost       = (clone $query)->where('STATUS', 'lost')->count();
        $norespon   = (clone $query)->where('STATUS', 'norespon')->count();
        $cold       = (clone $query)->where('STATUS', 'lead')->count();
    
        return view('spv.dashboard', compact(
            'sales','salesId','source',
            'startDate','endDate',
            'total','opportunity','quotation','converted','lost','norespon','cold'
        ));
    }
    
}
