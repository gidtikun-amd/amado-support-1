<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Exports\SummaryBudgetEqreqAll;
use App\Exports\SummaryBudgetEqreqDept;

class SummaryBudgetEqreqExport implements  WithMultipleSheets
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
        $date_from = date('Y-m-01',strtotime($this->search->date));
        $date_to = date('Y-m-t',strtotime($this->search->date));
        
        foreach($this->departments as $dept){
            
            if($counter == 0){
                $data = new SummaryBudgetEqreqAll([
                    'date_from' => $date_from,
                    'date_to' =>  $date_to 
                ]);
            }
            else{
                $data = new SummaryBudgetEqreqDept([
                    'dept_id' => $dept->id,
                    'dept_name' => $dept->name,
                    'date_from' => $date_from,
                    'date_to' =>  $date_to 
                ]);
            }
            
            $counter++;
            array_push($sheets,$data);
            
        }
        // dd($sheets);
        return $sheets;

    }

    private function getDepartment(){

        array_push($this->departments,(object) array('id' => 0, 'name' => 'All'));

        $dept = DB::table('it_department')
            ->select('it_dept_id as id','it_dept_name as name')
            ->orderBy('it_dept_id','ASC')
            ->get();
        
        foreach($dept as $val)
            array_push($this->departments,$val);

    }
    
}