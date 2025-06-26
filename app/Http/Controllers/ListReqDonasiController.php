<?php

namespace App\Http\Controllers;

use App\Models\ListReqDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ListReqDonasiController extends Controller
{
    public function index()
    {
        try {
            $requestList = ListReqDonasi::all();

            return response()->json([
                'message' => 'Daftar semua list request donasi',
                'data' => $requestList
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(string $id_reqDonasi)
    {
        $searchData = ListReqDonasi::find($id_reqDonasi);

        if (!$searchData) {
            return response([
                'message' => 'Request tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Data request donasi ditemukan',
            'data' => $searchData
        ], 200);
    }

    public function store(Request $request)
    {
        $requestData = $request->all();

        $validate = Validator::make($requestData, [
            'desc_request' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $requestData['tgl_reqDonasi'] = Carbon::today();
        $request = ListReqDonasi::create($requestData);

        return response([
            'message' => 'List berhasil ditambahkan',
            'data' => $request
        ], 201);
    }

    public function destroy($id_reqDonasi)
    {
        $requestData = ListReqDonasi::find($id_reqDonasi);

        if (is_null($requestData)) {
            return response([
                'message' => 'Request tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($requestData->delete()) {
            return response([
                'message' => 'Request berhasil dihapus',
                'data' => $requestData,
            ], 200);
        }

        return response([
            'message' => 'Gagal menghapus request',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id_reqDonasi)
    {
        $requestData = ListReqDonasi::find($id_reqDonasi);

        if (is_null($requestData)) {
            return response([
                'message' => 'Request tidak ditemukan.',
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'desc_request' => 'required|string|max:255',
        ]);

        $requestData->desc_request = $validated['desc_request'];

        $requestData->save();

        return response([
            'message' => 'Data request berhasil diupdate.',
            'data' => $requestData
        ], 200);
    }
}
