<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_hardware extends Model
{
    protected $table = 'it_hardware';
    protected $fillable = ['it_hw_seq','it_hw_number','it_hw_name','it_hw_type_id','it_hw_serial','it_hw_status',
    'it_hw_group','it_hw_remark','it_hw_price','it_hw_expire', 'it_hw_warrantry'
    ,'it_hw_sdate', 'it_hw_edate'
    ];
}
