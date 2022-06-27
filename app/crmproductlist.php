<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crmproductlist extends Model
{
    protected $table = 'crmproductlist';
    protected $fillable = ['pdl_id', 'pdl_code', 'pdl_name', 'pdl_rprice', 'pdl_aprice',
        'pdl_unit', 'ctmg_id'];
}
