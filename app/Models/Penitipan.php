<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penitipan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "penitipans";
    protected $primaryKey = "id_penitipan";

    protected $fillable =[
        'tgl_penitipan',
        'tgl_kadaluarsa',
        'tgl_pengembalian',
        'status_penitipan',
        'id_pegawai',
        'id_barang',
        'id_penitip',
    ];

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function Barang(){
        return $this->hasOne(Barang::class, 'id_barang');
    }

    public function Penitip(){
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
}
