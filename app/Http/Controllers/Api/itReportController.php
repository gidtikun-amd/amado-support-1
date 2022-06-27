<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\ReportAssethandleExport;
use App\Exports\PaperJobreqestNonup;
use App\Exports\PaperItemrequestNonup;
use App\Exports\PaperMemoNonup;
use App\Exports\ReportLicenseHandle;
use App\Http\Controllers\Api\CalenderController;
use Excel;
use DB;

class itReportController extends Controller
{

    public function exportReportAssetHandle(Request $request){
        return (new ReportAssethandleExport($request))
        ->download('Hardware_Asset_Information.xlsx');
    }

    public function exportReportLicenseHandle(Request $request){
        return (new ReportLicenseHandle($request))
        ->download('รายการถือครอง License software.xlsx');
    }

    public function exportListNonupPaper(Request $request){
        if($request->TypePaper == 1){
            return (new PaperJobreqestNonup($request))
            ->download('job_request_dont_have_files.xlsx');
        }
        elseif($request->TypePaper == 2){
            return (new PaperItemrequestNonup($request))
            ->download('item_request_dont_have_files.xlsx');
        }
        elseif($request->TypePaper == 3){
            return (new PaperMemoNonup($request))
            ->download('memo_dont_have_files.xlsx');
        }
    }

    public function setProblemTicket($month){

        $date_from = date('Y-m-01',strtotime($month));
        $date_to = date('Y-m-t',strtotime($month));
        $datet_from = date('Y-m-01 00:00:00',strtotime($month));
        $datet_to = date('Y-m-t 23:59:59',strtotime($month));

        $detail_tk = DB::table('it_ticket_subject AS its')
                    ->select(

                        'its.it_ticket_subject_name AS subject_name',
                        DB::raw('COUNT(it.it_ticket_id) AS ccase')
                    )
                    ->join('it_ticket AS it','its.it_ticket_subject_id','it.it_ticket_subject_id')
                    ->whereBetween('it.it_ticket_date',[$datet_from,$datet_to])
                    ->where('it.it_ticket_status',4)
                    ->groupBy('it.it_ticket_subject_id')
                    ->groupBY('subject_name')
                    ->orderBy('it.it_ticket_subject_id')
                    ->get();

        $subject_os = array(
            0 => array(
                'subject' => 'Innoviz Solutions Co., Ltd.',
                'detail' => array()
            ),
            1 => array(
                'subject' => 'บริษัท โกบอล อินโนเวทีฟ โซลูชั่นส์ จำกัด',
                'detail' => array()
            )
        );

        $index_os = 0;

        foreach($subject_os as $subject){

            $detail_os = DB::table('it_external_problem')        
                ->select('ext_title as title')
                ->where('ext_subject',$subject['subject'])
                ->whereBetween('ext_date',[$date_from,$date_to])
                ->get();
            
            $subject_os[$index_os++]['detail'] = $detail_os;

        }
        
        $systs = DB::table('it_systemsts')
            ->select(
                'systs_title as title', 'systs_remark as remark',
                DB::RAW('CASE systs_status WHEN  1 THEN \'สถานะปกติ\' ELSE \'สถานะผิดปกติ\' END as status')
            )
            ->whereBetween('systs_date',[$date_from,$date_to])
            ->get();

        $result = array(
            'ticket' => $detail_tk,
            'external' => $subject_os
        );

        if(!empty($systs)) $result['system'] = $systs;
        else {
            $result['system'] = array(
                'title' => 'การสำรองข้อมูล', 'remark' => '', 'status' => 'ยังไม่ได้บันทึก',
                'title' => 'เครื่องคอมพิวเตอร์แม่เครือข่ายและอุปกรณ์เครือข่าย', 'remark' => '', 'status' => 'ยังไม่ได้บันทึก',
                'title' => 'ระบบเครือข่ายภายในสำนักงาน', 'remark' => '', 'status' => 'ยังไม่ได้บันทึก'
            );
        }
        
        return (object) $result;

    }

