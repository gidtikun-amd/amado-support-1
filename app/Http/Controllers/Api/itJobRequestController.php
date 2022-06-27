<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\notifyLineController;
use App\Http\Controllers\Security\LoginController;
use App\Http\Controllers\Api\exceptionController;
use App\it_jobreq;
use App\it_jobreq_detail;
use App\it_jobreq_runningno;
use App\it_jobreq_file;
use App\it_jobreq_log;
use App\it_jobreq_detail_file;
use Session;
use File;

class itJobRequestController extends Controller
{
    private $notify_line;
    private $exceptionController;

    public function __construct(){
        $this->notify_line = new notifyLineController();
    }

    private function saveLogError($dataset){
        
        $this->exceptionController = new exceptionController();
        $errormsg = explode('(SQL:',$dataset['message']);

        $this->exceptionController->saveLogQueryException(array(
            'module' => $dataset['module'],
            'action' => $dataset['action'],
            'message' => $dataset['message']
        ));

        return $errormsg[0];

    }

    private function saveFileJobreqDetail($id,$file){
        
        $file_newname = $id.'-'.$file[0];

        if($file[1]->move(public_path('upload/jobreqdetail'),$file_newname)){
            $upload = new it_jobreq_detail_file([
                'jobreq_id' => $id, 
                'jobreqd_filename' => $file_newname, 
                'jobreqd_filepath' => 'upload/jobreqdetail/'.$file_newname
            ]);
            $upload->save();
        }
        
        return;

    }

    public function store(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('Status' => false, 'exception' => 'login.fail'));
            
        $date = [ 'year' => date('y'), 'month' => date('m') ];
        $run_no = $this->getRunningNumber($date);
        $paper_no = 'IT-JOB-'.$date['year'].$date['month'].str_pad($run_no,7,'0',STR_PAD_LEFT);

