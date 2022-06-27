<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;

class manageRoleController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function index(){
        return view($this->policy->privilegePages('admin.role.management'));
    }

    public function PagesAndMenu(){
        return view($this->policy->privilegePages('admin.role.pageandmenu'));
    }

    public function userIdeneityAndRole(){
        return view($this->policy->privilegePages('admin.role.user'));
    }
}
