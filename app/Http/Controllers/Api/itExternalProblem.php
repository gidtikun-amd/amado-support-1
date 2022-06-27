<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\it_external_problem;
use App\it_systemsts;

class itExternalProblem extends Controller
{
    public function getListExternalProblem(Request $request){

        $admin = DB::table('user as u')
            ->select(
                'u.user','ie.it_emp_id',
                DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as emp_fullname')
            )
            ->join('it_employee as ie','u.it_emp_id','ie.it_emp_id')
            ->where('u.identity','admin');

        $SubQueryAdmin = $admin->toSql();
        $bindings = $admin->getBindings();

        foreach($bindings as $binding)
            $SubQueryAdmin = preg_replace('/\?/', "'" . $binding ."'", $SubQueryAdmin, 1);

        $list = DB::table('it_external_problem as iep')
            ->select(
                'iep.ext_id as id', 'iep.ext_no as no', 'iep.ext_subject as subject',
                'iep.ext_title as title', 'iep.ext_detail as detail', 'iep.ext_user as user',
                'iep.ext_date as date', 'emp.emp_fullname'
            )
            ->join(DB::raw('('.$SubQueryAdmin.') as emp'),'iep.ext_user','emp.user')
            ->orderBy('iep.created_at','DESC')
            ->paginate(25);

        return response()->json($list);

    }

    public function saveExternalProblem(Request $request){

        $save = new it_external_problem([
            'ext_date' => date('Y-m-d'),
            'ext_subject' => $request->subject,
            'ext_title' => $request->title,
            'ext_detail' => $request->detail,
            'ext_user' => $request->userId
        ]);

        if($save->save()){

            $id = $save->id;
            $no = 'IT-TIK-EXT-'.date('ym').str_pad($id,7,'0',STR_PAD_LEFT);

            $update = it_external_problem::where('ext_id',$id)
                ->update(['ext_no' => $no]);

            return response()->json(array('no' => $no));

        }
        else 
            return response()->json(false);

    }

    public function updateExternalProblem(Request $request){

        $update = it_external_problem::where('ext_id',$request->id)
            ->update([
                'ext_subject' => $request->subject,
                'ext_title' => $request->title,
                'ext_detail' => $request->detail,
                'ext_user' => $request->userId
            ]);
        
        return response()->json($update);

    }

    public function removeExternalProblem($id){
        $delete = it_external_problem::where('ext_id',$id)->delete();
        return response()->json($delete);
    }

    public function searchSystemStatus(Request $request){

        $sysDate = date('Y-m-01',strtotime($request->date));

        $detail = DB::table('it_systemsts')
            ->select(
                'systs_id as id', 'systs_date as date', 'systs_title as title',
                'systs_status as status', 'systs_remark as remark', 'systs_user as user'
            )
            ->where('systs_date',$sysDate)
            ->get();

        return response()->json($detail);

    }

    private function checkExistSystemStatusRecord($date){
        $count = DB::table('it_systemsts')->where('systs_date',$date)->count();
        return ( $count > 0 ? true : false );
    }

    public function saveSystemStatus(Request $request){

        $sysDate = date('Y-m-01',strtotime($request->date));

        if(!$this->checkExistSystemStatusRecord($sysDate)){

            $values = [];
            $stampDate = date('Y-m-d H:i:s');

            foreach($request->system as $val){
                array_push($values,[
                    'systs_date' => $sysDate,
                    'systs_title' => $val['title'],
                    'systs_status' => $val['success'],
                    'systs_remark' => preg_replace('/[\x{200B}-\x{200D}]/u','',$val['remark']['detail']),
                    'systs_user' => $request->user,
                    'created_at' => $stampDate,
                    'updated_at' => $stampDate
                ]);
            }

            $insert = it_systemsts::insert($values);

            if($insert) $result = array('status' => true, 'message' => 'บันทึกสถานะระบบได้เรียบร้อยแล้ว');
            else $result = array('status' => false, 'message' => 'ไม่สามารถบันทึกสถานะระบบได้');

            return response()->json($result);

        }
        else{
            $result = array('status' => false, 'message' => 'มีรายการบันทึกอยู่แล้ว');
            return response()->json($result);
        }

    }

    public function updateSystemStatus(Request $request){

        foreach($request->detail as $val){
            $update = it_systemsts::where('systs_id',$val['id'])
                ->update([
                    'systs_title' => $val['title'],
                    'systs_status' => $val['success'],
                    'systs_remark' => preg_replace('/[\x{200B}-\x{200D}]/u','',$val['remark']['detail']),
                    'systs_user' => $request->user,
                ]);
        }

        return response()->json(true);

    }
}
