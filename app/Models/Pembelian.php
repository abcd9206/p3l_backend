<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pembelian extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "pembelians";
    protected $primaryKey = "id_pembelian";
    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'id_pembelian',
        'jml_barang',
        'metode_pembayaran',
        'total_pembelian',
        'status_pembayaran',
        'foto_buktiPembayaran',
        'verifikasi_pembayaran',
        'tgl_checkout',
        'tgl_lunas',
        'tgl_selesai',
        'tgl_pengambilan',
        'id_pegawai',
        'id_pembeli',
        'id_barang',
    ];

    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function Pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    public function Barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
