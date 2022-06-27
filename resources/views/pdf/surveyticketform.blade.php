<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="{{ public_path('vendor/fontawesome/css/all.css') }}" rel="stylesheet">
<title>{{ $ticket_no }}</title>

<style>
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: normal;
        src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: bold;
        src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'THSarabunNew';
        font-style: italic;
        font-weight: normal;
        src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'THSarabunNew';
        font-style: italic;
        font-weight: bold;
        src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
    }

    body {
        font-family: "THSarabunNew";
        line-height: 0.9rem;
        font-size: 1.1rem;
    }
    .row {  width: 100%; display: table; }
    .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 { display: table-cell }
    .col-1 { width: 8.333333333333333%; }
    .col-2 { width: 16.66666666666667%; }
    .col-3 { width: 25%; }
    .col-4 { width: 33.3333333333%; }
    .col-5 { width: 41.66666666666667%; }
    .col-6 { width: 50%; }
    .col-7 { width: 58.33333333333333%; }
    .col-8 { width: 66.66666666666667%; }
    .col-9 { width: 75%; }
    .col-10 { width: 83.33333333333333%; }
    .col-11 { width: 91.66666666666667%; }
    .col-12 { width: 100%; }
    .p-0 {  padding: 0 !important; }
    .p-1 {  padding: 0.25rem !important; }
    .p-2 {  padding: 0.5rem !important; }
    .p-3 {  padding: 1rem !important; }
    .p-4 {  padding: 1.5rem !important; }
    .p-5 {  padding: 3rem !important; }
    .pt-1 {  padding-top: 0.25rem !important; }
    .pt-2 {  padding-top: 0.5rem !important; }
    .pt-3 {  padding-top: 1rem !important; }
    .pt-4 {  padding-top: 1.5rem !important; }
    .pt-5 {  padding-top: 3rem !important; }
    .pb-1 {  padding-bottom: 0.25rem !important; }
    .pb-2 {  padding-bottom: 0.5rem !important; }
    .pb-3 {  padding-bottom: 1rem !important; }
    .pb-4 {  padding-bottom: 1.5rem !important; }
    .pb-5 {  padding-bottom: 3rem !important; }
    .pl-1 {  padding-left: 0.25rem !important; }
    .pl-2 {  padding-left: 0.5rem !important; }
    .pl-3 {  padding-left: 1rem !important; }
    .pl-4 {  padding-left: 1.5rem !important; }
    .pl-5 {  padding-left: 3rem !important; }
    .pr-1 {  padding-right: 0.25rem !important; }
    .pr-2 {  padding-right: 0.5rem !important; }
    .pr-3 {  padding-right: 1rem !important; }
    .pr-4 {  padding-right: 1.5rem !important; }
    .pr-5 {  padding-right: 3rem !important; }
    .m-0 {  margin: 0 !important; }
    .m-1 {  margin: 0.25rem !important; }
    .m-2 {  margin: 0.5rem !important; }
    .m-3 {  margin: 1rem !important; }
    .m-4 {  margin: 1.5rem !important; }
    .m-5 {  margin: 3rem !important; }
    .mt-1 {  margin-top: 0.25rem !important; }
    .mt-2 {  margin-top: 0.5rem !important; }
    .mt-3 {  margin-top: 1rem !important; }
    .mt-4 {  margin-top: 1.5rem !important; }
    .mt-5 {  margin-top: 3rem !important; }
    .mb-1 {  margin-bottom: 0.25rem !important; }
    .mb-2 {  margin-bottom: 0.5rem !important; }
    .mb-3 {  margin-bottom: 1rem !important; }
    .mb-4 {  margin-bottom: 1.5rem !important; }
    .mb-5 {  margin-bottom: 3rem !important; }
    .ml-1 {  margin-left: 0.25rem !important; }
    .ml-2 {  margin-left: 0.5rem !important; }
    .ml-3 {  margin-left: 1rem !important; }
    .ml-4 {  margin-left: 1.5rem !important; }
    .ml-5 {  margin-left: 3rem !important; }
    .mr-1 {  margin-right: 0.25rem !important; }
    .mr-2 {  margin-right: 0.5rem !important; }
    .mr-3 {  margin-right: 1rem !important; }
    .mr-4 {  margin-right: 1.5rem !important; }
    .mr-5 {  margin-right: 3rem !important; }
    .text-left { text-align: left !important; }
    .text-right { text-align: right !important; }
    .text-center { text-align: center !important; }
    .text-top { vertical-align: text-top; }
    .float-left { float: left !important; }
    .float-right { float: right !important; }
    .float-none { float: none !important; }
    .far {
        font-size: 0.8rem!important;
        line-height: 0rem!important;
    }
    table { width: 93%; }
    table, th, td {
        line-height: 0.9rem;
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 0 0.25rem;
    }
    hr {
        height:0px;
        border: none;
        background: #000;

    }
    .w-50 { width: 50%; }
    .w-100 { width: 100%; }
    .logo-amd { height: 50px; width: auto; }
    .bottom-dotted-120 {
        width: 120px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }
    .bottom-dotted-160 {
        width: 160px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }
    .bottom-dotted-190 {
        width: 190px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }
    .bottom-dotted-200 {
        width: 200px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }
    .bottom-dotted-400 {
        width: 400px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }
    .bottom-dotted-620 {
        width: 620px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }

    .bottom-dotted-565 {
        width: 565px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }
    .text-fa {
        font-size: 0.8rem;
    }
    .w-10-percent {
        width: 10%;
    }
    .w-15-px {
        width: 15px;
    }
    .w-20-px {
        width: 20px;
    }
    .w-25-px {
        width: 55px;
    }
    .w-35-px {
        width: 35px;
    }
    .w-75-px {
        width: 75px;
    }
    .w-275-px {
        width: 275px;
    }
    .br-fixed {
        position: fixed;
        bottom: 0;
        right: 0;
    }
