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
                                <a class="nav-link active" id="success-tab" data-toggle="tab" href="#success"
                                role="tab" aria-controls="success" aria-selected="false"
                                v-on:click="getListTicketSurvey('success')">
                                    <i class="fas fa-check text-success"></i>
                                    <span class="ml-2">ประเมินสำเร็จ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="wait-tab" data-toggle="tab" href="#wait" role="tab"
                                aria-controls="wait" aria-selected="true"
                                v-on:click="getListTicketSurvey('wait')">
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รอประเมิน</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="">

                            <div class="row">
                                <div class="col-md-10 mt-3">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <label>เลขที่เอกสาร</label>
                                            <input class="form-control form-control-sm mr-sm-2" type="search"
                                            placeholder="กรอกเลขที่เอกสาร..." aria-label="Search"
                                            v-model="searchText"
                                            v-on:keyup.enter="getListTicketSurvey(paginate.method)"
                                            :disabled="disabled.searchText">
                                        </div>
                                        <div class="col-auto">
                                            <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                            v-model="searchName"
                                            v-on:keyup.enter="getListTicketSurvey(paginate.method)"
                                            :disabled="disabled.searchText">
                                        </div>
                                        <div class="col-auto my-4">
                                            <button class="btn btn-sm btn-amado my-2" type="button"
                                            v-on:click="getListTicketSurvey(paginate.method)"
                                            :disabled="disabled.searchBtn">
                                                <i class="fas fa-search mr-2"></i>ค้นหา
                                            </button>
                                            <button class="btn btn-sm btn-primary ml-2" type="button"
                                            v-on:click="getListTicketSurvey(paginate.method)"
                                            :disabled="disabled.dataReload">
                                                <i class="fas fa-sync-alt mr-2"></i>ดึงข้อมูลใหม่
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col mt-3">
                                    <div class="form-inline float-right mb-3">
                                        <button class="btn btn-sm btn-success ml-2 my-2 my-sm-0" type="button">
                                            <i class="fas fa-file-excel mr-2"></i> Export Excel
                                        </button>
                                    </div>
                                </div> -->
                            </div>

                            <div class="row mb-3" v-show="panel.progress">
                                <div class="col-md-12 text-center">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                        </div>
                                    </div>
                                    <strong class="text-primary">กำลังดึงข้อมูล</strong>
                                </div>
                            </div>

                            <div class="tab-pane fade show active" id="success" role="tabpanel" aria-labelledby="success-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่เอกสาร</th>
                                                    <th>ผู้ร้องขอ</th>
                                                    <th>ฝ่าย</th>
                                                    <th>เรื่อง</th>
                                                    <th>ผู้ดำเนินการ</th>
                                                    <th>วัน-เวลาปิดงาน</th>
                                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(listS,index) in ticket.success" :key="index">
                                                    <td>{{listS.index}}</td>
                                                    <td>{{listS.ticket_no}}</td>
                                                    <td>{{listS.ticket_pdate}}</td>
                                                    <td>{{listS.emp_name}}</td>
                                                    <td>{{listS.dept_name}}</td>
                                                    <td>{{listS.subject_name}}</td>
                                                    <td>{{listS.assign_name}}</td>
                                                    <td>{{listS.ticket_edate}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                        v-on:click="getSurveyDetail(listS.ticket_id)">
                                                            <i class="fas fa-file-alt mr-2"></i>รายละเอียด
                                                        </button>
                                                        <a type="button" class="btn btn-success btn-sm mb-1" :href="'/pdf/survey/ticket/'+listS.ticket_id"  target="_blank">
                                                            <i class="fas fa-print mr-2"></i>Print
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="wait" role="tabpanel" aria-labelledby="wait-tab">
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
                                                    <th>ฝ่าย</th>
                                                    <th>เรื่อง</th>
                                                    <th>ผู้ดำเนินการ</th>
                                                    <th>วัน-เวลาปิดงาน</th>
                                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(listW,index) in ticket.wait" :key="index">
                                                    <td>{{listW.index}}</td>
                                                    <td>{{listW.ticket_no}}</td>
                                                    <td>{{listW.ticket_pdate}}</td>
                                                    <td>{{listW.emp_name}}</td>
                                                    <td>{{listW.dept_name}}</td>
                                                    <td>{{listW.subject_name}}</td>
                                                    <td>{{listW.assign_name}}</td>
                                                    <td>{{listW.ticket_edate}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                        v-on:click="getSurveyDetail(listW.ticket_id)">
                                                            <i class="fas fa-file-alt mr-2"></i>รายละเอียด
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span><b>เลขที่คำขอ </b><span class="ml-2">{{survey.ticket.detail.ticket_no}}</span></span>
                                    <span class="mx-3">|</span>
                                    <span><b>วันที่สร้าง </b><span class="ml-2">{{survey.ticket.detail.ticket_date}}</span></span>
                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="mr-4"><b>ชื่อ-นามสกุล </b><span class="ml-2">{{survey.ticket.detail.emp_name}}</span></span>
                                <span><b>ฝ่าย </b><span class="ml-2 mr-4">{{survey.ticket.detail.detp_name}}</span></span>
                                <span><b>ติดต่อกลับ </b><span class="ml-2">{{survey.ticket.detail.ticket_callback}}</span></span>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row mb-3">
                            <div class="col-auto my-auto">
                                <h5 class="card-title m-0"><b>เรื่อง : {{survey.ticket.detail.subject_name}}</b></h5>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p class="card-text mb-0"><b>รายละเอียด : </b><span>{{survey.ticket.detail.ticket_detail}}</span></p>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-auto my-auto"><p class="card-text m-0"><b>ไฟล์ : </b></p></div>
                            <div class="col">
                                <div class="form-group col-md-12 p-0 mb-0">
                                    <ul class="list-group">
                                        <li class="list-group-item py-1" v-for="(file,index) in survey.ticket.files" :key="index">
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
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col text-center">
                                <p class="card-text mb-0"><b>ระดับความสำคัญของ Ticket : </b>
                                    <span class="badge badge-pill badge-success"
                                    v-show="survey.ticket.detail.ticket_level == 1">Low</span>
                                    <span class="badge badge-pill badge-warning"
                                    v-show="survey.ticket.detail.ticket_level == 2">Medium</span>
                                    <span class="badge badge-pill badge-danger"
                                    v-show="survey.ticket.detail.ticket_level == 3">High</span>
                                </p>
                            </div>
                            <div class="col text-center">
                                <p class="card-text mb-0"><b>ผู้ดำเนินการ : </b><span>{{survey.ticket.detail.assign_name}}</span></p>
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
                                                    <b><span>การประเมินความพึงพอใจการแก้ไขปัญหา</span></b>
                                                </h6>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2" class="text-center" scope="col">#</th>
                                                            <th rowspan="2" class="text-center" scope="col">รายการประเมิน</th>
                                                            <th colspan="5" class="text-center" scope="col">ระดับความพึงพอใจ</th>
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
                                                        <tr v-for="(score,index) in survey.detail.scores" :key="index">
                                                            <th class="text-center" scope="row">{{index+1}}</th>
                                                            <td>{{score.subject_text}}</td>
                                                            <td class="text-center"><span class="p-1"
                                                            v-show="score.survey_scores == 5"><i class="fas fa-check text-success"></i></span></td>
                                                            <td class="text-center"><span class="p-1"
                                                            v-show="score.survey_scores == 4"><i class="fas fa-check text-success"></i></span></td>
                                                            <td class="text-center"><span class="p-1"
                                                            v-show="score.survey_scores == 3"><i class="fas fa-check text-success"></i></span></td>
                                                            <td class="text-center"><span class="p-1"
                                                            v-show="score.survey_scores == 2"><i class="fas fa-check text-success"></i></span></td>
                                                            <td class="text-center"><span class="p-1"
                                                            v-show="score.survey_scores == 1"><i class="fas fa-check text-success"></i></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <h6 class="mb-3">
                                            <b><span>คุณพึงพอใจในการแก้ไขปัญหาครั้งนี้ขนาดใด</span></b>
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" scope="col">มากสุด</th>
                                                            <th class="text-center" scope="col">มาก</th>
                                                            <th class="text-center" scope="col">ปานกลาง</th>
                                                            <th class="text-center" scope="col">น้อย</th>
                                                            <th class="text-center" scope="col">น้อยมาก</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">
                                                                <span class="p-1" v-show="survey.detail.header.level_overall == 5"><i class="fas fa-check text-success"></i></span>
                                                            </td><td class="text-center">
                                                                <span class="p-1" v-show="survey.detail.header.level_overall == 4"><i class="fas fa-check text-success"></i></span>
                                                            </td><td class="text-center">
                                                                <span class="p-1" v-show="survey.detail.header.level_overall == 3"><i class="fas fa-check text-success"></i></span>
                                                            </td><td class="text-center">
                                                                <span class="p-1" v-show="survey.detail.header.level_overall == 2"><i class="fas fa-check text-success"></i></span>
                                                            </td><td class="text-center">
                                                                <span class="p-1" v-show="survey.detail.header.level_overall == 1"><i class="fas fa-check text-success"></i></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <h6 class="mb-3">
                                            <b><span>ข้อเสนอแนะ</span></b>
                                        </h6>
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <span>{{survey.detail.header.survey_remark}}</span>
                                            </div>
                                        </div>

                                        <div class="row mb-0 justify-content-center">
                                            <button type="button" class="col-md-2 btn btn-secondary mr-3" data-dismiss="modal">
                                                <i class="fas fa-times"></i><span class="ml-2">ปิด</span>
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
        this.getListTicketSurvey(this.paginate.method);
    },
    data(){
        return{
            searchText: '',
            searchName: '',
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
            survey:{
                ticket: {
                    detail: {
                        approve_remark: "",
                        assign_name: "",
                        detp_name: "",
                        emp_name: "",
                        subject_name: "",
                        ticket_date: "",
                        ticket_detail: "",
                        ticket_id: "",
                        ticket_level: "",
                        ticket_no: "",
                        ticket_sts: ""
                    },
                    files: {}
                },
                detail: {
                    header: {
                        level_overall: 0,
                        survey_remark: ''
                    },
                    scores: {}
                }
            },
            paginate: {
                method: 'success',
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
        getListTicketSurvey(status){

            this.paginate.method = status;
            this.disabled.searchText = true;
            this.disabled.searchBtn = true;
            this.disabled.dataReload = true;
            this.panel.progress = true;

            axios.post('/api/admin/it/ticket/survey',{
                search: this.searchText,
                searchEmp: this.searchName,
                status: (this.paginate.method == 'wait' ? 0 : 1)
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
            this.ticket.wait = this.paginate.method == 'wait' ? resultData : {};
            this.ticket.success = this.paginate.method == 'success' ? resultData : {};

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

            axios.post('/api/admin/it/ticket/survey',{
                search: this.searchText,
                searchEmp: this.searchName,
                status: (this.paginate.method == 'wait' ? 0 : 1)
            }).then(response => {this.setDataAndPaginate(response.data)});

        },
        getSurveyDetail(id){
            axios.get('/api/admin/it/ticket/survey/detail/'+id)
            .then(response => {
                this.survey.ticket = response.data.ticket;
                this.survey.detail = response.data.survey;
                $('#modal-survey-form').modal('show');
            });
        }
    }
}
</script>
