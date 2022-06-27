<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_external_problem extends Model
{
    protected $table = 'it_external_problem';
    protected $fillable = ['ext_id', 'ext_date', 'ext_no', 'ext_subject', 'ext_title', 'ext_detail', 'ext_user'];
}
