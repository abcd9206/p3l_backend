<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penitip extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "penitips";
    protected $primaryKey = "id_penitip";

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
