<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskusi extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "diskusis";
    protected $primaryKey = "id_diskusi";

    protected $fillable =[
        'comment',
        'id_pegawai',
        'id_barang',
        'id_pembeli',
    ];

    public function Pegawai(){
        return $this->belongsto(Pegawai::class, 'id_pegawai');
    }

    public function Parang(){
        return $this->belongsto(Barang::class, 'id_barang');
    }

    public function Pembeli(){
        return $this->belongsto(Pembeli::class, 'id_pembeli');
    }
}
