<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการยังไม่ได้อัพโหลดเอกสาร</span>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="col-auto">
                                <label>เลขที่เอกสาร</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกเลขที่เอกสาร..." aria-label="Search"
                                    v-model="searchTxt"
                                    v-on:keyup.enter="getDataPaginate('first')"
                                >
                            </div>
                            <div class="col-auto">
                                <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                    v-model="searchName"
                                    v-on:keyup.enter="getDataPaginate('first')"
                                >
                            </div>
                            <div class="col-auto">
                                <label>จุดประสงค์</label>
                                <select class="form-control form-control-sm"
                                v-model="searchObj"
                                v-on:keyup.enter="getDataPaginate('first')">
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
                            <div class="col-auto">
                                <label>สานะ</label>
                                <select class="form-control form-control-sm"
                                v-model="searchStatus"
                                v-on:keyup.enter="getDataPaginate('first')">
                                    <option value="99">ทั้งหมด</option>
                                    <option value="2">กำลังดำเนินการ</option>
                                    <option value="3">ดำเนินการเรียบร้อยแล้ว</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label>ปี/เดือน/วัน</label>
                                <div class="input-group input-group-sm">
                                    <input type="date" class="form-control"
                                    v-model='searchDateFrom'>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">ถึง</span>
                                    </div>
                                    <input type="date" class="form-control"
                                    v-model='searchDateTo'>
                                </div>
                            </div>
                            <div class="col-auto my-4">
                                <button class="btn btn-sm btn-amado my-2" type="button"
                                    v-on:click="getDataPaginate('first')"
                                >
                                    <i class="fas fa-search"></i>
                                    <span class="ml-2">ค้นหา</span>
                                </button>
                                <button class="btn btn-sm btn-primary ml-1" type="button"
                                    v-on:click="getDataPaginate('first')"
                                >
                                    <i class="fas fa-sync-alt mr-2"></i> ดึงข้อมูลใหม่
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center mt-2" v-show="table.progress">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                        </div>
                    </div>
                    <div class="col-md-12" v-show="table.show">
                        <table class="table table-hover table-sm">
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
                                    <th scope="col">สถานะเอกสาร</th>
                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="list in jbr" :key="list.jobreq_id">
                                    <th scope="row">{{list.index}}</th>
                                    <td>{{list.jobreq_no}}</td>
                                    <td>{{list.jobreq_date}}</td>
                                    <td>{{list.it_emp_name_th}} {{list.it_emp_surname_th}} ({{list.it_emp_nickname_th}})</td>
                                    <td>{{list.it_dept_name}}</td>
                                    <td>{{list.it_emp_position}}</td>
                                    <td>{{list.jobreq_obj_str}}</td>
                                    <td>{{list.jobreq_status_str}}</td>
                                    <td>{{list.sfiles}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                            v-on:click="getDatajbrDetail(list.jobreq_id)"
                                        >
                                            <i class="fas fa-file-alt"></i>
                                            <span class="ml-2">รายละเอียด</span>
                                        </button>
                                        <a class="btn btn-success btn-sm mb-1"
                                            :href="'/pdf/jobrequest/'+list.jobreq_id" :target="'_blank'"
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
                                <tr v-show="jbr.length <= 0"><td class="text-center" colspan="10"><b>ไม่มีข้อมูล</b></td></tr>
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

        <!-- Modal : #modal-jobreq-file -->
        <div class="modal fade" id="modal-jobreq-file" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary">
                            <i class="fas fa-folder-open mr-2"></i> <span>เอกสาร Job Request</span>
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
                            <i class="fas fa-times mr-2"></i>ออก
                        </button>
                    </div>
                </div>
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

                                    <span v-if="detail.jbr_status == 1" class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"></span>
                                    <span v-if="detail.jbr_status == 1" class="ml-2">รอดำเนินการ</span>

                                    <i v-if="detail.jbr_status == 2" class="fas fa-check text-warning"></i>
                                    <span v-if="detail.jbr_status == 2" class="ml-2">กำลังดำเนินการ</span>

                                    <i v-if="detail.jbr_status == 3" class="fas fa-list-ul text-success"></i>
                                    <span v-if="detail.jbr_status == 3" class="ml-2">ดำเนินการเรียบร้อย</span>

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
                                <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>JOB REQUEST FORM</b></p>
                                <p><b>AMADO GROUP</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">เลขที่เอกสาร</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span v-html="detail.jbr_no"><!--JR-003--></span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span v-html="detail.jbr_date"><!--19/11/2563--></span>
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
                                        <span class="ml-5" v-html="detail.jbr_fullnameTh"><!--นาย พงศกร เหล่านิยมไทย--></span>
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
                                        <span class="ml-5" v-html="detail.jbr_fullnameEng"><!--Mr. Pongsakorn Laoniyomthai--></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">แผนก</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="detail.jbr_deptName"><!--IT--></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="detail.jbr_posit"><!--Programmer--></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">เบอร์โทรศัพท์</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="detail.jbr_tel"><!--083-410-1045--></span>
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
                                                v-if="detail.jbr_obj==1" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_1">สร้างผู้ใช้ใหม่</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_2" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj==2" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_2">แก้ไขผู้ใช้งาน</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_3" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj==3" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_3">ลบผู้ใช้งาน</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_4" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj==4" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_4">พัฒนาใหม่</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_5" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj==5" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_5">ซื้อ/ติดตั้งอุปกรณ์</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_6" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj==6" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_6">สร้างอีเมลใหม่</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_7" name="optobj" value="" disabled
                                                v-if="detail.jbr_obj==7" :checked="true"
                                            >
                                            <label class="custom-control-label text-black" for="opt_7">อื่น ๆ : </label>
                                            <p class="ml-2" v-html="detail.jbr_other"><!--Lorem ipsum dolor sit amet consectetur, adipisicing elit.--></p>
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
                                                <tr v-for="(detail, index) in detail.jbr_detail" :key="index">
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
                                        <p v-html="detail.jbr_demand"><!--Lorem ipsum dolor sit amet consectetur adipisicing elit.--></p>
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
                                        <p v-html="detail.jbr_reason"><!--Lorem ipsum dolor sit amet consectetur adipisicing elit.--></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end m-0">
                            <div class="col-md-4 text-center">
                                <p class="mb-2">ผู้แจ้งขอดำเนินการ</p>
                                <p v-html="detail.jbr_fullnameTh"><!--นาย พงศกร เหล่านิยมไทย--></p>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row justify-content-center py-3" v-if="detail.jbr_status == 2">
                            <div class="col-md-5">
                                <div class="alert alert-warning text-center" role="alert">
                                    <div class="spinner-border spinner-border-sm text-dark mr-2"></div>กำลังดำเนินการ
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center py-3" v-if="detail.jbr_status == 3">
                            <div class="col-md-5">
                                <div class="alert alert-success text-center" role="alert">
                                    <i class="fas fa-check-circle"></i> <strong>ดำเนินการเรียบร้อย</strong>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center py-3" v-if="detail.jbr_status == 0">
                            <div class="col-md-5">
                                <div class="alert alert-danger text-center" role="alert">
                                    <i class="fas fa-ban"></i> <strong>ยกเลิก</strong>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-3">
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
                            <div class="col-6">
                                <i class="fas fa-tag mr-1"></i>
                                <label>หมายเหตุ</label>
                            </div>
                            <div class="col-6 text-right">
                            </div>
                            <div class="col-12">
                                <div class="from-group">
                                    <textarea class="form-control" rows="5" readonly
                                        v-model="detail.jbr_remark"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                        <hr class="pb-3">
                        <div class="row">
                            <div class="col-12">
                                <label><i class="fas fa-user-tag"></i> ผู้รับผิดชอบ</label>
                                <strong class="text-primary" v-html="detail.jbr_log.assign.emp_name">ไม่มีข้อรับผิดชอบ</strong>
                                <strong class="text-primary">(<font v-html="detail.jbr_log.assign.jobreq_assign"></font>)</strong>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label>Log การทำรายการ</label>
                                <ul class="list-group">
                                    <li class="list-group-item py-1" v-for="(list,index) in detail.jbr_log.list" :key="index">
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
                            <div class="col-md-auto">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">
                                    <i class="fas fa-times mr-2"></i>Close
                                </button>
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

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/th';

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    mounted(){
        // let date = new Date();
        // this.searchDate = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2);
        this.getDataJbr();
    },
    data(){
        return{
            searchTxt: '',
            searchName: '',
            searchObj: '',
            searchPaper: '',
            searchDateFrom: '',
            searchDateTo: '',
            searchStatus: 3,
            table: {
                show: true,
                progress: false,
            },
            jbr: [],
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
                jbr_file: [],
                jbr_log: {
                    assign: '',
                    list: []
                }
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
        getJobreFile(jobreqId,status){

            this.paper.jobreq_id = jobreqId;
            this.paper.upload.progress = false;
            this.paper.upload.status = ''
            this.paper.upload.classInptValid = false;
            this.paper.upload.classInptInvalid = false;
            this.paper.upload.inptDisabled = false;
            this.paper.upload.btnUploadDisabled = false;

            axios.get('/api/admin/it/jobrequest/file/'+this.paper.jobreq_id)
            .then(response => {
                this.paper.list = response.data;
                this.paper.list.jobreq_status = status;
                $('#modal-jobreq-file').modal('show');
            });

        },
        getDatajbrDetail(jbrId, savestatus){
            axios.get('/api/admin/it/jobrequest/detail/'+jbrId)
            .then(response => {

                var resHeader = response.data.header,
                    resDetail = response.data.detail,
                    resFiles = response.data.files,
                    resLog = response.data.log;

                this.detail.jbr_id = jbrId;
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

                if( resLog.admin !== null )
                    this.detail.jbr_log.assign = resLog.admin;
                else
                    this.detail.jbr_log.assign = { emp_name: 'ยังไม่ได้ Assign', jobreq_assign: '' };

                this.detail.jbr_log.list = resLog.list;

                if (savestatus == true)
                    switch(this.detail.jbr_status) {
                        case 0: this.$swal({
                            icon: 'error',
                            title: '<p class="m-0">เปลี่ยนสถานะเรียบร้อย</p>',
                            html: '<h4>สถานะ <i class="fas fa-arrow-right mx-2"></i><span class="badge badge-danger px-3 py-2">ยกเลิก</span></h4>',
                            footer: 'เลขที่เอกสาร : '+this.detail.jbr_no
                        }); break;
                        case 2: this.$swal({
                            icon: 'success',
                            title: '<p class="m-0">เปลี่ยนสถานะเรียบร้อย</p>',
                            html: '<h4>สถานะ <i class="fas fa-arrow-right mx-2"></i><span class="badge badge-warning px-3 py-2">กำลังดำเนินการ</span></h4>',
                            footer: 'เลขที่เอกสาร : '+this.detail.jbr_no
                        }); break;
                        case 3: this.$swal({
                            icon: 'success',
                            title: '<p class="m-0">เปลี่ยนสถานะเรียบร้อย</p>',
                            html: '<h4>สถานะ <i class="fas fa-arrow-right mx-2"></i><span class="badge badge-success px-3 py-2">ดำเนินการเรียบร้อย</span></h4>',
                            footer: 'เลขที่เอกสาร : '+this.detail.jbr_no
                        }); break;
                    }

                else
                    $('#modal-jobreq').modal('show');

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

            if(!this.checkSeessionLogin())
                return;

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

            axios.post('/api/admin/it/jobrequest/file/upload',formData,config)
            .then(response => {
                $('#jobreqUploadFile').next('.custom-file-label').html('Choose file');
                this.paper.file = null;
                let sts_sc = '<i class="fas fa-check-circle text-success mr-3"></i>';
                    sts_sc += '<strong class="text-success">Upload file complete.</strong>';
                this.paper.upload.status = sts_sc;
                this.paper.upload.progress = false;
                this.paper.upload.classInptValid = true;
                this.paper.upload.classInptInvalid = false;
                this.paper.upload.inptDisabled = false;
                this.paper.upload.btnUploadDisabled = false;
                this.getJobreFile(this.paper.jobreq_id,this.paper.list.jobreq_status);
                this.getDataPaginate(this.paginate.currentPage);
            })
            .catch(response => {
                this.paper.file = null;
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

            if(!this.checkSeessionLogin())
                return;

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
                    axios.post('/api/admin/it/jobrequest/file/remove',{
                        file_id: this.paper.list[index].jobreq_file_id,
                        file_path: this.paper.list[index].jobreq_file_path
                    })
                    .then(response => {
                        this.$swal({
                            icon: 'success',
                            title: 'ลบไฟล์เรียบร้อยแล้ว'
                        });
                        this.getJobreFile(this.paper.jobreq_id,this.paper.list.jobreq_status);
                        this.getDataPaginate(this.paginate.currentPage);
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
        getDataJbr(){

            this.searchTxt = '';
            this.table.show = false;
            this.table.progress = true;

            axios.post('/api/admin/it/jobrequest/list/non-upload-file',{
                search: this.searchTxt,
                searchEmp: this.searchName,
                searchObj: this.searchObj,
                searchDateFrom: this.searchDateFrom,
                searchDateTo: this.searchDateTo,
                searchStatus: this.searchStatus,
                paginate: this.paginate.list
            })
            .then(response => {
                this.setDataAndPaginate(response.data,'first');
            });

        },
        getDataPaginate(page){

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post(this.paginate.url,{
                search: this.searchTxt,
                searchEmp: this.searchName,
                searchObj: this.searchObj,
                searchDateFrom: this.searchDateFrom,
                searchDateTo: this.searchDateTo,
                searchStatus: this.searchStatus,
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
            this.paginate.show = true;
            this.table.show = true;
            this.table.progress = false;
            this.jbr = resultData;

        }

    }
}
</script>
