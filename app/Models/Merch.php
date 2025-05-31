<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merch extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "merches";
    protected $primaryKey = "id_merch";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_merch',
        'nama_merch',
        'stok_merch',
        'id_pegawai',
    ];

    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
