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
            'total_pembelian' => $request->total_pembelian,
            'id_pembeli' => $request->id_pembeli,
            'status_pembayaran' => 'pending',
            'foto_buktiPembayaran' => '-',
            'tgl_checkout' => now(),
            'tgl_lunas' => now(),
            'tgl_selesai' => now(),
            'tgl_pembelian' => now(),
            'tgl_pengembalian' => now(),
        ]);

        return response([
            'message' => 'Pembelian berhasil ditambahkan',
            'data' => $pembelian
        ], 201);
    }

    public function updatePembeli(Request $request, string $id_pembelian)
    {
        $pembeli = Auth::user();
        $user = Pembeli::find($pembeli->id_pembeli);

        if (!$pembeli) {
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

        // kalo lebih dr 1 menit dan belum ada bukti pelunasan
        if ($updateData->status_pembayaran === 'pending' && !$updateData->foto_buktiPembayaran && now()->diffInMinutes($updateData->tgl_chekout) >= 1) {
            $updateData->status_pembayaran = 'batal';

            // $pembelian->delete();

            // Pembelian::where('id_pembeli', $pembeli->id_pembeli)->delete();

            return response([
                'message' => 'Pembayaran dibatalkan karena melebihi batas waktu tanpa bukti pembayaran.',
            ], 200);
        }

        // kalo pembeli udah upload bukti
        if ($request->hasFile('foto_buktiPembayaran')) {
            $validate = Validator::make($request->all(), [
                'foto_buktiPembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($validate->fails()) {
                return response([
                    'message' => $validate->errors()
                ], 400);
            }

            $uploadFolder = 'bukti_pembayaran';
            $image = $request->file('foto_buktiPembayaran');
            $imagePath = $image->store($uploadFolder, 'public');
            $imageName = basename($imagePath);

            $updateData['foto_buktiPembayaran'] = $imageName;
        }

        if (!empty($updateData)) {
            $pembelian->update($updateData);
        }

        return response([
            'message' => 'Pembelian Update Successfully',
            'data' => $pembelian,
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
