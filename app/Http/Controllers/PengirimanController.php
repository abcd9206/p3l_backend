<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengiriman;
use App\Models\Pegawai;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengirimanController extends Controller
{
    public function index()
    {
        $pengiriman = Pengiriman::all();

        return response()->json([
            'message' => 'Daftar pengiriman',
            'data' => $pengiriman,
        ], 200);
    }

    public function search(string $id_pengiriman)
    {
        $searchData = Pengiriman::find($id_pengiriman);

        if (!$searchData) {
            return response([
                'message' => 'Pengiriman Not Found',
                'data' => $searchData
            ], 200);
        }

        return response([
            'message' => 'Data ditemukan',
            'data' => $searchData
        ], 200);
    }

    public function store(Request $request)
    {
        $pengirimanData = $request->all();

        $validate = Validator::make($pengirimanData, [
            'status_pengiriman' => 'required|in:Belum Dikirim',
            'id_pegawai' => 'nullable|exists:pegawai,id_pegawai',
            'id_alamat' => 'required|exists:alamat,id_alamat',
            'id_pembelian' => 'required|exists:pembelian,id_pembelian',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $pengirimanData = Pengiriman::create([
            'status_pengiriman' => $request->status_pengiriman ?? 'Belum Dikirim',
            'id_pegawai' => $request->id_pegawai,
            'id_alamat' => $request->id_alamat,
            'id_pembelian' => $request->id_pembelian,
        ]);

        return response([
            'message' => 'Pengiriman berhasil ditambahkan',
            'data' => $pengirimanData,
        ], 201);
    }

}
