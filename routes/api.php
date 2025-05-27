<?php

use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    //logout
    Route::post('/logoutOrganisasi', [OrganisasiController::class, 'logout']);
    Route::post('/logoutPegawai', [PegawaiController::class, 'logout']);
    Route::post('/logoutPenitip', [PenitipController::class, 'logout']);
    Route::post('/logoutPembeli', [PembeliController::class, 'logout']);

    //login
    Route::post('loginOrganisasi', [OrganisasiController::class, 'login']);
    Route::post('loginPegawai', [PegawaiController::class, 'login']);
    Route::post('loginPenitip', [PenitipController::class, 'login']);
    Route::post('loginPembeli', [PembeliController::class, 'login']);

    //edit
    Route::post('editOrganisasi', [OrganisasiController::class, 'update']);
    Route::post('editPegawai', [PegawaiController::class, 'update']);
    Route::post('editPenitip', [PenitipController::class, 'update']);
    Route::post('editPembeli', [PembeliController::class, 'update']);
});

//register
Route::post('/registerOrganisasi', [OrganisasiController::class, 'register']);
Route::post('/registerPegawai', [PegawaiController::class, 'register']);
Route::post('/registerPenitip', [PenitipController::class, 'register']);
Route::post('/registerPembeli', [PembeliController::class, 'register']);