<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PenitipController extends Controller
{
    public function register (Request $request)
    {
        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'NIK' => 'required|string|max:255',
            'email_penitip' => 'required|string|email|max:255|unique:penitips',
            'pass_penitip' => 'required|string|min:8',
        ]);

        $latest = DB::table('penitips')->select('id_penitip')
                ->where('id_penitip', 'like', 'N-%')
                ->orderByDesc('id_penitip')
                ->first();

        if ($latest) {
            $num = (int) substr($latest->id_penitip, 2);
            $newId = 'N-' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'N-0001';
        }

        $penitip = Penitip::create([
            'id_penitip' => $newId,
            'nama_penitip' => $request->nama_penitip,
            'NIK' => $request->NIK,
            'email_penitip' => $request->email_penitip,
            'pass_penitip' => Hash::make($request->pass_penitip),
        ]);

        return response()->json([
            'penitip' => $penitip,
            'message' => 'Penitip registered successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_penitip' => 'required|string|email',
            'pass_penitip' => 'required|string',
        ]);

        $penitip = Penitip::where('email_penitip', $request->email_penitip)->first();

        if (!$penitip || !Hash::check($request->pass, $penitip->pass_penitip)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $penitip->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'detail' => $penitip,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not logged in'], 401);
    }
}
