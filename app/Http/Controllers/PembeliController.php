<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PembeliController extends Controller
{
    public function register(Request $request)
    {
        $regisPembeli = $request->all();

        $validate = Validator::make($regisPembeli, [
            'nama_pembeli' => 'required|string|max:255',
            'alamat_pembeli' => 'required|string|max:255',
            'email_pembeli' => 'required|string|email|max:255|unique:pembelis',
            'pass_pembeli' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $regisPembeli['pass_pembeli'] = bcrypt($request->pass_pembeli);

        $latest = DB::table('pembelis')->select('id_pembeli')
            ->where('id_pembeli', 'like', 'P-%')
            ->orderByDesc('id_pembeli')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->id_pembeli, 2);
            $newId = 'P-' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'P-0001';
        }

        $pembeli = Pembeli::create($regisPembeli);

        return response()->json([
            'pembeli' => $pembeli,
            'message' => 'Pembeli registered successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $loginPembeli = $request->all();

        $validate = Validator::make($loginPembeli, [
            'email_pembeli' => 'required|string|email',
            'pass_pembeli' => 'required|min 8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        if (!Pembeli::attempt($loginPembeli)) {
            return response(['message' => 'Invalid email & password match'], 401);
        }

        $pembeli = Pembeli::pembeli();
        $token = $pembeli->createToken('Authentication Token')->accessToken;

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
