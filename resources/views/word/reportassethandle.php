<?php

use App\Http\Controllers\Api\itReportController;
use App\Http\Controllers\Api\CalenderController;

$obj = new itReportController();
$month = $_REQUEST['month'];
$detail = $obj->setTransferAsset($month);
$filename = 'report_asset_'.date('y',strtotime($month)).'_'.date('m',strtotime($month));       
$month_name_th = CalenderController::getFullnameMonthThai($month);
$year_th = CalenderController::convertYearToBuddhistera($month);

$data = [
    'paper_no' => $filename,
    'month' => $month_name_th.' '.$year_th,
    'detail' => $detail
];


$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->setDefaultFontName('Angsana New');
$phpWord->setDefaultFontSize(14);
$section = $phpWord->addSection();

$section->addImage(
    'img/logo-red.png',
    array(
        'width' => 130,
        'height' => 40,
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
    )
);

$section->addText(
    'รายงานสรุปผลการดำเนินงาน',
    array('bold' => true),
    array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
);

$section->addText(
    'เรื่อง รายงานสรุปทรัพย์สินที่เกิดขึ้น เดือน '.$data['month'],
    array('bold' => true),
    array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
);

$lineStyle = array('weight' => 1, 'width' => 450, 'height' => 0, 'color' => 00000);
$section->addLine($lineStyle);

$section->addText(
    '       การดำเนินการที่เกี่ยวกับอุปกรณ์สารสนเทศมีตวามสำคัญที่เกิดชึ้นเกี่ยวกับทุกแผนกในบริษัท ซึ่งจำเป็นต้องประสาน งานกับผู้ใช้งานโดยเร็วเพื่อให้อุปกรณ์สารสนเทศมึความพร้อมใช้งานและจัดการกับทะเบียนทรัพย์สินจึงขอสรุปดังนี้'
);

$section->addText(
    '       1. การบริการที่เกิดขึ้น'
);

$section->addText(
    '              1.1 ด้านทะเบียนทรัพย์สิน'
);

foreach($data['detail'] as $val){
    $section->addText(
        '                     - '.$val->hw_ststxt.'              '.number_format($val->cseq,0).' รายการ'
    );
}

$section->addText('');
$section->addText('');
$section->addText(
    '       ลงชื่อ...............................................................ผู้จัดทำ                     ลงชื่อ...............................................................ผู้ตรวจ'
);$section->addText(
    '               (...............................................................)                                       (...............................................................)'
);

$filename = 'รายงานสรุปทรัพย์สินที่เกิดขึ้น เดือน '.$data['month'].'.docx';
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($filename);

require('download.php');
download($filename);

?>