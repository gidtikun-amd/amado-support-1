<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pages_role extends Model
{
    protected $table = 'pages_role';
    protected $fillable = ['page_id','check_type','check_value',
    'ac_read','ac_create','ac_update','ac_delete'];
}
