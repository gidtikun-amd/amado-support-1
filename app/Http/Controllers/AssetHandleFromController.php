<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\itAssetHardwareController;
use PDF;

class AssetHandleFromController extends Controller
{
    
    public function pdf_handle($id){        
        $object = new itAssetHardwareController();
        $data = $object->dataPaperHandle($id);
        $pdf = PDF::loadView('pdf.transferassetform', $data) ->setPaper('a4');
        return @$pdf->stream($data->paper_no.'.pdf');
    }

    public function pdf_borrow($id){
        $object = new itAssetHardwareController();
        $data = $object->dataPaperBorrow($id);
        $pdf = PDF::loadView('pdf.borrowassetform', $data) ->setPaper('a4');
        return @$pdf->stream($data->paper_no.'.pdf');
    }

}
