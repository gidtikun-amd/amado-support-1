<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad_equipreq_runningno extends Model
{
    protected $table = 'ad_equipreq_runningno';
    protected $fillable = ['eqreq_rno_year', 'eqreq_rno_month', 'eqreq_rno_seq'];
}
