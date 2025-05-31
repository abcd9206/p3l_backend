<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "alamats";
    protected $primaryKey = "id_alamat";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_alamat',
        'alamat_pembeli',
        'id_pembeli'
    ];

    public function Pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
}
