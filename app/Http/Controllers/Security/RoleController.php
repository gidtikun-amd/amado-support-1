<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\LoginController;
use App\pages;
use App\pages_role;
use App\menus;
use App\menus_role;
use Session;
use DB;

class RoleController extends Controller
{

    private $role;

    private function getRedirectByIdentity(){
        if( session()->get('identity') == 'admin' )
            return 'admin.index';
        elseif( session()->get('identity') == 'user' )
            return 'user.index';
    }

    public function privilegePages($view){

        if( LoginController::getLoginStatus() )
            return 'login';

        if( LoginController::checkPasswordExpaired() )
            return 'changepassword';

        switch($view){
            /** page mis group */
            case 'admin.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.department.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.employee.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.asset.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.asset.detail': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.asset.paper': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.asset.swlicense': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.asset.swldetail': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.assettype.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.location.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.jobreq.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.report.asset': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.report.problemticket': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.report.summarysupport': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.report.problemtransferasset': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.itemreq.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.itemreq.historycrm': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.ticket.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.ticket.survey': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.ticket.subject': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.role.management': return ( $this->checkPrivilege($view,'pages','fixed') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.role.pageandmenu': return ( $this->checkPrivilege($view,'pages','fixed') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.role.user': return ( $this->checkPrivilege($view,'pages','fixed') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.it.memo.main': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.memo.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.memo.files': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.itemreq.files': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.jobreq.files': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.jobreq.survey': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.externalpb.index': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'admin.externalpb.sysstatus': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;            
            case 'admin.report.nonuppaperfiles': return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;

            case 'user.index': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.jobreq.send': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.jobreq.history': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.jobreq.survey': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.itemreq.send': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.itemreq.sendcrm': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.itemreq.history': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.itemreq.historycrm': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.itemreq.approve':
                if(
                    $this->checkPrivilege($view,'pages','identity') &&
                    $this->checkPrivilege($view,'pages','department')
                )
                    return $view;
                else
                    return $this->getRedirectByIdentity();
            break;
            case 'user.ticket.send':  return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.ticket.history': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.ticket.survey': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.memo.send': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'user.memo.history': return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;

            /** page administrative group */
            case 'administrative.equipmentreq.send':  return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'administrative.equipmentreq.history':  return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'administrative.equipmentreq.management':  return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'administrative.equipmentreq.items':  return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'administrative.jobreq.management':  return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'administrative.jobreq.send':  return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'administrative.jobreq.history':  return ( $this->checkPrivilege($view,'pages','identity') ? $view : $this->getRedirectByIdentity() ); break;
            case 'administrative.report.summaryBudgetEqreq':  return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;
            case 'administrative.report.summaryEqreq':  return ( $this->checkPrivilege($view,'pages','role') ? $view : $this->getRedirectByIdentity() ); break;

        }

    }

    public function privilegeMenus($menu){
        switch($menu){
            /** ADMIN MENUS */
            case 'admin.it.home': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.maindata.main': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'admin.maindata.location': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.maindata.assettype': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.maindata.department': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.maindata.employee': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.asset.main': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.asset.manage': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.asset.paper': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.asset.swlicense': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.jobreq.main': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.jobreq.list': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.report.main': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'admin.report.asset': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.report.problemticket': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.report.problemtransferasset': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.report.summarysupport': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.report.exception': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.itmreq.main': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.itmreq.list': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.ticket.main': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.ticket.list': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.ticket.saform': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.ticket.subject': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.role.main': return $this->checkPrivilege($menu,'menus','fixed'); break;
            case 'admin.it.memo.main': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.it.memo.list': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.it.extpb': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.it.syssts': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'admin.report.nonuppaperfiles': return $this->checkPrivilege($menu,'menus','role'); break;
            /** USER MENUS GROUP MIS */
            case 'user.home': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.jobreq.main': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.jobreq.create': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.jobreq.history': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.itmreq.main': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.itmreq.create': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.itmreq.createcrm': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.itmreq.history': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.itmreq.approve': return $this->checkPrivilege($menu,'menus','department'); break;
            case 'user.ticket.main': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.ticket.create': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.ticket.history': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.ticket.saform': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.itmemo.main': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.itmemo.create': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.itmemo.history': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.crm.itmreq.create': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.crm.itmreq.history': return $this->checkPrivilege($menu,'menus','identity'); break;
            /** USER MENUS GROUP ADMINISTRATIVE */
            case 'user.ad.equreq.main': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.ad.equreq.create': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.ad.equreq.history': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.ad.jobreq.main': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.ad.jobreq.create': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.ad.jobreq.history': return $this->checkPrivilege($menu,'menus','identity'); break;
            case 'user.admin.group': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'user.ad.equ.main': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'user.ad.equ.item': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'user.ad.equ.req': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'user.ad.equ.job.main': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'user.ad.equ.job.list': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'user.ad.report.main': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'user.ad.report.sbger': return $this->checkPrivilege($menu,'menus','role'); break;
            case 'user.ad.report.ser': return $this->checkPrivilege($menu,'menus','role'); break;
        }
    }

    private function checkPrivilege($code,$group,$part){

        if( $group == 'pages' ){
            $count = DB::table('pages_role AS r')
                        ->join('pages AS p','r.page_id','p.page_id')
                        ->where('p.page_view',$code);
        }
        else{
            $count = DB::table('menus_role AS r')
                        ->join('menus AS p','r.menu_id','p.menu_id')
                        ->where('p.menu_code',$code);
        }

        $count = $count->where('r.check_type',$part);

        if( $part == 'identity' ) $count = $count->where('r.check_value',session()->get('identity'));
        elseif( $part == 'role' ) $count = $count->where('r.check_value',session()->get('role'));
        elseif( $part == 'user' || $part == 'fixed' ) $count = $count->where('r.check_value',session()->get('user'));
        elseif( $part == 'department' ) $count = $count->where('r.check_value',session()->get('departmentId'));

        $count = $count->count();

        return ( $count > 0 ? true : false );

    }

    public function getRoleMenuUser(Request $request){

        $user = DB::table('user AS u')
                    ->select(
                        'u.user','u.identity','u.role','ie.it_dept_id',
                        DB::raw('CONCAT(ie.it_emp_name_th,\' \',ie.it_emp_surname_th) AS emp_name_th')
                    )
                    ->join('it_employee AS ie','u.it_emp_id','ie.it_emp_id')
                    ->where([
                        ['u.it_emp_id',$request->emp_name],
                        ['u.identity','user']
                    ])
                    ->first();

        $menu = DB::table('menus_role AS mr')
                    ->select(
                        'm.menu_name','m.menu_group',
                        DB::raw('CASE m.menu_type WHEN \'main\' THEN \'เมนูหลัก\' ELSE \'เมนูย่อย\' END AS menu_type'),
                        'mj.menu_name AS menu_main'
                    )
                    ->join('menus AS m','mr.menu_id','m.menu_id')
                    ->leftJoin('menus AS mj','m.menu_host','mj.menu_id')
                    ->where('mr.check_value',$user->user)
                    ->orWhere('mr.check_value',$user->identity)
                    ->orWhere('mr.check_value',$user->role)
                    ->orWhere('mr.check_value',$user->it_dept_id)
                    ->get();

        $page = DB::table('pages_role AS pr')
                    ->select(
                        'p.page_name','p.page_url',
                        'pr.ac_read','pr.ac_create','pr.ac_update','pr.ac_delete'
                    )
                    ->join('pages AS p','pr.page_id','p.page_id')
                    ->where('pr.check_value',$user->user)
                    ->orWhere('pr.check_value',$user->identity)
                    ->orWhere('pr.check_value',$user->role)
                    ->orWhere('pr.check_value',$user->it_dept_id)
                    ->get();

        return response()->json([
            'user' => $user->user,
            'emp_name' => $user->emp_name_th,
            'menus' => $menu,
            'pages' => $page
        ]);

    }

}
