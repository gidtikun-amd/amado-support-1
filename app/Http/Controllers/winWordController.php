<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class winWordController extends Controller
{
    public function getReportTicket(){
        return view('word.reportticket');
    }

    public function getReportAssetHandle(){
        return view('word.reportassethandle');
    }
}
