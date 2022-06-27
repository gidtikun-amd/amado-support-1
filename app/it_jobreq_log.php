<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_jobreq_log extends Model
{
    protected $table = 'it_jobreq_log';
    protected $fillable = ['jobreq_id','jobreq_no','jobreq_action','jobreq_user','created_at','updated_at'];
}
