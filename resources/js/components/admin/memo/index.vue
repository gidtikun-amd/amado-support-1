<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการคำขอ Memo</span>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs" id="" role="tablist" >
                            <li class="nav-item" v-show="manager.showTabWaitappr">
                                <a class="nav-link" id="waitappr-tab" data-toggle="tab" href="#waitappr"
                                role="tab" aria-controls="waitappr" aria-selected="true"
                                v-bind:class="{'active': manager.showTabWaitappr}"
                                v-on:click="getMemoList('waitappr')">
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รออนุมัติ</span>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" id="waitproc-tab" data-toggle="tab" href="#waitproc"
                                role="tab" aria-controls="waitproc" aria-selected="true"
                                v-on:click="getMemoList('waitproc')">
                                    <span class="spinner-grow spinner-grow-mini text-warning" role="status" aria-hidden="true"></span>
                                    <span class="ml-2">รอรับดำเนินการ</span>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" id="processing-tab" data-toggle="tab" href="#processing"
                                role="tab" aria-controls="processing" aria-selected="false"
                                v-bind:class="{'active': !manager.showTabWaitappr}"
                                v-on:click="getMemoList('progress')">
                                    <div class="spinner-border spinner-border-sm text-warning"></div>
                                    <span class="ml-2">กำลังดำเนินการ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="success-tab" data-toggle="tab" href="#success"
                                role="tab" aria-controls="success" aria-selected="false"
                                v-on:click="getMemoList('success')">
                                    <i class="fas fa-check text-success"></i>
                                    <span class="ml-2">ดำเนินการเรียบร้อย</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="void-tab" data-toggle="tab" href="#void"
                                role="tab" aria-controls="void" aria-selected="false"
                                v-on:click="getMemoList('void')">
                                    <i class="fas fa-minus text-danger"></i>
                                    <span class="ml-2">ไม่อนุมัติ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="all-tab" data-toggle="tab" href="#all"
                                role="tab" aria-controls="all" aria-selected="false"
                                v-on:click="getMemoList('all')">
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
                                            v-on:keyup.enter="getMemoList(paginate.method)">
                                        </div>
                                        <div class="col-auto">
                                            <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                                v-model="searchName"
                                                v-on:keyup.enter="getMemoList(paginate.method)"
                                            >
                                        </div>
                                        <div class="col-auto">
                                            <label>จุดประสงค์</label>
                                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกจุดประสงค์" aria-label="Search"
                                                v-model="searchObject"
                                                v-on:keyup.enter="getMemoList(paginate.method)"
                                            >
                                        </div>
                                        <div class="col-auto">
                                            <label>สถานะเอกสาร</label>
                                            <select class="form-control form-control-sm"
                                            v-model="searchPaper"
                                            v-on:keyup.enter="getMemoList(paginate.method)">
                                                <option value="">ทั้งหมด</option>
                                                <option value="1">มีเอกสารแล้ว</option>
                                                <option value="2">ยังไม่ได้อัพโหลดเอกสาร</option>
                                            </select>
                                        </div>
                                        <div class="col-auto my-4">
                                            <button class="btn btn-sm btn-amado my-2" type="button"
                                            v-on:click="getMemoList(paginate.method)"
                                            :disabled="button.search.disabled"
                                            >
                                                <i class="fas fa-search"></i>
                                                <span class="ml-2">ค้นหา</span>
                                            </button>
                                            <button class="btn btn-sm btn-primary ml-2" type="button"
                                            v-on:click="getMemoList(paginate.method)"
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

                            <div class="tab-pane fade" id="waitappr" role="tabpanel" aria-labelledby="waitappr-tab"
                            v-bind:class="{
                                'active': $session.get('role') == 'admin.it.manager',
                                'show': $session.get('role') == 'admin.it.manager'
                            }">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่คำขอ</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col">สถานะเอกสาร</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(wa,index) in memo.waitappr" :key="index">
                                                    <th scope="row">{{wa.index}}</th>
                                                    <td>{{wa.no}}</td>
                                                    <td>{{wa.date}}</td>
                                                    <td>{{wa.efullnameTh}}</td>
                                                    <td>{{wa.deptname}}</td>
                                                    <td>{{wa.object}}</td>
                                                    <td>รออนุมัติ</td>
                                                    <td>{{wa.sfiles}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                                v-on:click="getMemoDetail(index)">
                                                            <i class="fas fa-file-alt mr-2"></i>อนุมัติ
                                                        </button>
                                                        <a class="btn btn-success btn-sm mb-1"
                                                           :href="'/pdf/memo/'+wa.id"
                                                           :target="'_blank'">
                                                            <i class="fas fa-print mr-2"></i>Print
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                                v-on:click="modalMemoFile(index)">
                                                            <i class="far fa-folder-open mr-2"></i>เอกสาร
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="tab-pane fade" id="waitproc" role="tabpanel" aria-labelledby="waitproc-tab">
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
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(wa,index) in memo.waitproc" :key="index">
                                                    <th scope="row">{{wp.index}}</th>
                                                    <td>{{wp.no}}</td>
                                                    <td>{{wp.date}}</td>
                                                    <td>{{wp.efullnameTh}}</td>
                                                    <td>{{wp.deptname}}</td>
                                                    <td>{{wp.object}}</td>
                                                    <td>รอรับดำเนินการ</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-success btn-sm"
                                                        v-on:click="modalMemoFile(index)">
                                                            <i class="far fa-folder-open"></i>
                                                            <span class="ml-2">เอกสาร</span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                        v-on:click="getMemoDetail(index)">
                                                            <i class="fas fa-file-alt"></i><span class="ml-2">ทำรายการ</span>
                                                        </button>
                                                        <a class="btn btn-secondary btn-sm"
                                                        :href="'/pdf/memo/'+wp.id"
                                                        :target="'_blank'">
                                                            <i class="fas fa-file-pdf"></i><span class="ml-2">Print PDF</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> -->

                            <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="processing-tab"
                            v-bind:class="{
                                'active': $session.get('role') == 'admin.it',
                                'show': $session.get('role') == 'admin.it'
                            }">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่คำขอ</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col">ผู้ดำเนินการ</th>
                                                    <th scope="col">สถานะเอกสาร</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(p,index) in memo.progress" :key="index">
                                                    <th scope="row">{{p.index}}</th>
                                                    <td>{{p.no}}</td>
                                                    <td>{{p.date}}</td>
                                                    <td>{{p.efullnameTh}}</td>
                                                    <td>{{p.deptname}}</td>
                                                    <td>{{p.object}}</td>
                                                    <td>กำลังดำเนินการ</td>
                                                    <td>{{p.assign_name}}</td>
                                                    <td>{{p.sfiles}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                                v-on:click="getMemoDetail(index)">
                                                            <i class="fas fa-file-alt mr-2"></i>ทำรายการ
                                                        </button>
                                                        <a class="btn btn-success btn-sm mb-1"
                                                           :href="'/pdf/memo/'+p.id"
                                                           :target="'_blank'">
                                                            <i class="fas fa-print mr-2"></i>Print
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                        v-on:click="modalMemoFile(index)">
                                                            <i class="far fa-folder-open mr-2"></i>เอกสาร
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
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่คำขอ</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col">ผู้ดำเนินการ</th>
                                                    <th scope="col">สถานะเอกสาร</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(s,index) in memo.success" :key="index">
                                                    <th scope="row">{{s.index}}</th>
                                                    <td>{{s.no}}</td>
                                                    <td>{{s.date}}</td>
                                                    <td>{{s.efullnameTh}}</td>
                                                    <td>{{s.deptname}}</td>
                                                    <td>{{s.object}}</td>
                                                    <td>ดำเนินการเรียบร้อย</td>
                                                    <td>{{s.assign_name}}</td>
                                                    <td>{{s.sfiles}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                                v-on:click="getMemoDetail(index)">
                                                            <i class="fas fa-file-alt mr-2"></i>ดูรายละเอียด
                                                        </button>
                                                        <a class="btn btn-success btn-sm mb-1"
                                                           :href="'/pdf/memo/'+s.id"
                                                           :target="'_blank'">
                                                            <i class="fas fa-print mr-2"></i>Print
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                        v-on:click="modalMemoFile(index)">
                                                            <i class="far fa-folder-open mr-2"></i>เอกสาร
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
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">เลขที่คำขอ</th>
                                                    <th scope="col">วันที่สร้าง</th>
                                                    <th scope="col">ชื่อ</th>
                                                    <th scope="col">ฝ่าย</th>
                                                    <th scope="col">จุดประสงค์</th>
                                                    <th scope="col">สถานะ</th>
                                                    <th scope="col">สถานะเอกสาร</th>
                                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(v,index) in memo.void" :key="index">
                                                    <th scope="row">{{v.index}}</th>
                                                    <td>{{v.no}}</td>
                                                    <td>{{v.date}}</td>
                                                    <td>{{v.efullnameTh}}</td>
                                                    <td>{{v.deptname}}</td>
                                                    <td>{{v.object}}</td>
                                                    <td>ไม่อนุมัติ</td>
                                                    <td>{{v.sfiles}}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                                                v-on:click="getMemoDetail(index)">
                                                            <i class="fas fa-file-alt mr-2"></i>ดูรายละเอียด
                                                        </button>
                                                        <a class="btn btn-success btn-sm mb-1"
                                                           :href="'/pdf/memo/'+v.id"
                                                           :target="'_blank'">
                                                            <i class="fas fa-print mr-2"></i>Print
                                                        </a>
                                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                        v-on:click="modalMemoFile(index)">
                                                            <i class="far fa-folder-open mr-2"></i>เอกสาร
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
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">เลขที่คำขอ</th>
                                                        <th scope="col">วันที่สร้าง</th>
                                                        <th scope="col">ชื่อ</th>
                                                        <th scope="col">ฝ่าย</th>
                                                        <th scope="col">จุดประสงค์</th>
                                                        <th scope="col">สถานะ</th>
                                                        <th scope="col">ผู้ดำเนินการ</th>
                                                        <th scope="col">สถานะเอกสาร</th>
                                                        <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(a,index) in memo.all" :key="index">
                                                        <th scope="row">{{a.index}}</th>
                                                        <td>{{a.no}}</td>
                                                        <td>{{a.date}}</td>
                                                        <td>{{a.efullnameTh}}</td>
                                                        <td>{{a.deptname}}</td>
                                                        <td>{{a.object}}</td>
                                                        <td>{{a.apprstsstr}}</td>
                                                        <td>{{a.assign_name}}</td>
                                                        <td>{{a.sfiles}}</td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-primary btn-sm mb-1"
                                                                    v-on:click="getMemoDetail(index)">
                                                                <i class="fas fa-file-alt mr-2"></i>ดูรายละเอียด
                                                            </button>
                                                            <a class="btn btn-success btn-sm mb-1"
                                                               :href="'/pdf/memo/'+a.id"
                                                               :target="'_blank'">
                                                                <i class="fas fa-print mr-2"></i>Print
                                                            </a>
                                                            <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                            v-on:click="modalMemoFile(index)">
                                                                <i class="far fa-folder-open mr-2"></i>เอกสาร
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

        <!-- Modal : #modal-memo -->
        <div class="modal fade" id="modal-memo" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span class="mr-2">สถานะ : </span>
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"
                                    v-show="memo.data.header.apprsts == 0 && memo.data.header.status == 1"></span>
                                    <span class="ml-2" v-show="memo.data.header.apprsts == 0 && memo.data.header.status == 1">รออนุมัติ</span>
                                    <i class="fas fa-check text-warning" v-show="memo.data.header.status == 2"></i>
                                    <span class="ml-2" v-show="memo.data.header.status == 2">กำลังดำเนินการ</span>
                                    <i class="fas fa-list-ul text-success" v-show="memo.data.header.status == 3"></i>
                                    <span class="ml-2" v-show="memo.data.header.status == 3">ดำเนินการเรียบร้อย</span>
                                    <i class="fas fa-ban text-danger" v-show="memo.data.header.apprsts == 2 || memo.data.header.status == 0"></i>
                                    <span class="ml-2" v-show="memo.data.header.apprsts == 2 || memo.data.header.status == 0">ไม่อนุมัติ</span>
                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mx-4 mt-5">
                        <div class="row m-0 mb-3">
                            <div class="col-md-4">
                                <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>MEMO</b></p>
                                <p><b>AMADO GROUP</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">เลขที่เอกสาร</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span>{{memo.data.header.no}}</span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span>{{memo.data.header.date}}</span>
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
                                        <span class="ml-5">{{memo.data.header.efullnameTh}}</span>
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
                                        <span class="ml-5">{{memo.data.header.efullnameEn}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">แผนก</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{memo.data.header.deptname}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{memo.data.header.position}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">เบอร์โทรศัพท์</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{memo.data.header.tel}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">จุดประสงค์ในการดำเนินการ</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <div class="bottom-dotted-full">
                                        <span class="ml-5">{{memo.data.header.object}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3 mt-3">
                            <div class="col-md-12">
                                <table class="table table-hover table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan="2" class="text-center">รายละเอียด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(md,index) in memo.data.detail" :key="index">
                                            <th scope="row" class="text-center" width="7%">{{index+1}}</th>
                                            <td>{{md.detail}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-end m-0">
                            <div class="col-md-4 text-center">
                                <p class="mb-2">ผู้แจ้งขอดำเนินการ</p>
                                <p>{{memo.data.header.efullnameTh}}</p>
                            </div>
                        </div>
                        <div class="row" v-show="$session.get('role') == 'admin.it.manager' && ( memo.data.header.apprsts == 0 && memo.data.header.status == 1 )">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="m-0"><b>ส่วนของผู้อนุมัติ</b></h6>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row mb-0 justify-content-center" v-show="!approve.takeAction">
                                            <button type="button" class="col-md-2 btn btn-success mr-3"
                                            v-on:click="setApprove(true,true)">
                                                <i class="fas fa-check"></i><span class="ml-2">อนุมัติ</span>
                                            </button>
                                            <button type="button" class="col-md-2 btn btn-danger mr-3"
                                            v-on:click="setApprove(true,false)">
                                                <i class="fas fa-times"></i><span class="ml-2">ไม่อนุมัติ</span>
                                            </button>
                                        </div>
                                        <div class="row" v-show="approve.takeAction">
                                            <div class="col-md-12 mb-2" v-show="approve.isApprove">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text bg-white">
                                                            <i class="fas fa-check text-success"></i>
                                                            <span class="font-weight-bold ml-2 text-success">อนุมัติ</span>
                                                        </label>
                                                        <label class="rounded-0 input-group-text">
                                                            Assign To
                                                        </label>
                                                    </div>
                                                    <select class="custom-select rounded-right"
                                                    v-model="approve.inpAdmin"
                                                    :disabled="approve.disabledAdmin">
                                                        <option v-for="ad in mis" :key="ad.user" :value="ad.user">
                                                            {{ad.fullnameTh}} ({{ad.nickname}})
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-2" v-show="approve.isUnapprive">
                                                <p class="font-weight-bold ml-2">
                                                    <span class="text-danger"><i class="fas fa-times mr-2"></i>ไม่อนุมัติ</span>
                                                    <span class="ml-3 mr-3">-</span>
                                                    <span>หมายเหตุ</span>
                                                </p>
                                                <textarea class="form-control" rows="3"
                                                v-model="approve.inpRemark"
                                                :disabled="approve.disabledRemark"></textarea>
                                            </div>
                                            <div class="col-12 text-center mt-2">
                                                <button type="button" class="col-md-2 btn btn-success mr-3"
                                                v-on:click="saveApprove()"
                                                v-html="approve.contentBtnSave"
                                                :disabled="approve.disabledBtnSave">
                                                    <i class="fas fa-check"></i><span class="ml-2">บันทึก</span>
                                                </button>
                                                <button type="button" class="col-md-2 btn btn-danger mr-3"
                                                v-on:click="setApprove(false,false)"
                                                :disabled="approve.disabledBtnCancle">
                                                    <i class="fas fa-times"></i><span class="ml-2">ยกเลิก</span>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-show="memo.data.header.status == 2 && $session.get('user') == memo.data.header.assign">
                             <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="m-0"><b><i class="fas fa-exclamation-triangle text-amado mr-3"></i>ยืนยันสำเร็จ ?</b></h5>
                                            </div>
                                        </div>

                                        <hr class="mb-3">

                                        <div class="row mb-0 justify-content-center">
                                            <button type="button" class="col-md-3 btn btn-success mr-3"
                                            v-on:click="changeStatusMemo(3)"
                                            v-html="complete.contentBtnSave"
                                            :disabled="complete.disabledBtnSave">
                                                <i class="fas fa-check"></i><span class="ml-2">สำเร็จ</span>
                                            </button>
                                            <button type="button" class="col-md-2 btn btn-secondary mr-3" data-dismiss="modal"
                                            :disabled="complete.disabledBtnCancle">
                                                <i class="fas fa-times"></i><span class="ml-2">ยกเลิก</span>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row" v-show="memo.data.header.apprsts == 2 || memo.data.header.status == 3">
                            <div class="col-12">
                                <div class="alert alert-success text-center" role="alert" v-show="memo.data.header.status == 3">
                                    <h5><i class="fas fa-check mr-2"></i> รายการ Memo เสร็จเรียบร้อยแล้ว</h5>
                                </div>
                                <div class="alert alert-danger text-center" role="alert" v-show="memo.data.header.apprsts == 2">
                                    <h5><i class="fas fa-times mr-2"></i> รายการ Memo ไม่อนุมัติ</h5>
                                </div>
                                หมายเหตุ
                                <textarea class="form-control" rows="3"
                                v-show="memo.data.header.apprsts == 2"
                                v-html="memo.data.header.remark"
                                readonly>
                                </textarea>
                            </div>
                        </div>

                        <div class="row mt-2" v-show="memo.data.header.status == 2 || memo.data.header.status == 3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 align-middle">
                                                <i class="fas fa-user-tag"></i>
                                                <span class="font-weight-bold">ผู้ดำเนินการ :</span>
                                                <span class="font-weight-bold text-primary">{{memo.data.header.assign_name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                             <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="font-weight-bold">บันทึกการ</span>
                                                <hr style="margin-top: 0px;">
                                                <ul class="list-group">
                                                    <li class="list-group-item py-1" v-for="(l,index) in memo.data.log" :key="index">
                                                        <span class="mr-3">{{index+1}})</span>
                                                        <span class="font-weight-bold text-primary">{{l.action}}</span>
                                                        <span class="font-weight-bold ml-2 mr-2">:</span>
                                                        <span class="font-weight-bold text-primary">
                                                            {{l.fullname}} ({{l.created_at}})
                                                        </span>
                                                    </li>
                                                </ul>
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

        <!-- Modal : #modal-memo-file -->
        <div class="modal fade" id="modal-memo-file" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary">
                            <i class="fas fa-folder-open mr-2"></i><span>เอกสาร Memo</span>
                        </h5>
                    </div>
                    <div class="modal-body p-5">
                        <div class="row">
                            <div class="col-12">
                                <form enctype="multipart/form-data">
                                    <div class='form-group'>
                                        <label>Upload ไฟล์ : </label> <label v-html="files.upload.status"></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="memoUploadFile" aria-describedby="btnMemoUploadFile"
                                                v-on:change="onFileChange"
                                                v-bind:class="{
                                                    'is-void': files.upload.classInptValid,
                                                    'is-invalid': files.upload.classInptInvalid
                                                }"
                                                :disabled="files.upload.inptDisabled"
                                                >
                                                <label class="custom-file-label" for="jobreqUploadFile" >Choose file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-secondary" id="btnMemoUploadFile"
                                                @click.prevent="uploadItmreqFile"
                                                :disabled="files.upload.btnUploadDisabled"
                                                >
                                                    <i class="fas fa-cloud-upload-alt"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="progress my-3" v-show="files.upload.progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>รายการเอกสาร : </label>
                                <ul class="list-group">
                                    <li class="list-group-item py-1" v-for="(file,index) in memo.files" :key="index">
                                        <strong>#{{index+1}}</strong> File :
                                        <a class="text-decoration-none text-primary ml-3"
                                        :href="file.mm_file_path"
                                        :target="'_blank'">
                                            {{file.mm_file_name}}
                                        </a>
                                        <a href="javascript:;" class="float-right"
                                        v-on:click="removeMemoFile(index)">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>ออก
                        </button>
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
        this.getMemoList(( this.$session.get('role') == 'admin.it.manager' ? 'waitappr' : 'progress' ));
        this.getAdminMis();
    },
    data(){
        return{
            manager:{
                showTabWaitappr: false
            },
            searchTxt: '',
            searchName: '',
            searchPaper: '',
            searchObject: '',
            progress: {
                show: false,
            },
            manager: {
                showTabWaitappr: true,
            },
            button:{
                search:{
                    disabled: false,
                },
                redata:{
                    disabled: false,
                }
            },
            mis: [],
            memo: {
                tempData: {},
                waitappr: {},
                waitproc: {},
                progress: {},
                success: {},
                void: {},
                all: {},
                files: [],
                data: {
                    header: {},
                    detail: [],
                    log: {},
                },
            },
            approve:{
                index: '',
                memoId: '',
                status: 0,
                takeAction: false,
                isApprove: false,
                isUnapprive: false,
                inpAdmin: '',
                inpRemark: '',
                disabledAdmin: false,
                disabledRemark: false,
                contentBtnSave: '<i class="fas fa-check"></i><span class="ml-2">บันทึก</span>',
                disabledBtnSave: false,
                disabledBtnCancle: false,
            },
            complete:{
                contentBtnSave: '<i class="fas fa-check"></i><span class="ml-2">สำเร็จ</span>',
                disabledBtnSave: false,
                disabledBtnCancle: false,
            },
            files:{
                index: '',
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
                method: ( this.$session.get('role') == 'admin.it.manager' ? 'waitappr' : 'progress' ),
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
        getMemoList(target){

            this.progress.show = true;
            this.button.search.disabled = true;
            this.button.redata.disabled = true;
            this.paginate.method = target;            
            this.paginate.show = false;

            axios.post('/api/admin/it/it-memo/list',{
                search: this.searchTxt,
                searchEmp: this.searchName,
                searchPstatus: this.searchPaper,
                searchObject: this.searchObject,
                target: this.paginate.method,
                paginate: this.paginate.list
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            });

        },
        getDataPaginate(page){

            if(
                ( page == 'prev' && ( this.paginate.prevPageUrl === null || this.paginate.prevPageUrl == ""  ) )
                || ( page == 'next' && ( this.paginate.nextPageUrl === null || this.paginate.nextPageUrl == ""  ) )
            )
                return;

            this.progress.show = true;
            this.button.search.disabled = true;
            this.button.redata.disabled = true;         
            this.paginate.show = false;

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post(this.paginate.url,{
                search: this.searchTxt,
                searchEmp: this.searchName,
                searchPstatus: this.searchPaper,
                searchObject: this.searchObject,
                target: this.paginate.method,
                paginate: this.paginate.list
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
            this.memo.tempData = resultData;

            if(this.paginate.method == 'waitappr') this.memo.waitappr = this.memo.tempData;
            else if(this.paginate.method == 'waitproc') this.memo.waitproc = this.memo.tempData;
            else if(this.paginate.method == 'progress') this.memo.progress = this.memo.tempData;
            else if(this.paginate.method == 'success') this.memo.success = this.memo.tempData;
            else if(this.paginate.method == 'void') this.memo.void = this.memo.tempData;
            else this.memo.all = this.memo.tempData;

            this.progress.show = false;
            this.button.search.disabled = false;
            this.button.redata.disabled = false;
            this.paginate.show = true;

        },
        getAdminMis(){
            axios.get('/api/admin/it/user/list/admin-it')
            .then(response => {
                this.mis = response.data;
                this.approve.inpAdmin = this.mis[0].user;
            });
        },
        getMemoDetail(index){
            axios.get('/api/admin/it/it-memo/detail/'+this.memo.tempData[index].id)
            .then(response => {
                this.approve.index = index;
                this.approve.memoId = this.memo.tempData[index].id;
                this.approve.inpRemark = '';
                this.memo.data.header = response.data.header;
                this.memo.data.detail = response.data.detail;
                this.memo.data.log = response.data.log;
                this.approve.contentBtnSave = '<i class="fas fa-check"></i><span class="ml-2">บันทึก</span>';
                this.approve.disabledBtnSave = false;
                this.approve.disabledBtnCancle = false;
                this.approve.disabledRemark = false;
                this.approve.disabledAdmin = false
                $('#modal-memo').modal('show');
            });
        },
        setApprove(take,bool){

            if(take){
                this.approve.status = bool ? 1 : 2;
                this.approve.takeAction = take;
                this.approve.isApprove = bool ? true : false;
                this.approve.isUnapprive = bool ? false : true;
            }
            else{
                this.approve.status = 0;
                this.approve.takeAction = take;
                this.approve.isApprove = bool;
                this.approve.isUnapprive = bool;
            }

        },
        saveApprove(){

            if(this.approve.status == 2 && this.approve.inpRemark == ''){
                this.$swal({icon: 'error', text: 'กรุณาใส่หมายเหตุ'});
                return;
            }

            let btnContent = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
                btnContent += 'กำลังบันทึกข้อมูล....'

            this.approve.contentBtnSave = btnContent;
            this.approve.disabledBtnSave = true;
            this.approve.disabledBtnCancle = true;
            this.approve.disabledRemark = true;
            this.approve.disabledAdmin = true;

            axios.post('/api/admin/it/it-memo/approve',{
                id: this.approve.memoId,
                no: this.memo.tempData[this.approve.index].no,
                status: this.approve.status,
                user: this.$session.get('user'),
                assign: this.approve.inpAdmin,
                remark: this.approve.inpRemark
            })
            .then(response => {
                if(response.data.status){
                    this.$swal({icon: 'success', text: 'บันทึกการอนุมัติเรียบร้อยแล้ว'});
                    this.approve.takeAction = false;
                    $('#modal-memo').modal('hide');
                    this.getMemoDetail(this.approve.index);
                    this.getMemoList(this.paginate.method);
                }
                else{
                    
                    this.approve.contentBtnSave = '<i class="fas fa-check"></i><span class="ml-2">บันทึก</span>';
                    this.approve.disabledBtnSave = false;
                    this.approve.disabledBtnCancle = false;
                    this.approve.disabledRemark = false;
                    this.approve.disabledAdmin = false

                    if(response.data.error == 'login.fail') this.neviLogin();
                    else this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกการอนุมัติ'});
                }
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกการอนุมัติ'});
                this.approve.contentBtnSave = '<i class="fas fa-check"></i><span class="ml-2">บันทึก</span>';
                this.approve.disabledBtnSave = false;
                this.approve.disabledBtnCancle = false;
                this.approve.disabledRemark = false;
                this.approve.disabledAdmin = false
            });

        },
        changeStatusMemo(sts){

            let btnContent = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
                btnContent += 'กำลังบันทึกข้อมูล....'

            this.complete.contentBtnSave = btnContent;
            this.complete.disabledBtnSave = true;
            this.complete.disabledBtnCancle = true;

            axios.post('/api/admin/it/it-memo/change-status',{
                id: this.memo.data.header.id,
                no: this.memo.data.header.no,
                status: sts,
                user: this.$session.get('user')
            })
            .then(response => {
                this.complete.contentBtnSave = '<i class="fas fa-check"></i><span class="ml-2">สำเร็จ</span>';
                this.complete.disabledBtnSave = false;
                this.complete.disabledBtnCancle = false;

                if(response.data.status){
                    this.$swal({icon: 'success', text: 'เรียบร้อยแล้ว'});
                    $('#modal-memo').modal('hide');
                    this.getMemoDetail(this.approve.index);
                    this.getMemoList(this.paginate.method);
                }
                else{
                    if(response.data.error == 'login.fail') this.neviLogin();
                    else this.$swal({icon: 'error', text: 'ไม่สามารถได้'});
                }
            })
            .catch(response => {
                this.complete.contentBtnSave = '<i class="fas fa-check"></i><span class="ml-2">สำเร็จ</span>';
                this.complete.disabledBtnSave = false;
                this.complete.disabledBtnCancle = false;
                this.$swal({icon: 'error', text: 'ไม่สามารถได้'});
            });

        },
        modalMemoFile(index){
            this.files.index = index;
            $('#modal-memo-file').modal('show');
            this.getMemoFiles();
        },
        getMemoFiles(){
            axios.get('/api/admin/it/it-memo/files/list/'+this.memo.tempData[this.files.index].id)
            .then(response => {this.memo.files = response.data});
        },
        onFileChange(e){
            this.files.file = e.target.files[0];
            this.files.file_name = this.files.file.name;
            this.files.upload.status = ''
            this.files.upload.classInptValid = false;
            this.files.upload.classInptInvalid = false;
            this.files.upload.inptDisabled = false;
            this.files.upload.btnUploadDisabled = false;
            $('#memoUploadFile').next('.custom-file-label').html(this.files.file_name);
        },
        uploadItmreqFile(e){

            if( this.files.file === null ){
                let sts_fail = '<i class="fas fa-exclamation-triangle text-danger mr-2"></i>';
                    sts_fail += '<strong class="text-danger">กรุณาเลือก File</strong>';
                this.files.upload.status = sts_fail;
                this.files.upload.classInptValid = false;
                this.files.upload.classInptInvalid = true;
                return;
            }
            else if( this.files.file.type != 'application/pdf' ){
                let sts_fail = '<i class="fas fa-exclamation-triangle text-danger mr-2"></i>';
                    sts_fail += '<strong class="text-danger">กรุณาเลือก File PDF เท่านั้น</strong>';
                this.files.upload.status = sts_fail;
                this.files.upload.classInptValid = false;
                this.files.upload.classInptInvalid = true;
                return;
            }

            this.files.upload.status = ''
            this.files.upload.classInptValid = false;
            this.files.upload.classInptInvalid = false;
            this.files.upload.inptDisabled = true;
            this.files.upload.btnUploadDisabled = true;
            this.files.upload.progress = true;

            e.preventDefault();

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            let formData = new FormData();
            formData.append('id',this.memo.tempData[this.files.index].id);
            formData.append('file_name',this.files.file_name);
            formData.append('file',this.files.file);

            axios.post('/api/admin/it/it-memo/files/upload',formData,config)
            .then(response => {

                if(response.data.status){
                    $('#memoUploadFile').next('.custom-file-label').html('Choose file');
                    this.files.file = null;
                    var sts = '<i class="fas fa-check-circle text-success mr-3"></i>';
                        sts += '<strong class="text-success">Upload file complete.</strong>';
                    this.files.upload.classInptValid = true;
                    this.files.upload.classInptInvalid = false;
                }
                else{
                    var sts = '<i class="fas fa-exclamation-triangle text-danger mr-3"></i>';
                        sts += '<strong class="text-danger">Upload file fail!</strong>';
                    this.files.upload.classInptValid = false;
                    this.files.upload.classInptInvalid = true;
                }


                this.files.upload.status = sts;
                this.files.upload.progress = false;
                this.files.upload.inptDisabled = false;
                this.files.upload.btnUploadDisabled = false;

                if(response.data.status) {
                    this.getMemoFiles();
                    this.getMemoList(this.paginate.method);
                }
                else
                    if(response.data.error = 'login.fail') 
                        this.neviLogin();
            })
            .catch(response => {
                //this.files.file == null;
                let sts_fl = '<i class="fas fa-exclamation-triangle text-danger mr-3"></i>';
                    sts_fl += '<strong class="text-danger">Upload file fail!</strong>';
                this.files.upload.status = sts_fl;
                this.files.upload.progress = false;
                this.files.upload.classInptValid = false;
                this.files.upload.classInptInvalid = true;
                this.files.upload.inptDisabled = false;
                this.files.upload.btnUploadDisabled = false;
            });

        },
        removeMemoFile(index){

            this.$swal({
                icon: 'warning',
                text: 'ยืนยันการลบไฟล์ '+this.memo.files[index].mm_file_name,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ลบไฟล์',
                denyButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if( result.isConfirmed ){
                    axios.post('/api/admin/it/it-memo/files/remove',{
                        file_id: this.memo.files[index].mm_file_id,
                        file_path: this.memo.files[index].mm_file_path
                    })
                    .then(response => {
                        if(response.data.status){
                            this.$swal({ icon: 'success', title: 'ลบไฟล์เรียบร้อยแล้ว' });
                            this.getMemoFiles();
                        }
                        else{
                            if(response.data.error == 'login.fail') this.neviLogin();
                            else this.$swal({ icon: 'error', title: 'ไม่สามารถลบไฟล์ได้' });
                        }
                    })
                    .catch(response => {
                        this.$swal({ icon: 'error', title: 'ไม่สามารถลบไฟล์ได้' });
                    });
                }
            });
        }
    }
}
</script>
