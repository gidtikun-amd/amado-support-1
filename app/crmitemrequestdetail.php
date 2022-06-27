<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crmitemrequestdetail extends Model
{
    protected $table = 'crmitemrequestdetail';
    protected $fillable = ['itmreq_id', 'pdl_id', 'itmreqd_price', 'itmreq_qty', 
    'itmreq_ptotal', 'ctmg_id'];
}
