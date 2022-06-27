<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\exception_log;

class exceptionController extends Controller
{
    public function saveLogQueryException($dataset){
        
        $log = new exception_log([
            'ex_module' => $dataset['module'], 
            'ex_action' => $dataset['action'], 
            'ex_message' => $dataset['message']
        ]);

        $log->save();

    }

    public function getListLogException(){

        $data = DB::table('exception_log')
            ->select(
                'ex_module as module','ex_action as action',
                'ex_message as message','created_at as at'
            )
            ->orderBy('created_at','DESC')
            ->paginate(20);

        return response()->json($data);

    }
}
