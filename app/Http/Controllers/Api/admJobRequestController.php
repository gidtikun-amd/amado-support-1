<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\notifyLineController;
use App\ad_jobreq;
use App\ad_jobreq_detail;
use App\ad_jobreq_file;
use App\ad_jobreq_log;
use App\ad_jobreq_runningno;
use DB;
use Session;
use File;

class admJobRequestController extends Controller
{
    private $notify_line;

    public function __construct(){
        $this->notify_line = new notifyLineController();
    }

    public function pdf_data($id) {

        $header = DB::table('ad_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no',
                'b.it_emp_name_th', 'b.it_emp_surname_th', 'b.it_emp_name_eng', 'b.it_emp_surname_eng',
                'a.it_emp_position', 'c.it_dept_name', 'b.it_emp_tel',
                'a.jobreq_obj', 'a.jobreq_status', 'a.jobreq_demand', 'a.jobreq_reason',
                'a.jobreq_other',
                DB::raw('DATE_FORMAT(a.jobreq_date,"%d/%m/%Y") jobreq_date'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "ซ่อมแซม"
                        WHEN 2 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                        ELSE "อื่นๆ" END jobreq_obj_str'
                )
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->join('it_department AS c','b.it_dept_id','c.it_dept_id')
            ->where('a.jobreq_id',$id)
            ->first();

        $detail = DB::table('ad_jobreq_detail')
            ->select('jobreq_id','jobreq_line','jobreq_detail','jobreq_qty','jobreq_unit')
            ->where('jobreq_id',$id)
            ->get();

        $data = [
            'jobreq_no' => $header->jobreq_no,
            'jobreq_date' => $header->jobreq_date,
            'fullname_th' => $header->it_emp_name_th.' '.$header->it_emp_surname_th,
            'fullname_en' => $header->it_emp_name_eng.' '.$header->it_emp_surname_eng,
            'department' => $header->it_dept_name,
            'position' => $header->it_emp_position,
            'tel' => $header->it_emp_tel,
            'obj' => $header->jobreq_obj,
            'other' => $header->jobreq_other,
            'demand' => $header->jobreq_demand,
            'reason' => $header->jobreq_reason,
            'detail' => $detail
        ];

        return $data;

    }

    public function store(Request $request){

        $date = [ 'year' => date('y'), 'month' => date('m') ];
        $run_no = $this->getRunningNumber($date);
        $paper_no = 'AD-JOB-'.$date['year'].$date['month'].str_pad($run_no,7,'0',STR_PAD_LEFT);

        $jobreq = new ad_jobreq([
            'jobreq_no' => $paper_no,
            'jobreq_date' => $request->get('date'),
            'it_emp_id' => $request->get('emp_id'),
            'it_emp_position' => $request->get('position'),
            'jobreq_obj' => $request->get('obj'),
            'jobreq_other' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->get('other')),
            'jobreq_demand' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->get('demand')),
            'jobreq_reason' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->get('reason')),
            'log_session' => $request->get('emp_id'),
            'jobreq_status' => 1
        ]);

        if( $jobreq->save() ){

            $inst_id = $jobreq->id;

            $values = [];
            $line = 1;

            foreach( $request->get('list') as $val ){
                array_push($values,[
                    'jobreq_id' => $inst_id,
                    'jobreq_line' => $line++,
                    'jobreq_detail' => preg_replace('/[\x{200B}-\x{200D}]/u','',$val['detail']),
                    'jobreq_qty' => $val['qty'],
                    'jobreq_unit' => preg_replace('/[\x{200B}-\x{200D}]/u','',$val['unit']),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $jobreq_detail = ad_jobreq_detail::insert($values);

            if( $jobreq_detail ){

                $notify_msg = 'คุณ '.session()->get('name').' แผนก '.session()->get('department').' ขอเปิด Job Request เอกสารเลขที่ '.$paper_no;
                $this->notify_line->call_notity_group_administrative('jobreq',array('message' => $notify_msg));

                $this->setLog([ 'id' => $inst_id, 'no' => $paper_no, 'action' => 'สร้าง' ]);
                return response()->json(['Status' => true, 'paper_id' => $paper_no]);

            }
            else{

                $jobreq_delete = ad_jobreq::where('jobreq_id',$inst_id)->delete();
                return response()->json(['Status' => false]);

            }

        }
        else
            return response()->json(['Status' => false]);

    }

    private function getRunningNumber($date){

        $running_no = ad_jobreq_runningno::select('jobreq_rno_seq')
            ->where('jobreq_rno_year',$date['year'])
            ->where('jobreq_rno_month',$date['month']);

        $count_rows = $running_no;
        $checkEmpty = $count_rows = $count_rows->count();

        if( $checkEmpty > 0 ){

            $running_no  = $running_no->get();
            $number = $running_no[0]['jobreq_rno_seq']+1;

            $running_upd = ad_jobreq_runningno::where('jobreq_rno_year',$date['year'])
                ->where('jobreq_rno_month',$date['month'])
                ->update([
                    'jobreq_rno_seq' => $number
                ]);


            return $number;

        }
        else{

            $running_inst = new ad_jobreq_runningno([
                'jobreq_rno_year' => $date['year'],
                'jobreq_rno_month' => $date['month'],
                'jobreq_rno_seq' => 1
            ]);

            $running_inst->save();
            return 1;

        }

    }

    public function getJobRequestUserHistory(Request $request){

        $history = DB::table('ad_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no','a.jobreq_status',
                DB::raw('DATE_FORMAT(jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw('CONCAT(b.it_emp_name_th," ",b.it_emp_surname_th) emp_fullname'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "ซ่อมแซม"
                        WHEN 2 THEN "ซื้อ/ติดตั้งอุปกรณ์"
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

    public function searchJobRequestUserHistory(Request $request){

        $history = DB::table('ad_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no','a.jobreq_status',
                DB::raw('DATE_FORMAT(jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw('CONCAT(b.it_emp_name_th," ",b.it_emp_surname_th) emp_fullname'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "ซ่อมแซม"
                        WHEN 2 THEN "ซื้อ/ติดตั้งอุปกรณ์"
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
            ->where('a.jobreq_no','LIKE','%'.$request->search.'%')
            ->where('b.it_dept_id',$request->dept_id)
            ->orderBy('a.created_at','DESC')
            ->paginate($request->paginate);

        return response()->json($history);

    }

    public function getJobrequestPagin(Request $request){

        $result = DB::table('ad_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no','a.jobreq_status',
                'b.it_emp_name_th', 'b.it_emp_surname_th', 'b.it_emp_nickname_th',
                'a.it_emp_position', 'c.it_dept_name',
                DB::raw('DATE_FORMAT(a.jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "ซ่อมแซม"
                        WHEN 2 THEN "ซื้อ/ติดตั้งอุปกรณ์"
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
            ->join('it_department AS c','b.it_dept_id','c.it_dept_id');


        if( $request->filter == 'wait' ) $result = $result->where('a.jobreq_status',1);
        elseif( $request->filter == 'inprogress' ) $result = $result->where('a.jobreq_status',2);
        elseif( $request->filter == 'success' ) $result = $result->where('a.jobreq_status',3);
        elseif( $request->filter == 'void' ) $result = $result->where('a.jobreq_status',0);

        if( !empty($request->search) )
            $result = $result->where('a.jobreq_no','LIKE','%'.$request->search.'%');

        $result = $result->orderBy('a.jobreq_date','DESC')
            ->orderBy('a.created_at','DESC')
            ->paginate($request->paginate);

        return response()->json($result);

    }

    public function getJobrequestDetail($id){

        $hader = DB::table('ad_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no',
                'b.it_emp_name_th', 'b.it_emp_surname_th', 'b.it_emp_name_eng', 'b.it_emp_surname_eng',
                'a.it_emp_position', 'c.it_dept_name', 'b.it_emp_tel',
                'a.jobreq_obj', 'a.jobreq_status', 'a.jobreq_demand', 'a.jobreq_reason',
                'a.jobreq_other', 'a.jobreq_remark',
                DB::raw('DATE_FORMAT(a.jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "ซ่อมแซม"
                        WHEN 2 THEN "ซื้อ/ติดตั้งอุปกรณ์"
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

        $detial = ad_jobreq_detail::select('jobreq_id','jobreq_line','jobreq_detail','jobreq_qty','jobreq_unit')
            ->where('jobreq_id',$id)
            ->get();

        $admin = DB::table('ad_jobreq AS ij')
            ->select('ij.jobreq_assign',DB::raw('CONCAT(ie.it_emp_name_th," ",ie.it_emp_surname_th) AS emp_name'))
            ->join('user AS u','ij.jobreq_assign','u.user')
            ->join('it_employee AS ie','u.it_emp_id','ie.it_emp_id')
            ->where('ij.jobreq_id',$id)
            ->first();

        $log = DB::table('ad_jobreq_log AS ijl')
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
            'log' => [
                'admin' => $admin,
                'list' => $log
            ]
        ];

        return response()->json($result);

    }

    public function updateJobreqStauts(Request $request){

        $result = ad_jobreq::where('jobreq_id',$request->id)
            ->update([
                'jobreq_status' => $request->status,
                'log_session' => session()->get('user')
            ]);

        if( session()->get('role') == 'user.admin' || session()->get('role') == 'user.admin.manager' ){
            $assign = ad_jobreq::where('jobreq_id',$request->id)
                ->whereNull('jobreq_assign')
                ->update(['jobreq_assign' => session()->get('user')]);
        }

        if($result){

            $no = ad_jobreq::select('jobreq_no')
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

        return response()->json($result);

    }

    public function updateJobreqRemark(Request $request){

        $update = ad_jobreq::where('jobreq_id',$request->id)
            ->update([
                'jobreq_remark' => $request->remark
            ]);

        return response()->json($update);

    }

    public function getJobreqFile($id){

        $file = ad_jobreq_file::where('jobreq_id',$id)
            ->select('jobreq_file_id','jobreq_file_name','jobreq_file_path')
            ->get();

        return response()->json($file);

    }

    public function uploadJobreqFile(Request $request){

        $file_newname = 'ad_jobreq_'.date('YmdHis').'.pdf';

        if( $request->file->move(public_path('upload/jobreq'), $file_newname) ){

            $upload = new ad_jobreq_file([
                'jobreq_id' => $request->jobreq_id,
                'jobreq_file_name' => $file_newname,
                'jobreq_file_path' => '/upload/jobreq/'.$file_newname
            ]);

            $result = $upload->save();
            return response()->json($result);

        }
        else{
            $result = false;
            return response()->json($result);
        }

    }

    public function removeJobreqFile(Request $request){

        if( File::exists(public_path($request->file_path)) ){
            if( File::delete(public_path($request->file_path)) ){
                $remove = ad_jobreq_file::where('jobreq_file_id',$request->file_id)->delete();
                $result = true;
            }
            else
                $result = false;
        }
        else
            $result = false;

        return response()->json($result);

    }

    private function setLog($dataset){

        $log = new ad_jobreq_log([
            'jobreq_id' => $dataset['id'],
            'jobreq_no' => $dataset['no'],
            'jobreq_action' => $dataset['action'],
            'jobreq_user' => session()->get('user')
        ]);

        $log->save();

    }

    //
}
