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
use App\Imports\ProdukImport;
use App\Exports\ProdukExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeadExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
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
    
        return view('admin.dashboard', compact(
            'sales','salesId','source',
            'startDate','endDate',
            'total','opportunity','quotation','converted','lost','norespon','cold'
        ));
    }
    

    // ==== Kategori ====
    public function kategoriIndex()
    {
        // Ambil hanya kategori yang belum dihapus
        $kategori = Kategori::whereNull('DELETED_AT')
            ->orderBy('ID_KATEGORI', 'desc')
            ->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function kategoriStore(Request $request)
    {
        $request->validate([
            'NAMA' => 'required|string|max:255',
        ]);

        Kategori::create([
            'NAMA' => $request->NAMA,
            'CREATED_AT' => now(),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function kategoriEdit($id)
    {
        $kategori = Kategori::where('ID_KATEGORI', $id)->whereNull('DELETED_AT')->firstOrFail();
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function kategoriUpdate(Request $request, $id)
    {
        $request->validate([
            'NAMA' => 'required|string|max:255',
        ]);

        $kategori = Kategori::where('ID_KATEGORI', $id)->whereNull('DELETED_AT')->firstOrFail();
        $kategori->update([
            'NAMA' => $request->NAMA,
            'UPDATED_AT' => now(),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function kategoriDestroy($id)
    {
        $kategori = Kategori::where('ID_KATEGORI', $id)->whereNull('DELETED_AT')->firstOrFail();
        $kategori->update(['DELETED_AT' => now()]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }

    // ==== Sub Kategori ====
    public function subkategoriIndex()
    {
        $subkategori = SubKategori::whereNull('DELETED_AT')
            ->with('kategori')
            ->orderBy('ID_SUB', 'desc')
            ->paginate(10); // <= Pagination 10 per halaman

        $kategori = Kategori::whereNull('DELETED_AT')->get();

        return view('admin.subkategori.index', compact('subkategori', 'kategori'));
    }
    
    public function subkategoriStore(Request $request)
    {
        $request->validate([
            'NAMA' => 'required|string|max:255',
            'ID_KATEGORI' => 'required|exists:kategori,ID_KATEGORI',
        ]);
    
        SubKategori::create([
            'NAMA' => $request->NAMA,
            'ID_KATEGORI' => $request->ID_KATEGORI,
            'CREATED_AT' => now(),
        ]);
    
        return redirect()->route('subkategori.index')->with('success', 'SubKategori berhasil ditambahkan');
    }
    
    public function subkategoriEdit($id)
    {
        $subkategori = SubKategori::where('ID_SUB', $id)->whereNull('DELETED_AT')->firstOrFail();
        $kategori = Kategori::whereNull('DELETED_AT')->get();
    
        return view('admin.subkategori.edit', compact('subkategori', 'kategori'));
    }
    
    public function subkategoriUpdate(Request $request, $id)
    {
        $request->validate([
            'NAMA' => 'required|string|max:255',
            'ID_KATEGORI' => 'required|exists:kategori,ID_KATEGORI',
        ]);
    
        $subkategori = SubKategori::where('ID_SUB', $id)->whereNull('DELETED_AT')->firstOrFail();
        $subkategori->update([
            'NAMA' => $request->NAMA,
            'ID_KATEGORI' => $request->ID_KATEGORI,
            'UPDATED_AT' => now(),
        ]);
    
        return redirect()->route('subkategori.index')->with('success', 'SubKategori berhasil diperbarui');
    }
    
    public function subkategoriDestroy($id)
    {
        $subkategori = SubKategori::where('ID_SUB', $id)->whereNull('DELETED_AT')->firstOrFail();
        $subkategori->update(['DELETED_AT' => now()]);
    
        return redirect()->route('subkategori.index')->with('success', 'SubKategori berhasil dihapus');
    }

    // ==== PRODUK ====
    public function produkIndex(Request $request)
    {
        $search = $request->get('search');
    
        $produk = Produk::with('subkategori.kategori')
            ->whereNull('DELETED_AT')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('SKU', 'like', "%{$search}%")
                          ->orWhere('NAMA', 'like', "%{$search}%")
                          ->orWhereHas('subkategori', function ($sub) use ($search) {
                              $sub->where('NAMA', 'like', "%{$search}%")
                                  ->orWhereHas('kategori', function ($kat) use ($search) {
                                      $kat->where('NAMA', 'like', "%{$search}%");
                                  });
                          });
                });
            })
            ->orderBy('ID_PRODUK', 'desc')
            ->paginate(15);
    
        if ($request->ajax()) {
            return view('admin.produk._table', compact('produk'))->render();
        }
    
        return view('admin.produk.index', compact('produk'));
    }
    

    public function getSubkategori(Request $request)
    {
        $search = $request->get('q');

        $subkategori = SubKategori::where('NAMA', 'like', '%' . $search . '%')
            ->limit(20)
            ->get();

        return response()->json(
            $subkategori->map(function($item) {
                return [
                    'id'   => $item->ID_SUB,
                    'text' => $item->NAMA
                ];
            })
        );
    }
    
    
    public function produkImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
    
        Excel::import(new ProdukImport, $request->file('file'));
    
        return redirect()->route('produk.index')->with('success', 'Produk berhasil diimport');
    }

    public function produkExport()
    {
        return Excel::download(new ProdukExport, 'produk-zaintech.xlsx');
    }

    public function produkStore(Request $request)
    {
        $request->validate([
            'NAMA'    => 'required|string|max:255',
            'SKU'     => 'nullable|string|max:100',
            'ID_SUB'  => 'required|exists:sub_kategori,ID_SUB',
        ], [
            'NAMA.required'   => 'Nama produk wajib diisi',
            'ID_SUB.required' => 'Sub kategori wajib dipilih',
            'ID_SUB.exists'   => 'Sub kategori tidak valid',
        ]);
    
        Produk::create([
            'NAMA'       => $request->NAMA,
            'SKU'        => $request->SKU,
            'ID_SUB'     => $request->ID_SUB,
            'IMAGE'      => null,
            'STATUS'     => 'aktif',
            'CREATED_AT' => now(),
        ]);
    
        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambah');
    }

    public function produkDestroy($id)
    {
        $produk = Produk::where('ID_PRODUK', $id)->whereNull('DELETED_AT')->firstOrFail();
        $produk->update(['DELETED_AT' => now()]);
    
        return redirect()->route('produk.index')->with('warning', 'Produk berhasil dihapus');
    }

    public function produkEdit($id)
    {
        $produk = Produk::findOrFail($id);
        $subkategori = SubKategori::all();
    
        return view('admin.produk.edit', compact('produk', 'subkategori'));
    }
    
    public function produkUpdate(Request $request, $id)
    {
        $request->validate([
            'NAMA' => 'required|string|max:255',
            'SKU' => 'required|string|max:100',
            'ID_SUB' => 'required|exists:sub_kategori,ID_SUB',
        ]);
    
        $produk = Produk::findOrFail($id);
        $produk->update([
            'NAMA' => $request->NAMA,
            'SKU' => $request->SKU,
            'ID_SUB' => $request->ID_SUB,
        ]);
    
        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // ==== LEAD ====
    public function dataLead(Request $request)
    {
        $search     = $request->get('search');
        $sales      = $request->get('sales');
        $source     = $request->get('source');
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');
        $status     = $request->get('status'); // ✅ filter STATUS baru
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
                        })
                        ->orWhereHas('kota', function ($k) use ($search) {
                            $k->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($sales, function ($q) use ($sales) {
                if ($sales == 404) {
                    // Kalau 404: ID_USER null
                    $q->whereNull('ID_USER');
                } elseif ($sales == 200) {
                    // Kalau 200: ID_USER tidak null
                    $q->whereNotNull('ID_USER');
                } else {
                    // Kalau lainnya: ID_USER sama dengan $sales
                    $q->where('ID_USER', $sales);
                }
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
            ->orderBy('LEAD_ID', 'desc')
            ->paginate(100)->withQueryString();

        if ($request->ajax()) {
            return view('admin.lead._table', compact('lead'))->render();
        }

        $user = User::where('ROLE', 'sales')
            ->whereNull('DELETED_AT')
            ->get(['ID_USER', 'NAMA']);

        return view('admin.lead.datalead', compact('lead', 'user'));
    }


    public function dataOpp(Request $request)
    {
        $search     = $request->get('search');
        $sales      = $request->get('sales');
        $source     = $request->get('source');
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');
        $myLead     = $request->get('myLead');
        
        $opp = Opportunity::with([
                'lead.sub_kategori',
                'lead.user',
                'lead.kota' // jangan lupa eager load juga biar ga N+1
            ])
            ->whereNull('DELETED_AT')

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
                                // Cari di sub_kategori
                                ->orWhereHas('sub_kategori', function ($sub) use ($search) {
                                    $sub->where('NAMA', 'like', "%{$search}%");
                                })
                                // Cari di user
                                ->orWhereHas('user', function ($u) use ($search) {
                                    $u->where('NAMA', 'like', "%{$search}%");
                                })
                                // ✅ Cari di kota (relasi lead->kota)
                                ->orWhereHas('kota', function ($k) use ($search) {
                                    $k->where('name', 'like', "%{$search}%");
                                });
                        });
                });
            })
        
            // Filter Sales
            ->when($sales, function ($q) use ($sales) {
                $q->whereHas('lead', function ($lead) use ($sales) {
                    $lead->where('ID_USER', $sales);
                });
            })
        
            // Filter Source
            ->when($source, function ($q) use ($source) {
                $q->whereHas('lead', function ($lead) use ($source) {
                    $lead->where('LEAD_SOURCE', $source);
                });
            })
        
            // Filter Tanggal
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $start = $startDate . ' 00:00:00';
                $end   = $endDate . ' 23:59:59';
                $q->whereBetween('CREATED_AT', [$start, $end]);
            })
        
            // Filter MyLead (kalau perlu)
            ->when($myLead, function ($q) {
                $q->whereHas('lead', function ($lead) {
                    $lead->where('ID_USER', auth()->id());
                });
            })
        
            ->orderBy('OPPORTUNITY_ID', 'desc')
            ->paginate(15);
        
        if ($request->ajax()) {
            return view('admin.opportunity._table', compact('opp'))->render();
        }
        
        $user = User::where('ROLE', 'sales')
            ->whereNull('DELETED_AT')
            ->get(['ID_USER', 'NAMA']);
        
        return view('admin.opportunity.dataopp', compact('opp', 'user'));
        
    }

    public function dataQuo(Request $request)
    {
        $search     = $request->get('search');
        $sales      = $request->get('sales');
        $source     = $request->get('source');
        $startDate  = $request->get('startDate');
        $endDate    = $request->get('endDate');
        $myLead     = $request->get('myLead');
        
        $quo = Quotation::with([
                'opportunity.lead.sub_kategori',
                'opportunity.lead.user',
                'opportunity.lead.kota', // ✅ eager load kota
            ])
            ->whereNull('DELETED_AT')

            // Pencarian
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('QUO_ID', 'like', "%{$search}%")
                        ->orWhereHas('opportunity.lead', function ($lead) use ($search) {
                            $lead->where('NAMA', 'like', "%{$search}%")
                                ->orWhere('PERUSAHAAN', 'like', "%{$search}%")
                                ->orWhere('KOTA', 'like', "%{$search}%")
                                ->orWhere('NO_TELP', 'like', "%{$search}%")
                                // Cari di sub_kategori
                                ->orWhereHas('sub_kategori', function ($sub) use ($search) {
                                    $sub->where('NAMA', 'like', "%{$search}%");
                                })
                                // Cari di user
                                ->orWhereHas('user', function ($u) use ($search) {
                                    $u->where('NAMA', 'like', "%{$search}%");
                                })
                                // ✅ Cari di kota (relasi lead->kota)
                                ->orWhereHas('kota', function ($k) use ($search) {
                                    $k->where('name', 'like', "%{$search}%");
                                });
                        });
                });
            })
        
            // Filter Sales
            ->when($sales, function ($q) use ($sales) {
                $q->whereHas('opportunity.lead', function ($lead) use ($sales) {
                    $lead->where('ID_USER', $sales);
                });
            })
        
            // Filter Source
            ->when($source, function ($q) use ($source) {
                $q->whereHas('opportunity.lead', function ($lead) use ($source) {
                    $lead->where('LEAD_SOURCE', $source);
                });
            })
        
            // Filter Tanggal
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $start = $startDate . ' 00:00:00';
                $end   = $endDate . ' 23:59:59';
                $q->whereBetween('CREATED_AT', [$start, $end]);
            })
        
            // Filter MyLead
            ->when($myLead, function ($q) {
                $q->whereHas('opportunity.lead', function ($lead) {
                    $lead->where('ID_USER', auth()->id());
                });
            })
        
            ->orderBy('QUO_ID', 'desc')
            ->paginate(15);
        
        if ($request->ajax()) {
            return view('admin.quotation._table', compact('quo'))->render();
        }
        
        $user = User::where('ROLE', 'sales')
            ->whereNull('DELETED_AT')
            ->get(['ID_USER', 'NAMA']);
        
        return view('admin.quotation.dataquo', compact('quo', 'user'));
        
    }

    public function detailLead($lead_id)
    {
        $lead = Lead::where('LEAD_ID', $lead_id)->firstOrFail();
        $user = User::all();
    
        // default follow kosong
        $follow = collect();
    
        if (in_array($lead->STATUS, ['opportunity', 'quotation', 'lost', 'converted'])) {
            $opp = Opportunity::where('LEAD_ID', $lead->LEAD_ID)->firstOrFail();
            $item = ItemTable::where('OPPORTUNITY_ID', $opp->OPPORTUNITY_ID)->get();
    
            // Ambil follow up yang punya salah satu atau keduanya
            $follow = FollowUp::where(function ($q) use ($lead, $opp) {
                    $q->where('LEAD_ID', $lead->LEAD_ID)
                      ->orWhere('OPPORTUNITY_ID', $opp->OPPORTUNITY_ID);
                })
                ->get();
    
            return view('admin.lead.detail', compact('lead', 'user', 'opp', 'item', 'follow'));
        }
    
        // Kalau belum ada opportunity → hanya filter LEAD_ID
        $follow = FollowUp::where('LEAD_ID', $lead->LEAD_ID)->get();
    
        return view('admin.lead.detail', compact('lead', 'user', 'follow'));
    }
    

    public function detailOpp($opp_id)
    {
        $opp = Opportunity::where('OPPORTUNITY_ID', $opp_id)->firstOrFail();
        $item = ItemTable::where('OPPORTUNITY_ID', $opp_id)->get();

        return view('admin.opportunity.detail', compact('opp','item'));
    }

    public function detailQuo($quo_id)
    {
        $quo = Quotation::where('QUO_ID', $quo_id)->firstOrFail();
    
        // Ambil hanya tanggal (abaikan jam)
        $validDate = Carbon::parse($quo->VALID_DATE)->toDateString();
        $today     = now()->toDateString();
    
        if ($validDate < $today) {
            $quo->update(['STATUS' => 'EXPIRED']);
        } else {
            $quo->update(['STATUS' => 'OPEN']);
        }
    
        $opp  = Opportunity::where('OPPORTUNITY_ID', $quo->OPPORTUNITY_ID)->firstOrFail();
        $lead = Lead::where('LEAD_ID', $opp->LEAD_ID)->firstOrFail();
        $item = ItemTable::where('OPPORTUNITY_ID', $quo->OPPORTUNITY_ID)->get();
    
        return view('admin.quotation.detail', compact('quo','opp','lead','item'));
    }

    public function exportLead(Request $request)
    {
        $filters = $request->only(['search', 'sales', 'source', 'startDate', 'endDate', 'status']);
        return Excel::download(new LeadExport($filters), 'lead_export.xlsx');
    }


    public function getUser()
    {
        $users = User::all(); // Ambil semua user
        return view('admin.users.getuser', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'NAMA' => 'required|string|max:255',
            'EMAIL' => 'required|email|unique:user,EMAIL',
            'PASSWORD' => 'required|string',
            'ROLE' => 'required|string',
        ]);

        User::create([
            'NAMA'       => $request->NAMA,
            'EMAIL'      => $request->EMAIL,
            'PASSWORD'   => $request->PASSWORD, // kalau mau hash => bcrypt($request->PASSWORD)
            'ROLE'       => $request->ROLE,
            'CREATED_AT' => Carbon::now(),
            'UPDATED_AT' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'ID_USER'   => 'required',
            'NAMA'      => 'required|string|max:255',
            'EMAIL'     => 'required|email|max:255',
            'PASSWORD'  => 'required|string|max:255',
            'ROLE'      => 'required|in:admin,gate,sales',
        ]);
    
        $user = User::find($request->ID_USER);
    
        if ($user) {
            $user->NAMA       = $request->NAMA;
            $user->EMAIL      = $request->EMAIL;
            $user->PASSWORD   = $request->PASSWORD; // tidak di-hash
            $user->ROLE       = $request->ROLE;
            $user->UPDATED_AT = now();
            $user->save();
    
            return back()->with('success', 'User berhasil diupdate!');
        }
    
        return back()->with('error', 'User tidak ditemukan.');
    }

    public function nonaktifUser(Request $request)
    {
        $user = User::findOrFail($request->ID_USER);

        // Tandai user sebagai nonaktif
        $user->update([
            'DELETED_AT' => now(),
        ]);

        return redirect()->route('getuser.admin')->with('success', 'User berhasil dinonaktifkan');
    }


}
