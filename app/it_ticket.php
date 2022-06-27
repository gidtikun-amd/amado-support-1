<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_ticket extends Model
{
    protected $table = 'it_ticket';
    protected $fillable = ['it_ticket_id', 'it_ticket_no', 'it_ticket_date', 'it_ticket_end', 'it_emp_id',
    'it_ticket_subject_id', 'it_ticket_detail', 'it_ticket_level', 'it_ticket_status', 'it_ticket_approve',
    'it_ticket_approve_remark', 'it_ticket_approve_date', 'it_ticket_operator', 'it_ticket_assign_date',
    'it_survey_id','survey_status','survey_user','survey_date','it_ticket_callback','it_ticket_status_agree'];
}
