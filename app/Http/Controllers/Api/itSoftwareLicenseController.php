<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\it_software_license;
use App\it_software_license_handle;
use DB;

class itSoftwareLicenseController extends Controller
{
    public function addSoftwareLicense(Request $request){

        $save = new it_software_license([
            'it_hw_id' => $request->hw_id,
            'swl_license' => $request->license,
            'swl_volumes' => $request->volumes,
            'swl_status' => $request->status
        ]);

        $result = $save->save();
        return response()->json($result);

    }

    public function updateSoftwareLicense(Request $request){

        $update = it_software_license::where('swl_id',$request->id)
                    ->update([
                        'swl_license' => $request->license,
                        'swl_volumes' => $request->volumes,
                        'swl_status' => $request->status
                    ]);
        
        return response()->json($update);

    }

    public function deleteSoftwareLicense($id){
        $delete = it_software_license::where('swl_id',$id)->delete();
        return response()->json($delete);
    }

    public function getAssetSoftware(Request $request){

        $software = DB::table('it_hardware AS ih')
            ->select(
                'ih.it_hw_seq','ih.it_hw_number','ih.it_hw_name','ih.it_hw_status',
                'ih.it_hw_group','ih.it_hw_serial','ih.it_hw_type_id','iht.it_hw_type_name',
                DB::raw('CASE ih.it_hw_status 
                            WHEN 1 THEN \'ใช้งาน\'
                            WHEN 2 THEN \'สำรอง\'
                            WHEN 3 THEN \'ส่งซ่อม\'
                            WHEN 4 THEN \'ออกจำหน่าย\'
                            WHEN 5 THEN \'ยืมใช้งาน\'
                            ELSE \'ไม่ใช้งาน\' END AS it_hw_status')
            )
            ->join('it_hardware_type AS iht','ih.it_hw_type_id','iht.it_hw_type_id')
            ->whereIn('ih.it_hw_type_id',[20,26]);
        
        if(!empty($request->keyword)){
            $software = $software->whereRaw('(
                ih.it_hw_number LIKE \'%'.$request->keyword.'%\'
                or ih.it_hw_name LIKE \'%'.$request->keyword.'%\'
            )');
        }        
    
        $software = $software->orderBy('ih.it_hw_seq','DESC')
            ->paginate(20);
            
        return response()->json($software);

    }

    public function getSoftwareLicense($id){

        $list = DB::table('it_software_license as isl')
            ->select(
                'isl.swl_id', 'isl.swl_license', 'isl.swl_volumes',
                DB::raw('IFNULL(COUNT(islh.swlh_id),0) swl_used'),
                DB::raw('CASE isl.swl_status WHEN 1 THEN \'ใช้งาน\' ELSE \'ไม่ได้ใช้งาน\' END swl_status')
            )
            ->leftJoin('it_software_license_handle as islh','isl.swl_id','islh.swl_id')
            ->where('islh.swlh_status',1)
            ->where('isl.it_hw_id',$id)
            ->groupBy('isl.swl_id','isl.swl_license','isl.swl_volumes','swl_status')
            ->orderBy('isl.created_at','DESC')
            ->get();
        
        return response()->json($list);

    }

    public function getSoftwareLicenseHandle($id){

        $list = DB::table('it_software_license_handle AS islh')
                ->select(
                    'islh.swlh_id AS handle_id','islh.it_emp_id AS emp_id',
                    DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name'),
                    'id.it_dept_name AS dept_name','islh.it_locat_id AS locat_id',
                    'il.it_locat_name AS locat_name','islh.swlh_status AS handle_status',
                    'islh.swlh_start_date AS handle_sdate','islh.swlh_end_date AS handle_edate'
                )
                ->join('it_employee AS ie','islh.it_emp_id','ie.it_emp_id')
                ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
                ->join('it_location AS il','islh.it_locat_id','il.it_locat_id')
                ->where('islh.swl_id',$id)
                ->orderBy('islh.swlh_id','DESC')
                ->get();
        
        return response()->json($list);

    }

    private function checkSummarySWLicenseHandle($dataset){

        $valumes = it_software_license::select('swl_volumes')->where('swl_id',$dataset->license_id)->first();
        $summary = it_software_license_handle::where([
                        ['swl_id',$dataset->license_id],
                        ['swlh_status',1]
                    ])
                    ->count();
        
        return $valumes->swl_volumes > $summary ? true : false;

    }

    public function saveSoftwareLicenseHandle(Request $request){

        if(!$this->checkSummarySWLicenseHandle($request))
            return response()->json(['status' => false, 'msg' => 'License นี้มีผู้ถือครองเต็ม Volumes แล้ว']);

        $create = new it_software_license_handle([
                        'swl_id' => $request->license_id,
                        'it_emp_id' => $request->emp_id,
                        'it_locat_id' => $request->locat_id,
                        'swlh_status' => $request->handle_status,
                        'swlh_start_date' => $request->handle_sdate,
                        'swlh_end_date' => $request->handle_edate,
                        'swlh_user' => session()->get('user')
                    ]);
        
        $result = $create->save();
        return response()->json([
            'status' => $result, 
            'msg' => ($result ? 'บันทึกผู้ถือครอง License เรียบร้อยแล้ว' : 'ไม่สามารถบันทึกผู้ถือครอง License ได้')
            ]);

    }

    public function updateSoftwareLicenseHandle(Request $request){
        
        if($request->handle_status == '1')
            if(!$this->checkSummarySWLicenseHandle($request))
                return response()->json(['status' => false, 'msg' => 'License นี้มีผู้ถือครองเต็ม Volumes แล้ว']);

        $update = it_software_license_handle::where('swlh_id',$request->handle_id)
                    ->update([
                        'it_locat_id' => $request->locat_id,
                        'swlh_status' => $request->handle_status,
                        'swlh_start_date' => $request->handle_sdate,
                        'swlh_end_date' => $request->handle_edate,
                        'swlh_user' => $request->user
                    ]);
        
        return response()->json([
            'status' => $update, 
            'msg' => ($update ? 'บันทึกผู้ถือครอง License เรียบร้อยแล้ว' : 'ไม่สามารถบันทึกผู้ถือครอง License ได้')
            ]);

    }

    public function deleteSoftwareLicenseHandle($id){
        $delete = it_software_license_handle::where('swlh_id',$id)->delete();
        return response()->json($delete);
    }

    public function getLicenseUserHandle($id){

        $result = DB::table('it_software_license_handle as islh')
            ->select(
                'ih.it_hw_number as hw_number', 'ih.it_hw_name as hw_name','isl.swl_license',
                DB::raw('case islh.swlh_status WHEN 1 THEN \'ถือครอง\' ELSE \'ไม่ได้ถือครอง\' END status')
            )
            ->join('it_software_license as isl','islh.swl_id','isl.swl_id')
            ->join('it_hardware as ih','isl.it_hw_id','ih.it_hw_seq')
            ->where('islh.it_emp_id',$id)
            ->get();

        return response()->json($result);

    }
}
