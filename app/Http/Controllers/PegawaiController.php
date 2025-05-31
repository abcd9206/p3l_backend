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
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$pegawai) {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $pegawai = Pegawai::all();

        return response()->json([
            'message' => 'Daftar pegawai',
            'data' => $pegawai
        ], 200);
    }

    public function search(string $id_pegawai)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$pegawai || $pegawai->jabatan !== 'Admin') {
            return response(['message' => 'Hanya admin yang dapat mengakses data pegawai'], 403);
        }

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
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if ($pegawai->jabatan !== 'Admin') {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $pegawaiData = $request->all();

        $validate = Validator::make($pegawaiData, [
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email_pegawai' => 'required|string|email|max:255|unique:pegawais,email_pegawai',
            'pass_pegawai' => 'required|string|min:8',
            'tgl_lahir' => 'required|date',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

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
        $authPegawai = Auth::user();
        $admin = Pegawai::find($authPegawai->id_pegawai);

        if (!$admin || $admin->jabatan !== 'Admin') {
            return response(['message' => 'Hanya Admin yang dapat melakukan update data pegawai.'], 403);
        }

        $pegawai = Pegawai::find($id_pegawai);

        if (is_null($pegawai)) {
            return response([
                'message' => 'Pegawai tidak ditemukan.',
                'data' => null
            ], 404);
        }

        $updateData = $request->only([
            'email_pegawai',
            'pass_pegawai',
            'jabatan',
        ]);

        $validate = Validator::make($updateData, [
            'email_pegawai' => 'nullable|string|email|max:255|unique:pegawais,email_pegawai,' . $pegawai->id_pegawai . ',id_pegawai',
            'pass_pegawai' => 'nullable|string|min:8',
            'jabatan' => 'nullable|string|max:255',
        ]);

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
            'email_pegawai' => 'required|string|email',
            'pass_pegawai' => 'required|min:8',
        ]);

        $pegawai = Pegawai::where('email_pegawai', $loginPegawai['email_pegawai'])->first();

        if (!$pegawai || $pegawai->pass_pegawai !== $loginPegawai['pass_pegawai']) {
            return response(['message' => 'Invalid email & password match'], 401);
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
