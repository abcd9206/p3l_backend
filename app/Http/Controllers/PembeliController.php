<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PembeliController extends Controller
{
    public function register (Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'tlpn_pembeli' => 'required|string|max:20',
            'email_pembeli' => 'required|string|email|max:255|unique:pembelis',
            'pass_pembeli' => 'required|string|min:8',
        ]);

        $latest = DB::table('pembelis')->select('id_pembeli')
                ->where('id_pembeli', 'like', 'B-%')
                ->orderByDesc('id_pembeli')
                ->first();

        if ($latest) {
            $num = (int) substr($latest->id_pembeli, 2);
            $newId = 'B-' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'B-0001';
        }

        $pembeli = Pembeli::create([
            'id_pembeli' => $newId,
            'nama_pembeli' => $request->nama_pembeli,
            'tlpn_pembeli' => $request->tlpn_pembeli,
            'email_pembeli' => $request->email_pembeli,
            'pass_pembeli' => Hash::make($request->pass_pembeli),
        ]);

        return response()->json([
            'pembeli' => $pembeli,
            'message' => 'Pembeli registered successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_pembeli' => 'required|string|email',
            'pass_pembeli' => 'required|string',
        ]);

        $pembeli = Pembeli::where('email_pembeli', $request->email_pembeli)->first();

        if (!$pembeli || !Hash::check($request->pass, $pembeli->pass_pembeli)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $pembeli->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'detail' => $pembeli,
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
