<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    public function index()
    {
        $allAlamat = Alamat::all();
        return response()->json($allAlamat);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'alamat_pembeli' => 'required',
        ]);

        $pembeliId = Auth::id_pembeli();

        $alamat = Alamat::create([
            'id_pembeli' => $pembeliId,
            'alamat_pembeli' => $validatedData['alamat_pembeli'],
        ]);

        return response()->json([
            'message' => 'Address created successfully',
        ], 201);
    }


}
