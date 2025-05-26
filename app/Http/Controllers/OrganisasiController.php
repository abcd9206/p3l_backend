<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrganisasiController extends Controller
{
    public function register (Request $request)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'alamat_organisasi' => 'required|string|max:255',
            'email_organisasi' => 'required|string|email|max:255|unique:organisasis',
            'pass_organisasi' => 'required|string|min:8',
        ]);

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

        $organisasi = Organisasi::create([
            'id_organisasi' => $newId,
            'nama_organisasi' => $request->nama_organisasi,
            'email_organisasi' => $request->email_organisasi,
            'pass_organisasi' => Hash::make($request->pass_organisasi),
        ]);

        return response()->json([
            'organisasi' => $organisasi,
            'message' => 'Organisasi registered successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_organisasi' => 'required|string|email',
            'pass' => 'required|string',
        ]);

        $organisasi = Organisasi::where('email', $request->email)->first();

        if (!$organisasi || !Hash::check($request->pass, $user->pass)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $organisasi->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'detail' => $organisasi,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message'=>'Logged out successfully']);
        }

        return response()->json(['message' => 'Not logged in'], 401);
    }
}
