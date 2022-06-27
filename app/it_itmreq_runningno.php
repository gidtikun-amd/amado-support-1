<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_itmreq_runningno extends Model
{
    protected $table = 'it_itmreq_runningno';
    protected $fillable = ['itmreq_rno_year','itmreq_rno_month','itmreq_rno_seq'];
}
