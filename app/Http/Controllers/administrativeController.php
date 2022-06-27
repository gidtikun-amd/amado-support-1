<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;

class administrativeController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function userCreateEquReq(){
        return view($this->policy->privilegePages('administrative.equipmentreq.send'));
    }

    public function userHistoryEquReq(){
        return view($this->policy->privilegePages('administrative.equipmentreq.history'));
    }

    public function adminManagementEquReq(){
        return view($this->policy->privilegePages('administrative.equipmentreq.management'));
    }

    public function adminManagementItems(){
        return view($this->policy->privilegePages('administrative.equipmentreq.items'));
    }

    public function adminManagementJobReq(){
        return view($this->policy->privilegePages('administrative.jobreq.management'));
    }

    public function userCreateJobReq(){
        return view($this->policy->privilegePages('administrative.jobreq.send'));
    }

    public function userHistoryJobReq(){
        return view($this->policy->privilegePages('administrative.jobreq.history'));
    }

    public function reportSummaryEqreq(){
        return view($this->policy->privilegePages('administrative.report.summaryEqreq'));
    }

    public function reportSummaryBudgetEqreq(){
        return view($this->policy->privilegePages('administrative.report.summaryBudgetEqreq'));
    }
}
