<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "pendapatans";
    protected $primaryKey = "id_pendapatan";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pendapatan',
        'total_pendapatan',
        'id_penitip',
    ];

    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
}
