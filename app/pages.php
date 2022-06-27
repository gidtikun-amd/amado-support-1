<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pages extends Model
{
    protected $table = 'pages';
    protected $fillable = ['page_id','page_name','page_view','page_url'];
}
