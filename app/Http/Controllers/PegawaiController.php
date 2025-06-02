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
    public function index()
    {
        $pegawai = Pegawai::all();

        return response()->json([
            'message' => 'Daftar pegawai',
            'data' => $pegawai
        ], 200);
    }

    public function search(string $id_pegawai)
    {
        $searchData = Pegawai::find($id_pegawai);

        if (!$searchData) {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        return response([
            'message' => 'Pegawai ditemukan',
            'data' => $searchData
        ], 200);
    }

    public function store(Request $request)
    {
        $pegawaiData = $request->all();

        $validate = Validator::make($pegawaiData, [
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pegawais,email',
            'password' => 'required|string|min:8',
            'tgl_lahir' => 'required|date',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $pegawaiData['password'] = bcrypt($pegawaiData['password']);

        $pegawai = Pegawai::create($pegawaiData);

        return response([
            'message' => 'Penitip berhasil ditambahkan',
            'data' => $pegawai
        ], 201);
    }

    public function destroy($id_pegawai)
    {
        $pegawai = Pegawai::find($id_pegawai);

        if (is_null($pegawai)) {
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404);
        }

        if ($pegawai->delete()) {
            return response([
                'message' => 'Pegawai Deleted Successfully',
                'data' => $pegawai,
            ], 200);
        }

        return response([
            'message' => 'Delete pegawai Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id_pegawai)
    {
        $pegawai = Pegawai::find($id_pegawai);

        if (is_null($pegawai)) {
            return response([
                'message' => 'Pegawai tidak ditemukan.',
                'data' => null
            ], 404);
        }

        $updateData = $request->only([
            'email',
            'password',
            'jabatan',
        ]);

        $validate = Validator::make($updateData, [
            'email' => 'nullable|string|email|max:255|unique:pegawais,email,' . $pegawai->id_pegawai . ',id_pegawai',
            'password' => 'nullable|string|min:8',
            'jabatan' => 'nullable|string|max:255',
        ]);

        if (!empty($updateData['password'])) {
            $updateData['password'] = bcrypt($updateData['password']);
        }

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $pegawai->update($updateData);

        return response([
            'message' => 'Data pegawai berhasil diupdate.',
            'data' => $pegawai
        ], 200);
    }

    public function login(Request $request)
    {
        $loginPegawai = $request->all();

        $validate = Validator::make($loginPegawai, [
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        $pegawai = Pegawai::where('email', $request->email)->first();

        // Gunakan Hash::check() untuk mencocokkan password
        if (!$pegawai || !Hash::check($request->password, $pegawai->password)) {
            return response(['message' => 'Email atau password salah'], 401);
        }

        $token = $pegawai->createToken('Authentication Token')->plainTextToken;

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
