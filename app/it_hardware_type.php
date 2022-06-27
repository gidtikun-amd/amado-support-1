<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_hardware_type extends Model
{
    protected $table = 'it_hardware_type';
    protected $fillable = ['it_hw_type_id','it_hw_type_name'];
}
