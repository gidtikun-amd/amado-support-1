<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_survey_runningno extends Model
{
    protected $table = 'it_survey_runningno';
    protected $fillable = ['survey_rno_type', 'survey_rno_year', 'survey_rno_month', 'survey_rno_seq'];
}
