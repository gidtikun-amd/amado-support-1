<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_location extends Model
{
    protected $table = 'it_location';
    protected $fillable = ['it_locat_id','it_locat_name'];
}
