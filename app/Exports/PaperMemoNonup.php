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

class PaperMemoNonup implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($params){
        $this->search = $params;
    }

    public function collection(){

        $cfiles = DB::table('it_memo_file')
            ->select('memo_id',DB::raw('count(mm_file_id) cfile'))
            ->groupBy('memo_id');

        $SubQueryInCFiles = $cfiles->toSql();
        $bindings = $cfiles->getBindings();

        foreach($bindings as $binding)
            $SubQueryInCFiles = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInCFiles, 1);

        $select = [
            'im.memo_no as no', 'im.memo_date as date', 'im.memo_object as object',
            'id.it_dept_name as deptname',
            DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as efullnameTh'),
            DB::raw('ifnull(sub1.assign_name,\'ไม่มีผู้ดำเนินการ\') assign_name'),
            DB::raw(
                'CASE WHEN im.memo_apprsts = 0 THEN \'รออนุมัติ\'
                    WHEN im.memo_apprsts = 1 AND im.memo_status = 1 THEN \'รอรับดำเนินการ\'
                    WHEN im.memo_apprsts = 1 AND im.memo_status = 2 THEN \'กำลังดำเนินการ\'
                    WHEN im.memo_apprsts = 1 AND im.memo_status = 3 THEN \'ดำเนินการเรียบร้อย\'
                    ELSE \'ไม่อนุมัติ\' END apprstsstr'
            )
        ]; 

        $sql = DB::table('it_memo as im')
            ->select(
                'im.memo_id as id', 'im.memo_no as no', 'im.memo_date as date', 'im.memo_object as object',
                'im.memo_status as status', 'im.memo_apprsts as apprsts',
                'im.memo_assign as assign',
                DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as efullnameTh')
            )
            ->join('it_employee as ie','im.it_emp_id','ie.it_emp_id')
            ->join('it_department as id','ie.it_dept_id','id.it_dept_id')
            ->leftJoin(DB::raw('('.$SubQueryInCFiles.') as f'),'im.memo_id','f.memo_id');
        
        $inna = DB::table('it_employee as innie')
        ->select(
            'innu.user',
            DB::raw('CONCAT(innie.it_emp_name_th,\' \',innie.it_emp_surname_th) as assign_name')
        )
        ->join('user as innu','innie.it_emp_id','innu.it_emp_id');

        $sql = $sql->leftJoin(DB::raw('('.$inna->toSql().') as sub1'),'im.memo_assign','sub1.user');
        $sql = $sql->select($select);

        if( $this->search->StatusPaper != 99 ) $sql = $sql->where('im.memo_status',$this->search->StatusPaper);
        else $sql = $sql->whereIn('im.memo_status',[2,3]);

        if( !empty($this->search->DateFrom) || !empty($this->search->DateTo) ){
            $from = !empty($this->search->DateFrom) ? $this->search->DateFrom : $this->search->DateTo;
            $to = !empty($this->search->DateTo) ? $this->search->DateTo : $this->search->DateFrom;
            $sql = $sql->whereBetween('im.memo_date',[$from,$to]);
        }

        $sql = $sql->where('im.memo_apprsts',1)
            ->orderBy('im.created_at','DESC');
        
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
            'สถานะ',
            'ผู้ดำเนินการ'
        ];
    }

    public function map($result): array{
        return [
            $result->no, /** A */
            $result->date, /** B */
            $result->efullnameTh, /** C */
            $result->deptname, /** D */
            $result->object, /** E */
            $result->apprstsstr, /** F */
            $result->assign_name /** G */
        ];
    }

    public function columnFormats(): array{
        return ['B' => NumberFormat::FORMAT_DATE_YYYYMMDD2];
    }
}
