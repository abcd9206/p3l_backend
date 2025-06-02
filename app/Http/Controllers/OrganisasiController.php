<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class OrganisasiController extends Controller
{
    public function register(Request $request)
    {
        $regisOrganisasi = $request->all();

        $validate = Validator::make($regisOrganisasi, [
            'nama_organisasi' => 'required|string|max:255',
            'alamat_organisasi' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:organisasis',
            'password' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $regisOrganisasi['password'] = bcrypt($request->password);

        // Hapus manual ID, biarkan database generate id_organisasi otomatis
        $regisOrganisasi = Organisasi::create($regisOrganisasi);

        return response([
            'message' => 'Organisasi berhasil ditambahkan',
            'data' => $regisOrganisasi
        ], 201);
    }

    public function login(Request $request)
    {
        $loginOrganisasi = $request->all();

        $validate = Validator::make($loginOrganisasi, [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        // Sesuaikan nama kolom email dan password dengan yang di database!
        $organisasi = Organisasi::where('email', $request->email)->first();

        if (!$organisasi || !Hash::check($request->password, $organisasi->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        if (!$organisasi) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = $organisasi->createToken('Authentication Token')->plainTextToken;

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
