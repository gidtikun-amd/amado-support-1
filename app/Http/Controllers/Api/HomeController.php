<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\it_jobreq;

class HomeController extends Controller
{
    public function userIndexGetJobreq(){

        $count_wait = it_jobreq::where('jobreq_status',1)->count();
        $count_inpg = it_jobreq::where('jobreq_status',2)->count();
        $count_succ = it_jobreq::where('jobreq_status',3)->count();

        $jobreq = it_jobreq::select(
                    'jobreq_no','jobreq_date','jobreq_other',
                    'jobreq_status','jobreq_obj',                    
                    DB::raw(
                        'CASE jobreq_obj 
                            WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                            WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                            WHEN 3 THEN "ลบผู้ใช้งาน"
                            WHEN 4 THEN "พัฒนาใหม่"
                            WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                            WHEN 6 THEN "แจ้งอุปกรณ์เสีย/ซ่อม"
                            ELSE "อื่นๆ" END jobreq_obj_str'
                    )
                )
                ->whereIn('jobreq_status',[1,2,3])
                ->limit(5)
                ->get();
        
        $result = [ 
            'count_wait' => $count_wait,
            'count_inprogress' => $count_inpg,
            'count_success' => $count_succ,
            'data' => $jobreq
        ];

        return response()->json($result);

    }
}
