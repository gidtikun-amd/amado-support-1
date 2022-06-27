<?php

use App\Http\Controllers\Api\itReportController;
use App\Http\Controllers\Api\CalenderController;

$obj = new itReportController();
$month = $_REQUEST['month'];
$detail = $obj->setProblemTicket($month);
$filename = 'report_problem_'.date('y',strtotime($month)).'_'.date('m',strtotime($month));       
$month_name_th = CalenderController::getFullnameMonthThai($month);
$year_th = CalenderController::convertYearToBuddhistera($month);

$data = [
    'paper_no' => $filename,
    'month' => $month_name_th.' '.$year_th,
    'detail' => $detail->ticket,
    'external' => $detail->external
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
    'เรื่อง สรุปปัญหาที่เกิดขึ้น เดือน '.$data['month'],
    array('bold' => true),
    array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
);

$lineStyle = array('weight' => 1, 'width' => 450, 'height' => 0, 'color' => 00000);
$section->addLine($lineStyle);

$section->addText(
    '       การดำเนินการเกี่ยวกับเรื่องปัญหาที่เกิดขึ้นมีความสำคัญอย่างมาก อีกทั้งปัญหาที่เกิดขึ้นเกี่ยวข้องกับทุกแผนกใน บริษัท ฯ ซึ่งจำเป็นต้องประสานงานแก้ไขและอำนวยความสะดวกกับผู้ใช้งานโดยเร็ว เพื่อให้อุปกรณ์มีความพร้อมใช้ งานสำหรับการทำงาน จึงขอสรุปปัญหาที่เกิดขึ้น ดังนี้'
);

$section->addText(
    '       1. ปัญหาที่เกิดขึ้นภายในบริษัท'
);

foreach($data['detail'] as $val){
    $section->addText(
        '              - '.$val->subject_name.'              '.number_format($val->ccase,0).' รายการ'
    );
}

$section->addText(
    '       2. ปัญหาที่เกิดขึ้นภายนอกบริษัท'
);

$extLine = 1;

foreach($data['external'] as $val){

    $section->addText(
        '              2.'.($extLine++).' '.$val['subject']
    );

    if(!empty($val['detail'])){
        foreach ($val['detail'] as $vald){
            $section->addText(
                '                     - '.$vald->title
            );
        }
    }
    else{

    }

}

$section->addText('');
$section->addText('');
$section->addText(
    '       ลงชื่อ...............................................................ผู้จัดทำ                     ลงชื่อ...............................................................ผู้ตรวจ'
);$section->addText(
    '               (...............................................................)                                       (...............................................................)'
);

$filename = 'สรุปปัญหาที่เกิดขึ้น เดือน '.$data['month'].'.docx';
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($filename);

require('download.php');
download($filename);

?>