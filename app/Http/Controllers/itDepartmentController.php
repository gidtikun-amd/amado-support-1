<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;

class itDepartmentController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function index(){
        return view($this->policy->privilegePages('admin.department.index'));
    }
}
