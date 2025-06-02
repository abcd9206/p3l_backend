<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Organisasi extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'organisasis';
    protected $primaryKey = 'id_organisasi';
    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'id_organisasi',
        'id_organisasi',
        'email',
        'password',
        'nama_organisasi',
        'alamat_organisasi',
    ];
}
