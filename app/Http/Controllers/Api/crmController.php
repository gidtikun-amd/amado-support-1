<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\crmproductgroup;
use App\crmcustomergroup;
use App\crmproductlist;
use App\crmitemrequest;
use App\crmitemrequestdetail;
use App\crmitemrequest_runningno;
use App\crmitemrequest_api;
use App\Http\Controllers\Security\LoginController;
use Curl;

class crmController extends Controller
{
    private $linkApiCrmItmreqProd = 'https://crm.amadogroup.co.th/api/newproduct';
    private $linkApiCrmItmreqDev = 'https://crm.dev.amadogroup.co.th/api/newproduct';
    private $TokenApiCrmItmreq = 'HIGdoI2GN1E5MfEiqkrSLnU9kHdUDsjk';

    public function getProductList(){
        $data = DB::table('crmproductlist as pl')
            ->select(
                'pl.pdl_id as id', 'pl.pdl_code as code', 'pl.pdl_name as name', 
                'pl.pdl_rprice as rprice', 'pl.pdl_aprice as aprice', 
                'pl.pdl_unit as unit', 'pl.ctmg_id as pgid'
                // ,'pg.ctmg_name as pgname'
            )
            // ->join('crmcustomergroup as pg','pl.ctmg_id','pg.ctmg_id')
            ->orderBy('pl.pdl_name','ASC')
            ->get();

        return response()->json($data);
    }

    public function getProductGroupList(){
        $data = crmproductgroup::select('pdg_id as id', 'pdg_name as name')->get();
        return response()->json($data);
    }

    public function getCustomerGroupList(){
        $data = crmcustomergroup::select('ctmg_id as id', 'ctmg_name as name')->get();
        return response()->json($data);
    }

    public function getListCrmItemreq(Request $request){

        $lists = DB::table('crmitemrequest as cir')
            ->select(
                'cir.itmreq_id','cir.itmreq_no','cir.itmreq_date',
                'cir.it_emp_id','cir.itmreq_status','cir.itmreq_obj',
                'cir.itmreq_pcode',
                'cir.itmreq_interface_sts as api_status',
                DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) emp_fullname'),
                DB::raw(
                    'CASE cir.itmreq_status
                        WHEN 1 THEN "รอดำเนินการ"
                        WHEN 2 THEN "กำลังดำเนินการ"
                        WHEN 3 THEN "ดำเนินการเรียบร้อย"
                        ELSE "ยกเลิก" END status_str'
                ),
                DB::raw(
                    'CASE cir.itmreq_approve
                        WHEN 1 THEN \'อนุมัติ\'
                        WHEN 2 THEN \'ไม่อนุมัติ\'
                        ELSE \'ยังไม่ได้อนุมัติ\' END approve_str'
                )

            )
            ->join('it_employee as ie','cir.it_emp_id','ie.it_emp_id');

        switch($request->methods){
            case 'waitappr': $lists = $lists->where('cir.itmreq_approve',0); break; 
            case 'success': $lists = $lists->where('cir.itmreq_status',3); break;            
            case 'void': $lists = $lists->where('cir.itmreq_status',0); break; 
        }

        if(!empty($request->search))
            $lists = $lists->where('cir.itmreq_no','LIKE','%'.$request->search.'%');

            if( !empty($request->searchEmp) ){
                $lists = $lists->whereRaw('(
                    ie.it_emp_id LIKE \'%'.$request->searchEmp.'%\'
                    or ie.it_emp_name_th LIKE \'%'.$request->searchEmp.'%\'
                    or ie.it_emp_surname_th LIKE \'%'.$request->searchEmp.'%\'
                )');
            }    

        if($request->role == 'user')
            $lists = $lists->where('ie.it_dept_id',session()->get('departmentId'));
        else{

        }

        $lists = $lists->orderBy('cir.created_at','DESC')
                ->paginate(20);
        
