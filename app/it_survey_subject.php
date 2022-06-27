<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_survey_subject extends Model
{
    protected $table = 'it_survey_subject';
    protected $fillable = ['it_survey_subject_id', 'it_survey_id', 'it_survey_subject_text', 'it_survey_subject_active'];
}
