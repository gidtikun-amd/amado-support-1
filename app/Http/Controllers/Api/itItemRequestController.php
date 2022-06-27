<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\LoginController;
use App\Http\Controllers\Api\notifyLineController;
use App\Http\Controllers\Api\exceptionController;
use App\it_itmreq;
use App\it_itmreq_detail;
use App\it_itmreq_runningno;
use App\it_itmreq_log;
use App\it_itmreq_file;
use DB;
use Session;
use File;

class itItemRequestController extends Controller
{
    private $exceptionController;

    public function __construct(){
        // $this->exceptionController = new exceptionController();
    }
    
    public function getAdminListItmreq(Request $request){

        $cfiles = DB::table('it_itmreq_file')
            ->select('itmreq_id',DB::raw('count(itmreq_file_id) cfile'))
            ->groupBy('itmreq_id');

        $SubQueryInCFiles = $cfiles->toSql();
        $bindings = $cfiles->getBindings();

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

        $list = DB::table('it_itmreq AS ii')
            ->select(
                'ii.itmreq_id','ii.itmreq_no','ii.itmreq_date','ii.it_emp_id','ii.itmreq_status',
                'ii.itmreq_obj','ii.it_emp_position','id.it_dept_name',
                DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th,\' (\',ie.it_emp_nickname_th,\')\') emp_fullname'),
                DB::raw(
                    'CASE ii.itmreq_status
                        WHEN 1 THEN "รอดำเนินการ"
                        WHEN 2 THEN "กำลังดำเนินการ"
                        WHEN 3 THEN "ดำเนินการเรียบร้อย"
                        ELSE "ยกเลิก" END itmreq_status_str'
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
            ->join('it_employee AS ie','ii.it_emp_id','ie.it_emp_id')
            ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
            ->leftJoin(DB::raw('('.$SubQueryInCFiles.') as f'),'ii.itmreq_id','f.itmreq_id')
            ->leftJoin(DB::raw('('.$SubQueryInUsers.') as ad'),'ii.itmreq_assign','ad.user');

        if( !empty($request->search) )
            $list = $list->where('ii.itmreq_no','LIKE','%'.$request->search.'%');

        if(!empty($request->searchObject))
            $list = $list->where('ii.itmreq_obj','LIKE','%'.$request->searchObject.'%');

        if( !empty($request->status) ){
            if( $request->status != 'all' ) {
                if( $request->status == 'wait' ) {
                    $sts = 1;
                } elseif( $request->status == 'inprogress' ) {
                    $sts = 2;
                    $list = $list->where('ii.itmreq_approve_sts',1);
                    // $list = $list->where('ii.itmreq_assign',session()->get('user'));
                } elseif ( $request->status == 'success' ) {
                    $sts = 3;
                    $list = $list->where('ii.itmreq_approve_sts',1);
                    // $list = $list->where('ii.itmreq_assign',session()->get('user'));
                } elseif ( $request->status == 'void' ) {
                    $sts = 0;
                    $list = $list->whereIn('ii.itmreq_approve_sts',[1,2]);
                    // $list = $list->where('ii.itmreq_assign',session()->get('user'));
                }
                $list = $list->where('ii.itmreq_status',$sts);
            } 
            // else {
            //     $list = $list->where('ii.itmreq_assign',session()->get('user'));
            // }
        }

        if( !empty($request->searchPstatus) ){
            if($request->searchPstatus == '1')
                $list = $list->whereNotNull('f.cfile');
            elseif($request->searchPstatus == '2')
                $list = $list->whereNull('f.cfile');
        }

        if( !empty($request->searchEmp) ){
            $list = $list->whereRaw('(
                ie.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        // $list = $list->where('ii.itmreq_approve_sts',1);
        $list = $list->orderBy('ii.created_at','DESC')
                    ->paginate(20);

        return response()->json($list);

    }

    public function getItemRequestNonUpFile(Request $request){

        $cfiles = DB::table('it_itmreq_file')
            ->select('itmreq_id',DB::raw('count(itmreq_file_id) cfile'))
            ->groupBy('itmreq_id');

        $SubQueryInCFiles = $cfiles->toSql();
        $bindings = $cfiles->getBindings();

        foreach($bindings as $binding)
            $SubQueryInCFiles = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInCFiles, 1);

        $list = DB::table('it_itmreq AS ii')
            ->select(
                'ii.itmreq_id','ii.itmreq_no','ii.itmreq_date','ii.it_emp_id','ii.itmreq_status',
                'ii.itmreq_obj','ii.it_emp_position','id.it_dept_name',
                DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) emp_fullname'),
                DB::raw(
                    'CASE ii.itmreq_status
                                WHEN 1 THEN "รอดำเนินการ"
                                WHEN 2 THEN "กำลังดำเนินการ"
                                WHEN 3 THEN "ดำเนินการเรียบร้อย"
                                ELSE "ยกเลิก" END itmreq_status_str'
                ),
                DB::raw(
                    'CASE WHEN f.cfile is null
                                THEN \'ยังไม่ได้อัพโหลดเอกสาร\'
                                ELSE \'มีเอกสารแล้ว\' END sfiles'
                )
            )
            ->join('it_employee AS ie','ii.it_emp_id','ie.it_emp_id')
            ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
            ->leftJoin(DB::raw('('.$SubQueryInCFiles.') as f'),'ii.itmreq_id','f.itmreq_id');
            //->where('ii.itmreq_assign',session()->get('user'));

        if( !empty($request->search) )
            $list = $list->where('ii.itmreq_no','LIKE','%'.$request->search.'%');

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
            $list = $list->whereBetween('ii.itmreq_date',[$from,$to]);
        }

        if( $request->searchStatus != 99 )
            $list = $list->where('ii.itmreq_status',$request->searchStatus);
        else 
            $list = $list->whereIn('ii.itmreq_status',[2,3]);

        $list = $list->where('ii.itmreq_approve_sts',1)
            ->whereNull('f.cfile')
            ->orderBy('ii.created_at','DESC')
            ->paginate(20);

        return response()->json($list);

    }

