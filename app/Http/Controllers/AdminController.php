<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\Produk;
use App\Models\Lead;
use App\Models\User;
use App\Imports\ProdukImport;
use App\Exports\ProdukExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeadExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
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
            return view('admin.lead._table', compact('lead'))->render();
        }
    
        $user = User::where('ROLE', 'sales')
            ->whereNull('DELETED_AT')
            ->get(['ID_USER', 'NAMA']);
    
        return view('admin.lead.datalead', compact('lead', 'user'));
    }

    public function exportLead(Request $request)
    {
        $filters = $request->only(['search', 'sales', 'source', 'startDate', 'endDate']);
        return Excel::download(new LeadExport($filters), 'lead_export.xlsx');
    }

    

}
