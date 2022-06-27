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

class PaperItemrequestNonup implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($params){
        $this->search = $params;
    }

    public function collection(){

        $cfiles = DB::table('it_itmreq_file')
            ->select('itmreq_id',DB::raw('count(itmreq_file_id) cfile'))
            ->groupBy('itmreq_id');

        $SubQueryInCFiles = $cfiles->toSql();
        $bindings = $cfiles->getBindings();

        foreach($bindings as $binding)
            $SubQueryInCFiles = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInCFiles, 1);

        $sql = DB::table('it_itmreq AS ii')
            ->select(
                'ii.itmreq_no','ii.itmreq_date',
                'ii.itmreq_obj','ii.it_emp_position','id.it_dept_name',
                DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) emp_fullname'),
                DB::raw(
                    'CASE ii.itmreq_status
                        WHEN 1 THEN "รอดำเนินการ"
                        WHEN 2 THEN "กำลังดำเนินการ"
                        WHEN 3 THEN "ดำเนินการเรียบร้อย"
                        ELSE "ยกเลิก" END itmreq_status_str'
                )
            )
            ->join('it_employee AS ie','ii.it_emp_id','ie.it_emp_id')
            ->join('it_department AS id','ie.it_dept_id','id.it_dept_id')
            ->leftJoin(DB::raw('('.$SubQueryInCFiles.') as f'),'ii.itmreq_id','f.itmreq_id');
        
        if( $this->search->StatusPaper != 99 ) $sql = $sql->where('ii.itmreq_status',$this->search->StatusPaper);
        else $sql = $sql->whereIn('ii.itmreq_status',[2,3]);

        if( !empty($this->search->DateFrom) || !empty($this->search->DateTo) ){
            $from = !empty($this->search->DateFrom) ? $this->search->DateFrom : $this->search->DateTo;
            $to = !empty($this->search->DateTo) ? $this->search->DateTo : $this->search->DateFrom;
            $sql = $sql->whereBetween('ii.itmreq_date',[$from,$to]);
        }

        $sql = $sql->where('ii.itmreq_approve_sts',1)
            ->whereNull('f.cfile')
            ->orderBy('ii.created_at','DESC');

        $result = $sql->get();
        return $result;

    }

    public function headings(): array {
        return [
            'เลขที่เอกสาร',
            'วันที่สร้าง',
            'ชื่อ-สกุล',
            'ฝ่าย',
            'จุดประสงค์',
            'สถานะ'
        ];
    }

    public function map($result): array{
        return [
            $result->itmreq_no, /** A */
            $result->itmreq_date, /** B */
            $result->emp_fullname, /** C */
            $result->it_dept_name, /** D */
            $result->itmreq_obj, /** E */
            $result->itmreq_status_str, /** F */
        ];
    }

    public function columnFormats(): array{
        return ['B' => NumberFormat::FORMAT_DATE_YYYYMMDD2];
    }
}
