<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CalenderController;
use DB;
use Session;

class adminDashboardController extends Controller
{
    public function getCountTicketNotDone(){
        $count = DB::table('it_ticket')->select('it_ticket_id')->where('it_ticket_status',2)->count();        
        return response()->json(['count' => $count]);
    }

    public function getCountJobreqNotDone(){
        $count = DB::table('it_jobreq')->select('jobreq_id')->where('jobreq_status',1)->count();        
        return response()->json(['count' => $count]);
    }

    public function getCountItemreqNotDone(){
        $count = DB::table('it_itmreq')->select('itmreq_id')->where('itmreq_status',1)->count();        
        return response()->json(['count' => $count]);
    }

    public function getCountNumberOfEmployees(){
        $count = DB::table('it_employee')->select('it_emp_id')->where('it_emp_active',1)->count();        
        return response()->json(['count' => $count]);
    }

    public function getDataGraphAssetStatus(){

        $result = array(
            'names' => array('ไม่ใช้งาน','ใช้งาน','สำรอง','ส่งซ่อม','ออกจำหน่าย','ยืมใช้งาน'),
            'values' => array()
        );
        
        for($i = 0; $i < 6; $i++){

            $query = DB::table('it_hardware')
                    ->select(DB::raw('IFNULL(COUNT(it_hw_seq),0) as chw'))
                    ->where('it_hw_status',$i)
                    ->first();

            array_push($result['values'],$query->chw);
            // array_push($result['values'],rand(50,1000));

        }

        return response()->json($result);

    }

