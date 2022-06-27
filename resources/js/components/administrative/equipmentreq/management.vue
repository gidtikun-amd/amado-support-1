<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการใบเบิกอุปกรณ์สำนักงาน</span>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs" id="" role="tablist">

                            <li class="nav-item" v-show="$session.get('role') == 'user.purchase'">
                                <a class="nav-link active" id="approve-tab" data-toggle="tab" href="#approve" role="tab" aria-controls="approve" aria-selected="true"
                                    v-bind:class="{'active': $session.get('role') == 'user.admin'}"
                                    v-on:click="getListReq('approve')"
                                >
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รออนุมัติ</span>
                                </a>
                            </li>
                            <li class="nav-item" v-show="$session.get('role') == 'user.admin'">
                                <a class="nav-link" id="wait-tab" data-toggle="tab" href="#wait" role="tab" aria-controls="wait" aria-selected="false"
                                    v-bind:class="{'active': $session.get('role') == 'user.admin'}"
                                    v-on:click="getListReq('wait')"
                                >
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รอดำเนินการ</span>
                                </a>
                            </li>
                            <li class="nav-item" v-show="$session.get('role') == 'user.admin'">
                                <a class="nav-link" id="inprogress-tab" data-toggle="tab" href="#inprogress" role="tab" aria-controls="inprogress" aria-selected="false"
                                    v-on:click="getListReq('inprogress')"
                                >
                                    <div class="spinner-border spinner-border-sm text-warning"></div>
                                    <span class="ml-2">กำลังดำเนินการ</span>
                                </a>
                            </li>
                            <li class="nav-item" v-show="$session.get('role') == 'user.admin'">
                                <a class="nav-link" id="remain-tab" data-toggle="tab" href="#remain" role="tab" aria-controls="remain" aria-selected="false"
                                    v-on:click="getListReq('remain')"
                                >
                                    <i class="fas fa-tags text-danger"></i>
                                    <span class="ml-2">คงค้าง</span>
                                </a>
                            </li>
                            <li class="nav-item" v-show="$session.get('role') == 'user.admin'">
                                <a class="nav-link" id="success-tab" data-toggle="tab" href="#success" role="tab" aria-controls="success" aria-selected="false"
                                    v-on:click="getListReq('success')"
                                >
                                    <i class="fas fa-check text-success"></i>
                                    <span class="ml-2">ดำเนินการเรียบร้อย</span>
                                </a>
                            </li>
                            <li class="nav-item" v-show="$session.get('role') == 'user.admin'">
                                <a class="nav-link" id="void-tab" data-toggle="tab" href="#void" role="tab" aria-controls="void" aria-selected="false"
                                    v-on:click="getListReq('void')"
                                >
                                    <i class="fas fa-minus text-danger"></i>
                                    <span class="ml-2">ยกเลิก</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false"
                                    v-on:click="getListReq('all')"
                                >
                                    <i class="fas fa-list-ul text-primary"></i>
                                    <span class="ml-2">รายการทั้งหมด</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <div class="form-inline mb-3">
                                        <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกเลขที่เอกสาร..." aria-label="Search"
                                            v-model="searchTxt"
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

                            <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="" id="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 text-center mt-2" v-show="progress.main">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-show="!progress.main">
                                    <div class="col-md-12">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>เลขที่เอกสาร</th>
                                                    <th>วันที่ร้องขอ</th>
                                                    <th>ผู้ร้องขอ</th>
                                                    <th>ฝ่าย</th>
                                                    <th>สถานะ</th>
                                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(list,index) in equip.data" :key="index">
                                                    <th class="align-middle">{{list.index}}</th>
                                                    <td class="align-middle">{{list.no}}</td>
                                                    <td class="align-middle">{{list.date}}</td>
                                                    <td class="align-middle">{{list.fullnameTh}}</td>
                                                    <td class="align-middle">{{list.deptname}}</td>
                                                    <td class="align-middle">
                                                        <span v-if="list.appsts == 0 && list.status == 1">รออนุมัติ</span>
                                                        <span v-else-if="$session.get('role') == 'user.purchase' && list.appsts == 1">อนุมัติแล้ว</span>
                                                        <span v-else>{{list.strsts}}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            v-on:click="getReqDetail(index)"
                                                        ><i class="fas fa-file-alt mr-1"></i> รายละเอียด</button>
                                                        <a class="btn btn-success btn-sm"
                                                            :href="'/pdf/equipreq/'+list.id" :target="'_blank'"
                                                        >
                                                            <i class="fas fa-print"></i><span class="ml-2">Print</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr v-show="equip.data.length <= 0">
                                                    <td colspan="7" class="text-center">
                                                        <b>ไม่มีข้อมูล</b>
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

         <!-- Modal : #modal-req -->
        <div class="modal fade" id="modal-req" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span class="mr-2">สถานะ : </span>

                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status"
                                    aria-hidden="true" v-show="equip.one.header.appsts == 0 && equip.one.header.appsts == 1"></span>
                                    <span class="ml-2" v-show="equip.one.header.appsts == 0 && equip.one.header.appsts == 1">รออนุมัติ</span>

                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status"
                                    aria-hidden="true" v-show="equip.one.header.status == 1 && equip.one.header.appsts == 1"></span>
                                    <span class="ml-2" v-show="equip.one.header.status == 1 && equip.one.header.appsts == 1">รอดำเนินการ</span>

                                    <i class="fas fa-check text-warning" v-show="equip.one.header.status == 2 && equip.one.header.appsts == 1"></i>
                                    <span class="ml-2" v-show="equip.one.header.status == 2 && equip.one.header.appsts == 1">กำลังดำเนินการ</span>

                                    <i class="fas fa-shipping-fast text-danger" v-show="equip.one.header.status == 3 && equip.one.header.appsts == 1"></i>
                                    <span class="ml-2" v-show="equip.one.header.status == 3 && equip.one.header.appsts == 1">คงค้าง</span>

                                    <i class="fas fa-list-ul text-success" v-show="equip.one.header.status == 4 && equip.one.header.appsts == 1"></i>
                                    <span class="ml-2" v-show="equip.one.header.status == 4 && equip.one.header.appsts == 1">ดำเนินการเรียบร้อย</span>

                                    <i class="fas fa-ban text-danger" v-show="equip.one.header.status == 0"></i>
                                    <span class="ml-2" v-show="equip.one.header.status == 0">ยกเลิก</span>
                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mx-4 mt-5">
                        <div class="row">
                            <div class="col-md-4">
                                <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>ใบเบิกอุปกรณ์สำนักงาน</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">เลขที่เอกสาร</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span>{{equip.one.header.no}}</span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span>{{equip.one.header.dmyDate}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mt-5 mb-3">
                            <div class="col-md-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อผู้เบิก</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <div class="bottom-dotted-full">
                                        <span class="ml-5">{{equip.one.header.fullnameTh}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle" rowspan="2" width="7%">ลำดับ</th>
                                            <th class="text-center align-middle" rowspan="2">รายการ</th>
                                            <th class="text-center align-middle" colspan="3" width="35%">จำนวนเบิก</th>
                                            <th class="text-center align-middle" rowspan="2" width="7%">ได้รับ</th>
                                            <th class="text-center align-middle" rowspan="2" width="7%">ยังไม่ได้รับ</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle">จำนวน</th>
                                            <th class="text-center align-middle">หน่วยนับ</th>
                                            <th class="text-center align-middle">Budget</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(dt,index) in equip.one.detail" :key="index">
                                            <th class="text-center align-middle">{{index+1}}</th>
                                            <td class="align-middle">{{dt.itName}}</td>
                                            <td class="text-right align-middle">
                                                <span v-show="
                                                    equip.one.header.appsts != 0
                                                    || (
                                                        $session.get('role') == 'user.admin'
                                                        || $session.get('role') == 'user.admin.manager'
                                                    )">{{dt.qty}}</span>
                                                <input type="number" class="form-control form-control-sm" min="1"
                                                v-model="dt.qty"
                                                v-show="equip.one.header.appsts == 0 && $session.get('role') == 'user.purchase'"
                                                v-on:keyup="changeQty(index)"
                                                v-on:change="changeQty(index)"
                                                :readonly="disabled.detail.qty">
                                            </td>
                                            <td class="text-center align-middle">{{dt.itUnit}}</td>
                                            <td class="text-right align-middle">{{dt.ttbudget}}</td>
                                            <td class="text-right align-middle">
                                                <span v-show="equip.one.header.appsts == 0
                                                    || equip.one.header.status == 1
                                                    || equip.one.header.status == 4">{{dt.sendto}}</span>
                                                <input type="number" class="form-control form-control-sm" min="1"
                                                v-model="dt.sendto"
                                                v-show="equip.one.header.appsts == 1
                                                    && (
                                                        $session.get('role') == 'user.admin'
                                                        || $session.get('role') == 'user.admin.manager' )
                                                    && (
                                                        equip.one.header.status == 2
                                                        || equip.one.header.status == 3
                                                    )"
                                                v-on:keyup="changeQty(index)"
                                                v-on:change="changeQty(index)"
                                                :readonly="disabled.detail.sendto">
                                            </td>
                                            <td class="text-right align-middle">
                                                <span>{{dt.outstand}}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7">
                                                รวม Budget : <b>{{equip.one.header.netbudget | numFormat('0.00')}}</b>
                                            </td>
                                        </tr>
                                        <tr><td colspan="7" class="text-right">
                                            <button class="btn btn-primary btn-sm"
                                            v-on:click="updateReqDetail()"
                                            :disabled="disabled.detail.btnChangeDetail"
                                             v-show="
                                                ( equip.one.header.appsts == 0 && (
                                                        $session.get('role') == 'user.purchase'
                                                        || $session.get('role') == 'user.purchase.manager'
                                                    )
                                                )
                                                || (
                                                    equip.one.header.status == 2
                                                    || equip.one.header.status == 3
                                                    && (
                                                        $session.get('role') == 'user.admin'
                                                        || $session.get('role') == 'user.admin.manager'
                                                    )
                                                )
                                            ">
                                                <i class="far fa-save mr-2"></i><span>บันทึก</span>
                                            </button>
                                        </td></tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-end m-0">
                            <div class="col-md-4 text-center">
                                <p class="mb-2">ผู้เบิก</p>
                                <p>{{equip.one.header.fullnameTh}}</p>
                            </div>
                        </div>
                        <div class="row" v-show="equip.one.header.appsts == 0 && $session.get('role') == 'user.purchase'">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="m-0"><b>ส่วนของผู้อนุมัติ</b></h6>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row" v-show="progress.approve">
                                            <div class="col-12">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-0 justify-content-center" v-show="!progress.approve">
                                            <button type="button" class="col-md-2 btn btn-success mr-3"
                                            v-on:click="setApprove(1)">
                                                <i class="fas fa-check"></i><span class="ml-2">อนุมัติ</span>
                                            </button>
                                            <button type="button" class="col-md-2 btn btn-danger mr-3"
                                            v-on:click="setApprove(2)">
                                                <i class="fas fa-ban"></i><span class="ml-2">ไม่อนุมัติ</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row"
                        v-show="equip.one.header.appsts == 1
                            && ( equip.one.header.status == 1
                            || equip.one.header.status == 2
                            || equip.one.header.status == 3 )
                            && $session.get('role') == 'user.admin'
                        ">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="m-0">
                                                    <i class="fas fa-toggle-on mr-2"></i>
                                                    <b>ปรับสถานะใบเบิกอุปกรณ์สำนักงาน</b>
                                                </h6>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row" v-show="progress.changeStatus">
                                            <div class="col-12">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-0 justify-content-center" v-show="!progress.changeStatus">
                                            <button type="button" class="col-md-3 btn btn-warning mr-3"
                                            v-on:click="changeStatus(2)"
                                            v-show="equip.one.header.status == 1">
                                                <i class="fas fa-check"></i><span class="ml-2">รับดำเนินการ</span>
                                            </button>
                                            <button type="button" class="col-md-3 btn btn-danger mr-3"
                                            v-on:click="changeStatus(3)"
                                            v-show="equip.one.header.status == 2">
                                                <i class="fas fa-tasks"></i><span class="ml-2">ยังรับของไม่ครบ</span>
                                            </button>
                                            <button type="button" class="col-md-3 btn btn-success mr-3"
                                            v-on:click="changeStatus(4)"
                                            v-show="equip.one.header.status == 2 || equip.one.header.status == 3">
                                                <i class="fas fa-check"></i><span class="ml-2">ดำเนินการเรียบร้อยแล้ว</span>
                                            </button>
                                            <button type="button" class="col-md-3 btn btn-danger mr-3"
                                            v-on:click="changeStatus(0)"
                                            v-show="equip.one.header.status == 1 || equip.one.header.status == 2">
                                                <i class="fas fa-ban"></i><span class="ml-2">ยกเลิก</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-0 justify-content-center"
                        v-show="equip.one.header.status == 4 || equip.one.header.status == 0">
                            <div class="col-6">
                                <div class="alert alert-success text-center" role="alert"
                                v-if="equip.one.header.status == 4">
                                    <i class="fas fa-check mr-2"></i><b>ทำรายการเรียบร้อยแล้ว</b>
                                </div>
                                <div class="alert alert-danger text-center" role="alert"
                                v-else>
                                    <i class="fas fa-ban mr-2"></i>
                                    <b v-if="equip.one.header.appsts == 0 && equip.one.header.status == 0">ยกเลิกแล้ว</b>
                                    <b v-else-if="equip.one.header.appsts == 2">ไม่อนุมัติ</b>
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
                                                    <i class="fas fa-tag mr-2"></i>
                                                    <b>หมายเหตุ</b>
                                                </h6>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <form>
                                                    <div class="form-group">
                                                        <textarea class="form-control" v-model="equip.remark" rows="3"></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary btn-sm"
                                                v-on:click="saveRemark()">
                                                    <i class="far fa-save mr-2"></i>
                                                    บันทึก
                                                </button>
                                            </div>
                                        </div>
                                    </div>
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
                                                    <b>เอกสารใบเบิกอุปกรณ์สำนักงาน</b>
                                                </h6>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <form enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>Upload ไฟล์ : </label> <label v-html="paper.upload.status"></label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="equipUploadFile"
                                                                aria-describedby="btnEquipUploadFile" accept=".pdf"
                                                                v-on:change="onFileChange"
                                                                v-bind:class="{
                                                                    'is-valid': paper.upload.classInptValid,
                                                                    'is-invalid': paper.upload.classInptInvalid
                                                                }"
                                                                :disabled="paper.upload.inptDisabled">
                                                                <label class="custom-file-label" for="equipUploadFile" >Choose file</label>
                                                            </div>
                                                            <div class="input-group-append">
                                                                <button type="submit" class="btn btn-outline-secondary" id="btnEquipUploadFile"
                                                                 @click.prevent="uploadFile"
                                                                :disabled="paper.upload.btnUploadDisabled">
                                                                    <i class="fas fa-cloud-upload-alt"></i> Upload
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="progress my-3" v-show="progress.uploadFile">
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
                                                    v-for="(file,index) in equip.one.files" :key="index">
                                                        <strong>#{{index+1}}</strong> File :
                                                        <a class="text-decoration-none text-primary ml-3" target="_blank"
                                                        :href="file.filePath"
                                                        >
                                                        {{file.fileName}}
                                                        </a>
                                                        <a href="javascript:;" class="float-right"
                                                        v-on:click="removeFile(index)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item text-center py-1"
                                                    v-show="equip.one.files.length <= 0"><b>ไม่มีไฟล์เอกสาร</b></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-4 justify-content-center">
                            <button type="button" class="col-md-3 btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>

