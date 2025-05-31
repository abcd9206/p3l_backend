<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penitip;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class PenitipController extends Controller
{

    public function index()
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$user || $user->jabatan !== 'CS') {
            return response(['message' => 'Pegawai tidak ditemukan atau bukan CS'], 403);
        }

        $penitipList = Penitip::all();

        return response()->json([
            'message' => 'Daftar semua penitip',
            'data' => $penitipList
        ], 200);
    }

    public function search(string $id_pegawai)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$user || $user->jabatan !== 'CS') {
            return response(['message' => 'Hanya pegawai dengan jabatan CS yang dapat mengakses fitur ini'], 403);
        }

        $searchData = Pegawai::find($id_pegawai);

        if (!$searchData) {
            return response([
                'message' => 'Penitip tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Data pegawai ditemukan',
            'data' => $searchData
        ], 200);
    }

    public function store(Request $request)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if ($pegawai->jabatan !== 'CS') {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $penitipData = $request->all();

        $validate = Validator::make($penitipData, [
            'NIK' => 'required|string|max:255',
            'nama_penitip' => 'required|string|max:255',
            'email_penitip' => 'required|string|email|max:255|unique:pegawais,email_pegawai',
            'pass_penitip' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $penitip = Penitip::create($penitipData);

        return response([
            'message' => 'Penitip berhasil ditambahkan',
            'data' => $penitip
        ], 201);
    }

    public function destroy($id_penitip)
    {
        $penitip = Penitip::find($id_penitip);

        if (is_null($penitip)) {
            return response([
                'message' => 'Penitip Not Found',
                'data' => null
            ], 404);
        }

        if ($penitip->delete()) {
            return response([
                'message' => 'Penitip Deleted Successfully',
                'data' => $penitip,
            ], 200);
        }

        return response([
            'message' => 'Delete penitip Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id_penitip)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$user || $user->jabatan !== 'CS') {
            return response(['message' => 'Hanya Pegawai dengan jabatan CS yang dapat mengubah data penitip.'], 403);
        }

        $penitip = Penitip::find($id_penitip);

        if (is_null($penitip)) {
            return response([
                'message' => 'Penitip tidak ditemukan.',
                'data' => null
            ], 404);
        }

        $updateData = $request->only([
            'nama_penitip',
            'email_penitip',
            'pass_penitip'
        ]);

        if (isset($updateData['pass_penitip'])) {
            $updateData['pass_penitip'] = bcrypt($updateData['pass_penitip']);
        }

        $penitip->update($updateData);

        return response([
            'message' => 'Data penitip berhasil diupdate.',
            'data' => $penitip
        ], 200);
    }


    public function login(Request $request)
    {
        $loginPenitip = $request->all();

        $validate = Validator::make($loginPenitip, [
            'email_penitip' => 'required|string|email',
            'pass_penitip' => 'required|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $penitip = Penitip::where('email_penitip', $request->email_penitip)
            ->where('pass_penitip', $request->pass_penitip)
            ->first();

        if (!$penitip) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = $penitip->createToken('Authentication Token')->plainTextToken;

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
