<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_department extends Model
{
    protected $table = 'it_department';    
    protected $primaryKey = 'it_dept_id';
    protected $fillable = ['it_dept_id','it_dept_name'];
}
