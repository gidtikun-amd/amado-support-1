<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalenderController extends Controller
{

    private static $months_thai = [
        'full' => [
            "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", 
            "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
        ],
        'mini' => [
            "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", 
            "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."
        ],
        'miniNoDot' => [
            "มค", "กพ", "มีค", "เมย", "พค", "มิย", 
            "กค", "สค", "กย", "ตค", "พย", "ธค"
        ]       
    ];

    public static function getNameMonthAll($target){
        return $target == 'full' ? self::$months_thai['full'] : self::$months_thai['miniNoDot'];
    }

    public static function getFullnameMonthThai($date){
        $numMonth = (int) date('m',strtotime($date));
        return self::$months_thai['full'][$numMonth-1];
    }

    public static function getMininameMonthThai($date){
        $numMonth = (int) date('m',strtotime($date));
        return self::$months_thai['mini'][$numMonth-1];
    }

    public static function convertYearToBuddhistera($date){
        return date('Y',strtotime($date.' + 543 year'));
    }

}
