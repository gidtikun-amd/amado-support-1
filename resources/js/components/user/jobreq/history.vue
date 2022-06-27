<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-list-ul"></i><span class="ml-2">รายการ Job Request</span>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="col-auto">
                                <label>เลขที่เอกสาร</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกเลขที่เอกสาร..." aria-label="Search" 
                                    v-model="page.searchTxt"
                                    v-on:keyup.enter="searchHistory()"
                                >
                            </div>
                            <div class="col-auto">
                                <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                    v-model="page.searchName"
                                    v-on:keyup.enter="searchHistory()"
                                >
                            </div>
                            <div class="col-auto">
                                <label>จุดประสงค์</label>
                                <select class="form-control form-control-sm"
                                v-model="page.searchObj"
                                v-on:keyup.enter="searchHistory()">                                                            
                                    <option value="">ทั้งหมด</option>
                                    <option value="1">สร้างผู้ใช้ใหม่</option>                                                            
                                    <option value="2">แก้ไขผู้ใช้งาน</option>                                                            
                                    <option value="3">ลบผู้ใช้งาน</option>                                                            
                                    <option value="4">พัฒนาใหม่</option>                                                            
                                    <option value="5">ซื้อ/ติดตั้งอุปกรณ์</option>                                                            
                                    <option value="6">สร้างอีเมลใหม่</option>                                                            
                                    <option value="7">อื่น ๆ</option>
                                </select>
                            </div>
                            <div class="col-auto my-4">
                                <button class="btn btn-sm btn-amado my-2" type="button"
                                    v-on:click="searchHistory()"
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
                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list) in history" :key="list.index">
                                    <td>{{list.index}}</td>
                                    <td>{{list.jobreq_no}}</td>
                                    <td>{{list.jobreq_date}}</td>
                                    <td>{{list.emp_fullname}}</td>
                                    <td>{{list.jobreq_obj}}</td>
                                    <td>{{list.jobreq_status_str}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            v-on:click="getDatajbrDetail(list.jobreq_id)"
                                        ><i class="fas fa-file-alt mr-1"></i> รายละเอียด</button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            :disabled="list.jobreq_status != 1"
                                            v-on:click="confirmVoidJobReq(list.index-1)"
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

        <!-- Modal : #modal-jobreq -->
        <div class="modal fade" id="modal-jobreq" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span class="mr-2">สถานะ : </span>

                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true" v-show="detail.jbr_status == 1"></span>
                                    <span class="ml-2" v-show="detail.jbr_status == 1">รอดำเนินการ</span>

                                    <i class="fas fa-check text-warning" v-show="detail.jbr_status == 2"></i>
                                    <span class="ml-2" v-show="detail.jbr_status == 2">กำลังดำเนินการ</span>

                                    <i class="fas fa-list-ul text-success" v-show="detail.jbr_status == 3"></i>
                                    <span class="ml-2" v-show="detail.jbr_status == 3">ดำเนินการเรียบร้อย</span>

                                    <i class="fas fa-ban text-danger" v-show="detail.jbr_status == 0"></i>
                                    <span class="ml-2" v-show="detail.jbr_status == 0">ยกเลิก</span>

                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mx-4 mt-5">
                        <div class="row m-0 mb-3">
                            <div class="col-md-4">
                                <img class="logo-report" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>JOB REQUEST FORM</b></p>
                                <p><b>AMADO GROUP</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">เลขที่เอกสาร</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span v-html="detail.jbr_no">IT-JOBREQ-20110000001</span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span v-html="detail.jbr_date">19/11/2563</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อ-นามสกุล (T)</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <div class="bottom-dotted-full">
                                        <span class="ml-5" v-html="detail.jbr_fullnameTh">นาย พงศกร เหล่านิยมไทย</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อ-นามสกุล (E)</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <div class="bottom-dotted-full">
                                        <span class="ml-5" v-html="detail.jbr_fullnameEng">Mr. Pongsakorn Laoniyomthai</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">แผนก</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="detail.jbr_deptName">IT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="detail.jbr_posit">Programmer</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">เบอร์โทรศัพท์</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="detail.jbr_tel">083-410-1045</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">จุดประสงค์การดำเนินการ</p>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_1" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj == 1" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_1">สร้างผู้ใช้ใหม่</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_2" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj == 2" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_2">แก้ไขผู้ใช้งาน</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_3" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj == 3" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_3">ลบผู้ใช้งาน</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_4" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj == 4" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_4">พัฒนาใหม่</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_5" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj == 5" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_5">ซื้อ/ติดตั้งอุปกรณ์</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_6" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj == 6" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_6">สร้างอีเมลใหม่</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_7" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj == 7" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_7">อื่น ๆ : </label>
                                            <p class="ml-2" v-html="detail.jbr_other">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">รายการที่ร้องขอดังนี้</p>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">รายละเอียด</th>
                                                <th class="text-center">จำนวน</th>
                                                <th class="text-center">หน่วย</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(list, index) in detail.jbr_detail" :key="index">
                                                    <th class="w-5 text-center">{{index+1}}</th>
                                                    <td class="ml-5">{{list.jobreq_detail}}</td>
                                                    <td class="w-10 text-center">{{list.jobreq_qty}}</td>
                                                    <td class="w-10 text-center">{{list.jobreq_unit}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">ความต้องการ</p>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <p v-html="detail.jbr_demand">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">ประโยชน์ / เหตุผลที่ขอ</p>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <p v-html="detail.jbr_reason">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end m-0">
                            <div class="col-md-4 text-center">
                                <p class="mb-2">ผู้แจ้งขอดำเนินการ</p>
                                <p v-html="detail.jbr_fullnameTh">นาย พงศกร เหล่านิยมไทย</p>
                            </div>
                        </div>
                        <hr class="pb-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <i class="fas fa-paperclip mr-1"></i>
                                    <label>ไฟล์แนบ</label>
                                    <ul class="list-group">                                        
                                        <li class="list-group-item text-center py-1"
                                        v-show="detail.jbr_file.length <= 0">ไม่มีไฟล์แนบ</li>
                                    </ul>
                                    <ul class="list-group">
                                        <li class="list-group-item py-1"
                                            v-for="(list,index) in detail.jbr_file" :key="index"
                                        >
                                            <strong>#{{index+1}}</strong> File : 
                                            <a class="text-decoration-none text-primary ml-3" target="_blank"                                         
                                                :href="'../'+list.jobreqd_filepath"
                                            >{{list.jobreqd_filename}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="from-group"> 
                                    <i class="fas fa-tag mr-1"></i>
                                    <label>หมายเหตุจากเจ้าหน้าที่</label>
                                    <textarea class="form-control" rows="5" readonly
                                        v-model="detail.jbr_remark"
                                    ></textarea>
                                </div>
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
                                                    <b>เอกสาร Job Request</b>
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
                                                            :href="list.jobreq_file_path"
                                                        >{{list.jobreq_file_name}}</a>
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
                                    :href="'../pdf/jobrequest/'+detail.jbr_id" :target="'_blank'"
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

        <!-- Modal : #modal_cnf_voidjobreq -->
        <div class="modal fade" id="modal_cnf_voidjobreq" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fas fa-exclamation-circle text-danger mr-2"></i>Void Job Request</h4>
                    </div>
                    <div class="modal-body" v-html="page.void_content">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success"
                            v-on:click="setVoidJobReq()"
                            v-html="page.void_btntxt"
                            v-show="page.void_saveshow"
                            :disabled="page.void_savedisabled"
                        ><i class="fas fa-check mr-2"></i>ยืนยัน</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            :disabled="page.void_closedisabled"
                        ><i class="fas fa-times mr-2"></i>ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
<script>

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    mounted(){
        this.getHistory();
    },
    data(){
        return{
            page: {
                search: false,
                searchTxt: '',     
                searchName: '',
                searchObj: '',
                void_id: '',
                void_no: '',
                void_content: '',
                void_btntxt: '<i class="fas fa-check mr-2"></i>ยืนยัน</button>',
                void_saveshow: true,
                void_savedisabled: false,
                void_closedisabled: false
            },
            employee: {
                id: this.$session.get('userId'),
                deptId: this.$session.get('departmentId')
            },
            history: [],
            detail: {
                jbr_id: '', jbr_status: '',
                jbr_no: '', jbr_date: '',
                jbr_nameTh: '', jbr_surnameTh: '',
                jbr_nameEng: '', jbr_surnameEng: '',
                jbr_fullnameTh: '', jbr_fullnameEng: '',
                jbr_deptName: '', jbr_posit: '', jbr_tel: '', 
                jbr_obj: '', jbr_other: '',
                jbr_demand: '', jbr_reason: '',
                jbr_remark: '',
                jbr_detail: [],
                jbr_file: []
            },
            files: {
                progress: false,
                data: []
            },
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
            }
        }
    },
    methods:{
        getHistory(){
            axios.post('/api/user/jobreq/history',{
                dept_id: this.employee.deptId,
                paginate: this.paginate.list
            })
            .then(response => {
                this.page.search  = false;
                this.setDataAndPaginate(response.data);
            });
        },
        searchHistory(){
            axios.post('/api/user/jobreq/history/search',{
                search: this.page.searchTxt,
                searchEmp: this.searchName,
                searchObj: this.page.searchObj,
                dept_id: this.employee.deptId,
                paginate: this.paginate.list
            })
            .then(response => {
                this.page.search  = true;
                this.setDataAndPaginate(response.data);
            });
        },
        getDataPaginate(page){
            
            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }
            
            if( this.paginate.url === null || this.paginate.url === undefined )
                return;
            
            let postData = {};

            if( this.page.search )
                postData = { search: this.page.searchTxt, dept_id: this.employee.deptId, paginate: this.paginate.list };
            else 
                postData = { dept_id: this.employee.deptId, paginate: this.paginate.list };
            
            axios.post(this.paginate.url,postData)
            .then(response => { this.setDataAndPaginate(response.data,'second') });  

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

            this.history = resultData
            this.paginate.numberPage = resultPageNumber;
            
        },
        confirmVoidJobReq(index){

            this.page.void_id = this.history[index].jobreq_id;
            this.page.void_no = this.history[index].jobreq_no;
            this.page.void_btntxt = '<i class="fas fa-check mr-2"></i>ยืนยัน</button>';

            let content = 'คุณต้องการยกเลิก Job Request : ';
            content += '<strong>'+this.page.void_no+'</strong> หรือไม่';

            this.page.void_content = content;
            this.page.void_savedisabled = false;
            this.page.void_closedisabled = false;
            this.page.void_saveshow = true;

            $('#modal_cnf_voidjobreq').modal('show');

        },
        setVoidJobReq(){

            let btn = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btn += 'ระบบกำลังบันทึกข้อมูล...';

            this.page.void_btntxt = btn;
            this.page.void_savedisabled = true;
            this.page.void_closedisabled = true;

            axios.post('/api/user/jobreq/udstatus',{
                id: this.page.void_id,
                user: this.$session.get('userId'),
                status: 0
            })
            .then(response => {

                let content = '<i class="fas fa-check-circle text-success"></i> ';
                content += 'ระบบยกเลิก Job Request : ';
                content += '<strong>'+this.page.void_no+'</strong> เรียบร้อยแล้ว';

                this.page.void_content = content;
                this.page.void_saveshow = false;
                this.page.void_closedisabled = false;

                this.getDataPaginate(this.paginate.currentPage);

            })
            .catch(response => {

                let content = '<i class="fas fa-exclamation-circle text-danger"></i> ';
                content += 'ระบบไม่สามารถยกเลิก Job Request : ';
                content += '<strong>'+this.page.void_no+'</strong> ได้';

                this.page.void_content = content;
                this.page.void_btntxt = '<i class="fas fa-check mr-2"></i>ยืนยัน</button>';
                this.page.void_saveshow = false;
                this.page.void_savedisabled = false;
                this.page.void_closedisabled = false;

            });

        },
        getDatajbrDetail(jobreqId){
            axios.get('/api/user/jobreq/detail/'+jobreqId)
            .then(response => {

                let resHeader = response.data.header,
                    resDetail = response.data.detail,
                    resFiles = response.data.files;
                
                this.detail.jbr_id = jobreqId;
                this.detail.jbr_status = resHeader.jobreq_status;
                this.detail.jbr_no = resHeader.jobreq_no;
                this.detail.jbr_date = resHeader.jobreq_date;                
                this.detail.jbr_nameTh = resHeader.it_emp_name_th;                
                this.detail.jbr_surnameTh = resHeader.it_emp_surname_th;                
                this.detail.jbr_nameEng = resHeader.it_emp_name_eng;                
                this.detail.jbr_surnameEng = resHeader.it_emp_surname_eng;
                this.detail.jbr_fullnameTh = this.detail.jbr_nameTh+' '+this.detail.jbr_surnameTh;
                this.detail.jbr_fullnameEng = this.detail.jbr_nameEng+' '+this.detail.jbr_surnameEng;                
                this.detail.jbr_deptName = resHeader.it_dept_name;                
                this.detail.jbr_posit = resHeader.it_emp_position;               
                this.detail.jbr_tel = resHeader.it_emp_tel;                
                this.detail.jbr_obj = resHeader.jobreq_obj;               
                this.detail.jbr_other = resHeader.jobreq_other;                             
                this.detail.jbr_demand = resHeader.jobreq_demand;                          
                this.detail.jbr_reason = resHeader.jobreq_reason;
                this.detail.jbr_remark = resHeader.jobreq_remark;
                this.detail.jbr_detail = resDetail;
                this.detail.jbr_file = resFiles;

                this.getJobreFile(jobreqId);
                $('#modal-jobreq').modal('show');

            });
        },
        getJobreFile(jobreqId){

            this.files.progress = true;

            axios.get('/api/admin/it/jobrequest/file/'+jobreqId)
            .then(response => {
                this.files.data = response.data;
                this.files.progress = false;
            });  
            
        }

    }
}
</script>