        return response()->json($lists);

    }

    public function getDataCrmItmreqDetail(Request $request){
        $data = $this->setCrmItmreqDetail($request);
        if($request->format == 'json') return response()->json($data);
        else return $data;
    }

    public function pdf_data($id){
        $parm = (object) array('id' => $id, 'format' => 'array', 'role' => '');
        $data = $this->setCrmItmreqDetail($parm);
        return $data;
    }

    private function setCrmItmreqDetail($dataset){

        $header = DB::table('crmitemrequest as cir')
            ->select(
                'cir.itmreq_id','cir.itmreq_no','cir.itmreq_status','cir.itmreq_obj',
                'cir.it_emp_id','cir.it_emp_position','cir.itmreq_date',
                'cir.itmreq_approve','itmreq_pbcode','itmreq_pname','itmreq_punit',
                'itmreq_rprice','itmreq_aprice','itmreq_efdate','itmreq_exdate',
                'itmreq_status','cir.itmreq_approve','cir.itmreq_approve_u',
                'itmreq_vipamdprofit as vipamdprofit', 'itmreq_vipbfnprofit as vipbfnprofit', 
                'itmreq_stockprofit as stockprofit',
                'itmreq_interface_sts as api_status', 'itmreq_interface_msg as api_msg',
                DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) it_emp_fullname')
            )
            ->join('it_employee as ie','cir.it_emp_id','ie.it_emp_id')
            ->where('cir.itmreq_id',$dataset->id)
            ->first();

        $detail = DB::table('crmitemrequestdetail as aicd')
            ->select(
                'aicd.pdl_id','aicd.itmreqd_price','aicd.itmreq_qty','aicd.itmreq_ptotal',
                'cpl.pdl_name','cpl.pdl_unit','cpl.pdl_code'
            )
            ->join('crmproductlist as cpl','aicd.pdl_id','cpl.pdl_id')
            ->where('aicd.itmreq_id',$dataset->id)
            ->get();

        $result = array(
            'header' => $header,
            'detail' => $detail,
            'api' => array(),
            'approve' => array()       
        );

        if($header->itmreq_status == 3 || $header->itmreq_status == 0){

            $user = DB::table('user as u')
                ->select(
                    'u.user','ie.it_emp_id',
                    DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) it_emp_fullname')
                )
                ->join('it_employee as ie','u.user','ie.it_emp_id')
                ->where('u.user',$header->itmreq_approve_u)
                ->first();

            $result['approve'] = $user;

        }

        if($dataset->role == 'admin'){
            
            $api = DB::table('crmitemrequest_api')
                ->select(
                    'api_id', 'api_ref', 'itmreq_id', 
                    'api_json_send', 'api_json_receive', 
                    'api_status', 'api_user', 'api_msg',
                    'created_at', 'updated_at'
                )
                ->where('itmreq_id',$dataset->id)
                ->orderBy('created_at','DESC')
                ->get();

            $result['api'] = $api;

        }        

        return $result;

    }

    public function saveCrmJobrequest(Request $request){

        $date = [ 'year' => date('y'), 'month' => date('m') ];
        $run_no = $this->getRunningNumber($date);
        $paper_no = 'IT-CRM-ITEM-'.$date['year'].$date['month'].str_pad($run_no,7,'0',STR_PAD_LEFT);
        
        $itmreq = new crmitemrequest([
            'itmreq_no' => $paper_no, 
            'itmreq_date' => $request->header['today'], 
            'it_emp_id' => $request->header['emp_id'], 
            'it_emp_position' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->header['emp_position']), 
            'itmreq_obj' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->header['itmreq_obj']), 
            'pdg_id' => $request->header['prod_group'], 
            'ctmg_id' => $request->header['cust_group'], 
            'itmreq_pbcode' => $request->header['prod_barcode'], 
            'itmreq_pname' => preg_replace('/[\x{200B}-\x{200D}]/u','',$request->header['prod_name']), 
            'itmreq_punit' => $request->header['prod_unit'], 
            'itmreq_rprice' => $request->header['prod_rprice'], 
            'itmreq_aprice' => $request->header['prod_aprice'], 
            'itmreq_efdate' => $request->header['prom_sdate'], 
            'itmreq_exdate' => $request->header['prom_edate'], 
            'itmreq_vipamdprofit' => $request->header['prod_VIPAmadoprofitshare'], 
            'itmreq_vipbfnprofit' => $request->header['prod_VIPBifinaProfitShare'], 
            'itmreq_stockprofit' => $request->header['prod_StockProfitShare'],
            'itmreq_status' => 1, 
        ]);

        if( $itmreq->save() ){

            $inst_id = $itmreq->id;
            $values = [];
            $line = 1;

            foreach( $request->detail as $val ){
                array_push($values,[
                    'itmreq_id' => $inst_id, 
                    'pdl_id' => $val['id'], 
                    'itmreqd_price' => $val['price'], 
                    'itmreq_qty' => $val['qty'], 
                    'itmreq_ptotal' => $val['tprice'], 
                    'created_at' => date('Y-m-d H:i:s'), 
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $itmreq_detail = crmitemrequestdetail::insert($values);

            if( $itmreq_detail ){
                return response()->json(['Status' => true, 'paper_id' => $paper_no]);
            }
            else{
                return response()->json(['Status' => false]);
            }

        }

    }

    private function getRunningNumber($date){

        $running_no = crmitemrequest_runningno::select('itmreq_rno_seq')
            ->where([
                ['itmreq_rno_year',$date['year']],
                ['itmreq_rno_month',$date['month']]
            ]);

        $count_rows = $running_no;
        $checkEmpty = $count_rows->count();

        if( $checkEmpty > 0 ){

            $running_no = $running_no->first();
            $number = $running_no['itmreq_rno_seq']+1;

            $running_upd = crmitemrequest_runningno::where([
                ['itmreq_rno_year',$date['year']],
                ['itmreq_rno_month',$date['month']]
            ])
            ->update([ 'itmreq_rno_seq' => $number ]);

            return $number;

        }
        else{

            $running_inst = new crmitemrequest_runningno([
                'itmreq_rno_year' => $date['year'],
                'itmreq_rno_month' => $date['month'],
                'itmreq_rno_seq' => 1
            ]);

            $running_inst->save();
            return 1;

        }

    }

    public function voidCrmItmreq($id){

        if(LoginController::getLoginStatus())
            return response()->json(array('Status' => false, 'error' => 'login.fail'));

        $update = crmitemrequest::where('itmreq_id',$id)
            ->update([
                'itmreq_status' => 0,
                'itmreq_approve' => 2,
                'itmreq_approve_u' => session()->get('user')
            ]);
        
        return response()->json(array('Status' => $update));

    }

    public function approveCrmItmreq($id){

        if(LoginController::getLoginStatus())
            return response()->json(array('Status' => false, 'error' => 'login.fail'));

        $update = crmitemrequest::where('itmreq_id',$id)
            ->update([
                'itmreq_status' => 3,
                'itmreq_approve' => 1,
                'itmreq_approve_u' => session()->get('user')
            ]);

        if($update){
            $this->callApiCrmItmreq($id);
        }

        return response()->json(array('Status' => $update));
    }

    public function recallApiCrmItmreq($id){
        if(LoginController::getLoginStatus())
            return response()->json(array('Status' => false, 'error' => 'login.fail'));
        $this->callApiCrmItmreq($id);
        return response()->json(array('Status' => true));
    }

    private function callApiCrmItmreq($id){

        $dataset = $this->setJsonApiCrmItmreq($id);

        $log = new crmitemrequest_api([
            'api_ref' => $dataset['reflot'], 
            'itmreq_id' => $id, 
            'api_json_send' => json_encode($dataset), 
            'api_status' => 1, 
            'api_user' => session()->get('user')
        ]);

        $log->save();
        $inst_id = $log->id;
        
        $response = Curl::to($this->linkApiCrmItmreqDev)
            ->withContentType('application/json')
            ->withData($dataset)
            ->withBearer($this->TokenApiCrmItmreq)
            ->asJson(true)
            ->post();
        // {
        //     "datamsg":"Success",
        //     "messageError":"",
        //     "status":true,
        //     "productstatus":"Error",
        //     "productmsg":"",
        //     "lproductcode":null,
        //     "messageproductError":"Trying to get property 'lUserID' of non-object"
        // }
        $response = (object) $response;
        $api_status = $response->productstatus == 'Success' ? 2 : 1;        
        $log_status = $response->productstatus == 'Success' ? 3 : 2;
        $product_no = $response->productstatus == 'Success' ? $response->lproductcode : '';
        $api_msg = preg_replace('/[\x{200B}-\x{200D}]/u','',$response->messageproductError);

        $update_main = crmitemrequest::where('itmreq_id',$id)
            ->update([
                'itmreq_pcode' => $product_no,
                'itmreq_interface_sts' => $api_status,
                'itmreq_interface_msg' => $api_msg
            ]);
        
        $update_log = crmitemrequest_api::where('api_id',$inst_id)
            ->update([
                'api_status' => $log_status,
                'api_msg' => $api_msg,
                'api_json_receive' => json_encode($response)
            ]);

    }

    private function setJsonApiCrmItmreq($id){

        $reflot = date('Ymdhis');

        $header = DB::table('crmitemrequest')
            ->select(
                'pdg_id','ctmg_id','itmreq_pbcode','itmreq_pname','itmreq_punit',
                'itmreq_rprice','itmreq_aprice','itmreq_efdate','itmreq_exdate',
                'it_emp_id','itmreq_vipamdprofit', 'itmreq_vipbfnprofit', 
                'itmreq_stockprofit','itmreq_no'
            )
            ->where('itmreq_id',$id)
            ->first();
            
        $detail = DB::table('crmitemrequestdetail as dt')
            ->select(
                'pd.pdl_code as productId','dt.itmreqd_price as productPrice',
                'dt.itmreq_qty as productQty','dt.itmreq_ptotal as productPTotal'
            )
            ->join('crmproductlist as pd','dt.pdl_id','pd.pdl_id')
            ->where('itmreq_id',$id)
            ->get();
            
        $result = array(
            'reflot' => $reflot,
            'ItmreqNo' => $header->itmreq_no,
            'UserId' => $header->it_emp_id,
            'productGroup' => str_pad($header->pdg_id,2,'0',STR_PAD_LEFT),
            'customerGroup' => $header->ctmg_id,
            // 'barcode' => $header->itmreq_pbcode,
            'productName' => $header->itmreq_pname,
            'productUnit' => $header->itmreq_punit,
            'priceRetail' => $header->itmreq_rprice,
            'priceTotal' => $header->itmreq_aprice,
            'EffectiveDate' => $header->itmreq_efdate,
            'ExpirationDate' => $header->itmreq_exdate, 
            'VIPAmadoprofitshare' => $header->itmreq_vipamdprofit,
            'VIPBifinaProfitShare' => $header->itmreq_vipbfnprofit,
            'StockProfitShare' => $header->itmreq_stockprofit,
            'detail' => $detail
        );

        return $result;

    }

}
