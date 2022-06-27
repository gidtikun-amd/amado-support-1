<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_memo_file extends Model
{
    protected $table = 'it_memo_file';
    protected $fillable = ['mm_file_id', 'memo_id', 'mm_file_name', 'mm_file_path'];
}
