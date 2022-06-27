<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="{{ public_path('vendor/fontawesome/css/all.css') }}" rel="stylesheet">
<title>{{$paper_no}}</title>

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
    .fas {
        font-size: 0.8rem!important;
        line-height: 0rem!important;
    }
    table { width: 100%; }
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
    .bottom-dotted-170 {
        width: 170px;
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
    .bottom-dotted-390 {
        width: 390px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }
    .bottom-dotted-25 {
        width: 25px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }
    .bottom-dotted-50 {
        width: 50px;
        border-bottom: thin dotted;
        display: inline-block;
        line-height: 1rem;
    }
    .bottom-dotted-75 {
        width: 75px;
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
    .w-15-px {
        width: 5px;
    }
    .w-25-px {
        width: 25px;
    }
    .w-150-px {
        width: 300px;
    }
    td:empty::after{
        content: "\00a0";
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
            <p class="m-0"><b>ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน</b></p>
            <p class="m-0"><b>AMADO GROUP</b></p>
        </div>
        <div class="col-4 ml-5 mt-2">
{{--            <p class="m-0">--}}
{{--                <span>เลขที่เอกสาร</span>--}}
{{--                <span class="bottom-dotted-120 text-center">{{ $paper_no }}</span>--}}
{{--            </p>--}}
{{--            <p class="m-0">--}}
{{--                <span>วันที่</span>--}}
{{--                <span class="bottom-dotted-160 text-center">{{ $paper_date }}</span>--}}
{{--            </p>--}}
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <span>ผู้โอน : </span>
            <div class="bottom-dotted-170 text-center">
                <span>{{ $admin_name }}</span>
            </div>
        </div>
        <div class="col-8">
            <span>แผนก : </span>
            <div class="bottom-dotted-170 text-center">
                <span>{{ $admin_dept }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <span>ผู้รับโอน : </span>
            <div class="bottom-dotted-170 text-center">
                <span>{{ $emp_name }}</span>
            </div>
        </div>
        <div class="col-4">
            <span>แผนก : </span>
            <div class="bottom-dotted-170 text-center">
                <span>{{ $emp_dept }}</span>
            </div>
        </div>
        <div class="col-4">
            <span>วันที่ : </span>
            <div class="bottom-dotted-170 text-center">
                <span>{{ $paper_date }}</span>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <label>สถานะ</label>
            <span class="ml-3">
                <i class="far fa-square"></i>
                <label class="ml-1">ซื้อใหม่</label>
            </span>
            <span class="ml-3">
                <i class="far fa-square"></i>
                <label class="ml-1">โอนย้าย</label>
            </span>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <p class="m-0">
                <label>เรื่อง : การโอนเปลี่ยนผู้ควบคุมดูแลทรัพย์สิน</label>
            </p>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <p class="m-0">
                <span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ด้วย (ผู้โอน)
                    <div class="bottom-dotted-170 text-center"><span>{{ $admin_name }}</span></div>
                    มีความประสงค์ขอโอนทรัพย์สินเพื่อเปลี่ยนผู้ควบคุมดูแลรักษาทรัพย์สินตามรายละเอียดข้างล่างนี้ เนื่องจาก (ให้ระบุสาเหตุการเปลี่ยนผู้ควบคุมดูแลรักษาทรัพย์สิน)
                    <div class="bottom-dotted-390 text-center"><span></span></div>
                    โดยได้ทำการส่งมอบให้ผู้รับโอนวันที่
                    <div class="bottom-dotted-25 text-center"><span>{{$paper_day}}</span></div>
                    เดือน
                    <div class="bottom-dotted-75 text-center"><span>{{$paper_month}}</span></div>
                    พ.ศ.
                    <div class="bottom-dotted-50 text-center"><span>{{$paper_year}}</span></div>
                    เรียบร้อยแล้ว
                </span>
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <p class="m-0">
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับที่</th>
                            <th>รายการ</th>
                            <th>รายละเอียด<br>ชนิด/ขนาด/รุ่น/SN/สี</th>
                            <th>จำนวน<br>(หน่วย)</th>
                            <th>รายละเอียด<br>สภาพทรัพย์สิน</th>
                            <th>รหัสทรัพย์สิน</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $counter = 0;
                    @endphp
                    @foreach ($asset as $key=>$list)
                        {{$counter++}}
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <td class="w-150-px">{{$list->it_hw_name}}</td>
                            <td class="text-center">{{$list->it_hw_serial}}</td>
                            <td class="text-center">1 เครื่อง</td>
                            <td class="text-center">{{$list->it_locat_name}}</td>
                            <td class="text-center">{{$list->it_hw_number}}</td>
                        </tr>
                    @endforeach
                    @if ($counter < 10)
                        @for ($i = $counter+1; $i <= 10; $i++)
                        <tr>
                            <td class="text-center">{{$i}}</td>
                            <td class="w-150-px"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr>
                        @endfor
                    @endif
                    </tbody>
                </table>
            </p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <table>
                <tr>
                    <td class="w-50 text-center p-1 pl-3">
                        <p class="m-0 text-center mt-4"><span class="mr-2">_________________________________</span></p>
                        <p class="m-0 text-center mt-2"><span>ผู้โอน</span></p>
                        <p class="m-0 text-center"><span class="mr-2">วันที่ ......./......./..............</span></p>
                    </td>
                    <td class="w-50 text-center p-1 pl-3">
                        <p class="m-0 text-center mt-4"><span class="mr-2">_________________________________</span></p>
                        <p class="m-0 text-center mt-2"><span>ผู้รับโอน</span></p>
                        <p class="m-0 text-center"><span class="mr-2">วันที่ ......./......./..............</span></p>
                    </td>
                    <td class="w-50 text-center p-1 pl-3">
                        <p class="m-0 text-center mt-4"><span class="mr-2">_________________________________</span></p>
                        <p class="m-0 text-center mt-2"><span>เจ้าหน้าที่แผนกบัญชี</span></p>
                        <p class="m-0 text-center"><span class="mr-2">วันที่ ......./......./..............</span></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-8 pt-2">
            <p class="m-0 mt-4"><label>ผู้โอน <i class="fas fa-arrow-right"></i> ผู้รับโอน <i class="fas fa-arrow-right"></i> เจ้าหน้าที่แผนกบัญชี</label></p>
        </div>
        <div class="col-4">
            <p class="m-0">ต้นฉบับ - แผนกบัญชี</p>
            <p class="m-0">สำเนา 1 - ผู้โอน</p>
            <p class="m-0">สำเนา 2 - ผู้รับโอนเก็บไว้เป็นหลักฐาน</p>
        </div>
    </div>
    <span class="br-fixed text-right">AMD-AC-006 RV.2</span>
</body>
</html>
