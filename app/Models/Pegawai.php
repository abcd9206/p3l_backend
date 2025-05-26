<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\Pegawai as Authenticatable;

class Pegawai extends Authenticatable
{
    use HasFactory, HasApiTokens;

    public $timestamps = false;

    protected $table = "pegawais";
    protected $primaryKey = "id_pegawai";

    protected $fillable =[
        'email_pegawai',
        'pass_pegawai',
        'jabatan',
        'nama_pegawai',
        'jabatan',
        'tgl_lahir',
    ];
}
