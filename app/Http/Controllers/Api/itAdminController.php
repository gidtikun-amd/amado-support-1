<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class itAdminController extends Controller
{
    public function getJobreqAlert(){

        $result = DB::table('it_jobreq AS a')
            ->select(
                    'a.jobreq_id','a.jobreq_other','a.jobreq_demand','a.jobreq_date','c.it_dept_name',
                    DB::raw('CONCAT(b.it_emp_name_th," ",b.it_emp_surname_th) it_emp_fullname'),
                    DB::raw(
                        'CASE a.jobreq_obj WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                            WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                            WHEN 3 THEN "ลบผู้ใช้งาน"
                            WHEN 4 THEN "พัฒนาใหม่"
                            WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                            WHEN 6 THEN "แจ้งอุปกรณ์เสีย/ซ่อม"
                            ELSE "อื่นๆ" END jobreq_obj'
                    )
                )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->join('it_department AS c','b.it_dept_id','c.it_dept_id')
            ->where('a.jobreq_status',1)
            ->orderBy('a.jobreq_date','DESC')
            ->limit(5)
            ->get();
        
        $count = DB::table('it_jobreq')
            ->select('jobreq_id')
            ->where('jobreq_status',1)
            ->count();
        
        return response()->json([ 'list' => $result, 'count' => $count ]);

    }
}
