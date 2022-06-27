<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crmcustomergroup extends Model
{
    protected $table = 'crmcustomergroup';
    protected $fillable = ['ctmg_id','ctmg_name'];
}
