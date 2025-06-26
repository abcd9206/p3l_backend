<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use App\Models\Pegawai;
use App\Models\Barang;
use App\Models\Penitipan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenitipanController extends Controller
{
    public function index()
    {
        $penitipan = Penitipan::all();

        return response()->json([
            'message' => 'Daftar penitipan',
            'data' => $penitipan,
        ], 200);
    }

    public function store(Request $request)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$pegawai || $pegawai->jabatan !== 'Gudang') {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $penitipanData = $request->all();

        $validate = Validator::make($penitipanData, [
            'nama_QC' => 'required|max:255',
            'tgl_penitipan' => 'required|date',
            'id_penitip' => 'required',
            'konfirmasi_perpanjangan' => 'nullable|boolean',
            'tgl_kadaluarsa' => 'required|date',
            'id_pegawai' => 'required',
            'id_barang' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $tglPenitipan = Carbon::parse($request->tgl_penitipan);

        $tglKadaluarsa = $tglPenitipan->copy()->addDays(30);
        $tglPengembalian = $tglKadaluarsa->copy()->subDays(7);

        $penitipanData = Penitipan::create([
            'id_penitip' => $request->id_penitip,
            'tgl_penitipan' => $tglPenitipan,
            'tgl_kadaluarsa' => $tglKadaluarsa,
            'tgl_pengembalian' => $tglPengembalian,
            'nama_QC' => $request->nama_QC,
            'konfirmasi_perpanjangan' => $request->konfirmasi_perpanjangan,
            'id_pegawai' => $request->id_pegawai,
            'id_barang' => $request->id_barang,
        ]);

        return response([
            'message' => 'Penitipan berhasil ditambahkan',
            'data' => $penitipanData,
        ], 201);
    }

    public function update(Request $request, string $id_penitipan)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$pegawai || $pegawai->jabatan !== 'Gudang') {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $penitipan = Penitipan::find($id_penitipan);

        if (is_null($penitipan)) {
            return response([
                'message' => 'Penitipan Not Found',
                'data' => null
            ], 404);
        }

        $validate = Validator::make($request->all(), [
            'konfirmasi_perpanjangan' => 'required|boolean',
            'tgl_penitipan' => 'nullable|date',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $konfirmasi = $request->konfirmasi_perpanjangan;

        if ($konfirmasi) {
            // Perpanjangan disetujui
            $tglBaru = $request->tgl_penitipan ? Carbon::parse($request->tgl_penitipan) : now();
            dd($tglBaru); // Apakah nilainya sesuai?
            $tglKadaluarsaBaru = $tglBaru->copy()->addDays(30);
            $tglPengambilanBaru = $tglKadaluarsaBaru->copy()->subDays(7);

            $penitipan->update([
                'konfirmasi_perpanjangan' => true,
                'tgl_penitipan' => $tglBaru,
                'tgl_kadaluarsa' => $tglKadaluarsaBaru,
                'tgl_pengembalian' => $tglPengambilanBaru,
            ]);
        } else {
            // Tidak diperpanjang, hanya update konfirmasi
            $penitipan->update([
                'konfirmasi_perpanjangan' => false,
            ]);

            // Cek apakah sudah lewat dari 7 hari setelah tgl_pengembalian
            if (Carbon::now()->gt(Carbon::parse($penitipan->tgl_pengembalian)->addDays(7))) {
                // Misalnya update status barang jadi "didonasikan"
                $penitipan->status = 'didonasikan';
                $penitipan->save();
            }
        }

        return response([
            'message' => 'Data penitipan berhasil diperbarui',
            'data' => $penitipan
        ], 200);
    }

    public function search(string $id_penitipan)
    {
        $pegawai = Auth::user();
        $user = Pegawai::find($pegawai->id_pegawai);

        if (!$pegawai || $pegawai->jabatan !== 'Gudang') {
            return response(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $searchData = Penitipan::find($id_penitipan);

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

    public function destroy($id_penitipan)
    {
        $penitipan = Penitipan::find($id_penitipan);

        if (is_null($penitipan)) {
            return response([
                'message' => 'Penitipan Not Found',
                'data' => null
            ], 404);
        }

        if ($penitipan->delete()) {
            return response([
                'message' => 'Penitipan Deleted Successfully',
                'data' => $penitipan,
            ], 200);
        }

        return response([
            'message' => 'Delete penitipan Failed',
            'data' => null,
        ], 400);
    }

    public function laporanBarangKadaluarsa()
    {
        $tahun = \Carbon\Carbon::now()->year;
        $tanggalCetak = \Carbon\Carbon::now()->format('d F Y');
        $today = \Carbon\Carbon::today();

        $laporan = DB::table('penitipans')
            ->join('barangs', 'barangs.id_barang', '=', 'penitipans.id_barang')
            ->join('penitips', 'penitips.id_penitip', '=', 'penitipans.id_penitip')
            ->whereDate('penitipans.tgl_kadaluarsa', '=', $today)
            ->select(
                // 'penitipans.*',
                'barangs.id_barang',
                'barangs.nama_barang',
                'penitips.id_penitip',
                'penitips.nama_penitip',
                'penitipans.tgl_penitipan',
                'penitipans.tgl_kadaluarsa',
                'penitipans.tgl_pengembalian'
            )
            ->get();

        return response()->json([
            'tahun' => $tahun,
            'tanggal_cetak' => $tanggalCetak,
            'data' => $laporan
        ]);
    }

}
