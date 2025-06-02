<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Pembelian;
use App\Models\Barang;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BarangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class PembelianController extends Controller
{
    public function index()
    {
        $pembelian = Pembelian::all();

        return response()->json([
            'message' => 'Daftar pembelian',
            'data' => $pembelian,
        ], 200);
    }

    public function store(Request $request)
    {
        $pembeli = Auth::user();
        $user = Pembeli::find($pembeli->id_pembeli);

        if (!$pembeli) {
            return response(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $pembelianData = $request->all();

        $validate = Validator::make($pembelianData, [
            'jml_barang' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string|max:50',
            'verifikasi_pembayaran' => 'required|boolean',
            'id_barang' => '',
            'id_pembeli' => '',
            'id_pegawai' => '',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }


        $barang = Barang::find($request->id_barang);


        if (!$barang) {
            return response(['message' => 'Barang tidak ditemukan'], 404);
        }

        $total = $barang->harga_barang * $request->jml_barang;
        $pembelianData['total_pembelian'] = $total;

        $pembelianData['id_pembeli'] = $pembeli->id_pembeli;

        $pembelianData['status_pembayaran'] = 'pending';

        $pembelianData['foto_buktiPembayaran'] = '-';

        $pembelian = Pembelian::create([
            'jml_barang' => $request->jml_barang,
            'metode_pembayaran' => $request->metode_pembayaran,
            'verifikasi_pembayaran' => 0,
            'id_barang' => $request->id_barang,
            'total_pembelian' => $total,
            'id_pembeli' => $request->id_pembeli,
            'status_pembayaran' => 'pending',
            'foto_buktiPembayaran' => '-',
            'tgl_checkout' => Carbon::now(),
            'tgl_lunas' => Carbon::now(),
            'tgl_selesai' => Carbon::now(),
            'tgl_pembelian' => Carbon::now(),
            'tgl_pengambilan' => Carbon::now(),
            'id_pegawai' => $request->id_pegawai,
        ]);

        return response([
            'message' => 'Pembelian berhasil ditambahkan',
            'data' => $pembelian
        ], 201);
    }

    public function updatePembeli(Request $request, $id)
    {
        $pembelian = Pembelian::find($id);

        if (!$pembelian) {
            return response([
                'message' => 'Pembelian tidak ditemukan.'
            ], 404);
        }

        $updateData = $request->all();

        // === Cek kondisi batal jika pending dan belum upload bukti ===
        if (
            $pembelian->status_pembayaran === 'pending' &&
            empty($pembelian->foto_buktiPembayaran)
        ) {
            $checkoutTime = Carbon::parse($pembelian->tgl_chekout);
            $now = now();

            if ($checkoutTime->diffInMinutes($now) >= 1) {
                $pembelian->status_pembayaran = 'batal';
                $pembelian->save();

                return response([
                    'message' => 'Pembayaran dibatalkan karena melebihi batas waktu tanpa bukti pembayaran.',
                    'data' => $pembelian
                ], 200);
            }
        }

        // === Cek jika ada upload bukti pembayaran dan sebelumnya belum ada ===
        if (
            isset($updateData['foto_buktiPembayaran']) &&
            empty($pembelian->foto_buktiPembayaran)
        ) {
            $pembelian->status_pembayaran = 'berhasil';
        }

        // Update semua data
        $pembelian->update($updateData);

        return response([
            'message' => 'Data pembelian berhasil diperbarui.',
            'data' => $pembelian
        ], 200);
    }


    public function updatePegawai(Request $request, string $id_pembelian)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$pegawai || $pegawai->jabatan !== 'CS') {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $pembelian = Pembelian::find($id_pembelian);

        if (is_null($pembelian)) {
            return response([
                'message' => 'Pembelian Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'verifikasi_pembayaran' => 'nullable|boolean',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_pembelian',
            'tgl_pengambilan' => 'nullable|date|after_or_equal:tgl_pembelian',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        // Jika user mengunggah bukti pembayaran (dilakukan oleh pembeli sebelumnya)
        if ($pembelian->foto_buktiPembayaran && isset($updateData['verifikasi_pembayaran']) && $updateData['verifikasi_pembayaran'] == true) {
            $updateData['status_pembayaran'] = 'berhasil';
            $updateData['tgl_lunas'] = now();
        }

        // Isi tgl_selesai jika barang sudah diterima oleh pembeli
        if (isset($updateData['tgl_selesai'])) {
            $updateData['tgl_selesai'] = now();
        }

        // Isi tgl_pengambilan hanya jika <= 7 hari dari tgl_pembelian
        if (isset($updateData['tgl_pengambilan'])) {
            $tglPembelian = \Carbon\Carbon::parse($pembelian->tgl_pembelian);
            $tglPengambilan = \Carbon\Carbon::parse($updateData['tgl_pengambilan']);

            if ($tglPengambilan->diffInDays($tglPembelian) > 7) {
                return response([
                    'message' => 'Tanggal pengambilan tidak boleh lebih dari 7 hari sejak pembelian.'
                ], 400);
            }
        }

        $pembelian->update($updateData);

        return response([
            'message' => 'Pembelian Update Successfully',
            'data' => $pembelian,
        ], 200);
    }

    public function search(string $id_pembelian)
    {
        $pembeli = Auth::user();
        $user = Pembeli::find($pembeli->id_pembeli);

        if (!$pembeli) {
            return response(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $searchData = Pembelian::find($id_pembelian);

        if (!$searchData) {
            return response([
                'message' => 'Barang Not Found',
                'data' => $searchData
            ], 200);
        }

        return response([
            'message' => 'Data ditemukan',
            'data' => $searchData
        ], 200);
    }
}
