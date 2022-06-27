<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_itmreq_file extends Model
{
    protected $table = 'it_itmreq_file';
    protected $fillable = ['itmreq_file_id','itmreq_id','itmreq_file_name','itmreq_file_path'];
}
