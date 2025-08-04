<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\SubKategori;

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
}