    public function setTransferAsset($month){

        $date_from = date('Y-m-01 00:00:00',strtotime($month));
        $date_to = date('Y-m-t 23:59:59',strtotime($month));

        $detail = DB::table('it_asset_hardware')
                    ->select(
                        'it_hw_status AS hw_status',
                        DB::raw('COUNT(it_asst_hw_seq) AS cseq'),
                        DB::raw('CASE it_hw_status
                                WHEN 1 THEN \'ใช้งาน\'
                                WHEN 2 THEN \'สำรอง\'
                                WHEN 3 THEN \'ส่งซ่อม\'
                                WHEN 4 THEN \'ออกจำหน่าย\'
                                WHEN 5 THEN \'ยืมใช้งาน\'
                                WHEN 6 THEN \'ไม่ใช้งาน\'
                            END AS hw_ststxt')
                    )
                    ->whereBetween('created_at',[$date_from,$date_to])
                    ->groupBy('it_hw_status')
                    ->orderBy('it_hw_status')
                    ->get();

        return $detail;

    }

    public function getSummarySupportJson(Request $request){
        return response()->json($this->setSummarySupport($request));
    }

    private function setSummarySupport($request){

        $date_from = date('Y-m-d 00:00:00',strtotime($request->sdate));
        $date_to = date('Y-m-d 23:59:59',strtotime($request->edate));

        $admin = DB::table('it_ticket as it')
                    ->select(
                        'it.it_ticket_operator as assign_id',
                        DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as assign_name')
                    )
                    ->join('user as u','it.it_ticket_operator','u.user')
                    ->join('it_employee as ie','u.it_emp_id','ie.it_emp_id')
                    ->whereBetween('it.it_ticket_date',[$date_from,$date_to])
                    ->distinct()
                    ->get();

        $result = array();

        foreach($admin as $vlad){

            $sub_in_survey = DB::table('it_ticket AS inna')
                            ->select('inna.it_ticket_id')
                            ->where([
                                ['inna.it_ticket_operator',$vlad->assign_id],
                                ['inna.it_ticket_status',4],
                                ['inna.survey_status',1]
                            ])
                            ->whereBetween('inna.it_ticket_date',[$date_from,$date_to]);

            $SubQueryInSurvey = $sub_in_survey->toSql();
            $bindings = $sub_in_survey->getBindings();

            foreach($bindings as $binding)
                $SubQueryInSurvey = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInSurvey, 1);

            $sub_survey = DB::table('it_survey_header AS ish')
                            ->select(
                                'ish.it_survey_header_id AS survey_hid',
                                'ish.it_survey_id AS survey_id', 'ish.it_survey_ref_id AS ticket_id',
                                'ish.it_survey_level_overall AS soverall',
                                DB::raw('SUM(isd.it_survey_subject_level) AS sscore')
                            )
                            ->join('it_survey_detail AS isd','ish.it_survey_header_id','isd.it_survey_header_id')
                            ->whereRaw('ish.it_survey_ref_id IN ('.$SubQueryInSurvey.')')
                            ->groupBy('ish.it_survey_header_id','ish.it_survey_id','ish.it_survey_ref_id','ish.it_survey_level_overall');

            $SubQuerySurvey = $sub_survey->toSql();
            $bindings = $sub_survey->getBindings();

            foreach($bindings as $binding)
                $SubQuerySurvey = preg_replace('/\?/', "'" . $binding ."'", $SubQuerySurvey, 1);

            $sub_ticket = DB::table('it_ticket AS it')
                ->select(
                    'it.it_ticket_operator AS emp_id',
                    'it.it_ticket_id AS ticket_id','it.it_survey_id AS survey_id')
                ->where([
                    ['it.it_ticket_status',4],
                    ['it.survey_status',1]
                ])
                ->whereBetween('it.it_ticket_date',[$date_from,$date_to]);

            $SubQueryTicket = $sub_ticket->toSql();
            $bindings = $sub_ticket->getBindings();

            foreach($bindings as $binding)
                $SubQueryTicket = preg_replace('/\?/', "'" . $binding ."'", $SubQueryTicket, 1);

            $scorse = DB::table(DB::raw('('.$SubQueryTicket.') AS sub1'))
                ->select(
                    DB::raw('IFNULL(COUNT(sub1.ticket_id),0) AS cticket'),
                    DB::raw('IFNULL(SUM(sub2.soverall),0) AS sova'),
                    DB::raw('IFNULL(SUM(sub2.sscore),0) AS sscore')
                )
                ->leftJoin(DB::raw('('.$SubQuerySurvey.') AS sub2'),'sub1.ticket_id','sub2.ticket_id')
                ->groupBy('sub1.emp_id')
                ->first();

            $tikcet_success = DB::table('it_ticket')
                ->select(DB::raw('COUNT(it_ticket_id) AS cticket'))
                ->where('it_ticket_operator',$vlad->assign_id)
                ->whereIn('it_ticket_status',[4])
                ->whereBetween('it_ticket_date',[$date_from,$date_to])
                ->first();

            $ticket_unsuccess = DB::table('it_ticket')
                ->select(DB::raw('COUNT(it_ticket_id) AS cticket'))
                ->where('it_ticket_operator',$vlad->assign_id)
                ->whereNotIn('it_ticket_status',[4,0])
                ->whereBetween('it_ticket_date',[$date_from,$date_to])
                ->first();

            array_push($result,[
                'emp_id' => $vlad->assign_id,
                'emp_name' => $vlad->assign_name,
                'cticket' => $scorse->cticket,
                'sova' => $scorse->sova,
                'sscore' => $scorse->sscore,
                'unsuccess' => $ticket_unsuccess->cticket,
                'success' => $tikcet_success->cticket
            ]);

        }

        return $result;

    }

}
