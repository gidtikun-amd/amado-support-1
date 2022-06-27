<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_memo_runningno extends Model
{
    protected $table = 'it_memo_runningno';
    protected $fillable = ['memo_rno_year', 'memo_rno_month', 'memo_rno_seq'];
}
