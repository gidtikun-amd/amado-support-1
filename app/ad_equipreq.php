<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad_equipreq extends Model
{
    protected $table = 'ad_equipreq';
    protected $fillable = ['eqreq_id', 'eqreq_no', 'eqreq_date', 'eqreq_status',
    'eqreq_netbudget', 'it_emp_id', 'it_dept_id', 'eqreq_tack', 'eqreq_remark',
    'eqreq_approve_status', 'eqreq_approve_user','eqreq_approve_date'];
}
