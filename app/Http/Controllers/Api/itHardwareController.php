<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\LoginController;
use App\it_hardware;
use App\it_hardware_file;
use App\it_asset_hardware;
use File;
use Session;

class itHardwareController extends Controller
{    
    private $exceptionController;
    
    public function __construct(){
        $this->exceptionController = new exceptionController();
    }
    
    public function index(){ 
        
        $hardware = DB::table('it_hardware AS a')
            ->select(
                'a.it_hw_seq','a.it_hw_number','a.it_hw_name','a.it_hw_status',
                'a.it_hw_group','a.it_hw_serial','a.it_hw_type_id','b.it_hw_type_name',
                DB::raw('CASE WHEN a.it_hw_group = 1 THEN "ทรัพย์สินบุคคลถือครอง" ELSE "ทรัพย์สินส่วนกลางฝ่าย" END group_name'),
                DB::raw(
                    'CASE WHEN a.it_hw_status = 1 THEN "ใช้งาน"
                        WHEN a.it_hw_status = 2 THEN "สำรอง"
                        WHEN a.it_hw_status = 3 THEN "ส่งซ่อม"
                        WHEN a.it_hw_status = 4 THEN "ออกจำหน่าย"
                        WHEN a.it_hw_status = 5 THEN "ยืมใช้งาน"
                        ELSE "ไม่ใช้งาน" 
                    END status_name'
                )
            )
            ->join('it_hardware_type AS b','a.it_hw_type_id','b.it_hw_type_id')
            ->orderBy('a.it_hw_seq','DESC')
            ->paginate(20);
        
        return response()->json($hardware);

    }

