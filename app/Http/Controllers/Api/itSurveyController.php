<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\itTicketController;
use App\it_ticket;
use App\it_ticket_log;
use App\it_jobreq;
use App\it_survey;
use App\it_survey_subject;
use App\it_survey_header;
use App\it_survey_detail;
use App\it_survey_runningno;
use DB;
use Session;

class itSurveyController extends Controller
{


    public function getJobreqSurveyDetail(Request $request){

        $jobreq_h = DB::table('it_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no','c.it_dept_name','a.jobreq_date',
                'a.jobreq_svsts','a.jobreq_svuser','a.jobreq_svdate',
                'a.it_emp_id',
                DB::raw('CONCAT(b.it_emp_name_th,\' \',b.it_emp_surname_th) as emp_fullname')
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->join('it_department AS c','b.it_dept_id','c.it_dept_id')
            ->where('a.jobreq_id',$request->id)
            ->first();

        $jobreq_d = DB::table('it_jobreq_detail')
            ->select('jobreq_id','jobreq_line','jobreq_detail','jobreq_qty','jobreq_unit')
            ->where('jobreq_id',$request->id)
            ->get();

        $jobreq_ad = DB::table('it_jobreq AS ij')
            ->select(
                'ij.jobreq_assign',            
                DB::raw('CONCAT(ie.it_emp_name_th," ",ie.it_emp_surname_th) AS emp_name')
            )
            ->join('user AS u','ij.jobreq_assign','u.user')
            ->join('it_employee AS ie','u.it_emp_id','ie.it_emp_id')
            ->where('ij.jobreq_id',$request->id)
            ->first();

        if($jobreq_h->jobreq_svsts == 1){

            $survey_h = DB::table('it_survey_header as ish')
                ->select(
                    'ish.it_survey_header_id as header_id', 'ish.it_survey_no as survey_no', 
                    'ish.it_survey_id as survey_id', 'ish.it_survey_ref_id as ref_id', 
                    'ish.it_survey_remark as survey_remark', 'ish.it_survey_result as survey_result'
                )
                ->join('it_survey as is','ish.it_survey_id','is.it_survey_id')
                ->where([
                    ['ish.it_survey_ref_id',$jobreq_h->jobreq_id],
                    ['is.it_survey_type',1],
                    ['is.it_survey_active',1]
                ])
                ->first();

            $survey_d = DB::table('it_survey_subject as iss')
                    ->select(
                        'iss.it_survey_subject_id as subject_id',
                        'iss.it_survey_subject_text as subject_text',
                        DB::raw('IFNULL(isd.it_survey_subject_level,0) as survey_level')
                    )
                    ->leftJoin('it_survey_detail as isd','iss.it_survey_subject_id','isd.it_survey_subject_id') 
                    ->where('isd.it_survey_header_id',$survey_h->header_id)
                    ->orderBy('iss.it_survey_subject_id')
                    ->get();
            
            $survey = array(
                'header' => $survey_h,
                'detail' => $survey_d
            );

        }
        else{

            $survey_d = DB::table('it_survey_subject as iss')
                ->select(
                    'iss.it_survey_subject_id as subject_id', 'iss.it_survey_id as survey_id', 
                    'iss.it_survey_subject_text as subject_text',
                    DB::raw('0 as survey_level')
                )
                ->join('it_survey as is','iss.it_survey_id','is.it_survey_id')
                ->where([
                    ['is.it_survey_type',1],
                    ['is.it_survey_active',1],
                    ['iss.it_survey_subject_active',1]
                ])
                ->orderBy('iss.it_survey_subject_id','ASC')
                ->get();

            $survey = array(
                'header' => array(
                    'ref_id' => $jobreq_h->jobreq_id,
                    'survey_id' => $survey_d[0]->survey_id,
                    'survey_remark' => '',
                    'survey_result' => ''
                ),
                'detail' => $survey_d
            );

        }

        $result = array(
            'jobreq' => array(
                'header' => $jobreq_h,
                'detail' => $jobreq_d,
                'admin' => $jobreq_ad
            ),
            'survey' => $survey
        );

        return response()->json($result);

    }    

    public function saveJobreqSurvey(Request $request){
        
        $date = [ 'year' => date('y'), 'month' => date('m') ];
        $run_no = $this->getRunningNumber($date,'JR');
        $action_datetime = date('Y-m-d H:i:s');

        $paper_no = 'IT-EVASYS-JR-'.$date['year'].$date['month'].str_pad($run_no,7,'0',STR_PAD_LEFT);

        $survey_header = new it_survey_header([
            'it_survey_no' => $paper_no,
            'it_survey_id' => $request->dataset['header']['survey_id'],
            'it_survey_ref_id' => $request->dataset['header']['ref_id'],
            'it_survey_result' => $request->dataset['header']['survey_result'],
            'it_survey_remark' => $request->dataset['header']['survey_remark']
        ]);

        if($survey_header->save()){

            $inst_id = $survey_header->id;
            $values = [];

            foreach($request->dataset['detail'] as $val){
                array_push($values,[
                    'it_survey_header_id' => $inst_id,
                    'it_survey_subject_id' => $val['subject_id'],
                    'it_survey_subject_level' => $val['survey_level'],
                    'created_at' => $action_datetime,
                    'updated_at' => $action_datetime
                ]);
            }

            $survey_detail = it_survey_detail::insert($values);

            $update_jobreq = it_jobreq::where('jobreq_id',$request->dataset['header']['ref_id'])
                ->update([
                    'jobreq_svsts' => 1,
                    'jobreq_svuser' => $request->user,
                    'jobreq_svdate' => $action_datetime
                ]);
            
            return response()->json(true);

        }
        else 
            return response()->json(false);

    }

    public function getAdminListTicketSurvey(Request $request){

        $list = DB::table('it_ticket AS it')
                ->select(
                    'it.it_ticket_id AS ticket_id','it.it_ticket_no AS ticket_no',
                    'its.it_ticket_subject_name AS subject_name',
                    'id.it_dept_name AS dept_name',
                    DB::raw('DATE_FORMAT(it.it_ticket_date,\'%Y-%m-%d\') AS ticket_pdate'),
                    DB::raw('DATE_FORMAT(it.it_ticket_end,\'%Y-%m-%d %H:%i\') AS ticket_edate'),
                    DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name'),
                    DB::raw('IFNULL(
                        (SELECT CONCAT(innie.it_emp_name_th,\' \',innie.it_emp_surname_th)
                            FROM it_employee AS innie
                            INNER JOIN user AS u ON innie.it_emp_id = u.it_emp_id
                            WHERE u.user = it.it_ticket_operator
                    ),\'ไม่มีผู้ดำเนินการ\') AS assign_name')
                )
                ->join('it_ticket_subject AS its','it.it_ticket_subject_id','its.it_ticket_subject_id')
                ->join('it_employee AS ie','it.it_emp_id','ie.it_emp_id')
                ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
                ->where([
                    ['it_ticket_status',4],
                    ['survey_status',$request->status]
                ]);

        if(!empty($request->search))
            $list = $list->where('it.it_ticket_no','LIKE','%'.$request->search.'%');

        if(!empty($request->dept_id))
            $list = $list->where('ie.it_dept_id',$request->dept_id);

        if( !empty($request->searchEmp) ){
            $list = $list->whereRaw('(
                ie.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                or ie.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
            )');
        }

        $list = $list->orderBy('it.it_ticket_end','DESC')
                ->paginate(20);

        return response()->json($list);

    }

    public function setTicketSurveyDetailJson($id){
        return response()->json($this->getTikcetSurveyDetail($id));
    }

    public function setTicketSurveyDetailArray($id){
        return $this->getTikcetSurveyDetail($id);
    }

    private function getTikcetSurveyDetail($id){

        $ticket = new itTicketController();
        $ticket_detail = $ticket->setTicketDetailArray($id);

        $survey = [ 'lavel_overall' => 0, 'survey_remark' => '' ];
        $survey_detail = [];

        if($ticket_detail->detail->survey_status == 1){
            $survey = it_survey_header::where('it_survey_ref_id',$id)
                        ->select(
                            'it_survey_header_id AS header_id','it_survey_id AS survey_id',
                            'it_survey_ref_id AS ref_id','it_survey_level_overall AS level_overall',
                            'it_survey_remark AS survey_remark'
                        )
                        ->first();

            $survey_detail = DB::table('it_survey_subject AS iss')
                            ->select(
                                'iss.it_survey_subject_id AS subject_id',
                                'iss.it_survey_subject_text AS subject_text',
                                DB::raw('IFNULL(isd.it_survey_subject_level,0) AS survey_scores')
                            )
                            ->leftJoin('it_survey_detail AS isd','iss.it_survey_subject_id','isd.it_survey_subject_id')
                            ->where('iss.it_survey_id',$ticket_detail->detail->it_survey_id)
                            ->where('isd.it_survey_header_id',$survey->header_id)
                            ->get();
        }
        else{
            $survey_detail = it_survey_subject::where('it_survey_id',$ticket_detail->detail->it_survey_id)
                                ->select(
                                    'it_survey_subject_id AS subject_id',
                                    'it_survey_subject_text AS subject_text',
                                    DB::raw('0 AS survey_scores')
                                )
                                ->get();
        }

        return [
            'ticket' => $ticket_detail,
            'survey' => [
                'header' => $survey,
                'scores' => $survey_detail
            ]
        ];

    }

    public function getTicketSurveyPDF($id){

        $ticket = DB::table('it_ticket AS it')
                    ->join('it_ticket_subject AS its','it.it_ticket_subject_id','its.it_ticket_subject_id')
                    ->join('it_employee AS ie','it.it_emp_id','ie.it_emp_id')
                    ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
                    ->where('it.it_ticket_id',$id)
                    ->select(
                        'it.it_ticket_no AS ticket_no',
                        DB::raw('CONCAT(ie.it_emp_name_th," ",ie.it_emp_surname_th) AS name_th'),
                        DB::raw('CONCAT(ie.it_emp_name_eng," ",ie.it_emp_surname_eng) AS name_eng'),
                        'id.it_dept_name AS dept_name','ie.it_emp_tel AS tel',
                        'its.it_ticket_subject_name AS subject_name','it.it_survey_id',
                        'it.it_ticket_callback AS ticket_callback',
                        DB::raw("DATE_FORMAT(it.it_ticket_date,'%Y-%m-%d') AS ticket_date")
                    )
                    ->first();

        $survey = it_survey_header::where('it_survey_ref_id',$id)
                    ->select(
                        'it_survey_header_id AS header_id','it_survey_id AS survey_id',
                        'it_survey_ref_id AS ref_id','it_survey_level_overall AS level_overall',
                        'it_survey_remark AS survey_remark',
                        'it_survey_no AS survey_no',
                        DB::raw('DATE_FORMAT(created_at,\'%Y-%m-%d\') AS survey_date')
                    )
                    ->first();


        $survey_detail = DB::table('it_survey_subject AS iss')
                        ->select(
                            'iss.it_survey_subject_id AS subject_id',
                            'iss.it_survey_subject_text AS subject_text',
                            DB::raw('IFNULL(isd.it_survey_subject_level,0) AS survey_scores')
                        )
                        ->leftJoin('it_survey_detail AS isd','iss.it_survey_subject_id','isd.it_survey_subject_id')
                        ->where('iss.it_survey_id',$ticket->it_survey_id)
                        ->where('isd.it_survey_header_id',$survey->header_id)
                        ->get();

        return (object) [
            'ticket' => $ticket,
            'survey_header' => $survey,
            'survey_detail' => $survey_detail
        ];

    }

    public function saveAssessmentSurvey(Request $request){

        $date = [ 'year' => date('y'), 'month' => date('m') ];
        $run_no = $this->getRunningNumber($date,$request->type);
        $action_datetime = date('Y-m-d H:i:s');

        if($request->type == 'TK') $prefix = 'IT-EVASUPP';
        elseif($request->type == 'PG') $prefix = 'IT-EVASYS';

        $paper_no = $prefix.'-'.$request->type.'-'.$date['year'].$date['month'].str_pad($run_no,7,'0',STR_PAD_LEFT);

        $survey_header = new it_survey_header([
            'it_survey_no' => $paper_no,
            'it_survey_id' => $request->survey_id,
            'it_survey_ref_id' => $request->ref_id,
            'it_survey_level_overall' => $request->survey_overall,
            'it_survey_remark' => $request->survey_remark
        ]);

        if($survey_header->save()){

            $inst_id = $survey_header->id;
            $values = [];

            foreach($request->survey_scores as $val){
                array_push($values,[
                    'it_survey_header_id' => $inst_id,
                    'it_survey_subject_id' => $val['subject_id'],
                    'it_survey_subject_level' => $val['survey_scores'],
                    'created_at' => $action_datetime,
                    'updated_at' => $action_datetime
                ]);
            }

            $survey_detail = it_survey_detail::insert($values);

            if($request->type == 'TK'){

                $ticket = it_ticket::where('it_ticket_id',$request->ref_id)
                            ->update([
                                'survey_status' => 1,
                                'survey_user' => $request->user,
                                'survey_date' => $action_datetime
                            ]);

                $log = [
                    'id' => $request->ref_id,
                    'action' => 'ทำแบบประเมิน',
                    'date' => $action_datetime,
                    'user' => $request->user
                ];

                $obj = new itTicketController();
                $obj->setLog($log);

            }

            return response()->json(true);

        }
        else
            return response()->json(false);

    }

    private function getRunningNumber($date,$type){

        $running_no = it_survey_runningno::select('survey_rno_seq')
                        ->where([
                            ['survey_rno_year',$date['year']],
                            ['survey_rno_month',$date['month']],
                            ['survey_rno_type',$type]
                        ]);

        $count_rows = $running_no;
        $checkEmpty = $count_rows->count();

        if($checkEmpty > 0){

            $running_no = $running_no->first();
            $number = $running_no['survey_rno_seq']+1;

            $running_upd = it_survey_runningno::where([
                ['survey_rno_year',$date['year']],
                ['survey_rno_month',$date['month']],
                ['survey_rno_type',$type]
            ])
            ->update([ 'survey_rno_seq' => $number ]);

            return $number;

        }
        else{

            $running_inst = new it_survey_runningno([
                'survey_rno_year' => $date['year'],
                'survey_rno_month' => $date['month'],
                'survey_rno_type' => $type,
                'survey_rno_seq' => 1
            ]);

            $running_inst->save();
            return 1;

        }

    }
}
