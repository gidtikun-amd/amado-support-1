<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\notifyLineController;
use App\Http\Controllers\Security\LoginController;
use App\it_memo;
use App\it_memo_detail;
use App\it_memo_runningno;
use App\it_memo_log;
use App\it_memo_file;
use DB;
use Session;
use File;

class itMemoController extends Controller
{
    private $notify_line;
    private $exceptionController;

    public function __construct(){
        $this->notify_line = new notifyLineController();
        $this->exceptionController = new exceptionController();
    }

    private function saveLogError($dataset){
        
        $errormsg = explode('(SQL:',$dataset['message']);

        $this->exceptionController->saveLogQueryException(array(
            'module' => $dataset['module'],
            'action' => $dataset['action'],
            'message' => $dataset['message']
        ));

        return $errormsg[0];

    }

    public function saveMemo(Request $request){

        $date = [ 'year' => date('y'), 'month' => date('m') ];
        $run_no = $this->getRunningNumber($date);
        $paper_no = 'IT-MEMO-'.$date['year'].$date['month'].str_pad($run_no,7,'0',STR_PAD_LEFT);

        try{

            $header = new it_memo([
                'memo_no' => $paper_no,
                'it_emp_id' => $request->empId,
                'memo_date' => $request->date,
                'memo_position' => $request->position,
                'memo_object' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->object),
                'memo_tel' => $request->tel,
                'memo_remark' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->remark),
                'memo_status' => 1
            ]);

