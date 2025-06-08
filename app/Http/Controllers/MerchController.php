<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchController extends Controller
{
    public function index()
    {
        $merch = Merchandise::all();

        return response()->json([
            'message' => 'Daftar semua merchandise',
            'data' => $merch
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'nama_merch' => 'required|max:255',
            'stok' => 'required|integer|min:0',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $merch = Merchandise::create($data);

        return response([
            'message' => 'Merchandise berhasil ditambahkan',
            'data' => $merch
        ], 201);
    }

    public function search(string $id_merch)
    {
        $merch = Merchandise::find($id_merch);

        if (!$merch) {
            return response([
                'message' => 'Merchandise tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Data merchandise ditemukan',
            'data' => $merch
        ], 200);
    }

    public function update(Request $request, string $id_merch)
    {
        $merch = Merchandise::find($id_merch);

        if (!$merch) {
            return response([
                'message' => 'Merchandise tidak ditemukan',
                'data' => null
            ], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'nama_merch' => 'required|max:255',
            'stok' => 'required|integer|min:0',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $merch->update($data);

        return response([
            'message' => 'Merchandise berhasil diupdate',
            'data' => $merch
        ], 200);
    }

    public function destroy($id_merch)
    {
        $merch = Merchandise::find($id_merch);

        if (!$merch) {
            return response([
                'message' => 'Merchandise tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($merch->delete()) {
            return response([
                'message' => 'Merchandise berhasil dihapus',
                'data' => $merch
            ], 200);
        }

        return response([
            'message' => 'Gagal menghapus merchandise',
            'data' => null
        ], 400);
    }
}
