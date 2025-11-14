<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\Produk;
use App\Models\Lead;
use App\Models\User;
use App\Models\Kota;
use App\Models\Opportunity;
use App\Models\ItemTable;
use App\Models\Quotation;
use App\Models\FollowUp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date')
            ? Carbon::parse($request->get('start_date'))->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : now()->endOfDay();

        // ambil filter lead source, default null = semua
        $source = $request->get('source');

        $query = Lead::where('ID_USER', Auth::id())
                    ->whereBetween('CREATED_AT', [$startDate, $endDate]);

        if (!empty($source)) {
            $query->where('LEAD_SOURCE', $source);
        }

        $total      = (clone $query)->count();
        $opportunity= (clone $query)->where('STATUS', 'opportunity')->count();
        $quotation  = (clone $query)->where('STATUS', 'quotation')->count();
        $converted  = (clone $query)->where('STATUS', 'converted')->count();
        $lost        = (clone $query)->where('STATUS', 'lost')->count();
        $cold       = (clone $query)->where('STATUS', 'lead')->count();

        return view('sales.dashboard', compact(
            'source','startDate','endDate',
            'total','opportunity','quotation','converted','lost', 'cold'
        ));
    }

    public function dataLead(Request $request)
    {
        $search     = $request->get('search');
        $sales      = $request->get('sales');
        $source     = $request->get('source');
        $status     = $request->get('status');
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');
        $follow     = $request->get('follow');
    
        $lead = Lead::with(['sub_kategori', 'kota', 'opportunities'])
            ->whereNull('DELETED_AT')
            ->when($sales, function ($q) use ($sales) {
                if ($sales == 'me') {
                    $q->where('ID_USER', auth()->user()->ID_USER);
                } else {
                    $q->where('ID_USER', $sales);
                }
            }, function ($q) {
                $q->where('ID_USER', auth()->user()->ID_USER);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('LEAD_ID', 'like', "%{$search}%")
                        ->orWhere('NAMA', 'like', "%{$search}%")
                        ->orWhere('NO_TELP', 'like', "%{$search}%")
                        ->orWhere('PERUSAHAAN', 'like', "%{$search}%")
                        ->orWhereHas('sub_kategori', function ($sub) use ($search) {
                            $sub->where('NAMA', 'like', "%{$search}%");
                        })
                        ->orWhereHas('kota', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($source, function ($q) use ($source) {
                $q->where('LEAD_SOURCE', $source);
            })
            ->when($status, function ($q) use ($status) {
                if ($status === 'opportunity') { // Warm
                    $q->where('STATUS', 'opportunity')
                      ->whereHas('opportunities', function($op) {
                          $op->where('PROSENTASE_PROSPECT', '>', 10)
                             ->where('PROSENTASE_PROSPECT', '<=', 50);
                      });
                } elseif ($status === 'lead') { // Cold
                    $q->where(function($query) {
                        $query->where('STATUS', 'lead')
                              ->orWhereHas('opportunities', function($op) {
                                  $op->where('PROSENTASE_PROSPECT', '<=', 10);
                              })
                              ->orWhereDoesntHave('opportunities');
                    })->whereNotIn('STATUS', ['norespon', 'lost']);
                } elseif ($status === 'quotation') { // Hot
                    $q->where(function ($q) {
                        $q->where('STATUS', 'quotation')
                          ->orWhere(function ($q) {
                              $q->where('STATUS', 'opportunity')
                                ->whereHas('opportunities', function ($op) {
                                    $op->where('PROSENTASE_PROSPECT', '>', 50);
                                });
                          });
                    });
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
        
            // ================= FOLLOW UP =================
            ->addSelect([
                'fu_lead_count' => FollowUp::selectRaw('count(*)')
                    ->whereColumn('follow_up.LEAD_ID', 'lead.LEAD_ID'),
            ])
            ->addSelect([
                'fu_opp_count' => FollowUp::selectRaw('count(*)')
                    ->whereIn('follow_up.OPPORTUNITY_ID', function ($sub) {
                        $sub->select('opportunity.OPPORTUNITY_ID')
                            ->from('opportunity')
                            ->whereColumn('opportunity.LEAD_ID', 'lead.LEAD_ID');
                    }),
            ])
            ->addSelect(DB::raw('( 
                (select count(*) from follow_up where follow_up.LEAD_ID = lead.LEAD_ID) 
                + 
                (select count(*) from follow_up where follow_up.OPPORTUNITY_ID in 
                    (select opportunity.OPPORTUNITY_ID from opportunity where opportunity.LEAD_ID = lead.LEAD_ID)
                )
            ) as total_fu'))
            ->when($follow, function ($q) use ($follow) {
                if ($follow == 9) {
                    $q->having('total_fu', '<', 1);
                } elseif ($follow == 1) {
                    $q->having('total_fu', '=', 1);
                } elseif ($follow == 2) {
                    $q->having('total_fu', '=', 2);
                } elseif ($follow == 3) {
                    $q->having('total_fu', '>=', 3);
                }
            })
            // =============================================
        
            ->orderBy('LEAD_ID', 'desc')
            ->paginate(100)->withQueryString();
    
        if ($request->ajax()) {
            return view('sales.lead._table', compact('lead'))->render();
        }
    
        $user = User::where('ROLE', 'sales')
            ->whereNull('DELETED_AT')
            ->where('ID_USER', '!=', auth()->user()->ID_USER)
            ->get(['ID_USER', 'NAMA']);
    
        return view('sales.lead.datalead', compact('lead', 'user'));
    }
    
    

    public function inputLead()
    {
        $subkategori = SubKategori::whereNull('DELETED_AT')
             ->get();

        return view('sales.lead.inputlead', compact('subkategori'));
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

    public function storeLead(Request $request)
    {
        // Validasi sesuai kondisi
        $request->validate([
            'LEAD_SOURCE' => 'required',
            'NO_TELP'     => 'required|numeric|min:10000000', // min 8 digit
        ], [
            'LEAD_SOURCE.required' => 'Sumber Lead wajib dipilih',
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
            return redirect()->route('inputlead.sales')
                            ->with('error', 'Lead dengan nomor telepon yang sama sudah ada!');
        }

        // === Generate LEAD_ID ===
        // Ambil tanggal dari CREATED_AT (kalau diisi), kalau tidak pakai now()
        $createdAt = $request->CREATED_AT ? Carbon::parse($request->CREATED_AT) : now();
        $today     = $createdAt->format('Ymd');

        $prefix = "LEAD-{$today}-";

        // Cari nomor urut terakhir untuk tanggal tsb
        $lastLead = Lead::where('LEAD_ID', 'like', $prefix.'%')
                        ->orderBy('LEAD_ID', 'desc')
                        ->first();

        if ($lastLead) {
            $lastNumber  = (int) substr($lastLead->LEAD_ID, -4);
            $nextNumber  = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber  = '0001';
        }

        $LEAD_ID = $prefix.$nextNumber;

        $creatorId = Auth::user()->ID_USER;  // jika nama kolom user kamu "ID_USER"
        $creatorId = Auth::user()->ID_USER;  // jika nama kolom user kamu "ID_USER"
        
        // Simpan data ke tabel lead
        Lead::create([
            'LEAD_ID'       => $LEAD_ID,
            'ID_SUB'        => $request->KEBUTUHAN,
            'ID_USER'       => Auth::user()->ID_USER,
            'NAMA'          => $request->NAMA,
            'PERUSAHAAN'    => $request->PERUSAHAAN,
            'KATEGORI'      => $request->KATEGORI,
            'kode_kota'     => $request->kode_kota,
            'NO_TELP'       => $request->NO_TELP,
            'EMAIL'         => $request->EMAIL,
            'STATUS'        => 'lead',
            'LEAD_SOURCE'   => $request->LEAD_SOURCE,
            'NOTE'          => $request->NOTE,
            'CREATED_AT' => $request->CREATED_AT ?? now(),
            'CREATOR_ID'    => $creatorId,
            // kolom tambahan sesuai kebutuhan
        ]);
        return redirect()->route('opportunity.create', ['lead_id' => $LEAD_ID]);
    }

    public function createOpportunity($lead_id)
    {
        $lead = Lead::where('LEAD_ID', $lead_id)->firstOrFail();
        $followups = $lead->followUps()->get();
        return view('sales.opportunity.create', compact('lead','followups'));
    }
    
    public function getProdukSales(Request $request)
    {
        $search = $request->get('q');
    
        $query = \App\Models\Produk::query()
            ->whereNull('DELETED_AT');
    
        if (!empty($search)) {
            $query->where('NAMA', 'like', "%{$search}%");
        }
    
        $produk = $query->orderBy('NAMA')
                        ->limit(50)
                        ->get(['ID_PRODUK', 'NAMA', 'SKU' , 'HARGA']);
    
        return response()->json($produk);
    }
    
    public function storeOpportunity(Request $request)
    {
        // Validasi dasar (sesuaikan pesan/aturan bila perlu)
        $request->validate([
            'LEAD_ID' => 'required',
            'NILAI_PROSPECT' => 'nullable|numeric',
            'PROSENTASE_PROSPECT' => 'nullable|numeric|min:0|max:100',
            'NOTE' => 'nullable|string',
            'produk' => 'required|array|min:1',
            'produk.*.ID_PRODUK' => 'required',
            'produk.*.QTY' => 'required|integer|min:1',
            'produk.*.PRICE' => 'required|numeric|min:0',
            'produk.*.TOTAL' => 'required|numeric|min:0',
        ]);

        try {
            // Gunakan transaksi supaya semua insert atomik
            $opportunity = DB::transaction(function () use ($request) {
                // 1) Generate OPPORTUNITY_ID -> OPP-yyyymmdd-0001 (reset tiap hari)
                $today = Carbon::now()->format('Ymd');
                $prefix = 'OPP-' . $today . '-';

                // Ambil ID terakhir yang sesuai hari ini (urut desc)
                $lastId = DB::table('opportunity')
                    ->where('OPPORTUNITY_ID', 'like', $prefix . '%')
                    ->orderBy('OPPORTUNITY_ID', 'desc')
                    ->value('OPPORTUNITY_ID');

                $nextSeq = 1;
                if ($lastId) {
                    // ambil angka di belakang dash terakhir
                    $lastSeq = intval(substr($lastId, strrpos($lastId, '-') + 1));
                    $nextSeq = $lastSeq + 1;
                }
                $seqStr = str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
                $opportunityId = $prefix . $seqStr;

                // 2) Simpan Opportunity (manual timestamps)
                $now = Carbon::now()->toDateTimeString();

                $op = Opportunity::create([
                    'OPPORTUNITY_ID' => $opportunityId,
                    'LEAD_ID' => $request->input('LEAD_ID'),
                    // NILAI_PROSPECT, PROSENTASE_PROSPECT sudah dikirim sebagai raw numeric dari form (script frontend)
                    'NILAI_PROSPECT' => $request->input('NILAI_PROSPECT', 0),
                    'PROSENTASE_PROSPECT' => $request->input('PROSENTASE_PROSPECT', 0),
                    'NOTE' => $request->input('NOTE'),
                    'CREATED_AT' => $now,
                    'UPDATED_AT' => $now,
                ]);

                // 3) Simpan semua item ke item_table
                $items = $request->input('produk', []);

                foreach ($items as $row) {
                    // Sanitasi / fallback numeric
                    $qty = isset($row['QTY']) ? intval($row['QTY']) : 0;
                    $price = isset($row['PRICE']) ? intval($row['PRICE']) : 0;
                    $total = isset($row['TOTAL']) ? intval($row['TOTAL']) : ($qty * $price);

                    ItemTable::create([
                        'OPPORTUNITY_ID' => $opportunityId,
                        'ID_PRODUK' => $row['ID_PRODUK'],
                        'QTY' => $qty,
                        'PRICE' => $price,
                        'TOTAL' => $total,
                        'CREATED_AT' => $now,
                        'UPDATED_AT' => $now,
                    ]);
                }

                return $op;
            });

            $lead = Lead::where('LEAD_ID', $request->input('LEAD_ID'))->update([
                'STATUS' => 'opportunity',
                'UPDATED_AT' => now(),
            ]);

            return redirect()->route('quotation.create', ['id' => $opportunity->OPPORTUNITY_ID])
                ->with('success', 'Opportunity tersimpan: ' . $opportunity->OPPORTUNITY_ID);
        } catch (Exception $e) {
            // Pada kegagalan, kembalikan input dan tampilkan error singkat
            return back()->withInput()
                         ->withErrors(['error' => 'Gagal menyimpan opportunity: ' . $e->getMessage()]);
        }
    }

    public function opportunity(Request $request)
    {
        $search     = $request->get('search');
        $source     = $request->get('source');
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');
    
        $opp = Opportunity::with(['lead.sub_kategori', 'lead.kota']) // eager load kota juga
        ->whereNull('DELETED_AT')
    
        // Filter ID_USER dari tabel lead
        ->whereHas('lead', function ($q) {
            $q->where('ID_USER', auth()->user()->ID_USER);
        })
    
        // Pencarian
        ->when($search, function ($q) use ($search) {
            $q->where(function ($query) use ($search) {
                $query->where('OPPORTUNITY_ID', 'like', "%{$search}%")
                      ->orWhere('LEAD_ID', 'like', "%{$search}%")
                      ->orWhereHas('lead', function ($lead) use ($search) {
                          $lead->where('NAMA', 'like', "%{$search}%")
                               ->orWhere('PERUSAHAAN', 'like', "%{$search}%")
                               ->orWhere('KOTA', 'like', "%{$search}%")
                               ->orWhere('NO_TELP', 'like', "%{$search}%")
                               // Cari juga di tabel sub_kategori
                               ->orWhereHas('sub_kategori', function ($sub) use ($search) {
                                   $sub->where('NAMA', 'like', "%{$search}%");
                               })
                               // Cari di tabel reg_regencies (nama kota)
                               ->orWhereHas('kota', function ($k) use ($search) {
                                   $k->where('name', 'like', "%{$search}%");
                               });
                      });
            });
        })
    
        // Filter LEAD_SOURCE dari tabel lead
        ->when($source, function ($q) use ($source) {
            $q->whereHas('lead', function ($lead) use ($source) {
                $lead->where('LEAD_SOURCE', $source);
            });
        })
    
        // Filter tanggal dari CREATED_AT opportunity
        ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
            $start = $startDate . ' 00:00:00';
            $end   = $endDate . ' 23:59:59';
            $q->whereBetween('CREATED_AT', [$start, $end]);
        })
    
        ->orderBy('OPPORTUNITY_ID', 'desc')
        ->paginate(15);
    
    if ($request->ajax()) {
        return view('sales.opportunity._table', compact('opp'))->render();
    }
    
        
        return view('sales.opportunity.viewopp', compact('opp'));
    }

    public function updateOpportunity(Request $request)
    {
        
        $request->validate([
            'OPPORTUNITY_ID' => 'required',
            // 'produk'         => 'required|array|min:1',
            // 'produk.*.ID_PRODUK' => 'required',
            // 'produk.*.QTY'   => 'required|integer|min:1',
            // 'produk.*.PRICE' => 'required|numeric|min:0',
            // 'produk.*.TOTAL' => 'required|numeric|min:0',
        ]);
        // dd($nilaiProspect);
    
        try {
            DB::transaction(function () use ($request) {
                $now = now()->toDateTimeString();
                $opportunityId = $request->input('OPPORTUNITY_ID');
                $nilaiProspect = preg_replace('/\D/', '', $request->input('NILAI_PROSPECT'));
                $nilaiProspect = (int) $nilaiProspect;
    
                // Update tabel opportunity
                Opportunity::where('OPPORTUNITY_ID', $opportunityId)
                    ->update([
                        'NILAI_PROSPECT'      => $nilaiProspect,
                        'PROSENTASE_PROSPECT' => (int) preg_replace('/[^0-9.]/', '', $request->input('PROSENTASE_PROSPECT')),
                        'UPDATED_AT'          => $now,
                    ]);

                // Update tabel LEAD
                Lead::where('LEAD_ID', $request->input('LEAD_ID'))
                ->update([
                    'STATUS'        => $request->input('STATUS'),
                    'REASON'        => $request->input('REASON'),
                    'UPDATED_AT'    => $now,
                ]);
                
    
                $items = $request->input('produk', []);
                $sentIds = []; // Track semua ID_ITEM yang diproses
    
                foreach ($items as $row) {
                    $qty   = intval($row['QTY']);
                    // $price = intval($row['PRICE']);
                    $price = (int) preg_replace('/\D/', '', $row['PRICE']);
                    // dd($price);
                    $total = intval($row['TOTAL'] ?? $qty * $price);
    
                    if (!empty($row['ID_ITEM'])) {
                        // Cek data item lama untuk update
                        $existing = ItemTable::where('ID_ITEM', $row['ID_ITEM'])
                            ->where('OPPORTUNITY_ID', $opportunityId)
                            ->exists();
    
                        if ($existing) {
                            ItemTable::where('ID_ITEM', $row['ID_ITEM'])
                                ->update([
                                    'ID_PRODUK'  => $row['ID_PRODUK'],
                                    'QTY'        => $qty,
                                    'PRICE'      => $price,
                                    'TOTAL'      => $total,
                                    'UPDATED_AT' => $now,
                                ]);
                            $sentIds[] = $row['ID_ITEM'];
                        }
                    } else {
                        // Insert produk baru
                        $newItem = ItemTable::create([
                            'OPPORTUNITY_ID' => $opportunityId,
                            'ID_PRODUK'      => $row['ID_PRODUK'],
                            'QTY'            => $qty,
                            'PRICE'          => $price,
                            'TOTAL'          => $total,
                            'CREATED_AT'     => $now,
                            'UPDATED_AT'     => $now,
                        ]);
                        $sentIds[] = $newItem->ID_ITEM;
                    }
                }
    
                // Hapus item yang tidak ada di form (dihapus user)
                ItemTable::where('OPPORTUNITY_ID', $opportunityId)
                    ->whereNotIn('ID_ITEM', $sentIds)
                    ->delete();
            });
    
            return redirect()->route('datalead.sales')->with('success', 'Opportunity terupdate');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan opportunity: ' . $e->getMessage()]);
        }
    }

    public function createQuotation($id)
    {
        $opp = Opportunity::where('OPPORTUNITY_ID', $id)->firstOrFail();
        $item = ItemTable::where('OPPORTUNITY_ID', $id)->get();
    
        // follow up dari opportunity
        $fuOpp = $opp->followUps()->get();
    
        // follow up dari lead (karena opportunity->lead)
        $fuLead = $opp->lead ? $opp->lead->followUps()->get() : collect();
    
        // gabungkan keduanya
        $followups = $fuOpp->merge($fuLead);
    
        return view('sales.quotation.create', compact('opp', 'item', 'followups'));
    }

    public function storeQuotation(Request $request)
    {
        $request->validate([
            // 'SNK' => 'required',
        ]);
    
        $today = now()->format('Ymd');
        $lastQuotation = Quotation::whereDate('CREATED_AT', now()->toDateString())
            ->orderBy('QUO_ID', 'desc')
            ->first();
        if ($lastQuotation) {
            $lastNumber = intval(substr($lastQuotation->QUO_ID, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        $newId = "QUO-{$today}-{$newNumber}";
    
        $quotation = Quotation::create([
            'QUO_ID' => $newId,
            'OPPORTUNITY_ID' => $request->OPPORTUNITY_ID,
            'SNK' => $request->SNK,
            'VALID_DATE' => $request->VALID_DATE,
            'STATUS' => $request->STATUS,
            'CREATED_AT' => now(),
            'UPDATED_AT' => now(),
        ]);
    
        try {
            DB::transaction(function () use ($request) {
                $now = now()->toDateTimeString();
                $opportunityId = $request->input('OPPORTUNITY_ID');
                $nilaiProspect = (int) preg_replace('/\D/', '', $request->input('NILAI_PROSPECT'));
                $opportunity = Opportunity::where('OPPORTUNITY_ID', $opportunityId)->first();
    
                $prosentaseInput = (int) preg_replace('/[^0-9.]/', '', $request->input('PROSENTASE_PROSPECT'));
                $prosentase = $prosentaseInput != $opportunity->PROSENTASE_PROSPECT ? $prosentaseInput : 90;
    
                $opportunity->update([
                    'NILAI_PROSPECT' => $nilaiProspect,
                    'PROSENTASE_PROSPECT' => $prosentase,
                    'UPDATED_AT' => $now,
                ]);
    
                $items = $request->input('produk', []);
                $sentIds = [];
                foreach ($items as $row) {
                    $qty = intval($row['QTY']);
                    $price = (int) preg_replace('/\D/', '', $row['PRICE']);
                    $total = intval($row['TOTAL'] ?? $qty * $price);
                    if (!empty($row['ID_ITEM'])) {
                        $existing = ItemTable::where('ID_ITEM', $row['ID_ITEM'])
                            ->where('OPPORTUNITY_ID', $opportunityId)
                            ->exists();
                        if ($existing) {
                            ItemTable::where('ID_ITEM', $row['ID_ITEM'])->update([
                                'ID_PRODUK' => $row['ID_PRODUK'],
                                'QTY' => $qty,
                                'PRICE' => $price,
                                'TOTAL' => $total,
                                'UPDATED_AT' => $now,
                            ]);
                            $sentIds[] = $row['ID_ITEM'];
                        }
                    } else {
                        $newItem = ItemTable::create([
                            'OPPORTUNITY_ID' => $opportunityId,
                            'ID_PRODUK' => $row['ID_PRODUK'],
                            'QTY' => $qty,
                            'PRICE' => $price,
                            'TOTAL' => $total,
                            'CREATED_AT' => $now,
                            'UPDATED_AT' => $now,
                        ]);
                        $sentIds[] = $newItem->ID_ITEM;
                    }
                }
                ItemTable::where('OPPORTUNITY_ID', $opportunityId)
                    ->whereNotIn('ID_ITEM', $sentIds)
                    ->delete();
            });
    
            $lead_id = Opportunity::where('OPPORTUNITY_ID', $request->input('OPPORTUNITY_ID'))->first();
            Lead::where('LEAD_ID', $lead_id->LEAD_ID)->update([
                'STATUS' => 'quotation',
                'UPDATED_AT' => now(),
            ]);
    
            return redirect()->route('datalead.sales')->with('success', 'Opportunity terupdate');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan opportunity: ' . $e->getMessage()]);
        }
    }
    


    public function quotation(Request $request)
    {
        $search     = $request->get('search');
        $source     = $request->get('source');
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');
    
        $quo = Quotation::with(['opportunity.lead'])
        ->whereNull('DELETED_AT')
    
        // Filter ID_USER dari tabel lead
        ->whereHas('opportunity.lead', function ($q) {
            $q->where('ID_USER', auth()->user()->ID_USER);
        })
    
        // Pencarian
        ->when($search, function ($q) use ($search) {
            $q->where(function ($query) use ($search) {
                $query->where('QUO_ID', 'like', "%{$search}%")
                      ->orWhere('OPPORTUNITY_ID', 'like', "%{$search}%")
                      ->orWhereHas('opportunity', function ($opportunity) use ($search) {
                          $opportunity->where('LEAD_ID', 'like', "%{$search}%")
                              ->orWhereHas('lead', function ($lead) use ($search) {
                                  $lead->where('NAMA', 'like', "%{$search}%")
                                       ->orWhere('PERUSAHAAN', 'like', "%{$search}%")
                                       ->orWhere('NO_TELP', 'like', "%{$search}%")
                                       ->orWhereHas('kota', function ($kota) use ($search) {
                                           $kota->where('name', 'like', "%{$search}%");
                                       });
                              });
                      });
            });
        })
        
    
        // Filter LEAD_SOURCE dari tabel lead
        ->when($source, function ($q) use ($source) {
            $q->whereHas('opportunity.lead', function ($lead) use ($source) {
                $lead->where('LEAD_SOURCE', $source);
            });
        })
    
        // Filter tanggal dari CREATED_AT opportunity
        ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
            $start = $startDate . ' 00:00:00';
            $end   = $endDate . ' 23:59:59';
            $q->whereBetween('CREATED_AT', [$start, $end]);
        })
    
        ->orderBy('QUO_ID', 'desc')
        ->paginate(15);
    
        if ($request->ajax()) {
            return view('sales.quotation._table', compact('quo'))->render();
        }
        
        return view('sales.quotation.viewquo', compact('quo'));
    }

    public function updateQuotation(Request $request)
    {
        $opp = Opportunity::where('OPPORTUNITY_ID', $request->input('OPPORTUNITY_ID'))->first();
    
        Quotation::where('OPPORTUNITY_ID', $request->input('OPPORTUNITY_ID'))->update([
            'SNK'        => $request->SNK,
            'UPDATED_AT' => now(),
        ]);
    
        try {
            DB::transaction(function () use ($request, $opp) {
                $now = now()->toDateTimeString();
                $opportunityId = $request->input('OPPORTUNITY_ID');
                $nilaiProspect = (int) preg_replace('/\D/', '', $request->input('NILAI_PROSPECT'));
    
                // ambil prosentase dari request
                $prosentaseInput = (int) preg_replace('/[^0-9.]/', '', $request->input('PROSENTASE_PROSPECT'));
                // cek apakah berubah
                $prosentase = $prosentaseInput != $opp->PROSENTASE_PROSPECT ? $prosentaseInput : 90;
    
                Opportunity::where('OPPORTUNITY_ID', $opportunityId)->update([
                    'NILAI_PROSPECT'      => $nilaiProspect,
                    'PROSENTASE_PROSPECT' => $prosentase,
                    'UPDATED_AT'          => $now,
                ]);
    
                $items = $request->input('produk', []);
                $sentIds = [];
    
                foreach ($items as $row) {
                    $qty   = intval($row['QTY']);
                    $price = (int) preg_replace('/\D/', '', $row['PRICE']);
                    $total = intval($row['TOTAL'] ?? $qty * $price);
    
                    if (!empty($row['ID_ITEM'])) {
                        $existing = ItemTable::where('ID_ITEM', $row['ID_ITEM'])
                            ->where('OPPORTUNITY_ID', $opportunityId)
                            ->exists();
                        if ($existing) {
                            ItemTable::where('ID_ITEM', $row['ID_ITEM'])->update([
                                'ID_PRODUK'  => $row['ID_PRODUK'],
                                'QTY'        => $qty,
                                'PRICE'      => $price,
                                'TOTAL'      => $total,
                                'UPDATED_AT' => $now,
                            ]);
                            $sentIds[] = $row['ID_ITEM'];
                        }
                    } else {
                        $newItem = ItemTable::create([
                            'OPPORTUNITY_ID' => $opportunityId,
                            'ID_PRODUK'      => $row['ID_PRODUK'],
                            'QTY'            => $qty,
                            'PRICE'          => $price,
                            'TOTAL'          => $total,
                            'CREATED_AT'     => $now,
                            'UPDATED_AT'     => $now,
                        ]);
                        $sentIds[] = $newItem->ID_ITEM;
                    }
                }
    
                ItemTable::where('OPPORTUNITY_ID', $opportunityId)
                    ->whereNotIn('ID_ITEM', $sentIds)
                    ->delete();
    
                $leadData = [
                    'STATUS'     => $request->STATUS,
                    'UPDATED_AT' => $now,
                ];
                $leadData['REASON'] = $request->STATUS == 'lost' ? $request->input('REASON') : null;
    
                Lead::where('LEAD_ID', $opp->LEAD_ID)->update($leadData);
            });
    
            return redirect()->route('datalead.sales')->with('success', 'Opportunity terupdate');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan opportunity: ' . $e->getMessage()]);
        }
    }
    
    

    public function detailQuotation($quo_id)
    {
        $quo = Quotation::where('QUO_ID', $quo_id)->firstOrFail();
    
        $validDate = Carbon::parse($quo->VALID_DATE)->toDateString();
        $today     = now()->toDateString();
    
        if ($validDate < $today) {
            $quo->update(['STATUS' => 'EXPIRED']);
        } else {
            $quo->update(['STATUS' => 'OPEN']);
        }
    
        $opp  = Opportunity::with('followups', 'lead.followUps')
                ->where('OPPORTUNITY_ID', $quo->OPPORTUNITY_ID)
                ->firstOrFail();
    
        $lead = $opp->lead;
        $item = ItemTable::where('OPPORTUNITY_ID', $quo->OPPORTUNITY_ID)->get();
    
        // gabungkan follow up dari opp + lead
        $fuOpp  = $opp->followUps()->orderBy('TGL_FOLLOW','desc')->get();
        $fuLead = $lead ? $lead->followUps()->orderBy('TGL_FOLLOW','desc')->get() : collect();
    
        $followups = $fuOpp->merge($fuLead)->sortByDesc('TGL_FOLLOW');
    
        return view('sales.quotation.detail', compact('quo','opp','lead','item','followups'));
    }
    
    
    

    public function detailLead($lead_id)
    {
        $lead = Lead::where('LEAD_ID', $lead_id)->firstOrFail();
        $user = User::all();
        if($lead->STATUS == 'opportunity'){
            $opp = Opportunity::where('LEAD_ID', $lead->LEAD_ID)->firstOrFail();
            // $item = ItemTable::where('OPPORTUNITY_ID', $opp->OPPORTUNITY_ID)->get();
            return redirect()->route('quotation.create', ['id' => $opp->OPPORTUNITY_ID]);
        }

        if (in_array($lead->STATUS, ['lost', 'converted'])){
            $opp = Opportunity::where('LEAD_ID', $lead->LEAD_ID)->firstOrFail();
            // $item = ItemTable::where('OPPORTUNITY_ID', $opp->OPPORTUNITY_ID)->get();
            return redirect()->route('opportunity.sales.detail', ['opp_id' => $opp->OPPORTUNITY_ID]);
        }
        if ($lead->STATUS == 'quotation'){
            $opp = Opportunity::where('LEAD_ID', $lead->LEAD_ID)->firstOrFail();
            $quo = Quotation::where('OPPORTUNITY_ID', $opp->OPPORTUNITY_ID)->firstOrFail();
            return redirect()->route('quotation.sales.detail', ['quo_id' => $quo->QUO_ID]);
        }

        return view('sales.lead.detail', compact('lead','user'));
    }

    public function detailOpportunity($opp_id)
    {
        $opp = Opportunity::where('OPPORTUNITY_ID', $opp_id)->firstOrFail();
        $item = ItemTable::where('OPPORTUNITY_ID', $opp_id)->get();

        return view('sales.opportunity.detail', compact('opp','item'));
    }

    public function storeFollow(Request $request)
    {
        $opportunityId = $request->input('OPPORTUNITY_ID');
        $leadId        = $request->input('LEAD_ID');
        // dd($leadId );
    
        if ($request->has('followup')) {
            foreach ($request->followup as $fu) {
                // Cocokkan nama field dengan form
                if (empty($fu['TANGGAL_FOLLOW']) && empty($fu['RESPON']) && empty($fu['PROGRESS'])) {
                    continue;
                }
    
                $data = [
                    'TGL_FOLLOW' => $fu['TANGGAL_FOLLOW'] ?? null,
                    'RESPON'     => $fu['RESPON'] ?? null,
                    'KETERANGAN' => $fu['PROGRESS'] ?? null, // progress dari form disimpan ke KETERANGAN
                    'CREATED_AT' => now(),
                    'UPDATED_AT' => now(),
                ];
    
                // pilih pakai OPPORTUNITY_ID atau LEAD_ID
                if (!empty($opportunityId)) {
                    $data['OPPORTUNITY_ID'] = $opportunityId;
                } elseif (!empty($leadId)) {
                    $data['LEAD_ID'] = $leadId;
                }
    
                FollowUp::create($data);
            }
        }
    
        return redirect()->back()->with('success', 'Follow up berhasil ditambahkan!');
    }

    public function updateFollow(Request $request, $ID_FOLLOW)
    {
        $request->validate([
            'field' => 'required|string|in:RESPON,KETERANGAN',
            'value' => 'nullable|string',
        ]);
    
        // Gunakan update langsung
        FollowUp::where('ID_FOLLOW', $ID_FOLLOW)
            ->update([$request->field => $request->value]);
    
        return response()->json(['status'=>'success']);
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

        return view('sales.lead.editlead', compact('lead','user','subkategori'));
    }

    public function updateLead(Request $request)
    {
        // Validasi sesuai kondisi
        $request->validate([
            'LEAD_SOURCE' => 'required',
            // 'STATUS' => 'required|in:lead,norespon',
            // 'USER'   => $request->STATUS == 'lead' ? 'required' : 'nullable',
            // 'NO_TELP'     => 'required|numeric|min:10000000', // min 8 digit
        ], [
            'LEAD_SOURCE.required' => 'Sumber Lead wajib dipilih',
            // 'USER.required' => 'Sales wajib dipilih jika status Lead',
            // 'STATUS.required' => 'Status wajib diisi',
            'NO_TELP.required'     => 'No. Telepon wajib diisi',
            'NO_TELP.numeric'      => 'No. Telepon hanya boleh angka',
            'NO_TELP.min'          => 'No. Telepon minimal 8 digit',
        ]);
        
        // Ambil data lead lama
        $lead = Lead::where('LEAD_ID', $request->LEAD_ID)->first();

        $data = [
            'ID_SUB'        => $request->KEBUTUHAN,
            'NAMA'          => $request->NAMA,
            'PERUSAHAAN'    => $request->PERUSAHAAN,
            'KATEGORI'      => $request->KATEGORI,
            'kode_kota'     => $request->kode_kota,
            'NO_TELP'       => $request->NO_TELP,
            'EMAIL'         => $request->EMAIL,
            'LEAD_SOURCE'   => $request->LEAD_SOURCE,
            'NOTE'          => $request->NOTE,
            'UPDATED_AT'    => now(),
        ];

        // Kalau status lama = lost, simpan reason dari request
        if ($lead && $lead->STATUS === 'lost') {
            $data['REASON'] = $request->REASON;
        }
        if ($lead && $lead->STATUS != 'lead') {
            $opp = Opportunity::where('LEAD_ID', $request->LEAD_ID)->update([
                'NOTE'=> $request->NOTE,
            ]);
        }

        $lead->update($data);

        return redirect()
        ->route('datalead.sales')
        ->with('success', 'Lead berhasil diperbarui.');
    }
    
    
    
}