import numeral from 'numeral';
import numFormat from 'vue-filter-number-format';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    mounted(){
        this.getListReq((this.$session.get('role') == 'user.purchase' ? 'approve' : 'wait'));
    },
    data(){
        return{
            searchTxt: '',
            progress: {
                main: false,
                approve: false,
                changeStatus: false,
                uploadFile: false
            },
            disabled: {
                detail: {
                    qty: false,
                    sendto: false,
                    btnChangeDetail: false,
                }
            },
            equip: {
                data: [],
                filter: [],
                one: {
                    header: {},
                    detail: {},
                    files: []
                },
                remark: ''
            },
            paper: {
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
            },
            paginate: {
                target: '',
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
        }
    },
    methods:{
        getListReq(target){

            $('.nav-item a').removeClass('active');
            switch (target) {
                case 'approve': var attr = 'approve-tab'; break;
                case 'wait': var attr = 'wait-tab'; break;
                case 'inprogress': var attr = 'inprogress-tab'; break;
                case 'remain': var attr = 'remain-tab'; break;
                case 'success': var attr = 'success-tab'; break;
                case 'void': var attr = 'void-tab'; break;
                case 'all': var attr = 'all-tab'; break;
            }
            $('#tabpanel').attr('aria-label', attr);
            $('#'+attr).addClass('active');

            this.progress.main = true;
            this.paginate.target = target;
            axios.post('/api/ad/equip-req/history',{
                page: 'admin',
                search: this.searchTxt,
                status: this.paginate.target,
                paginate: this.paginate.list
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            });
        },
        getDataPaginate(page){

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'perv' ){ this.paginate.url = this.paginate.pervPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            this.progress.main = true;
            this.paginate.target = target;

            axios.post(this.paginate.url,{
                page: 'admin',
                search: this.searchTxt,
                status: this.paginate.target,
                paginate: this.paginate.list
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            })

        },
        setDataAndPaginate(dataArr){
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

            this.equip.data = resultData;
            this.equip.filter = resultData;
            this.paginate.numberPage = resultPageNumber;
            this.progress.main = false;
            this.paginate.show = true;
        },
        getReqDetail(index){
            axios.get('/api/ad/equip-req/detail/'+this.equip.data[index].id)
            .then(response => {
                this.equip.one = response.data;
                this.equip.remark = response.data.header.remark;
                $('#modal-req').modal('show');
            });
        },
        changeQty(index){

            if(this.equip.one.detail[index].qty < this.equip.one.detail[index].sendto){
                // this.$swal({icon: 'error', text: 'ในแถวที่ '+(index+1)+' จำนวนได้รับมากกว่าจำนวนเบิก'});
                this.equip.one.detail[index].sendto = this.equip.one.detail[index].qty;
            }


            if(this.equip.one.detail[index].qty == '' || this.equip.one.detail[index].qty < 0)
                this.equip.one.detail[index].qty = 0;

            const qty = parseInt(this.equip.one.detail[index].qty),
                sendto = parseInt(this.equip.one.detail[index].sendto),
                budget = parseFloat(this.equip.one.detail[index].budget),
                netbudget = parseFloat(this.equip.one.header.netbudget),
                ttbudget = parseFloat(this.equip.one.detail[index].ttbudget);

            let outstand = qty - sendto,
                new_ttbudget =  budget * qty,
                old_netbudget = netbudget - ttbudget,
                new_netbudget = old_netbudget + new_ttbudget;

            this.equip.one.detail[index].outstand = outstand;
            this.equip.one.detail[index].ttbudget = ttbudget;
            this.equip.one.header.netbudget = new_netbudget;

        },
        updateReqDetail(){

            for(var i = 0; i < this.equip.one.detail.length; i++){
                if(this.equip.one.detail[i].qty < this.equip.one.detail[i].sendto){
                    this.$swal({icon: 'error', text: 'ในแถวที่ '+(i+1)+' จำนวนได้รับมากกว่าจำนวนเบิก'});
                    return;
                }
            }

            this.$swal({
                text: 'ยืนยันการเปลี่ยนแปลงรายละเอียดใบเบิกอุปกรณ์สำนักงาน',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if(result.isConfirmed){

                    this.disabled.detail.qty = true;
                    this.disabled.detail.sendto = true;
                    this.disabled.detail.btnChangeDetail = true;

                    axios.post('/api/ad/equip-req/update/detail',{
                        id: this.equip.one.header.id,
                        netbudget: this.equip.one.header.netbudget,
                        detail: this.equip.one.detail
                    })
                    .then(response => {
                        this.$swal({icon: 'success', text: 'เปลี่ยนแปลงรายละเอียดใบเบิกอุปกรณ์สำนักงานเรียบร้อยแล้ว'});

                        for(var i = 0; i < this.equip.one.detail.length; i++)
                            this.equip.one.detail[i].rcost = this.equip.one.detail[i].outstand;

                        this.disabled.detail.qty = false;
                        this.disabled.detail.sendto = false;
                        this.disabled.detail.btnChangeDetail = false;
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถเปลี่ยนแปลงรายละเอียดใบเบิกอุปกรณ์สำนักงานได้'});
                        this.disabled.detail.qty = false;
                        this.disabled.detail.sendto = false;
                        this.disabled.detail.btnChangeDetail = false;
                    });

                }
            });

        },
        saveRemark(){
            axios.post('/api/ad/equip-req/remark/save',{
                id: this.equip.one.header.id,
                remark: this.equip.remark
            })
            .then(response => {
                this.$swal({icon: 'success', text: 'บันทึกหมายเหตุเรียบร้อยแล้ว'});
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกหมายเหตุได้'});
            });
        },
        setApprove(sts){
            this.progress.approve = true;
            axios.post('/api/ad/equip-req/approve',{
                id: this.equip.one.header.id,
                no: this.equip.one.header.no,
                status: sts,
                user: this.$session.get('user')
            })
            .then(response => {
                this.$swal({icon: 'success', text: 'อนุมัติใบเบิกอุปกรณ์สำนักงานเรียบร้อยแล้ว'});
                this.progress.approve = false;
                $('#modal-req').modal('hide');
                this.getListReq(this.paginate.target);
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถอนุมัติใบเบิกอุปกรณ์สำนักงานได้'});
                this.progress.approve = false;
            });
        },
        changeStatus(sts){
            if(sts == 0){
                this.$swal({
                    title: 'ยกเลิกใบเบิกอุปกรณ์สำนักงาน',
                    text: 'ต้องการยกเลิก '+this.equip.one.header.no+' หรือไม่',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                })
                .then((result) => {
                    if(result.isConfirmed)
                        this.setStatus(sts);
                });
            }
            else if(sts == 3){

                let total_qty = 0,
                    total_sendto = 0;

                for(var i = 0; i < this.equip.one.detail.length; i++){
                    total_qty += parseInt(this.equip.one.detail[i].qty);
                    total_sendto += parseInt(this.equip.one.detail[i].sendto);
                }

                if(total_qty == total_sendto){
                    this.$swal({icon: 'warning', text: 'บันทึกรับครบหมดแล้ว กรุณาเปลี่ยนสถานะเป็น ดำเนินการเรียบร้อยแล้ว'});
                    return;
                }

                this.setStatus(sts);

            }
            else if(sts == 4){

                for(var i = 0; i < this.equip.one.detail.length; i++){
                    if(this.equip.one.detail[i].qty > this.equip.one.detail[i].sendto){
                        this.$swal({icon: 'error', text: 'ในแถวที่ '+(i+1)+' ยังบันทึกรับไม่ครบ'});
                        return;
                    }
                }

                var checkSendToAll = true;

                for(var i = 0; i < this.equip.one.detail.length; i++){
                    if(this.equip.one.detail[i].rcost > 0){
                        checkSendToAll = false;
                    }
                }

                if(!checkSendToAll){
                    this.$swal({icon: 'error', text: 'กรุณากดบันทึกการรับอุปกรณ์สำนักงานก่อน'});
                    return;
                }
                
                this.setStatus(sts);

            }
            else this.setStatus(sts);
        },
        setStatus(sts){
            this.progress.changeStatus = true;
            this.disabled.detail.qty = true;
            this.disabled.detail.sendto = true;
            this.disabled.detail.btnChangeDetail = true;
            axios.post('/api/ad/equip-req/update/status',{
                id: this.equip.one.header.id,
                status: sts,
                user: this.$session.get('user')
            })
            .then(response => {
                this.$swal({icon: 'success', text: 'ปรับสถานะใบเบิกอุปกรณ์สำนักงานเรียบร้อยแล้ว'});
                $('#modal-req').modal('hide');
                this.progress.changeStatus = false;
                this.disabled.detail.qty = false;
                this.disabled.detail.sendto = false;
                this.disabled.detail.btnChangeDetail = false;
                this.getListReq(this.paginate.target);
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถปรับสถานะใบเบิกอุปกรณ์สำนักงานได้'});
                this.progress.changeStatus = false;
                this.disabled.detail.qty = false;
                this.disabled.detail.sendto = false;
                this.disabled.detail.btnChangeDetail = false;
            });
        },
        getListFiles(id){
            this.paper.upload.progress = true;
            axios.get('/api/ad/equip-req/files/list/'+id)
            .then(response => {
                this.equip.one.files = response.data;
                this.paper.upload.progress = false;
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

            $('#equipUploadFile').next('.custom-file-label').html(this.paper.file_name);

        },
        uploadFile(e){

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
            formData.append('id',this.equip.one.header.id);
            formData.append('file_name',this.paper.file_name);
            formData.append('file',this.paper.file);

            axios.post('/api/ad/equip-req/files/upload',formData,config)
            .then(response => {
                $('#equipUploadFile').next('.custom-file-label').html('Choose file');
                let sts_sc = '<i class="fas fa-check-circle text-success mr-3"></i>';
                    sts_sc += '<strong class="text-success">Upload file complete.</strong>';
                this.paper.upload.status = sts_sc;
                this.paper.upload.progress = false;
                this.paper.upload.classInptValid = true;
                this.paper.upload.classInptInvalid = false;
                this.paper.upload.inptDisabled = false;
                this.paper.upload.btnUploadDisabled = false;
                this.getListFiles(this.equip.one.header.id);
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
        removeFile(index){
            this.$swal({
                icon: 'warning',
                title: 'ยืนยันการลบไฟล์ <br>'+this.equip.one.files[index].fileName,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ลบไฟล์',
                denyButtonText: 'ยกเลิก',
            })
            .then((result) => {
                if(result.isConfirmed){

                    this.paper.upload.progress = true;

                    axios.post('/api/ad/equip-req/files/remove',{
                        id: this.equip.one.files[index].fileId,
                        path: this.equip.one.files[index].filePath
                    })
                    .then(response => {
                        this.$swal({icon: 'success', title: 'ลบไฟล์เรียบร้อยแล้ว'});
                        this.getListFiles(this.equip.one.header.id);
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', title: 'ไม่สามารถลบไฟล์ได้'});
                    });

                }
            });
        }
    }
}
</script>
