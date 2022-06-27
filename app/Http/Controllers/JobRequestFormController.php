<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class JobRequestFormController extends Controller
{

    public function __construct(){}

    public function pdf_index($id) {
        
        $hader_temp = DB::table('it_jobreq AS a')
            ->select(
                'a.jobreq_id','a.jobreq_no',
                'b.it_emp_name_th', 'b.it_emp_surname_th', 'b.it_emp_name_eng', 'b.it_emp_surname_eng',
                'a.it_emp_position', 'c.it_dept_name', 'b.it_emp_tel',
                'a.jobreq_obj', 'a.jobreq_status', 'a.jobreq_demand', 'a.jobreq_reason',
                'a.jobreq_other',
                DB::raw('DATE_FORMAT(a.jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw(
                    'CASE a.jobreq_obj 
                        WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                        WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                        WHEN 3 THEN "ลบผู้ใช้งาน"
                        WHEN 4 THEN "พัฒนาใหม่"
                        WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                        WHEN 6 THEN "แจ้งอุปกรณ์เสีย/ซ่อม"
                        ELSE "อื่นๆ" END jobreq_obj_str'
                ),
                DB::raw(
                    'CASE a.jobreq_status 
                        WHEN 1 THEN "รอดำเนินการ"
                        WHEN 2 THEN "กำลังดำเนินการ"
                        WHEN 3 THEN "ดำเนินการเรียบร้อย"
                        ELSE "ยกเลิก" END jobreq_status_str'
                )
            )
            ->join('it_employee AS b','a.it_emp_id','b.it_emp_id')
            ->join('it_department AS c','b.it_dept_id','c.it_dept_id')
            ->where('a.jobreq_id',$id)
        ->get();
        
        $header = $hader_temp[0];
        $detail = DB::table('it_jobreq_detail')
            ->select('jobreq_id','jobreq_line','jobreq_detail','jobreq_qty','jobreq_unit')
            ->where('jobreq_id',$id)
            ->get();

        $data = [
            'jobreq_no' => $header->jobreq_no,
            'jobreq_date' => $header->jobreq_date,
            'fullname_th' => $header->it_emp_name_th.' '.$header->it_emp_surname_th,
            'fullname_en' => $header->it_emp_name_eng.' '.$header->it_emp_surname_eng,
            'department' => $header->it_dept_name,
            'position' => $header->it_emp_position,
            'tel' => $header->it_emp_tel,
            'obj' => $header->jobreq_obj,
            'other' => $header->jobreq_other,
            'demand' => $header->jobreq_demand,
            'reason' => $header->jobreq_reason,
            'detail' => $detail
        ];
        
        $pdf = PDF::loadView('pdf.jobrequestform', $data) ->setPaper('a4');
        return @$pdf->stream($header->jobreq_no.'.pdf');
        
    }
}
