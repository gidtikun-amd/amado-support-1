<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crmitemrequest extends Model
{
    protected $table = 'crmitemrequest';
    protected $fillable = ['itmreq_id', 'itmreq_no', 'itmreq_date', 'it_emp_id', 
    'it_emp_position', 'itmreq_obj', 'pdg_id', 'ctmg_id', 'itmreq_pcode', 
    'itmreq_pbcode', 'itmreq_pname', 'itmreq_punit', 'itmreq_rprice', 
    'itmreq_aprice', 'itmreq_efdate', 'itmreq_exdate', 'itmreq_status', 
    'itmreq_approve', 'itmreq_approve_u', 'itmreq_interface_sts', 
    'itmreq_interface_msg', 'itmreq_vipamdprofit', 'itmreq_vipbfnprofit', 
    'itmreq_stockprofit'];
}
