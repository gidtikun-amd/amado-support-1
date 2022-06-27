<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_systemsts extends Model
{
    protected $table = 'it_systemsts';
    protected $fillable = ['systs_id', 'systs_date', 'systs_title', 'systs_status', 'systs_remark', 'systs_user'];
}
