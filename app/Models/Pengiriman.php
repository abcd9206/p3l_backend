<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "pengirimen";
    protected $primaryKey = "id_pengiriman";

    protected $fillable =[
        'status_pengiriman',
        'id_pegawai',
        'id_alamat',
        'id_pembelian',
    ];

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function Alamat(){
        return $this->belongsTo(Alamat::class, 'id_alamat');
    }

    public function Pembelian(){
        return $this->hasOne(Pembelian::class, 'id_pembelian');
    }
}
