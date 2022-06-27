<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crmproductgroup extends Model
{
    protected $table = 'crmproductgroup';
    protected $fillable = ['pdg_id','pdg_name'];
}