            $header->save();

        }
        catch(QueryException $e){

            $errormsg = $this->saveLogError(array(
                'module' => 'memo_by_user',
                'action' => 'insert_db',
                'message' => $e->getMessage()
            ));

            return response()->json(array(
                'Status' => false,
                'exception' => 'sqlError',
                'message' => $errormsg
            )); 

        }

        $inst_id = $header->id;
        $values = [];
        $line = 1;

        foreach($request->detail as $val){
            array_push($values,[
                'memo_id' => $inst_id,
                'memo_line' => $line++,
                'memo_detail' => preg_replace('/[\x{200B}-\x{200D}]/u','',$val['text']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        try{

            $detail = it_memo_detail::insert($values);

            $notify_msg = 'คุณ '.session()->get('name').' แผนก '.session()->get('department').' ขอเปิด Memo เอกสารเลขที่ '.$paper_no;
            $this->notify_line->call_notify_group_ticket_it_support('memo',array('message' => $notify_msg));

            $this->saveLog([
                'id' => $inst_id,
                'no' => $paper_no,
                'action' => 'สร้าง',
                'user' => $request->empId
            ]);

            return response()->json([
                'Status' => true, 
                'exception' => '',
                'message' => '',
                'paper_no' => $paper_no
            ]);

        }
        catch(QueryException $e){

            $update = it_memo::where('memo_id',$inst_id)
                ->update([
                    'memo_status' => 0,
                    'memo_apprsts' => 1
                ]);

            $errormsg = $this->saveLogError(array(
                'module' => 'memo_by_user',
                'action' => 'insert_db',
                'message' => $e->getMessage()
            ));

            return response()->json(array(
                'Status' => false,
                'exception' => 'sqlError',
                'message' => $errormsg
            )); 

            return response()->json(false, 500);

        }

    }

    private function getRunningNumber($date){

        $running_no = it_memo_runningno::select('memo_rno_seq')
            ->where('memo_rno_year',$date['year'])
            ->where('memo_rno_month',$date['month']);

        $count_rows = $running_no;
        $checkEmpty = $count_rows = $count_rows->count();

        if($checkEmpty > 0){

            $running_no  = $running_no->get();
            $number = $running_no[0]['memo_rno_seq']+1;

            $running_upd = it_memo_runningno::where('memo_rno_year',$date['year'])
                ->where('memo_rno_month',$date['month'])
                ->update([
                    'memo_rno_seq' => $number
                ]);


            return $number;

        }
        else{

            $running_inst = new it_memo_runningno([
                'memo_rno_year' => $date['year'],
                'memo_rno_month' => $date['month'],
                'memo_rno_seq' => 1
            ]);

            $running_inst->save();
            return 1;

        }

    }

    public function getMemoUserHistory(Request $request){

        $history = DB::table('it_memo as im')
                    ->select(
                        'im.memo_id as id','im.memo_no as no','im.memo_date as date',
                        'im.memo_object as object','im.memo_status as status','im.it_emp_id as empId',
                        DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) empName'),
                        DB::raw('case im.memo_status
                            when 1 then \'รอดำเนินการ\'
                            when 2 then \'กำลังดำเนินการ\'
                            when 3 then \'ดำเนินการเรียบร้อยแล้ว\'
                            else \'ยกเลิก\' end stsstr')
                    )
                    ->join('it_employee as ie','im.it_emp_id','ie.it_emp_id')
                    ->where('ie.it_dept_id',session()->get('departmentId'));

        if(!empty($request->text))
            $history = $history->where('im.memo_no','LIKE','%'.$request->text.'%');

        if( !empty($request->searchEmp) ){
            $history = $history->whereRaw('(
                ie.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        $history = $history->orderBy('im.created_at','DESC')
                    ->paginate($request->paginate);

        return response()->json($history);

    }

    public function userGetMemoDetailToPage($id){
        $result = $this->userGetMemoDetail($id);
        return response()->json($result);
    }

    public function userGetMemoDetailToObject($id){
        $result = (object) $this->userGetMemoDetail($id);
        return $result;
    }

    private function userGetMemoDetail($id){

        $sub1 = DB::table('it_employee as innie')
                    ->select(
                        'innu.user',
                        DB::raw('CONCAT(innie.it_emp_name_th,\' \',innie.it_emp_surname_th) as assign_name')
                    )
                    ->join('user as innu','innie.it_emp_id','innu.it_emp_id');

        $header = DB::table('it_memo as im')
                    ->select(
                        'im.memo_id as id', 'im.memo_no as no', 'im.memo_date as date', 'im.memo_position as position',
                        'im.memo_object as object', 'im.memo_tel as tel', 'im.memo_remark as remark',
                        'im.memo_status as status', 'im.memo_apprsts as apprsts', 'id.it_dept_name as deptname',
                        'im.memo_assign as assign', 'im.memo_appruser as appruser',
                        DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as efullnameTh'),
                        DB::raw('concat(ie.it_emp_name_eng,\' \',ie.it_emp_surname_eng) as efullnameEn'),
                        DB::raw('IFNULL(sub1.assign_name,\'ไม่มีผู้ดำเนินการ\') as assign_name')
                    )
                    ->join('it_employee as ie','im.it_emp_id','ie.it_emp_id')
                    ->join('it_department as id','ie.it_dept_id','id.it_dept_id')
                    ->leftJoin(DB::raw('('.$sub1->toSql().') as sub1'),'im.memo_assign','sub1.user')
                    ->where('im.memo_id',$id)
                    ->first();

        $detail = it_memo_detail::where('memo_id',$id)
                    ->select('memo_id as id', 'memo_line as line', 'memo_detail as detail')
                    ->orderBy('memo_line','ASC')
                    ->get();

        $result = array(
            'header' => $header,
            'detail' => $detail
        );

        return $result;

    }

    public function voidMemo(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $result = it_memo::where('memo_id',$request->id)
                ->update([
                    'memo_apprsts' => 2,
                    'memo_status' => $request->status,
                    'memo_remark' => $request->remark
                ]);

        if($result)
            $this->saveLog([
                'id' => $request->id,
                'no' => $request->no,
                'action' => 'ยกเลิก',
                'user' => session()->get('user')
            ]);

        return response()->json(array('status' => $result, 'error' => 'page.action'));

    }

    public function getMemoAdminList(Request $request){

        $cfiles = DB::table('it_memo_file')
            ->select('memo_id',DB::raw('count(mm_file_id) cfile'))
            ->groupBy('memo_id');

        $SubQueryInCFiles = $cfiles->toSql();
        $bindings = $cfiles->getBindings();

        foreach($bindings as $binding)
            $SubQueryInCFiles = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInCFiles, 1);

        $field = [
            'im.memo_id as id', 'im.memo_no as no', 'im.memo_date as date', 'im.memo_object as object',
            'im.memo_status as status', 'im.memo_apprsts as apprsts', 'id.it_dept_name as deptname',
            'im.memo_assign as assign',
            DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as efullnameTh'),
            DB::raw('CASE WHEN f.cfile is null THEN \'ยังไม่ได้อัพโหลดเอกสาร\' ELSE \'มีเอกสารแล้ว\' END as sfiles')
        ];

        $list = DB::table('it_memo as im')
                ->select(
                    'im.memo_id as id', 'im.memo_no as no', 'im.memo_date as date', 'im.memo_object as object',
                    'im.memo_status as status', 'im.memo_apprsts as apprsts',
                    'im.memo_assign as assign',
                    DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as efullnameTh')
                )
                ->join('it_employee as ie','im.it_emp_id','ie.it_emp_id')
                ->join('it_department as id','ie.it_dept_id','id.it_dept_id')
                ->leftJoin(DB::raw('('.$SubQueryInCFiles.') as f'),'im.memo_id','f.memo_id');

        if($request->target != 'waitappr' && $request->target != 'waitproc' && $request->target != 'void'){
            $inna = DB::table('it_employee as innie')
                    ->select(
                        'innu.user',
                        DB::raw('CONCAT(innie.it_emp_name_th,\' \',innie.it_emp_surname_th) as assign_name')
                    )
                    ->join('user as innu','innie.it_emp_id','innu.it_emp_id');

            array_push($field,'sub1.assign_name');

            $list = $list->join(DB::raw('('.$inna->toSql().') as sub1'),'im.memo_assign','sub1.user');
        }

        if($request->target == 'all'){
            array_push($field,DB::raw(
                        'CASE WHEN im.memo_apprsts = 0 THEN \'รออนุมัติ\'
                            WHEN im.memo_apprsts = 1 AND im.memo_status = 1 THEN \'รอรับดำเนินการ\'
                            WHEN im.memo_apprsts = 1 AND im.memo_status = 2 THEN \'กำลังดำเนินการ\'
                            WHEN im.memo_apprsts = 1 AND im.memo_status = 3 THEN \'ดำเนินการเรียบร้อย\'
                            ELSE \'ไม่อนุมัติ\' END apprstsstr
                        '
                    ));
        }

        $list = $list->select($field);

        switch($request->target){
            case 'waitappr':
                $list = $list->where([
                    ['im.memo_apprsts',0],
                    ['im.memo_status','!=',0]
                ]);
            break;
            case 'waitproc':
                $list = $list->where([
                    ['im.memo_apprsts',1],
                    ['im.memo_status',1]
                ]);
            break;
            case 'progress':
                $list = $list->where([
                    ['im.memo_apprsts',1],
                    ['im.memo_status',2]
                ]);
            break;
            case 'success':
                $list = $list->where([
                    ['im.memo_apprsts',1],
                    ['im.memo_status',3]
                ]);
            break;
            case 'void':
                // $list = $list->where([
                //     ['im.memo_apprsts',2],
                //     ['im.memo_status',0]
                // ]);
                $list = $list->where('im.memo_status',0);
            break;
        }

        if(!empty($request->search))
            $list = $list->where('im.memo_no','LIKE','%'.$request->search.'%');

        if(!empty($request->searchObject))
            $list = $list->where('im.memo_object','LIKE','%'.$request->searchObject.'%');

        //if(session()->get('role') == 'admin.it')
            //$list = $list->where('im.memo_assign',session()->get('user'));

        if( !empty($request->searchPstatus) ){
            if($request->searchPstatus == 1)
                $list = $list->whereNotNull('f.cfile');
            elseif($request->searchPstatus == 2)
                $list = $list->whereNull('f.cfile');
        }

        if( $request->searchEmp != '' ){
            $list = $list->whereRaw('(
                ie.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        $list = $list->orderBy('im.created_at','DESC')
                ->paginate($request->paginate);

        return response()->json($list);

    }

    public function getMemoNonUpFile(Request $request){

        $cfiles = DB::table('it_memo_file')
            ->select('memo_id',DB::raw('count(mm_file_id) cfile'))
            ->groupBy('memo_id');

        $SubQueryInCFiles = $cfiles->toSql();
        $bindings = $cfiles->getBindings();

        foreach($bindings as $binding)
            $SubQueryInCFiles = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInCFiles, 1);

        $field = [
            'im.memo_id as id', 'im.memo_no as no', 'im.memo_date as date', 'im.memo_object as object',
            'im.memo_status as status', 'im.memo_apprsts as apprsts', 'id.it_dept_name as deptname',
            'im.memo_assign as assign',
            DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as efullnameTh'),
            DB::raw('CASE WHEN f.cfile is null THEN \'ยังไม่ได้อัพโหลดเอกสาร\' ELSE \'มีเอกสารแล้ว\' END as sfiles')
        ];

        $list = DB::table('it_memo as im')
            ->select(
                'im.memo_id as id', 'im.memo_no as no', 'im.memo_date as date', 'im.memo_object as object',
                'im.memo_status as status', 'im.memo_apprsts as apprsts',
                'im.memo_assign as assign',
                DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as efullnameTh')
            )
            ->join('it_employee as ie','im.it_emp_id','ie.it_emp_id')
            ->join('it_department as id','ie.it_dept_id','id.it_dept_id')
            ->leftJoin(DB::raw('('.$SubQueryInCFiles.') as f'),'im.memo_id','f.memo_id');

        $inna = DB::table('it_employee as innie')
            ->select(
                'innu.user',
                DB::raw('CONCAT(innie.it_emp_name_th,\' \',innie.it_emp_surname_th) as assign_name')
            )
            ->join('user as innu','innie.it_emp_id','innu.it_emp_id');

        array_push($field,DB::raw('ifnull(sub1.assign_name,\'ไม่มีผู้ดำเนินการ\') assign_name'));

        $list = $list->leftJoin(DB::raw('('.$inna->toSql().') as sub1'),'im.memo_assign','sub1.user');

        array_push($field,DB::raw(
            'CASE WHEN im.memo_apprsts = 0 THEN \'รออนุมัติ\'
                        WHEN im.memo_apprsts = 1 AND im.memo_status = 1 THEN \'รอรับดำเนินการ\'
                        WHEN im.memo_apprsts = 1 AND im.memo_status = 2 THEN \'กำลังดำเนินการ\'
                        WHEN im.memo_apprsts = 1 AND im.memo_status = 3 THEN \'ดำเนินการเรียบร้อย\'
                        ELSE \'ไม่อนุมัติ\' END apprstsstr
                    '
        ));

        $list = $list->select($field);

        if(!empty($request->search))
            $list = $list->where('im.memo_no','LIKE','%'.$request->search.'%');

        if( !empty($request->searchEmp) ){
            $list = $list->whereRaw('(
                ie.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        if( !empty($request->searchDateFrom) || !empty($request->searchDateTo) ){
            $from = !empty($request->searchDateFrom) ? $request->searchDateFrom : $request->searchDateTo;
            $to = !empty($request->searchDateTo) ? $request->searchDateTo : $request->searchDateFrom;
            $list = $list->whereBetween('im.memo_date',[$from,$to]);
        }

        if( $request->searchStatus != 99 )
            $list = $list->where('im.memo_status',$request->searchStatus);
        else 
            $list = $list->whereIn('im.memo_status',[2,3]);

        $list = $list->where('im.memo_apprsts',1)
            ->orderBy('im.created_at','DESC')
            ->paginate($request->paginate);

        return response()->json($list);

    }

    public function adminGetMemoDetail($id){

        $sub1 = DB::table('it_employee as innie')
        ->select(
            'innu.user',
            DB::raw('CONCAT(innie.it_emp_name_th,\' \',innie.it_emp_surname_th) as assign_name')
        )
        ->join('user as innu','innie.it_emp_id','innu.it_emp_id');

        $header = DB::table('it_memo as im')
                    ->select(
                        'im.memo_id as id', 'im.memo_no as no', 'im.memo_date as date', 'im.memo_position as position',
                        'im.memo_object as object', 'im.memo_tel as tel', 'im.memo_remark as remark',
                        'im.memo_status as status', 'im.memo_apprsts as apprsts', 'id.it_dept_name as deptname',
                        'im.memo_assign as assign', 'im.memo_appruser as appruser',
                        DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as efullnameTh'),
                        DB::raw('concat(ie.it_emp_name_eng,\' \',ie.it_emp_surname_eng) as efullnameEn'),
                        DB::raw('IFNULL(sub1.assign_name,\'ยังไม่มีผู้รับผิดชอบ\') as assign_name')
                    )
                    ->join('it_employee as ie','im.it_emp_id','ie.it_emp_id')
                    ->join('it_department as id','ie.it_dept_id','id.it_dept_id')
                    ->leftJoin(DB::raw('('.$sub1->toSql().') as sub1'),'im.memo_assign','sub1.user')
                    ->where('im.memo_id',$id)
                    ->first();

        $detail = it_memo_detail::where('memo_id',$id)
                    ->select('memo_id as id', 'memo_line as line', 'memo_detail as detail')
                    ->orderBy('memo_line','ASC')
                    ->get();

        $log = DB::table('it_memo_log as iml')
                ->select(
                    'iml.memo_action as action', 'iml.memo_user as user', 'iml.created_at',
                    DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as fullname')
                )
                ->join('user as u','iml.memo_user','u.user')
                ->join('it_employee as ie','u.it_emp_id','ie.it_emp_id')
                ->where('iml.memo_id',$id)
                ->orderBy('created_at')
                ->get();

        $result = array(
            'header' => $header,
            'detail' => $detail,
            'log' => $log
        );

        return $result;

    }

    public function approveMemo(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $update = it_memo::where('memo_id',$request->id)
                    ->update([
                        'memo_apprsts' => $request->status,
                        // 'memo_appruser' => $request->user,
                        'memo_appruser' => session()->get('user'),
                        'memo_remark' => $request->remark,
                        'memo_assign' => $request->assign,
                        'memo_status' => ($request->status == 1 ? 2 : 0)
                    ]);

        $this->saveLog([
            'id' => $request->id,
            'no' => $request->no,
            'action' => ($request->status == 1 ? 'อนุมัติ' : 'ไม่อนุมัติ'),
            'user' => session()->get('user')
            // 'user' => $request->user
        ]);

        if($request->status == 1){
            $this->saveLog([
                'id' => $request->id,
                'no' => $request->no,
                'action' => 'ดำเนินการ',
                'user' => $request->assign
            ]);
        }

        return response()->json(array('status' => $update));

    }

    public function changStatusMemo(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $result = it_memo::where('memo_id',$request->id)
                ->update(['memo_status' => $request->status]);

        if($request->status == 2)
            $action = 'รอดำเนินการ';
        elseif($request->status == 2)
            $action = 'กำลังดำเนินการ';
        elseif($request->status == 3)
            $action = 'ดำเนินการเรียบร้อยแล้ว';

        if($result)
            $this->saveLog([
                'id' => $request->id,
                'no' => $request->no,
                'action' => $action,
                'user' => session()->get('user')
                // 'user' => $request->user
            ]);

        return response()->json(array('status' => $result));

    }

    public function getMemoFile($id){

        $file = it_memo_file::where('memo_id',$id)
                ->select('mm_file_id','mm_file_name','mm_file_path')
                ->get();

        return response()->json($file);

    }

    private function checkIsVoid($id){
        $status = it_memo::where('memo_id',$id)
            ->select('memo_status')
            ->first();
        return $status['memo_status'];
    }

    public function uploadMemoFile(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $file_newname = 'memo_'.date('YmdHis').'.pdf';

        if( $request->file->move(public_path('upload/it_memo'), $file_newname) ){

            $upload = new it_memo_file([
                    'memo_id' => $request->id,
                    'mm_file_name' => $file_newname,
                    'mm_file_path' => '/upload/it_memo/'.$file_newname
                ]);

            $result = $upload->save();

            if($result && $this->checkIsVoid($request->id) != 0){
                $status = it_memo::where('memo_id',$request->id)
                    ->update(['memo_status' => 3]);

                $this->saveLog([
                    'id' => $request->id,
                    'no' => '',
                    'action' => 'ดำเนินการเรียบร้อยแล้ว',
                    'user' => session()->get('user')
                ]);
            }

            return response()->json(array('status' => $result));

        }
        else{
            $result = false;
            return response()->json(array('status' => $result));
        }

    }

    public function removeMemoFile(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        if( File::exists(public_path($request->file_path)) ){
            if( File::delete(public_path($request->file_path)) ){
                $remove = it_memo_file::where('mm_file_id',$request->file_id)->delete();
                $result = true;
            }
            else
                $result = false;
        }
        else
            $result = false;

        return response()->json(array('status' => $result));

    }

    public function saveLog($dataset){

        $log = new it_memo_log([
            'memo_id' => $dataset['id'],
            'memo_no' => $dataset['no'],
            'memo_action' => $dataset['action'],
            'memo_user' => $dataset['user']
        ]);

        $log->save();

    }
}
