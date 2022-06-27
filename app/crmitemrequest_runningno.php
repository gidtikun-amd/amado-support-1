<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crmitemrequest_runningno extends Model
{
    protected $table = 'crmitemrequest_runningno';
    protected $fillable = ['itmreq_rno_year', 'itmreq_rno_month', 'itmreq_rno_seq'];
}
