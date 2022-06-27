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

class EmployeesListExport implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping
{

    use Exportable;
    
    public function collection()
    {
        
        $sql = DB::table('it_employee')
            ->select(
                'it_emp_id as id','it_emp_name_th as name','it_emp_surname_th as surname',
                DB::raw('CASE it_emp_active WHEN 1 THEN \'Active\' ELSE \'Inactive\' END as status')
            )
            ->orderBy('it_emp_id');

        $result = $sql->get();
        return $result;

    }

    public function headings(): array {
        return [
            'รหัสพนักงาน',
            'ชื่อ',
            'นามสกุล',
            'สถานะ'
        ];
    }

    public function map($result): array{
        return [
            $result->id,
            $result->name,
            $result->surname,
            $result->status
        ];
    }

    public function columnFormats(): array{
        return [
            'A' => NumberFormat::FORMAT_TEXT
        ];
    }
}