    public function getAccApproveListItmreq(Request $request){

        $list = DB::table('it_itmreq AS ii')
                    ->select(
                        'ii.itmreq_id','ii.itmreq_no','ii.itmreq_date','ii.it_emp_id','ii.itmreq_status',
                        'ii.itmreq_obj','ii.it_emp_position','id.it_dept_name','ii.itmreq_approve_sts',
                        DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) emp_fullname'),
                        DB::raw(
                            'CASE ii.itmreq_status
                                WHEN 1 THEN "รอดำเนินการ"
                                WHEN 2 THEN "กำลังดำเนินการ"
                                WHEN 3 THEN "ดำเนินการเรียบร้อย"
                                ELSE "ยกเลิก" END itmreq_status_str'
                        ),
                        DB::raw(
                            'CASE ii.itmreq_approve_sts
                                WHEN 1 THEN \'อนุมัติแล้ว\'
                                WHEN 2 THEN \'ไม่อนุมัติ\'
                                ELSE \'ยังไม่ได้อนุมัติ\' END approve_str'
                        )
                    )
                    ->join('it_employee AS ie','ii.it_emp_id','ie.it_emp_id')
                    ->join('it_department AS id','ie.it_dept_id','id.it_dept_id');

        if( !empty($request->search) )
                    $list = $list->where('ii.itmreq_no','LIKE','%'.$request->search.'%');

        if( !empty($request->status) ){
            if( $request->status != 'all' ){

                if( $request->status == 'wait' ) $sts = 0;
                elseif( $request->status == 'success' ) $sts = 1;
                elseif( $request->status == 'void' ) $sts = 2;

                $list = $list->where('ii.itmreq_approve_sts',$sts);

            }
        }

        $list = $list->orderBy('ii.created_at','DESC')
                    ->paginate(20);

