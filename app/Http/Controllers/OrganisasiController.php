<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrganisasiController extends Controller
{
    public function register(Request $request)
    {
        $regisOrganisasi = $request->all();

        $validate = Validator::make($regisOrganisasi, [
            'nama_organisasi' => 'required|string|max:255',
            'alamat_organisasi' => 'required|string|max:255',
            'email_organisasi' => 'required|string|email|max:255|unique:organisasis',
            'pass_organisasi' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $regisOrganisasi['pass_organisasi'] = bcrypt($request->pass_organisasi);

        $latest = DB::table('organisasis')->select('id_organisasi')
            ->where('id_organisasi', 'like', 'O-%')
            ->orderByDesc('id_organisasi')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->id_organisasi, 2);
            $newId = 'O-' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'O-0001';
        }

        $organisasi = Organisasi::create($regisOrganisasi);

        return response()->json([
            'organisasi' => $organisasi,
            'message' => 'Organisasi registered successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $loginOrganisasi = $request->all();

        $validate = Validator::make($loginOrganisasi, [
            'email_organisasi' => 'required|string|email',
            'pass_pegawai' => 'required|min 8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        if (!Organisasi::attempt($loginOrganisasi)) {
            return response(['message' => 'Invalid email & password match'], 401);
        }

        $organisasi = Organisasi::organisasi();
        $token = $organisasi->createToken('Authentication Token')->accessToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'detail' => $organisasi,
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
