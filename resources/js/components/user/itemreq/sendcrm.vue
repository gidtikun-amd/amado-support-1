<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fab fa-wpforms"></i><span class="ml-2">Item Request Form</span>
            </div>
            <div class="card-body p-5">

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="row m-0 mb-3">
                            <div class="col-md-4">
                                <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>ITEM REQUEST FORM ( AMADO CRM )</b></p>
                                <p><b>AMADO GROUP</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">เลขที่เอกสาร</div>
                                    <div class="bottom-dotted-125 text-center">
                                        <input type="text" class="form-control" value="Auto Generate" readonly>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-125 text-center">
                                        <input type="text" class="form-control"
                                               v-model="paper.header.today"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-8 my-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อ-นามสกุล</div>
                                    <div class="bottom-dotted-650 ">
                                        <span class="ml-5" v-html="paper.header.emp_fullname">Fullname</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <input v-model="paper.header.emp_position" type="text" class="form-control bottom-dotted-290 text-center"  placeholder="กรอกตำแหน่ง..">
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-4">
                            <div class="col-md-auto">
                                <div class="mt-2">
                                    <div class="mr-1 d-inline-block">จุดประสงค์ในการดำเนินการ</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <input v-model="paper.header.itmreq_obj" type="text" class="form-control bottom-dotted-full" placeholder="กรอกจุดประสงค์ในการดำเนินการ..">
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3">
                            <div class="col-md-12 p-0">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row m-0 mb-3">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-2">รายละเอียดสินค้า</h5>
                                                        <hr class="mb-3">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>รหัสสินค้า / Product Code</label>
                                                            <input type="text" class="form-control" placeholder="Enter Product Code.."
                                                            v-model="paper.header.prod_code"
                                                            disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Product Group / กลุ่มสินค้า</label>
                                                            <select class="form-control" v-model="paper.header.prod_group">
                                                                <option value="" selected disabled>Select Product Group..</option>
                                                                <option v-for="(pg, pgIndex) in productGroup" :key="pgIndex" :value="pg.id">
                                                                    {{pg.name}}
                                                                </option>
                                                                <!-- <option value="01">Colligi</option>
                                                                <option value="02">Cerigi</option>
                                                                <option value="03">H-Collagen</option>
                                                                <option value="04">Ben-C</option>
                                                                <option value="05">Kane-Z</option>
                                                                <option value="06">B-Linz</option>
                                                                <option value="07">Immu</option>
                                                                <option value="08">Bifina</option>
                                                                <option value="09">Cream</option>
                                                                <option value="10">Amado-R and Pre-B Fiber</option>
                                                                <option value="11">Consignment</option>
                                                                <option value="12">ของแถม + แลกซื้อ</option>
                                                                <option value="13">Silver Collagen</option>
                                                                <option value="14">Alcohol</option>
                                                                <option value="99">Other</option> -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Customer Group / กลุ่มลูกค้า</label>
                                                            <select class="form-control" v-model="paper.header.cust_group"
                                                            v-on:change="checkCustomerGroup()">
                                                                <option value="" selected disabled>Select Customer Group..</option>
                                                                <option v-for="(cg, cgIndex) in customerGroup" :key="cgIndex" :value="cg.id">
                                                                    {{cg.name}}
                                                                </option>
                                                                <!-- <option value="1">ขายปลีกออนไลน์</option>
                                                                <option value="2">ตัวแทน VIP</option>
                                                                <option value="3">ตัวแทน Stock</option>
                                                                <option value="4">Kiosk</option>
                                                                <option value="5">Consignment</option>
                                                                <option value="6">ตัวแทนรายใหญ่</option>
                                                                <option value="7">สวัสดิการพนักงาน</option>
                                                                <option value="8">ตัวแทนต่างประเทศ</option>
                                                                <option value="9">ขายส่งต่างประเทศ</option>
                                                                <option value="10">ตัวแทน VIP Bifina</option>
                                                                <option value="11">ขายปลีก Online</option>
                                                                <option value="12">Outbound</option>
                                                                <option value="13">ขายปลีก Online Agent</option> -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Barcode / บาร์โค้ด</label>
                                                            <input type="text" class="form-control" placeholder="Enter Barcode.." @keypress="numberOnly($event)"
                                                            v-model="paper.header.prod_barcode">
                                                        </div>
                                                    </div> -->
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>ชื่อสินค้า / Product Name</label>
                                                            <input type="text" class="form-control" placeholder="Enter Product Name.."
                                                            v-model="paper.header.prod_name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Base Unit / หน่วย</label>
                                                            <select class="form-control" v-model="paper.header.prod_unit">
                                                                <option value="" selected disabled>Select Base Unit..</option>
                                                                <option value="เซ็ท">เซ็ท</option>
                                                                <option value="กระปุก">กระปุก</option>
                                                                <option value="กระป๋อง">กระป๋อง</option>
                                                                <option value="กล่อง">กล่อง</option>
                                                                <option value="ขวด">ขวด</option>
                                                                <option value="ครั้ง">ครั้ง</option>
                                                                <option value="ชิ้น">ชิ้น</option>
                                                                <option value="ซอง">ซอง</option>
                                                                <option value="ลัง">ลัง</option>
                                                                <option value="อัน">อัน</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row m-0 mb-3">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-2">รายละเอียดราคา/ส่วนแบ่ง</h5>
                                                        <hr class="mb-3">
                                                    </div>                                                    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>VIP Amado Profitshare / ส่วนแบ่ง VIP Amado</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" placeholder="Enter Product Price.." aria-label="Price" aria-describedby="aria-price"
                                                                @keypress="numberOnly($event)"
                                                                v-model="paper.header.prod_VIPAmadoprofitshare">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" id="aria-price">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                   
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>VIP Bifina Profitshare / ส่วนแบ่ง VIP Bifina</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" placeholder="Enter Product Price.." aria-label="Price" aria-describedby="aria-price"
                                                                @keypress="numberOnly($event)"
                                                                v-model="paper.header.prod_VIPBifinaProfitShare">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" id="aria-price">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                               
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Stock Profitshare / ส่วนแบ่ง Stock</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" placeholder="Enter Product Price.." aria-label="Price" aria-describedby="aria-price"
                                                                @keypress="numberOnly($event)"
                                                                v-model="paper.header.prod_StockProfitShare">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" id="aria-price">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Product Price / ราคาสินค้า (ปลีก)</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" placeholder="Enter Product Price.." aria-label="Price" aria-describedby="aria-price"
                                                                @keypress="numberOnly($event)"
                                                                v-model="paper.header.prod_rprice"
                                                                :disabled="paper.header.prod_rprice_rol">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" id="aria-price">บาท/THB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Product Price Total / ราคาสินค้ารวม</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" placeholder="Enter Agent Product Price.." aria-label="AgentPrice" aria-describedby="aria-agentprice"
                                                                @keypress="numberOnly($event)"
                                                                v-model="paper.header.prod_aprice"
                                                                disabled>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" id="aria-agentprice">บาท/THB</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                                <div class="row m-0 mb-3">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-2">เงื่อนไขโปรโมชั่น</h5>
                                                        <hr class="mb-3">
                                                    </div>
                                                    <div class="col-md-3 text-center my-auto">
                                                        <i class="far fa-calendar-alt validate-ico"></i>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Effective Date / วันเริ่มต้น</label>
                                                            <date-picker v-model="paper.header.prom_sdate" class="d-block w-100" value-type="format" format="YYYY-MM-DD" placeholder="Enter Effective Date.."></date-picker>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Expiration Date / วันสิ้นสุด</label>
                                                            <date-picker v-model="paper.header.prom_edate" class="d-block w-100" value-type="format" format="YYYY-MM-DD" placeholder="Enter Expiration Date.."></date-picker>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-4">
                                                <div class="row m-0 justify-content-md-center">
                                                    <div class="col-md-12">
                                                        <h5 class="card-title mt-2"><i class="fas fa-cloud-upload-alt mr-2"></i>Upload product image</h5>
                                                        <hr class="mb-3">
                                                    </div>
                                                    <div class="col-md-10 text-center">
                                                        <div class="form-group mb-0">
                                                            <img class="img-fluid preview-imgitem rounded" :src="'/img/no-preview.png'" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-center">
                                                        <button type="button" class="btn btn-danger btn-sm mb-3">
                                                            <i class="fas fa-times mr-2"></i> ลบรูปภาพ
                                                        </button>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class='form-group'>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="productImgFile" aria-describedby="productImgFile" accept=".png, .jpg, .jpeg">
                                                                    <label class="custom-file-label" for="productImgFile" >Select image file..</label>
                                                                </div>
                                                                <div class="input-group-append">
                                                                    <button type="submit" class="btn btn-outline-secondary">
                                                                        <i class="fas fa-cloud-upload-alt"></i> Upload
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>

                                        <hr class="mb-3">

                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class=" btn btn-success"
                                                        v-on:click="addItmRow()">
                                                    <i class="fas fa-plus mr-2"></i> เพิ่มรายการสินค้า
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3">
                            <div class="col-md-12 p-0">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row m-0">
                                            <div class="col-md-12">
                                                <h5 class="card-title mt-2"><i class="fas fa-list-ul mr-2 text-success"></i>รายการสินค้า</h5>
                                                <hr class="mb-3">
                                            </div>
                                            <div class="col-md-12">
                                                <table class="table table-hover table-sm">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col" class="text-center">#</th>
                                                        <th scope="col" class="text-center">รหัสสินค้า</th>
                                                        <th scope="col" class="text-center">ชื่อสินค้า</th>                                                        
                                                        <th scope="col" class="text-center">ราคา/หน่วย</th>
                                                        <th scope="col" class="text-center">จำนวน</th>
                                                        <th scope="col" class="text-center">ราคารวม</th>
                                                        <th scope="col" class="text-center">หน่วย</th>
                                                        <!-- <th scope="col" class="text-center">กลุ่มลูกค้า</th> -->
                                                        <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(pl, plIndex) in product.main" :key="plIndex">
                                                            <th class="text-center" scope="row">{{plIndex+1}}</th>
                                                            <td>{{pl.code}}</td>
                                                            <td>{{pl.name}}</td>
                                                            <td class="text-right">
                                                                <input type="number" class="form-control-sm" value="1"
                                                                v-model="pl.price"                                                                
                                                                v-on:change="calProductPrice(plIndex)">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control-sm" value="1"
                                                                v-model="pl.qty"                                                                
                                                                v-on:change="calProductPrice(plIndex)">
                                                            </td>
                                                            <td class="text-right">{{pl.tprice}}</td>
                                                            <td>{{pl.unit}}</td>
                                                            <!-- <td>{{pl.pgname}}</td> -->
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                v-on:click="removeProductMain(plIndex)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-right" colspan="5"><b>ราคารวม</b></td>
                                                            <td class="text-right"><b>{{paper.header.prod_aprice}}</b></td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <hr class="mb-3">

                                        <div class="row justify-content-center py-3">
                                            <button type="button" class="col-md-3 btn btn-primary"
                                                    v-on:click="setSaveItmreq()"
                                            >
                                                <i class="fas fa-paper-plane mr-2"></i> ส่ง Item Request
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        <!-- Modal : #modal_cfm_senditemreq -->
        <div class="modal fade" id="modal_cfm_senditemreq" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fas fa-exclamation-circle text-danger mr-2"></i>Alert</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12" v-show="modal.confirm.content1">
                                <p class="mb-0" v-html="modal.confirm.content1txt">ยืนยันการส่ง CRM Item Request หรือไม่ ?</p>
                            </div>
                            <div class="col-12 text-center" v-show="modal.confirm.content2">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <div class="text-center text-primary"><strong> ระบบกำลังบันทึกข้อมูล</strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" v-show="modal.confirm.content0">
                        <button type="button" class="btn btn-success"
                        v-on:click="callCreateItmreq()"
                        v-show="modal.confirm.content0btn1">
                            <i class="fas fa-check mr-2"></i>ยืนยัน
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        v-show="modal.confirm.content0btn2">
                            <i class="fas fa-times mr-2"></i>ยกเลิก
                        </button>
                        <a class="btn btn-success" href="/user/history_itemrequest_crm"
                        v-show="modal.confirm.content0btn3">
                            <i class="fas fa-sign-out-alt mr-2"></i>ไปยังหน้ารายการ CRM Item Request
                        </a>
                        <button type="button" class="btn btn-primary"
                        v-on:click="reloadPage()"
                        v-show="modal.confirm.content0btn4">
                            <i class="fas fa-notes-medical mr-2"></i>ทำรายการใหม่
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal_resp_senditemreq -->
<!--        <div class="modal fade" id="modal_resp_senditemreq" >-->
<!--            <div class="modal-dialog modal-dialog-centered">-->
<!--                <div class="modal-content">-->
<!--                    <div class="modal-header">-->
<!--                        <h4 class="modal-title" v-html="progress.title"><i class="fas fa-clipboard-check text-success mr-2"></i>System response</h4>-->
<!--                    </div>-->
<!--                    <div class="modal-body" v-html="progress.content">-->
<!--                        <p class="mb-0">-->
<!--                            เลขที่เอกสาร <strong></strong><br>-->
<!--                            ระบบกำลังนำทางไปหน้าประวัติคำขอ Item Request-->
<!--                        </p>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

        <!-- Modal : #modal-items -->
        <div class="modal fade" id="modal-items" data-backdrop="static" 
        data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-clipboard-check text-primary mr-2"></i>
                            เลือกสินค้า
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" 
                        aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <b>รายการสินค้า</b>
                                    </div>
                                    <div class="card-body">
                                        <button type="button" class="btn btn-outline-primary btn-sm mb-1 mr-1"
                                        v-for="(ps,psIndex) in product.select" :key="psIndex"
                                        v-on:click="removeProductList(psIndex)">
                                            {{ps.name}}
                                            <i class="fas fa-times-circle ml-2"></i>
                                        </button>
                                        <i class="fas fa-exclamation-triangle text-danger mr-1"
                                        v-show="product.select.length <= 0"></i>
                                        <b class="text-danger" v-show="product.select.length <= 0">ไม่มีรายการสินค้า</b>
                                    </div>
                                </div>                                
                                <button type="button" class="btn btn-success btn-sm float-right ml-1"
                                data-dismiss="modal">
                                    <i class="fas fa-sign-out-alt"></i> ออก
                                </button>
                                <button type="button" class="btn btn-danger btn-sm float-right ml-1"
                                v-on:click="cancleProductList()">
                                    <i class="fas fa-times mr-1"></i> ยกเลิก
                                </button>
                                <button type="button" class="btn btn-primary btn-sm float-right"
                                v-on:click="confirmProductList()">
                                    <i class="fas fa-check mr-1"></i> ตกลง
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-bind:style="style.table" class="scrollbar-custom bg-white">
                        <table class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" width="7%">เลือก</th>
                                    <th class="text-center">สินค้า</th>
                                    <th class="text-center" width="7%">หน่วย</th>
                                    <th class="text-center" width="10%">กลุ่มลูกค้า</th>
                                </tr>
                                <tr>
                                    <td colspan="4" class="p-1">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm"
                                            placeholder="ใส่รหัสหรือชื่อสินค้า"
                                            v-model="product.search"
                                            v-on:keyup="filterProduct()"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-filter"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pf, pfIndex) in product.filter" :key="pfIndex">
                                    <td class="text-center">
                                        <button class="btn btn-outline-primary btn-sm"
                                            v-on:click="selectProductList(pfIndex)"
                                        >
                                            <i class="fas fa-hand-pointer mr-1"></i> เลือก
                                        </button>
                                    </td>
                                    <td class="align-middle">{{pf.code}} {{pf.name}}</td>
                                    <td class="text-center align-middle">{{pf.unit}}</td>
                                    <td class="text-center align-middle">{{pf.pgname}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/th';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    mounted(){
        this.get_today();
        this.getDefaultData();
    },
    data(){
        return{
            style: {
                table: {
                    position: 'relative',
                    height: '450px',
                    overflow: 'auto',
                    display: 'block',
                    borderTop: 'thin solid #d8d8d8'
                }
            },
            paper: {
                header: {
                    today: '',
                    emp_id: this.$session.get('userId'),
                    emp_fullname: this.$session.get('name'),
                    emp_position: '',
                    itmreq_obj: '',
                    prod_group: '',
                    cust_group: '',
                    prod_code: 'CRM Generate',
                    prod_barcode: '',
                    prod_name: '',
                    prod_unit: '',
                    prod_rprice: 0,
                    prod_rprice_rol: true,
                    prod_aprice: 0,
                    prom_sdate: '',
                    prom_edate: '',
                    prod_VIPAmadoprofitshare: 0,
                    prod_VIPBifinaProfitShare: 0,
                    prod_StockProfitShare: 0,

                },
                detail: [{
                    itemcode: '',
                    itemname: '',
                    barcode: '',
                    baseunit: '',
                    price: '',
                    priceagent: '',
                    prodgroup: '',
                    cusgroup: '',
                    action: '1',
                    app: '1'
                }]
            },
            customerGroup: [],
            productGroup: [],
            product: {
                search: '',
                default: [],
                filter: [],
                select: [],
                main: []
            },
            modal: {
                confirm: {
                    content1: true,
                    content1txt: '',
                    content2: false,
                    content0: true,
                    content0btn1: true,
                    content0btn2: true,
                    content0btn3: true,
                    content0btn4: true
                }
            }
        }
    },
    methods:{
        checkSeessionLogin(){
            if(this.$session.exists()){
                return true;
            }
            else{

                this.$swal({
                    title: 'เวลาในการ Login หมดลงแล้ว',
                    text: 'กรุณา Login ใหม่',
                    icon: 'Error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ไปยังหน้า Login',
                })
                    .then((result) => {
                        if(result.isConfirmed){
                            location.href = '/login';
                        }
                    });

                return false;

            }
        },
        get_today(){
            let date = new Date();
            this.paper.header.today = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+(date.getDate())).slice(-2);
        },
        getDefaultData(){
            
            axios.get('/api/crm/product/list')
            .then(response => {
                this.product.default = this.product.filter = response.data
            });

            axios.get('/api/crm/product/group-list')
            .then(response => {this.productGroup = response.data});

            axios.get('/api/crm/customer/group-list')
            .then(response => {this.customerGroup = response.data});
            
        },
        numberOnly(e) {
            if (e.keyCode < 48 && e.keyCode !== 46 || e.keyCode > 59) {
                e.preventDefault()
            }
        },
        checkCustomerGroup(){
            var group_agent = [2,3,6,8,10];
            if(group_agent.includes(this.paper.header.cust_group))
                this.paper.header.prod_rprice_rol = false;
            else this.paper.header.prod_rprice_rol = true;
        },
        addItmRow() { 
            this.product.select = this.product.main;           
            $('#modal-items').modal('show');
        },
        filterProduct(){            

            if(this.product.search != ''){

                var filter = [];

                for(var i = 0; i < this.product.default.length; i++){
                    if(
                        this.product.default[i].code.search(this.product.search) >- 1
                        || this.product.default[i].name.search(this.product.search) >- 1
                    )
                        filter.push(this.product.default[i]);
                }

                this.product.filter = filter;

            }
            else this.product.filter = this.product.default;

        },
        selectProductList(index){

            let item = this.product.filter[index];
            this.product.select.push({
                id: item.id,
                code: item.code,
                name: item.name,
                price: item.rprice,
                qty: 1,
                tprice: item.rprice,
                unit: item.unit,
                pgname: item.pgname
            });
            

        },
        removeProductList(index){

            var temp = this.product.select;
            this.product.select = [];


            for(var i = 0; i < temp.length; i++)
                if( i != index)
                    this.product.select.push(temp[i]);

        },
        cancleProductList(){
            this.product.select = [];
        },
        confirmProductList(){
            this.product.main = this.product.select;
            this.product.select = [];
            var priceTotal = 0;
            for(var i = 0; i < this.product.main.length; i++)
                priceTotal += parseFloat(this.product.main[i].tprice);

            this.paper.header.prod_aprice = priceTotal;
            $('#modal-items').modal('hide');
        },
        calProductPrice(index){

            var price = this.product.main[index].price * this.product.main[index].qty;
            this.product.main[index].tprice = price;

            var priceTotal = 0;
            for(var i = 0; i < this.product.main.length; i++)
                priceTotal += parseFloat(this.product.main[i].tprice);

            this.paper.header.prod_aprice = priceTotal;

        },
        removeProductMain(index){

            var temp = this.product.main;
            var priceTotal = 0.00;
            this.product.main = [];

            for(var i = 0; i < temp.length; i++){
                if(i != index){
                    this.product.main.push(temp[i]);
                    priceTotal += parseFloat(temp[i].tprice);
                }
            }

            this.paper.header.prod_aprice = priceTotal;

        },
        setSaveItmreq(){

            if(!this.checkSeessionLogin())
                return;

            if( this.paper.header.emp_position == '' ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกตำแหน่ง' });
                return;
            }

            if( this.paper.header.itmreq_obj == '' ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกจุดประสงค์ในการดำเนินการ' });
                return;
            }

            if( this.paper.header.prod_group == '' ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกกลุ่มสินค้า' });
                return;
            }

            if( this.paper.header.cust_group == '' ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกกลุ่มลูกค้า' });
                return;
            }

            if( this.paper.header.prod_name == '' ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกชื่อสินค้า' });
                return;
            }

            if( this.paper.header.prod_unit == '' ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกหน่วย' });
                return;
            }

            var group_retail = [1,11,13];
            var group_agent = [2,3,6,8,10];

            if( 
                group_agent.includes(this.paper.header.cust_group) 
                && (
                    this.paper.header.prod_rprice == ''
                    || this.paper.header.prod_rprice <= 0
                )
            ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกราคาสินค้า (ปลีก)' });
                return;
            }

            if( 
                !group_retail.includes(this.paper.header.cust_group) 
                && (
                    this.paper.header.prod_aprice == ''
                    || this.paper.header.prod_aprice <= 0
                )
            ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกราคาสินค้า (ตัวแทน)' });
                return;
            }
            
            if( this.paper.header.prom_sdate == "" ){
                this.$swal({ 
                    icon: 'error', 
                    text: 'กรุณากรอกข้อมูลโปรโมชั่น วันเริ่ม ให้ถูกต้อง' 
                });
                return;
            }
            else if( this.paper.header.prom_edate == "" ){
                this.$swal({ 
                    icon: 'error', 
                    text: 'กรุณากรอกข้อมูลโปรโมชั่น วันสิ้นสุด ให้ถูกต้อง' 
                });
                return;
            }
            else if( this.paper.header.prom_sdate > this.paper.header.prom_edate ){
                this.$swal({ 
                    icon: 'error', 
                    text: 'กรุณากรอกข้อมูลโปรโมชั่น วันเริ่ม - สิ้นสุด ให้ถูกต้อง' 
                });
                return;
            }

            if( this.product.main.length <= 0 ){
                this.$swal({ 
                    icon: 'error', 
                    text: 'กรุณาเพิ่มรายการสินค้า' 
                });
                return;
            }

            let check_detail = false,
                line = 1;
            
            for( var i = 0; i < this.product.main.length; i++ ){
                if(
                    this.product.main[i].qty == ''
                    || this.product.main[i].qty <= 0
                ){
                    check_detail = true;
                    line = i+1;
                    break;
                }
            }
            
            if( check_detail ){
                this.$swal({ 
                    icon: 'error', 
                    text: 'กรุณากรอกข้อมูลรายการ Item แถวที่ '+line+' ให้ครบถ้วน' 
                });
                return;
            }

            this.modal.confirm.content1txt = 'ยืนยันการส่ง CRM Item Request หรือไม่ ?';
            this.modal.confirm.content1 = true;
            this.modal.confirm.content2 = false;
            this.modal.confirm.content0 = true;
            this.modal.confirm.content0btn1 = true;
            this.modal.confirm.content0btn2 = true;
            this.modal.confirm.content0btn3 = false;            
            this.modal.confirm.content0btn4 = false;
            $('#modal_cfm_senditemreq').modal('show');

        },
        callCreateItmreq(){

            this.modal.confirm.content1 = false;
            this.modal.confirm.content2 = true;
            this.modal.confirm.content0 = false;

            var postData = {
                header: this.paper.header,
                detail: this.product.main,
                files: []
            }

            axios.post('/api/crm/item-request/create',postData)
            .then(response => {
                if(response.data.Status){
                    var txt = 'เลขที่เอกสาร '+response.data.paper_id;
                    this.modal.confirm.content1txt = txt;
                    this.modal.confirm.content1 = true;
                    this.modal.confirm.content2 = false;
                    this.modal.confirm.content0 = true;
                    this.modal.confirm.content0btn1 = false;
                    this.modal.confirm.content0btn2 = false;
                    this.modal.confirm.content0btn3 = true;
                    this.modal.confirm.content0btn4 = true;
                }
                else{
                    this.modal.confirm.content1txt = 'ไม่สามารถบันทึก CRM Item Request ได้';
                    this.modal.confirm.content1 = true;
                    this.modal.confirm.content2 = false;
                    this.modal.confirm.content0 = true;
                    this.modal.confirm.content0btn1 = false;
                    this.modal.confirm.content0btn2 = true;
                }
            })
            .catch(response => {
                this.modal.confirm.content1txt = 'ไม่สามารถบันทึก CRM Item Request ได้';
                this.modal.confirm.content1 = true;
                this.modal.confirm.content2 = false;
                this.modal.confirm.content0 = true;
                this.modal.confirm.content0btn1 = false;
                this.modal.confirm.content0btn2 = true;
            });

        },
        reloadPage(){
            window.location.reload();
        }
    }
}
</script>
