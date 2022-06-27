<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\User;
use DB;

class UserController extends Controller
{
    public function resetPassword($id){

        $user = User::where('user',$id)
                    ->update([
                        'password' => base64_encode('amado.1234'),
                        'password_expaired' => date('Y-m-d')
                    ]);
                    
        return response()->json($user);         

    }

    public function getUserAdminMIS(){

        $user = DB::table('user as u')
                ->select(
                    'u.user', 'ie.it_emp_nickname_th as nickname', 
                    DB::raw('concat(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as fullnameTh')
                )
                ->join('it_employee as ie','u.it_emp_id','ie.it_emp_id')
                ->where([
                    ['u.identity','admin'],
                    ['ie.it_dept_id',11]
                ])
                ->orderBy('fullnameTh')
                ->get();

        return response()->json($user); 
    }
}
