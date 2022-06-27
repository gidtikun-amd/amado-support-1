<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_memo_log extends Model
{
    protected $table = 'it_memo_log';
    protected $fillable = ['memo_id', 'memo_no', 'memo_action', 'memo_user'];
}
