<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad_equipreq_file extends Model
{
    protected $table = 'ad_equipreq_file';
    protected $fillable = ['eqreq_file_id','eqreq_id','eqreq_file_name','eqreq_file_path'];
}
