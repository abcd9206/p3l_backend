<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\Organisasi as Authenticatable;

class Organisasi extends Authenticatable
{
    use HasFactory, HasApiTokens;

    public $timestamps = false;

    protected $table = "organisasis";
    protected $primaryKey = "id_organisasi";
    protected $keyType = 'string';

    protected $fillable =[
        'email_organisasi',
        'pass_organisasi',
        'nama_organisasi',
        'alamat_organisasi',
    ];
}
