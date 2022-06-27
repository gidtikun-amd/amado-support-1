<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;

class itMemoController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function userOpemMemoView(){
        return view($this->policy->privilegePages('user.memo.send'));
    }

    public function userHistoryMemoView(){
        return view($this->policy->privilegePages('user.memo.history'));
    }

    public function adminHostoryMemoView(){
        return view($this->policy->privilegePages('admin.memo.index'));
    }

    public function recheckFileUploadMemoView(){
        return view('admin.memo.files');
    }
}
