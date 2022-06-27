<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\menus;
use App\pages;
use App\menus_role;
use App\pages_role;
use App\User;
use DB;

class manageRoleController extends Controller
{
    public function saveNewMenu(Request $request){

        $save = new menus([
                    'menu_name' => $request->name,
                    'menu_code' => $request->view,
                    'menu_group' => $request->group,
                    'menu_type' => $request->type,
                    'menu_host' => $request->host
                ]);

        $result = $save->save();
        return response()->json($result);

    }

    public function updateNewMenu(Request $request){

        $result = menus::where('menu_id',$request->id)
                    ->update([
                        'menu_name' => $request->name,
                        'menu_code' => $request->view,
                        'menu_group' => $request->group,
                        'menu_type' => $request->type,
                        'menu_host' => $request->host
                    ]);

        return response()->json($result);

    }

    public function deleteMenu($id){

        $menu = menus::where('menu_id',$id)->delete();

        if($menu){
            $role = menus_role::where('menu_id',$id)->delete();
            return response()->json(true);
        }
        else
            return response()->json(false);

    }

    public function getListMenu(){

        $menus = DB::table('menus as m1')
                    ->select(
                        'm1.menu_id','m1.menu_name','m1.menu_code','m1.menu_group',
                        'm1.menu_type as menu_types','m1.menu_host as menu_hosts',
                        DB::raw('0 as active'),
                        DB::raw('ifnull(m2.menu_name,\'\') as menu_host'),
                        DB::raw('CASE m1.menu_type WHEN \'main\'
                                THEN \'เมนูหลัก\' ELSE \'เมนูย่อย\' END as menu_type')
                    )
                    ->leftJoin('menus as m2','m1.menu_host','m2.menu_id')
                    ->orderBy('m1.menu_id')
                    ->get();

        return response()->json($menus);

    }

    public function getListMenuRole($id){

        $roles = DB::table('menus_role')
                    ->select('check_type','check_value',
                        DB::raw('0 as inactive'))
                    ->where('menu_id',$id)
                    ->orderBy('check_type','ASC')
                    ->orderBy('check_value','ASC')
                    ->get();

        return response()->json($roles);

    }

    public function saveMenuRole(Request $request){

        $save = new menus_role([
                    'menu_id' => $request->id,
                    'check_type' => $request->type,
                    'check_value' => $request->value
                ]);

        $result = $save->save();
        return response()->json(['status' => $result],200);

    }

    public function removeMenuRole(Request $request){

        $result = menus_role::where([
                        ['menu_id',$request->id],
                        ['check_type',$request->type],
                        ['check_value',$request->value]
                    ])
                    ->delete();

        return response()->json(['status' => $result],200);
    }

    public function saveNewPage(Request $request){

        $save = new pages([
                    'page_name' => $request->name,
                    'page_view' => $request->view,
                    'page_url' => $request->url
                ]);

        $result = $save->save();
        return response()->json($result);

    }

    public function updatePage(Request $request){

        $result = pages::where('page_id',$request->id)
                    ->update([
                        'page_name' => $request->name,
                        'page_view' => $request->view,
                        'page_url' => $request->url
                    ]);

        return response()->json($result);

    }

    public function deletePage($id){

        $page = pages::where('page_id',$id)->delete();

        if($page){
            $role = pages_role::where('page_id',$id)->delete();
            return response()->json(true);
        }
        else
            return response()->json(false);
    }

    public function getListPage(){

        $pages = DB::table('pages')
                ->select('page_id','page_name','page_url','page_view')
                ->orderBy('page_id')
                ->get();

        return response()->json($pages);

    }

    public function getListPageRole($id){

        $roles = DB::table('pages_role')
                    ->select(
                        'check_type','check_value',
                        'ac_read','ac_create','ac_update','ac_delete',
                        DB::raw('0 as active')
                    )
                    ->where('page_id',$id)
                    ->orderBy('check_type','ASC')
                    ->orderBy('check_value','ASC')
                    ->get();

        return response()->json($roles);

    }

    public function savePageRole(Request $request){

        $save = new pages_role([
                    'page_id' => $request->id,
                    'check_type' => $request->type,
                    'check_value' => $request->value,
                    'ac_read' => $request->read,
                    'ac_create' => $request->create,
                    'ac_update' => $request->update,
                    'ac_delete' => $request->delete
                ]);

        $result = $save->save();
        return response()->json($result);

    }

    public function removePageRole(Request $request){

        $result = pages_role::where([
                        ['page_id',$request->id],
                        ['check_type',$request->type],
                        ['check_value',$request->value]
                    ])
                    ->delete();

        return response()->json($result);

    }

    public function updatePageRole(Request $request){

        $result = pages_role::where([
                        ['page_id',$request->id],
                        ['check_type',$request->type],
                        ['check_value',$request->value]
                    ])
                    ->update([
                        'ac_read' => $request->read,
                        'ac_create' => $request->create,
                        'ac_update' => $request->update,
                        'ac_delete' => $request->delete
                    ]);

        return response()->json($result);

    }

    public function getUserData($id){

        $user = DB::table('it_employee as ie')
                ->select(
                    'ie.it_emp_id as id','id.it_dept_name as dept',
                    'ie.it_emp_active as active','ie.it_emp_nickname_th as nickname',
                    DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) as fullname')
                )
                ->join('it_department as id','ie.it_dept_id','id.it_dept_id')
                ->where('ie.it_emp_id',$id)
                ->first();

        $account = DB::table('user')
                    ->where('it_emp_id',$id)
                    ->get();

        $result = array('user' => $user, 'account' => $account);
        return response()->json($result);

    }

    public function takeActionUser(Request $request){

        if($request->method == 'add'){

            $user = new User([
                        'user' => $request->userId,
                        'password' => base64_encode('amado.1234'),
                        'identity' => $request->identity,
                        'role' => $request->role,
                        'it_emp_id' => $request->empId,
                        'password_expaired' => $request->expaire
                    ]);

            $result = $user->save();

        }
        elseif($request->method == 'update'){

            $result = User::where('user',$request->userId)
                        ->update([
                            'user' => $request->userId,
                            'identity' => $request->identity,
                            'role' => $request->role,
                            'it_emp_id' => $request->empId,
                            'password_expaired' => $request->expaire
                        ]);
        }

        return response()->json($result);

    }

    public function resetPassword(Request $request){

        $result = User::where('user',$request->userId)
            ->update([
                'password' => base64_encode('amado.1234'),
                'password_expaired' => '2020-01-01'
            ]);

        return response()->json($result);

    }
}
