<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pegawai extends Authenticatable
{
    use HasFactory, HasApiTokens;
    public $timestamps = false;

    protected $table = "pegawais";
    protected $primaryKey = "id_pegawai";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pegawai',
        'email',
        'password',
        'jabatan',
        'nama_pegawai',
        'tgl_lahir',
    ];
}
