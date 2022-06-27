<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;

class itSurveyController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function adminSurveyTicketListView(){
        return view($this->policy->privilegePages('admin.ticket.survey'));
    }

    public function userSurveyTicketListView(){
        return view($this->policy->privilegePages('user.ticket.survey'));
    }
}
