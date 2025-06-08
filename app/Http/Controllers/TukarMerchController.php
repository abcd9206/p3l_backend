<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Merch;
use App\Models\TukarMerch;

class TukarMerchController extends Controller
{
    public function tukar(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();

        $validate = Validator::make($request->all(), [
            'id_merch' => 'required|exists:merches,id_merch',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $merch = Merch::find($request->id_merch);
        $poin = PoinPembeli::where('pembeli_id', $pembeli->id)->first();

        if (!$poin || $poin->jumlah_poin < $merch->poin_penukaran) {
            return response(['message' => 'Poin tidak mencukupi'], 400);
        }

        if ($merch->stok <= 0) {
            return response(['message' => 'Stok merchandise habis'], 400);
        }

        // Kurangi poin & stok
        $poin->jumlah_poin -= $merch->poin_penukaran;
        $poin->save();

        $merch->stok -= 1;
        $merch->save();

        // Catat histori poin
        HistoriPoinPembeli::create([
            'pembeli_id' => $pembeli->id,
            'tipe' => 'kurang',
            'jumlah' => $merch->poin_penukaran,
            'sumber' => 'Klaim Merchandise: ' . $merch->nama_merch
        ]);

        // Simpan ke tukar_merches
        $klaim = TukarMerch::create([
            'tgl_tukarMerch' => now(),
            'tgl_ambil' => null,
            'status_merch' => 'belum', // atau 'sudah'
            'id_pegawai' => null,
            'id_pembeli' => $pembeli->id,
            'id_merch' => $merch->id_merch,
            'id_penitip' => null,
        ]);

        return response([
            'message' => 'Merchandise berhasil diklaim',
            'data' => $klaim
        ], 200);
    }

    // Untuk CS melihat daftar klaim
    public function daftarKlaim()
    {
        $data = TukarMerch::with(['Merch', 'Pembeli'])->orderBy('tgl_tukarMerch', 'desc')->get();

        return response([
            'message' => 'Daftar penukaran merchandise',
            'data' => $data
        ]);
    }

    // Konfirmasi oleh CS bahwa merchandise sudah diambil
    public function konfirmasiAmbil($id)
    {
        $tukar = TukarMerch::find($id);

        if (!$tukar) {
            return response(['message' => 'Data tidak ditemukan'], 404);
        }

        $tukar->status_merch = 'sudah';
        $tukar->tgl_ambil = now();
        $tukar->id_pegawai = Auth::guard('pegawai')->id(); // Jika pakai guard pegawai
        $tukar->save();

        return response(['message' => 'Berhasil dikonfirmasi diambil']);
    }
}
