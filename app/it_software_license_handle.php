<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_software_license_handle extends Model
{
    protected $table = 'it_software_license_handle';
    protected $fillable = ['swlh_id', 'swl_id', 'it_emp_id', 'it_locat_id',
    'swlh_status', 'swlh_start_date', 'swlh_end_date', 'swlh_user'];
}
