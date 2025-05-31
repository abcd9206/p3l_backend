<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "komisis";
    protected $primaryKey = "id_komisi";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_komisi',
        'jml_komisiHunter',
        'jml_komisiPenitip',
        'jml_komisiReuseMart',
        'id_pegawai',
        'id_penitip',
        'id_pembelian',
    ];

    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function Pembelian()
    {
        return $this->hasOne(Pembelian::class, 'id_pembelian');
    }
}
