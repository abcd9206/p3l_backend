<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function register (Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'tgl _pegawai' => 'required|date',
            'email_pegawai' => 'required|string|email|max:255|unique:pegawais',
            'pass_pegawai' => 'required|string|min:8',
        ]);

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

        $pegawai = Pegawai::create([
            'id_pegawai' => $newId,
            'nama_pegawai' => $request->nama_pegawai,
            'tgl_lahir' => $request->tgl_lahir,
            'email_pegawai' => $request->email_pegawai,
            'pass_pegawai' => Hash::make($request->pass_pegawai),
        ]);

        return response()->json([
            'pegawai' => $pegawai,
            'message' => 'Pegawai registered successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_pegawai' => 'required|string|email',
            'pass' => 'required|string',
        ]);

        $pegawai = Pegawai::where('email_pegawai', $request->email_pegawai)->first();

        if (!$pegawai || !Hash::check($request->pass, $pegawai->pass_pegawai)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $pegawai->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'detail' => $pegawai,
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
