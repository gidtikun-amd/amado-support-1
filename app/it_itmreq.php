<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_itmreq extends Model
{
    protected $table = 'it_itmreq';
    protected $fillable = ['itmreq_id', 'itmreq_no', 'itmreq_date', 'it_emp_id', 'it_emp_position', 
    'itmreq_obj', 'itmreq_status', 'itmreq_assign', 'itmreq_approve_sts', 
    'itmreq_approve_emp', 'itmreq_approve_date','itmreq_remark'];
}
