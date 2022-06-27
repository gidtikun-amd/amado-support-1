<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menus_role extends Model
{
    protected $table = 'menus_role';
    protected $fillable = ['menu_id','check_type','check_value'];
}
