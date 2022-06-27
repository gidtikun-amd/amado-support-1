<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\exceptionController;
use DB;
use App\it_employee;
use App\it_asset_hardware;
use App\User;

class itEmployeeController extends Controller
{
    private $exceptionController;

    public function __construct(){
        $this->exceptionController = new exceptionController();
    }
    
    public function index(){

        $employees = DB::table('it_employee AS a')
            ->select(
                'a.it_emp_id', 
                'a.it_emp_name_th', 'a.it_emp_surname_th', 'a.it_emp_nickname_th', 
                'a.it_emp_name_eng', 'a.it_emp_surname_eng', 'a.it_emp_nickname_eng', 
                'a.it_emp_gender', 'a.it_emp_tel', 'a.it_emp_email_amd', 'a.it_emp_email_second', 
                'a.it_dept_id', 'b.it_dept_name',
                'a.it_emp_active',
                DB::raw('CASE a.it_emp_active WHEN 1 THEN \'Active\' ELSE \'Inactive\' END emp_active')
            )
            ->join('it_department AS b','a.it_dept_id','=','b.it_dept_id')
            ->paginate(30);
            //->get();
        
        return response()->json($employees);
    }

    public function getEmployees(Request $request){
       
        $employees = DB::table('it_employee AS a')
            ->select(
                'a.it_emp_id', 
                'a.it_emp_name_th', 'a.it_emp_surname_th', 'a.it_emp_nickname_th', 
                'a.it_emp_name_eng', 'a.it_emp_surname_eng', 'a.it_emp_nickname_eng', 
                'a.it_emp_gender', 'a.it_emp_tel', 'a.it_emp_email_amd', 'a.it_emp_email_second', 
                'a.it_dept_id', 'b.it_dept_name',
                'a.it_emp_active',
                DB::raw('CASE a.it_emp_active WHEN 1 THEN \'Active\' ELSE \'Inactive\' END emp_active')
            )
            ->join('it_department AS b','a.it_dept_id','=','b.it_dept_id');
        
        if( !empty($request->keyword) ){
            $employees = $employees->where('a.it_emp_id','LIKE','%'.$request->keyword.'%')
                ->orWhere('a.it_emp_name_th','LIKE','%'.$request->keyword.'%')
                ->orWhere('a.it_emp_surname_th','LIKE','%'.$request->keyword.'%')            
                ->orWhere('a.it_emp_nickname_th','LIKE','%'.$request->keyword.'%')            
                ->orWhere('b.it_dept_name','LIKE','%'.$request->keyword.'%');
        }

        
        $employees = $employees->paginate(30);
        return response()->json($employees);

    }

    public function searchAssetHardware($id){
        
        $emp_asset = DB::table('it_hardware AS a')
            ->select(
                'a.it_hw_number', 'a.it_hw_name', 'c.it_hw_type_name', 'b.it_locat_id', 'd.it_locat_name',
                'b.it_asst_hw_status', 'b.it_asst_hw_start_date', 'b.it_asst_hw_end_date', 'b.it_asst_hw_remark',
                'a.it_hw_seq'
            )
            ->join('it_asset_hardware AS b', 'a.it_hw_seq', 'b.it_hw_seq')
            ->join('it_hardware_type AS c', 'a.it_hw_type_id', 'c.it_hw_type_id')
            ->join('it_location AS d', 'b.it_locat_id','d.it_locat_id')
            ->where('b.it_emp_id',$id)
            ->orderBy('b.it_asst_hw_start_date','DESC')
            ->orderBY('a.it_hw_number','DESC')
            ->get();
            
        $emp_license = DB::table('it_software_license_handle as islh')
            ->select(
                'ih.it_hw_number', 'ih.it_hw_name', 'iht.it_hw_type_name',
                'isl.swl_license', 'ih.it_hw_seq',
                DB::RAW('CASE isl.swl_status WHEN 1 THEN \'ถือครอง\' ELSE \'ไม่ได้ถือครองแล้ว\' END swl_status')
            )
            ->join('it_software_license as isl','islh.swl_id','isl.swl_id')
            ->join('it_hardware as ih','isl.it_hw_id','ih.it_hw_seq')
            ->join('it_hardware_type as iht','ih.it_hw_type_id','iht.it_hw_type_id')
            ->where('islh.it_emp_id',$id)
            ->get();
        
        $result = array('asset' => $emp_asset, 'license' => $emp_license);
        return response()->json($result);

    }
    
    public function create(){
        //
    }

    private function checkExistEmpCode($id){
        $count = it_employee::where('it_emp_id',$id)->count();
        return $count > 0 ? true : false;
    }
    
