<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_admin extends Model
{
    protected $table = 'it_admin';
    protected $fillable = ['it_ad_user','it_ad_pass','it_ad_name','it_ad_active','it_ad_role'];
}