        try{

            $jobreq = new it_jobreq([
                'jobreq_no' => $paper_no,
                'jobreq_date' => $request->get('date'),
                // 'it_emp_id' => $request->get('emp_id'),
                'it_emp_id' => session()->get('user'),
                'it_emp_position' => $request->get('position'),
                'jobreq_obj' => $request->get('obj'),
                'jobreq_other' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->get('other')),
                'jobreq_demand' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->get('demand')),
                'jobreq_reason' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->get('reason')),
                // 'log_session' => $request->get('emp_id'),
                'log_session' => session()->get('user'),
                'jobreq_status' => 1
            ]);

            $jobreq->save();

        }
        catch(QueryException $e){

            $errormsg = $this->saveLogError(array(
                'module' => 'jobreq_by_user',
                'action' => 'insert_db',
                'message' => $e->getMessage()
            ));

            return response()->json(array(
                'Status' => false,
                'exception' => 'sqlError',
                'message' => $errormsg
            )); 

        }

        $inst_id = $jobreq->id;
        $values = [];
        $line = 1;

        foreach( $request->get('list') as $val ){
            array_push($values,[
                    'jobreq_id' => $inst_id,
                    'jobreq_line' => $line++,
                    'jobreq_detail' => preg_replace('/[\x{200B}-\x{200D}]/u','',$val[0]),
                    'jobreq_qty' => $val[1],
                    'jobreq_unit' => preg_replace('/[\x{200B}-\x{200D}]/u','',$val[2]),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        try{

            $jobreq_detail = it_jobreq_detail::insert($values);
            
            if(!empty($request['files']))
                for($i = 0; $i < count($request['files']); $i++)
                    $this->saveFileJobreqDetail($inst_id,$request['files'][$i]);

            $notify_msg = 'คุณ '.session()->get('name').' แผนก '.session()->get('department').' ขอเปิด Job Request เอกสารเลขที่ '.$paper_no;
            $this->notify_line->call_notify_group_ticket_it_support('jobreq',array('message' => $notify_msg));
            $this->setLog([ 'id' => $inst_id, 'no' => $paper_no, 'action' => 'สร้าง' ]);
            
            return response()->json([
                'Status' => true, 
                'exception' => '',
                'message' => '',
                'paper_id' => $paper_no
            ]);

        }
        catch(QueryException $e){

            $jobreq_update = it_jobreq::where('jobreq_id',$inst_id)
                ->update([
                    'jobreq_status' => 0
                ]);

            $errormsg = $this->saveLogError(array(
                'module' => 'jobreq_by_user',
                'action' => 'insert_db',
                'message' => $e->getMessage()
            ));

            return response()->json(array(
                'Status' => false,
                'exception' => 'sqlError',
                'message' => $errormsg
            )); 

        }

    }

    private function getRunningNumber($date){

        $running_no = it_jobreq_runningno::select('jobreq_rno_seq')
            ->where('jobreq_rno_year',$date['year'])
            ->where('jobreq_rno_month',$date['month']);

        $count_rows = $running_no;
        $checkEmpty = $count_rows = $count_rows->count();

        if( $checkEmpty > 0 ){

            $running_no  = $running_no->get();
            $number = $running_no[0]['jobreq_rno_seq']+1;

            $running_upd = it_jobreq_runningno::where('jobreq_rno_year',$date['year'])
                ->where('jobreq_rno_month',$date['month'])
                ->update([
                    'jobreq_rno_seq' => $number
                ]);


            return $number;

        }
        else{

            $running_inst = new it_jobreq_runningno([
                'jobreq_rno_year' => $date['year'],
                'jobreq_rno_month' => $date['month'],
                'jobreq_rno_seq' => 1
            ]);

            $running_inst->save();
            return 1;

        }

    }

    public function getJobRequestUserHistory(Request $request){

        $history = DB::table('it_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no','a.jobreq_status',
                DB::raw('DATE_FORMAT(jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw('CONCAT(b.it_emp_name_th," ",b.it_emp_surname_th) emp_fullname'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                        WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                        WHEN 3 THEN "ลบผู้ใช้งาน"
                        WHEN 4 THEN "พัฒนาใหม่"
                        WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                        WHEN 6 THEN "สร้างอีเมลใหม่"
                        ELSE "อื่นๆ" END jobreq_obj'
                ),
                DB::raw(
                    'CASE a.jobreq_status
                        WHEN 1 THEN "รอดำเนินการ"
                        WHEN 2 THEN "กำลังดำเนินการ"
                        WHEN 3 THEN "ดำเนินการเรียบร้อย"
                        ELSE "ยกเลิก" END jobreq_status_str'
                )
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->where('b.it_dept_id',$request->dept_id)
            ->orderBy('a.jobreq_date','DESC')
            ->paginate($request->paginate);

        return response()->json($history);

    }

    public function getJobRequestSurveyList(Request $request){

        $list = DB::table('it_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no','a.jobreq_status',
                DB::raw('DATE_FORMAT(jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw('CONCAT(b.it_emp_name_th," ",b.it_emp_surname_th) emp_fullname'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                        WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                        WHEN 3 THEN "ลบผู้ใช้งาน"
                        WHEN 4 THEN "พัฒนาใหม่"
                        WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                        WHEN 6 THEN "สร้างอีเมลใหม่"
                        ELSE "อื่นๆ" END jobreq_obj'
                )
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->where([
                ['a.jobreq_obj','4'],
                ['a.jobreq_status','3'],
                ['a.jobreq_svsts',$request->surv_sts]
            ]);
//            ->where('a.jobreq_assign',session()->get('user'));

        if(!empty($request->search))
            $list = $list->where('a.jobreq_no','LIKE','%'.$request->search.'%');

        if( !empty($request->searchEmp) ){
            $list = $list->whereRaw('(
                b.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or b.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or b.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        if($request->page == 'user')
            $list = $list->where('b.it_dept_id',$request->dept_id);

        $list = $list->orderBy('a.jobreq_date','DESC')
            ->paginate($request->paginate);

        return response()->json($list);

    }

    public function searchJobRequestUserHistory(Request $request){

        $history = DB::table('it_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no','a.jobreq_status',
                DB::raw('DATE_FORMAT(jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw('CONCAT(b.it_emp_name_th," ",b.it_emp_surname_th) emp_fullname'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                        WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                        WHEN 3 THEN "ลบผู้ใช้งาน"
                        WHEN 4 THEN "พัฒนาใหม่"
                        WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                        WHEN 6 THEN "สร้างอีเมลใหม่"
                        ELSE "อื่นๆ" END jobreq_obj'
                ),
                DB::raw(
                    'CASE a.jobreq_status
                        WHEN 1 THEN "รอดำเนินการ"
                        WHEN 2 THEN "กำลังดำเนินการ"
                        WHEN 3 THEN "ดำเนินการเรียบร้อย"
                        ELSE "ยกเลิก" END jobreq_status_str'
                )
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->where('b.it_dept_id',$request->dept_id);

        if(!empty($request->search))
            $history = $history->where('a.jobreq_no','LIKE','%'.$request->search.'%');

        if( !empty($request->searchObj) )
            $history = $history->where('a.jobreq_obj',$request->searchObj);

        if( !empty($request->searchEmp) ){
            $history = $history->whereRaw('(
                b.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or b.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or b.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        $history = $history->orderBy('a.jobreq_date','DESC')
            ->paginate($request->paginate);

        return response()->json($history);

    }

    public function getJobrequestPagin(Request $request){

        $cfiles = DB::table('it_jobreq_file')
            ->select('jobreq_id', DB::raw('count(jobreq_file_id) cfile'))
            ->groupBy('jobreq_id');

        $SubQueryInCFiles = $cfiles->toSql();
        $bindings = $cfiles->getBindings();

        foreach($bindings as $binding)
            $SubQueryInCFiles = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInCFiles, 1);
        
            foreach($bindings as $binding)
            $SubQueryInCFiles = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInCFiles, 1);

        $admin = DB::table('user as u')
            ->select('u.user', DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) fullname'))
            ->join('it_employee as ie','u.it_emp_id','ie.it_emp_id')
            ->where('u.identity','admin');

        $SubQueryInUsers = $admin->toSql();
        $bindings = $admin->getBindings();

        foreach($bindings as $binding)
            $SubQueryInUsers = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInUsers, 1);

        $result = DB::table('it_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no','a.jobreq_status',
                'b.it_emp_name_th', 'b.it_emp_surname_th', 'b.it_emp_nickname_th',
                'a.it_emp_position', 'c.it_dept_name',
                DB::raw('DATE_FORMAT(a.jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                        WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                        WHEN 3 THEN "ลบผู้ใช้งาน"
                        WHEN 4 THEN "พัฒนาใหม่"
                        WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                        WHEN 6 THEN "สร้างอีเมลใหม่"
                        ELSE "อื่นๆ" END jobreq_obj_str'
                ),
                DB::raw(
                    'CASE a.jobreq_status
                        WHEN 1 THEN "รอดำเนินการ"
                        WHEN 2 THEN "กำลังดำเนินการ"
                        WHEN 3 THEN "ดำเนินการเรียบร้อย"
                        ELSE "ยกเลิก" END jobreq_status_str'
                ),
                DB::raw(
                    'CASE WHEN f.cfile is null
                        THEN \'ยังไม่ได้อัพโหลดเอกสาร\'
                        ELSE \'มีเอกสารแล้ว\' END sfiles'
                ),
                DB::raw(
                    'IFNULL(ad.fullname,\'ไม่มีผู้ดำเนินงาน\') assign_name'
                )
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->join('it_department AS c','b.it_dept_id','c.it_dept_id')
            ->leftJoin(DB::raw('('.$SubQueryInCFiles.') as f'),'a.jobreq_id','f.jobreq_id')
            ->leftJoin(DB::raw('('.$SubQueryInUsers.') as ad'),'a.jobreq_assign','ad.user');



        if( $request->filter == 'wait' )
            $result = $result->where('a.jobreq_status',1);
        elseif( $request->filter == 'inprogress' )
            $result = $result->where('a.jobreq_status',2);
        elseif( $request->filter == 'success' )
            $result = $result->where('a.jobreq_status',3);
        elseif( $request->filter == 'void' )
            $result = $result->where('a.jobreq_status',0);

        if ($request->searchView == true)
            $result = $result->where('a.jobreq_assign',session()->get('user'));


        if( !empty($request->search) )
            $result = $result->where('a.jobreq_no','LIKE','%'.$request->search.'%');

        if( !empty($request->searchObj) )
            $result = $result->where('a.jobreq_obj',$request->searchObj);

        if( !empty($request->searchPstatus) ){
            if($request->searchPstatus == '1')
                $result = $result->whereNotNull('f.cfile');
            elseif($request->searchPstatus == '2')
                $result = $result->whereNull('f.cfile');
        }

        if( !empty($request->searchEmp) ){
            $result = $result->whereRaw('(
                b.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or b.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or b.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        $result = $result->orderBy('a.jobreq_date','DESC')
            ->orderBy('a.jobreq_id','DESC')
            ->paginate($request->paginate);


        return response()->json($result);

    }

    public function getJobrequestNonUpFile(Request $request){

        $cfiles = DB::table('it_jobreq_file')
            ->select('jobreq_id', DB::raw('count(jobreq_file_id) cfile'))
            ->groupBy('jobreq_id');

        $SubQueryInCFiles = $cfiles->toSql();
        $bindings = $cfiles->getBindings();

        foreach($bindings as $binding)
            $SubQueryInCFiles = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInCFiles, 1);

        $result = DB::table('it_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no','a.jobreq_status',
                'b.it_emp_name_th', 'b.it_emp_surname_th', 'b.it_emp_nickname_th',
                'a.it_emp_position', 'c.it_dept_name',
                DB::raw('DATE_FORMAT(a.jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                        WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                        WHEN 3 THEN "ลบผู้ใช้งาน"
                        WHEN 4 THEN "พัฒนาใหม่"
                        WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                        WHEN 6 THEN "สร้างอีเมลใหม่"
                        ELSE "อื่นๆ" END jobreq_obj_str'
                ),
                DB::raw(
                    'CASE a.jobreq_status
                        WHEN 1 THEN "รอดำเนินการ"
                        WHEN 2 THEN "กำลังดำเนินการ"
                        WHEN 3 THEN "ดำเนินการเรียบร้อย"
                        ELSE "ยกเลิก" END jobreq_status_str'
                ),
                DB::raw(
                    'CASE WHEN f.cfile is null
                        THEN \'ยังไม่ได้อัพโหลดเอกสาร\'
                        ELSE \'มีเอกสารแล้ว\' END sfiles'
                )
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->join('it_department AS c','b.it_dept_id','c.it_dept_id')
            ->leftJoin(DB::raw('('.$SubQueryInCFiles.') as f'),'a.jobreq_id','f.jobreq_id');
//            ->where('a.jobreq_assign',session()->get('user'));

        if( !empty($request->search) )
            $result = $result->where('a.jobreq_no','LIKE','%'.$request->search.'%');

        if( !empty($request->searchObj) )
            $result = $result->where('a.jobreq_obj',$request->searchObj);

        if( !empty($request->searchDateFrom) || !empty($request->searchDateTo) ){
            $from = !empty($request->searchDateFrom) ? $request->searchDateFrom : $request->searchDateTo;
            $to = !empty($request->searchDateTo) ? $request->searchDateTo : $request->searchDateFrom;
            $result = $result->whereBetween('a.jobreq_date',[$from,$to]);
        }
        
        if( $request->searchStatus != 99 )
            $result = $result->where('a.jobreq_status',$request->searchStatus);

        if( !empty($request->searchEmp) ){
            $result = $result->whereRaw('(
                b.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or b.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or b.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        $result = $result->whereNull('f.cfile')
            ->where('a.jobreq_status','!=',0)
            ->orderBy('a.jobreq_date','DESC')
            ->orderBy('a.jobreq_id','DESC')
            ->paginate($request->paginate);

        return response()->json($result);

    }

    public function getJobrequestDetail($id){

        $hader = DB::table('it_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no',
                'b.it_emp_name_th', 'b.it_emp_surname_th', 'b.it_emp_name_eng', 'b.it_emp_surname_eng',
                'a.it_emp_position', 'c.it_dept_name', 'b.it_emp_tel',
                'a.jobreq_obj', 'a.jobreq_status', 'a.jobreq_demand', 'a.jobreq_reason',
                'a.jobreq_other', 'a.jobreq_remark',
                DB::raw('DATE_FORMAT(a.jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                        WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                        WHEN 3 THEN "ลบผู้ใช้งาน"
                        WHEN 4 THEN "พัฒนาใหม่"
                        WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                        WHEN 6 THEN "สร้างอีเมลใหม่"
                        ELSE "อื่นๆ" END jobreq_obj_str'
                ),
                DB::raw(
                    'CASE a.jobreq_status
                        WHEN 1 THEN "รอดำเนินการ"
                        WHEN 2 THEN "กำลังดำเนินการ"
                        WHEN 3 THEN "ดำเนินการเรียบร้อย"
                        ELSE "ยกเลิก" END jobreq_status_str'
                )
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->join('it_department AS c','b.it_dept_id','c.it_dept_id')
            ->where('a.jobreq_id',$id)
            ->get();

        $detial = it_jobreq_detail::select('jobreq_id','jobreq_line','jobreq_detail','jobreq_qty','jobreq_unit')
            ->where('jobreq_id',$id)
            ->get();

        $admin = DB::table('it_jobreq AS ij')
            ->select('ij.jobreq_assign',DB::raw('CONCAT(ie.it_emp_name_th," ",ie.it_emp_surname_th) AS emp_name'))
            ->join('user AS u','ij.jobreq_assign','u.user')
            ->join('it_employee AS ie','u.it_emp_id','ie.it_emp_id')
            ->where('ij.jobreq_id',$id)
            ->first();

        $file = it_jobreq_detail_file::where('jobreq_id',$id)
            ->select('jobreqd_filename','jobreqd_filepath')
            ->get();

        $log = DB::table('it_jobreq_log AS ijl')
            ->select(
                DB::raw('CONCAT(ie.it_emp_name_th," ",ie.it_emp_surname_th) AS emp_name'),
                'ijl.jobreq_action','ijl.created_at'
            )
            ->join('user AS u','ijl.jobreq_user','u.user')
            ->join('it_employee AS ie','u.it_emp_id','ie.it_emp_id')
            ->where('ijl.jobreq_id',$id)
            ->get();

        $result = [
            'header' => $hader[0],
            'detail' => $detial,
            'files' => $file,
            'log' => [
                'admin' => $admin,
                'list' => $log
            ]
        ];

        return response()->json($result);

    }

    public function updateJobreqStauts(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $result = it_jobreq::where('jobreq_id',$request->id)
                    ->update([
                        'jobreq_status' => $request->status,
                        // 'log_session' => $request->user,
                        'log_session' => session()->get('user')
                    ]);

        if( session()->get('identity') == 'admin' ){
            $assign = it_jobreq::where('jobreq_id',$request->id)
                        ->whereNull('jobreq_assign')
                        ->update([
                            // 'jobreq_assign' => $request->user
                            'jobreq_assign' => session()->get('user')
                        ]);
        }

        if($result){

            $no = it_jobreq::select('jobreq_no')
                        ->where('jobreq_id',$request->id)
                        ->first();

            if( $request->status == 2 ) $action = 'กำลังดำเนินการ';
            elseif( $request->status == 3 ) $action = 'ดำเนินการเรียบร้อย';
            elseif( $request->status == 0 ) $action = 'ยกเลิก';

            $this->setLog([
                'id' => $request->id,
                'no' => $no->jobreq_no,
                'action' => $action
            ]);

        }

        return response()->json(array('status' => $result));

    }

    public function updateJobreqRemark(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $update = it_jobreq::where('jobreq_id',$request->id)
                    ->update([
                        'jobreq_remark' => $request->remark
                    ]);

        return response()->json(array('status' => $update));

    }

    public function getJobreqFile($id){

        $file = it_jobreq_file::where('jobreq_id',$id)
                    ->select('jobreq_file_id','jobreq_file_name','jobreq_file_path')
                    ->get();

        return response()->json($file);

    }

    private function checkIsVoid($id){
        $status = it_jobreq::where('jobreq_id',$id)
            ->select('jobreq_status')
            ->first();
        return $status['jobreq_status'];
    }

    public function uploadJobreqFile(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $file_newname = 'jobreq_'.date('YmdHis').'.pdf';

        if( $request->file->move(public_path('upload/jobreq'), $file_newname) ){

            $upload = new it_jobreq_file([
                    'jobreq_id' => $request->jobreq_id,
                    'jobreq_file_name' => $file_newname,
                    'jobreq_file_path' => '/upload/jobreq/'.$file_newname
                ]);

            $result = $upload->save();
            
            if($result && $this->checkIsVoid($request->jobreq_id) != 0){

                $status = it_jobreq::where('jobreq_id',$request->jobreq_id)
                        ->update([
                            'jobreq_status' => 3,
                            'log_session' => session()->get('user')
                        ]);

                $this->setLog([
                    'id' => $request->jobreq_id,
                    'no' => '',
                    'action' => 'ดำเนินการเรียบร้อย'
                ]);
    }

            return response()->json(array('status' => $result));

        }
        else{
            $result = false;
            return response()->json(array('status' => $result));
        }

    }

    public function removeJobreqFile(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        if( File::exists(public_path($request->file_path)) ){
            if( File::delete(public_path($request->file_path)) ){
                $remove = it_jobreq_file::where('jobreq_file_id',$request->file_id)->delete();
                $result = true;
            }
            else
                $result = false;
        }
        else
            $result = false;

        return response()->json(array('status' => $result));

    }

    private function setLog($dataset){

        $log = new it_jobreq_log([
                'jobreq_id' => $dataset['id'],
                'jobreq_no' => $dataset['no'],
                'jobreq_action' => $dataset['action'],
                'jobreq_user' => session()->get('user')
            ]);

        $log->save();

    }

}
