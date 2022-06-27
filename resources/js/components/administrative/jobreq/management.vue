<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการคำขอ Job Request</span>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs" id="" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="wait-tab" data-toggle="tab" href="#wait" role="tab" aria-controls="wait" aria-selected="true"
                                    v-on:click="getDataJbrFirst('wait')"
                                >
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รอดำเนินการ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="processing-tab" data-toggle="tab" href="#processing" role="tab" aria-controls="processing" aria-selected="false"
                                    v-on:click="getDataJbrFirst('inprogress')"
                                >
                                    <div class="spinner-border spinner-border-sm text-warning"></div>
                                    <span class="ml-2">กำลังดำเนินการ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="success-tab" data-toggle="tab" href="#success" role="tab" aria-controls="success" aria-selected="false"
                                    v-on:click="getDataJbrFirst('success')"
                                >
                                    <i class="fas fa-check text-success"></i>
                                    <span class="ml-2">ดำเนินการเรียบร้อย</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="void-tab" data-toggle="tab" href="#void" role="tab" aria-controls="void" aria-selected="false"
                                    v-on:click="getDataJbrFirst('void')"
                                >
                                    <i class="fas fa-minus text-danger"></i>
                                    <span class="ml-2">ยกเลิก</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false"
                                    v-on:click="getDataJbrFirst('all')"
                                >
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
                                            v-model="tab.searchTxt"
                                            v-on:keyup.enter="getDataPaginate(paginate.currentPage)"
                                        >
                                        <button class="btn btn-sm btn-amado my-2 my-sm-0" type="button"
                                            v-on:click="getDataPaginate(paginate.currentPage)"
                                        >
                                            <i class="fas fa-search"></i>
                                            <span class="ml-2">ค้นหา</span>
                                        </button>
                                        <button class="btn btn-sm btn-primary ml-2 my-2 my-sm-0" type="button"
                                            v-on:click="getDataPaginate('first')"
                                        >
                                            <i class="fas fa-sync-alt mr-2"></i> ดึงข้อมูลใหม่
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="wait" role="tabpanel" aria-labelledby="wait-tab">

                                <div class="row">
                                    <div class="col-md-12 text-center mt-2" v-show="tab.table.progress.wait">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" v-show="tab.table.show.wait">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่เอกสาร</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">ตำแหน่ง</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="list in jbr.wait" :key="list.jobreq_id">
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.jobreq_no}}</td>
                                                    <td>{{list.jobreq_date}}</td>
                                                    <td>{{list.it_emp_name_th}} {{list.it_emp_surname_th}} ({{list.it_emp_nickname_th}})</td>
                                                    <td>{{list.it_dept_name}}</td>
                                                    <td>{{list.it_emp_position}}</td>
                                                    <td>{{list.jobreq_obj_str}}</td>
                                                    <td>{{list.jobreq_status_str}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                            v-on:click="getDatajbrDetail(list.jobreq_id)"
                                                        >
                                                            <i class="fas fa-file-alt"></i><span class="ml-2">รายละเอียด</span>
                                                        </button>
                                                        <a class="btn btn-success btn-sm mb-1"
                                                            :href="'/pdf/ad/job-req/'+list.jobreq_id" :target="'_blank'"
                                                        >
                                                            <i class="fas fa-print"></i><span class="ml-2">Print</span>
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                            v-on:click="getJobreFile(list.jobreq_id,list.jobreq_status)"
                                                        >
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

                            <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="processing-tab">
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2" v-show="tab.table.progress.inprogress">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" v-show="tab.table.show.inprogress">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่เอกสาร</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">ตำแหน่ง</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="list in jbr.inprogress" :key="list.jobreq_id">
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.jobreq_no}}</td>
                                                    <td>{{list.jobreq_date}}</td>
                                                    <td>{{list.it_emp_name_th}} {{list.it_emp_surname_th}} ({{list.it_emp_nickname_th}})</td>
                                                    <td>{{list.it_dept_name}}</td>
                                                    <td>{{list.it_emp_position}}</td>
                                                    <td>{{list.jobreq_obj_str}}</td>
                                                    <td>{{list.jobreq_status_str}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                            v-on:click="getDatajbrDetail(list.jobreq_id)"
                                                        >
                                                            <i class="fas fa-file-alt"></i>
                                                            <span class="ml-2">รายละเอียด</span>
                                                        </button>
                                                        <a class="btn btn-success btn-sm mb-1"
                                                            :href="'../pdf/ad/job-req/'+list.jobreq_id" :target="'_blank'"
                                                        >
                                                            <i class="fas fa-print"></i><span class="ml-2">Print</span>
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                            v-on:click="getJobreFile(list.jobreq_id,list.jobreq_status)"
                                                        >
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

                            <div class="tab-pane fade" id="void" role="tabpanel" aria-labelledby="void-tab">
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2" v-show="tab.table.progress.void">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" v-show="tab.table.show.void">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่เอกสาร</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">ตำแหน่ง</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="list in jbr.void" :key="list.jobreq_id">
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.jobreq_no}}</td>
                                                    <td>{{list.jobreq_date}}</td>
                                                    <td>{{list.it_emp_name_th}} {{list.it_emp_surname_th}} ({{list.it_emp_nickname_th}})</td>
                                                    <td>{{list.it_dept_name}}</td>
                                                    <td>{{list.it_emp_position}}</td>
                                                    <td>{{list.jobreq_obj_str}}</td>
                                                    <td>{{list.jobreq_status_str}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                            v-on:click="getDatajbrDetail(list.jobreq_id)"
                                                        >
                                                            <i class="fas fa-file-alt"></i>
                                                            <span class="ml-2">รายละเอียด</span>
                                                        </button>
                                                        <a class="btn btn-success btn-sm mb-1"
                                                            :href="'../pdf/ad/job-req/'+list.jobreq_id" :target="'_blank'"
                                                        >
                                                            <i class="fas fa-print"></i><span class="ml-2">Print</span>
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                            v-on:click="getJobreFile(list.jobreq_id,list.jobreq_status)"
                                                        >
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

                            <div class="tab-pane fade" id="success" role="tabpanel" aria-labelledby="success-tab">
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2" v-show="tab.table.progress.success">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" v-show="tab.table.show.success">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่เอกสาร</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">ตำแหน่ง</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="list in jbr.success" :key="list.jobreq_id">
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.jobreq_no}}</td>
                                                    <td>{{list.jobreq_date}}</td>
                                                    <td>{{list.it_emp_name_th}} {{list.it_emp_surname_th}} ({{list.it_emp_nickname_th}})</td>
                                                    <td>{{list.it_dept_name}}</td>
                                                    <td>{{list.it_emp_position}}</td>
                                                    <td>{{list.jobreq_obj_str}}</td>
                                                    <td>{{list.jobreq_status_str}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                            v-on:click="getDatajbrDetail(list.jobreq_id)"
                                                        >
                                                            <i class="fas fa-file-alt"></i>
                                                            <span class="ml-2">รายละเอียด</span>
                                                        </button>
                                                        <a class="btn btn-success btn-sm mb-1"
                                                            :href="'../pdf/ad/job-req/'+list.jobreq_id" :target="'_blank'"
                                                        >
                                                            <i class="fas fa-print"></i><span class="ml-2">Print</span>
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                            v-on:click="getJobreFile(list.jobreq_id,list.jobreq_status)"
                                                        >
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

                            <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2" v-show="tab.table.progress.all">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" v-show="tab.table.show.all">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่เอกสาร</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">ตำแหน่ง</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="list in jbr.all" :key="list.jobreq_id">
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.jobreq_no}}</td>
                                                    <td>{{list.jobreq_date}}</td>
                                                    <td>{{list.it_emp_name_th}} {{list.it_emp_surname_th}} ({{list.it_emp_nickname_th}})</td>
                                                    <td>{{list.it_dept_name}}</td>
                                                    <td>{{list.it_emp_position}}</td>
                                                    <td>{{list.jobreq_obj_str}}</td>
                                                    <td>{{list.jobreq_status_str}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                            v-on:click="getDatajbrDetail(list.jobreq_id)"
                                                        >
                                                            <i class="fas fa-file-alt"></i>
                                                            <span class="ml-2">รายละเอียด</span>
                                                        </button>
                                                        <a class="btn btn-success btn-sm mb-1"
                                                            :href="'../pdf/ad/job-req/'+list.jobreq_id" :target="'_blank'"
                                                        >
                                                            <i class="fas fa-print"></i><span class="ml-2">Print</span>
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                            v-on:click="getJobreFile(list.jobreq_id,list.jobreq_status)"
                                                        >
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
                        <li class="page-item"><a class="page-link" href="javascript:;"
                            v-on:click="getDataPaginate('first')"
                        ><i class="fas fa-angle-double-left"></i></a></li>
                        <li class="page-item"><a class="page-link" href="javascript:;"
                            v-on:click="getDataPaginate('perv')"
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

                                    <span v-if="jbr.detail.jbr_status == 1" class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span v-if="jbr.detail.jbr_status == 1" class="ml-2">รอดำเนินการ</span>

                                    <i v-if="jbr.detail.jbr_status == 2" class="fas fa-check text-warning"></i>
                                    <span v-if="jbr.detail.jbr_status == 2" class="ml-2">กำลังดำเนินการ</span>

                                    <i v-if="jbr.detail.jbr_status == 3" class="fas fa-list-ul text-success"></i>
                                    <span v-if="jbr.detail.jbr_status == 3" class="ml-2">ดำเนินการเรียบร้อย</span>

                                    <i class="fas fa-ban text-danger" v-show="jbr.detail.jbr_status == 0"></i>
                                    <span class="ml-2" v-show="jbr.detail.jbr_status == 0">ยกเลิก</span>

                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal"
                            :disabled="jbr.detail.action.btnTTCloseDisabled"
                        >&times;</button>
                    </div>
                    <div class="modal-body mx-4 mt-5">
                        <div class="row m-0 mb-3">
                            <div class="col-md-4">
                                <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>JOB REQUEST FORM</b></p>
                                <p><b>AMADO GROUP</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">เลขที่เอกสาร</div>
                                    <div class="bottom-dotted-125 text-center">
                                        <span v-html="jbr.detail.jbr_no"><!--JR-003--></span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-125 text-center">
                                        <span v-html="jbr.detail.jbr_date"><!--19/11/2563--></span>
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
                                        <span class="ml-5" v-html="jbr.detail.jbr_fullnameTh"><!--นาย พงศกร เหล่านิยมไทย--></span>
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
                                        <span class="ml-5" v-html="jbr.detail.jbr_fullnameEng"><!--Mr. Pongsakorn Laoniyomthai--></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">แผนก</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="jbr.detail.jbr_deptName"><!--IT--></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="jbr.detail.jbr_posit"><!--Programmer--></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">เบอร์โทรศัพท์</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="jbr.detail.jbr_tel"><!--083-410-1045--></span>
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
                                    <div class="col-md-12 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_1" name="optobj" value="" disabled
                                                v-if="jbr.detail.jbr_obj==1" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_1">ซ่อมแซม</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_2" name="optobj" value="" disabled
                                                v-if="jbr.detail.jbr_obj==2" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_2">ซื้อ/ติดตั้งอุปกรณ์</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_3" name="optobj" value="" disabled
                                                v-if="jbr.detail.jbr_obj==3" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_3">อื่น ๆ : </label>
                                            <p class="ml-2" v-html="jbr.detail.jbr_other"><!--Lorem ipsum dolor sit amet consectetur, adipisicing elit.--></p>
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
                                                <tr v-for="(detail, index) in jbr.detail.jbr_detail" :key="index">
                                                    <td class="w-5 text-center">{{index+1}}</td>
                                                    <td class="ml-5">{{detail.jobreq_detail}}</td>
                                                    <td class="w-5 text-center">{{detail.jobreq_qty}}</td>
                                                    <td class="w-5 text-center">{{detail.jobreq_unit}}</td>
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
                                        <p v-html="jbr.detail.jbr_demand"><!--Lorem ipsum dolor sit amet consectetur adipisicing elit.--></p>
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
                                        <p v-html="jbr.detail.jbr_reason"><!--Lorem ipsum dolor sit amet consectetur adipisicing elit.--></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end m-0">
                            <div class="col-md-4 text-center">
                                <p class="mb-2">ผู้แจ้งขอดำเนินการ</p>
                                <p v-html="jbr.detail.jbr_fullnameTh"><!--นาย พงศกร เหล่านิยมไทย--></p>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row justify-content-center py-3"
                            v-if="jbr.detail.jbr_status != 0 && jbr.detail.jbr_status != 3"
                        >
                            <h5 class="col-md-2 mb-0 my-auto text-center">เปลี่ยนสถานะ <i class="fas fa-arrow-right ml-2"></i></h5>

                            <button v-if="jbr.detail.jbr_status == 1" type="button" class="col-md-3 btn btn-warning mr-3"
                                v-on:click="updateJbrStatus(2)"
                                :disabled="jbr.detail.action.btnSaveDisabled"
                                v-html="jbr.detail.action.btnSaveTxt"
                            >
                                <div class="spinner-border spinner-border-sm text-dark"></div> กำลังดำเนินการ
                            </button>

                            <button v-if="jbr.detail.jbr_status == 2" type="button" class="col-md-3 btn btn-success mr-3"
                                v-on:click="updateJbrStatus(3)"
                                :disabled="jbr.detail.action.btnSaveDisabled"
                                v-html="jbr.detail.action.btnSaveTxt"
                            >
                                <i class="fas fa-check text-white"></i> ดำเนินการเรียบร้อย
                            </button>

                            <button v-if="jbr.detail.jbr_status != 0" type="button" class="col-md-2 btn btn-danger"
                                v-on:click="updateJbrStatus(0)"
                                :disabled="jbr.detail.action.btnVoidDisabled"
                                v-html="jbr.detail.action.btnVoieTxt"
                            >
                                <i class="fas fa-ban text-white"></i> ยกเลิก
                            </button>

                        </div>
                        <div class="row justify-content-center py-3" v-if="jbr.detail.jbr_status == 3">
                            <div class="col-md-5">
                                <div class="alert alert-success text-center" role="alert">
                                    <i class="fas fa-check-circle"></i> <strong>ดำเนินการเรียบร้อย</strong>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center py-3" v-if="jbr.detail.jbr_status == 0">
                            <div class="col-md-5">
                                <div class="alert alert-danger text-center" role="alert">
                                    <i class="fas fa-ban"></i> <strong>ยกเลิก</strong>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label>หมายเหตุ</label>
                            </div>
                            <div class="col-6 text-right">
                                <button class="btn btn-primary btn-sm"
                                    v-on:click="updateJbrRemark()"
                                    v-html="jbr.detail.action.btnSaveRemarkTxt"
                                    :disabled="jbr.detail.action.btnSaveRemarkDisable"
                                >
                                    <i class="far fa-save mr-2"></i>บันทึก
                                </button>
                            </div>
                            <div class="col-12">
                                <div class="from-group">
                                    <textarea class="form-control" rows="5"
                                        v-model="jbr.detail.jbr_remark"
                                        :readonly="jbr.detail.action.jbrRemarkReadonly"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                        <hr class="pb-3">
                        <div class="row">
                            <div class="col-12">
                                <label><i class="fas fa-user-tag"></i> ผู้รับผิดชอบ</label>
                                <strong class="text-primary" v-html="jbr.detail.jbr_log.assign.emp_name">ไม่มีข้อรับผิดชอบ</strong>
                                <strong class="text-primary">(<font v-html="jbr.detail.jbr_log.assign.jobreq_assign"></font>)</strong>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label>Log การทำรายการ</label>
                                <ul class="list-group">
                                    <li class="list-group-item py-1" v-for="(list,index) in jbr.detail.jbr_log.list" :key="index">
                                        <label class="d-inline mr-3">{{index+1}})</label>
                                        <label class="d-inline mr-3">ผู้ทำรายการ</label>
                                        <strong class="text-primary mr-1">{{list.emp_name}}</strong>
                                        <i class="fas fa-long-arrow-alt-right mr-1"></i>
                                        <strong class="text-primary">{{list.jobreq_action}} ({{list.created_at}})</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="pb-3">
                        <div class="row justify-content-center pb-3">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal"
                                    :disabled="jbr.detail.action.btnCloseDisabled"
                                ><i class="fas fa-times"></i> ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-jobreq-file -->
        <div class="modal fade" id="modal-jobreq-file" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary">
                            <i class="fas fa-folder-open mr-2"></i><span>เอกสาร Job Request</span>
                        </h5>
                    </div>
                    <div class="modal-body p-5">
                        <div class="row">
                            <div class="col-12">
                                <form enctype="multipart/form-data">
                                    <div class='form-group'>
                                        <label>Upload ไฟล์ : </label> <label v-html="paper.upload.status"></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="jobreqUploadFile" aria-describedby="btnJobreqUploadFile" accept=".pdf"
                                                    v-on:change="onFileChange"
                                                    v-bind:class="{
                                                        'is-valid': paper.upload.classInptValid,
                                                        'is-invalid': paper.upload.classInptInvalid
                                                    }"
                                                    :disabled="paper.upload.inptDisabled"
                                                >
                                                <label class="custom-file-label" for="jobreqUploadFile" >Choose file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-secondary" id="btnJobreqUploadFile"
                                                    @click.prevent="uploadJobreqFile"
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
                                        v-for="(list,index) in paper.list" :key="index"
                                    >
                                        <strong>#{{index+1}}</strong> File :
                                        <a class="text-decoration-none text-primary ml-3" target="_blank"
                                            :href="list.jobreq_file_path"
                                        >{{list.jobreq_file_name}}</a>
                                        <a href="javascript:;" class="float-right"
                                            v-on:click="removeJobreqFile(index)"
                                            v-show="paper.list.jobreq_status == 1"
                                        >
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
       this.getDataJbrFirst('wait')
    },
    data(){
        return{
            tab: {
                searchTxt: '',
                table: {
                    show:{
                        wait: false,
                        inprogress: false,
                        success: false,
                        void: false,
                        all: false
                    },
                    progress:{
                        wait: false,
                        inprogress: false,
                        success: false,
                        void: false,
                        all: false
                    }
                }
            },
            jbr: {
                nowList: '',
                wait: [],
                inprogress: [],
                success: [],
                void: [],
                all: [],
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
                    jbr_log: {
                        assign: '',
                        list: []
                    },
                    action:{
                        btnTTCloseDisabled: false,
                        btnCloseDisabled: false,
                        btnSaveTxt: '',
                        btnSaveDisabled: false,
                        btnVoieTxt: '<i class="fas fa-ban text-white"></i> ยกเลิก',
                        btnVoidDisabled: false,
                        btnSaveRemarkTxt: '<i class="far fa-save mr-2"></i>บันทึก',
                        btnSaveRemarkDisable: false,
                        jbrRemarkReadonly: false
                    }
                }
            },
            paginate: {
                url: '',
                path: '',
                firstPageUrl: '',
                lastPageUrl: '',
                nextPageUrl: '',
                pervPageUrl: '',
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
                jobreq_id: '',
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
        getJobreFile(jobreqId,status){

            this.paper.jobreq_id = jobreqId;
            this.paper.upload.progress = false;
            this.paper.upload.status = ''
            this.paper.upload.classInptValid = false;
            this.paper.upload.classInptInvalid = false;
            this.paper.upload.inptDisabled = false;
            this.paper.upload.btnUploadDisabled = false;

            axios.get('/api/ad/job-req/files/list/'+this.paper.jobreq_id)
            .then(response => {

                this.paper.list = response.data;
                this.paper.list.jobreq_status = status;
                $('#modal-jobreq-file').modal('show');

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

            $('#jobreqUploadFile').next('.custom-file-label').html(this.paper.file_name);

        },
        uploadJobreqFile(e){

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
            formData.append('jobreq_id', this.paper.jobreq_id);
            formData.append('file_name', this.paper.file_name);
            formData.append('file', this.paper.file);

            axios.post('/api/ad/job-req/files/upload',formData,config)
            .then(response => {
                $('#jobreqUploadFile').next('.custom-file-label').html('Choose file');
                this.paper.file == null;
                let sts_sc = '<i class="fas fa-check-circle text-success mr-3"></i>';
                    sts_sc += '<strong class="text-success">Upload file complete.</strong>';
                this.paper.upload.status = sts_sc;
                this.paper.upload.progress = false;
                this.paper.upload.classInptValid = true;
                this.paper.upload.classInptInvalid = false;
                this.paper.upload.inptDisabled = false;
                this.paper.upload.btnUploadDisabled = false;
                this.getJobreFile(this.paper.jobreq_id,this.paper.list.jobreq_status);
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
        removeJobreqFile(index){
            this.$swal({
                icon: 'warning',
                title: 'ยืนยันการลบไฟล์ <br>'+this.paper.list[index].jobreq_file_name,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ลบไฟล์',
                denyButtonText: 'ยกเลิก',
            })
            .then((result) => {
                if( result.isConfirmed ){
                    axios.post('/api/ad/job-req/files/remove',{
                        file_id: this.paper.list[index].jobreq_file_id,
                        file_path: this.paper.list[index].jobreq_file_path
                    })
                    .then(response => {
                        this.$swal({
                            icon: 'success',
                            title: 'ลบไฟล์เรียบร้อยแล้ว'
                        });
                        this.getJobreFile(this.paper.jobreq_id,this.paper.list.jobreq_status);
                    })
                    .catch(response => {
                        this.$swal({
                            icon: 'error',
                            title: 'ไม่สามารถลบไฟล์ได้'
                        });
                    });
                }
            });
        },
        getDatajbrDetail(jbrId){
            axios.get('/api/ad/job-req/detail/'+jbrId)
            .then(response => {

                var resHeader = response.data.header,
                    resDetail = response.data.detail,
                    resLog = response.data.log;

                this.jbr.detail.jbr_id = jbrId;
                this.jbr.detail.jbr_status = resHeader.jobreq_status;
                this.jbr.detail.jbr_no = resHeader.jobreq_no;
                this.jbr.detail.jbr_date = resHeader.jobreq_date;
                this.jbr.detail.jbr_nameTh = resHeader.it_emp_name_th;
                this.jbr.detail.jbr_surnameTh = resHeader.it_emp_surname_th;
                this.jbr.detail.jbr_nameEng = resHeader.it_emp_name_eng;
                this.jbr.detail.jbr_surnameEng = resHeader.it_emp_surname_eng;
                this.jbr.detail.jbr_fullnameTh = this.jbr.detail.jbr_nameTh+' '+this.jbr.detail.jbr_surnameTh;
                this.jbr.detail.jbr_fullnameEng = this.jbr.detail.jbr_nameEng+' '+this.jbr.detail.jbr_surnameEng;
                this.jbr.detail.jbr_deptName = resHeader.it_dept_name;
                this.jbr.detail.jbr_posit = resHeader.it_emp_position;
                this.jbr.detail.jbr_tel = resHeader.it_emp_tel;
                this.jbr.detail.jbr_obj = resHeader.jobreq_obj;
                this.jbr.detail.jbr_other = resHeader.jobreq_other;
                this.jbr.detail.jbr_demand = resHeader.jobreq_demand;
                this.jbr.detail.jbr_reason = resHeader.jobreq_reason;
                this.jbr.detail.jbr_remark = resHeader.jobreq_remark;
                this.jbr.detail.jbr_detail = resDetail;

                if( resLog.admin !== null )
                    this.jbr.detail.jbr_log.assign = resLog.admin;
                else
                    this.jbr.detail.jbr_log.assign = { emp_name: 'ยังไม่ได้ Assign', jobreq_assign: '' };

                this.jbr.detail.jbr_log.list = resLog.list;

                if( this.jbr.detail.jbr_status == 1 )
                    this.jbr.detail.action.btnSaveTxt = '<div class="spinner-border spinner-border-sm text-dark"></div> กำลังดำเนินการ';
                else if( this.jbr.detail.jbr_status == 2 )
                    this.jbr.detail.action.btnSaveTxt = '<i class="fas fa-check text-white"></i> ดำเนินการเรียบร้อย';

                $('#modal-jobreq').modal('show');

            });
        },
        getDataJbrFirst(list){

            this.jbr.nowList = list;
            this.tab.searchTxt = '';

            this.tab.table.show.wait = false;
            this.tab.table.show.inprogress = false;
            this.tab.table.show.success = false,
            this.tab.table.show.void = false;
            this.tab.table.show.all = false;

            this.tab.table.progress.wait = ( this.jbr.nowList == 'wait' ? true : false );
            this.tab.table.progress.inprogress = ( this.jbr.nowList == 'inprogress' ? true : false );
            this.tab.table.progress.success = ( this.jbr.nowList == 'success' ? true : false );
            this.tab.table.progress.void = ( this.jbr.nowList == 'void' ? true : false );
            this.tab.table.progress.all = ( this.jbr.nowList == 'all' ? true : false );

            axios.post('/api/ad/job-req/history/pagin',{
                filter: this.jbr.nowList,
                search: this.tab.searchTxt,
                paginate: this.paginate.list
            })
            .then(response => {
                this.setDataAndPaginate(response.data,'first');
            });

        },
        getDataPaginate(page){

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'perv' ){ this.paginate.url = this.paginate.pervPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post(this.paginate.url,{
                filter: this.jbr.nowList,
                search: this.tab.searchTxt,
                paginate: this.paginate.list
            })
            .then(response => { this.setDataAndPaginate(response.data,'second') });

        },
        setDataAndPaginate(dataArr,set){

            this.paginate.url = dataArr.path+'?page=';
            this.paginate.path = dataArr.path;
            this.paginate.firstPageUrl = dataArr.first_page_url;
            this.paginate.lastPageUrl = dataArr.last_page_url;
            this.paginate.nextPageUrl = dataArr.next_page_url;
            this.paginate.pervPageUrl = dataArr.perv_page_url;
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

            if( set == 'first' ){
                this.paginate.show = true;

                this.tab.table.show.wait = ( this.jbr.nowList == 'wait' ? true : false );
                this.tab.table.show.inprogress = ( this.jbr.nowList == 'inprogress' ? true : false );
                this.tab.table.show.success = ( this.jbr.nowList == 'success' ? true : false );
                this.tab.table.show.void = ( this.jbr.nowList == 'void' ? true : false );
                this.tab.table.show.all = ( this.jbr.nowList == 'all' ? true : false );

                this.tab.table.progress.wait = false;
                this.tab.table.progress.inprogress = false;
                this.tab.table.progress.success = false;
                this.tab.table.progress.void = false;
                this.tab.table.progress.all = false;
            }

            if( this.jbr.nowList == 'wait' ) this.jbr.wait = resultData;
            else if( this.jbr.nowList == 'inprogress' ) this.jbr.inprogress = resultData;
            else if( this.jbr.nowList == 'success' ) this.jbr.success = resultData;
            else if( this.jbr.nowList == 'void' ) this.jbr.void = resultData;
            else if( this.jbr.nowList == 'all' ) this.jbr.all = resultData;

        },
        updateJbrStatus(jbrSts){

            let title = 'ยืนยันการเปลี่ยนสถานะ</br>'+this.jbr.detail.jbr_no,
                text = '';

            if(jbrSts == 2)
                text = 'ต้องการเปลี่ยนเป็น กำลังดำเนินการ ใช่หรือไม่';
            else if(jbrSts == 3)
                text = 'ต้องการเปลี่ยนเป็น ดำเนินการเรียบร้อย ใช่หรือไม่';
            else if(jbrSts == 0)
                text = 'ต้องการเปลี่ยนเป็น ยกเลิก ใช่หรือไม่';

            this.$swal({
                icon: 'warning',
                title: title,
                text: text,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ยืนยัน',
                denyButtonText: 'ยกเลิก',
            })
            .then((result) => {
                if( result.isConfirmed ){

                    this.jbr.detail.action.btnTTCloseDisabled = true;
                    this.jbr.detail.action.btnCloseDisabled = true;
                    this.jbr.detail.action.btnSaveDisabled = true;
                    this.jbr.detail.action.btnVoidDisabled = true;

                    if( jbrSts != 0 )
                        this.jbr.detail.action.btnSaveTxt = '<div class="spinner-border spinner-border-sm text-dark"></div> กำลังอัพเดทข้อมูล';
                    else
                        this.jbr.detail.action.btnVoieTxt = '<div class="spinner-border spinner-border-sm text-dark"></div> กำลังอัพเดทข้อมูล';

                    axios.post('/api/ad/job-req/update/status',{
                        id: this.jbr.detail.jbr_id,
                        status: jbrSts
                    })
                    .then(response => {

                        this.jbr.detail.jbr_status = jbrSts;

                        if( jbrSts != 0 )
                            this.jbr.detail.action.btnSaveTxt = '<i class="fas fa-check text-white"></i> ดำเนินการเรียบร้อย';
                        else
                            this.jbr.detail.action.btnVoieTxt = '<i class="fas fa-ban text-white"></i> ยกเลิก';

                        this.jbr.detail.action.btnTTCloseDisabled = false;
                        this.jbr.detail.action.btnCloseDisabled = false;
                        this.jbr.detail.action.btnSaveDisabled = false;
                        this.jbr.detail.action.btnVoidDisabled = false;
                        $('#modal-jobreq').modal('hide');

                        //this.getDataJbrFirst(this.jbr.nowList);
                        this.getDataPaginate(this.paginate.currentPage);
                        // this.getDatajbrDetail(this.jbr.detail.jbr_id);

                    })

                }

            });

        },
        updateJbrRemark(){

            let btnContent = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
                btnContent += 'Updating...';

            this.jbr.detail.action.btnSaveRemarkTxt = btnContent;
            this.jbr.detail.action.btnSaveRemarkDisable = true;
            this.jbr.detail.action.jbrRemarkReadonly = true;

            axios.post('/api/ad/job-req/update/remark',{
                id: this.jbr.detail.jbr_id,
                remark: this.jbr.detail.jbr_remark
            })
            .then(response => {
                this.$swal({ icon: 'success', title: 'บันทึกหมายเหตุเรียบร้อยแล้ว' });
                this.jbr.detail.action.btnSaveRemarkTxt = '<i class="far fa-save mr-2"></i>บันทึก';
                this.jbr.detail.action.btnSaveRemarkDisable = false;
                this.jbr.detail.action.jbrRemarkReadonly = false;
                // $('#modal-jobreq').modal('hide');
                //this.getDatajbrDetail(this.jbr.detail.jbr_id);
            })
            .catch(response => {
                this.$swal({ icon: 'error', title: 'ไม่สามารถบันทึกหมายเหตุได้' });
                this.jbr.detail.action.btnSaveRemarkTxt = '<i class="far fa-save mr-2"></i>บันทึก';
                this.jbr.detail.action.btnSaveRemarkDisable = false;
                this.jbr.detail.action.jbrRemarkReadonly = false;
            });

        }

    }
}
</script>
