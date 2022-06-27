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


class ReportAssethandleExport implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($params){
        $this->search = $params;
    }

    public function collection()
    {
        
        $sql = DB::table('it_asset_hardware AS hd')
            ->join('it_location AS lc','hd.it_locat_id','lc.it_locat_id')
            ->join('it_hardware AS hw','hd.it_hw_seq','hw.it_hw_seq')
            ->join('it_hardware_type AS hy','hw.it_hw_type_id','hy.it_hw_type_id')
            ->join('it_employee AS ep','hd.it_emp_id','ep.it_emp_id')
            ->join('it_department AS dp','ep.it_dept_id','dp.it_dept_id');
        
        $sql = $sql->select(
                    'hd.it_emp_id AS emp_id',
                    DB::raw('CONCAT(ep.it_emp_name_th," ",ep.it_emp_surname_th) AS fullname_th'),
                    DB::raw('CONCAT(ep.it_emp_name_eng," ",ep.it_emp_surname_eng) AS fullname_eng'),
                    'ep.it_emp_nickname_th AS nickname_th','ep.it_emp_nickname_eng AS nickname_eng',
                    'ep.it_emp_tel AS emp_tel',
                    'ep.it_emp_email_amd AS email_amd','ep.it_emp_email_second AS email_second',
                    'dp.it_dept_name AS dept_name','lc.it_locat_name AS locat_name',
                    'hw.it_hw_number AS hw_number','hy.it_hw_type_name AS type_name',
                    'hw.it_hw_name AS hw_name','hw.it_hw_serial AS hw_serial',
                    'hw.it_hw_price AS hw_price',
                    DB::raw('CASE hw.it_hw_status 
                                WHEN 1 THEN "ใช้งาน"
                                WHEN 2 THEN "สำรอง"
                                WHEN 3 THEN "ส่งซ่อม"
                                WHEN 4 THEN "ออกจำหน่าย"
                                WHEN 5 THEN "ยืมใช้งาน"
                                ELSE "ไม่ใช้งาน" END AS hw_status'),
                    DB::raw('CASE hw.it_hw_group
                                WHEN 1 THEN "ทรัพย์สินบุคคลถือคลอง"
                                ELSE "ทรัพย์สินส่วนกลางฝ่าย" END AS hw_group'),
                    DB::raw('CASE WHEN hd.it_asst_hw_start_date = "0000-00-00" 
                                THEN "" ELSE hd.it_asst_hw_start_date END AS start_date'),
                    DB::raw('CASE WHEN hd.it_asst_hw_end_date = "0000-00-00" 
                                THEN "" ELSE hd.it_asst_hw_end_date END AS end_date'),
                    'hd.it_asst_hw_remark AS hw_remark'
                );
        
        if( !empty($this->search->emp_id) ) $sql = $sql->where('hd.it_emp_id',$this->search->emp_id);
        if( !empty($this->search->locat_id) ) $sql = $sql->where('lc.it_locat_id',$this->search->locat_id);
        if( !empty($this->search->hw_no) ) $sql = $sql->where('hw.it_hw_number','LIKE','%'.$this->search->hw_no.'%');
        if( !empty($this->search->hw_name) ) $sql = $sql->where('hw.it_hw_name','LIKE','%'.$this->search->hw_name.'%');
        if( !empty($this->search->hw_serial) ) $sql = $sql->where('hw.it_hw_serial','LIKE','%'.$this->search->hw_serial.'%');  
        if( !empty($this->search->hw_status) ) $sql = $sql->where('hw.it_hw_status',$this->search->hw_status);
        if( !empty($this->search->hw_group) ) $sql = $sql->where('hw.it_hw_group',$this->search->hw_group);
        if( !empty($this->search->type_id) ) $sql = $sql->where('hy.it_hw_type_id',$this->search->type_id);
        if( !empty($this->search->dept_id) ) $sql = $sql->where('dp.it_dept_id',$this->search->dept_id);
        if( $this->search->emp_status != 99 ) $sql = $sql->where('ep.it_emp_active',$this->search->emp_status);
        
        $result = $sql->get();
        return $result;

    }

    public function headings(): array {
        return [
            'รหัสพนักงาน',
            'ชื่อ-สกุล(ไทย)',
            'ชื่อเล่น(ไทย)',
            'ชื่อ-สกุล(Eng)',
            'ชื่อเล่น(Eng)',
            'เบอร์ติดต่อ',
            'Email(Amado)',
            'Email(สำรอง)',
            'ฝ่าย',
            'Location',
            'Fixed asset number',
            'ประเภท',
            'Name',
            'Serial number',
            'ราคา',
            'Status',
            'หมวดหมู่',
            'วันที่เริ่มต้นใช้งาน',
            'วันที่สิ้นสุดการใช้งาน',
            'หมายเหตุ'
        ];
    }

    public function map($result): array{
        return [
            $result->emp_id,
            $result->fullname_th,
            $result->nickname_th,
            $result->fullname_eng,
            $result->nickname_eng, 
            $result->emp_tel, 
            $result->email_amd, 
            $result->email_second, 
            $result->dept_name, 
            $result->locat_name, 
            $result->hw_number, 
            $result->type_name, 
            $result->hw_name, 
            $result->hw_serial, 
            $result->hw_price, 
            $result->hw_status, 
            $result->hw_group, 
            $result->start_date, 
            $result->end_date, 
            $result->hw_remark 
        ];
    }

    public function columnFormats(): array{
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'N' => NumberFormat::FORMAT_TEXT,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'R' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'S' => NumberFormat::FORMAT_DATE_YYYYMMDD2
        ];
    }

}
