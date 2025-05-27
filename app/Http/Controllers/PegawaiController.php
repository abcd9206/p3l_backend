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
    public function register(Request $request)
    {
        $regisPegawai = $request->all();

        $validate = Validator::make($regisPegawai, [
            'nama_pegawai' => 'required|string|max:255',
            'alamat_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email_pegawai' => 'required|string|email|max:255|unique:pegawais',
            'pass_pegawai' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $regisPegawai['pass_pegawai'] = bcrypt($request->pass_pegawai);

        $latest = DB::table('pegawais')->select('id_pegawai')
            ->where('id_pegawai', 'like', 'P-%')
            ->orderByDesc('id_pegawai')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->id_pegawai, 2);
            $newId = 'P-' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'P-0001';
        }

        $pegawai = Pegawai::create($regisPegawai);

        return response()->json([
            'pegawai' => $pegawai,
            'message' => 'Pegawai registered successfully'
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
