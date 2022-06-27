<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\LoginController;

class UserController extends Controller
{
    public function changePassword(){
        if( !LoginController::getLoginStatus() )
            return view('changepassword');
        else 
            return view('login');
    }
}
