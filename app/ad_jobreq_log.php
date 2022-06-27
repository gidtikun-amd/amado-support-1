<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad_jobreq_log extends Model
{
    protected $table = 'ad_jobreq_log';
    protected $fillable = ['jobreq_id', 'jobreq_no', 'jobreq_action', 'jobreq_user'];
}
