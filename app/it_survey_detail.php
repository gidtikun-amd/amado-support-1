<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_survey_detail extends Model
{
    protected $table = 'it_survey_detail';
    protected $fillable = ['it_survey_detail_id', 'it_survey_header_id', 'it_survey_subject_id', 
    'it_survey_subject_level'];
}
