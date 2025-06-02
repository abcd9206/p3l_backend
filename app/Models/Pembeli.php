<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pembeli extends Authenticatable
{
    use HasFactory, HasApiTokens;
    public $timestamps = false;

    protected $table = "pembelis";
    protected $primaryKey = "id_pembeli";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pembeli',
        'email',
        'password',
        'nama_pembeli',
        'tlpn_pembeli',
        'point_reward',
    ];
}
