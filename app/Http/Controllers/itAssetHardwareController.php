<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class itAssetHardwareController extends Controller
{
    public function index(){
        return view('user.asset.index');
    }
}
