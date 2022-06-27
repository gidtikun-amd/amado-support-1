<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\notifyLineController;
use App\ad_items;
use App\ad_equipreq;
use App\ad_equipreq_detail;
use App\ad_equipreq_runningno;
use App\ad_equipreq_file;
use DB;
use Session;
use File;

class administrativeController extends Controller
{
    private $notify_line;

    public function __construct(){
        $this->notify_line = new notifyLineController();
    }

    public function getAllItems(){
        $items  = ad_items::all();
        return response()->json($items);
    }

    public function getActiveItems(){

        $items = DB::table('ad_items')
                    ->select(
                        'item_id as id','item_code as code', 'item_name as item',
                        'item_unit as unit', 'item_budget as budget'
                    )
                    ->where('item_active',1);

        if(session()->get('departmentId') != 2 && session()->get('departmentId') != 5)
            $items = $items->where('item_admin',0);

        $items = $items->orderby('item_code','ASC')
                    ->get();

        return response()->json($items);

    }

    public function saveItems(Request $request){

        $save = new ad_items([
            'item_code' => $request->code,
            'item_name' => $request->name,
            'item_unit' => $request->unit,
            'item_budget' => $request->budget,
            'item_active' => $request->active
        ]);

        $result = $save->save();
        return response()->json($result);

    }

    public function updateItems(Request $request){

        $result = ad_items::where('item_id',$request->id)
                    ->update([
                        'item_code' => $request->code,
                        'item_name' => $request->name,
                        'item_unit' => $request->unit,
                        'item_budget' => $request->budget,
                        'item_active' => $request->active
                    ]);

        return response()->json($result);

    }

    public function deleteItems($id){

        $delete = ad_items::where('item_id',$id)->delete();

        $result = array(
            'status' => $delete,
            'message' => ''
        );

        return response()->json($result);

    }

    public function getListEquipReq(Request $request){

        $list = DB::table('ad_equipreq as ae')
                ->select(
                    'ae.eqreq_id as id', 'ae.eqreq_no as no', 'ae.eqreq_date as date',
                    'ae.eqreq_status as status', 'id.it_dept_name as deptname',
                    'ae.eqreq_approve_status as appsts',
                    DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) fullnameTh'),
                    DB::raw('case
                        when ae.eqreq_approve_status = 0 and ae.eqreq_status = 1 then \'รออนุมัติ\'
                        when ae.eqreq_approve_status = 1 and ae.eqreq_status = 1 then \'รอดำเนินการ\'
                        when ae.eqreq_approve_status = 1 and ae.eqreq_status = 2 then \'กำลังดำเนินการ\'
                        when ae.eqreq_approve_status = 1 and ae.eqreq_status = 3 then \'คงค้าง\'
                        when ae.eqreq_approve_status = 1 and ae.eqreq_status = 4 then \'ดำเนินการเรียบร้อย\'
                        else \'ยกเลิก\' end as strsts')
                )
                ->join('it_employee as ie','ae.it_emp_id','ie.it_emp_id')
                ->join('it_department as id','ae.it_dept_id','id.it_dept_id');

        if($request->page == 'user'){
            $list = $list->where('ae.it_dept_id',session()->get('departmentId'));
        }
        elseif($request->page == 'admin'){
            if($request->status == 'approve'){
                $list = $list->where([
                    ['ae.eqreq_approve_status',0],
                    ['ae.eqreq_status',1]
                ]);
            }
            elseif($request->status != 'all'){

                if($request->status == 'wait') $status = 1;
                elseif($request->status == 'inprogress') $status = 2;
                elseif($request->status == 'remain') $status = 3;
                elseif($request->status == 'success') $status = 4;
                else $status = 0;

                $list = $list->where('ae.eqreq_status',$status);

                if($status == 0)
                    $list = $list->orWhere('ae.eqreq_approve_status',2);
                else
                    $list = $list->where('ae.eqreq_approve_status',1);

            }
        }

        if(!empty($request->search))
            $list = $list->where('ae.eqreq_no','LIKE','%'.$request->search.'%');

        $list = $list->orderBy('ae.created_at','DESC')
                    ->paginate($request->paginate);

