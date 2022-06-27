<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\notifyLineController;
use App\Http\Controllers\Security\LoginController;
use App\it_ticket;
use App\it_ticket_log;
use App\it_ticket_subject;
use App\it_ticket_file;
use App\it_ticket_runningno;
use App\it_ticket_message;
use DB;
use Session;

class itTicketController extends Controller
{
    private $notify_line;

    public function __construct(){
        $this->notify_line = new notifyLineController();
    }

    public function getAllTicketSubject(){
        $subject = it_ticket_subject::all();
        return response()->json($subject);
    }

    public function getAllTicketSubjectActive(){
        $subject = it_ticket_subject::where('it_ticket_subject_active',1)->get();
        return response()->json($subject);
    }

    public function saveTicketSubject(Request $request){

        $subject = new it_ticket_subject([
                        'it_ticket_subject_name' => $request->subject_text,
                        'it_ticket_subject_active' => $request->subject_active,
                        'it_survey_id' => 1
                    ]);

        $result = $subject->save();
        return response()->json($result);

    }

    public function updateStatusTicketSubject(Request $request){

        $subject = it_ticket_subject::where('it_ticket_subject_id',$request->subject_id)
                    ->update(['it_ticket_subject_active' => $request->subject_active]);

        return response()->json($subject);

    }

    public function deleteTicketSubject($id){

        if($this->checkSubjectInTicket($id) > 0){
            return response()->json([
                'status' => false,
                'message' => 'ไม่สามารถลบเรื่องการแจ้งได้ เนื่องจากมีข้อมูลใน Ticket'
            ]);
        }

        $subject = it_ticket_subject::where('it_ticket_subject_id',$id)->delete();
        return response()->json(['status' => true]);

    }

    private function checkSubjectInTicket($id){
        $count = it_ticket::where('it_ticket_subject_id',$id)->count();
        return $count;
    }

    private function checkUsedDoSurvey($dataset){

        $end_job = it_ticket::select('it_ticket_end')
                    ->where([
                        'it_emp_id' => $dataset->user,
                        'it_ticket_status' => 4,
                        'survey_status' => 0
                    ])
                    ->orderBy('it_ticket_end','ASC')
                    ->first();

        if( !empty($end_job) ){
            if($end_job->it_ticket_end < date('Y-m-d 23:59:59',strtotime('-1 day')) )
                return false;
            else
                return true;
        }
        else
            return true;

    }

    public function generateTicket(Request $request){

        if(!$this->checkUsedDoSurvey($request)){
            return response()->json([
                'status' => false,
                'title' => 'คุณมีแบบประเมินยังไม่ได้ทำ',
                'message' => 'กรุณาทำแบบประเมิน Tikcet ก่อนจึงจะเปิด Ticket ได้'
            ]);
        }

        $date = [ 'year' => date('y'), 'month' => date('m') ];
        $run_no = $this->getRunningNumber($date);
        $paper_no = 'IT-TICKET-'.$date['year'].$date['month'].str_pad($run_no,7,'0',STR_PAD_LEFT);
        $action_datetime = date('Y-m-d H:i:s');

        $ticket = new it_ticket([
            'it_ticket_no' => $paper_no,
            'it_ticket_date' => $action_datetime,
            'it_emp_id' => $request->user,
            'it_ticket_subject_id' => $request->subject_id,
            'it_ticket_detail' => $request->ticket_detail,
            'it_ticket_callback' => $request->ticket_callback,
            'it_ticket_level' => 1,
            'it_ticket_status' => 1,
            'it_ticket_status_agree' => 1
        ]);

        if($ticket->save()){

            $inst_id = $ticket->id;

            $update_surv = DB::table('it_ticket AS it')
                            ->join('it_ticket_subject AS its','it.it_ticket_subject_id','its.it_ticket_subject_id')
                            ->where('it.it_ticket_id',$inst_id)
                            ->update(['it.it_survey_id' => DB::raw('its.it_survey_id')]);

            if(!empty($request->file) && $request->file != 'null'){

                $file_newname = 'ticket_'.$inst_id.'_'.date('YmdHis').'.jpg';

                if( $request->file->move(public_path('upload/ticket'), $file_newname) ){

                    $upload = new it_ticket_file([
                        'it_ticket_id' => $inst_id,
                        'it_ticket_file' => $file_newname,
                        'it_ticket_file_path' => 'upload/ticket/'.$file_newname
                    ]);

                    $upload->save();

                }

            }

            $log = [
                'id' => $inst_id,
                'action' => 'เปิด Ticket',
                'date' => $action_datetime,
                'user' => $request->user
            ];

            $this->saveLog($log);

            $notify_msg = 'New ticket '.$paper_no.' กรุณาระบุความสำคัญ';
            $this->notify_line->call_notify_group_ticket_it_support('manager',array('message' => $notify_msg));

            return response()->json(['status' => true, 'paper_no' => $paper_no]);

        }
        else
            return response()->json(false);

    }

