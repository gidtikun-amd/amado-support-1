<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการคำขอ Ticket</span>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs" id="" role="tablist" >
                            <li class="nav-item" v-show="manager.showTabWaitappr">
                                <a class="nav-link" id="waitappr-tab" data-toggle="tab" href="#waitappr"
                                role="tab" aria-controls="waitappr" aria-selected="true"
                                v-bind:class="{'active': manager.showTabWaitappr}"
                                v-on:click="getTicketList('waitappr')">
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รออนุมัติ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="waitproc-tab" data-toggle="tab" href="#waitproc"
                                role="tab" aria-controls="waitproc" aria-selected="true"
                                v-bind:class="{'active': !manager.showTabWaitappr}"
                                v-on:click="getTicketList('waitproc')">
                                    <span class="spinner-grow spinner-grow-mini text-warning" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รอรับดำเนินการ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="processing-tab" data-toggle="tab" href="#processing"
                                role="tab" aria-controls="processing" aria-selected="false"
                                v-on:click="getTicketList('progress')">
                                    <div class="spinner-border spinner-border-sm text-warning"></div>
                                    <span class="ml-2">กำลังดำเนินการ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="success-tab" data-toggle="tab" href="#success"
                                role="tab" aria-controls="success" aria-selected="false"
                                v-on:click="getTicketList('success')">
                                    <i class="fas fa-check text-success"></i>
                                    <span class="ml-2">ดำเนินการเรียบร้อย</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="void-tab" data-toggle="tab" href="#void"
                                role="tab" aria-controls="void" aria-selected="false"
                                v-on:click="getTicketList('void')">
                                    <i class="fas fa-minus text-danger"></i>
                                    <span class="ml-2">ไม่อนุมัติ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="all-tab" data-toggle="tab" href="#all"
                                role="tab" aria-controls="all" aria-selected="false"
                                v-on:click="getTicketList('all')">
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
                                            v-model="searchTxt"
                                            v-on:keyup.enter="getTicketList(paginate.method)">
                                        </div>
                                        <div class="col-auto">
                                            <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                                v-model="searchName"
                                                v-on:keyup.enter="getTicketList(paginate.method)"
                                            >
                                        </div>
                                        <div class="col-auto my-4">
                                            <button class="btn btn-sm btn-amado my-2" type="button"
                                            v-on:click="getTicketList(paginate.method)"
                                            :disabled="button.search.disabled"
                                            >
                                                <i class="fas fa-search"></i>
                                                <span class="ml-2">ค้นหา</span>
                                            </button>
                                            <button class="btn btn-sm btn-primary ml-2" type="button"
                                            v-on:click="getTicketList(paginate.method)"
                                            :disabled="button.redata.disabled"
                                            >
                                                <i class="fas fa-sync-alt mr-2"></i> ดึงข้อมูลใหม่
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3" v-show="progress.show">
                                <div class="col-md-12 text-center">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                        </div>
                                    </div>
                                    <strong class="text-primary">กำลังดึงข้อมูล</strong>
                                </div>
                            </div>

                            <div class="tab-pane fade show active" id="waitappr" role="tabpanel" aria-labelledby="waitappr-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่คำขอ</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">เรื่อง</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(list,index) in ticket.waitappr" :key='index'>
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.ticket_no}}</td>
                                                    <td>{{list.ticket_date}}</td>
                                                    <td>{{list.emp_name}}</td>
                                                    <td>{{list.detp_name}}</td>
                                                    <td>{{list.subject_name}}</td>
                                                    <td>{{list.ticket_sts_str}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                        v-on:click="getTicketDetail(index)">
                                                            <i class="fas fa-file-alt"></i><span class="ml-2">ทำรายการอนุมัติ</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="waitproc" role="tabpanel" aria-labelledby="waitproc-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่คำขอ</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">เรื่อง</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col">ระดับ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(list,index) in ticket.waitproc" :key="index">
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.ticket_no}}</td>
                                                    <td>{{list.ticket_date}}</td>
                                                    <td>{{list.emp_name}}</td>
                                                    <td>{{list.detp_name}}</td>
                                                    <td>{{list.subject_name}}</td>
                                                    <td>{{list.ticket_sts_str}}</td>
                                                    <td>
                                                        <span class="badge badge-pill badge-success"
                                                        v-show="list.ticket_level == 1">Low</span>
                                                        <span class="badge badge-pill badge-warning"
                                                        v-show="list.ticket_level == 2">Medium</span>
                                                        <span class="badge badge-pill badge-danger"
                                                        v-show="list.ticket_level == 3">High</span>
                                                        <span class="badge badge-pill badge-danger"
                                                        v-show="list.ticket_level == 0">ยกเลิก</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                        v-on:click="getTicketDetail(index)">
                                                            <i class="fas fa-file-alt"></i><span class="ml-2">ทำรายการรับดำเนินการ</span>
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
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่คำขอ</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">เรื่อง</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col">ระดับ</th>
                                                    <th scope="col">ผู้ดำเนินการ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(list,index) in ticket.progress" :key="index">
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.ticket_no}}</td>
                                                    <td>{{list.ticket_date}}</td>
                                                    <td>{{list.emp_name}}</td>
                                                    <td>{{list.detp_name}}</td>
                                                    <td>{{list.subject_name}}</td>
                                                    <td>{{list.ticket_sts_str}}</td>
                                                    <td>
                                                        <span class="badge badge-pill badge-success"
                                                        v-show="list.ticket_level == 1">Low</span>
                                                        <span class="badge badge-pill badge-warning"
                                                        v-show="list.ticket_level == 2">Medium</span>
                                                        <span class="badge badge-pill badge-danger"
                                                        v-show="list.ticket_level == 3">High</span>
                                                        <span class="badge badge-pill badge-danger"
                                                        v-show="list.ticket_level == 0">ยกเลิก</span>
                                                    </td>
                                                    <td>{{list.assign_name}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                        v-on:click="getTicketDetail(index)">
                                                            <i class="fas fa-file-alt"></i><span class="ml-2">ทำรายการสำเร็จ</span>
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
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่คำขอ</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">เรื่อง</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col">ระดับ</th>
                                                    <th scope="col">ผู้ดำเนินการ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <tr v-for="(list,index) in ticket.success" :key="index">
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.ticket_no}}</td>
                                                    <td>{{list.ticket_date}}</td>
                                                    <td>{{list.emp_name}}</td>
                                                    <td>{{list.detp_name}}</td>
                                                    <td>{{list.subject_name}}</td>
                                                    <td>{{list.ticket_sts_str}}</td>
                                                    <td>
                                                        <span class="badge badge-pill badge-success"
                                                        v-show="list.ticket_level == 1">Low</span>
                                                        <span class="badge badge-pill badge-warning"
                                                        v-show="list.ticket_level == 2">Medium</span>
                                                        <span class="badge badge-pill badge-danger"
                                                        v-show="list.ticket_level == 3">High</span>
                                                        <span class="badge badge-pill badge-danger"
                                                        v-show="list.ticket_level == 0">ยกเลิก</span>
                                                    </td>
                                                    <td>{{list.assign_name}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                        v-on:click="getTicketDetail(index)">
                                                            <i class="fas fa-file-alt"></i><span class="ml-2">ดูรายละเอียด</span>
                                                        </button>
                                                        <button type="button" class="btn btn-amado btn-sm mb-1"
                                                        v-on:click="getTicketSurveyDetail(list.ticket_id)"
                                                        v-show="list.survey_status == 1">
                                                            <i class="fas fa-poll mr-1"></i> แบบสอบถาม
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
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่คำขอ</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">เรื่อง</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col">ระดับ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <tr v-for="(list,index) in ticket.void" :key="index">
                                                    <th scope="row">{{list.index}}</th>
                                                    <td>{{list.ticket_no}}</td>
                                                    <td>{{list.ticket_date}}</td>
                                                    <td>{{list.emp_name}}</td>
                                                    <td>{{list.detp_name}}</td>
                                                    <td>{{list.subject_name}}</td>
                                                    <td>{{list.ticket_sts_str}}</td>
                                                    <td>
                                                        <span class="badge badge-pill badge-success"
                                                        v-show="list.ticket_level == 1">Low</span>
                                                        <span class="badge badge-pill badge-warning"
                                                        v-show="list.ticket_level == 2">Medium</span>
                                                        <span class="badge badge-pill badge-danger"
                                                        v-show="list.ticket_level == 3">High</span>
                                                        <span class="badge badge-pill badge-danger"
                                                        v-show="list.ticket_level == 0">ยกเลิก</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                        v-on:click="getTicketDetail(index)">
                                                            <i class="fas fa-file-alt"></i><span class="ml-2">ดูรายละเอียด</span>
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
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">เลขที่คำขอ</th>
                                                        <th scope="col">วันที่สร้าง</th>
                                                        <th scope="col">ชื่อ</th>
                                                        <th scope="col">ฝ่าย</th>
                                                        <th scope="col">เรื่อง</th>
                                                        <th scope="col">สถานะ</th>
                                                        <th scope="col">ระดับ</th>
                                                        <th scope="col">ผู้ดำเนินการ</th>
                                                        <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(list,index) in ticket.all" :key="index">
                                                        <th scope="row">{{list.index}}</th>
                                                        <td>{{list.ticket_no}}</td>
                                                        <td>{{list.ticket_date}}</td>
                                                        <td>{{list.emp_name}}</td>
                                                        <td>{{list.detp_name}}</td>
                                                        <td>{{list.subject_name}}</td>
                                                        <td>{{list.ticket_sts_str}}</td>
                                                        <td>
                                                            <span class="badge badge-pill badge-success"
                                                            v-show="list.ticket_level == 1">Low</span>
                                                            <span class="badge badge-pill badge-warning"
                                                            v-show="list.ticket_level == 2">Medium</span>
                                                            <span class="badge badge-pill badge-danger"
                                                            v-show="list.ticket_level == 3">High</span>
                                                            <span class="badge badge-pill badge-danger"
                                                            v-show="list.ticket_level == 0">ยกเลิก</span>
                                                        </td>
                                                        <td>{{list.assign_name}}</td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-primary btn-sm mb-1"
                                                            v-on:click="getTicketDetail(index)">
                                                                <i class="fas fa-file-alt"></i><span class="ml-2">ดูรายละเอียด</span>
                                                            </button>
                                                            <button type="button" class="btn btn-amado btn-sm mb-1"
                                                            v-on:click="getTicketSurveyDetail(list.ticket_id)"
                                                            v-show="list.survey_status">
                                                                <i class="fas fa-poll mr-1"></i> แบบสอบถาม
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


        <!-- Modal : #modal-ticket -->
        <div class="modal fade" id="modal-ticket" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span class="mr-2">สถานะ : </span>

                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"
                                    v-show="ticket.detail.data.ticket_sts == 1"></span>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 1">รออนุมัติ</span>

                                    <span class="spinner-grow spinner-grow-mini text-warning" role="status" aria-hidden="true"
                                    v-show="ticket.detail.data.ticket_sts == 2"></span>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 2">รอรับดำเนินการ</span>

                                    <i class="fas fa-check text-warning"
                                    v-show="ticket.detail.data.ticket_sts == 3"></i>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 3">กำลังดำเนินการ</span>

                                    <i class="fas fa-list-ul text-success"
                                    v-show="ticket.detail.data.ticket_sts == 4"></i>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 4">ดำเนินการเรียบร้อย</span>

                                    <i class="fas fa-ban text-danger"
                                    v-show="ticket.detail.data.ticket_sts == 0"></i>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 0">ไม่อนุมัติ</span>

                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body m-4">
                        <div class="row m-0 mb-3">
                            <div class="col-md-12 text-center mb-2">
                                <img class="logo-nav" src="/img/logo-red.png" alt="">
                            </div>
                            <div class="col-md-12 text-center">
                                <p class="mb-0"><b>TICKET REQUEST</b></p>
                            </div>
                        </div>

                        <div class="row m-0 mt-3 mb-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <span><b>เลขที่คำขอ </b><span class="ml-2">{{ticket.detail.data.ticket_no}}</span></span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span><b>วันที่ </b><span class="ml-2">{{ticket.detail.data.ticket_date}}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <span class="mr-4"><b>ชื่อ-นามสกุล </b><span class="ml-2">{{ticket.detail.data.emp_name}}</span></span>
                                                <span><b>ฝ่าย </b><span class="ml-2 mr-4">{{ticket.detail.data.detp_name}}</span></span>
                                                <span><b>ติดต่อกลับ </b><span class="ml-2">{{ticket.detail.data.ticket_callback}}</span></span>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row mb-3">
                                            <div class="col-auto my-auto">
                                                <h5 class="card-title m-0"><b>เรื่อง : </b></h5>
                                            </div>
                                            <div class="col">
                                                <div class="input-group">
                                                    <select class="custom-select"
                                                    v-model="ticket.detail.subject_id"
                                                    :disabled="ticket.detail.data.ticket_sts != 1 && ticket.detail.data.ticket_sts != 2">
                                                        <option v-for="(subj,index) in subject" :key="index" :value="subj.it_ticket_subject_id">
                                                            {{subj.it_ticket_subject_name}}
                                                        </option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-primary" type="button"
                                                        v-on:click="setChangeSubjectTicket()"
                                                        :disabled="ticket.detail.data.ticket_sts != 1 && ticket.detail.data.ticket_sts != 2">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <p class="card-text mb-0"><b>รายละเอียด : </b><span>{{ticket.detail.data.ticket_detail}}</span></p>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">
                                            <div class="col-auto my-auto"><p class="card-text m-0"><b>ไฟล์ : </b></p></div>
                                            <div class="col">
                                                <div class="form-group col-md-12 p-0 mb-0">
                                                    <ul class="list-group">
                                                        <li class="list-group-item py-1" v-for="(list,index) in ticket.detail.file" :key="index">
                                                            <h5 class="d-inline mr-3">#{{index+1}}</h5>
                                                            <span>Url : </span>
                                                            <span class="badge badge-light"><a :href="'/'+list.it_ticket_file_path" target="_blank"
                                                            class="text-decoration-none">{{list.it_ticket_file}}</a></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">
                                            <div class="col text-center" v-show="ticket.detail.data.ticket_sts == 0">
                                                <p class="card-text mb-0"><b><i class="fas fa-exclamation-triangle text-amado mr-2"></i> ไม่อนุมัติ</b></p>
                                            </div>
                                            <div class="col text-center" v-show="ticket.detail.data.ticket_sts != 0">
                                                <p class="card-text mb-0"><b>ระดับความสำคัญของ Ticket : </b>
                                                    <span class="badge badge-pill badge-success"
                                                    v-show="ticket.detail.data.ticket_level == 1">Low</span>
                                                    <span class="badge badge-pill badge-warning"
                                                    v-show="ticket.detail.data.ticket_level == 2">Medium</span>
                                                    <span class="badge badge-pill badge-danger"
                                                    v-show="ticket.detail.data.ticket_level == 3">High</span>
                                                </p>
                                            </div>

                                            <div class="col text-center">
                                                <p class="card-text mb-0"><b>ผู้ดำเนินการ : </b><span>{{ticket.detail.data.assign_name}}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mt-3 mb-3" v-show="manager.showModalForAppr && ticket.detail.data.ticket_sts == 1">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="m-0"><b>ส่วนของผู้ทำรายการอนุมัติ</b></h6>
                                            </div>
                                        </div>

                                        <hr class="mb-3">

                                        <div class="row mb-2">
                                            <div class="col-auto my-auto">
                                                <input type="radio" class="form-check-input ml-1" name="optappr" value="1"
                                                v-model="ticket.detail.approve_sts">
                                                <span class="ml-4"><b>อนุมัติ</b></span>
                                                <i class="fas fa-check text-success ml-1"></i>
                                            </div>
                                            <div class="col-auto my-auto pr-0 text-right">
                                                <label class="m-0">เลือกระดับความสำคัญของ Ticket</label>
                                            </div>
                                            <div class="col">
                                                <select class="form-control form-control-sm"
                                                v-model="ticket.detail.approve_level">
                                                    <option value="" selected disabled>กรุณาเลือกระดับความสำคัญ...</option>
                                                    <option value="1">Low</option>
                                                    <option value="2">Medium</option>
                                                    <option value="3">High</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto my-auto">
                                                <input type="radio" class="form-check-input ml-1" name="optappr" value="0"
                                                v-model="ticket.detail.approve_sts">
                                                <span class="ml-4"><b>ไม่อนุมัติ</b></span>
                                                <i class="fas fa-ban text-danger ml-1"></i>
                                            </div>
                                            <div class="col-auto my-auto pr-0 text-right">
                                                <label class="m-0">เหตุผล</label>
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control form-control-sm" placeholder="กรุณาระบุเหตุผล..."
                                                v-model="ticket.detail.approve_remark">
                                            </div>
                                        </div>

                                        <hr class="mb-3">

                                        <div class="row mb-0 justify-content-center">
                                            <button type="button" class="col-md-3 btn btn-primary mr-3"
                                            v-on:click="setTicketLevel()"
                                            v-html="button.setlevel.saveContent"
                                            :disabled="button.setlevel.saveDisabled">
                                                <i class="fas fa-check"></i><span class="ml-2">ตกลง</span>
                                            </button>
                                            <button type="button" class="col-md-2 btn btn-secondary mr-3" data-dismiss="modal"
                                            :disabled="button.setlevel.closeDisabled">
                                                <i class="fas fa-times"></i><span class="ml-2">ยกเลิก</span>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mt-3 mb-3" v-show="ticket.detail.data.ticket_sts == 2">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="m-0"><b>ส่วนของผู้ทำรายการรับดำเนินการ</b></h6>
                                            </div>
                                        </div>

                                        <hr class="mb-3">

                                        <div class="row">
                                            <div class="col-auto my-auto">
                                                <label class="m-0">รับดำเนินการรายการนี้โดย</label>
                                            </div>
                                            <div class="col my-auto">
                                                <p class="m-0">{{$session.get('name')}}</p>
                                            </div>
                                        </div>

                                        <hr class="mb-3">

                                        <div class="row mb-0 justify-content-center">
                                            <button type="button" class="col-md-3 btn btn-success mr-3"
                                            v-on:click="setTicketAssign()"
                                            v-html="button.setassign.saveContent"
                                            :disabled="button.setassign.saveDisabled">
                                                <i class="fas fa-check"></i><span class="ml-2">ยืนยันรับดำเนินการ</span>
                                            </button>
                                            <button type="button" class="col-md-2 btn btn-secondary mr-3" data-dismiss="modal"
                                            :disabled="button.setassign.closeDisabled">
                                                <i class="fas fa-times"></i><span class="ml-2">ยกเลิก</span>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mt-3 mb-3" v-show="ticket.detail.data.ticket_sts == 3 && ticket.detail.data.ticket_operator == $session.get('user')">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="m-0"><b><i class="fas fa-exclamation-triangle text-amado mr-3"></i>ยืนยันทำรายการสำเร็จ ?</b></h5>
                                            </div>
                                        </div>

                                        <hr class="mb-3">

                                        <div class="row mb-0 justify-content-center">
                                            <button type="button" class="col-md-3 btn btn-success mr-3"
                                            v-on:click="setTicketEndJob()"
                                            v-html="button.setendjob.saveContent"
                                            :disabled="button.setendjob.saveDisabled">
                                                <i class="fas fa-check"></i><span class="ml-2">สำเร็จ</span>
                                            </button>
                                            <button type="button" class="col-md-2 btn btn-secondary mr-3" data-dismiss="modal"
                                            :disabled="button.setendjob.closeDisabled">
                                                <i class="fas fa-times"></i><span class="ml-2">ยกเลิก</span>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mt-3 mb-3"
                        v-show="ticket.detail.data.ticket_sts == 3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fas fa-comment-dots"></i> <b>ข้อความตอบกลับ User</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" v-show="ticket.detail.data.ticket_operator == $session.get('user')">
                                            <div class="col-md-12">
                                                <form>
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="3"
                                                        v-model="ticket.messageText"></textarea>
                                                    </div>
                                                    <button type="reset" class="btn btn-danger btn-sm float-right">
                                                        <i class="fas fa-comment-slash mr-1"></i> ยกเลิก
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-sm float-right mr-1"
                                                    v-on:click="saveTicketMessage()">
                                                        <i class="fas fa-comment-medical mr-1"></i> บันทึก
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <hr v-show="ticket.detail.data.ticket_operator == $session.get('user')">
                                        <div class="row justify-content-end">
                                            <div class="col-md-8 mb-2" v-for="(msg,index) in ticket.messageList" :key="index">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text">{{msg.tkms_text}}</p>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <small class="text-muted">โดย {{msg.emp_name}} ({{msg.created_at}})</small>
                                                        <button type="button" class="close ml-2" aria-label="Close"
                                                        v-on:click="removeTicketMessage(msg.tkms_id)">
                                                        <span aria-hidden="true">&times;</span>
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
                                        <label>Upload ไฟล์ : </label> <label></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="jobreqUploadFile" aria-describedby="btnJobreqUploadFile">
                                                <label class="custom-file-label" for="jobreqUploadFile" >Choose file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-secondary" id="btnJobreqUploadFile">
                                                    <i class="fas fa-cloud-upload-alt"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="progress my-3">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>รายการเอกสาร : </label>
                                <ul class="list-group">
                                    <li class="list-group-item py-1">
                                        <strong>#1</strong> File :
                                        <a class="text-decoration-none text-primary ml-3"></a>
                                        <a href="javascript:;" class="float-right">
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

        <!-- Modal : #modal-survey-form -->
        <div class="modal fade" id="modal-survey-form" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span><b>เลขที่คำขอ </b><span class="ml-2">{{survey.ticket.detail.ticket_no}}</span></span>
                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body m-3">
                        <div class="row m-0">
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
        this.manager.showTabWaitappr = ( this.$session.get('role') == 'admin.it.manager' ? true : false );
        this.manager.showModalForAppr = ( this.$session.get('role') == 'admin.it.manager' ? true : false );

        this.getTicketSubject();
        this.getTicketList(( this.$session.get('role') == 'admin.it.manager' ? 'waitappr' : 'waitproc' ));

    },
    data(){
        return{
            manager:{
                showTabWaitappr: true,
                showModalForAppr: true
            },
            searchTxt: '',
            searchName: '',
            progress: {
                show: false
            },
            button: {
                search: {
                    disabled: false
                },
                redata: {
                    disabled: false
                },
                setlevel: {
                    saveContent: '<i class="fas fa-check"></i><span class="ml-2">ตกลง</span>',
                    saveDisabled: false,
                    closeDisabled: false
                },
                setassign: {
                    saveContent: '<i class="fas fa-check"></i><span class="ml-2">ยืนยันรับดำเนินการ</span>',
                    saveDisabled: false,
                    closeDisabled: false
                },
                setendjob: {
                    saveContent: '<i class="fas fa-check"></i><span class="ml-2">สำเร็จ</span>',
                    saveDisabled: false,
                    closeDisabled: false
                }
            },
            ticket: {
                tempData: {},
                waitappr: {},
                waitproc: {},
                progress: {},
                success: {},
                void: {},
                all: {},
                detail: {
                    subject_id: '',
                    approve_sts: '',
                    approve_level: '',
                    approve_remark: '',
                    data: {},
                    file: []
                },
                messageText: '',
                messageList: {}
            },
            subject: {},
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
                method: (this.$session.get('role') == 'admin.it.manager' ? 'waitappr' : 'waitproc'),
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
            }
        }
    },
    methods: {
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
        getTicketSubject(){
            axios.get('/api/admin/it/ticket/subject')
            .then(response => {
                this.subject = response.data;
            });
        },
        getTicketList(section){

            this.progress.show = true;
            this.button.search.disabled = true;
            this.button.redata.disabled = true;
            this.paginate.method = section;

            axios.post('/api/admin/it/ticket/list',{
                search: this.searchTxt,
                searchEmp: this.searchName,
                status: section
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            })

        },
        getDataPaginate(page){

            this.progress.show = true;
            this.button.search.disabled = true;
            this.button.redata.disabled = true;

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post(this.paginate.url,{
                search: this.search_txt,
                searchEmp: this.searchName,
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
            this.ticket.tempData = resultData;

            if(this.paginate.method == 'waitappr') this.ticket.waitappr = this.ticket.tempData;
            else if(this.paginate.method == 'waitproc') this.ticket.waitproc = this.ticket.tempData;
            else if(this.paginate.method == 'progress') this.ticket.progress = this.ticket.tempData;
            else if(this.paginate.method == 'success') this.ticket.success = this.ticket.tempData;
            else if(this.paginate.method == 'void') this.ticket.void = this.ticket.tempData;
            else this.ticket.all = this.ticket.tempData;

            this.progress.show = false;
            this.button.search.disabled = false;
            this.button.redata.disabled = false;

        },
        getTicketDetail(index){
            axios.get('/api/admin/it/ticket/detail/'+this.ticket.tempData[index].ticket_id)
            .then(response => {

                this.ticket.detail.data = response.data.detail;
                this.ticket.detail.file = response.data.files;
                this.ticket.detail.subject_id = response.data.detail.subject_id;
                this.ticket.detail.approve_sts = ( response.data.detail.ticket == 0 ? 0 : 1 );
                this.ticket.detail.approve_level = response.data.detail.ticket_level;
                this.ticket.detail.approve_remark = response.data.detail.approve_remark;

                this.button.setlevel.saveContent = '<i class="fas fa-check"></i><span class="ml-2">ตกลง</span>';
                this.button.setlevel.saveDisabled = false;
                this.button.setlevel.closeDisabled = false;
                this.getTicketMessage(this.ticket.detail.data.ticket_id);

                $('#modal-ticket').modal('show');
            });
        },
        setChangeSubjectTicket(){
            this.$swal({
                title: 'ยืนยันการเปลี่ยนเรื่อง',
                text: 'Ticket เลขที่ '+this.ticket.detail.data.ticket_no,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน'
            }).then((result) => {
                if(result.isConfirmed){
                    axios.post('/api/admin/it/ticket/subject/change',{
                        ticket_id: this.ticket.detail.data.ticket_id,
                        user: this.$session.get('user'),
                        subject_id: this.ticket.detail.subject_id,
                        oid: this.ticket.detail.data.subject_id,
                        nid: this.ticket.detail.subject_id
                    })
                    .then(response => {
                        if(response.data.status){
                            this.$swal({icon: 'success', text: 'เปลี่ยนแปลงเรื่องเรียบร้อยแล้ว'});
                            this.ticket.detail.data.subject_id = this.ticket.detail.subject_id;
                        }
                        else{
                            if(response.data.error == 'login.fail') this.neviLogin();
                            else this.$swal({icon: 'error', text: 'ไม่สามารถเปลี่ยนแปลงเรื่องได้'});
                        }
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถเปลี่ยนแปลงเรื่องได้'});
                    });
                }
            });
        },
        setTicketLevel(){

            if(this.ticket.detail.approve_sts == ''){
                this.$swal({icon: 'info', text: 'กรุณาเลือกการอนุมัติ'});
                return;
            }

            if(this.ticket.detail.approve_sts == 1 && this.ticket.detail.approve_level == ''){
                this.$swal({icon: 'info', text: 'กรุณาเลือกระดับความสำคัญของ Ticket'});
                return;
            }

            if(this.ticket.detail.approve_sts == 0 && this.ticket.detail.approve_remark == ''){
                this.$swal({icon: 'info', text: 'กรุณาใส่เหตุผลการไม่อนุมัติ'});
                return;
            }

            let btnContent = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
                btnContent += 'กำลังบันทึกข้อมูล...';

            this.button.setlevel.saveContent = btnContent;
            this.button.setlevel.saveDisabled = true;
            this.button.setlevel.closeDisabled = true;

            let postData = {
                ticket_id: this.ticket.detail.data.ticket_id,
                ticket_no: this.ticket.detail.data.ticket_no,
                user: this.$session.get('user'),
                next_sts: 2,
                approve_sts: this.ticket.detail.approve_sts,
                approve_level: this.ticket.detail.approve_level,
                approve_remark: this.ticket.detail.approve_remark
            };

            axios.post('/api/admin/it/ticket/approve',postData)
            .then(response => {

                this.button.setlevel.saveContent = '<i class="fas fa-check"></i><span class="ml-2">ตกลง</span>';
                this.button.setlevel.saveDisabled = false;
                this.button.setlevel.closeDisabled = false;
                
                if(response.data.status){
                    this.$swal({icon: 'success', text: 'บันทึกการอนุมัติ และระดับความสำคัญของ Ticket เรียบร้อยแล้ว'});
                    var resData = response.data.detail;
                    this.ticket.detail.data.ticket_sts = resData.ticket_sts;
                    this.ticket.detail.data.ticket_level = resData.ticket_lvl;
                    $('#modal-ticket').modal('hide');
                    this.getTicketList(this.paginate.method);
                }
                else{
                    if(response.data.error == 'login.fail') this.neviLogin();
                    else this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกการอนุมัติ และระดับความสำคัญของ Ticket ได้'});
                }

            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกการอนุมัติ และระดับความสำคัญของ Ticket ได้'});
                this.button.setlevel.saveContent = '<i class="fas fa-check"></i><span class="ml-2">ตกลง</span>';
                this.button.setlevel.saveDisabled = false;
                this.button.setlevel.closeDisabled = false;
            });

        },
        setTicketAssign(){

            let btnContent = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
                btnContent += 'กำลังบันทึกข้อมูล...';

            this.button.setassign.saveContent = btnContent;
            this.button.setassign.saveDisabled = true;
            this.button.setassign.closeDisabled = true;

            let postData = {
                ticket_id: this.ticket.detail.data.ticket_id,
                user: this.$session.get('user'),
                next_sts: 3
            }

            axios.post('/api/admin/it/ticket/assign',postData)
            .then(response => {

                this.button.setassign.saveContent = '<i class="fas fa-check"></i><span class="ml-2">ยืนยันรับดำเนินการ</span>';
                this.button.setassign.saveDisabled = false;
                this.button.setassign.closeDisabled = false;
                
                if(response.data.status){
                    this.$swal({icon: 'success', text: 'บันทึกการรับดำเนินการเรียบร้อยแล้ว'});
                    this.ticket.detail.data.assign_name = response.data.assign_name;
                    this.ticket.detail.data.ticket_sts = 3;
                    $('#modal-ticket').modal('hide');
                    this.getTicketList(this.paginate.method);
                }
                else{
                    if(response.data.error == 'login.fail') this.neviLogin();
                    else this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกการรับดำเนินการได้'});
                }

            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกการรับดำเนินการได้'});
                this.button.setassign.saveContent = '<i class="fas fa-check"></i><span class="ml-2">ตกลง</span>';
                this.button.setassign.saveDisabled = false;
                this.button.setassign.closeDisabled = false;
            });

        },
        setTicketEndJob(){

            this.$swal({
                icon: 'info',
                title: 'ยืนยันการปิดงาน',
                text: 'Ticket เลขที่ '+this.ticket.detail.data.ticket_no,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ปิดงาน',
                denyButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed){

                    let btnContent = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
                        btnContent += 'กำลังบันทึกข้อมูล...';

                    this.button.setendjob.saveContent = btnContent;
                    this.button.setendjob.saveDisabled = true;
                    this.button.setendjob.closeDisabled = true;

                    let postData = {
                        ticket_id: this.ticket.detail.data.ticket_id,
                        user: this.$session.get('user'),
                        next_sts: 4
                    }

                    axios.post('/api/admin/it/ticket/endjob',postData)
                    .then(response => {
                        this.button.setendjob.saveContent = '<i class="fas fa-check"></i><span class="ml-2">สำเร็จ</span>';
                        this.button.setendjob.saveDisabled = false;
                        this.button.setendjob.closeDisabled = false;
                        
                        if(response.data.status){
                            this.$swal({icon: 'success', text: 'บันทึกการปิดงานเรียบร้อยแล้ว'});
                            let resData = response.data;
                            this.ticket.detail.data.ticket_st = postData.next_sts;
                            this.ticket.detail.data.ticket_end = resData.edate;
                            $('#modal-ticket').modal('hide');
                            this.getTicketList(this.paginate.method);
                        }
                        else{
                            if(response.data.error == 'login.fail') this.neviLogin();
                            else this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกการปิดงานได้'});
                        }
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกการปิดงานได้'});
                        this.button.setendjob.saveContent = '<i class="fas fa-check"></i><span class="ml-2">สำเร็จ</span>';
                        this.button.setendjob.saveDisabled = false;
                        this.button.setendjob.closeDisabled = false;
                    });

                }
            });
        },
        getTicketSurveyDetail(id){
            axios.get('/api/admin/it/ticket/survey/detail/'+id)
            .then(response => {
                this.survey.ticket = response.data.ticket;
                this.survey.detail = response.data.survey;
                $('#modal-survey-form').modal('show');
            });
        },
        getTicketMessage(ticketId){
            axios.get('/api/admin/it/ticket/message/list/'+ticketId)
            .then(response => { this.ticket.messageList = response.data });
        },
        saveTicketMessage(){

            if(this.ticket.messageText == ''){
                this.$swal({ icon: 'error', text: 'กรุณาใส่ข้อความตอบกลับ User' });
                return;
            }

            axios.post('/api/admin/it/ticket/message/save',{
                ticket_id: this.ticket.detail.data.ticket_id,
                user: this.$session.get('user'),
                message_text: this.ticket.messageText
            })
            .then(response => {
                if(response.data.status){
                    this.$swal({ icon: 'success', text: 'บันทึกข้อความตอบกลับ User เรียบร้อยแล้ว' });
                    this.ticket.messageText = '';
                    this.getTicketMessage(this.ticket.detail.data.ticket_id);
                }
                else{
                    if(response.data.error == 'login.fail') this.neviLogin();
                    else this.$swal({ icon: 'error', text: 'ไม่สามารถบันทึกข้อความตอบกลับ User ได้' });
                }
            })
            .catch(response => {
                this.$swal({ icon: 'error', text: 'ไม่สามารถบันทึกข้อความตอบกลับ User ได้' });
            });

        },
        removeTicketMessage(msgId){

            this.$swal({
                title: 'ยืนยันการลบข้อความ',
                text: 'คุณต้องการลบข้อความตอบกลับ User หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ต้องการ',
                cancelButtonText: 'ยกเลิก'

            }).then((result) => {
                if(result.isConfirmed){
                    axios.get('/api/admin/it/ticket/message/remove/'+msgId)
                    .then(response => {
                        if(response.data.status){
                            this.$swal({ icon: 'success', text: 'ลบข้อความตอบกลับ User เรียบร้อยแล้ว' });
                            this.getTicketMessage(this.ticket.detail.data.ticket_id);
                        }
                        else{
                            if(response.data.error == 'login.fail') this.neviLogin();
                            else this.$swal({ icon: 'error', text: 'ไม่สามารถลบข้อความตอบกลับ User ได้' });
                        }
                    })
                    .catch(response => {
                        this.$swal({ icon: 'error', text: 'ไม่สามารถลบข้อความตอบกลับ User ได้' });
                    });
                }
            });
        }
    }
}
</script>
