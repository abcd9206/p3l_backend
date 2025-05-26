<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function store(Request $request)
    {
        $path = $request->file('foto_produk')->store('images', 'public');

        Gallery::create([
            'id_gambar' => 'G-001',
            'foto_produk' => $path,
            'id_barang' => $request->id_barang,
        ]);
    }

}
