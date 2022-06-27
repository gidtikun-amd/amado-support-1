<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\itMemoController;
use App\Http\Controllers\Api\administrativeController;
use App\Http\Controllers\Api\admJobRequestController;
use App\Http\Controllers\Api\crmController;
use PDF;

class paperController extends Controller
{
    public function pdf_memo($id){

        $obj = new itMemoController();
        $memo = $obj->userGetMemoDetailToObject($id);

        $data = array(
            'no' => $memo->header->no,
            'date' => $memo->header->date,
            'fullnameTh' => $memo->header->efullnameTh,
            'fullnameEn' => $memo->header->efullnameEn,
            'department' => $memo->header->deptname,
            'position' => $memo->header->position,
            'tel' => $memo->header->tel,
            'object' => $memo->header->object,
            'detail' => $memo->detail
        );

        $pdf = PDF::loadView('pdf.memoform', $data) ->setPaper('a4');
        return @$pdf->stream($memo->header->no.'.pdf');

    }

    public function pdf_ad_equipment_req($id){

        $obj = new administrativeController();
        $eqreq = $obj->setObjectEquipReqDetail($id);

        $data = array(
            'no' => $eqreq->header->no,
            'date' => $eqreq->header->dmyDate,
            'fullnameTh' => $eqreq->header->fullnameTh,
            'detail' => $eqreq->detail
        );

        $pdf = PDF::loadView('pdf.adequipmentreq', $data) ->setPaper('a4');
        return @$pdf->stream($eqreq->header->no.'.pdf');

    }

    public function pdf_ad_job_request($id){

        $obj = new admJobRequestController();
        $jobreq = $obj->pdf_data($id);

        $pdf = PDF::loadView('pdf.adjobrequestform', $jobreq) ->setPaper('a4');
        return @$pdf->stream($jobreq->jobreq_no.'.pdf');

    }

    public function pdf_crm_item_request($id){

        $obj = new crmController();        
        $itmreq = $obj->pdf_data($id);

        $data = array(
            'header' => $itmreq['header'],
            'detail' => $itmreq['detail']
        );
        
        $pdf = PDF::loadView('pdf.crmitemrequestform', $data) ->setPaper('a4');
        return @$pdf->stream($data['header']->itmreq_no);

    }
}
