<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_hardware_file extends Model
{
    protected $table = 'it_hardware_file';
    protected $fillable = ['it_hw_file_id','it_hw_seq','it_hw_file_name','it_hw_file_path'];
}
