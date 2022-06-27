<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad_jobreq_file extends Model
{
    protected $table = 'ad_jobreq_file';
    protected $fillable = ['jobreq_file_id', 'jobreq_id', 'jobreq_file_name', 'jobreq_file_path'];
}
