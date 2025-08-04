<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GateController;

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

// halaman role
Route::get('/dashboard/gate', fn()=> 'Dashboard Gate')->name('dashboard.gate')->middleware('auth');
Route::get('/dashboard/sales', fn()=> 'Dashboard Sales')->name('dashboard.sales')->middleware('auth');
Route::get('/', fn()=> 'Homepage')->name('home');

// Dashboard berdasarkan role
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');
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
});

Route::get('/dashboard/gate', function () {
    return view('gate.dashboard');
})->middleware(['auth','role:gate'])->name('dashboard.gate');
Route::get('/inputlead/gate', [GateController::class, 'inputLead'])
    ->middleware(['auth','role:gate'])
    ->name('inputlead.gate');

Route::get('/dashboard/sales', function () {
    return view('sales.dashboard');
})->middleware(['auth','role:sales'])->name('dashboard.sales');

Route::get('/', fn()=> 'Homepage')->name('home');
