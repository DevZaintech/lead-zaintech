<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Quotation;
use App\Models\ItemTable;
use App\Models\SubKategori;
use App\Models\User;
use App\Models\Kota;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class GateController extends Controller
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
    
        // Ambil daftar sales yang ada di lead milik CREATOR_ID ini
        $sales = User::where('ROLE', 'sales')
                    ->get();
    
        $query = Lead::where('CREATOR_ID', Auth::id())
                    ->whereBetween('CREATED_AT', [$startDate, $endDate]);
    
        if ($salesId) {
            $query->where('ID_USER', $salesId);
        }
    
        $total      = (clone $query)->count();
        $opportunity= (clone $query)->where('STATUS', 'opportunity')->count();
        $quotation  = (clone $query)->where('STATUS', 'quotation')->count();
        $converted  = (clone $query)->where('STATUS', 'converted')->count();
        $lost       = (clone $query)->where('STATUS', 'lost')->count();
        $norespon   = (clone $query)->where('STATUS', 'norespon')->count(); // 👈 tambahan
        
        return view('gate.dashboard', compact(
            'sales','salesId',
            'startDate','endDate',
            'total','opportunity','quotation','converted','lost','norespon'
        ));
    }
    
    public function inputLead()
    {
        $user = User::where('ROLE', 'sales')
             ->whereNull('DELETED_AT')
             ->get();

        $subkategori = SubKategori::whereNull('DELETED_AT')
             ->get();

        return view('gate.lead.inputlead', compact('user','subkategori'));
    }

    public function getKota(Request $request)
    {
        $search = $request->get('q');
    
        $query = Kota::query();
    
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%"); // ✅ ganti ke name
        }
    
        $kota = $query->orderBy('name')
                      ->limit(50)
                      ->get([
                          'id as id',     // value yang disimpan (kode_kota)
                          'name as text'  // label yang ditampilkan
                      ]);
    
        return response()->json($kota);
    }
    
    public function storeLead(Request $request)
    {
        // Validasi sesuai kondisi
        $request->validate([
            'LEAD_SOURCE' => 'required',
            'STATUS' => 'required|in:lead,norespon',
            'USER'   => $request->STATUS == 'lead' ? 'required' : 'nullable',
            'NO_TELP'     => 'required|numeric|min:10000000', // min 8 digit
        ], [
            'LEAD_SOURCE.required' => 'Sumber Lead wajib dipilih',
            'USER.required' => 'Sales wajib dipilih jika status Lead',
            'STATUS.required' => 'Status wajib diisi',
            'NO_TELP.required'     => 'No. Telepon wajib diisi',
            'NO_TELP.numeric'      => 'No. Telepon hanya boleh angka',
            'NO_TELP.min'          => 'No. Telepon minimal 8 digit',
        ]);

        // Ambil 8 digit terakhir nomor yang diinput
        $last8 = substr($request->NO_TELP, -8);

        // Cek apakah ada nomor telepon yang 8 digit terakhirnya sama dan belum dihapus
        $cekLead = Lead::whereNull('DELETED_AT')
                    ->whereRaw('RIGHT(NO_TELP, 8) = ?', [$last8])
                    ->first();

        if ($cekLead) {
            return redirect()->route('inputlead.gate')
                            ->with('error', 'Lead dengan 8 digit akhir nomor telepon yang sama sudah ada!');
        }

        // === Generate LEAD_ID ===
        $today = Carbon::now()->format('Ymd');
        $prefix = "LEAD-{$today}-";
        // Cari nomor urut terakhir hari ini
        $lastLead = Lead::where('LEAD_ID', 'like', $prefix.'%')
                        ->orderBy('LEAD_ID', 'desc')
                        ->first();
        if ($lastLead) {
            $lastNumber = (int) substr($lastLead->LEAD_ID, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }
        $LEAD_ID = $prefix.$nextNumber;
        $creatorId = Auth::user()->ID_USER;  // jika nama kolom user kamu "ID_USER"
        
        // Simpan data ke tabel lead
        Lead::create([
            'LEAD_ID'       => $LEAD_ID,
            'ID_SUB'        => $request->KEBUTUHAN,
            'ID_USER'       => $request->USER,
            'NAMA'          => $request->NAMA,
            'PERUSAHAAN'    => $request->PERUSAHAAN,
            'KATEGORI'      => $request->KATEGORI,
            'kode_kota'     => $request->kode_kota,
            'NO_TELP'       => $request->NO_TELP,
            'EMAIL'         => $request->EMAIL,
            'STATUS'        => $request->STATUS,
            'LEAD_SOURCE'   => $request->LEAD_SOURCE,
            'NOTE'          => $request->NOTE,
            'CREATED_AT'    => now(),
            'CREATOR_ID'    => $creatorId,
            // kolom tambahan sesuai kebutuhan
        ]);
        return redirect()->route('inputlead.gate')->with('success', 'Lead berhasil disimpan');
    }

    public function getSubkategori(Request $request)
    {
        $search = $request->get('q');
    
        $query = \App\Models\SubKategori::query()
            ->whereNull('DELETED_AT'); // hanya yang aktif
    
        if (!empty($search)) {
            $query->where('NAMA', 'like', "%{$search}%");
        }
    
        $subkategori = $query->orderBy('NAMA')
                             ->limit(50) // batasi agar ringan kalau tanpa search
                             ->get(['ID_SUB as id', 'NAMA as text']);
    
        return response()->json($subkategori);
    }

    public function dataLead(Request $request)
    {
        $search     = $request->get('search');
        $sales      = $request->get('sales');
        $source     = $request->get('source');
        $status     = $request->get('status'); // ✅ tambahan
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');
    
        // ✅ kalau bukan ajax → default MyLead = true
        if ($request->ajax()) {
            $myLead = $request->get('myLead');
        } else {
            $myLead = 'true';
        }
    
        $lead = Lead::with(['sub_kategori', 'user', 'kota'])
            ->whereNull('DELETED_AT')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('LEAD_ID', 'like', "%{$search}%")
                          ->orWhere('NAMA', 'like', "%{$search}%")
                          ->orWhere('NO_TELP', 'like', "%{$search}%")
                          ->orWhereHas('sub_kategori', function ($sub) use ($search) {
                              $sub->where('NAMA', 'like', "%{$search}%");
                          })
                          ->orWhereHas('user', function ($u) use ($search) {
                              $u->where('NAMA', 'like', "%{$search}%");
                          })
                          ->orWhereHas('kota', function ($k) use ($search) {
                              $k->where('name', 'like', "%{$search}%");
                          });
                });
            })            
            ->when($sales, function ($q) use ($sales) {
                $q->where('ID_USER', $sales);
            })
            ->when($source, function ($q) use ($source) {
                $q->where('LEAD_SOURCE', $source);
            })
            ->when($status, function ($q) use ($status) {
                if ($status === 'opportunity') { // Warm
                    $q->where('STATUS', 'opportunity')
                      ->whereHas('opportunities', function($op) {
                          $op->where('PROSENTASE_PROSPECT', '>=', 50);
                      });
                } elseif ($status === 'lead') { // Cold
                    $q->where(function($query) {
                        $query->where('STATUS', 'lead')
                              ->orWhereHas('opportunities', function($op) {
                                  $op->where('PROSENTASE_PROSPECT', '<', 50);
                              })
                              ->orWhereDoesntHave('opportunities'); // Termasuk lead tanpa opportunity
                    })->where('STATUS', '!=', 'norespon');
                } elseif ($status === 'quotation') { // Hot
                    $q->where('STATUS', 'quotation');
                } elseif ($status === 'lost') {
                    $q->where('STATUS', 'lost');
                } elseif ($status === 'converted') { // Deal
                    $q->where('STATUS', 'converted');
                } elseif ($status === 'norespon') {
                    $q->where('STATUS', 'norespon');
                }
            })
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $start = $startDate . ' 00:00:00';
                $end   = $endDate . ' 23:59:59';
                $q->whereBetween('CREATED_AT', [$start, $end]);
            })
            ->when($myLead == 'true', function ($q) {
                $q->where('CREATOR_ID', Auth::user()->ID_USER);
            })
            ->orderBy('LEAD_ID', 'desc')
            ->paginate(15)->appends(request()->query());
    
        // ✅ kalau ajax → hanya balikin tabel
        if ($request->ajax()) {
            return view('gate.lead._table', compact('lead'))->render();
        }
    
        // ✅ kalau load awal → MyLead saja yang ditampilkan
        $user = User::where('ROLE', 'sales')
            ->whereNull('DELETED_AT')
            ->get(['ID_USER', 'NAMA']);
    
        return view('gate.lead.datalead', compact('lead', 'user'));
    }
    

    public function detailLead($lead_id)
    {
        $lead = Lead::where('LEAD_ID', $lead_id)->firstOrFail();
        $user = User::all();

        if (in_array($lead->STATUS, ['opportunity', 'lost', 'converted'])){
            $opp = Opportunity::where('LEAD_ID', $lead->LEAD_ID)->firstOrFail();
            $item = ItemTable::where('OPPORTUNITY_ID', $opp->OPPORTUNITY_ID)->get();
            return view('gate.lead.detail', compact('lead','user','opp','item'));
        }

        return view('gate.lead.detail', compact('lead','user'));
    }

    public function editLead($lead_id)
    {
        $lead = Lead::with(['sub_kategori', 'kota', 'user'])
            ->where('LEAD_ID', $lead_id)
            ->firstOrFail();
        $user = User::where('ROLE', 'sales')
        ->whereNull('DELETED_AT')
        ->get();

        $subkategori = SubKategori::whereNull('DELETED_AT')
        ->get();

        return view('gate.lead.editlead', compact('lead','user','subkategori'));
    }

    public function updateLead(Request $request)
    {
        // Validasi sesuai kondisi
        $request->validate([
            'LEAD_SOURCE' => 'required',
            'STATUS' => 'required|in:lead,norespon',
            'USER'   => $request->STATUS == 'lead' ? 'required' : 'nullable',
            'NO_TELP'     => 'required|numeric|min:10000000', // min 8 digit
        ], [
            'LEAD_SOURCE.required' => 'Sumber Lead wajib dipilih',
            'USER.required' => 'Sales wajib dipilih jika status Lead',
            'STATUS.required' => 'Status wajib diisi',
            'NO_TELP.required'     => 'No. Telepon wajib diisi',
            'NO_TELP.numeric'      => 'No. Telepon hanya boleh angka',
            'NO_TELP.min'          => 'No. Telepon minimal 8 digit',
        ]);
        
        // Simpan data ke tabel lead
        Lead::where('LEAD_ID', $request->LEAD_ID)->update([
            'ID_SUB'        => $request->KEBUTUHAN,
            'ID_USER'       => $request->USER,
            'NAMA'          => $request->NAMA,
            'PERUSAHAAN'    => $request->PERUSAHAAN,
            'KATEGORI'       => $request->KATEGORI,
            'kode_kota'     => $request->kode_kota,
            'NO_TELP'       => $request->NO_TELP,
            'EMAIL'         => $request->EMAIL,
            'STATUS'        => $request->STATUS,
            'LEAD_SOURCE'   => $request->LEAD_SOURCE,
            'NOTE'          => $request->NOTE,
            'UPDATED_AT'    => now(),
            // kolom tambahan sesuai kebutuhan
        ]);
        return redirect()
        ->route('lead.gate.detail', ['lead_id' => $request->LEAD_ID])
        ->with('success', 'Data berhasil diperbarui.');
    }
    
    
}
