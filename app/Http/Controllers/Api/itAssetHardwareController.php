<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\LoginController;
use App\it_hardware;
use App\it_asset_hardware;
use App\it_assetborrow_runningno;
use App\it_assethandle_runningno;
use App\it_assethandle_file;
use File;
use App\Http\Controllers\Api\CalenderController;

class itAssetHardwareController extends Controller
{
    private $exceptionController;

    public function __construct(){
        $this->exceptionController = new exceptionController();
    }
    
    public function index(){}

    public function show($id){}

    private function saveLogError($dataset){
        
        $errormsg = explode('(SQL:',$dataset['message']);

        $this->exceptionController->saveLogQueryException(array(
            'module' => $dataset['module'],
            'action' => $dataset['action'],
            'message' => $dataset['message']
        ));

        return $errormsg[0];

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

        try{
        
            $handle = new it_asset_hardware([
                'it_emp_id' => $request->emp_id,
                'it_hw_seq' => $request->asset_id,
                'it_hw_status' => $request->asset_status,
                'it_locat_id' => $request->locat_id,
                'it_asst_hw_start_date' => $request->handle_sdate,
                'it_asst_hw_remark' => $request->handle_remark,
                'it_asst_hw_status' => $request->handle_status
            ]);

            $handle->save();

            $update_hw = it_hardware::where('it_hw_seq',$request->asset_id)
                            ->update(['it_hw_status' => $request->asset_status]);

            $end_date = date('Y-m-d',strtotime($request->handle_sdate.' -1 days'));

            $update_handle = it_asset_hardware::where('it_asst_hw_seq',$request->last_id)
                ->update([
                    'it_asst_hw_status' => 2,
                    'it_asst_hw_end_date' => $end_date
                ]);

            return response()->json(array(
                'status' => true, 
                'exception' => '',
                'message' => ''
            ));

        }
        catch(QueryException $e){

            $errormsg = $this->saveLogError(array(
                'module' => 'assthandle_by_admin',
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
        
            $result = it_asset_hardware::where('it_asst_hw_seq',$id)
                ->update([
                    'it_locat_id' => $request->get('locat_id'),
                    'it_hw_status' => $request->get('asset_status'),
                    'it_asst_hw_status' => $request->get('handle_status'),
                    'it_asst_hw_start_date' => $request->get('start_date'),
                    'it_asst_hw_end_date' => $request->get('end_date'),
                    'it_asst_hw_remark' => $request->get('handle_remark')
                ]);

            return response()->json(array(
                'status' => true, 
                'exception' => '',
                'message' => ''
            ));
        
        }
        catch(QueryException $e){

            $errormsg = $this->saveLogError(array(
                'module' => 'assthandle_by_admin',
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

    public function destroy($id){

        $result = it_asset_hardware::where('it_asst_hw_seq',$id)->delete();
        
        if( $result ){

            $files = it_assethandle_file::select('assthd_id','assthd_path')
                ->where('it_asst_hw_seq',$id)
                ->get();
            
            foreach($files as $file){
                if( File::exists(public_path($file->assthd_path)) ){
                    if( File::delete(public_path($file->assthd_path)) ){
                        it_assethandle_file::where('assthd_id',$file->assthd_id)->delete();
                    }
                }
            }

        }
        
        return response()->json($result);

    }

    public function getAssetHandlePaper(Request $request){
        
        $paper_no = it_asset_hardware::select('it_assthd_no','it_asstbr_no')
                        ->where('it_asst_hw_seq',$request->assthd_id)
                        ->get();
        
        $paper = it_assethandle_file::where('it_asst_hw_seq',$request->assthd_id)->get();
        return response()->json(['paper_no' => $paper_no, 'paper' => $paper]);

    }

    public function getAssetHandleFile(Request $request){
        $file = it_assethandle_file::where('it_asst_hw_seq',$request->assthd_id)->get();
        return response()->json($file);
    }

    public function createAssethandleNumber(Request $request){

        $date = [ 'year' => date('y'), 'month' => date('m') ];

        $running_no = it_assethandle_runningno::select('assthd_rno_seq')
            ->where([
                    ['assthd_rno_year',$date['year']],
                    ['assthd_rno_month',$date['month']]
                ]);
        
        $count_rows = $running_no;
        $checkEmpty = $count_rows->count();

        if( $checkEmpty > 0 ){

            $running_no = $running_no->get();
            $number = $running_no[0]['assthd_rno_seq']+1;

            $running_upd = it_assethandle_runningno::where([
                    ['assthd_rno_year',$date['year']],
                    ['assthd_rno_month',$date['month']]
                ])
                ->update([ 'assthd_rno_seq' => $number ]);

        }
        else{

            $running_inst = new it_assethandle_runningno([
                    'assthd_rno_year' => $date['year'],
                    'assthd_rno_month' => $date['month'],
                    'assthd_rno_seq' => 1
                ]);
            
            $running_inst->save();
            $number = 1;

        }

        $paper_no = 'AMD-AC-006-RV2-'.$date['year'].$date['month'].str_pad($number,7,'0',STR_PAD_LEFT);

        $asst_paper = it_asset_hardware::where('it_asst_hw_seq',$request->handle_id)
                        ->update([ 'it_assthd_no' => $paper_no ]);
        
        return response()->json(['paper_no' => $paper_no]);

    }

    public function createAssetBorrowNumber(Request $request){

        $date = [ 'year' => date('y'), 'month' => date('m') ];

        $running_no = it_assetborrow_runningno::select('asstbr_rno_seq')
            ->where([
                    ['asstbr_rno_year',$date['year']],
                    ['asstbr_rno_month',$date['month']]
                ]);
        
        $count_rows = $running_no;
        $checkEmpty = $count_rows->count();

        if( $checkEmpty > 0 ){

            $running_no = $running_no->get();
            $number = $running_no[0]['asstbr_rno_seq']+1;

            $running_upd = it_assetborrow_runningno::where([
                    ['asstbr_rno_year',$date['year']],
                    ['asstbr_rno_month',$date['month']]
                ])
                ->update([ 'asstbr_rno_seq' => $number ]);

        }
        else{

            $running_inst = new it_assetborrow_runningno([
                    'asstbr_rno_year' => $date['year'],
                    'asstbr_rno_month' => $date['month'],
                    'asstbr_rno_seq' => 1
                ]);
            
            $running_inst->save();
            $number = 1;

        }

        $paper_no = 'AMD-IT-008-RV1-'.$date['year'].$date['month'].str_pad($number,7,'0',STR_PAD_LEFT);

        $asst_paper = it_asset_hardware::where('it_asst_hw_seq',$request->handle_id)
                        ->update([ 'it_asstbr_no' => $paper_no ]);
        
        return response()->json(['paper_no' => $paper_no]);

    }

    public function uploadHandleFile(Request $request){

        if( $request->handle_group == 1 ){
            $file_newname = 'asset_handle_'.$request->handle_id.'_'.date('Ymdhi').'.pdf';
        }
        elseif( $request->handle_group == 2 ){
            $file_newname = 'asset_borrow_'.$request->handle_id.'_'.date('Ymdhi').'.pdf';
        }
       
        if( $request->file->move(public_path('upload/assethandle'), $file_newname) ){

            $upload = new it_assethandle_file([
                'it_asst_hw_seq' => $request->handle_id,
                'assthd_file_group' => $request->handle_group,
                'assthd_file' => $file_newname,
                'assthd_path' => '/upload/assethandle/'.$file_newname
            ]);

            $result = $upload->save();
            return response()->json($result);

        }
        else{
            $result = false;
            return response()->json($result);
        }

    }

    public function deleteHandleFile(Request $request){

        if( File::exists(public_path($request->file_path)) ){
            if( File::delete(public_path($request->file_path)) ){
                $remove = it_assethandle_file::where('assthd_id',$request->file_id)->delete();
                $result = true;
            }
            else
                $result = false;            
        }
        else 
            $result = false;
        
        return response()->json($result);

    }

    public function dataPaperHandle($id){

        $header = DB::table('it_asset_hardware AS iah')
                    ->select(
                        DB::raw('CONCAT(ie.it_emp_name_th," ",ie.it_emp_surname_th) AS emp_name'),
                        'id.it_dept_name','iah.it_asst_hw_start_date',
                        'iah.it_assthd_no'
                    )
                    ->join('it_employee AS ie','iah.it_emp_id','ie.it_emp_id')
                    ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
                    ->where('iah.it_asst_hw_seq',$id)
                    ->first();
        
        $asset = DB::table('it_asset_hardware AS iah')
                    ->select(
                        'ih.it_hw_name','ih.it_hw_serial','ih.it_hw_number','il.it_locat_name'
                    )
                    ->join('it_hardware AS ih','iah.it_hw_seq','ih.it_hw_seq')
                    ->join('it_location AS il','iah.it_locat_id','il.it_locat_id')
                    ->where('iah.it_asst_hw_seq',$id)
                    ->get();
        
        $admin = DB::table('it_employee AS ie')
                    ->select(
                        DB::raw('CONCAT(ie.it_emp_name_th," ",ie.it_emp_surname_th) AS ad_name'),
                        'id.it_dept_name'
                    )
                    ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
                    ->where('ie.it_emp_id',Session()->get('userId'))
                    ->first();
        
        $result = [
            'admin_name' => $admin->ad_name,
            'admin_dept' => $admin->it_dept_name,
            'emp_name' => $header->emp_name,
            'emp_dept' => $header->it_dept_name,
            'paper_date' => $header->it_asst_hw_start_date,
            'paper_day' => date('d',strtotime($header->it_asst_hw_start_date)),
            'paper_month' => CalenderController::getFullnameMonthThai($header->it_asst_hw_start_date),
            'paper_year' => CalenderController::convertYearToBuddhistera($header->it_asst_hw_start_date), 
            'paper_no' => $header->it_assthd_no,
            'asset' => $asset
        ];

        return $result;

    }

    public function dataPaperBorrow($id){

        $header = DB::table('it_asset_hardware AS iah')
                    ->select(
                        DB::raw('CONCAT(ie.it_emp_name_th," ",ie.it_emp_surname_th) AS emp_name_th'),
                        DB::raw('CONCAT(ie.it_emp_name_eng," ",ie.it_emp_surname_eng) AS emp_name_eng'),
                        'id.it_dept_name','ie.it_emp_tel',
                        'iah.it_asst_hw_start_date','iah.it_asstbr_no'
                    )
                    ->join('it_employee AS ie','iah.it_emp_id','ie.it_emp_id')
                    ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
                    ->where('iah.it_asst_hw_seq',$id)
                    ->first();
        
        $asset = DB::table('it_asset_hardware AS iah')
                    ->select('ih.it_hw_name')
                    ->join('it_hardware AS ih','iah.it_hw_seq','ih.it_hw_seq')
                    ->where('iah.it_asst_hw_seq',$id)
                    ->get();
        
        $result = [
            'name_th' => $header->emp_name_th,
            'name_eng' => $header->emp_name_eng,
            'department' => $header->it_dept_name,
            'tel' => $header->it_emp_tel,
            'paper_date' => $header->it_asst_hw_start_date,
            'paper_no' => $header->it_asstbr_no,
            'asset' => $asset
        ];
        
        return $result;
        
    }

}