    public function searchAssetByKeyword(Request $request){
        
        $hardware = DB::table('it_hardware AS a')
            ->select(
                'a.it_hw_seq','a.it_hw_number','a.it_hw_name','a.it_hw_status',
                'a.it_hw_group','a.it_hw_serial','a.it_hw_type_id','b.it_hw_type_name',
                DB::raw('CASE WHEN a.it_hw_group = 1 THEN "ทรัพย์สินบุคคลถือครอง" ELSE "ทรัพย์สินส่วนกลางฝ่าย" END group_name'),
                DB::raw(
                    'CASE WHEN a.it_hw_status = 1 THEN "ใช้งาน"
                        WHEN a.it_hw_status = 2 THEN "สำรอง"
                        WHEN a.it_hw_status = 3 THEN "ส่งซ่อม"
                        WHEN a.it_hw_status = 4 THEN "ออกจำหน่าย"
                        WHEN a.it_hw_status = 5 THEN "ยืมใช้งาน"
                        ELSE "ไม่ใช้งาน" 
                    END status_name'
                )
            )
            ->join('it_hardware_type AS b','a.it_hw_type_id','b.it_hw_type_id')
            ->where('a.it_hw_name','LIKE','%'.$request->keyword.'%')
            ->orWhere('a.it_hw_number','LIKE','%'.$request->keyword.'%')
            ->orWhere('a.it_hw_serial','LIKE','%'.$request->keyword.'%')            
            ->orWhere('b.it_hw_type_name','LIKE','%'.$request->keyword.'%')
            ->orderBy('a.it_hw_seq','DESC')
            ->paginate(20);
        
        return response()->json($hardware);

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

    private function checkExistHardwareNumber($number){
        $count = it_hardware::where('it_hw_number',$number)->count();
        return $count > 0 ? true : false;
    }

    public function store(Request $request){

        if(LoginController::getLoginStatus()){
            if($this->checkExistHardwareNumber($request->get('asset_number'))){
                return response()->json(array(
                    'status' => false, 
                    'exception' => 'loginExpire',
                    'message' => ''));
            }
        }

        if($this->checkExistHardwareNumber($request->get('asset_number'))){
            return response()->json(array(
                'status' => false, 
                'exception' => 'codeExist',
                'message' => 'รหัสทรัพย์สินซ้ำ'));
        }

        try{

            $values = [
                'it_hw_number' => $request->get('asset_number'),
                'it_hw_name' => $request->get('asset_name'),
                'it_hw_type_id' => $request->get('asset_type'),
                'it_hw_serial' => $request->get('asset_serial'),
                'it_hw_status' => $request->get('asset_status'),
                'it_hw_group' => $request->get('asset_group'),
                'it_hw_remark' => $request->get('asset_remark'),
                'it_hw_price' => $request->get('asset_price')
            ];

            if(!empty($request->get('asset_expire')))
                $values['it_hw_expire'] = $request->get('asset_expire');

            if(!empty($request->get('asset_warrantry')))
                $values['it_hw_warrantry'] = $request->get('asset_warrantry');

            $asset = new it_hardware($values);

            $asset->save();

            $handle = new it_asset_hardware([
                'it_hw_seq' => $asset->id,
                'it_emp_id' => 'CMIS',
                'it_locat_id' => 'HO_B2_F2',
                'it_asst_hw_start_date' => date('Y-m-d'),
                'it_asst_hw_remark' => 'นำทรัพย์สินเข้าครั้งแรก',
                'it_asst_hw_status' => 1
            ]);

            $handle->save();

            return response()->json(array(
                'status' => true, 
                'exception' => '',
                'message' => 'ระบบบันทึกข้อมูลเรียบร้อยแล้ว'
            ));

        }
        catch(QueryException $e){

            $errormsg = $this->saveLogError(array(
                'module' => 'asset_by_admin',
                'action' => 'insert_db',
                'message' => $e->getMessage()
            ));

            return response()->json(array(
                'status' => false, 
                'exception' => 'sqlError',
                'message' => $errormsg
            ));

        }

    }

    public function show($id){

        $hardware = DB::table('it_hardware AS a')
            ->select(
                'a.it_hw_seq','a.it_hw_number','a.it_hw_name','a.it_hw_status',
                'a.it_hw_group','a.it_hw_serial','a.it_hw_type_id','b.it_hw_type_name',
                'a.it_hw_remark','a.it_hw_price',
                DB::raw('IFNULL(a.it_hw_expire,\'\') as expired'), 
                DB::raw('IFNULL(a.it_hw_warrantry,\'0 Y\') as warrantry'),
                DB::raw('CASE WHEN a.it_hw_group = 1 THEN "ทรัพย์สินบุคคลถือครอง" ELSE "ทรัพย์สินส่วนกลางฝ่าย" END group_name'),
                DB::raw(
                    'CASE WHEN a.it_hw_status = 1 THEN "ใช้งาน"
                        WHEN a.it_hw_status = 2 THEN "สำรอง"
                        WHEN a.it_hw_status = 3 THEN "ส่งซ่อม"
                        WHEN a.it_hw_status = 4 THEN "ออกจำหน่าย"
                        WHEN a.it_hw_status = 5 THEN "ยืมใช้งาน"
                        ELSE "ไม่ใช้งาน" 
                    END status_name'
                ),
                DB::raw(
                    'CASE WHEN a.it_hw_sdate IS NULL OR a.it_hw_sdate = \'0000-00-00\'
                        THEN \'\' ELSE a.it_hw_sdate END start_date'
                ),
                DB::raw(
                    'CASE WHEN a.it_hw_edate IS NULL OR a.it_hw_edate = \'0000-00-00\'
                        THEN \'\' ELSE a.it_hw_edate END end_date'
                )
            )
            ->join('it_hardware_type AS b','a.it_hw_type_id','b.it_hw_type_id')
            ->where('a.it_hw_seq',$id)
            ->get();
        
        $result = $hardware[0];
        return response()->json($result);

    }

    public function update(Request $request, $id){

        if(LoginController::getLoginStatus()){
            if($this->checkExistHardwareNumber($request->get('asset_number'))){
                return response()->json(array(
                    'status' => false, 
                    'exception' => 'loginExpire',
                    'message' => ''));
            }
        }

        try{

            $data = array(
                'it_hw_number' => $request->get('asset_number'),
                'it_hw_name' => $request->get('asset_name'),
                'it_hw_type_id' => $request->get('asset_type'),
                'it_hw_serial' => $request->get('asset_serial'),
                'it_hw_status' => $request->get('asset_status'),
                'it_hw_group' => $request->get('asset_group'),
                'it_hw_remark' => $request->get('asset_remark'),
                'it_hw_price' => $request->get('asset_price'),
                'it_hw_expire' => $request->get('asset_expired'),
                'it_hw_warrantry' => $request->get('asset_warrantry')
            );

            if(!empty($request->get('asset_startdate')))
                $data['it_hw_sdate'] = $request->get('asset_startdate');
            
            if(!empty($request->get('asset_enddate')))
                $data['it_hw_edate'] = $request->get('asset_enddate');
         
            $result = it_hardware::where('it_hw_seq',$id)
                ->update($data);
            
            return response()->json(array(
                'status' => true, 
                'exception' => '',
                'message' => ''
            ));

        }
        catch(QueryException $e){

            $errormsg = $this->saveLogError(array(
                'module' => 'asset_by_admin',
                'action' => 'update_db',
                'message' => $e->getMessage()
            ));

            return response()->json(array(
                'status' => false, 
                'exception' => 'sqlError',
                'message' => $errormsg
            ));

        }

    }

    public function uploadAssetFile(Request $request){

        $tmp_filetype = explode('.',$request->file_name);
        $file_type = end($tmp_filetype);
        $file_newname = 'asset_'.$request->asset_id.'_'.date('YmdHi').'.'.$file_type;
        
        if( $request->file->move(public_path('upload/asset'), $file_newname) ){

            $upload = new it_hardware_file([
                    'it_hw_seq' => $request->asset_id,
                    'it_hw_file_name' => $file_newname,
                    'it_hw_file_path' => '/upload/asset/'.$file_newname
                ]);
            
            $result = $upload->save();
            return response()->json($result);

        }
        else{
            $result = false;
            return response()->json($result);
        }

    }

    public function searchAssetFile($id){

        $files = it_hardware_file::where('it_hw_seq',$id)
            ->orderBy('it_hw_file_id','DESC')
            ->get();
        
        return response()->json($files);

    }

    public function searchAssetHandel($id){

        $handle = DB::table('it_asset_hardware AS a')
            ->select(
                'a.it_emp_id','b.it_emp_name_th','b.it_emp_surname_th','d.it_dept_name',
                'a.it_locat_id','c.it_locat_name','a.it_asst_hw_remark','a.it_asst_hw_status',
                'a.it_asst_hw_start_date','a.it_asst_hw_end_date',
                'a.it_asst_hw_seq','a.it_hw_status'
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->leftJoin('it_location AS c','a.it_locat_id','c.it_locat_id')
            ->join('it_department AS d','b.it_dept_id','d.it_dept_id')
            ->where('a.it_hw_seq',$id)
            ->orderBy('a.it_asst_hw_seq','DESC')
            ->get();
        
        return response()->json($handle);

    }

    public function deleteAssetFile(Request $request){
        
        if( File::exists(public_path($request->file_path)) ){
            if( File::delete(public_path($request->file_path)) ){
                $remove = it_hardware_file::where('it_hw_file_id',$request->file_id)->delete();
                $result = true;
            }
            else
                $result = false;            
        }
        else 
            $result = false;
        
        return response()->json([ 'Status' => $result ]);

    }

    public function searchAssetPaper(Request $request){
        
        if( empty($request->type) ){

            $asset1 = DB::table('it_asset_hardware AS iah')
                        ->select(
                            'iah.it_asst_hw_seq AS handle_id','iah.it_assthd_no AS paper_no',
                            'ih.it_hw_seq AS asset_id','ih.it_hw_number AS asset_no',
                            'ih.it_hw_name AS asset_name','iah.it_asst_hw_status AS asset_sts',
                            'iah.it_emp_id AS emp_id',
                            DB::raw('1 AS paper_ty'),
                            DB::raw('\'ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน\' AS paper_type'),
                            DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name'),
                            DB::raw('CASE iah.it_asst_hw_status WHEN 1 THEN \'กำลังถือคลอง\' ELSE \'ไม่ได้ถือคลอง\' END AS status_str'),
                            DB::raw('CASE ih.it_hw_group WHEN 1 THEN \'ทรัพย์สินบุคคลถือคลอง\' ELSE \'ทรัพย์สินส่วนกลางฝ่าย\' END AS group_str')
                        )
                        ->join('it_hardware AS ih','iah.it_hw_seq','ih.it_hw_seq')
                        ->join('it_employee AS ie','iah.it_emp_id','ie.it_emp_id')
                        ->where('iah.it_assthd_no','LIKE','%'.$request->text.'%')
                        ->orWhere('ih.it_hw_number','LIKE','%'.$request->text.'%')
                        ->whereNotNull('iah.it_assthd_no');
            
            $asset2 = DB::table('it_asset_hardware AS iah')
                        ->select(
                            'iah.it_asst_hw_seq AS handle_id','iah.it_asstbr_no AS paper_no',
                            'ih.it_hw_seq AS asset_id','ih.it_hw_number AS asset_no',
                            'ih.it_hw_name AS asset_name','iah.it_asst_hw_status AS asset_sts',
                            'iah.it_emp_id AS emp_id',
                            DB::raw('2 AS paper_ty'),
                            DB::raw('\'ใบยืม-คืนอุปกรณ์คอมพิวเตอร\' AS paper_type'),
                            DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name'),
                            DB::raw('CASE iah.it_asst_hw_status WHEN 1 THEN \'กำลังถือคลอง\' ELSE \'ไม่ได้ถือคลอง\' END AS status_str'),
                            DB::raw('CASE ih.it_hw_group WHEN 1 THEN \'ทรัพย์สินบุคคลถือคลอง\' ELSE \'ทรัพย์สินส่วนกลางฝ่าย\' END AS group_str')
                        )
                        ->join('it_hardware AS ih','iah.it_hw_seq','ih.it_hw_seq')
                        ->join('it_employee AS ie','iah.it_emp_id','ie.it_emp_id')
                        ->where('iah.it_asstbr_no','LIKE','%'.$request->text.'%')
                        ->orWhere('ih.it_hw_number','LIKE','%'.$request->text.'%')
                        ->whereNotNull('iah.it_asstbr_no')
                        ->union($asset1)
                        ->get();
            
            return response()->json($asset2);

        }
        elseif( $request->type == 1 ){

            $assthd = DB::table('it_asset_hardware AS iah')
                        ->select(
                            'iah.it_asst_hw_seq AS handle_id','iah.it_assthd_no AS paper_no',
                            'ih.it_hw_seq AS asset_id','ih.it_hw_number AS asset_no',
                            'ih.it_hw_name AS asset_name','iah.it_asst_hw_status AS asset_sts',
                            'iah.it_emp_id AS emp_id',
                            DB::raw('1 AS paper_ty'),
                            DB::raw('\'ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน\' AS paper_type'),
                            DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name'),
                            DB::raw('CASE iah.it_asst_hw_status WHEN 1 THEN \'กำลังถือคลอง\' ELSE \'ไม่ได้ถือคลอง\' END AS status_str'),
                            DB::raw('CASE ih.it_hw_group WHEN 1 THEN \'ทรัพย์สินบุคคลถือคลอง\' ELSE \'ทรัพย์สินส่วนกลางฝ่าย\' END AS group_str')
                        )
                        ->join('it_hardware AS ih','iah.it_hw_seq','ih.it_hw_seq')
                        ->join('it_employee AS ie','iah.it_emp_id','ie.it_emp_id')
                        ->where('iah.it_assthd_no','LIKE','%'.$request->text.'%')
                        ->orWhere('ih.it_hw_number','LIKE','%'.$request->text.'%')
                        ->whereNotNull('iah.it_assthd_no')
                        ->get();
            
            return response()->json($assthd);

        }
        elseif( $request->type == 2 ){

            $asstbr = DB::table('it_asset_hardware AS iah')
                        ->select(
                            'iah.it_asst_hw_seq AS handle_id','iah.it_asstbr_no AS paper_no',
                            'ih.it_hw_seq AS asset_id','ih.it_hw_number AS asset_no',
                            'ih.it_hw_name AS asset_name','iah.it_asst_hw_status AS asset_sts',
                            'iah.it_emp_id AS emp_id',
                            DB::raw('2 AS paper_ty'),
                            DB::raw('\'ใบยืม-คืนอุปกรณ์คอมพิวเตอร\' AS paper_type'),
                            DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name'),
                            DB::raw('CASE iah.it_asst_hw_status WHEN 1 THEN \'กำลังถือคลอง\' ELSE \'ไม่ได้ถือคลอง\' END AS status_str'),
                            DB::raw('CASE ih.it_hw_group WHEN 1 THEN \'ทรัพย์สินบุคคลถือคลอง\' ELSE \'ทรัพย์สินส่วนกลางฝ่าย\' END AS group_str')
                        )
                        ->join('it_hardware AS ih','iah.it_hw_seq','ih.it_hw_seq')
                        ->join('it_employee AS ie','iah.it_emp_id','ie.it_emp_id')
                        ->where('iah.it_asstbr_no','LIKE','%'.$request->text.'%')
                        ->orWhere('ih.it_hw_number','LIKE','%'.$request->text.'%')
                        ->whereNotNull('iah.it_asstbr_no')
                        ->get();
            
            return response()->json($asstbr);

        }
        
    }

}
