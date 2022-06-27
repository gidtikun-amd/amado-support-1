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

class SummaryBudgetEqreqAll implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping, WithTitle
{ 
    use Exportable;
    private $date_from;
    private $date_to;

    public function __construct($dataset){
        $this->date_from = $dataset['date_from'];
        $this->date_to = $dataset['date_to'];
    }

    public function collection(){
        $eqreq = DB::table('ad_equipreq')
            ->select('eqreq_id', 'it_dept_id', 'eqreq_netbudget')
            ->where([
                ['eqreq_approve_status', '=', '1'],
                ['eqreq_status', '<>', '0']
            ])
            ->whereBetween('eqreq_date',[$this->date_from, $this->date_to]);

        $SubQueryInEqreq = $eqreq->toSql();
        $bindings = $eqreq->getBindings();

        foreach($bindings as $binding)
            $SubQueryInEqreq = preg_replace('/\?/', "'" . $binding ."'", $SubQueryInEqreq, 1);

        $result = DB::table('it_department as id')
            ->select(
                'id.it_dept_name as name',
                DB::raw('ifnull(sum(ae.eqreq_netbudget),0.00) as budget')
            )
            ->leftJoin(DB::raw('('.$SubQueryInEqreq.') as ae'),'id.it_dept_id','ae.it_dept_id')
            ->groupBy('id.it_dept_id', 'id.it_dept_name')
            ->orderBy('name','ASC')
            ->get();

        return $result;
    }

    public function headings(): array{
        return ['แผนก','ใช้ Budget'];
    }

    public function map($result): array{
        return [$result->name, $result->budget];
    }

    public function columnFormats(): array{
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
        ];
    }

    public function title(): string{
        return 'สรุป Budget แต่ละแผนก';
    }
}
