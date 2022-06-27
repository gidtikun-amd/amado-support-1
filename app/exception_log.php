<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class exception_log extends Model
{
    protected $table = 'exception_log';
    protected $fillable = ['ex_id', 'ex_module', 'ex_action', 'ex_message'];
}
