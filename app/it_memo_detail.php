<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_memo_detail extends Model
{
    protected $table = 'it_memo_detail';
    protected $fillable = ['memo_id', 'memo_line', 'memo_detail', 'created_at', 'updated_at'];
}
