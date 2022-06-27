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

class SummaryBudgetEqreqDept implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping, WithTitle
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

        $result = DB::table('ad_equipreq as ae')
            ->select(
                'aed.item_budget as budget',
                DB::raw('CONCAT(ai.item_code,\' \',ai.item_name) as item'),
                DB::raw('sum(aed.eqreq_qty) as qty'),
                DB::raw('sum(aed.item_ttbudget) as ttbudget'),
                DB::raw('sum(aed.eqreq_sendto) as sendto'),
                DB::raw('sum(aed.eqreq_outstand) as outstand')
            )
            ->join('ad_equipreq_detail as aed','ae.eqreq_id','aed.eqreq_id')
            ->join('ad_items as ai','aed.item_id','ai.item_id')
            ->where('ae.it_dept_id',$this->dept_id)
            ->whereIn('ae.eqreq_status',[3,4])
            ->whereBetween('ae.eqreq_date',[$this->date_from,$this->date_to])
            ->groupBy('aed.item_id', 'ai.item_code', 'ai.item_name', 'aed.item_budget')
            ->orderBy('item','ASC')
            ->orderBy('budget','ASC')
            ->get();

        return $result;

    }

    public function headings(): array{
        return ['อุปกรณ์สำนักงาน','จำนวนเบิก','Budget','Budget รวม','รับแล้ว','ยังไม่ได้รับ'];
    }

    public function map($result): array{
        return [
            $result->item, 
            $result->qty, 
            $result->budget, 
            $result->ttbudget, 
            $result->sendto, 
            $result->outstand
        ];
    }

    public function columnFormats(): array{
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,            
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,            
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT
        ];
    }

    public function title(): string{
        return $this->dept_name;
    }
    
}
