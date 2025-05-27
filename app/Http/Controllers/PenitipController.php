<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenitipController extends Controller
{
    public function register(Request $request)
    {
        $regisPenitip = $request->all();

        $validate = Validator::make($regisPenitip, [
            'nama_penitip' => 'required|string|max:255',
            'alamat_penitip' => 'required|string|max:255',
            'email_penitip' => 'required|string|email|max:255|unique:penitips',
            'pass_penitip' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $regisPenitip['pass_penitip'] = bcrypt($request->pass_penitip);

        $latest = DB::table('penitips')->select('id_penitip')
            ->where('id_penitip', 'like', 'O-%')
            ->orderByDesc('id_penitip')
            ->first();

        if ($latest) {
            $num = (int) substr($latest->id_penitip, 2);
            $newId = 'O-' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'O-0001';
        }

        $penitip = Penitip::create($regisPenitip);

        return response()->json([
            'penitip' => $penitip,
            'message' => 'Penitip registered successfully'
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
