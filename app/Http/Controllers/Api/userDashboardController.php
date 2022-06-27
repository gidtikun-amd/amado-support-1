<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CalenderController;
use App\it_jobreq;
use App\it_itmreq;
use App\it_ticket;
use DB;

class userDashboardController extends Controller
{
    public function getDataJobreqList(){

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

    public function getDataItemreqList(){

        $count_wait = it_itmreq::where('itmreq_status',1)->count();
        $count_inpg = it_itmreq::where('itmreq_status',2)->count();
        $count_succ = it_itmreq::where('itmreq_status',3)->count();

        $itmreq = it_itmreq::select(
                        'itmreq_id', 'itmreq_no', 'itmreq_status',
                        'itmreq_obj', 'itmreq_date'
                    )
                    ->whereIn('itmreq_status',[1,2,3])
                    ->limit(5)
                    ->get();
        
        $result = [ 
            'count_wait' => $count_wait,
            'count_inprogress' => $count_inpg,
            'count_success' => $count_succ,
            'data' => $itmreq
        ];

        return response()->json($result);

    }

    public function getDataTicketList(){

        $count_wait = DB::table('it_ticket AS it')
                        ->select('it.it_ticket_id')
                        ->join('it_employee AS ie','it.it_emp_id','ie.it_emp_id')
                        ->where('ie.it_dept_id',session()->get('departmentId'))
                        ->whereIn('it.it_ticket_status',[1,2])
                        ->count();

        $count_inpg = DB::table('it_ticket AS it')
                        ->select('it.it_ticket_id')
                        ->join('it_employee AS ie','it.it_emp_id','ie.it_emp_id')
                        ->where([
                            ['ie.it_dept_id',session()->get('departmentId')],
                            ['it.it_ticket_status',3]
                        ])
                        ->count();

        $count_succ = DB::table('it_ticket AS it')
                        ->select('it.it_ticket_id')
                        ->join('it_employee AS ie','it.it_emp_id','ie.it_emp_id')
                        ->where([
                            ['ie.it_dept_id',session()->get('departmentId')],
                            ['it.it_ticket_status',4]
                        ])
                        ->count();

        $ticket = DB::table('it_ticket AS it')
                    ->select(
                        'it.it_ticket_id AS ticket_id','it.it_ticket_no AS ticket_no',
                        'its.it_ticket_subject_name AS subject_name',
                        'it.it_ticket_status AS ticket_sts','it.it_ticket_level AS ticket_level',
                        DB::raw('DATE_FORMAT(it.it_ticket_date,\'%Y-%m-%d\') AS ticket_date'),
                        DB::raw('CASE it.it_ticket_status
                                    WHEN 1 THEN \'รออนุมัติ\'
                                    WHEN 2 THEN \'รอรับดำเนินการ\'
                                    WHEN 3 THEN \'กำลังดำเนินการ\'
                                    WHEN 4 THEN \'ดำเนินการเรียบร้อย\'
                                    ELSE \'ไม่อนุมัติ/ยกเลิก\' END AS ticket_sts_str')
                    )
                    ->join('it_ticket_subject AS its','it.it_ticket_subject_id','its.it_ticket_subject_id')
                    ->join('it_employee AS ie','it.it_emp_id','ie.it_emp_id')
                    ->where('ie.it_dept_id',session()->get('departmentId'))
                    ->whereIn('it.it_ticket_status',[1,2,3,4])
                    ->limit(5)
                    ->get();
    
            $result = [ 
                'count_wait' => $count_wait,
                'count_inprogress' => $count_inpg,
                'count_success' => $count_succ,
                'data' => $ticket
            ];
    
            return response()->json($result);

    }

    public function getPieAllData(){

        $ticket = DB::table('it_ticket')
                    ->whereIN('it_ticket_status',[1,2,3,4])
                    ->whereYear('it_ticket_date',date('Y'))
                    ->count();        

        $jobreq = DB::table('it_jobreq')
                    ->whereIn('jobreq_status',[1,2,3])
                    ->whereYear('jobreq_date',date('Y'))
                    ->count();        

        $itmreq = DB::table('it_itmreq')
                    ->whereIn('itmreq_status',[1,2,3])
                    ->whereYear('itmreq_date',date('Y'))
                    ->count();
        
        $result = array(
            'labels' => array('Ticket','Job Request','Item Request'),
            'series' => array($ticket,$jobreq,$itmreq)
            // 'series' => array(200,192,100)
        );

        return response()->json($result);

    }

    public function getBarAllDataPerMonth(){

        $category = CalenderController::getNameMonthAll('full');
        $year = date('Y');

        $series = array(
            array('name' => 'ticket', 'data' => array()),
            array('name' => 'Job Request', 'data' => array()),
            array('name' => 'Item Request', 'data' => array())
        );

        for($a1 = 0; $a1 < 3; $a1++){
            for($a2 = 0; $a2 < 12; $a2++){

                if($a1 == 1){
                    $db = array(
                        'table' => 'it_ticket',
                        'in' => array('field' => 'it_ticket_status','values' => array(1,2,3,4)),
                        'date' => 'it_ticket_date'
                    );
                }
                elseif($a1 == 2){
                    $db = array(
                        'table' => 'it_jobreq',
                        'in' => array('field' => 'jobreq_status','values' => array(1,2,3)),
                        'date' => 'jobreq_date'
                    );
                }
                else{
                    $db = array(
                        'table' => 'it_itmreq',
                        'in' => array('field' => 'itmreq_status','values' => array(1,2,3)),
                        'date' => 'itmreq_date'
                    );
                }

                $query = DB::table($db['table'])
                            ->whereIn($db['in']['field'],$db['in']['values'])
                            ->whereYear($db['date'],date('Y'))
                            ->whereMonth($db['date'],str_pad(($a2+1),2,'0',STR_PAD_LEFT))
                            ->count();
                // $query = rand(50,1000);
                array_push($series[$a1]['data'],$query);

            }
        }

        $result = array(
            'serise' => $series,
            'category' => $category
        );

        return response()->json($result);

    }
}
