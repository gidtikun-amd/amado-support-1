<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class SummaryEqreqDept implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping, WithTitle
{
    use Exportable;
    private $dept_id;
    private $dept_name;
    private $date_from;
    private $date_to;

    public function __construct($dataset){
        $this->dept_id = $dataset['dept_id'];
        $this->dept_name = $dataset['dept_name'];
        $this->date_from = $dataset['date_from'];
        $this->date_to = $dataset['date_to'];
    }

    public function collection(){

        $eqreq = DB::table('ad_equipreq')
            ->select('eqreq_id')
            ->whereIN('eqreq_status',[2,3,4])
            ->whereBetween('eqreq_date',[$this->date_from,$this->date_to]);

        if(!empty($this->dept_id))
            $eqreq = $eqreq->where('it_dept_id',$this->dept_id);

        $SubQueryInEqreq = $eqreq->toSql();
        $bindings = $eqreq->getBindings();

        foreach($bindings as $binding)
            $SubQueryInEqreq = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInEqreq, 1);

        $result = DB::table('ad_equipreq_detail as aed')
            ->select(
                'ai.item_code as code', 'ai.item_name as name',
                'ai.item_unit as unit',
                DB::raw('sum(aed.eqreq_qty) as qty'),
                DB::raw('sum(aed.eqreq_sendto) as sendto'),
                DB::raw('sum(aed.eqreq_outstand) as outstand')
            )
            ->join(DB::raw('('.$SubQueryInEqreq.') as ae'),'aed.eqreq_id','ae.eqreq_id')
            ->join('ad_items as ai','aed.item_id','ai.item_id')
            ->groupBy('aed.item_id','ai.item_code','ai.item_name','ai.item_unit')
            ->orderBy('ai.item_code')
            ->get();

        return $result;

    }

    public function headings(): array{
        return [
            'อุปกรณ์สำนักงาน',
            'จำนวนเบิก',
            'รับแล้ว',
            'ยังไม่ได้รับ',
        ];
    }

    public function map($result): array{
        return [
            $result->code.' '.$result->name,
            $result->qty,
            $result->sendto,
            $result->outstand,            
        ];
    }

    public function columnFormats(): array{
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string{
        return $this->dept_name;
    }
}
