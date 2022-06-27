<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menus extends Model
{
    protected $table = 'menus';
    protected $fillable = ['menu_id','menu_name','menu_code','menu_group','menu_type','menu_host'];
}
