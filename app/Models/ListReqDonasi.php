<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListReqDonasi extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "list_req_donasis";
    protected $primaryKey = "id_reqDonasi";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_reqDonasi',
        'desc_request',
        'tgl_reqDonasi',
        'id_organisasi',
    ];

    public function Organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
}
