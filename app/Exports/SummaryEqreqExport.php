<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Exports\SummaryEqreqDept;

class SummaryEqreqExport implements WithMultipleSheets
{
    use Exportable;
    private $search;
    private $departments = [];

    public function __construct($params){
        $this->search = $params;
        $this->getDepartment();
    }

    public function sheets(): array{

        $sheets = [];
        $counter = 0;

        foreach($this->departments as $dept){

            $param = [
                'dept_id' => $dept->id,
                'dept_name' => $dept->name,
                'date_from' => $this->search->date_from,                
                'date_to' => $this->search->date_to,
            ];

            $data = new SummaryEqreqDept($param);
            array_push($sheets,$data);
            $counter++;

        }

        return $sheets;
        
    }

    private function getDepartment(){

        array_push($this->departments,(object) array('id' => '', 'name' => 'ทั้งหมด'));

        $dept = DB::table('it_department')
            ->select('it_dept_id as id','it_dept_name as name')
            ->orderBy('it_dept_id','ASC')
            ->get();
        
        foreach($dept as $val)
            array_push($this->departments,$val);

    }

}
