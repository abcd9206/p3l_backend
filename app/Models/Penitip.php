<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Pegawai;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Penitip extends Authenticatable
{
    use HasFactory, HasApiTokens;

    public $timestamps = false;

    protected $table = "penitips";
    protected $primaryKey = "id_penitip";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_penitip',
        'NIK',
        'email',
        'password',
        'nama_penitip',
        'point_reward',
        'saldo',
        'jml_terjual',
        'jml_terdonasi',
        'badge_penitip',
        'ratarata_rating',
    ];

    public function Pendapatan()
    {
        return $this->belongsTo(Pendapatan::class, 'id_pendapatan');
    }
}
