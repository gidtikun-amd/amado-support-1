<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_survey extends Model
{
    protected $table = 'it_survey';
    protected $fillable = ['it_survey_id', 'it_ticket_subject_id', 'it_survey_type', 'it_survey_active'];
}
