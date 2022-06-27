<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_ticket_subject extends Model
{
    protected $table = 'it_ticket_subject';
    protected $fillable = ['it_ticket_subject_id','it_ticket_subject_name','it_ticket_subject_active','it_survey_id'];
}
