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
        $penitipList = Penitip::all();

        return response()->json([
            'message' => 'Daftar semua penitip',
            'data' => $penitipList
        ], 200);
    }

    public function search(string $id_penitip)
    {
        $searchData = Penitip::find($id_penitip);

        if (!$searchData) {
            return response([
                'message' => 'Penitip tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Data penitip ditemukan',
            'data' => $searchData
        ], 200);
    }

    public function store(Request $request)
    {
        $penitipData = $request->all();

        $validate = Validator::make($penitipData, [
            'NIK' => 'required|string|max:255',
            'nama_penitip' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pegawais,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $penitipData['saldo'] = 0;
        $penitipData['jml_terjual'] = 0;
        $penitipData['jml_terdonasi'] = 0;
        $penitipData['badge_penitip'] = "";
        $penitipData['ratarata_rating'] = 0;
        $penitipData['point_reward'] = '0';
        $penitipData['password'] = bcrypt($request->password);
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
        $penitip = Penitip::find($id_penitip);

        if (is_null($penitip)) {
            return response([
                'message' => 'Penitip tidak ditemukan.',
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'nama_penitip' => 'required|string|max:100',
            'NIK' => 'required|string|max:20',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $penitip->nama_penitip = $validated['nama_penitip'];
        $penitip->NIK = $validated['NIK'];
        $penitip->email = $validated['email'];
        $penitip->password = bcrypt($validated['password']);

        $penitip->save();

        return response([
            'message' => 'Data penitip berhasil diupdate.',
            'data' => $penitip
        ], 200);
    }

    public function updatePegawai(Request $request, $id_penitip)
    {
        $penitip = Penitip::with('barang')->find($id_penitip);

        if (is_null($penitip)) {
            return response([
                'message' => 'Penitip tidak ditemukan.',
                'data' => null
            ], 404);
        }

        $request->validate([
            'barang' => 'required|array',
            'barang.*.jumlah_terjual' => 'nullable|integer|min:0',
            'barang.*.jumlah_terdonasi' => 'nullable|integer|min:0',
        ]);

        $saldo = 0;
        $totalRating = 0;
        $ratingCount = 0;

        foreach ($penitip->barang as $barang) {
            $barangId = $barang->id;

            // Ambil nilai dari input (jika ada)
            $jumlahTerjual = $request->input("barang.$barangId.jumlah_terjual", $barang->jumlah_terjual);
            $jumlahTerdonasi = $request->input("barang.$barangId.jumlah_terdonasi", $barang->jumlah_terdonasi);

            // Update jika ada perubahan
            $barang->jumlah_terjual = $jumlahTerjual;
            $barang->jumlah_terdonasi = $jumlahTerdonasi;
            $barang->save();

            // Hitung saldo
            if ($jumlahTerjual > 0) {
                $pendapatanBarang = $barang->harga * $jumlahTerjual * 0.85;
                $saldo += $pendapatanBarang;
            }

            // Hitung total rating
            if ($barang->rating && is_numeric($barang->rating)) {
                $totalRating += $barang->rating;
                $ratingCount++;
            }
        }

        // Update saldo ke penitip
        $penitip->saldo = $saldo;

        // Hitung rata-rata rating jika ada
        if ($ratingCount > 0) {
            $penitip->rating = round($totalRating / $ratingCount, 2);
        }

        $penitip->save();

        return response([
            'message' => 'Data penitip berhasil diperbarui.',
            'saldo_terbaru' => $penitip->saldo,
            'rata_rata_rating' => $penitip->rating,
            'data' => $penitip
        ], 200);
    }

    public function login(Request $request)
    {
        $loginPenitip = $request->all();

        $validate = Validator::make($loginPenitip, [
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $penitip = Penitip::where('email', $request->email)->first();

        if (!$penitip || !Hash::check($request->password, $penitip->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

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
