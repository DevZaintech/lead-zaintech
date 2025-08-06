<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\SubKategori;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class GateController extends Controller
{
    public function index()
    {
        return view('gate.dashboard');
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

    public function storeLead(Request $request)
    {
        // Validasi sesuai kondisi
        $request->validate([
            'LEAD_SOURCE' => 'required',
            'STATUS' => 'required|in:lead,lost',
            'USER'   => $request->STATUS === 'lead' ? 'required' : 'nullable',
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
            'KOTA'          => $request->KOTA,
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
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');
        $myLead     = $request->get('myLead');
    
        $lead = Lead::with(['sub_kategori', 'user'])
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
                          });
                });
            })
            ->when($sales, function ($q) use ($sales) {
                $q->where('ID_USER', $sales);
            })
            ->when($source, function ($q) use ($source) {
                $q->where('LEAD_SOURCE', $source);
            })
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('CREATED_AT', [$startDate, $endDate]);
            })
            ->when($myLead == 'true', function ($q) {
                $q->where('CREATOR_ID', Auth::user()->ID_USER);
            })
            ->orderBy('LEAD_ID', 'desc')
            ->paginate(15);
    
        if ($request->ajax()) {
            return view('gate.lead._table', compact('lead'))->render();
        }
    
        $user = User::where('ROLE', 'sales')
            ->whereNull('DELETED_AT')
            ->get(['ID_USER', 'NAMA']);
    
        return view('gate.lead.datalead', compact('lead', 'user'));
    }
    
    
}
