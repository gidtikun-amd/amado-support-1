<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_assetborrow_runningno extends Model
{
    protected $table = 'it_assetborrow_runningno';
    protected $fillable = ['asstbr_rno_year','asstbr_rno_month','asstbr_rno_seq'];
}
