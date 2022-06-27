<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad_jobreq extends Model
{
    protected $table = 'ad_jobreq';
    protected $fillable = ['jobreq_id', 'jobreq_no', 'jobreq_date', 'it_emp_id',
        'it_emp_position', 'jobreq_obj', 'jobreq_other', 'jobreq_demand',
        'jobreq_reason', 'jobreq_status', 'jobreq_assign', 'jobreq_remark',
        'log_session'];
}
