<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_assethandle_runningno extends Model
{
    protected $table = 'it_assethandle_runningno';
    protected $fillable = ['assthd_rno_year','assthd_rno_month','assthd_rno_seq'];
}
