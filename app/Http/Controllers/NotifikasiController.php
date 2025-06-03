<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pegawai;
use App\Models\Penitip;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotifikasiController extends Controller
{
    public function kirimKeUser(Request $request)
{
    $request->validate([
        'judul' => 'required|string',
        'pesan' => 'required|string',
    ]);

    $tokens = [];
    $saved = [];

    if ($request->has('id_pegawai')) {
        $pegawai = \App\Models\Pegawai::find($request->id_pegawai);
        if ($pegawai) {
            $tokens[] = $pegawai->fcm_token;
            $saved[] = \App\Models\Notifikasi::create([
                'id_pegawai' => $pegawai->id_pegawai,
                'judul' => $request->judul,
                'pesan' => $request->pesan,
            ]);
        }
    }

    if ($request->has('id_penitip')) {
        $penitip = \App\Models\Penitip::find($request->id_penitip);
        if ($penitip) {
            $tokens[] = $penitip->fcm_token;
            $saved[] = \App\Models\Notifikasi::create([
                'id_penitip' => $penitip->id_penitip,
                'judul' => $request->judul,
                'pesan' => $request->pesan,
            ]);
        }
    }

    if ($request->has('id_pembeli')) {
        $pembeli = \App\Models\Pembeli::find($request->id_pembeli);
        if ($pembeli) {
            $tokens[] = $pembeli->fcm_token;
            $saved[] = \App\Models\Notifikasi::create([
                'id_pembeli' => $pembeli->id_pembeli,
                'judul' => $request->judul,
                'pesan' => $request->pesan,
            ]);
        }
    }

    // Kirim FCM ke semua yang punya token
    $serverKey = env('FCM_SERVER_KEY');
    foreach ($tokens as $token) {
        if ($token) {
            \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $token,
                'notification' => [
                    'title' => $request->judul,
                    'body' => $request->pesan,
                ],
                'priority' => 'high',
            ]);
        }
    }

    return response()->json([
        'message' => 'Notifikasi dikirim ke semua user terkait.',
        'data' => $saved
    ]);
}

}
