<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_itmreq_detail extends Model
{
    protected $table = 'it_itmreq_detail';
    protected $fillable = ['itmreq_id','itmreq_line','itmreq_barcode','itmreq_item',
        'itmreq_action','itmreq_prom_sdate','itmreq_prom_edate','itmreq_app',
        'created_at', 'updated_at'];
}
