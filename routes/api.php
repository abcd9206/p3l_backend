<?php

use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenitipanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotifikasiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    //logout
    Route::post('/logoutOrganisasi', [OrganisasiController::class, 'logout']);
    Route::post('/logoutPegawai', [PegawaiController::class, 'logout']);
    Route::post('/logoutPenitip', [PenitipController::class, 'logout']);
    Route::post('/logoutPembeli', [PembeliController::class, 'logout']);

    //edit
    Route::post('/editOrganisasi', [OrganisasiController::class, 'update']);
    Route::post('/editPegawai', [PegawaiController::class, 'update']);
    Route::post('/editPenitip', [PenitipController::class, 'update']);
    Route::post('/editPembeli', [PembeliController::class, 'update']);

    //pegawai
    Route::get('/pegawai', [PegawaiController::class, 'index']);
    Route::post('/pegawai', [PegawaiController::class, 'store']);
    Route::get('/pegawai/{id_pegawai}', [PegawaiController::class, 'search']);
    Route::post('/pegawai/{id_pegawai}', [PegawaiController::class, 'update']);
    Route::delete('/pegawai/{id_pegawai}', [PegawaiController::class, 'destroy']);

    //penitip
    Route::get('/penitip', [PenitipController::class, 'index']);
    Route::post('/penitip', [PenitipController::class, 'store']);
    Route::get('/penitip/{id_penitip}', [PenitipController::class, 'search']);
    Route::post('/penitip/{id_penitip}', [PenitipController::class, 'update']);
    Route::post('/penitip/{id_penitip}', [PenitipController::class, 'updatePegawai']);
    Route::delete('/penitip/{id_penitip}', [PenitipController::class, 'destroy']);

    //barang
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::get('/barang/{id_barang}', [BarangController::class, 'search']);
    Route::post('/barang/{id_barang}', [BarangController::class, 'update']);
    Route::delete('/barang/{id_barang}', [BarangController::class, 'destroy']);

    //penitipan
    Route::get('/penitipan', [PenitipanController::class, 'index']);
    Route::post('/penitipan', [PenitipanController::class, 'store']);
    Route::get('/penitipan/{id_penitipan}', [PenitipanController::class, 'search']);
    Route::post('/penitipan/{id_penitipan}', [PenitipanController::class, 'update']);
    Route::delete('/penitipan/{id_penitipan}', [PenitipanController::class, 'destroy']);

    //pembelian
    Route::get('/pembelian', [PembelianController::class, 'index']);
    Route::post('/pembelian', [PembelianController::class, 'store']);
    Route::get('/pembelian/{id_pemebelian}', [PembelianController::class, 'search']);
    Route::post('/pembelian/{id_pembelian}', [PembelianController::class, 'updatePembeli']);
    Route::post('/pembelian/{id_pembelian}', [PembelianController::class, 'updatePegawai']);
    Route::delete('/pembelian/{id_pembelian}', [PembelianController::class, 'destroy']);

    //kategori
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::post('/kategori', [KategoriController::class, 'store']);

    //pengiriman
    Route::get('/pengiriman', [PengirimanController::class, 'index']);
    Route::post('/pengiriman', [PengirimanController::class, 'store']);
    Route::get('/pengiriman/{id_pengiriman}', [PengirimanController::class, 'search']);
    Route::post('/pengiriman/{id_pengiriman}', [PengirimanController::class, 'update']);

    //alamat
    Route::get('/alamat', [AlamatController::class, 'index']);
    Route::post('/alamat', [AlamatController::class, 'store']);
    Route::get('/alamat/{id_alamat}', [AlamatController::class, 'search']);
    Route::post('/alamat/{id_alamat}', [AlamatController::class, 'update']);
    Route::delete('/alamat/{id_alamat}', [AlamatController::class, 'destroy']);

    //notifikasi
    Route::post('/notifikasi', [NotifikasiController::class, 'kirimKeUser']);
    
});

//register
Route::post('/registerOrganisasi', [OrganisasiController::class, 'register']);
Route::post('/registerPembeli', [PembeliController::class, 'register']);

//login
Route::post('/loginOrganisasi', [OrganisasiController::class, 'login']);
Route::post('/loginPegawai', [PegawaiController::class, 'login']);
Route::post('/loginPenitip', [PenitipController::class, 'login']);
Route::post('/loginPembeli', [PembeliController::class, 'login']);