<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "kategoris";
    protected $primaryKey = "id_kategori";
    
    protected $fillable =[
        'jenis_kategori',
    ];
}
