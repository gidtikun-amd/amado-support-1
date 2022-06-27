<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad_equipreq_detail extends Model
{
    protected $table = 'ad_equipreq_detail';
    protected $fillable = ['eqreq_id', 'eqreq_line', 'item_id',
        'item_budget', 'item_ttbudget', 'item_unit',
        'eqreq_qty', 'eqreq_sendto', 'eqreq_outstand'];
}
