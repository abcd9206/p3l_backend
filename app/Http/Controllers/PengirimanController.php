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
            'status_pengiriman' => 'required',
            'id_pegawai' => 'nullable|exists:pegawais,id_pegawai',
            'id_alamat' => 'required|exists:alamats,id_alamat',
            'id_pembelian' => 'required|exists:pembelians,id_pembelian',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $pengirimanData = Pengiriman::create([
            'status_pengiriman' => $request->status_pengiriman,
            'id_pegawai' => $request->id_pegawai,
            'id_alamat' => $request->id_alamat,
            'id_pembelian' => $request->id_pembelian,
        ]);

        return response([
            'message' => 'Pengiriman berhasil ditambahkan',
            'data' => $pengirimanData,
        ], 201);
    }

    public function update(Request $request, string $id_pengiriman)
    {
        $pengiriman = Pengiriman::find($id_pengiriman);

        if (is_null($pengiriman)) {
            return response([
                'message' => 'Pengiriman Not Found',
                'data' => null
            ], 404);
        }

        if ($pengiriman->id_pengiriman !== $pengiriman->id_pengiriman) {
            return response([
                'message' => 'Unauthorized: This Orders is not yours.',
            ], 403);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'status_pengiriman' => 'required|max:255',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $pengiriman->update($updateData);

        return response([
            'message' => 'Address Update Successfully',
            'data' => $pengiriman,
        ], 200);
    }

    public function destroy($id_pengiriman)
    {
        $pengiriman = Penitipan::find($id_pengiriman);

        if (is_null($pengiriman)) {
            return response([
                'message' => 'Pengiriman Not Found',
                'data' => null
            ], 404);
        }

        if ($pengiriman->delete()) {
            return response([
                'message' => 'Pengiriman Deleted Successfully',
                'data' => $pengiriman,
            ], 200);
        }

        return response([
            'message' => 'Delete pengiriman Failed',
            'data' => null,
        ], 400);
    }

}
