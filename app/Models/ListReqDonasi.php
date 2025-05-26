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

    protected $fillable =[
        'desc_request',
        'id_organisasi',
    ];

    public function Organisasi(){
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
}
