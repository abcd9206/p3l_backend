<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\Pembeli as Authenticatable;

class Pembeli extends Authenticatable
{
    use HasFactory, HasApiTokens;

    public $timestamps = false;

    protected $table = "pembelis";
    protected $primaryKey = "id_pembeli";
    protected $keyType = 'string';

    protected $fillable = [
        'email_pembeli',
        'pass_pembeli',
        'nama_penitip',
        'tlpn_pembeli',
        'point_reward',
    ];
}
