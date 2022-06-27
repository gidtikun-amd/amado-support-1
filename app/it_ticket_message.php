<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_ticket_message extends Model
{
    protected $table = 'it_ticket_message';
    protected $fillable = ['tkms_id', 'it_ticket_id', 'tkms_user', 'tkms_text'];
}
