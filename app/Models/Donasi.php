<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "donasis";
    protected $primaryKey = "id_donasi";
    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'id_donasi',
        'nama_penerima',
        'tgl_donasi',
        'id_pegawai',
        'id_penitip',
    ];

    public function Pegawai()
    {
        return $this->belongsto(Pegawai::class, 'id_pegawai');
    }

    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
}
