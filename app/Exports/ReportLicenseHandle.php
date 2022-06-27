<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportLicenseHandle implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($params){
        $this->search = $params;
    }
    
    public function collection(){

        $sql = DB::table('it_hardware as ih')
            ->select(
                'islh.it_emp_id as emp_id',
                DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as fullname_th'),
                'ie.it_emp_nickname_th as nickname_th',
                'id.it_dept_name as dept_name', 'il.it_locat_name as locat_name',
                'ih.it_hw_number as hw_number', 'iht.it_hw_type_name as hw_type',
                'ih.it_hw_name as hw_name', 'isl.swl_license',
                DB::raw('CASE ih.it_hw_status
                    WHEN 1 THEN \'ใช้งาน\'
                    WHEN 2 THEN \'สำรอง\'
                    WHEN 3 THEN \'ส่งซ่อม\'
                    WHEN 4 THEN \'ออกจำหน่าย\'
                    WHEN 5 THEN \'ยืมใช้งาน\'
                    ELSE \'ไม่ใช้งาน\' END AS hw_status'),
                DB::raw('CASE ih.it_hw_group
                    WHEN 1 THEN \'ทรัพย์สินบุคคลถือคลอง\'
                    ELSE \'ทรัพย์สินส่วนกลางฝ่าย\' END AS hw_group'),
                DB::raw('CASE isl.swl_status WHEN 1 THEN \'ใช้งาน\' ELSE \'ไม่ใช้งาน\' END as swl_status'),
                DB::raw('CASE islh.swlh_status WHEN 1 THEN \'กำลังถือครอง\' ELSE \'ไม่ได้ถือครอง\' END as swlh_status'),
                DB::raw('CASE WHEN islh.swlh_start_date = \'0000-00-00\' OR islh.swlh_start_date IS NULL
                    THEN \'\' ELSE islh.swlh_start_date END AS start_date'),
                DB::raw('CASE WHEN islh.swlh_end_date = \'0000-00-00\' OR islh.swlh_end_date IS NULL
                    THEN \'\' ELSE islh.swlh_end_date END AS end_date')
            )
            ->join('it_hardware_type as iht','ih.it_hw_type_id','iht.it_hw_type_id')
            ->join('it_software_license as isl','ih.it_hw_seq','isl.it_hw_id')
            ->join('it_software_license_handle as islh','isl.swl_id','islh.swl_id')
            ->join('it_location as il','islh.it_locat_id','il.it_locat_id')
            ->join('it_employee as ie','islh.it_emp_id','ie.it_emp_id')
            ->join('it_department as id','ie.it_dept_id','id.it_dept_id');
        
            if( !empty($this->search->emp_id) ) $sql = $sql->where('islh.it_emp_id',$this->search->emp_id);
            if( !empty($this->search->locat_id) ) $sql = $sql->where('islh.it_locat_id',$this->search->locat_id);
            if( !empty($this->search->hw_no) ) $sql = $sql->where('ih.it_hw_number','LIKE','%'.$this->search->hw_no.'%');
            if( !empty($this->search->hw_name) ) $sql = $sql->where('ih.it_hw_name','LIKE','%'.$this->search->hw_name.'%');
            if( !empty($this->search->hw_status) ) $sql = $sql->where('ih.it_hw_status',$this->search->hw_status);
            if( !empty($this->search->hw_group) ) $sql = $sql->where('ih.it_hw_group',$this->search->hw_group);
            if( !empty($this->search->type_id) ) $sql = $sql->where('iht.it_hw_type_id',$this->search->type_id);
            if( !empty($this->search->dept_id) ) $sql = $sql->where('id.it_dept_id',$this->search->dept_id);            
            if( $this->search->lc_status != 999 ) $sql = $sql->where('isl.swl_status',$this->search->lc_status);            
            if( $this->search->lc_handle != 999 ) $sql = $sql->where('islh.swlh_status',$this->search->lc_handle);
        
        $result = $sql->get();
        return $result;

    }

    public function headings(): array{
        return [
            'รหัสพนักงาน',
            'ชื่อ-สกุล',
            'ชื่อเล่น',
            'ฝ่าย',
            'Location',
            'Fixed asset number',
            'ประเภท',
            'Name',
            'หมวดหมู่',
            'License',
            'สถานะทรัพย์สิน',
            'สถานะ License',
            'สถานะกถือครอง',
            'วันที่เริ่มต้นใช้งาน',
            'วันที่สิ้นสุดการใช้งาน'
        ];
    }

    public function map($result): array{
        return [
            $result->emp_id,
            $result->fullname_th,
            $result->nickname_th,
            $result->dept_name,
            $result->locat_name,
            $result->hw_number,
            $result->hw_type,
            $result->hw_name,
            $result->hw_group,
            $result->swl_license,
            $result->hw_status,
            $result->swl_status,
            $result->swlh_status,
            $result->start_date,
            $result->end_date,
        ];
    }

    public function columnFormats(): array{
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'N' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'O' => NumberFormat::FORMAT_DATE_YYYYMMDD2
        ];
    }
}