</style>

</head>


<body>
    <div class="row mb-3 mt-2">
        <div class="col-4 text-left">
            <img class="logo-amd" src="{{ public_path('img/logo-red.png') }}" alt="">
        </div>
        <div class="col-4 text-center">
            <p class="m-0"><b>การประเมินความพึงพอใจในการแก้ไขปัญหา</b></p>
            <p class="m-0"><b>ด้านเทคโนโลยีสารสนเทศ</b></p>
        </div>
        <div class="col-4 ml-5 mt-2">
            <p class="m-0">
                <span>เลขที่เอกสาร</span>
                <span class="bottom-dotted-120 text-center" style="font-size:0.95rem;">{{ $survey_no }}</span>
            </p>
            <p class="m-0">
                <span>วันที่</span>
                <span class="bottom-dotted-160 text-center">{{ $survey_date }}</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <span>ชื่อ-นามสกุล (T)</span>
            <div class="bottom-dotted-620">
                <span class="ml-4">{{ $emp_fullname_th }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <span>ชื่อ-นามสกุล (E)</span>
            <div class="bottom-dotted-620">
                <span class="ml-4">{{ $emp_fullname_en }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <span>แผนก</span>
            <div class="bottom-dotted-200">
                <span class="ml-4">{{ $emp_department }}</span>
            </div>
        </div>
        <div class="col-4">
            <span>ตำแหน่ง</span>
            <div class="bottom-dotted-190">
                <span class="ml-4">{{ $emp_position }}</span>
            </div>
        </div>
        <div class="col-4">
            <span>เบอร์โทรศัพท์</span>
            <div class="bottom-dotted-160">
                <span class="ml-4">{{ $emp_tel }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <span>แก้ไขปัญหาเรื่อง</span>
            <div class="bottom-dotted-620">
                <span class="ml-4">{{ $ticket_subject }}</span>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <span><b>การประเมินความพึงพอใจในการแก้ไขปัญหา</b></span>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table>
                <thead>
                    <tr>
                        <th class="text-center">ลำดับ</th>
                        <th class="text-center">รายละเอียด</th>
                        <th class="text-center">มากที่สุด</th>
                        <th class="text-center">มาก</th>
                        <th class="text-center">ปานกลาง</th>
                        <th class="text-center">น้อย</th>
                        <th class="text-center">น้อยที่สุด</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($survey_score as $key=>$val)
                    <tr>
                        <td class="text-center w-15-px">{{$key+1}}</td>
                        <td class="w-275-px">{{$val->subject_text}}</td>
                        <td class="text-center pl-3 w-25-px">
                            @if($val->survey_scores == 5)
                            <span class="fas fa-check text-fa ml-3"></span>
                            @endif
                        </td>
                        <td class="text-center pl-3 w-25-px">
                            @if($val->survey_scores == 4)
                            <span class="fas fa-check text-fa ml-3"></span>
                            @endif
                        </td>
                        <td class="text-center pl-3 w-25-px">
                            @if($val->survey_scores == 3)
                            <span class="fas fa-check text-fa ml-3"></span>
                            @endif
                        </td>
                        <td class="text-center pl-3 w-25-px">
                            @if($val->survey_scores == 2)
                            <span class="fas fa-check text-fa ml-3"></span>
                            @endif
                        </td>
                        <td class="text-center pl-3 w-25-px">
                            @if($val->survey_scores == 1)
                            <span class="fas fa-check text-fa ml-3"></span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <span><b>คุณพึงพอใจในการแก้ไขปัญหาครั้งนี้ขนาดใด</b></span>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="w-100">
                    <tr>
                        <th class="text-center">มากที่สุด</th>
                        <th class="text-center">มาก</th>
                        <th class="text-center">ปานกลาง</th>
                        <th class="text-center">น้อย</th>
                        <th class="text-center">น้อยที่สุด</th>
                    </tr>
                    <tr>
                        <td class="w-10-percent pl-2">
                            @if($survey_score_overall == 5)
                            <span class="fas fa-check text-fa ml-5 mt-2 mb-2"></span>
                            @endif
                        </td>
                        <td class="w-10-percent pl-2">
                            @if($survey_score_overall == 4)
                            <span class="fas fa-check text-fa ml-5 mt-2 mb-2"></span>
                            @endif
                        </td>
                        <td class="w-10-percent pl-2">
                            @if($survey_score_overall == 3)
                            <span class="fas fa-check text-fa ml-5 mt-2 mb-2"></span>
                            @endif
                        </td>
                        <td class="w-10-percent pl-2">
                            @if($survey_score_overall == 2)
                            <span class="fas fa-check text-fa ml-5 mt-2 mb-2"></span>
                            @endif
                        </td>
                        <td class="w-10-percent pl-2">
                            @if($survey_score_overall == 1)
                            <span class="fas fa-check text-fa ml-5 mt-2 mb-2"></span>
                            @endif
                        </td>
                    </tr>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <span><b>ข้อเสนอแนะ</b></span>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <span>{{ $survey_remark }}</span>
            {{-- <span>-</span> --}}
        </div>
    </div>
    <div class="row pt-5">
        <div class="col-6">
            <table class="w-100">
                <tr>
                    <td class="w-50 text-center text-top p-3 pb-4">
                        <span>ตรวจสอบการแก้ไขเรียบร้อย</span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <p class="m-0 text-center"><span class="mr-2">{{ $emp_fullname_th }}  ผู้ประเมิน</span></p>
            <p class="m-0 text-center"><span class="mr-2">วันที่ {{ $survey_date }}</span></p>
        </div>
    </div>
    <span class="br-fixed text-right">AMD-IT-013 RV.00</span>
</body>
</html>
