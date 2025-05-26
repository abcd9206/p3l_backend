<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "pendapatans";
    protected $primaryKey = "id_totalPendapatan";

    protected $fillable =[
        'total_pendapatan',
        'id_penitip',
    ];

    public function Penitip(){
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
}
