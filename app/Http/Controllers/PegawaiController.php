<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function store(Request $request)
    {
        $count = Pegawai::count() + 1;

        // Format ID: P-001, P-002, dst
        $id_pegawai = 'P-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        Pegawai::create([
            'id_pegawai'    => $id_pegawai,
            'jabatan'       => $request->jabatan,
            'email_pegawai' => $request->email_pegawai,
            'pass_pegawai'  => $request->pass_pegawai,
            'tgl_lahir'     => $request->tgl_lahir,
        ]);

        return redirect()->back()->with('success', 'Pegawai berhasil ditambahkan.');
    }
}
