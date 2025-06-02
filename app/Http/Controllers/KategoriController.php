<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return response()->json(Kategori::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon' => 'nullable|string',
            'jenis_kategori' => 'required|string|max:255',
        ]);

        $kategori = Kategori::create($request->all());

        return response()->json($kategori, 201);
    }
}
