<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use DB;
use Session;
use Auth;
use App\User;

class LoginController extends Controller
{   

    protected $role;

    public function __construct(){
        if( !session()->has('active') || !session()->has('isLogin') )
            return response()->view('login', [], 500);
    }

    public function index(){
        if( session()->has('active') && session()->has('isLogin') ){
            if(session()->get('identity') == 'admin')
                return view('admin.index');
            else 
                return view('user.index');
        }
        else
            return view('login');
    }

    public function checkSessionExistFontEnd(){
        return response()->json(session()->has('active') && session()->has('isLogin') ? true : false);
    }
    
    public static function getLoginStatus(){
        return (!session()->has('active') && !session()->has('isLogin') ? true : false);
        // return (session()->has('active') && session()->has('isLogin') ? true : false);
    }

    public static function checkPasswordExpaired(){
        if( date('Y-m-d') >= session()->get('password_expaired') )
            return true;
    }

    private function checkExistUser($user){

        $count_user = User::where([
                ['user','=',$user->username],
                ['password','=',base64_encode($user->password)]
            ])
            ->count();
        
        return $count_user;
        
    }

    public function login(Request $request){
             
        if( $this->checkExistUser($request) <= 0 ) 
            return response()->json(['Status' => false]);           
        
        $user = DB::table('user AS usr')
                    ->select(
                        'usr.user','usr.identity','usr.role','usr.it_emp_id','usr.password_expaired',
                        'emp.it_dept_id','id.it_dept_name',
                        DB::raw('CONCAT(emp.it_emp_name_th," ",emp.it_emp_surname_th) AS emp_name')
                    )
                    ->join('it_employee AS emp','usr.it_emp_id','emp.it_emp_id')
                    ->join('it_department AS id','emp.it_dept_id','id.it_dept_id')
                    ->where('usr.user',$request->username)
                    ->first();
        
        session([
            'user' => $user->user,
            'userId' => $user->it_emp_id,
            'name' => $user->emp_name,
            'departmentId' => $user->it_dept_id,
            'department' => $user->it_dept_name,
            'identity' => $user->identity,
            'role' => $user->role,
            'password_expaired' => $user->password_expaired,
            'isLogin' => true,
            'active' => true
        ]);

        return response()->json(['Status' => true, 'session' => session()->all()]);   

    }

    public function logout(){
        session()->flush();
        return redirect('/login');
    }

    public function showSessionLogin(Request $request){
        dd(session()->all());
        // session()->flush();
        // return response()->view('login', [], 500);
    }

    private function checkOldPassword($user){

        $check_oldpass = User::where([
                ['user',$user->username],
                ['password',base64_encode($user->pass_old)]
            ])->count();

        return ( $check_oldpass > 0 ? true : false );

    }

    public function changePasswore(Request $request){
        if( $this->checkOldPassword($request) ){

            $new_date_expaired = date('Y-m-d', strtotime('+2 months'));
            session()->put('password_expaired',$new_date_expaired);

            $update = User::where('user',$request->username)
                        ->update([
                            'password' => base64_encode($request->pass_new),
                            'password_expaired' => $new_date_expaired
                        ]);
            
            return response()->json(['Status' => $update]);

        }
        else 
            return response()->json(['Status' => false]);
    }
   
}