    public function store(Request $request){    
        
        if($this->checkExistEmpCode($request->get('emp_id'))){
            return response()->json(array(
                'status' => false,
                'exception' => 'dataExist',
                'message' => 'รหัสพนักงานนี้มีภายในระบบแล้ว'
            ));
        }

        try{

            $employee = new it_employee([
                'it_emp_id' => $request->get('emp_id'),
                'it_emp_name_th' => $request->get('emp_name_th'),
                'it_emp_surname_th' => $request->get('emp_surname_th'),
                'it_emp_nickname_th' => $request->get('emp_nickname_th'),
                'it_emp_name_eng' => $request->get('emp_name_eng'),
                'it_emp_surname_eng' => $request->get('emp_surname_eng'),
                'it_emp_nickname_eng' => $request->get('emp_nickname_eng'),
                'it_emp_gender' => $request->get('emp_gender'),
                'it_emp_tel' => $request->get('emp_tel'),
                'it_emp_email_amd' => $request->get('emp_email_amd'),
                'it_emp_email_second' => $request->get('emp_email_second'),
                'it_emp_active' => $request->get('emp_active'),
                'it_dept_id' => $request->get('dept_id'),
                'it_locat_id' => $request->get('emp_location')
            ]);

            $result = $employee->save();  

            if( $result ){

                $user = new User([
                    'user' => $request->get('emp_id'),
                    'password' => base64_encode('amado.1234'),
                    'identity' => 'user',
                    'role' => 'user',
                    'it_emp_id' => $request->get('emp_id'), 
                    'password_expaired' => date('Y-m-d')
                ]);

                $sv_user = $user->save();

            }

            return response()->json(array(
                    'status' => $result,
                    'exception' => '',
                    'message' => ''
                ));        

        }
        catch(QueryException $e){

            $syserror = $e->getMessage();
            $errormsg = explode('(SQL:',$syserror);

            $this->exceptionController->saveLogQueryException(array(
                'module' => 'employee_by_admin',
                'action' => 'insert_db',
                'message' => $syserror
            ));

            return response()->json(array(
                'status' => false,
                'exception' => 'sqlError',
                'message' => $errormsg[0]
            )); 

        }
        
    }
    
    public function show($id){
        //
    }
    
    public function edit($id){

        $employees = DB::table('it_employee AS a')
            ->select(
                'a.it_emp_id', 
                'a.it_emp_name_th', 'a.it_emp_surname_th', 'a.it_emp_nickname_th', 
                'a.it_emp_name_eng', 'a.it_emp_surname_eng', 'a.it_emp_nickname_eng', 
                'a.it_emp_gender', 'a.it_emp_tel', 'a.it_emp_email_amd', 'a.it_emp_email_second', 
                'a.it_dept_id', 'b.it_dept_name', 'a.it_emp_active', 'a.it_locat_id'
            )
            ->join('it_department AS b','a.it_dept_id','=','b.it_dept_id')
            ->where('a.it_emp_id','=',$id)
            ->get();
        
        $result = $employees[0];
        
        return response()->json($result);

    }
    
    public function update(Request $request, $id){

        try{
        
            $result = it_employee::find($id)
                ->update([
                    'it_emp_name_th' => $request->get('emp_name_th'),
                    'it_emp_surname_th' => $request->get('emp_surname_th'),
                    'it_emp_nickname_th' => $request->get('emp_nickname_th'),
                    'it_emp_name_eng' => $request->get('emp_name_eng'),
                    'it_emp_surname_eng' => $request->get('emp_surname_eng'),
                    'it_emp_nickname_eng' => $request->get('emp_nickname_eng'),
                    'it_emp_gender' => $request->get('emp_gender'),
                    'it_emp_tel' => $request->get('emp_tel'),
                    'it_emp_email_amd' => $request->get('emp_email_amd'),
                    'it_emp_email_second' => $request->get('emp_email_second'),
                    'it_emp_active' => $request->get('emp_active'),
                    'it_dept_id' => $request->get('dept_id'),
                    'it_locat_id' => $request->get('emp_location')
                ]);
                
            return response()->json(array(
                'status' => $result,
                'exception' => '',
                'message' => ''
            ));   
        
        }
        catch(QueryException $e){

            $syserror = $e->getMessage();
            $errormsg = explode('(SQL:',$syserror);

            $this->exceptionController->saveLogQueryException(array(
                'module' => 'employee_by_admin',
                'action' => 'update_db',
                'message' => $syserror
            ));

            return response()->json(array(
                'status' => false,
                'exception' => 'sqlError',
                'message' => $errormsg[0]
            )); 

        }

    }
    
    public function destroy($id){

        if( $this->checkExistEmpInAssetHw($id) > 0 ){

            return response()->json([
                'Status' => false,
                'ErrMessage' => 'ระบบไม่สามารถลบข้อมูลพนักงานได้ เนื่องจากมีข้อมูลถือคลองทรัพย์สินอยู่'
            ]);

        }
        else{

            $result = it_employee::where('it_emp_id',$id)->delete();

            if( $result )
                User::where('it_emp_id',$id)->delete();

            return response()->json([ 'Status' => $result ]);

        }

    }

    private function checkExistEmpInAssetHw($id){
        $count = it_asset_hardware::where('it_emp_id',$id)->count();
        return $count;
    }

    public function searchEmployeesAsstHandle(Request $request){

        $result = DB::table('it_employee AS a')
            ->select(
                'a.it_emp_id','a.it_emp_name_th','a.it_emp_surname_th','a.it_emp_nickname_th',
                'b.it_dept_name','a.it_locat_id','a.it_dept_id'
            )
            ->join('it_department AS b','a.it_dept_id','b.it_dept_id')
            ->where('a.it_emp_name_th','LIKE','%'.$request->keyword.'%')
            ->whereOr('a.it_emp_surname_th','LIKE','%'.$request->keyword.'%')
            ->whereOr('a.it_emp_id','LIKE','%'.$request->keyword.'%')
            ->orderBy('a.it_emp_id','ASC')
            ->get();
            
        return response()->json($result);

    }

    public function searchEmployeeFixedID($id){

        $result = DB::table('it_employee AS a')
            ->select(
                'a.it_emp_id','a.it_emp_name_th','a.it_emp_surname_th','a.it_emp_nickname_th',
                'b.it_dept_name','a.it_locat_id','a.it_dept_id'
            )
            ->join('it_department AS b','a.it_dept_id','b.it_dept_id')
            ->where('a.it_emp_id',$id)
            ->first();
            
        return response()->json($result);

    }

}
