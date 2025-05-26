<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "barangs";
    protected $primaryKey = "id_barang";

    protected $fillable =[
        'nama_barang',
        'status_garansi',
        'tgl_garansi',
        'rating_barang',
        'tgl_didonasikan',
        'id_pegawai',
        'id_penitip',
        'id_kategori',
        'id_donasi',
        'id_pembelian',
    ];

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function Penitip(){
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function Kategori(){
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function Pembelian(){
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function Donasi(){
        return $this->hasOne(Donasi::class, 'id_donasi');
    }
}
