<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "galleries";
    protected $primaryKey = "id_gambar";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_gambar',
        'foto_barang',
        'id_barang',
    ];

    public function Barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
