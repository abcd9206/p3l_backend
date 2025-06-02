<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Alamat;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    public function index()
    {
        $pembeli = Auth::user();
        $user = Pembeli::find($pembeli->id_pembeli);

        if (!$user) {
            return response(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $alamat = Alamat::where('id_pembeli', $pembeli->id_pembeli)->get();

        return response()->json([
            'message' => 'Daftar alamat milik pembeli',
            'data' => $alamat
        ], 200);
    }

    public function store(Request $request)
    {
        $pembeli = Auth::user();
        $user = Pembeli::find($pembeli->id_pembeli);

        if (!$user) {
            return response(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $alamatData = $request->all();

        $validate = Validator::make($alamatData, [
            'alamat_pembeli' => 'required|max:255',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $alamatData['id_pembeli'] = $pembeli->id_pembeli;

        $alamat = Alamat::create($alamatData);

        return response([
            'message' => 'Alamat berhasil ditambahkan',
            'data' => $alamat
        ], 201);
    }

    public function search(string $id_alamat)
    {
        $pembeli = Auth::user();
        $user = Pembeli::find($pembeli->id_pembeli);

        if (!$user) {
            return response(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $searchData = Alamat::find($id_alamat);

        if (!$searchData) {
            return response([
                'message' => 'Address Not Found',
                'data' => $searchData
            ], 200);
        }
    }

    public function update(Request $request, string $id_alamat)
    {
        $pembeli = Auth::user();
        $user = Pembeli::find($pembeli->id_pembeli);

        if (!$user) {
            return response(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $alamat = Alamat::find($id_alamat);

        if (is_null($alamat)) {
            return response([
                'message' => 'Address Not Found',
                'data' => null
            ], 404);
        }

        // FIX INI:
        if ($alamat->id_pembeli !== $pembeli->id_pembeli) {
            return response([
                'message' => 'Unauthorized: This address is not yours.',
            ], 403);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'alamat_pembeli' => 'required|max:255',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $alamat->update($updateData);

        return response([
            'message' => 'Address Update Successfully',
            'data' => $alamat,
        ], 200);
    }


    public function destroy($id_alamat)
    {
        $alamat = Alamat::find($id_alamat);

        if (is_null($alamat)) {
            return response([
                'message' => 'Alamat Not Found',
                'data' => null
            ], 404);
        }

        if ($alamat->delete()) {
            return response([
                'message' => 'Alamat Deleted Successfully',
                'data' => $alamat,
            ], 200);
        }

        return response([
            'message' => 'Delete Alamat Failed',
            'data' => null,
        ], 400);
    }
}
