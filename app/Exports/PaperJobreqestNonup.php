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

class PaperJobreqestNonup implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($params){
        $this->search = $params;
    }

    public function collection(){

        $cfiles = DB::table('it_jobreq_file')
            ->select('jobreq_id', DB::raw('count(jobreq_file_id) cfile'))
            ->groupBy('jobreq_id');

        $SubQueryInCFiles = $cfiles->toSql();
        $bindings = $cfiles->getBindings();

        foreach($bindings as $binding)
            $SubQueryInCFiles = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInCFiles, 1);

        $sql = DB::table('it_jobreq AS a')
            ->select(
                'a.jobreq_no', 'a.it_emp_position', 'c.it_dept_name',
                DB::raw('CONCAT(b.it_emp_name_th,\' \',b.it_emp_surname_th,\' (\',b.it_emp_nickname_th,\')\') emp_name'),
                DB::raw('DATE_FORMAT(a.jobreq_date,"%Y-%m-%d") jobreq_date'),
                DB::raw(
                    'CASE a.jobreq_obj
                        WHEN 1 THEN "สร้างผู้ใช้ใหม่"
                        WHEN 2 THEN "แก้ไขผู้ใช้งาน"
                        WHEN 3 THEN "ลบผู้ใช้งาน"
                        WHEN 4 THEN "พัฒนาใหม่"
                        WHEN 5 THEN "ซื้อ/ติดตั้งอุปกรณ์"
                        WHEN 6 THEN "สร้างอีเมลใหม่"
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
            ->leftJoin(DB::raw('('.$SubQueryInCFiles.') as f'),'a.jobreq_id','f.jobreq_id');

        if( $this->search->StatusPaper != 99 ) $sql = $sql->where('a.jobreq_status',$this->search->StatusPaper);
        else $sql = $sql->whereIn('a.jobreq_status',[2,3]);

        if( !empty($this->search->DateFrom) || !empty($this->search->DateTo) ){
            $from = !empty($this->search->DateFrom) ? $this->search->DateFrom : $this->search->DateTo;
            $to = !empty($this->search->DateTo) ? $this->search->DateTo : $this->search->DateFrom;
            $sql = $sql->whereBetween('a.jobreq_date',[$from,$to]);
        }
            
        $sql = $sql->whereNull('f.cfile')
            ->where('a.jobreq_status','!=',0)
            ->orderBy('a.jobreq_date','DESC')
            ->orderBy('a.jobreq_id','DESC');
            
        $result = $sql->get();
        return $result;

    }

    public function headings(): array {
        return [
            'เลขที่เอกสาร',
            'วันที่สร้าง',
            'ชื่อ-สกุล',
            'ฝ่าย',
            'ตำแหน่ง',
            'จุดประสงค์',
            'สถานะ'
        ];
    }

    public function map($result): array{
        return [
            $result->jobreq_no, /** A */
            $result->jobreq_date, /** B */
            $result->emp_name, /** C */
            $result->it_dept_name, /** D */
            $result->it_emp_position, /** E */
            $result->jobreq_obj_str, /** F */
            $result->jobreq_status_str, /** G */
        ];
    }

    public function columnFormats(): array{
        return ['B' => NumberFormat::FORMAT_DATE_YYYYMMDD2];
    }
}
