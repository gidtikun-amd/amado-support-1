<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการคำขอ Item Request</span>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs" id="" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="wait-tab" data-toggle="tab" href="javascript:;" role="tab" aria-controls="wait" aria-selected="true"
                                    v-on:click="getListData('wait')">
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รอดำเนินการ Approve</span>
                                </a>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link" id="success-tab" data-toggle="tab" href="javascript:;" role="tab" aria-controls="success" aria-selected="false"
                                    v-on:click="getListData('success')">
                                    <i class="fas fa-check text-success"></i>
                                    <span class="ml-2">อนุมัติแล้ว</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="void-tab" data-toggle="tab" href="javascript:;" role="tab" aria-controls="void" aria-selected="false"
                                    v-on:click="getListData('void')">
                                    <i class="fas fa-minus text-danger"></i>
                                    <span class="ml-2">ไม่อนุมัติ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="all-tab" data-toggle="tab" href="javascript:;" role="tab" aria-controls="all" aria-selected="false"
                                    v-on:click="getListData('all')">
                                    <i class="fas fa-list-ul text-primary"></i>
                                    <span class="ml-2">รายการทั้งหมด</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="">
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <div class="form-inline mb-3">
                                        <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกเลขที่เอกสาร..." aria-label="Search" 
                                            v-model="search_txt"
                                            v-on:keyup.enter="getListData(paginate.method)"
                                        >
                                        <button class="btn btn-sm btn-amado my-2 my-sm-0" type="button"
                                            v-on:click="getListData(paginate.method)"
                                        >
                                            <i class="fas fa-search"></i>
                                            <span class="ml-2">ค้นหา</span>
                                        </button>
                                        <button class="btn btn-sm btn-primary ml-2 my-2 my-sm-0" type="button"
                                            v-on:click="getListData(paginate.method)"
                                        >
                                            <i class="fas fa-sync-alt mr-2"></i> ดึงข้อมูลใหม่
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="">
                                <div class="row" v-show="panel.progress">
                                    <div class="col-md-12 text-center">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                            </div>
                                        </div>   
                                        <strong class="text-primary">กำลังดึงข้อมูล</strong>                                     
                                    </div>
                                </div>
                                <div class="row" v-show="panel.list">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">#</th>
                                                    <th scope="col">เลขที่เอกสาร</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>                                                    
                                                    <th scope="col">Approve</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(list,index) in data" :key="index">
                                                    <th scope="row" class="text-center">{{list.index}}</th>
                                                    <td>{{list.itmreq_no}}</td>
                                                    <td>{{list.itmreq_date}}</td>
                                                    <td>{{list.emp_fullname}}</td>
                                                    <td>{{list.it_dept_name}}</td>
                                                    <td>{{list.itmreq_obj}}</td>
                                                    <td>{{list.itmreq_status_str}}</td>
                                                    <td>{{list.approve_str}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm" 
                                                         v-on:click="getItmreqDetail(index)">
                                                            <i class="fas fa-file-alt"></i><span class="ml-2">รายละเอียด</span>
                                                        </button>
                                                        <!-- <a class="btn btn-success btn-sm" :href="'../pdf/itemrequest/'+list.itmreq_id" :target="'_blank'">
                                                            <i class="fas fa-print"></i><span class="ml-2">Print</span>
                                                        </a> -->
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                        v-on:click="getItmreqFile(list.itmreq_id,list.itmreq_status)">
                                                            <i class="far fa-folder-open"></i>
                                                            <span class="ml-2">เอกสาร</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="card-footer small text-muted">
                
                <nav aria-label="Page navigation example" v-show="paginate.show">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#"
                            v-on:click="getDataPaginate('first')" 
                        ><i class="fas fa-angle-double-left"></i></a></li>
                        <li class="page-item"><a class="page-link" href="#" 
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
                        <li class="page-item"><a class="page-link" href="" 
                            v-on:click="getDataPaginate('next')"
                        ><i class="fas fa-angle-right"></i></a></li>
                        <li class="page-item"><a class="page-link" href="" 
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

                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"
                                        v-show="detail.data.header.itmreq_approve_sts == 0"></span>
                                    <span class="ml-2" v-show="detail.data.header.itmreq_approve_sts == 0">รอดำเนินการ</span>

                                    <i class="fas fa-list-ul text-success" v-show="detail.data.header.itmreq_approve_sts == 1"></i>
                                    <span class="ml-2" v-show="detail.data.header.itmreq_approve_sts == 1">อนุมัติแล้ว</span>

                                    <i class="fas fa-ban text-danger" v-show="detail.data.header.itmreq_approve_sts == 2"></i>
                                    <span class="ml-2" v-show="detail.data.header.itmreq_approve_sts == 2">ไม่อนุมัติ</span>

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
                                        <span v-html="detail.data.header.itmreq_no"></span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span v-html="detail.data.header.itmreq_date"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อ-นามสกุล</div>
                                    <div class="bottom-dotted-590 text-center">
                                        <span v-html="detail.data.header.it_emp_fullname"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-265 text-center">
                                        <span v-html="detail.data.header.it_emp_position"></span>
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
                                        <span class="ml-5" v-html="detail.data.header.itmreq_obj"></span>
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
                                                <tr v-for="(list,index) in detail.data.detail" :key="index">
                                                    <td class="w-5 text-center">{{index+1}}</td>
                                                    <td class="ml-5 text-center">{{list.itmreq_barcode}}</td>
                                                    <td class="w-30">{{list.itmreq_item}}</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optoperation_1" name="optoperation" value="" disabled 
                                                                        :checked="list.itmreq_action == 1" >
                                                                    <label class="custom-control-label text-black" for="optoperation_1">สร้าง</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optoperation_2" name="optoperation" value="" disabled
                                                                        :checked="list.itmreq_action == 2" >
                                                                    <label class="custom-control-label text-black" for="optoperation_2">ยกเลิก</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{list.itmreq_prom_sdate}}<br>ถึงวันที่<br>{{list.itmreq_prom_edate}}</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optapp_1" name="optapp" value="" disabled
                                                                        :checked="list.itmreq_app == 1">
                                                                    <label class="custom-control-label text-black" for="optapp_1">AMADO</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optapp_2" name="optapp" value="" disabled
                                                                        :checked="list.itmreq_app == 2">
                                                                    <label class="custom-control-label text-black" for="optapp_2">Kiosk</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optapp_3" name="optapp" value="" disabled
                                                                        :checked="list.itmreq_app == 3">
                                                                    <label class="custom-control-label text-black" for="optapp_3">Dynamics AX</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optapp_4" name="optapp" value="" disabled
                                                                        :checked="list.itmreq_app == 4">
                                                                    <label class="custom-control-label text-black" for="optapp_4">Sale Force</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
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
                                <p v-html="detail.data.header.it_emp_fullname"></p>
                            </div>
                        </div>

                        <hr class="mb-3">
                        <div class="row justify-content-center py-3"
                            v-show="detail.data.header.itmreq_approve_sts == 0">
                            <h5 class="col-md-2 mb-0 my-auto text-center">Approve <i class="fas fa-arrow-right ml-2"></i></h5>

                            <button type="button" class="col-md-3 btn btn-success mr-3"
                                v-on:click="updateItmreqStatus(4)">
                                <i class="fas fa-check text-white"></i> อนุมัติ
                            </button>

                            <button type="button" class="col-md-2 btn btn-danger"
                                v-on:click="updateItmreqStatus(5)">
                                <i class="fas fa-ban text-white"></i> ไม่อนุมัติ
                            </button>

                        </div>

                        <div class="row justify-content-center py-3"
                            v-show="detail.data.header.itmreq_approve_sts == 1">
                            <div class="col-md-5">
                                <div class="alert alert-success text-center" role="alert">
                                    <i class="fas fa-check-circle"></i> <strong>อนุมัติแล้ว</strong>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center py-3"
                            v-show="detail.data.header.itmreq_approve_sts == 2">
                            <div class="col-md-5">
                                <div class="alert alert-danger text-center" role="alert">
                                    <i class="fas fa-ban"></i> <strong>ไม่อนุมัติ</strong>
                                </div>
                            </div>
                        </div>

                        <!-- <hr class="mb-1">
                        <div class="row py-3">
                            <div class="col-md-12">
                                <p class="mb-2">หมายเหตุ</p>
                                <textarea class="form-control" rows="4"></textarea>
                            </div>
                        </div> -->
                        <hr class="pb-3">

                        <div class="row justify-content-center pb-3">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal"><i class="fas fa-times"></i> ปิด</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- Modal : #modal-itemreq-file -->
        <div class="modal fade" id="modal-itemreq-file" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary">
                            <i class="fas fa-folder-open"></i> <span>เอกสาร Item Request</span>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form enctype="multipart/form-data">
                                    <div class='form-group'>
                                        <label>Upload ไฟล์ : </label> <label v-html="paper.upload.status"></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="itmreqUploadFile" aria-describedby="btnitmreqUploadFile"
                                                    v-on:change="onFileChange"
                                                    v-bind:class="{
                                                        'is-void': paper.upload.classInptValid,
                                                        'is-invalid': paper.upload.classInptInvalid
                                                    }"
                                                    :disabled="paper.upload.inptDisabled"
                                                >
                                                <label class="custom-file-label" for="itmreqUploadFile" >Choose file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-secondary" id="btnitmreqUploadFile"
                                                    @click.prevent="uploadItmreqFile"
                                                    :disabled="paper.upload.btnUploadDisabled"
                                                >
                                                    <i class="fas fa-cloud-upload-alt"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="progress my-3" v-show="paper.upload.progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" 
                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>รายการเอกสาร : </label>
                                <ul class="list-group">
                                    <li class="list-group-item py-1"
                                    v-for="(list,index) in paper.list" :key="index">
                                        <strong>#{{index+1}}</strong> File : 
                                        <a class="text-decoration-none text-primary ml-3"
                                        :href="list.itmreq_file_path" :target="'_blank'">
                                        {{list.itmreq_file_name}}</a>
                                        <a href="javascript:;" class="float-right"
                                        v-on:click="removeItmreqFile(index)"
                                        v-show="paper.list.itmreq_status == 1">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times"></i> ออก
                        </button>
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
        this.getListData('wait');
    },
    data(){
        return{
            search_txt: '',
            panel: {
                progress: true,
                list: false
            },
            data: {},
            detail: {
                itmreq_id: '',
                index: '',
                data: {
                    header: {
                        itmreq_status: ''
                    },
                    detail: {}
                }
            },
            paginate: {
                method: 'wait',
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
                show: false
            },
            paper: {
                itmreq_id: '',
                list: [],
                file_name: '',
                file: null,
                upload: {
                    progress: false,
                    status: '',
                    classInptValid: false,
                    classInptInvalid: false,
                    inptDisabled: false,
                    btnUploadDisabled: false
                }
            }
        }
    },
    methods:{
        getListData(status){

            this.paginate.method = status;
            this.panel.progress = true;
            this.panel.list = false;
            this.paginate.show = false;

            axios.post('/api/user/itmreq/approve/list',{
                search: this.search_txt,
                status: this.paginate.method
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            });

        },
        getDataPaginate(page){

            this.panel.progress = true;
            this.panel.list = false;
            this.paginate.show = false;

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post(this.paginate.url,{
                search: this.search_txt,
                status: this.paginate.method
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

            this.paginate.numberPage = resultPageNumber;
            this.data = resultData

            this.panel.progress = false;
            this.panel.list = true;
            this.paginate.show = true;

        },
        getItmreqDetail(index){

            this.detail.itmreq_id = this.data[index].itmreq_id;
            this.detail.index = index;

            axios.post('/api/admin/it/itemrequest/detail',{
                id: this.detail.itmreq_id,
                status: ''
            })
            .then(response => {                
                this.detail.data = response.data;
                if( this.detail.data.admin === null )
                    this.detail.data.admin = { itmreq_assign: '', emp_name: 'ไม่มีผู้รับผิดชอบ' };
                $('#modal-itemreq').modal('show');
            });

        },
        updateItmreqStatus(status){

            
            var title_txt = 'ยืนยันการ Approve';

            if( status == 4 )
                    var content_txt = 'ต้องการ อนุมัติ '+this.data[this.detail.index].itmreq_no+' หรือไม่';
            else
                    var content_txt = 'ต้องการ ไม่อนุมัติ '+this.data[this.detail.index].itmreq_no+' หรือไม่';

            this.$swal({
                title: title_txt,
                text: content_txt,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ยืนยัน',
                denyButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if( result.isConfirmed ){
                    axios.post('/api/admin/it/itemrequest/udstatus',{
                        id: this.detail.itmreq_id,
                        status: status
                    })
                    .then(response => {
                        this.$swal({ icon: 'success', text: 'เปลี่ยนแปลงรายการเรียบร้อยแล้ว' });
                        this.getItmreqDetail(this.detail.index);
                        this.getListData(this.paginate.method);
                    })
                    .catch(response => {
                        this.$swal({ icon: 'error', text: 'ไม่สามารถเปลี่ยนแปลงรายการได้' });
                    });
                }
            });

        },
        getItmreqFile(itmreq_id,status){

            this.paper.itmreq_id = itmreq_id;
            this.paper.upload.progress = false;
            this.paper.upload.status = ''
            this.paper.upload.classInptValid = false;
            this.paper.upload.classInptInvalid = false;
            this.paper.upload.inptDisabled = false;
            this.paper.upload.btnUploadDisabled = false;

            axios.get('/api/admin/it/itemrequest/file/'+this.paper.itmreq_id)
            .then(response => {

                this.paper.list = response.data;
                this.paper.list.itmreq_status = status;
                $('#modal-itemreq-file').modal('show');

            });  
            
        },
        onFileChange(e){
            this.paper.file = e.target.files[0];
            this.paper.file_name = this.paper.file.name;
            this.paper.upload.status = ''
            this.paper.upload.classInptValid = false;
            this.paper.upload.classInptInvalid = false;
            this.paper.upload.inptDisabled = false;
            this.paper.upload.btnUploadDisabled = false;
            $('#itmreqUploadFile').next('.custom-file-label').html(this.paper.file_name);
        },
        uploadItmreqFile(e){

            if( this.paper.file === null ){
                let sts_fail = '<i class="fas fa-exclamation-triangle text-danger mr-2"></i>';
                    sts_fail += '<strong class="text-danger">กรุณาเลือก File</strong>';
                this.paper.upload.status = sts_fail;
                this.paper.upload.classInptValid = false;
                this.paper.upload.classInptInvalid = true;
                return;
            }
            else if( this.paper.file.type != 'application/pdf' ){
                let sts_fail = '<i class="fas fa-exclamation-triangle text-danger mr-2"></i>';
                    sts_fail += '<strong class="text-danger">กรุณาเลือก File PDF เท่านั้น</strong>';
                this.paper.upload.status = sts_fail;
                this.paper.upload.classInptValid = false;
                this.paper.upload.classInptInvalid = true;
                return;
            }
            
            this.paper.upload.status = ''
            this.paper.upload.classInptValid = false;
            this.paper.upload.classInptInvalid = false;
            this.paper.upload.inptDisabled = true;
            this.paper.upload.btnUploadDisabled = true;
            this.paper.upload.progress = true;

            e.preventDefault();

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            let formData = new FormData();
            formData.append('itmreq_id',this.paper.itmreq_id);
            formData.append('file_name',this.paper.file_name);
            formData.append('file',this.paper.file);

            axios.post('/api/admin/it/itemrequest/file/upload',formData,config)
            .then(response => {
                $('#itmreqUploadFile').next('.custom-file-label').html('Choose file');
                this.paper.file == null;
                let sts_sc = '<i class="fas fa-check-circle text-success mr-3"></i>';
                    sts_sc += '<strong class="text-success">Upload file complete.</strong>';
                this.paper.upload.status = sts_sc; 
                this.paper.upload.progress = false;               
                this.paper.upload.classInptValid = true;
                this.paper.upload.classInptInvalid = false;
                this.paper.upload.inptDisabled = false;
                this.paper.upload.btnUploadDisabled = false;        
                this.getItmreqFile(this.paper.itmreq_id,this.paper.list.itmreq_status);  
            })
            .catch(response => {
                this.paper.file == null;
                let sts_fl = '<i class="fas fa-exclamation-triangle text-danger mr-3"></i>';
                    sts_fl += '<strong class="text-danger">Upload file fail!</strong>';
                this.paper.upload.status = sts_fl;      
                this.paper.upload.progress = false;          
                this.paper.upload.classInptValid = false;
                this.paper.upload.classInptInvalid = true;
                this.paper.upload.inptDisabled = false;
                this.paper.upload.btnUploadDisabled = false; 
            });

        },
        removeItmreqFile(index){
            this.$swal({
                icon: 'warning',
                text: 'ยืนยันการลบไฟล์ '+this.paper.list[index].itmreq_file_name,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ลบไฟล์',
                denyButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if( result.isConfirmed ){
                    axios.post('/api/admin/it/itemrequest/file/remove',{
                        file_id: this.paper.list[index].itmreq_file_id,
                        file_path: this.paper.list[index].itmreq_file_path
                    })
                    .then(response => {
                        this.$swal({
                            icon: 'success',
                            title: 'ลบไฟล์เรียบร้อยแล้ว'
                        });
                        this.getItmreqFile(this.paper.itmreq_id,this.paper.list.itmreq_status); 
                    })
                    .catch(response => {
                        this.$swal({
                            icon: 'error',
                            title: 'ไม่สามารถลบไฟล์ได้'
                        });
                    });
                }
            });
        }
    }
}
</script>