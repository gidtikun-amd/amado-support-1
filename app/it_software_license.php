<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_software_license extends Model
{
    protected $table = 'it_software_license';
    protected $fillable = ['swl_id', 'it_hw_id', 'swl_license', 'swl_status',
    'swl_volumes'];
}
