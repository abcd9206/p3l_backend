<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PembeliController extends Controller
{
    public function register(Request $request)
    {
        $regisPembeli = $request->all();

        $validate = Validator::make($regisPembeli, [
            'nama_pembeli' => 'required|string|max:255',
            'tlpn_pembeli' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pembelis',
            'password' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $regisPembeli['password'] = bcrypt($request->password);

        $regisPembeli = Pembeli::create($regisPembeli);

        return response([
            'message' => 'Pembeli berhasil ditambahkan',
            'data' => $regisPembeli
        ], 201);
    }


    public function login(Request $request)
    {
        $loginPembeli = $request->all();

        $validate = Validator::make($loginPembeli, [
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $pembeli = Pembeli::where('email', $request->email)->first();

        if (!$pembeli || !Hash::check($request->password, $pembeli->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = $pembeli->createToken('Authentication Token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'detail' => $pembeli,
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
