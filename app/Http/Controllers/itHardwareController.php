<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;

class itHardwareController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function index(){
        return view($this->policy->privilegePages('admin.asset.index'));
    }

    public function show($id){
        return view($this->policy->privilegePages('admin.asset.detail'),compact('id'));
    }

    public function paper(){
        return view($this->policy->privilegePages('admin.asset.paper'));
    }
}
