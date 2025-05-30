<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    public function store(Request $request)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$pegawai || $pegawai->jabatan !== 'Admin') {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $pegawaiData = $request->all();

        $validate = Validator::make($pegawaiData, [
            'nama_pegawai' => 'required|string|max:255',
            'alamat_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email_pegawai' => 'required|string|email|max:255|unique:pegawais,email_pegawai',
            'pass_pegawai' => 'required|string|min:8',
        ]);

        $latest = Pegawai::where('id_pegawai', 'like', 'P-%')
            ->orderByDesc('id_pegawai')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->id_pegawai, 2);
            $newId = 'P-' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'P-0001';
        }

        $pegawaiData['id_pegawai'] = $newId;
        $pegawaiData['pass_pegawai'] = bcrypt($pegawaiData['pass_pegawai']);

        $pegawai = Pegawai::create($pegawaiData);

        return response([
            'message' => 'Penitip berhasil ditambahkan',
            'data' => $pegawai
        ], 201);
    }

    public function login(Request $request)
    {
        $loginPegawai = $request->all();

        $validate = Validator::make($loginPegawai, [
            'email_pegawai' => 'required|string|email',
            'pass_pegawai' => 'required|min 8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        if (!Pegawai::attempt($loginPegawai)) {
            return response(['message' => 'Invalid email & password match'], 401);
        }

        $pegawai = Pegawai::pegawai();
        $token = $pegawai->createToken('Authentication Token')->accessToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'detail' => $pegawai,
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
