<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;

class itItemRequestController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function index(){
        return view($this->policy->privilegePages('admin.itemreq.index'));
    }

    public function userCreateItemRequestView(){
        return view($this->policy->privilegePages('user.itemreq.send'));
    }

    public function userCreateItemRequestCRMView(){
        return view($this->policy->privilegePages('user.itemreq.sendcrm'));
    }

    public function userHistoryItemRequestView(){
        return view($this->policy->privilegePages('user.itemreq.history'));
    }

    public function userHistoryItemRequestCRMView(){
        return view($this->policy->privilegePages('user.itemreq.historycrm'));
    }

    public function adminHistoryItemRequestCRMView(){
        return view($this->policy->privilegePages('admin.itemreq.historycrm'));
    }

    public function accApproveItemRequestView(){
        return view($this->policy->privilegePages('user.itemreq.approve'));
    }

    public function recheckFileUploadItemReqView(){
        return view('admin.itemreq.files');
    }
}