    public function getDataGraphTicketStatus(){

        $result = array(
            'names' => array("ไม่อนุมัติ","รออนุมัติ","รอรับดำเนินการ","กำลังดำเนินการ","ดำเนินการเรียบร้อย"),
            'values' => array(
                0 => array(),
                1 => array(),
                2 => array(),
                3 => array(),
                4 => array()
            )
        );

        for($i = 0; $i < 5; $i++){

            $query = DB::table('it_ticket')                     
                    ->select(DB::raw('IFNULL(COUNT(it_ticket_id),0) as ctk'))
                    ->where('it_ticket_status',$i)
                    ->whereBetween('it_ticket_date',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')])
                    ->first();
            
            array_push($result['values'][$i],$query->ctk);
            // array_push($result['values'][$i],rand(50,1000));

        }

        return response()->json($result);

    }

    public function getDataGraphJobreqStatus(){

        $result = array(
            'names' => array("ยกเลิก","รอรับดำเนินการ","กำลังดำเนินการ","ดำเนินการเรียบร้อย"),
            'values' => array(
                0 => array(),
                1 => array(),
                2 => array(),
                3 => array()
            )
        );

        for($i = 0; $i < 4; $i++){

            $query = DB::table('it_jobreq')                     
                    ->select(DB::raw('IFNULL(COUNT(jobreq_id),0) as cjr'))
                    ->where('jobreq_status',$i)
                    ->whereBetween('jobreq_date',[date('Y-m-01'),date('Y-m-31')])
                    ->first();
                    
            array_push($result['values'][$i],$query->cjr);
            // array_push($result['values'][$i],rand(50,1000));
        
        }

        return response()->json($result);

    }

    public function getDataGraphItemreqStatus(){

        $result = array(
            'names' => array("ยกเลิก","รอรับดำเนินการ","กำลังดำเนินการ","ดำเนินการเรียบร้อย"),
            'values' => array(
                0 => array(),
                1 => array(),
                2 => array(),
                3 => array()
            )
        );

        for($i = 0; $i < 4; $i++){
            

            $query = DB::table('it_itmreq')                     
                    ->select(DB::raw('IFNULL(COUNT(itmreq_id),0) as citm'))
                    ->where('itmreq_status',$i)
                    ->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')])
                    ->first();
                    
            array_push($result['values'][$i],$query->citm);
            // array_push($result['values'][$i],rand(50,1000));
        
        }

        return response()->json($result);

    }

    public function getDataCountSoftwareLicense(){

        $result = DB::table('it_hardware as ih')
                    ->select(
                        'ih.it_hw_seq as asset_id',
                        'ih.it_hw_name as asset_name',
                        DB::raw('ifnull(count(isl.swl_id),0) as clicense')
                    )
                    ->leftJoin('it_software_license as isl','ih.it_hw_seq','isl.it_hw_id')
                    ->whereIn('ih.it_hw_type_id',[20,26])
                    ->groupBy('ih.it_hw_seq','ih.it_hw_name')
                    ->orderBy('clicense','DESC')
                    ->orderBy('asset_name','ASC')
                    ->get();
        
        return response()->json($result);

    }

    public function getDataSummaryAssetAndIssue(){

        $ticket = DB::table('it_ticket as innit')
                    ->select('innit.it_ticket_subject_id',DB::raw('sum(innit.it_ticket_id) as cticket'))
                    ->where('innit.it_ticket_status','!=',0)
                    ->whereBetween('innit.it_ticket_date',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')])
                    ->groupBy('innit.it_ticket_subject_id');
        
        $SubQueryTicket = $ticket->toSql();
        $bindings = $ticket->getBindings();

        foreach($bindings as $binding)
            $SubQueryTicket = preg_replace('/\?/', "'" . $binding ."'", $SubQueryTicket, 1);
        
        $issue = DB::table('it_ticket_subject as its')
                    ->select(
                        'its.it_ticket_subject_id as subject_id',
                        'its.it_ticket_subject_name as subject_name',
                        DB::raw('ifnull(it.cticket,0) as cticket')
                    )
                    ->leftJoin(DB::raw('('.$SubQueryTicket.') as it'),'its.it_ticket_subject_id','it.it_ticket_subject_id')
                    ->where('its.it_ticket_subject_active',1)
                    ->groupBy('its.it_ticket_subject_id','its.it_ticket_subject_name','cticket')
                    ->get();
        
        $asset = array(
            array('hw_status' => 'ไม่ใช้งาน', 'c_status' => 0),
            array('hw_status' => 'ใช้งาน', 'c_status' => 0),
            array('hw_status' => 'สำรอง', 'c_status' => 0),
            array('hw_status' => 'ส่งซ่อม', 'c_status' => 0),
            array('hw_status' => 'ออกจำหน่าย', 'c_status' => 0),
            array('hw_status' => 'ยืมใช้งาน', 'c_status' => 0)
        );

        for($i = 0; $i < 6; $i++){

            $count = DB::table('it_asset_hardware')
                        ->where('it_hw_status',$i)
                        ->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')])
                        ->count();
            
            $asset[$i]['c_status'] = $count;

        }

        $result = array('issue' => $issue, 'asset' => $asset);
        return response()->json($result);

    }

    public function getDataAssetType(){
        
        $type = DB::table('it_hardware as ih')
                        ->select(
                            'ih.it_hw_type_id as type_id','iht.it_hw_type_name as type_name',
                            DB::raw('count(ih.it_hw_seq) as casset')
                        )
                        ->join('it_hardware_type as iht','ih.it_hw_type_id','iht.it_hw_type_id')
                        ->groupBy('type_id','type_name')
                        ->orderBy('type_name')
                        ->get();

        return response()->json($type);

    }

    public function getDataGrowTicketAndAsset(){

        $category = CalenderController::getNameMonthAll('full');

        $series = array(
            array( 'name' => 'ปัญหาที่เกิดขึ้น', 'data' => array() ),
            array( 'name' => 'ทรัพย์สินที่เกิดขึ้น', 'data' => array()  )
        );

        for($i = 1; $i <= 12; $i++){

            $ticket = DB::table('it_ticket')
                        ->where('it_ticket_status','!=',0)
                        ->whereMonth('it_ticket_date',$i)
                        ->count();
            
            $asset = DB::table('it_asset_hardware')
                        ->whereMonth('created_at',$i)
                        ->count();
            
            array_push($series[0]['data'],$ticket);
            array_push($series[1]['data'],$asset);
            // array_push($series[0]['data'],rand(20,300));
            // array_push($series[1]['data'],rand(20,300));

        }

        $result = array( 'category' => $category, 'series' => $series );
        return response()->json($result);

    }
}
