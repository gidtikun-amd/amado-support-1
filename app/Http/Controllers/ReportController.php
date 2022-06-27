<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Api\itReportController;
use App\Http\Controllers\Api\CalenderController;
use App\Exports\ReportAssethandleExport;
use Excel;
use PDF;

class ReportController extends Controller
{
    private $policy;

    public function __construct(){
        $this->policy = new RoleController;
    }

    public function viewReportAdminAssetHandel(){
        return view($this->policy->privilegePages('admin.report.asset'));
    }

    public function viewReportAdminLicenseHandel(){
        return view('admin.report.license');
    }

    public function viewReportAdminProblemTicket(){
        return view($this->policy->privilegePages('admin.report.problemticket'));
    }

    public function viewReportAdminProblemTransferAsset(){
        return view($this->policy->privilegePages('admin.report.problemtransferasset'));
    }

    public function viewReportAdminSummarySupport(){
        return view($this->policy->privilegePages('admin.report.summarysupport'));
    }

    public function exportReportAssetHandle(Request $request){
        return (new ReportAssethandleExport($request))->download('Hardware_Asset_Information.xlsx');
    }

    Public function viewReportItException(){
        return view('admin.report.exception');
    }

    Public function viewReportAdmminPaperFiles(){
        return view($this->policy->privilegePages('admin.report.nonuppaperfiles'));
    }

    public function paperReportProblemTicket($month){

        $obj = new itReportController();
        $detail = $obj->setProblemTicket($month);
        $filename = 'report_problem_'.date('y',strtotime($month)).'_'.date('m',strtotime($month));       
        $month_name_th = CalenderController::getFullnameMonthThai($month);
        $year_th = CalenderController::convertYearToBuddhistera($month);

        $data = [
            'paper_no' => $filename,
            'month' => $month_name_th.' '.$year_th,
            'detail' => $detail->ticket,
            'external' => $detail->external,
            'systemsts' => $detail->system
        ];
        
        $pdf = PDF::loadView('pdf.reportticket', $data) ->setPaper('a4');
        return @$pdf->stream($filename.'.pdf');

    }
    
    public function paperReportAssetHandle($month){

        $obj = new itReportController();
        $detail = $obj->setTransferAsset($month);
        $filename = 'report_asset_'.date('y',strtotime($month)).'_'.date('m',strtotime($month));       
        $month_name_th = CalenderController::getFullnameMonthThai($month);
        $year_th = CalenderController::convertYearToBuddhistera($month);

        $data = [
            'paper_no' => $filename,
            'month' => $month_name_th.' '.$year_th,
            'detail' => $detail
        ];
        
        $pdf = PDF::loadView('pdf.reportassethandle', $data) ->setPaper('a4');
        return @$pdf->stream($filename.'.pdf');

    }
}
