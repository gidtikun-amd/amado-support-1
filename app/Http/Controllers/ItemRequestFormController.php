<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\itItemRequestController;
use PDF;

class ItemRequestFormController extends Controller
{
    public function __construct(){}

    public function pdf_index($id) {
        $obj = new itItemRequestController();
        $detail = $obj->getDataPDFItmreq($id);
        $data = [
            'itmreq_no' => $detail->header->itmreq_no,
            'itmreq_date' => $detail->header->itmreq_date,
            'emp_fullname' => $detail->header->it_emp_fullname,
            'emp_position' => $detail->header->it_emp_position,
            'itmreq_obj' => $detail->header->itmreq_obj,
            'detail' => $detail->detail
        ];
        
        $pdf = PDF::loadView('pdf.itemrequestform', $data) ->setPaper('a4');
        return @$pdf->stream($data['itmreq_no'].'.pdf');
    }
}
