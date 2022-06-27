<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_asset_hardware extends Model
{
    protected $fillable = [
        'it_asst_hw_seq','it_assthd_no','it_assetbr_no',
        'it_emp_id','it_hw_seq','it_hw_status','it_locat_id',
        'it_asst_hw_start_date','it_asst_hw_end_date',
        'it_asst_hw_remark','it_asst_hw_status'];
}
