<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-list-ul"></i><span class="ml-2">รายการ CRM Item Request</span>
            </div>
            
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs" id="" role="tablist" >
                            <li class="nav-item">
                                <a class="nav-link active" id="waitappr-tab" data-toggle="tab" href="#waitappr"
                                role="tab" aria-controls="waitappr" aria-selected="true"
                                v-on:click="getListItmReq('waitappr')">
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รออนุมัติ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="success-tab" data-toggle="tab" href="#success"
                                role="tab" aria-controls="success" aria-selected="false"
                                v-on:click="getListItmReq('success')">
                                    <i class="fas fa-check text-success"></i>
                                    <span class="ml-2">อนุมัติแล้ว</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="void-tab" data-toggle="tab" href="#void"
                                role="tab" aria-controls="void" aria-selected="false"
                                v-on:click="getListItmReq('void')">
                                    <i class="fas fa-minus text-danger"></i>
                                    <span class="ml-2">ไม่อนุมัติ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="all-tab" data-toggle="tab" href="#all"
                                role="tab" aria-controls="all" aria-selected="false"
                                v-on:click="getListItmReq('all')">
                                    <i class="fas fa-list-ul text-primary"></i>
                                    <span class="ml-2">รายการทั้งหมด</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="">

                            <div class="row">
                                <div class="col-md-12 mt-3">

                                    <div class="form-row">
                                        <div class="col-auto">
                                            <label>เลขที่เอกสาร</label>
                                            <input class="form-control form-control-sm mr-sm-2" type="search" 
                                            placeholder="กรอกเลขที่เอกสาร..." aria-label="Search" 
                                                v-model="search_txt"
                                                v-on:keyup.enter="getListItmReq('')"
                                            >
                                        </div>
                                        <div class="col-auto">
                                            <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                                v-model="searchName"
                                                v-on:keyup.enter="getListItmReq('')"
                                            >
                                        </div>
                                        <div class="col-auto my-4">
                                            <button class="btn btn-sm btn-amado my-2" type="button"
                                                v-on:click="getListItmReq('')"
                                            >
                                                <i class="fas fa-search"></i>
                                                <span class="ml-2">ค้นหา</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row" v-show="progress.tableMain">
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="row" v-show="!progress.tableMain">
                    <div class="col-md-12">

                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>เลขที่เอกสาร</th>
                                    <th>วันที่เอกสาร</th>
                                    <th>ผู้ร้องขอ</th>
                                    <th>จุดประสงค์</th>
                                    <th>สถานะ</th>                                    
                                    <th>Approve</th>
                                    <th>Product code</th>
                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lst in list" :key="lst.index">
                                    <td>{{lst.index}}</td>
                                    <td>{{lst.itmreq_no}}</td>
                                    <td>{{lst.itmreq_date}}</td>
                                    <td>{{lst.emp_fullname}}</td>
                                    <td>{{lst.itmreq_obj}}</td>
                                    <td>{{lst.status_str}}</td>
                                    <td>{{lst.approve_str}}</td>
                                    <td>
                                        <span v-if="lst.api_status == 0">ยังไม่ได้สร่าง</span>
                                        <span v-else-if="lst.api_status == 1">CRM สร้างไม่สำเร็จ</span>
                                        <span v-else-if="lst.api_status == 2">{{lst.itmreq_pcode}}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-itemreq"
                                            v-on:click="showDetailItmReq(lst.index-1)"
                                        >
                                            <i class="fas fa-file-alt mr-1"></i> รายละเอียด
                                        </button>
                                        <!-- <button type="button" class="btn btn-danger btn-sm"
                                            :disabled="lst.itmreq_status != 1" 
                                            v-on:click="voidItmReq(lst.index-1)"
                                        ><i class="fas fa-ban mr-1"></i> ยกเลิก</button> -->
                                    </td>
                                </tr>
                                <tr v-show="list.length <= 0">
                                    <td class="text-center" colspan="8"><b>ไม่มีรายการ</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="card-footer small text-muted">
                
                <nav aria-label="Page navigation example" v-show="paginate.show">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="javascript:;"
                            v-on:click="getDataPaginate('first')" 
                        ><i class="fas fa-angle-double-left"></i></a></li>
                        <li class="page-item"><a class="page-link" href="javascript:;" 
                            v-on:click="getDataPaginate('prev')"
                        ><i class="fas fa-angle-left"></i></a></li>
                        <li class="page-item">
                            <select class="page-link page-link-dropdown"
                                v-model="paginate.numberPagin"
                                v-on:change="getDataPaginate('select')"
                            >
                                <option v-for="num in paginate.numberPage" :key="num.number" :value="num.number">
                                    {{num.number}}
                                </option>
                            </select>
                        </li>
                        <li class="page-item"><a class="page-link" href="javascript:;" 
                            v-on:click="getDataPaginate('next')"
                        ><i class="fas fa-angle-right"></i></a></li>
                        <li class="page-item"><a class="page-link" href="javascript:;" 
                            v-on:click="getDataPaginate('last')"
                        ><i class="fas fa-angle-double-right"></i></a></li>
                    </ul>
                </nav>
            </div>

        </div>


        <!-- Modal : #modal-itemreq -->
        <div class="modal fade" id="modal-itemreq" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span class="mr-2">สถานะ : </span>

                                    <span class="spinner-grow spinner-grow-mini text-danger" 
                                    role="status" aria-hidden="true"
                                    v-show="detail.header.itmreq_approve == 0"
                                    ></span>
                                    <span class="ml-2" v-show="detail.header.itmreq_approve == 0">รออนุมัติ</span>                                    

                                    <i class="fas fa-list-ul text-success" 
                                    v-show="detail.header.itmreq_approve == 1 && detail.header.itmreq_status == 3"></i>
                                    <span class="ml-2" v-show="detail.header.itmreq_approve == 1 && detail.header.itmreq_status == 3">อนุมัติแล้ว</span>

                                    <i class="fas fa-ban text-danger" 
                                    v-show="detail.header.itmreq_approve == 2"></i>
                                    <span class="ml-2" v-show="detail.header.itmreq_approve == 2">ไม่อนุมัติ</span>

                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mx-4 mt-5">
                        <div class="row m-0 mb-3">
                            <div class="col-md-4">
                                <img class="logo-nav" src="/img/logo-red.png" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>ITEM REQUEST FORM</b></p>
                                <p><b>AMADO GROUP</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">เลขที่เอกสาร</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span v-html="detail.header.itmreq_no"></span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span v-html="detail.header.itmreq_date"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อ-นามสกุล</div>
                                    <div class="bottom-dotted-590 text-center">
                                        <span v-html="detail.header.it_emp_fullname"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-265 text-center">
                                        <span v-html="detail.header.it_emp_position"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3">
                            <div class="col-md-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">จุดประสงค์ในการดำเนินการ</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <div class="bottom-dotted-full">
                                        <span class="ml-5" v-html="detail.header.itmreq_obj"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">ลำดับ<br>(No.)</th>
                                                    <th class="text-center">บาร์โค้ด<br>(Barcode)</th>
                                                    <th class="text-center">ชื่อสินค้า<br>(Item)</th>
                                                    <th class="text-center">ดำเนินการ<br>(Operation)</th>
                                                    <th class="text-center">โปรโมชั่น<br>วันเริ่ม - สิ้นสุด</th>
                                                    <th class="text-center">โปรแกรม<br>(Application)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="w-5 text-center">1</td>
                                                    <td class="ml-5 text-center">{{detail.header.itmreq_pbcode}}</td>
                                                    <td class="w-30">
                                                        <span>{{detail.header.itmreq_pname}}</span>
                                                        <span class="ml-2">ราคารวม {{detail.header.itmreq_aprice}}</span>
                                                        <span class="ml-2"
                                                        v-show="detail.header.itmreq_rprice > 0">
                                                            ราคาปลีก {{detail.header.itmreq_rprice}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" value="" disabled checked>
                                                                    <label class="custom-control-label text-black" for="optoperation_1">สร้าง</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" value="" disabled>
                                                                    <label class="custom-control-label text-black" for="optoperation_2">ยกเลิก</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{detail.header.itmreq_efdate}}<br>ถึงวันที่<br>{{detail.header.itmreq_exdate}}</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" checked>
                                                                    <label class="custom-control-label text-black" for="optapp_1">AMADO</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input">
                                                                    <label class="custom-control-label text-black" for="optapp_2">Kiosk</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input">
                                                                    <label class="custom-control-label text-black" for="optapp_3">Dynamics AX</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input">
                                                                    <label class="custom-control-label text-black" for="optapp_4">Sale Force</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr v-for="(list, index) in detail.detail" :key="index">
                                                    <td class="w-5 text-center">{{index+2}}</td>
                                                    <td class="ml-5 text-center">{{list.itmreq_barcode}}</td>
                                                    <td class="w-30">{{list.pdl_code}} {{list.pdl_name}} ({{list.itmreq_qty}} {{list.pdl_unit}})</td>
                                                    <td>สินค้าประกอบ</td>
                                                    <td class="text-center"></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="w-5 text-center"></td>
                                                    <td class="ml-5 text-center"></td>
                                                    <td class="w-30">VIP Amado Profitshare {{detail.header.vipamdprofit}} %</td>
                                                    <td class="text-center"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="w-5 text-center"></td>
                                                    <td class="ml-5 text-center"></td>
                                                    <td class="w-30">VIP Bifina Profitshare {{detail.header.vipbfnprofit}} %</td>
                                                    <td class="text-center"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="w-5 text-center"></td>
                                                    <td class="ml-5 text-center"></td>
                                                    <td class="w-30">Stock Profitshare {{detail.header.stockprofit}} %</td>
                                                    <td class="text-center"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end m-0">
                            <div class="col-md-4 text-center">
                                <p class="mb-2">ผู้แจ้งขอดำเนินการ</p>
                                <p v-html="detail.header.it_emp_fullname"></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-center">
                            <div class="col-md-3" v-show="approve.btn && $session.get('role') == 'user.crm.appr'"
                            v-on:click="confirmItmReq(detail.header.itmreq_id)">
                                <button type="button" class="btn btn-success w-100">
                                    <i class="fas fa-check mr-2"></i>Approve
                                </button>
                            </div>
                            <div class="col-md-3" v-show="approve.btn"
                            v-on:click="voidItmReq(detail.header.itmreq_id)">
                                <button type="button" class="btn btn-danger w-100">
                                    <i class="fas fa-times mr-2"></i>ยกเลิก
                                </button>
                            </div>
                            <div class="col-md-12" v-show="approve.yes">
                                <div class="alert alert-success text-center" role="alert">
                                    <i class="fas fa-check mr-2"></i>
                                    <b>Approved</b>
                                </div>
                            </div>
                            <div class="col-md-12" v-show="approve.no">
                                <div class="alert alert-danger text-center" role="alert">
                                    <i class="fas fa-times mr-2"></i>
                                    <b>ยกเลิก</b>
                                </div>
                            </div>
                        </div>
                        <hr class="pb-3">
                        <div class="row justify-content-center pb-3">
                            <div class="col-md-4">
                                <a type="button" class="btn btn-primary w-100"
                                    :href="'../pdf/crm/itemrequest/'+detail.header.itmreq_id" :target="'_bland'"
                                ><i class="fas fa-print mr-1"></i> พิมพ์</a>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal"><i class="fas fa-times mr-1"></i> ปิด</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

