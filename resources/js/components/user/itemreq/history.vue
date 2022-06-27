<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-list-ul"></i><span class="ml-2">รายการ Item Request</span>
            </div>
            
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="col-auto">
                                <label>เลขที่เอกสาร</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" 
                                placeholder="กรอกเลขที่เอกสาร..." aria-label="Search" 
                                    v-model="search_txt"
                                    v-on:keyup.enter="getListItmReq()"
                                >
                            </div>
                            <div class="col-auto">
                                <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                    v-model="searchName"
                                    v-on:keyup.enter="getListItmReq()"
                                >
                            </div>
                            <div class="col-auto my-4">
                                <button class="btn btn-sm btn-amado my-2" type="button"
                                    v-on:click="getListItmReq()"
                                >
                                    <i class="fas fa-search"></i>
                                    <span class="ml-2">ค้นหา</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                                    <!-- <th>Approve</th> -->
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
                                    <td>{{lst.itmreq_status_str}}</td>
                                    <!-- <td>{{lst.approve_str}}</td> -->
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-itemreq"
                                            v-on:click="showDetailItmReq(lst.index-1)"
                                        >
                                            <i class="fas fa-file-alt mr-1"></i> รายละเอียด
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            :disabled="lst.itmreq_status != 1" 
                                            v-on:click="voidItmReq(lst.index-1)"
                                        ><i class="fas fa-ban mr-1"></i> ยกเลิก</button>
                                    </td>
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

                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"
                                        v-show="detail.header.itmreq_status == 1"
                                    ></span>
                                    <span class="ml-2" v-show="detail.header.itmreq_status == 1">รอดำเนินการ</span>

                                    <i class="fas fa-check text-warning" v-show="detail.header.itmreq_status == 2"></i>
                                    <span class="ml-2" v-show="detail.header.itmreq_status == 2">กำลังดำเนินการ</span>

                                    <i class="fas fa-list-ul text-success" v-show="detail.header.itmreq_status == 3"></i>
                                    <span class="ml-2" v-show="detail.header.itmreq_status == 3">ดำเนินการเรียบร้อย</span>

                                    <i class="fas fa-ban text-danger" v-show="detail.header.itmreq_status == 0"></i>
                                    <span class="ml-2" v-show="detail.header.itmreq_status == 0">ยกเลิก</span>

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
                                        <span v-html="detail.header.itmreq_no">IT-ITEMREQ-20120000006</span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span v-html="detail.header.itmreq_date">11/12/2563</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อ-นามสกุล</div>
                                    <div class="bottom-dotted-590 text-center">
                                        <span v-html="detail.header.it_emp_fullname">กมลชนก ขุนทอง</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-265 text-center">
                                        <span v-html="detail.header.it_emp_position">RD</span>
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
                                        <span class="ml-5" v-html="detail.header.itmreq_obj">เปลี่ยนชื่อสินค้า Gold Caviar</span>
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
                                                <tr v-for="(list, index) in detail.detail" :key="index">
                                                    <td class="w-5 text-center">{{index+1}}</td>
                                                    <td class="ml-5 text-center">{{list.itmreq_barcode}}</td>
                                                    <td class="w-30">{{list.itmreq_item}}</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optoperation_1" :name="'optoperation'+index" value="" disabled 
                                                                        :checked="list.itmreq_action == 1" >
                                                                    <label class="custom-control-label text-black" for="optoperation_1">สร้าง</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optoperation_2" :name="'optoperation'+index" value="" disabled
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
                                                                    <input type="radio" class="custom-control-input" id="optapp_1" :name="'optapp'+index" value="" disabled
                                                                        :checked="list.itmreq_app == 1">
                                                                    <label class="custom-control-label text-black" for="optapp_1">AMADO</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optapp_2" :name="'optapp'+index" value="" disabled
                                                                        :checked="list.itmreq_app == 2">
                                                                    <label class="custom-control-label text-black" for="optapp_2">Kiosk</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optapp_3" :name="'optapp'+index" value="" disabled
                                                                        :checked="list.itmreq_app == 3">
                                                                    <label class="custom-control-label text-black" for="optapp_3">Dynamics AX</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="optapp_4" :name="'optapp'+index" value="" disabled
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
                                <p v-html="detail.header.it_emp_fullname">กมลชนก ขุนทอง</p>
                            </div>
                        </div>

                        <hr>
                        <div class="row" v-show="detail.header.itmreq_status == 0">
                            <div class="col-12">
                                <span>หมายเหตุ : </span> <span>{{detail.header.itmreq_remark}}</span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="m-0">
                                                    <i class="fas fa-folder-open mr-2"></i>
                                                    <b>เอกสาร Item Request</b>
                                                </h6>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">                                            
                                            <div class="col-12" v-show="files.progress">
                                                <div class="progress my-3">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" 
                                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                                </div>
                                            </div>
                                            <div class="col-12" v-show="!files.progress">
                                                <label>รายการเอกสาร : </label>
                                                <ul class="list-group">
                                                    <li class="list-group-item py-1"
                                                        v-for="(list,index) in files.data" :key="index"
                                                    >
                                                        <strong>#{{index+1}}</strong> File : 
                                                        <a class="text-decoration-none text-primary ml-3" target="_blank"                                         
                                                            :href="list.itmreq_file_path"
                                                        >{{list.itmreq_file_name}}</a>
                                                    </li>
                                                    <li class="list-group-item text-center py-1"
                                                    v-show="files.data.length <= 0"><b>ไม่มีไฟล์เอกสาร</b></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="pb-3">

                        <div class="row justify-content-center pb-3">
                            <div class="col-md-4">
                                <a type="button" class="btn btn-primary w-100"
                                    :href="'../pdf/itemrequest/'+detail.itmreq_id" :target="'_bland'"
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
        this.getListItmReq();
    },
    data(){
        return{
            search_txt: '',
            searchName: '',
            paginate: {
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
        getListItmReq(){
            axios.post('/api/user/itmreq/history/list',{
                search: this.search_txt,
                searchEmp: this.searchName,
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
                search: this.search_txt,
                searchEmp: this.searchName,
            })
            .then(response => { this.setDataAndPaginate(response.data) }); 

        },
        voidItmReq(index){
            this.$swal({
                icon: 'warning',
                title: 'ยืนยันการยกเลิก Item Request',
                text: 'ต้องการยกเลิก '+this.list[index].itmreq_no+' หรือไม่',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ยืนยัน',
                denyButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if(result.isConfirmed){
                    axios.post('/api/user/itmreq/udstatus',{
                        id: this.list[index].itmreq_id,
                        status: 0
                    })
                    .then(response => {
                            this.$swal({ icon: 'success', text: 'ยกเลิก '+this.list[index].itmreq_no+' เรียบร้อยแล้ว' });
                            this.getDataPaginate(this.paginate.numberPagin);
                    })
                    .catch(response => {
                        console.log(response);
                        this.$swal({ icon: 'error', text: 'ยกเลิก '+this.list[index].itmreq_no+' ไม่สำเร็จ2' });
                    });
                }
            });
        },
        showDetailItmReq(index){
            axios.post('/api/admin/it/itemrequest/detail',{
                id: this.list[index].itmreq_id,
                status: '' 
            })
            .then(response => {
                this.detail.itmreq_id = this.list[index].itmreq_id;
                this.detail.header = response.data.header;
                this.detail.detail = response.data.detail;
                this.getItmreqFile();
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