<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_jobreq_runningno extends Model
{
    protected $table = 'it_jobreq_runningno';
    protected $fillable = ['jobreq_rno_year','jobreq_rno_month','jobreq_rno_seq'];
}
