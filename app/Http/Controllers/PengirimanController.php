<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$pegawai || $pegawai->jabatan !== 'Gudang') {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Daftar pengiriman',
            'data' => $pegawai,
        ], 200);
    }


}
