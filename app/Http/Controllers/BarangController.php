<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Barang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();

        return response()->json([
            'message' => 'Daftar barang milik pembeli',
            'data' => $barang
        ], 200);
    }

    public function store(Request $request)
    {
        $barangData = $request->all();

        $validate = Validator::make($barangData, [
            'nama_barang' => 'required|max:255',
            'tgl_garansi' => 'nullable|date',
            'harga_barang' => 'required|numeric|min:0',
            'status_barang' => 'required',
            'id_kategori' => 'required',
            'desc_barang' => 'required|max:255',
        ]);

        $barangData["rating_barang"] = 0;
        $barangData["tgl_didonasikan"] = "2000-01-01";
        $barangData["tgl_terdonasi"] = "2000-01-01";

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $barang = Barang::create($barangData);

        return response([
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    public function search(string $id_barang)
    {
        $searchData = Barang::find($id_barang);

        if (!$searchData) {
            return response([
                'message' => 'Barang Not Found',
                'data' => $searchData
            ], 200);
        }

        return response([
            'message' => 'Data ditemukan',
            'data' => $searchData
        ], 200);
    }

    public function update(Request $request, string $id_barang)
    {
        $barang = Barang::find($id_barang);

        if (is_null($barang)) {
            return response([
                'message' => 'Barang Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama_barang' => 'required|max:255',
            'tgl_garansi' => 'nullable|date|after_or_equal:tgl_garansi',
            'harga_barang' => 'required|numeric|min:0',
            'desc_barang' => 'required|max:255',
            'status_barang' => 'required',

        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        if ($updateData['status_barang'] === 'didonasikan') {
            $updateData['tgl_didonasikan'] = now();
        }

        if ($updateData['status_barang'] === 'terdonasi') {
            $updateData['tgl_terdonasi'] = now();
        }

        $barang->update($updateData);

        return response([
            'message' => 'Address Update Successfully',
            'data' => $barang,
        ], 200);
    }

    public function destroy($id_barang)
    {
        $barang = Barang::find($id_barang);

        if (is_null($barang)) {
            return response([
                'message' => 'Barang Not Found',
                'data' => null
            ], 404);
        }

        if ($barang->delete()) {
            return response([
                'message' => 'Barang Deleted Successfully',
                'data' => $barang,
            ], 200);
        }

        return response([
            'message' => 'Delete Barang Failed',
            'data' => null,
        ], 400);
    }
}
