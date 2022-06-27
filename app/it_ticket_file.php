<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_ticket_file extends Model
{
    protected $table = 'it_ticket_file';
    protected $fillable = ['it_ticket_file_id', 'it_ticket_id', 'it_ticket_file', 'it_ticket_file_path'];
}