        return response()->json($list);

    }

    public function getUserListItmreq(Request $request){

        $list = DB::table('it_itmreq AS ii')
                    ->select(
                        'ii.itmreq_id','ii.itmreq_no','ii.itmreq_date','ii.it_emp_id','ii.itmreq_status',
                        'ii.itmreq_obj',
                        DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) emp_fullname'),
                        DB::raw(
                            'CASE ii.itmreq_status
                                WHEN 1 THEN "รอดำเนินการ"
                                WHEN 2 THEN "กำลังดำเนินการ"
                                WHEN 3 THEN "ดำเนินการเรียบร้อย"
                                ELSE "ยกเลิก" END itmreq_status_str'
                        ),
                        DB::raw(
                            'CASE ii.itmreq_approve_sts
                                WHEN 1 THEN \'อนุมัติ\'
                                WHEN 2 THEN \'ไม่อนุมัติ\'
                                ELSE \'ยังไม่ได้อนุมัติ\' END approve_str'
                        )
                    )
                    ->join('it_employee AS ie','ii.it_emp_id','ie.it_emp_id');

        if( !empty($request->search) )
            $list = $list->where('ii.itmreq_no','LIKE','%'.$request->search.'%');

        if( !empty($request->searchEmp) ){
            $list = $list->whereRaw('(
                ie.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        $list = $list->where('ie.it_dept_id',session()->get('departmentId'))
                    ->orderBy('ii.created_at','DESC')
                    ->paginate(20);

        return response()->json($list);

    }

    public function getItmreqDetail(Request $request){
        return response()->json($this->setItmreqDetail($request));
    }

    public function getDataPDFItmreq($id){
        $dataset = (object) array('id' => $id, 'status' => '');
        return (object) $this->setItmreqDetail($dataset);
    }

    private function setItmreqDetail($dataset){
        $header = DB::table('it_itmreq AS ii')
                    ->select(
                        'ii.itmreq_id','ii.itmreq_no','ii.itmreq_status','ii.itmreq_obj',
                        'ii.it_emp_id','ii.it_emp_position','ii.it_emp_id','ii.itmreq_date',
                        'ii.itmreq_approve_sts','ii.itmreq_remark',
                        DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) it_emp_fullname')
                    )
                    ->join('it_employee AS ie','ii.it_emp_id','ie.it_emp_id');

        if( !empty($dataset->id) )
            $header = $header->where('ii.itmreq_id',$dataset->id);

        if( !empty($dataset->status) ){
            if( $dataset->status != 'all' ){

                if( $dataset->status == 'wait' ) $sts = 1;
                elseif( $dataset->status == 'inprogress' ) $sts = 2;
                elseif( $dataset->status == 'success' ) $sts = 3;
                elseif( $dataset->status == 'void' ) $sts = 0;

                $header = $header->where('ii.itmreq_status',$sts);

            }
        }

        $header = $header->first();

        $detail = DB::table('it_itmreq_detail')
                    ->select(
                        'itmreq_barcode','itmreq_item','itmreq_action',
                        'itmreq_prom_sdate','itmreq_prom_edate','itmreq_app',
                        DB::raw('CASE itmreq_action WHEN 1 THEN \'สร้าง\' ELSE \'ยกเลิก\' END action_str'),
                        DB::raw(
                            'CASE itmreq_app
                                WHEN 1 THEN \'AMADO\'
                                WHEN 2 THEN \'KIOSK\'
                                WHEN 3 THEN \'AX\'
                                ELSE \'Sale Force\'
                            END app_str'
                        )
                    )
                    ->where('itmreq_id',$dataset->id)
                    ->orderBy('itmreq_line','ASC')
                    ->get();

        if( session()->get('identity') == 'admin' ){

            $admin = DB::table('it_itmreq AS ii')
                        ->select(
                            'ii.itmreq_assign',
                            DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name')
                        )
                        ->join('user AS u','ii.itmreq_assign','u.user')
                        ->join('it_employee AS ie','u.it_emp_id','ie.it_emp_id')
                        ->where('ii.itmreq_id',$dataset->id)
                        ->first();

            $log = DB::table('it_itmreq_log AS iil')
                        ->select(
                            'iil.itmreq_action','iil.created_at',
                            DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name')
                        )
                        ->join('user AS u','iil.itmreq_user','u.user')
                        ->join('it_employee AS ie','u.it_emp_id','ie.it_emp_id')
                        ->where('iil.itmreq_id',$dataset->id)
                        ->get();

            return ['header' => $header, 'detail' => $detail, 'admin' => $admin, 'log' => $log];

        }
        else
            return ['header' => $header, 'detail' => $detail];

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

    public function store(Request $request){

        $date = [ 'year' => date('y'), 'month' => date('m') ];
        $run_no = $this->getRunningNumber($date);
        $paper_no = 'IT-ITEM-'.$date['year'].$date['month'].str_pad($run_no,7,'0',STR_PAD_LEFT);

        try{

            $itmreq = new it_itmreq([
                'itmreq_no' => $paper_no,
                'itmreq_date' => $request->header['today'],
                'it_emp_id' => $request->header['emp_id'],
                'it_emp_position' => $request->header['emp_position'],
                'itmreq_obj' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->header['itmreq_obj']),
                'itmreq_approve_sts' => 1,
                'itmreq_approve_emp' => 'System auto approve',
                'itmreq_approve_date' => date('Y-m-d H:i:s')
            ]);

            $itmreq->save();
        
        }
        catch(QueryException $e){

            $errormsg = $this->saveLogError(array(
                'module' => 'itemreq_by_user',
                'action' => 'insert_db',
                'message' => $e->getMessage()
            ));

            return response()->json(array(
                'Status' => false,
                'exception' => 'sqlError',
                'message' => $errormsg
            )); 

        }

        $inst_id = $itmreq->id;
        $values = [];
        $line = 1;

        foreach( $request->detail as $val ){
            array_push($values,[
                'itmreq_id' => $inst_id,
                'itmreq_line' => $line++,
                'itmreq_barcode' => preg_replace('/[\x{200B}-\x{200D}]/u','',$val['barcode']),
                'itmreq_item' => preg_replace('/[\x{200B}-\x{200D}]/u','',$val['item']),
                'itmreq_action' => $val['action'],
                'itmreq_prom_sdate' => $val['prom_sdate'],
                'itmreq_prom_edate' => $val['prom_edate'],
                'itmreq_app' => $val['app'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        try{

            $itmreq_detail = it_itmreq_detail::insert($values);
            
            $this->sendLineNoti(['no' => $paper_no, 'emp_id' => $request->header['emp_id']]);
            $this->setLog([ 'id' => $inst_id, 'no' => $paper_no, 'action' => 'สร้าง' ]);

            return response()->json([
                'Status' => true, 
                'exception' => '',
                'message' => '',
                'paper_id' => $paper_no
            ]);

        }
        catch(QueryException $e){

            $itmreq_update = it_itmreq::where('itmreq_id',$inst_id)
                ->update([
                    'itmreq_status' => 0,
                    'itmreq_approve_sts' => 2
                ]);

            $errormsg = $this->saveLogError(array(
                'module' => 'itemreq_by_user',
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

    private function sendLineNoti($dataset){

        $emp = DB::table('it_employee as ie')
                ->select(
                    DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as fullname'),
                    'id.it_dept_name as department'
                )
                ->join('it_department as id','ie.it_dept_id','id.it_dept_id')
                ->where('ie.it_emp_id',$dataset['emp_id'])
                ->first();

        $noti = new notifyLineController();
        $message = "คุณ ".$emp->fullname." ฝ่าย ".$emp->department." ขอเปิด Item เอกสารเลขที่ ".$dataset['no'];
        $noti->call_notify_group_item_request(['message' => $message]);

    }

    private function getRunningNumber($date){

        $running_no = it_itmreq_runningno::select('itmreq_rno_seq')
            ->where([
                ['itmreq_rno_year',$date['year']],
                ['itmreq_rno_month',$date['month']]
            ]);

        $count_rows = $running_no;
        $checkEmpty = $count_rows->count();

        if( $checkEmpty > 0 ){

            $running_no = $running_no->first();
            $number = $running_no['itmreq_rno_seq']+1;

            $running_upd = it_itmreq_runningno::where([
                ['itmreq_rno_year',$date['year']],
                ['itmreq_rno_month',$date['month']]
            ])
            ->update([ 'itmreq_rno_seq' => $number ]);

            return $number;

        }
        else{

            $running_inst = new it_itmreq_runningno([
                'itmreq_rno_year' => $date['year'],
                'itmreq_rno_month' => $date['month'],
                'itmreq_rno_seq' => 1
            ]);

            $running_inst->save();
            return 1;

        }

    }

    public function updateStsItmReq(Request $request){        

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $status_normal = array(2,3,0);
        $status_approve = array(4,5);

        if( in_array($request->status,$status_normal) ){
            $upd_sts = it_itmreq::where('itmreq_id',$request->id)
                    ->update([ 'itmreq_status' => $request->status ]);

            if( session()->get('identity') == 'admin' ){
                $assign = it_itmreq::where('itmreq_id',$request->id)
                            ->whereNull('itmreq_assign')
                            ->update([
                                // 'itmreq_assign' => $request->user,
                                'itmreq_assign' => session()->get('user'),
                                'itmreq_remark' => $request->remark
                            ]);
            }
        }
        elseif( in_array($request->status,$status_approve) ){

            $int_sts = ( $request->status == 4 ? 1 : 2 );

            $upd_sts = it_itmreq::where('itmreq_id',$request->id)
                    ->update([
                        'itmreq_approve_sts' => $int_sts,
                        // 'itmreq_approve_emp' => $request->user,
                        'itmreq_approve_emp' => session()->get('user'),
                        'itmreq_approve_date' => date('Y-m-d H:i:s')
                    ]);

            if( $int_sts == 2 ){
                $upd_sts = it_itmreq::where('itmreq_id',$request->id)
                    ->update([ 'itmreq_status' => 0 ]);
            }

        }

        if( $upd_sts ){

            $no = it_itmreq::select('itmreq_no')
                    ->where('itmreq_id',$request->id)
                    ->first();

            if( $request->status == 2 ) $action = 'กำลังดำเนินการ';
            elseif( $request->status == 3 ) $action = 'ดำเนินการเรียบร้อย';
            elseif( $request->status == 0 ) $action = 'ยกเลิก';
            elseif( $request->status == 4 ) $action = 'อนุมัติ';
            elseif( $request->status == 5 ) $action = 'ไม่อนุมัติ';

            $this->setLog([
                'id' => $request->id,
                'no' => $no->itmreq_no,
                'action' => $action
            ]);

        }

        return response()->json(array('status' => ($upd_sts > 0 ? true : false)));

    }

    private function setLog($dataset){
        $log = new it_itmreq_log([
            'itmreq_id' => $dataset['id'],
            'itmreq_no' => $dataset['no'],
            'itmreq_action' => $dataset['action'],
            'itmreq_user' => session()->get('user')
        ]);

        $log->save();
    }

    public function getItemreqFile($id){

        $file = it_itmreq_file::where('itmreq_id',$id)
                    ->select('itmreq_file_id','itmreq_file_name','itmreq_file_path')
                    ->get();

        return response()->json($file);

    }

    private function checkIsVoid($id){
        $status = it_itmreq::where('itmreq_id',$id)
            ->select('itmreq_status')
            ->first();
        return $status['itmreq_status'];
    }

    public function uploadItemreqFile(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $file_newname = 'itmreq_'.$request->itmreq_id.'_'.date('YmdHis').'.pdf';

        if( $request->file->move(public_path('upload/itemreq'), $file_newname) ){

            $upload = new it_itmreq_file([
                'itmreq_id' => $request->itmreq_id,
                'itmreq_file_name' => $file_newname,
                'itmreq_file_path' => '/upload/itemreq/'.$file_newname
            ]);

            $result = $upload->save();

            if($result && $this->checkIsVoid($request->itmreq_id) != 0){

                $status = it_itmreq::where('itmreq_id',$request->itmreq_id)
                    ->update([
                        'itmreq_status' => 3
                    ]);

                $this->setLog([
                    'id' => $request->itmreq_id,
                    'no' => '',
                    'action' => 'ดำเนินการเรียบร้อย'
                ]);
            }

            return response()->json(array('status' => $result));

        }
        else
            return response()->json(false,500);

    }

    public function removeItemreqFile(Request $request){

        if( File::exists(public_path($request->file_path)) ){
            if( File::delete(public_path($request->file_path)) ){
                $remove = it_itmreq_file::where('itmreq_file_id',$request->file_id)->delete();
                $result = $remove;
            }
            else
                $result = false;
        }
        else
            $result = false;

        return response()->json($result);

    }
}
