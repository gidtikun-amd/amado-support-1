<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_ticket_log extends Model
{
    protected $table  = 'it_ticket_log';
    protected $fillable = ['it_ticket_id','it_ticket_status','it_ticket_status_date','it_ticket_action_user'];
}
