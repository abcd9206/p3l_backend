<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Barang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();

        return response()->json([
            'message' => 'Daftar barang milik pembeli',
            'data' => $barang
        ], 200);
    }

    public function store(Request $request)
    {
        $barangData = $request->all();

        $validate = Validator::make($barangData, [
            'nama_barang' => 'required|max:255',
            'tgl_garansi' => 'nullable|date',
            'harga_barang' => 'required|numeric|min:0',
            'status_barang' => 'required',
            'id_kategori' => 'required',
            'desc_barang' => 'required|max:255',
        ]);

        $barangData["rating_barang"] = 0;
        $barangData["tgl_didonasikan"] = "2000-01-01";
        $barangData["tgl_terdonasi"] = "2000-01-01";
        $barangData["total_barang"] = 1;
        $barangData["total_keseluruhan"] = $barangData["harga_barang"];


        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $barang = Barang::create($barangData);

        return response([
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    public function search(string $id_barang)
    {
        $searchData = Barang::find($id_barang);

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

    public function update(Request $request, string $id_barang)
    {
        $barang = Barang::find($id_barang);

        if (is_null($barang)) {
            return response([
                'message' => 'Barang Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama_barang' => 'required|max:255',
            'tgl_garansi' => 'nullable|date|after_or_equal:tgl_garansi',
            'harga_barang' => 'required|numeric|min:0',
            'desc_barang' => 'required|max:255',
            'status_barang' => 'required',
            'rating_barang' => 'required|numeric|min:0|max:5 ',

        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        if ($updateData['status_barang'] === 'didonasikan') {
            $updateData['tgl_didonasikan'] = now();
        }

        if ($updateData['status_barang'] === 'terdonasi') {
            $updateData['tgl_terdonasi'] = now();
        }

        $barang->update($updateData);

        return response([
            'message' => 'Address Update Successfully',
            'data' => $barang,
        ], 200);
    }

    public function destroy($id_barang)
    {
        $barang = Barang::find($id_barang);

        if (is_null($barang)) {
            return response([
                'message' => 'Barang Not Found',
                'data' => null
            ], 404);
        }

        if ($barang->delete()) {
            return response([
                'message' => 'Barang Deleted Successfully',
                'data' => $barang,
            ], 200);
        }

        return response([
            'message' => 'Delete Barang Failed',
            'data' => null,
        ], 400);
    }

    public function laporanStokGudang()
    {
        $tanggalCetak = Carbon::now()->format('d F Y');

        $dataStok = DB::table('barangs')
            ->leftJoin('penitipans', 'barangs.id_barang', '=', 'penitipans.id_barang')
            ->leftJoin('penitips', 'barangs.id_penitip', '=', 'penitips.id_penitip')
            ->leftJoin('pegawais', 'penitipans.id_pegawai', '=', 'pegawais.id_pegawai')
            ->select(
                'barangs.id_barang',
                'barangs.nama_barang',
                'barangs.status_barang',
                'barangs.harga_barang',
                'penitips.id_penitip',
                'penitipans.tgl_penitipan',
                'penitipans.tgl_kadaluarsa',
                'penitipans.tgl_pengembalian',
                'pegawais.id_pegawai',
                'pegawais.nama_pegawai as nama_petugas',
                'penitipans.konfirmasi_perpanjangan'
            )
            //->where('barangs.status_barang', 'tersedia')
            //->whereDate('penitipans.tgl_penitipan', '<=', Carbon::now()->toDateString()) // stok aktif per hari ini
            ->get();

        return response()->json([
            'tanggal_cetak' => $tanggalCetak,
            'data' => $dataStok,
        ]);
    }

    public function laporanPerKategori()
    {
        $tahun = Carbon::now()->year;
        $tanggalCetak = Carbon::now()->format('d F Y');

        $laporan = DB::table('kategoris')
            ->leftJoin('barangs', 'kategoris.id_kategori', '=', 'barangs.id_kategori')
            ->select(
                'kategoris.jenis_kategori as kategori',
                DB::raw("SUM(CASE WHEN barangs.status_barang = 'terjual' THEN 1 ELSE 0 END) as jumlah_terjual"),
                DB::raw("SUM(CASE WHEN barangs.status_barang = 'gagal' THEN 1 ELSE 0 END) as jumlah_gagal")
            )
            ->groupBy('kategoris.jenis_kategori')
            ->orderBy('kategoris.jenis_kategori')
            ->get();


        return response()->json([
            'tahun' => $tahun,
            'tanggal_cetak' => $tanggalCetak,
            'data' => $laporan
        ]);
    }

}