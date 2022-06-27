<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_employee extends Model
{
    protected $table = 'it_employee';
    protected $primaryKey = 'it_emp_id';
    protected $fillable = [
        'it_emp_id',
        'it_emp_name_th', 'it_emp_surname_th',
        'it_emp_name_eng', 'it_emp_surname_eng',
        'it_emp_nickname_th','it_emp_nickname_eng',
        'it_emp_gender', 'it_emp_tel',
        'it_emp_email_amd','it_emp_email_second',
        'it_emp_username', 'it_emp_password',
        'it_emp_active',
        'it_dept_id','it_locat_id'];
}
