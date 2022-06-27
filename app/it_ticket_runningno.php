<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_ticket_runningno extends Model
{
    protected $table = 'it_ticket_runningno';
    protected $fillable = ['ittk_rno_year','ittk_rno_month','ittk_rno_seq'];
}
