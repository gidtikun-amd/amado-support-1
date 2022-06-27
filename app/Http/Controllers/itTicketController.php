<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;

class itTicketController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function index(){
        return view($this->policy->privilegePages('admin.ticket.index'));
    }

    public function adminSubjectTicketView(){
        return view($this->policy->privilegePages('admin.ticket.subject'));
    }

    public function userCreateTicketView(){
        return view($this->policy->privilegePages('user.ticket.send'));
    }

    public function userHistoryTicketView(){
        return view($this->policy->privilegePages('user.ticket.history'));
    }
}
