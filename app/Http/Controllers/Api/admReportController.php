<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\SummaryBudgetEqreqExport;
use App\Exports\SummaryEqreqExport;
use App\Exports\EmployeesListExport;
use DB;

class admReportController extends Controller
{
    public function export_employees_list(){
        return(new EmployeesListExport())
            ->download('รายชื่อพนักงาน.xlsx');
    }

    public function export_summary_report_budget_eqreq(Request $request){
        return (new SummaryBudgetEqreqExport($request))
            ->download('รายงานสรุป Budget การเบิกอุปกรณ์สำนักงาน '.$request->date.'.xlsx');
    }

    public function export_summary_report_eqreq(Request $request){
        return (new SummaryEqreqExport($request))
            ->download('รายงานสรุปการเบิกอุปกรณ์สำนักงาน '.$request->date_from.' ถึง '.$request->date_to.'.xlsx');
    }

    public function summary_report_budget_eqreq_array(Request $request){
        return (object) $this->summary_report_budget_eqreq($request);
    }

    public function summary_report_budget_eqreq_json(Request $request){
        return response()->json($this->summary_report_budget_eqreq($request));
    }

    private function summary_report_budget_eqreq($request){

        $date_from = date('Y-m-01',strtotime($request->date));
        $date_to = date('Y-m-t',strtotime($request->date));

        $eqreq = DB::table('ad_equipreq')
            ->select('eqreq_id', 'it_dept_id', 'eqreq_netbudget')
            ->where([
                ['eqreq_approve_status', '=', '1'],
                ['eqreq_status', '<>', '0']
            ])
            ->whereBetween('eqreq_date',[$date_from,$date_to]);

        $SubQueryInEqreq = $eqreq->toSql();
        $bindings = $eqreq->getBindings();

        foreach($bindings as $binding)
            $SubQueryInEqreq = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInEqreq, 1);

        $report = DB::table('it_department as id')
            ->select(
                'id.it_dept_id as id', 'id.it_dept_name as name',
                DB::raw('ifnull(sum(ae.eqreq_netbudget),0.00) as budget')
            )
            ->leftJoin(DB::raw('('.$SubQueryInEqreq.') as ae'),'id.it_dept_id','ae.it_dept_id')
            ->groupBy('id.it_dept_id', 'id.it_dept_name')
            ->orderBy('name','ASC')
            ->get();

        return $report;

    }

    public function summary_report_budget_eqreq_dept_array(Request $request){
        return (object) $this->summary_report_budget_eqreq_dept($request);
    }

    public function summary_report_budget_eqreq_dept_json(Request $request){
        return response()->json($this->summary_report_budget_eqreq_dept($request));
    }

    private function summary_report_budget_eqreq_dept($request){

        $date_from = date('Y-m-01',strtotime($request->date));
        $date_to = date('Y-m-t',strtotime($request->date));

        $report = DB::table('ad_equipreq as ae')
            ->select(
                'aed.item_id as id', 'aed.item_budget as budget',
                DB::raw('CONCAT(ai.item_code,\' \',ai.item_name) as item'),
                DB::raw('sum(aed.eqreq_qty) as qty'),
                DB::raw('sum(aed.item_ttbudget) as ttbudget'),
                DB::raw('sum(aed.eqreq_sendto) as sendto'),
                DB::raw('sum(aed.eqreq_outstand) as outstand')
            )
            ->join('ad_equipreq_detail as aed','ae.eqreq_id','aed.eqreq_id')
            ->join('ad_items as ai','aed.item_id','ai.item_id')
            ->where('ae.it_dept_id',$request->deptId)
            ->whereIn('ae.eqreq_status',[3,4])
            ->whereBetween('ae.eqreq_date',[$date_from,$date_to])
            ->groupBy('aed.item_id', 'ai.item_code', 'ai.item_name', 'aed.item_budget')
            ->orderBy('item','ASC')
            ->orderBy('budget','ASC')
            ->get();

        return $report;

    }

    public function summary_report_eqreq_array(Request $request){
        return $this->summary_report_eqreq($request);
    }

    public function summary_report_eqreq_json(Request $request){
        return response()->json($this->summary_report_eqreq($request));
    }

    private function summary_report_eqreq($request){

        $eqreq = DB::table('ad_equipreq')
            ->select('eqreq_id')
            ->whereIN('eqreq_status',[2,3,4])
            ->whereBetween('eqreq_date',[$request->date_from,$request->date_to]);

        if(!empty($request->deptId))
            $eqreq = $eqreq->where('it_dept_id',$request->deptId);

        $SubQueryInEqreq = $eqreq->toSql();
        $bindings = $eqreq->getBindings();

        foreach($bindings as $binding)
            $SubQueryInEqreq = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInEqreq, 1);

        $report = DB::table('ad_equipreq_detail as aed')
            ->select(
                'aed.item_id as id', 'ai.item_code as code', 'ai.item_name as name',
                'ai.item_unit as unit',
                DB::raw('sum(aed.eqreq_qty) as qty'),
                DB::raw('sum(aed.eqreq_sendto) as sendto'),
                DB::raw('sum(aed.eqreq_outstand) as outstand')
            )
            ->join(DB::raw('('.$SubQueryInEqreq.') as ae'),'aed.eqreq_id','ae.eqreq_id')
            ->join('ad_items as ai','aed.item_id','ai.item_id')
            ->groupBy('aed.item_id','ai.item_code','ai.item_name','ai.item_unit')
            ->orderBy('ai.item_code')
            ->get();

        return $report;

    }
}
