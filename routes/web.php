<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])
    ->name('profile.update');

// halaman role
Route::get('/dashboard/gate', fn()=> 'Dashboard Gate')->name('dashboard.gate')->middleware('auth');
Route::get('/dashboard/sales', fn()=> 'Dashboard Sales')->name('dashboard.sales')->middleware('auth');
Route::get('/', fn()=> 'Homepage')->name('home');

// Dashboard berdasarkan role
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/chart-admin', [AdminController::class, 'filterChart'])->name('chart.admin');
    Route::get('/kategori', [AdminController::class, 'kategoriIndex'])->name('kategori.index');
    Route::post('/kategori', [AdminController::class, 'kategoriStore'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [AdminController::class, 'kategoriEdit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [AdminController::class, 'kategoriUpdate'])->name('kategori.update');
    Route::delete('/kategori/{id}', [AdminController::class, 'kategoriDestroy'])->name('kategori.destroy');

    Route::get('/subkategori', [AdminController::class, 'subkategoriIndex'])->name('subkategori.index');
    Route::post('/subkategori', [AdminController::class, 'subkategoriStore'])->name('subkategori.store');
    Route::get('/subkategori/{id}/edit', [AdminController::class, 'subkategoriEdit'])->name('subkategori.edit');
    Route::put('/subkategori/{id}', [AdminController::class, 'subkategoriUpdate'])->name('subkategori.update');
    Route::delete('/subkategori/{id}', [AdminController::class, 'subkategoriDestroy'])->name('subkategori.destroy');

    Route::get('/produk', [AdminController::class, 'produkIndex'])->name('produk.index');
    Route::post('/produk/import', [AdminController::class, 'produkImport'])->name('produk.import');
    Route::get('/produk/export', [AdminController::class, 'produkExport'])->name('produk.export');
    Route::post('/produk/store', [AdminController::class, 'produkStore'])->name('produk.store');
    Route::get('/api/subkategori', [AdminController::class, 'getSubkategori'])->name('subkategori.search');
    Route::delete('/produk/{id}', [AdminController::class, 'produkDestroy'])->name('produk.destroy');
    Route::get('/produk/{id}/edit', [AdminController::class, 'produkEdit'])->name('produk.edit');
    Route::put('/produk/{id}', [AdminController::class, 'produkUpdate'])->name('produk.update');

    Route::get('/datalead/admin', [AdminController::class, 'dataLead'])->name('datalead.admin');
    Route::get('/exportlead/admin', [AdminController::class, 'exportLead'])->name('exportlead.admin');
    Route::get('/dataopp/admin', [AdminController::class, 'dataOpp'])->name('dataopp.admin');
    Route::get('/dataquo/admin', [AdminController::class, 'dataQuo'])->name('dataquo.admin');
    Route::get('/datalead/{lead_id}/detail', [AdminController::class, 'detailLead'])->name('lead.admin.detail');
    Route::get('/dataopp/{opp_id}/detail', [AdminController::class, 'detailOpp'])->name('opp.admin.detail');
    Route::get('/dataquo/{quo_id}/detail', [AdminController::class, 'detailQuo'])->name('dataquo.admin.detail');

});

Route::middleware(['auth','role:gate'])->group(function () {
    Route::get('/dashboard/gate', [GateController::class, 'index'])->name('dashboard.gate');
    Route::get('/dashboard/chart-gate', [GateController::class, 'filterChart'])->name('chart.gate');
    Route::get('/inputlead/gate', [GateController::class, 'inputLead'])->name('inputlead.gate');
    Route::post('/storelead/gate', [GateController::class, 'storeLead'])->name('storelead.gate');
    Route::get('/get-subkategori/gate', [GateController::class, 'getSubkategori'])->name('get.subkategori.gate');
    Route::get('/get-kota/gate', [GateController::class, 'getKota'])->name('get.kota.gate');

    Route::get('/datalead/gate', [GateController::class, 'dataLead'])->name('datalead.gate');
    Route::get('/datalead/detail-{lead_id}/gate', [GateController::class, 'detailLead'])->name('lead.gate.detail');
    Route::get('/editlead/{lead_id}/gate', [GateController::class, 'editLead'])->name('edit.lead.gate');
    Route::post('/updatelead/gate', [GateController::class, 'updateLead'])->name('updatelead.gate');
});

Route::middleware(['auth','role:sales'])->group(function () {
    Route::get('/dashboard/sales', [SalesController::class, 'index'])->name('dashboard.sales');
    Route::get('/datalead/sales', [SalesController::class, 'dataLead'])->name('datalead.sales');
    Route::get('/get-kota/sales', [SalesController::class, 'getKota'])->name('get.kota.sales');
    Route::get('/inputlead/sales', [SalesController::class, 'inputLead'])->name('inputlead.sales');
    Route::post('/storelead/sales', [SalesController::class, 'storeLead'])->name('storelead.sales');
    Route::get('/get-subkategori/sales', [SalesController::class, 'getSubkategori'])->name('get.subkategori.sales');
    Route::get('/opportunity/create/{lead_id}', [SalesController::class, 'createOpportunity'])->name('opportunity.create');
    Route::post('/opportunity/store', [SalesController::class, 'storeOpportunity'])->name('opportunity.store');
    Route::get('/produk-sales', [SalesController::class, 'getProdukSales'])->name('get.produk.sales');

    Route::get('/opportunity/sales', [SalesController::class, 'opportunity'])->name('opportunity.sales');
    Route::post('/opportunity/update', [SalesController::class, 'updateOpportunity'])->name('opportunity.update');

    Route::get('/quotation/create/{id}', [SalesController::class, 'createQuotation'])->name('quotation.create');
    Route::post('/quotation/store', [SalesController::class, 'storeQuotation'])->name('quotation.store');
    Route::post('/quotation/update', [SalesController::class, 'updateQuotation'])->name('quotation.update');

    Route::get('/quotation/sales', [SalesController::class, 'quotation'])->name('quotation.sales');
    Route::get('/quotation/{quo_id}/detail', [SalesController::class, 'detailQuotation'])->name('quotation.sales.detail');
    Route::get('/datalead/detail-{lead_id}/sales', [SalesController::class, 'detailLead'])->name('lead.sales.detail');
    Route::get('/opportunity/{opp_id}/detail', [SalesController::class, 'detailOpportunity'])->name('opportunity.sales.detail');

});


Route::get('/', fn()=> 'Homepage')->name('home');
