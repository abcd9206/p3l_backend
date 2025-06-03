<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function kirimKeUser(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required|exists:users,id',
            'id_penitip' => '',
            'id_pembeli' => '',
            'judul' => 'required|string',
            'pesan' => 'required|string',
        ]);

        $user = User::find($request->user_id);

        // Kirim push via FCM (jika token tersedia)
        if ($user->fcm_token) {
            $serverKey = env('FCM_SERVER_KEY');

            Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                        'to' => $user->fcm_token,
                        'notification' => [
                            'title' => $request->judul,
                            'body' => $request->pesan,
                        ],
                        'priority' => 'high'
                    ]);
        }

        // Simpan ke database
        $notifikasi = Notifikasi::create([
            'id_pegawai' => $request->id_pegawai,
            'id_penitip' => $request->id_penitip,
            'id_pembeli' => $request->id_pembeli,
            'judul' => $request->judul,
            'pesan' => $request->pesan,
        ]);

        return response()->json([
            'message' => 'Notifikasi berhasil dikirim dan disimpan',
            'data' => $notifikasi
        ]);
    }
}
