<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_itmreq_log extends Model
{
    protected $table = 'it_itmreq_log';
    protected $fillable = ['itmreq_id','itmreq_no','itmreq_action','itmreq_user'];
}
