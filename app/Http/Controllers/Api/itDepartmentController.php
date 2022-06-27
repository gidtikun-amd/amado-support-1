<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\it_department;
use App\it_employee;

class itDepartmentController extends Controller
{
    public function index(){
        $departments = it_department::orderBy('it_dept_name')->get();
        return response()->json($departments);
    }

    public function store(Request $request){

        $department = new it_department([
            'it_dept_name' => $request->get('dept_name')
        ]);

        $result = $department->save();
        return response()->json($result);

    }

    public function edit($id){
        $department = it_department::where('it_dept_id',$id)->get();
        $result = $department[0];
        return response()->json($result);
    }

    public function update(Request $request, $id){

        $result = it_department::where('it_dept_id',$id)
            ->update([
                'it_dept_name' => $request->get('dept_name')
            ]);

        return response()->json($result);

    }

    public function destroy($id){

        if( $this->checkExistTypeInHardware($id) > 0 ){

            return response()->json([
                    'Status' => false,
                    'ErrMessage' => 'ระบบไม่สามารถลบฝ่ายได้ เนื่องจากมีข้อมูลพนักงานอยู่ในฝ่าย'
                ]);

        }
        else{
            $result = it_department::where('it_dept_id',$id)->delete();
            return response()->json([ 'Status' => $result ]);
        }

    }

    private function checkExistTypeInHardware($id){
        $count = it_employee::where('it_dept_id',$id)->count();
        return $count;
    }

}
