<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TukarMerch extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "tukar_merches";
    protected $primaryKey = "id_tukarMerch";

    protected $fillable =[
        'tgl_tukarMerch',
        'tgl_ambil',
        'status_merch',
        'id_pegawai',
        'id_pembeli',
        'id_merch',
        'id_penitip',
    ];

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_tukarMerch');
    }

    public function Pembeli(){
        return $this->belongsTo(Pembeli::class, 'id_tukarMerch');
    }

    public function Merch(){
        return $this->belongsTo(Merch::class, 'id_merch');
    }

    public function Penitip(){
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
}