export default {
    mounted(){
        this.getListItmReq(this.paginate.methods);
    },
    data(){
        return{
            search_txt: '',
            searchName: '',
            progress: {
                tableMain: false,
            },
            approve: {
                btn: true,
                yes: false,
                no: false
            },
            paginate: {
                methods: 'waitappr',
                url: '',
                path: '',
                firstPageUrl: '',
                lastPageUrl: '',
                nextPageUrl: '',
                prevPageUrl: '',
                currentPage: 0,
                lastPage: 0,
                from: 0,
                to: 0,
                numberPage: [],
                numberPagin: 1,
                list: 25,
                show: true
            },
            list: {},
            detail: {
                itmreq_id: '',
                header: {},
                detail: {}
            },
            files:{
                progress: false,
                data: []
            }
        }
    },
    methods:{
        neviLogin(){
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
        },
        getListItmReq(methods){

            this.paginate.methods = ( methods == '' ? this.paginate.methods : methods );
            this.progress.tableMain = true;

            axios.post('/api/crm/item-request/list',{
                methods: this.paginate.methods,
                search: this.search_txt,
                searchEmp: this.searchName,
                role: 'user'
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            });

        },
        setDataAndPaginate(dataArr){

            this.paginate.url = dataArr.path+'?page=';
            this.paginate.path = dataArr.path;
            this.paginate.firstPageUrl = dataArr.first_page_url;
            this.paginate.lastPageUrl = dataArr.last_page_url;
            this.paginate.nextPageUrl = dataArr.next_page_url;
            this.paginate.prevPageUrl = dataArr.prev_page_url;
            this.paginate.currentPage = dataArr.current_page;
            this.paginate.firstPage = 1;
            this.paginate.lastPage = dataArr.last_page;
            this.paginate.from = dataArr.from;
            this.paginate.to = dataArr.to; 
            
            var tempData = dataArr.data,
                resultData = [],
                resultPageNumber=  [],
                rowNumber = this.paginate.from;
                
            for(var i = 0; i < tempData.length; i++){
                resultData.push(tempData[i]);
                resultData[i].index = rowNumber++;
            }

            for(var i = this.paginate.firstPage; i <= this.paginate.lastPage; i++){
                resultPageNumber.push({number: i});
            }

            this.list = resultData
            this.paginate.numberPage = resultPageNumber;
            this.progress.tableMain = false;

        },
        getDataPaginate(page){

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }
            
            if( this.paginate.url === null || this.paginate.url === undefined )
                return;
            
            axios.post(this.paginate.url,{
                methods: this.paginate.methods,
                search: this.search_txt,
                searchEmp: this.searchName,
                role: 'user'
            })
            .then(response => { this.setDataAndPaginate(response.data) }); 

        },
        confirmItmReq(itmreq_id){
            this.$swal({
                icon: 'warning',
                title: 'Approve CRM Item Request',
                text: 'ต้องการ Approve '+this.detail.header.itmreq_no+' หรือไม่',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ยืนยัน',
                denyButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if(result.isConfirmed){
                    axios.get('/api/crm/item-request/approve/'+itmreq_id)
                    .then(response => {
                        if(response.data.Status){
                            this.$swal({ 
                                icon: 'success', 
                                text: 'Approve '+this.detail.header.itmreq_no+' เรียบร้อยแล้ว' 
                            });
                            $('#modal-itemreq').modal('hide');
                            this.getDataPaginate(this.paginate.numberPagin);
                        }
                        else{
                            if(response.data.error == 'login.fail')
                                this.neviLogin();
                            else{
                                this.$swal({ 
                                    icon: 'error', 
                                    text: 'Approve '+this.detail.header.itmreq_no+' ไม่สำเร็จ' 
                                });
                            }
                        }
                    })
                    .catch(response => {
                        this.$swal({ 
                            icon: 'error', 
                            text: 'Approve '+this.detail.header.itmreq_no+' ไม่สำเร็จ' 
                        });
                    });
                }
            });
        },
        voidItmReq(itmreq_id){
            this.$swal({
                icon: 'warning',
                title: 'ยืนยันการยกเลิก CRM Item Request',
                text: 'ต้องการยกเลิก '+this.detail.header.itmreq_no+' หรือไม่',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ยืนยัน',
                denyButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if(result.isConfirmed){
                    axios.get('/api/crm/item-request/void/'+itmreq_id)
                    .then(response => {
                        if(response.data.Status){
                            this.$swal({ icon: 'success', text: 'ยกเลิก '+this.detail.header.itmreq_no+' เรียบร้อยแล้ว' });
                            this.approve.btn = false;
                            this.approve.yes = false;
                            this.approve.no = true;
                            $('#modal-itemreq').modal('hide');
                            this.getDataPaginate(this.paginate.numberPagin);
                        }
                        else{
                            if(response.data.error == 'login.fail')
                                this.neviLogin();
                            else
                                this.$swal({ icon: 'error', text: 'ยกเลิก '+this.detail.header.itmreq_no+' ไม่สำเร็จ' });
                        }
                    })
                    .catch(response => {
                        this.$swal({ icon: 'error', text: 'ยกเลิก '+this.detail.header.itmreq_no+' ไม่สำเร็จ' });
                    });
                }
            });
        },
        showDetailItmReq(index){
            axios.post('/api/crm/item-request/detail',{
                id: this.list[index].itmreq_id,
                role: 'user',
                format: 'json'
            })
            .then(response => {

                this.detail.itmreq_id = this.list[index].itmreq_id;
                this.detail.header = response.data.header;
                this.detail.detail = response.data.detail;

                switch(this.detail.header.itmreq_approve){
                    case 1:
                        this.approve.btn = this.approve.no = false;
                        this.approve.yes = true;
                    break;
                    case 2:
                        this.approve.btn = this.approve.yes = false;
                        this.approve.no = true;
                    break;
                    case 0:
                        this.approve.no = this.approve.yes = false;
                        this.approve.btn = true;
                    break;
                }
                // this.getItmreqFile();
            });
        },
        getItmreqFile(){

            this.files.progress = true;

            axios.get('/api/admin/it/itemrequest/file/'+this.detail.itmreq_id)
            .then(response => {
                this.files.data = response.data;
                this.files.progress = false;
            });  
            
        }
    }
}
</script>