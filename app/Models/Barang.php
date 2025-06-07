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
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'tgl_garansi',
        'rating_barang',
        'tgl_didonasikan',
        'tgl_terdonasi',
        'harga_barang',
        'desc_barang',
        'total_barang',
        'total_keseluruhan',
        'id_pegawai',
        'id_penitip',
        'id_kategori',
        'id_donasi',
        'id_pembeli',
    ];

    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function Kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function Pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function Donasi()
    {
        return $this->hasOne(Donasi::class, 'id_donasi');
    }
}
