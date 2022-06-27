<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ad_items extends Model
{
    protected $table = 'ad_items';
    protected $fillable = ['item_id', 'item_code', 'item_name', 'item_unit', 'item_budget', 'item_admin', 'item_active'];
}
