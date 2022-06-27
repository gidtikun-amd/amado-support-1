<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_jobreq_detail_file extends Model
{
    protected $table = 'it_jobreq_detail_file';
    protected $fillable = ['jobreqd_file', 'jobreq_id', 'jobreqd_filename', 'jobreqd_filepath'];
}
