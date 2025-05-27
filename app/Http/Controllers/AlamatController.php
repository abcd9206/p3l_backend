<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    public function index()
    {
        $pembeli = Auth::pembeli();

        $alamat = Alamat::where('id_pembeli', $pembeli->id_pembeli)->get();

        return response()->json([
            'message' => 'Daftar alamat milik pembeli',
            'data' => $alamat
        ], 200);
    }

    public function store(Request $request)
    {
        $pembeli = Auth::pembeli();

        if (!$pembeli) {
            return response(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $alamatData = $request->all();

        $validate = Validator::make($alamatData, [
            'nama_penerima' => 'required|max:100',
            'no_hp' => 'required|max:15',
            'alamat_lengkap' => 'required|max:255',
            'kode_pos' => 'required|digits:5',
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

    public function search(string $id_organisasi)
    {
        $pembeli = Auth::pembeli();

        $searchData = Alamat::find($id_organisasi);

        if (!$searchData) {
            return response ([
                'message'=> 'Address Not Found',
                'data'=> $searchData
            ], 200);
        }
    }

    public function update(Request $request, string $id_organisasi)
    {
        
    }
}
