<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_memo extends Model
{
    protected $table = 'it_memo';
    protected $fillable = ['memo_id', 'memo_no', 'it_emp_id', 'memo_date', 'memo_position', 
    'memo_object', 'memo_tel', 'memo_remark', 'memo_assign', 'memo_status',
    'memo_apprsts', 'memo_appruser'];
}
