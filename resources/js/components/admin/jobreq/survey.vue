<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-list-ul"></i><span class="ml-2">รายการประเมินความพึงพอใจ</span>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs" id="" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="wait-tab" data-toggle="tab" href="#wait" 
                                role="tab" aria-controls="wait" aria-selected="true"
                                v-on:click="getListJobreqSurvey('wait')">
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รอประเมิน</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="success-tab" data-toggle="tab" href="#success" 
                                role="tab" aria-controls="success" aria-selected="false"
                                v-on:click="getListJobreqSurvey('success')">
                                    <i class="fas fa-check text-success"></i>
                                    <span class="ml-2">ประเมินสำเร็จ</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="">

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <label>เลขที่เอกสาร</label>
                                            <input class="form-control form-control-sm mr-sm-2" type="search" 
                                            placeholder="กรอกเลขที่เอกสาร..." aria-label="Search"
                                            v-model="searchText"
                                            v-on:keyup.enter="getListJobreqSurvey(paginate.method)"
                                            :disabled="disabled.searchText">
                                        </div>
                                        <div class="col-auto">
                                            <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                            v-model="searchName"
                                            v-on:keyup.enter="getListJobreqSurvey(paginate.method)"
                                            :disabled="disabled.searchText">
                                        </div>
                                        <div class="col-auto my-4">
                                            <button class="btn btn-sm btn-amado my-2" type="button"
                                            v-on:click="getListJobreqSurvey(paginate.method)"
                                            :disabled="disabled.searchBtn">
                                                <i class="fas fa-search"></i>
                                                <span class="ml-2">ค้นหา</span>
                                            </button>
                                            <button class="btn btn-sm btn-primary ml-2" type="button"
                                            v-on:click="getListJobreqSurvey(paginate.method)"
                                            :disabled="disabled.dataReload">
                                                <i class="fas fa-sync-alt mr-2"></i> ดึงข้อมูลใหม่
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row  mb-3" v-show="panel.progress">
                                <div class="col-md-12 text-center">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                        </div>
                                    </div>
                                    <strong class="text-primary">กำลังดึงข้อมูล</strong>
                                </div>
                            </div>

                            <div class="tab-pane fade show active" id="wait" role="tabpanel" aria-labelledby="wait-tab">
                                <div class="row">
                                    <!-- <div class="col-md-12 text-center mt-2" hidden>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-12">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่เอกสาร</th>
                                                    <th>ผู้ร้องขอ</th>
                                                    <th>จุดประสงค์</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(list_w,index) in jobreq.wait" :key="index">
                                                    <td>{{list_w.index}}</td>
                                                    <td>{{list_w.jobreq_no}}</td>
                                                    <td>{{list_w.jobreq_date}}</td>
                                                    <td>{{list_w.emp_fullname}}</td>
                                                    <td>{{list_w.jobreq_obj}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="success" role="tabpanel" aria-labelledby="success-tab">
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2" hidden>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่เอกสาร</th>
                                                    <th>ผู้ร้องขอ</th>
                                                    <th>จุดประสงค์</th>
                                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(list_s,index) in jobreq.success" :key="index">
                                                    <td>{{list_s.index}}</td>
                                                    <td>{{list_s.jobreq_no}}</td>
                                                    <td>{{list_s.jobreq_date}}</td>
                                                    <td>{{list_s.emp_fullname}}</td>
                                                    <td>{{list_s.jobreq_obj}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                        v-on:click="getSurveyDetail(list_s.jobreq_id)">
                                                            <i class="fas fa-file-alt mr-1"></i> รายละเอียด
                                                        </button>
                                                        <!-- <a type="button" class="btn btn-success btn-sm" 
                                                        :href="'/pdf/survey/ticket/'+list_s.jobreq_id"  
                                                        target="_blank">
                                                            <i class="fas fa-print mr-1"></i> Print
                                                        </a> -->
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


        <!-- Modal : #modal-survey-form -->
        <div class="modal fade" id="modal-survey-form" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span><b>เลขที่คำขอ </b><span class="ml-2">{{survey.jobreq.header.jobreq_no}}</span></span>
                                    <span class="mx-3">|</span>
                                    <span><b>วันที่สร้าง </b><span class="ml-2">{{survey.jobreq.header.jobreq_date}}</span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="mr-4"><b>ชื่อ-นามสกุล </b><span class="ml-2">{{survey.jobreq.header.emp_fullname}}</span></span>
                                <span><b>ฝ่าย </b><span class="ml-2 mr-4">{{survey.jobreq.header.it_dept_name}}</span></span>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row mb-3">
                            <div class="col-auto my-auto">
                                <h5 class="card-title m-0"><b>จุดประสงค์การดำเนินการ : </b> พัฒนาใหม่</h5>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p class="card-text mb-0"><b>รายละเอียด : </b></p>
                                <p class="card-text mb-0"
                                v-for="(jobr_d, index) in survey.jobreq.detail" :key="index">
                                    {{index+1}}.) {{jobr_d.jobreq_detail}} {{jobr_d.jobreq_qty}} {{jobr_d.jobreq_unit}}
                                </p>
                            </div>
                        </div>
                        <!-- <hr class="mb-3">
                        <div class="row">
                            <div class="col-auto my-auto"><p class="card-text m-0"><b>ไฟล์ : </b></p></div>
                            <div class="col">
                                <div class="form-group col-md-12 p-0 mb-0">
                                    <ul class="list-group">
                                        <li class="list-group-item py-1" v-for="(file,index) in survey.files" :key="index">
                                            <h5 class="d-inline mr-3">#{{index+1}}</h5>
                                            <span>Url : </span>
                                            <span class="badge badge-light">
                                                <a :href="file.it_ticket_file_path" target="_blank" 
                                                class="text-decoration-none">{{file.it_ticket_file}}</a>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> -->
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col">
                                <p class="card-text mb-0"><b>ผู้ดำเนินการ : </b>
                                <span>{{survey.jobreq.admin.emp_name}} ({{survey.jobreq.admin.jobreq_assign}})</span>
                                </p>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col">
                                <p class="card-text mb-0">
                                    <b>ผลการประเมิน : </b>
                                    <span class="text-danger font-weight-bolder ml-2">{{survey.assessment.psscore}}%</span>
                                </p>
                            </div>
                        </div>
                        <hr class="mb-3">

                        <div class="row m-0 mt-4 mb-3">
                            <div class="col-md-12 p-0">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="mb-0">
                                                    <b>
                                                        <i class="fas fa-poll text-amado mr-2"></i>
                                                        <span>แบบประเมินความพึงพอใจ</span>
                                                    </b>
                                                </h5>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="mb-3">
                                                    <b><span>การประเมินด้านการใช้งานระบบ</span></b>
                                                </h6>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2" class="text-center align-middle" scope="col">#</th>
                                                            <th rowspan="2" class="text-center align-middle" scope="col">รายการประเมิน</th>
                                                            <th colspan="5" class="text-center align-middle" scope="col">ระดับความพึงพอใจ</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center" scope="col">มากสุด</th>
                                                            <th class="text-center" scope="col">มาก</th>
                                                            <th class="text-center" scope="col">ปานกลาง</th>
                                                            <th class="text-center" scope="col">น้อย</th>
                                                            <th class="text-center" scope="col">น้อยมาก</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(score,index) in survey.detail" :key="index">
                                                            <th class="text-center" scope="row">{{index+1}}</th>
                                                            <td>{{score.subject_text}}</td>
                                                            <td class="text-center">
                                                                <input type="radio" class="form-check-input position-relative m-0" :name="'optsurvey'+index" value="5"
                                                                v-model="score.survey_level"
                                                                :checked="score.survey_level == 5"
                                                                :disabled="survey.assessment.inputDisabled"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 0">
                                                                <span class="p-1"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 1 && score.survey_level == 5">
                                                                    <i class="fas fa-check text-success"></i>
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="radio" class="form-check-input position-relative m-0" :name="'optsurvey'+index" value="4"
                                                                v-model="score.survey_level"
                                                                :checked="score.survey_level == 4"
                                                                :disabled="survey.assessment.inputDisabled"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 0">
                                                                <span class="p-1"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 1 && score.survey_level == 4">
                                                                    <i class="fas fa-check text-success"></i>
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="radio" class="form-check-input position-relative m-0" :name="'optsurvey'+index" value="3"
                                                                v-model="score.survey_level"
                                                                :checked="score.survey_level == 3"
                                                                :disabled="survey.assessment.inputDisabled"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 0">
                                                                <span class="p-1"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 1 && score.survey_level == 3">
                                                                    <i class="fas fa-check text-success"></i>
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="radio" class="form-check-input position-relative m-0" :name="'optsurvey'+index" value="2"
                                                                v-model="score.survey_level"
                                                                :checked="score.survey_level == 2"
                                                                :disabled="survey.assessment.inputDisabled"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 0">
                                                                <span class="p-1"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 1 && score.survey_level == 2">
                                                                    <i class="fas fa-check text-success"></i>
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="radio" class="form-check-input position-relative m-0" :name="'optsurvey'+index" value="1"
                                                                v-model="score.survey_level"
                                                                :checked="score.survey_level == 1"
                                                                :disabled="survey.assessment.inputDisabled"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 0">
                                                                <span class="p-1"
                                                                v-show="survey.jobreq.header.jobreq_svsts == 1 && score.survey_level == 1">
                                                                    <i class="fas fa-check text-success"></i>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <h6 class="mb-3">
                                            <b><span>ผลการทดสอบ</span></b>
                                        </h6>
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <textarea class="form-control" rows="3"
                                                v-model="survey.header.survey_result"
                                                v-show="survey.jobreq.header.jobreq_svsts == 0"
                                                :disabled="survey.assessment.inputDisabled"></textarea>
                                                <span v-show="survey.jobreq.header.jobreq_svsts == 1">
                                                    {{survey.header.survey_result}}
                                                </span>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <h6 class="mb-3">
                                            <b><span>ข้อเสนอแนะ</span></b>
                                        </h6>
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <textarea class="form-control" rows="3"
                                                v-model="survey.header.survey_remark"
                                                v-show="survey.jobreq.header.jobreq_svsts == 0"
                                                :disabled="survey.assessment.inputDisabled"></textarea>
                                                <span v-show="survey.jobreq.header.jobreq_svsts == 1">
                                                    {{survey.header.survey_remark}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-0 justify-content-center">
                                            <button type="button" class="col-md-3 btn btn-primary mr-3"
                                            v-show="!survey.assessment.inputDisabled"
                                            :disabled="survey.assessment.btnDisabled"
                                            v-html="survey.assessment.btnContent"
                                            v-on:click="saveAssessment()">
                                                <i class="fas fa-paper-plane"></i><span class="ml-2">ส่งแบบสอบถาม</span>
                                            </button>
                                            <button type="button" class="col-md-2 btn btn-secondary mr-3" data-dismiss="modal"
                                            :disabled="survey.assessment.btnCloseDisabled">
                                                <i class="fas fa-times"></i><span class="ml-2">ยกเลิก</span>
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

    </div>
</template>
<script>

import VueSession from 'vue-session'
Vue.use(VueSession);

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

export default {
    mounted(){
        this.getListJobreqSurvey(this.paginate.method);
    },
    data(){
        return{
            searchText: '',     
            searchName: '',
            department: this.$session.get('departmentId'),
            panel: {
                progress: false
            },
            disabled: {
                searchText: false,
                searchBtn: false,
                dataReload: false
            },    
            ticket: {
                wait:{},
                success:{}
            },
            jobreq: {
                wait:{},
                success:{}
            },
            survey:{
                assessment: {
                    inputDisabled: false,
                    btnCloseDisabled: false,
                    btnDisalbeld: false,
                    btnContent: '<i class="fas fa-paper-plane"></i><span class="ml-2">ส่งแบบสอบถาม</span>',
                    ttscore: 0,
                    psscore: 0
                },
                header: {},
                detail: {},
                jobreq: {
                    header: {},
                    detail: {},
                    admin: {}
                },
                files: {}
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
                show: true
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
        getListJobreqSurvey(status){

            this.paginate.method = status;
            this.disabled.searchText = true;
            this.disabled.searchBtn = true;
            this.disabled.dataReload = true;
            this.panel.progress = true;

            axios.post('/api/user/jobreq/survey/list',{
                page: 'admin',
                search: this.searchText,
                searchEmp: this.searchName,
                surv_sts: (this.paginate.method == 'wait' ? 0 : 1),
                dept_id: this.department,
                paginate: this.paginate.list
            }).then(response => {this.setDataAndPaginate(response.data)});

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
            this.jobreq.wait = this.paginate.method == 'wait' ? resultData : {};
            this.jobreq.success = this.paginate.method == 'success' ? resultData : {};
            
            this.disabled.searchText = false;
            this.disabled.searchBtn = false;
            this.disabled.dataReload = false;
            this.panel.progress = false;

        },
        getDataPaginate(page){

            this.paginate.method = status;
            this.disabled.searchText = true;
            this.disabled.searchBtn = true;
            this.disabled.dataReload = true;
            this.panel.progress = true;

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post('/api/user/ticket/survey',{
                page: 'admin',
                search: this.searchText,
                searchEmp: this.searchName,
                surv_sts: (this.paginate.method == 'wait' ? 0 : 1),
                dept_id: this.department,
                paginate: this.paginate.list
            }).then(response => {this.setDataAndPaginate(response.data)});

        },
        getSurveyDetail(id){
            axios.post('/api/user/jobreq/survey/detail',{
                id: id
            })
            .then(response => {
                
                this.survey.jobreq.header = response.data.jobreq.header;
                this.survey.jobreq.detail = response.data.jobreq.detail;
                this.survey.jobreq.admin = response.data.jobreq.admin;

                if(response.data.survey.header.length <= 0)
                    this.survey.header = {};
                else this.survey.header = response.data.survey.header;

                this.survey.detail = response.data.survey.detail;

                if(
                    // this.survey.jobreq.header.it_emp_id == this.$session.get('user') && 
                    this.survey.jobreq.header.jobreq_svsts == 0
                ) this.survey.assessment.inputDisabled = false;
                else this.survey.assessment.inputDisabled = true;

                this.survey.assessment.ttscore = 0;
                var point = this.survey.detail.length;

                for(var i = 0; i < point; i++)
                    this.survey.assessment.ttscore += this.survey.detail[i].survey_level;

                var persent = ( this.survey.assessment.ttscore / ( point * 5 ) ) * 100;
                this.survey.assessment.psscore = persent.toFixed(2);

                $('#modal-survey-form').modal('show');
            });
        },
        saveAssessment(){

            if(!this.checkSeessionLogin())
                return;
            
            for(var i = 0; i < this.survey.detail.length; i++){
                if(this.survey.detail[i].survey_level == 0){
                    this.$swal({
                        icon: 'error',
                        text: 'กรุณาประเมิน'+this.survey.detail[i].subject_text
                    });
                    return;
                }
            }

            if(this.survey.header.survey_result == ''){
                this.$swal({icon: 'error', 'text': 'กรุณากรอกผลการทดสอบ'});
                return;
            }


            this.$swal({
                title: 'ยืนยันการส่งแบบประเมิน',
                text: "คุณต้องการส่งแบบประเมินหรือไม่",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ส่งแบบประเมิน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed){

                    let btnContent = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
                        btnContent += 'ระบบกำลังบันทึกข้อมูล...';

                    this.survey.assessment.inputDisabled = true;
                    this.survey.assessment.btnCloseDisabled = true;
                    this.survey.assessment.btnDisalbeld = true;
                    this.survey.assessment.btnContent = btnContent;

                    axios.post('/api/user/jobreq/survey/save',{
                        user: this.$session.get('user'),
                        dataset: this.survey
                    })
                    .then(response => {
                        $('#modal-survey-form').modal('hide');
                        this.$swal({incon: 'success', text: 'บันทึกแบบประเมินเรียบร้อยแล้ว'});
                        this.survey.assessment.inputDisabled = false;
                        this.survey.assessment.btnCloseDisabled = false;
                        this.survey.assessment.btnDisalbeld = false;
                        this.survey.assessment.btnContent = '<i class="fas fa-paper-plane"></i><span class="ml-2">ส่งแบบสอบถาม</span>';
                        $('#modal-survey-form').modal('hide');
                        this.getListJobreqSurvey(this.paginate.method);
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกแบบประเมินได้'});
                        this.survey.assessment.inputDisabled = false;
                        this.survey.assessment.btnCloseDisabled = false;
                        this.survey.assessment.btnDisalbeld = false;
                        this.survey.assessment.btnContent = '<i class="fas fa-paper-plane"></i><span class="ml-2">ส่งแบบสอบถาม</span>';
                    });

                }
            });
        }
    }
}
</script>