<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penitip;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenitipController extends Controller
{

    public function store(Request $request)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$pegawai || $pegawai->jabatan !== 'CS') {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $penitipData = $request->all();

        $validate = Validator::make($penitipData, [
            'nama_penitip' => 'required|string|max:255',
            'NIK' => 'required|string|max:255',
            'email_penitip' => 'required|string|email|max:255|unique:penitips',
            'pass_penitip' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $latest = Penitip::where('id_penitip', 'like', 'N-%')
            ->orderByDesc('id_penitip')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->id_penitip, 2); // ambil angka setelah "N-"
            $newId = 'N-' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'N-0001';
        }

        $penitipData['id_penitip'] = $newId;
        $penitipData['pass_penitip'] = bcrypt($penitipData['pass_penitip']);

        $penitip = Penitip::create($penitipData);

        return response([
            'message' => 'Penitip berhasil ditambahkan',
            'data' => $pegawai
        ], 201);
    }

    public function login(Request $request)
    {
        $loginPenitip = $request->all();

        $validate = Validator::make($loginPenitip, [
            'email_penitip' => 'required|string|email',
            'pass_penitip' => 'required|min 8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        if (!Penitip::attempt($loginPenitip)) {
            return response(['message' => 'Invalid email & password match'], 401);
        }

        $penitip = Penitip::penitip();
        $token = $penitip->createToken('Authentication Token')->accessToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'detail' => $penitip,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response([
            'message' => 'Logged Out'
        ]);
    }
}
