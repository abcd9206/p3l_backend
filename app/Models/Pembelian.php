<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pembelian extends Model
{
    use HasFactory;

    protected $fillable =[
        'id_pembelian',
        'jml_barang',
        'metode_pembayaran',
        'total_pembelian',
        'status_pembayaran',
        'foto_buktiPembayaran',
        'verifikasi_pembayaran',
        'status_pembelian',
        'tgl_pembelian',
        'tgl_selesai',
        'tgl_pengambilan',
        'status_proses',
        'id_pegawai',
        'id_pembeli',
    ];

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function Pembeli(){
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
}
