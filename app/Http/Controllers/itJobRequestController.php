<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;

class itJobRequestController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function index(){
        return view($this->policy->privilegePages('admin.jobreq.index'));
    }

    public function userCreateJobReqView(){
        return view($this->policy->privilegePages('user.jobreq.send'));
    }

    public function uerHistoryJobReqView(){
        return view($this->policy->privilegePages('user.jobreq.history'));
    }

    public function uerSurveyJobReqView(){
        return view($this->policy->privilegePages('user.jobreq.survey'));
    }

    public function adminSurveyJobReqView(){
        return view($this->policy->privilegePages('admin.jobreq.survey'));
    }

    public function recheckFileUploadJobReqView(){
        return view($this->policy->privilegePages('admin.jobreq.files'));
    }

}
