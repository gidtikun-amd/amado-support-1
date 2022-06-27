<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad_jobreq_detail extends Model
{
    protected $table = 'ad_jobreq_detail';
    protected $fillable = ['jobreq_id', 'jobreq_line', 'jobreq_detail', 'jobreq_qty', 'jobreq_unit'];
}