    private function getRunningNumber($date){

        $running_no = it_ticket_runningno::select('ittk_rno_seq')
            ->where([
                ['ittk_rno_year',$date['year']],
                ['ittk_rno_month',$date['month']]
            ]);

        $count_rows = $running_no;
        $checkEmpty = $count_rows->count();

        if($checkEmpty > 0){

            $running_no = $running_no->first();
            $number = $running_no['ittk_rno_seq']+1;

            $running_upd = it_ticket_runningno::where([
                ['ittk_rno_year',$date['year']],
                ['ittk_rno_month',$date['month']]
            ])
            ->update([ 'ittk_rno_seq' => $number ]);

            return $number;

        }
        else{

            $running_inst = new it_ticket_runningno([
                'ittk_rno_year' => $date['year'],
                'ittk_rno_month' => $date['month'],
                'ittk_rno_seq' => 1
            ]);

            $running_inst->save();
            return 1;

        }

    }

    public function getTicketUserHistory(Request $request){

        $history = DB::table('it_ticket AS it')
                    ->select(
                        'it.it_ticket_id AS ticket_id','it.it_ticket_no AS ticket_no',
                        'its.it_ticket_subject_name AS subject_name',
                        'it.it_ticket_status AS ticket_sts','it.it_ticket_level AS ticket_level',
                        DB::raw('DATE_FORMAT(it.it_ticket_date,\'%Y-%m-%d\') AS ticket_date'),
                        DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name'),
                        DB::raw('IFNULL(
                            (SELECT CONCAT(innie.it_emp_name_th,\' \',innie.it_emp_surname_th)
                                FROM it_employee AS innie
                                INNER JOIN user AS u ON innie.it_emp_id = u.it_emp_id
                                WHERE u.user = it.it_ticket_operator
                        ),\'ไม่มีผู้ดำเนินการ\') AS assign_name'),
                        DB::raw('CASE it.it_ticket_status
                                    WHEN 1 THEN \'รออนุมัติ\'
                                    WHEN 2 THEN \'รอรับดำเนินการ\'
                                    WHEN 3 THEN \'กำลังดำเนินการ\'
                                    WHEN 4 THEN \'ดำเนินการเรียบร้อย\'
                                    ELSE \'ไม่อนุมัติ/ยกเลิก\' END AS ticket_sts_str')
                    )
                    ->join('it_ticket_subject AS its','it.it_ticket_subject_id','its.it_ticket_subject_id')
                    ->join('it_employee AS ie','it.it_emp_id','ie.it_emp_id')
                    ->where('ie.it_dept_id',session()->get('departmentId'));

        if(!empty($request->search))
            $history = $history->where('it.it_ticket_no','LIKE','%'.$request->search.'%');

        if( !empty($request->searchEmp) ){
            $history = $history->whereRaw('(
                ie.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        $history = $history->orderBy('it.it_ticket_date','DESC')
                    ->paginate(20);

        return response()->json($history);

    }

    public function getTicketList(Request $request){

        $list = DB::table('it_ticket AS it')
                ->select(
                    'it.it_ticket_id AS ticket_id','it.it_ticket_no AS ticket_no',
                    'id.it_dept_name AS detp_name','its.it_ticket_subject_name AS subject_name',
                    'it.it_ticket_status AS ticket_sts','it.it_ticket_level AS ticket_level',
                    'it.survey_status',
                    DB::raw('DATE_FORMAT(it.it_ticket_date,\'%Y-%m-%d\') AS ticket_date'),
                    DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name'),
                    DB::raw('IFNULL(
                        (SELECT CONCAT(innie.it_emp_name_th,\' \',innie.it_emp_surname_th)
                            FROM it_employee AS innie
                            INNER JOIN user AS u ON innie.it_emp_id = u.it_emp_id
                            WHERE u.user = it.it_ticket_operator
                    ),\'ไม่มีผู้ดำเนินการ\') AS assign_name'),
                    DB::raw('CASE it.it_ticket_status
                                WHEN 1 THEN \'รออนุมัติ\'
                                WHEN 2 THEN \'รอรับดำเนินการ\'
                                WHEN 3 THEN \'กำลังดำเนินการ\'
                                WHEN 4 THEN \'ดำเนินการเรียบร้อย\'
                                ELSE \'ไม่อนุมัติ/ยกเลิก\' END AS ticket_sts_str')
                )
                ->join('it_ticket_subject AS its','it.it_ticket_subject_id','its.it_ticket_subject_id')
                ->join('it_employee AS ie','it.it_emp_id','ie.it_emp_id')
                ->join('it_department AS id','ie.it_dept_id','id.it_dept_id');

        if(!empty($request->search))
            $list = $list->where('it.it_ticket_no','LIKE','%'.$request->search.'%');

        if(!empty($request->status)){
            if($request->status != 'all'){

                if($request->status == 'waitappr') $sts = 1;
                elseif($request->status == 'waitproc') $sts = 2;
                elseif($request->status == 'progress') $sts = 3;
                elseif($request->status == 'success') $sts = 4;
                else $sts = 0;

                $list = $list->where('it.it_ticket_status',$sts);

            }
        }

        if( !empty($request->searchEmp) ){
            $list = $list->whereRaw('(
                ie.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        $list = $list->orderBy('it.updated_at','DESC')
                    ->paginate(20);

        return response()->json($list);

    }

    public function setTicketDetailJson($id){
        return response()->json($this->getTicketDetail($id));
    }

    public function setTicketDetailArray($id){
        return (object) $this->getTicketDetail($id);
    }

    private function getTicketDetail($id){

        $detail = DB::table('it_ticket AS it')
                ->select(
                    'it.it_ticket_id AS ticket_id','it.it_ticket_no AS ticket_no',
                    'id.it_dept_name AS detp_name','its.it_ticket_subject_name AS subject_name',
                    'it.it_ticket_status AS ticket_sts','it.it_ticket_level AS ticket_level',
                    'it.it_ticket_detail AS ticket_detail','it.it_ticket_approve_remark AS approve_remark',
                    'it.it_ticket_assign_date AS assign_date','it.it_ticket_subject_id AS subject_id',
                    'it.it_ticket_approve AS ticket_approve',
                    'it.it_ticket_operator AS ticket_operator',
                    'it.it_survey_id','it.survey_status','it.it_emp_id',
                    'it.it_ticket_callback AS ticket_callback',
                    DB::raw('DATE_FORMAT(it.it_ticket_date,\'%Y-%m-%d\') AS ticket_date'),
                    DB::raw('DATE_FORMAT(it.it_ticket_end,\'%Y-%m-%d\') AS ticket_end'),
                    DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name'),
                    DB::raw('IFNULL(
                        (SELECT CONCAT(innie.it_emp_name_th,\' \',innie.it_emp_surname_th)
                            FROM it_employee AS innie
                            INNER JOIN user AS u ON innie.it_emp_id = u.it_emp_id
                            WHERE u.user = it.it_ticket_operator
                        ),\'ไม่มีผู้ดำเนินการ\') AS assign_name'),
                    DB::raw('CASE it.it_ticket_status
                                WHEN 1 THEN \'รออนุมัติ\'
                                WHEN 2 THEN \'รอรับดำเนินการ\'
                                WHEN 3 THEN \'กำลังดำเนินการ\'
                                WHEN 4 THEN \'ดำเนินการเรียบร้อย\'
                                ELSE \'ไม่อนุมัติ/ยกเลิก\' END AS ticket_sts_str')
                )
                ->join('it_ticket_subject AS its','it.it_ticket_subject_id','its.it_ticket_subject_id')
                ->join('it_employee AS ie','it.it_emp_id','ie.it_emp_id')
                ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
                ->where('it.it_ticket_id',$id)
                ->first();

        $file = it_ticket_file::where('it_ticket_id',$id)->get();

        return ['detail' => $detail, 'files' => $file];

    }

    public function setChangeSubjectTicket(Request $request){

        $change = it_ticket::where('it_ticket_id',$request->ticket_id)
                    ->update(['it_ticket_subject_id' => $request->subject_id]);

        if($change){

            $log = [
                'id' => $request->ticket_id,
                'action' => 'เปลี่ยนแปลง Subject จาก ID '.$request->oid.' เป็น '.$request->nid,
                'date' => date('Y-m-d H:i:s'),
                'user' => $request->user
            ];

            $this->saveLog($log);
            return response()->json(true);

        }
        else
            return response()->json(false);

    }

    public function setTicketLevel(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $ticket_lvl = ($request->approve_sts != 0 ? $request->approve_level : 0);
        $ticket_sts = ($request->approve_sts != 0 ? $request->next_sts : 0);
        $date = date('Y-m-d H:i:s');

        $approve = it_ticket::where('it_ticket_id',$request->ticket_id)
                    ->update([
                        'it_ticket_level' => $ticket_lvl,
                        'it_ticket_status' => $ticket_sts,
                        // 'it_ticket_approve' => $request->user,
                        'it_ticket_approve' => session()->get('user'),
                        'it_ticket_approve_remark' => $request->approve_remark,
                        'it_ticket_approve_date' => $date
                    ]);

        if($approve){

            $log = [
                'id' => $request->ticket_id,
                'action' => 'อนุมัติและกำหนดความสำคัญ',
                'date' => $date,
                'user' => session()->get('user')
                // 'user' => $request->user
            ];

            $this->saveLog($log);

            if( $ticket_sts != 0 ){

                if( $ticket_lvl == 1 )
                    $appr_noti = ' ได้กำหนดความสำคัญเป็น Low ผู้ที่เกี่ยวข้องกรุณาดำเนินการต่อ';
                elseif( $ticket_lvl == 2 )
                    $appr_noti = ' ได้กำหนดความสำคัญเป็น Medium ผู้ที่เกี่ยวข้องกรุณารีบดำเนินการ';
                elseif( $ticket_lvl == 3 )
                    $appr_noti = ' ได้กำหนดความสำคัญเป็น high ผู้ที่เกี่ยวข้องกรุณารีบดำเนินการโดยด่วน';

                $notify_msg = 'ticket '.$request->ticket_no.$appr_noti;
                $this->notify_line->call_notify_group_ticket_it_support('staff',array('message' => $notify_msg));

            }
            else{
                $cancel_who = it_ticket::where('it_ticket_id',$request->ticket_id)
                                ->update([
                                    'it_ticket_operator' => session()->get('user')
                                    // 'it_ticket_operator' => $request->user
                                ]);
            }

            $response = ['ticket_sts' => $ticket_sts, 'ticket_lvl' => $ticket_lvl];
            return response()->json(array('status' => true, 'detail' => $response));

        }
        else
            return response()->json(array('status' => false));

    }

    public function setTicketAssign(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $assign = it_ticket::where('it_ticket_id',$request->ticket_id)
                    ->update([
                        'it_ticket_status' => $request->next_sts,
                        // 'it_ticket_operator' => $request->user,
                        'it_ticket_operator' => session()->get('user'),
                        'it_ticket_assign_date' => date('Y-m-d H:i:s')
                    ]);

        if($assign){

            $log = [
                'id' => $request->ticket_id,
                'action' => 'รับดำเนินการ',
                'date' => date('Y-m-d H:i:s'),
                'user' => session()->get('user')
                // 'user' => $request->user
            ];

            $this->saveLog($log);

            $assign_user = DB::table('it_employee AS ie')
                            ->select(DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS assign_name'))
                            ->join('user AS u','ie.it_emp_id','u.it_emp_id')
                            ->where('u.user',session()->get('user'))
                            ->first();

            return response()->json(array('status' => true, 'assign_name' => $assign_user->assign_name));

        }
        else
            return response()->json(array('status' => false));

    }

    public function setTicketEndJob(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $end_date = date('Y-m-d H:i:s');

        $end = it_ticket::where('it_ticket_id',$request->ticket_id)
                    ->update([
                        'it_ticket_end' => $end_date,
                        'it_ticket_status' => $request->next_sts
                    ]);

        if($end){

            $log = [
                'id' => $request->ticket_id,
                'action' => 'ปิดงาน',
                'date' => $end_date,
                'user' => session()->get('user')
                // 'user' => $request->user
            ];

            $this->saveLog($log);
            return response()->json(array('status' => true, 'edate' => $end_date));

        }
        else
            return response()->json(array('status' => false));

    }

    public function updateStsTicket(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $update = it_ticket::where('it_ticket_id',$request->ticket_id)
                    ->update([
                        'it_ticket_status' => $request->status,
                        'it_ticket_level' => 0
                    ]);

        if( $update ){

            $log = [
                'id' => $request->ticket_id,
                'action' => 'Void',
                'date' => date('Y-m-d H:i:s'),
                'user' => session()->get('user')
                // 'user' => $request->user
            ];

            $this->saveLog($log);

        }

        return response()->json(array('status' => $update));

    }

    public function setLog($dataset){
        $this->saveLog($dataset);
    }

    private function saveLog($dataset){

        $log = new it_ticket_log([
            'it_ticket_id' => $dataset['id'],
            'it_ticket_status' => $dataset['action'],
            'it_ticket_status_date' => $dataset['date'],
            'it_ticket_action_user' => $dataset['user']
        ]);

        $log->save();

    }

    public function getTicketMessage($id){

        $list = DB::table('it_ticket_message AS itm')
                ->join('user AS u','itm.tkms_user','u.user')
                ->join('it_employee AS ie','u.it_emp_id','ie.it_emp_id')
                ->select(
                    'itm.tkms_id','itm.it_ticket_id AS ticket_id',
                    'itm.tkms_text','itm.created_at',
                    DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name')
                )
                ->where('itm.it_ticket_id',$id)
                ->orderBy('itm.created_at','DESC')
                ->get();

        return response()->json($list);

    }

    public function getUserTicketMessage($id){

        $message =  DB::table('it_ticket_message AS itm')
                    ->join('user AS u','itm.tkms_user','u.user')
                    ->join('it_employee AS ie','u.it_emp_id','ie.it_emp_id')
                    ->select(
                        'itm.tkms_id AS id','itm.it_ticket_id AS ticket_id',
                        'itm.tkms_text AS msg_text','itm.created_at AS create_date',
                        DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name')
                    )
                    ->where('itm.it_ticket_id',$id)
                    ->orderBy('create_date','DESC')
                    ->get();

        return response()->json($message);

    }

    public function setTicketMessage(Request $request){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $new = new it_ticket_message([
            'it_ticket_id' => $request->ticket_id,
            'tkms_text' => $request->message_text,
            'tkms_user' => $request->user
        ]);

        $result = $new->save();
        return response()->json(array('status' => $result));

    }

    public function removeTicketMessage($id){

        if(LoginController::getLoginStatus())
            return response()->json(array('status' => false, 'error' => 'login.fail'));

        $delete = it_ticket_message::where('tkms_id',$id)->delete();
        return response()->json($delete);

    }

    public function countUserTicketNonSurvey(){

        $count = it_ticket::select('it_ticket_id')
                    ->where([
                        ['it_emp_id',session()->get('user')],
                        ['it_ticket_status',4],
                        ['survey_status',0]
                    ])
                    ->count();

        return response()->json(['count' => $count]);

    }
}