        return response()->json($list);

    }

    public function setJsonEquipReqDetail($id){
        $result = $this->getEquipReqDetail($id);
        return response()->json($result);
    }

    public function setObjectEquipReqDetail($id){
        $result = (object) $this->getEquipReqDetail($id);
        return $result;
    }

    private function getEquipReqDetail($id){

        $header = DB::table('ad_equipreq as ae')
                    ->select(
                        'ae.eqreq_id as id', 'ae.eqreq_no as no', 'ae.eqreq_date as date',
                        'ae.eqreq_status as status', 'ae.eqreq_approve_status as appsts',
                        'ae.eqreq_netbudget as netbudget', 'ae.eqreq_remark as remark',
                        DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as fullnameTh'),
                        DB::raw('DATE_FORMAT(ae.eqreq_date,\'%d/%m/%Y\') as dmyDate')
                    )
                    ->join('it_employee as ie','ae.it_emp_id','ie.it_emp_id')
                    ->where('ae.eqreq_id',$id)
                    ->first();

        $detail = DB::table('ad_equipreq_detail as aed')
                    ->select(
                        'aed.eqreq_id as id', 'aed.eqreq_line as line',
                        'aed.item_id as itId', 'ai.item_name as itName',
                        'aed.item_unit as itUnit', 'aed.eqreq_qty as qty',
                        'aed.item_budget as budget', 'aed.item_ttbudget as ttbudget',
                        'aed.eqreq_sendto as sendto', 'aed.eqreq_outstand as outstand',
                        'aed.eqreq_outstand as rcost'
                    )
                    ->join('ad_items as ai','aed.item_id','ai.item_id')
                    ->where('aed.eqreq_id',$id)
                    ->orderBy('aed.eqreq_line','ASC')
                    ->get();

        $files = ad_equipreq_file::where('eqreq_id',$id)
                    ->select(
                        'eqreq_file_id as fileId','eqreq_file_name as fileName',
                        'eqreq_file_path as filePath'
                    )
                    ->orderBy('created_at','DESC')
                    ->get();

        $result = array(
            'header' => $header,
            'detail' => $detail,
            'files' => $files
        );

        return $result;

    }

    public function saveEquipReq(Request $request){

        $date = [ 'year' => date('y'), 'month' => date('m') ];
        $run_no = $this->getRunningNumber($date);
        $paper_no = 'AD-EQM-'.$date['year'].$date['month'].str_pad($run_no,7,'0',STR_PAD_LEFT);

        $header = new ad_equipreq([
            'eqreq_no' => $paper_no,
            'eqreq_date' => $request->date,
            'eqreq_netbudget' => $request->netbudget,
            'eqreq_status' => 1,
            'it_emp_id' => $request->user,
            'it_dept_id' => $request->dept
        ]);

        if($header->save()){

            $inst_id = $header->id;
            $values = array();
            $line = 1;

            foreach($request->detail as $val){
                array_push($values,array(
                    'eqreq_id' => $inst_id,
                    'eqreq_line' => $line++,
                    'item_id' => $val['id'],
                    'item_budget' => $val['budget'],
                    'item_ttbudget' => $val['ttbudget'],
                    'item_unit' => $val['unit'],
                    'eqreq_qty' => $val['qty'],
                    'eqreq_sendto' => 0,
                    'eqreq_outstand' => $val['qty'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ));
            }

            $detail = ad_equipreq_detail::insert($values);

            if($detail){

                $notify_msg = 'คุณ '.session()->get('name');
                $notify_msg .= ' แผนก '.session()->get('department');
                $notify_msg .= ' ขอเปิดใบเบิกอุปกรณ์สำนักงาน เอกสารเลขที่ '.$paper_no;

                $this->notify_line->call_notity_group_administrative('eqreq',array('message' => $notify_msg));

                return response()->json(array('paper_no' => $paper_no),200);

            }
            else {
                $delete = ad_equipreq::where('eqreq_id',$inst_id)->delete();
                return response()->json(false);
            }

        }
        else {
            return response()->json(false);
        }

    }

    private function getRunningNumber($date){

        $running_no = ad_equipreq_runningno::select('eqreq_rno_seq')
            ->where('eqreq_rno_year',$date['year'])
            ->where('eqreq_rno_month',$date['month']);

        $count_rows = $running_no;
        $checkEmpty = $count_rows = $count_rows->count();

        if( $checkEmpty > 0 ){

            $running_no  = $running_no->first();
            $number = $running_no['eqreq_rno_seq']+1;

            $running_upd = ad_equipreq_runningno::where('eqreq_rno_year',$date['year'])
                ->where('eqreq_rno_month',$date['month'])
                ->update(['eqreq_rno_seq' => $number]);

            return $number;

        }
        else{

            $running_inst = new ad_equipreq_runningno([
                'eqreq_rno_year' => $date['year'],
                'eqreq_rno_month' => $date['month'],
                'eqreq_rno_seq' => 1
            ]);

            $running_inst->save();
            return 1;

        }

    }

    public function updateStatusEquipReq(Request $request){

        $update = ad_equipreq::where('eqreq_id',$request->id)
                    ->update([
                       'eqreq_status' => $request->status,
                       'eqreq_tack' => $request->user
                    ]);

        return response()->json($update);

    }

    public function approveEquipReq(Request $request){

        $update = ad_equipreq::where('eqreq_id',$request->id)
                    ->update([
                        'eqreq_approve_status' => $request->status,
                        'eqreq_approve_user' => $request->uer,
                        'eqreq_approve_date' => date('Y-m-d H:i:s'),
                        'eqreq_tack' => $request->user,
                        'eqreq_status' => ($request->status == 1 ? 1 : 0)
                    ]);

        $notify_msg = 'ใบเบิกอุปกรณ์สำนักงาน เอกสารเลขที่ '.$request->no.' ได้รับการอนุมัติแล้ว ผู้ที่เกี่ยวข้องกรุณาดำเนินการ';
        $this->notify_line->call_notity_group_administrative('eqreq',array('message' => $notify_msg));

        return response()->json($update);

    }

    public function updateEquipReqDetail(Request $request){

        $upd_netbudget = ad_equipreq::where('eqreq_id',$request->id)
            ->update(['eqreq_netbudget' => $request->netbudget]);

        foreach($request->detail as $val){
            if($val['qty'] <= 0){
                $delete = ad_equipreq_detail::where([
                    ['eqreq_id',$request->id],
                    ['eqreq_line',$val['line']]
                ])->delete();
            }
            else{
                $update = ad_equipreq_detail::where([
                    ['eqreq_id',$request->id],
                    ['eqreq_line',$val['line']]
                ])
                ->update([
                    'eqreq_qty' => $val['qty'],
                    'eqreq_sendto' => $val['sendto'],
                    'eqreq_outstand' => $val['outstand'],
                    'item_ttbudget' => $val['ttbudget']
                ]);
            }
        }

        return response()->json(true);

    }

    public function getEquipReqFiles($id){

        $files = ad_equipreq_file::where('eqreq_id',$id)
            ->select(
                'eqreq_file_id as fileId','eqreq_file_name as fileName',
                'eqreq_file_path as filePath'
            )
            ->orderBy('created_at','DESC')
            ->get();

        return response()->json($files);

    }

    public function uploadEquipReqFiles(Request $request){

        $file_newname = 'adeqreq_'.$request->id.'_'.date('YmdHis').'.pdf';

        if($request->file->move(public_path('upload/equipreq'),$file_newname)){

            $upload = new ad_equipreq_file([
                'eqreq_id' => $request->id,
                'eqreq_file_name' => $file_newname,
                'eqreq_file_path' => '/upload/equipreq/'.$file_newname
            ]);

            $result = $upload->save();
            return response()->json($result);

        }
        else
            return response()->json(false);

    }

    public function removeEquipReqFiles(Request $request){

        if(File::exists(public_path($request->path))){
            if(File::delete(public_path($request->path))){

                $remove = ad_equipreq_file::where('eqreq_file_id',$request->id)
                            ->delete();

                return response()->json($remove);

            }
            else
                return response()->json(false);
        }
        else
            return response()->json(false);

    }

    public function saveRemarkEquipReq(Request $request){

        $save = ad_equipreq::where('eqreq_id',$request->id)
            ->update(['eqreq_remark' => $request->remark]);

        return response()->json(false);

    }
}
