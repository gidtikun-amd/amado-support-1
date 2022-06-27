<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_survey_header extends Model
{
    protected $table = 'it_survey_header';
    protected $fillable = ['it_survey_header_id', 'it_survey_id', 'it_survey_ref_id', 
    'it_survey_level_overall', 'it_survey_remark', 'it_survey_result', 'it_survey_no'];
}
