<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\Pegawai as Authenticatable;

class Penitip extends Authenticatable
{
    use HasFactory, HasApiTokens;

    public $timestamps = false;

    protected $table = "penitips";
    protected $primaryKey = "id_penitip";
    protected $keyType = 'string';

    protected $fillable =[
        'NIK',
        'email_penitip',
        'pass_penitip',
        'nama_penitip',
        'point_reward',
        'saldo',
        'jml_terjual',
        'jml_terdonasi',
        'badge_penitip',
        'ratarata_rating',
    ];
}
