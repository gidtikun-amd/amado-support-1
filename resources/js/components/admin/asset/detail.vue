<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-file-alt"></i><span class="ml-2">รายละเอียดข้อมูลทรัพย์สิน</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 py-3"><h4>รหัสทรัพย์สิน : <span class="text-amado"
                        v-html="html.str.assetNumberHeader"
                    >COM000921</span></h4></div>
                    <!--<div class="col-6">
                        <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modal-historyasset">
                            <i class="fas fa-history"></i>
                            <span class="ml-2">ประวัติการทำรายการทรัพย์สิน</span>
                        </button>
                    </div>-->
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6"><h5>รายละเอียดทรัพย์สิน</h5></div>
                            <div class="col-6">
                                <button type="button" class="btn btn-sm btn-warning float-right"
                                    v-on:click="setShowAssetData('edit')"
                                >
                                    <i class="fas fa-pen"></i> <span class="ml-2">แก้ไขข้อมูลทรัพย์สิน</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger float-right mr-2"
                                    v-on:click="setShowAssetData('show')"
                                    v-show="html.btn.btnAssetSetCancelShow"
                                    :disabled="html.btn.btnAssetSetCancelDisabled"
                                >
                                    <i class="fas fa-ban"></i> <span class="ml-2">ยกเลิก</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-success float-right mr-2"
                                    v-on:click="setAssetEdit()" 
                                    v-html="html.btn.btnAssetSetUpdateTxt"
                                    v-show="html.btn.btnAssetSetUpdateShow"                                    
                                    :disabled="html.btn.btnAssetSetUpdateDisabled"
                                >
                                    <i class="fas fa-save"></i> <span class="ml-2">บันทึก</span>
                                </button>
                            </div>
                        </div>
                        <div class="row mb-3 mx-3">
                            <div class="col-12">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">ชื่อทรัพย์สิน :</span>
                                    <span
                                        v-html="html.str.assetName"
                                        v-show="html.str.showAssetName"
                                    ></span>
                                    <input type="text" class="form-control form-control-sm w-50" value=""
                                        v-model="html.inpt.assetName"
                                        v-show="html.inpt.showAssetName"
                                        v-bind:class="{'is-invalid':html.inpt.assetNameInvalid}"
                                    >
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">สถานะ :</span>
                                    <span
                                        v-html="html.str.assetStatus"
                                        v-show="html.str.showAssetStatus"
                                    ><i class="fas fa-circle mr-2 text-success"></i>ใช้งาน</span>
                                    <select class="form-control form-control-sm"
                                        v-model="html.inpt.assetStatus"
                                        v-show="html.inpt.showAssetStatus"
                                    >
                                        <option value="" disabled>กรุณาเลือก...</option>
                                        <option value="1" selected>ใช้งาน</option>
                                        <option value="2">สำรอง</option>
                                        <option value="3">ส่งซ่อม</option>
                                        <option value="4">ออกจำหน่าย</option>
                                        <option value="5">ยืมใช้งาน</option>
                                        <option value="6">ไม่ใช้งาน</option>
                                    </select>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">ประเภททรัพย์สิน :</span>
                                    <span
                                        v-html="html.str.assetType"
                                        v-show="html.str.showAssetType"                                    
                                    ></span>
                                    <select class="form-control form-control-sm"
                                        v-model="html.inpt.assetType"
                                        v-show="html.inpt.showAssetType"
                                    >
                                        <option value="" disabled select>กรุณาเลือก...</option>
                                        <option v-for="asstt in data.assettype" :key="asstt.it_hw_type_id" :value="asstt.it_hw_type_id" >
                                            {{asstt.it_hw_type_name}}
                                        </option>
                                    </select>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">ประเภทถือครอง :</span>
                                    <span
                                        v-html="html.str.assetGroup"
                                        v-show="html.str.showAssetGroup"   
                                    ></span>
                                    <select class="form-control form-control-sm"
                                        v-model="html.inpt.assetGroup"
                                        v-show="html.inpt.showAssetGroup"
                                    >
                                        <option value="" disabled>กรุณาเลือก...</option>
                                        <option value="1" selected>ทรัพย์สินบุคคลถือครอง</option>
                                        <option value="2">ทรัพย์สินส่วนกลางฝ่าย</option>
                                    </select>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">Serial Number :</span>
                                    <span
                                        v-html="html.str.assetSerial"
                                        v-show="html.str.showAssetSerial"
                                    ></span>
                                    <input type="text" class="form-control form-control-sm" value=""
                                        v-model="html.inpt.assetSerial"
                                        v-show="html.inpt.showAssetSerial"
                                    >
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">ราคา :</span>
                                    <span
                                        v-html="html.str.assetPrice"
                                        v-show="html.str.showAssetPrice"
                                    ></span>
                                    <input type="number" class="form-control form-control-sm" value=""
                                        v-model="html.inpt.assetPrice"
                                        v-show="html.inpt.showAssetPrice"
                                        v-bind:class="{'is-invalid':html.inpt.assetPriceInvalid}"
                                    >
                                    <span class="ml-1">บาท</span>
                                </p>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">Expired Date :</span>
                                    <span
                                        v-html="html.str.assetExpire"
                                        v-show="html.str.showAssetExpire"
                                    ></span>
                                    <input type="text" class="form-control form-control-sm"
                                    placeholder="yyyy-mm-dd"
                                        v-model="html.inpt.assetExpire"
                                        v-show="html.inpt.showAssetExpire"
                                    >
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">Warrantry :</span>
                                    <span
                                        v-html="html.str.assetWarrantry"
                                        v-show="html.str.showAssetWarrantry"
                                    ></span>
                                    <input type="text" class="form-control form-control-sm"
                                    placeholder=""
                                        v-model="html.inpt.assetWarrantry"
                                        v-show="html.inpt.showAssetWarrantry"
                                    >
                                </p>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">วันที่เริ่มใช้งาน :</span>
                                    <span
                                        v-html="html.str.assetSdate"
                                        v-show="html.str.showAssetSdate"
                                    ></span>
                                    <input type="text" class="form-control form-control-sm"
                                    placeholder="yyyy-mm-dd"
                                        v-model="html.inpt.assetSdate"
                                        v-show="html.inpt.showAssetSdate"
                                    >
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">วันที่หมดอายุ :</span>
                                    <span
                                        v-html="html.str.assetEdate"
                                        v-show="html.str.showAssetEdate"
                                    ></span>
                                    <input type="text" class="form-control form-control-sm"
                                    placeholder="yyyy-mm-dd"
                                        v-model="html.inpt.assetEdate"
                                        v-show="html.inpt.showAssetEdate"
                                    >
                                </p>
                            </div>
                            <div class="col-12">
                                <p class="mb-1">
                                    <span class="mr-2">หมายเหตุ :</span>
                                    <span
                                        v-html="html.str.assetRemark"
                                        v-show="html.str.showAssetRemark"
                                    ></span>
                                    <textarea class="form-control form-control-sm" rows="5" placeholder="กรอกหมายเหตุ..."
                                        v-model="html.inpt.assetRemark"
                                        v-show="html.inpt.showAssetRemark"
                                    >
                                    </textarea>
                                </p>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-12"><h5>ไฟล์ที่แนบไว้</h5></div>
                        </div>
                        <div class="row mb-3 mx-3">
                            <div class="col-12 pt-1">
                                <div class="form-group col-md-12 px-0">
                                    <label>Upload ไฟล์ : </label> <label v-html="html.upload.uploadStatus"></label>
                                    <form enctype="multipart/form-data">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="assetUploadFile" aria-describedby="btnAssetUploadFile" accept=".pdf"
                                                    v-bind:class="{'is-valid': html.upload.inputUploadClassValid, 'is-invalid': html.upload.inputUploadClassInvalid}"
                                                    v-on:change="onFileChange"
                                                    :disabled="html.upload.inputUploadDisabled"
                                                >
                                                <label class="custom-file-label" for="assetUploadFile" >Choose file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button @click.prevent="uploadAssetFile" type="submit" class="btn btn-outline-secondary" id="btnAssetUploadFile"
                                                    :disabled="html.upload.btnUploadDisabled"
                                                >
                                                    <i class="fas fa-cloud-upload-alt"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="progress my-3"
                                        v-show="html.upload.showProgress"
                                    >
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" 
                                        aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 p-0">
                                    <ul class="list-group">
                                        <li class="list-group-item py-1" v-for="(file, index) in data.assetfile" :key="index">
                                            <h5 class="d-inline mr-3">#{{index+1}}</h5><span>Url : </span>
                                            <span class="badge badge-light">
                                                <a class="text-decoration-none" 
                                                v-bind:href="file.it_hw_file_path" v-bind:target="'_blank'">{{file.it_hw_file_name}}</a>
                                            </span>
                                            <a href="javascript:;" class="float-right"
                                                v-on:click="setRemoveAssetFile(index)"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6"><h5>ประวัติผู้ถือครองทรัพย์สิน</h5></div>
                            <div class="col-6">
                                <button type="button" class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#modal-holdasset-add">
                                    <i class="fas fa-plus"></i> <span class="ml-2">เพิ่มผู้ถือครองทรัพย์สิน</span>
                                </button>
                            </div>
                        </div>
                        <div class="row mb-3 pt-1">
                            <div class="col-12">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">ผู้ถือครองทรัพย์สิน</th>
                                            <th scope="col">ฝ่าย</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">วันที่เริ่มต้นใช้งาน</th>
                                            <th scope="col">วันที่สิ้นสุดใช้งาน</th>
                                            <th scope="col">สถานะถือครอง</th>
                                            <th scope="col">Remark</th>
                                            <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(handle, index) in data.assethandle" :key="index">
                                            <th class="align-middle" scope="row">{{index+1}}</th>
                                            <td class="align-middle">{{handle.it_emp_name_th}}</td>
                                            <td class="align-middle">{{handle.it_dept_name}}</td>
                                            <td class="align-middle">
                                                <span>{{handle.it_locat_name}}</span> 
                                            </td>
                                            <td class="align-middle">
                                                <span>{{handle.it_asst_hw_start_date}}</span> 
                                            </td>
                                            <td class="align-middle">
                                                <span>{{(handle.it_asst_hw_end_date!='0000-00-00'?handle.it_asst_hw_end_date:'')}}</span> 
                                            </td>
                                            <td class="align-middle">
                                                <span v-if="handle.it_asst_hw_status == 1" 
                                                class="badge badge-pill badge-tbl-custom badge-success">ถือครอง</span>
                                                <span v-else class="badge badge-pill badge-tbl-custom badge-danger">สิ้นสุด</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="d-inline-block text-truncate" style="max-width: 80px;">{{handle.it_asst_hw_remark}}</span> 
                                            </td>
                                            <td class="text-center">                                                
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    v-on:click="getHandlePaper(handle.it_asst_hw_seq,handle.it_emp_id)"
                                                ><i class="fas fa-file-pdf"></i></button>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    v-on:click="getHandleEdit(index)"
                                                ><i class="fas fa-edit"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    v-on:click="getHandleDelete(index)"
                                                ><i class="fas fa-minus"></i></button>
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

        <!-- Modal : #modal-holdasset-edit -->
        <div class="modal fade" id="modal-holdasset-edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <i class="fas fa-pen text-warning"></i> 
                            <span class="ml-2" v-html="html.handle.title">แก้ไขประวัติผู้ถือครองทรัพย์สิน</span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            :disabled="html.handle.btnTitleCloseDisabled"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"
                            v-show="html.handle.alertError"
                        >
                            <i class="fas fa-exclamation-circle"></i> <strong>ไม่สามารถแก้ไขประวัติผู้ถือครองทรัพย์สินได้!!!</strong> 
                            <span v-show="html.handle.alertErrorMsg != ''"><br><b>[Error]</b> {{html.handle.alertErrorMsg}}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form>
                            <div class="row m-3">
                                <div class="form-group col-md-7">
                                    <label for="uname">ผู้ถือครองทรัพย์สิน : </label>
                                    <input type="text" class="form-control" readonly
                                        v-model="html.handle.inptEmpFullName"
                                    >
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="uname">ฝ่าย : </label>
                                    <input type="text" class="form-control" readonly
                                        v-model="html.handle.inptDeptName"
                                    >
                                </div>
                            </div>
                            <hr class="pb-3">
                            <div class="row m-3">
                                <div class="form-group col-md-7">
                                    <label for="uname">สถานะทรัพย์สิน : </label>
                                    <select class="form-control" v-model="html.handle.inptHwStatus">
                                        <option value="" disabled>กรุณาเลือก...</option>
                                        <option value="1" selected>ใช้งาน</option>
                                        <option value="2">สำรอง</option>
                                        <option value="3">ส่งซ่อม</option>
                                        <option value="4">ออกจำหน่าย</option>
                                        <option value="5">ยืมใช้งาน</option>
                                        <option value="6">ไม่ใช้งาน</option>
                                    </select>
                                </div>
                            </div>
                            <hr class="pb-3">
                            <div class="row m-3">
                                <div class="form-group col-md-7">
                                    <label for="uname">Location : </label>
                                    <select class="form-control" 
                                        v-model="html.handle.inptLocatId"
                                        v-bind:class="{'is-invalid':html.handle.validLocatId}"
                                    >
                                        <option value="" disabled selected>กรุณาเลือก...</option>
                                        <option v-for="loc in data.locations" :key="loc.it_locat_id" :value="loc.it_locat_id">
                                            {{loc.it_locat_name}}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือก Location...!</div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="uname">สถานะถือครอง	 : </label>
                                    <select class="form-control" 
                                        v-model="html.handle.inptHandleStatus"
                                        v-bind:class="{'is-invalid':html.handle.validHandleStatus}"
                                        :disabled="html.handle.inptHandleStatus == 2"
                                    >
                                        <option value="" disabled selected>กรุณาเลือก...</option>
                                        <option value="1">ถือครอง</option>
                                        <option value="2">สิ้นสุด</option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือก สถานะถือครอง...!</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="uname">วันที่เริ่มต้นใช้งาน : </label>
                                    <date-picker value-type="format" format="YYYY-MM-DD"
                                        v-model="html.handle.inptHandleStartDate"
                                        v-bind:class="{'is-invalid':html.handle.validHandleStartDate}"
                                        :disabled="html.handle.inptHandleStatus == 2"
                                    ></date-picker>
                                    <div class="invalid-feedback">กรุณาใส่ วันที่เริ่มต้นใช้งาน...!</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="uname">วันที่สิ้นสุดใช้งาน : </label>
                                    <date-picker value-type="format" format="YYYY-MM-DD" :disabled="true"
                                        v-model="html.handle.inptHandleEndDate"
                                    ></date-picker>
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="uname">Remark : </label>
                                    <textarea class="form-control" rows="3"
                                        v-model="html.handle.inptHandleRemark"
                                    ></textarea>
                                </div>
                            </div>
                            <div class="row justify-content-md-center m-3">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-success w-100" 
                                        v-on:click="setHandleEdit()"
                                        v-html="html.handle.btnSaveTxt"
                                        :disabled="html.handle.btnSaveDisabled"
                                    >
                                        <i class="fas fa-save"></i> บันทึก
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100" data-dismiss="modal" aria-label="Close"
                                        :disabled="html.handle.btnCloseDisabled"
                                    >
                                        <i class="fas fa-ban"></i> ยกเลิก
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-holdasset-add -->
        <div class="modal fade" id="modal-holdasset-add"  data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <i class="fas fa-plus text-success"></i>
                            <span class="ml-2">เพิ่มผู้ถือครองทรัพย์สิน</span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal"
                            :disabled="html.handle.btnAddTTCloseDisabled"
                        >&times;</button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row m-3 pb-3">
                            <div class="col-6">
                                <div class="form-inline">
                                    <input class="form-control mr-sm-2" type="search" placeholder="ค้นหาพนักงาน..." aria-label="Search"
                                        v-model="html.handle.searchEmpTxt"
                                        v-on:keyup.enter="searchEmployees()"
                                    >
                                    <button class="btn  btn-amado my-2 my-sm-0" type="button"
                                        v-on:click="searchEmployees()"
                                    >
                                        <i class="fas fa-search"></i>
                                        <span class="ml-2">ค้นหา</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row m-3">
                            <div class="col-md-12">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">รหัสพนักงาน</th>
                                            <th scope="col">ชื่อ</th>
                                            <th scope="col">นามสกุล</th>
                                            <th scope="col">ชื่อเล่น</th>
                                            <th scope="col">ฝ่าย</th>
                                            <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(emp, index) in data.employees" :key="index">
                                            <th scope="row">{{emp.it_emp_id}}</th>
                                            <td>{{emp.it_emp_name_th}}</td>
                                            <td>{{emp.it_emp_surname_th}}</td>
                                            <td>{{emp.it_emp_nickname_th}}</td>
                                            <td>{{emp.it_dept_name}}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-success btn-sm"
                                                    v-on:click="selectEmployee(index)"
                                                ><i class="fas fa-plus"></i> เลือก</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr class="pb-3">
                        <div class="row justify-content-md-center m-3">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal"
                                    v-html="html.handle.btnAddCloseTxt"
                                    :disabled="html.handle.btnAddCloseDisabled"
                                ><i class="fas fa-ban"></i> ยกเลิก</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-remove-file -->
        <div class="modal fade" id="modal-remove-file" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <i class="fas fa-clipboard-check"> ยืนยันการลบข้อมูล ไฟล์ที่แนบไว้</i>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            :disabled="removeFile.btnCloseTTDisabled"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-html="removeFile.modelContent">
                    </div>
                    <div class="modal-footer">            
                        <button type="button" class="btn btn-primary"
                            v-html="removeFile.btnConfirmTxt"
                            v-on:click="removeAssetFile()"
                            :disabled="removeFile.btnConfirmDisabled"
                        >
                            <i class="fas fa-trash"></i> ยืนยัน
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                            :disabled="removeFile.btnCloseDisabled"
                        >
                            <i class="fas fa-ban"></i> ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-remove-handle -->
        <div class="modal fade" id="modal-remove-handle" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <i class="fas fa-clipboard-check"> ยืนยันการลบข้อมูล ประวัติผู้ถือครองทรัพย์สิน</i>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            :disabled="html.handle.btnCloseTTDisabled"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-html="html.handle.modelContent">
                    </div>
                    <div class="modal-footer">            
                        <button type="button" class="btn btn-primary"
                            v-html="html.handle.btnConfirmTxt"
                            v-on:click="removeAssetHandle()"
                            :disabled="html.handle.btnConfirmDisabled"
                        >
                            <i class="fas fa-trash"></i> ยืนยัน
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                            :disabled="html.handle.btnCloseDisabled"
                        >
                            <i class="fas fa-ban"></i> ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-holdasset-file -->
        <div class="modal fade" id="modal-holdasset-file"  data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <i class="fas fa-file-pdf text-primary"></i> <span class="ml-2">เอกสารประวัติผู้ถือครองทรัพย์สิน</span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row m-3 pb-3">
                            <div class="col-12">
                                <ul class="list-group">                                    
                                    <li class="list-group-item py-1">
                                        ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน : 
                                        <a class="text-decoration-none" 
                                            :href="'../../pdf/asset/handle/'+upload.handle_id" 
                                            :target="'_blank'"
                                            v-show="html.str.showPaperHandleNoPrint"
                                        > 
                                            <i class="fas fa-print mr-2 ml-2"></i> 
                                            <strong class="text-primary" v-html="html.str.paperHandleNo"></strong>
                                        </a>
                                        <a class="text-decoration-none" href="javascript:;" 
                                            v-show="html.str.showPaperHandleNoCreate"
                                            v-on:click="createHandleFile()"
                                        > 
                                            <i class="fas fa-file-medical mr-2 ml-2"></i> 
                                            <strong class="text-primary">สร้างเอกสาร</strong>
                                        </a>
                                    </li>                                   
                                    <li class="list-group-item py-1">
                                        ใบยืม - คืนอุปกรณ์คอมพิวเตอร : 
                                        <a class="text-decoration-none" 
                                            :href="'../../pdf/asset/borrow/'+upload.handle_id" 
                                            :target="'_blank'"
                                            v-show="html.str.showPaperBorrowNoPrint"
                                        > 
                                            <i class="fas fa-print mr-2 ml-2"></i> 
                                            <strong class="text-primary" v-html="html.str.paperBorrowNo"></strong>
                                        </a>
                                        <a class="text-decoration-none" href="javascript:;" 
                                            v-show="html.str.showPaperBorrowNoCreate"
                                            v-on:click="createBorrowFile()"
                                        > 
                                            <i class="fas fa-file-medical mr-2 ml-2"></i> 
                                            <strong class="text-primary">สร้างเอกสาร</strong>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row m-3 pb-3">
                            <div class="col-12">
                                <form enctype="multipart/form-data">
                                    <div class='form-group'>
                                        <label>Upload ไฟล์ : </label> <label v-html="html.upload.uploadHandleStatus"></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="handleUploadFile" aria-describedby="btnHandleUploadFile" accept=".pdf"
                                                    v-on:change="onFileHandleChange"
                                                    v-bind:class="{
                                                        'is-valid': html.upload.inputHandleClassValid,
                                                        'is-invalid': html.upload.inputHandleClassInvalid
                                                    }"
                                                    :disabled="html.upload.inputHandleDisabled"
                                                >
                                                <label class="custom-file-label" for="handleUploadFile" >Choose file</label>
                                            </div>
                                            <select class="custom-select"
                                                v-model="upload.handle_type"
                                            >
                                                <option value="1">ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน</option>
                                                <option value="2">ใบยืม - คืนอุปกรณ์คอมพิวเตอร์</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-secondary" id="btnHandleUploadFile"
                                                    @click.prevent="uploadHandleFile" 
                                                    :disabled="html.upload.btnUploadHandleDisabled"
                                                >
                                                    <i class="fas fa-cloud-upload-alt"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="progress my-3" v-show="html.upload.showHandleProgress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" 
                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>รายการเอกสาร : </label>
                                <ul class="list-group">
                                    <li class="list-group-item py-1" 
                                        v-for="(hdf,index) in data.handlepaper" :key="index"
                                    >
                                        <h5 class="d-inline mr-3">#{{index+1}}</h5>
                                        <span class="mr-3 text-primary"
                                            v-show="hdf.assthd_file_group == 1"
                                        >ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน</span>
                                        <span class="mr-3 text-primary"
                                            v-show="hdf.assthd_file_group == 2"
                                        >ใบยืม - คืนอุปกรณ์คอมพิวเตอร์</span>
                                        <span>Url : </span>
                                        <span class="badge badge-light">
                                            <a class="text-decoration-none" 
                                            v-bind:href="hdf.assthd_path" v-bind:target="'_blank'">{{hdf.assthd_file}}</a>
                                        </span>
                                        <a href="javascript:;" class="float-right"
                                            v-on:click="removeHandleFile(index)"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="pb-3">
                        <div class="row justify-content-md-center m-3">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal"
                                    v-html="html.handle.btnAddCloseTxt"
                                    :disabled="html.handle.btnAddCloseDisabled"
                                ><i class="fas fa-ban"></i> ยกเลิก</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-progress -->
        <div class="modal fade" id="modal-progress" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <i class="fab fa-windows"> System message</i>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-show="html.progress.btnCloseShow"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center" v-html="html.progress.content">
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/th';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    props:['id'],
    components:{
        DatePicker 
    },
    mounted(){
        this.getMasterAssettype();
        this.getMasterLocation();
        this.getAssetHere();
        this.getAssetFile();
        this.getAssetHandle();
    },
    data(){
        return{
            html: {
                str: {
                    assetNumberHeader: '', 
                    assetNumber: '', showAssetNumber: false,
                    assetName: '', showAssetName: false,
                    assetStatus: '', showAssetStatus: false,
                    assetType: '', showAssetType: false,
                    assetGroup: '', showAssetGroup: false,
                    assetSerial: '', showAssetSerial: false,
                    assetPrice: '', showAssetPrice: false,
                    assetRemark: '', showAssetRemark: false,
                    paperHandleNo: '', paperBorrowNo: '',
                    assetExpire: '', showAssetExpire: true,
                    assetWarrantry: '', showAssetWarrantry: true,
                    assetSdate: '', showAssetSdate: true,
                    assetEdate: '', showAssetEdate: true,
                    showPaperHandleNoCreate: false,
                    showPaperBorrowNoCreate: false,
                    showPaperHandleNoPrint: false,
                    showPaperBorrowNoPrint: false
                },
                inpt: {
                    assetId: '',
                    assetNumber: '', showAssetNumber: false,
                    assetName: '', showAssetName: false,
                    assetStatus: '', showAssetStatus: false,
                    assetType: '', showAssetType: false,
                    assetGroup: '', showAssetGroup: false,
                    assetSerial: '', showAssetSerial: false,
                    assetPrice: '', showAssetPrice: false,
                    assetRemark: '', showAssetRemark: false,
                    assetExpire: '', showAssetExpire: false,
                    assetWarrantry: '', showAssetWarrantry: false,
                    assetSdate: '', showAssetSdate: false,
                    assetEdate: '', showAssetEdate: false,
                    assetNameInvalid: false,
                    assetPriceInvalid: false,
                    assetFileName: ''
                },
                btn: {
                    btnAssetSetUpdateShow: false,
                    btnAssetSetUpdateDisabled: false,
                    btnAssetSetUpdateTxt: '',
                    btnAssetSetCancelShow: false,
                    btnAssetSetCancelDisabled: false,
                    uploadStatus: ''
                },
                progress: {
                    content: '',
                    btnCloseShow: false
                },
                upload: {
                    showProgress: false,
                    inputUploadClassValid: false,
                    inputUploadClassInvalid: false,
                    inputUploadDisabled: false,
                    btnUploadDisabled: false,
                    uploadStatus: '',
                    showHandleProgress: false,
                    inputHandleClassValid: false,
                    inputHandleClassInvalid: false,
                    inputHandleDisabled: false,
                    btnUploadHandleDisabled: false,
                    uploadHandleStatus: ''
                },
                handle: {
                    action: 'create',
                    title: '',
                    searchEmpTxt: '',
                    inptHandleSeq: '', inptArrIndex: '',
                    inptEmpId: '', inptEmpName: '', inptEmpSurname: '',
                    inptEmpFullName: '',
                    inptDeptName: '', inptLocatId: '', inptLocatName: '',
                    inptHandleRemark: '', inptHandleStatus: '',
                    inptHandleStartDate: '', inptHandleEndDate: '',
                    inptHwStatus: 1,
                    validLocatId: false,
                    validHandleStatus: false,
                    validHandleStartDate: false,
                    btnSaveTxt: '', 
                    btnSaveDisabled: false,
                    btnCloseDisabled: false, 
                    btnTitleCloseDisabled: false,
                    btnAddTTCloseDisabled: false,
                    btnAddCloseDisabled: false,
                    btnAddCloseTxt: '<i class="fas fa-ban"></i> ยกเลิก</button>',
                    alertError: false,
                    alertErrorMsg: '',
                    modelContent: '', btnConfirmTxt: '',
                    btnConfirmDisabled: false, btnCloseDisabled: false,
                    btnCloseTTDisabled: false,
                }
            },
            upload: {
                asset_id: '',
                file_name: '',
                handle_type: 1,
                handle_id: '',
                handle_filename: ''
            },
            removeFile: {
                index: '',
                id: '', file_name: '', file_path: '',
                modelContent: '', btnConfirmTxt: '',
                btnConfirmDisabled: false, btnCloseDisabled: false,
                btnCloseTTDisabled: false,
            },
            file: null,
            filehandle: null,
            data: {
                asset: [],
                assettype: [],
                assetfile: [],
                assethandle: [],
                locations: [],
                employees: [],
                handlepaper: []
            }

        }
    },
    methods:{
        checkSeessionLogin(bool){
            if(bool){
                axios.get('/api/login/check-exist-session')
                .then(response => {
                    if(response.data)
                        return true;
                    else{
                        this.$swal({
                            title: 'เวลาในการ Login หมดลงแล้ว', text: 'กรุณา Login ใหม่', icon: 'Error',
                            showCancelButton: false, cancelButtonColor: '#d33',
                            confirmButtonColor: '#3085d6', confirmButtonText: 'ไปยังหน้า Login',
                        })
                        .then((result) => { if(result.isConfirmed){ location.href = '/login'; } });
                        return false;
                    }
                })
                .catch(response => {
                    this.$swal({
                        title: 'เวลาในการ Login หมดลงแล้ว', text: 'กรุณา Login ใหม่', icon: 'Error',
                        showCancelButton: false, cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6', confirmButtonText: 'ไปยังหน้า Login',
                    })
                    .then((result) => { if(result.isConfirmed){ location.href = '/login'; } });
                    return false;
                });
            }
            else{
                this.$swal({
                    title: 'เวลาในการ Login หมดลงแล้ว', text: 'กรุณา Login ใหม่', icon: 'Error',
                    showCancelButton: false, cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6', confirmButtonText: 'ไปยังหน้า Login',
                })
                .then((result) => { if(result.isConfirmed){ location.href = '/login'; } });
                return false;
            }
        },
        /** Start Handle method */
        getHandleDelete(index){

            // if(!this.checkSeessionLogin())
            //     return;

            var pcontent = 'คุณต้องการลบประวัติผู้ถือครองทรัพย์สิน ';
            pcontent += this.data.assethandle[index].it_emp_name_th+' '+this.data.assethandle[index].it_emp_surname_th;;
            pcontent += ' หรือไม่';

            this.html.handle.modelContent = pcontent;
            this.html.handle.btnConfirmTxt = '<i class="fas fa-trash"></i> ยืนยัน';

            this.html.handle.inptHandleSeq = this.data.assethandle[index].it_asst_hw_seq;
            this.html.handle.btnCloseTTDisabled = false;
            this.html.handle.btnConfirmDisabled = false;
            this.html.handle.btnCloseDisabled = false;

            $('#modal-remove-handle').modal('show');

        },
        removeAssetHandle(){

            var strBtnSave = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ระบบกำลังบันทึกข้อมูล...';
            this.html.handle.btnConfirmTxt = strBtnSave;
            this.html.handle.btnConfirmDisabled = true;
            this.html.handle.btnCloseDisabled = true;
            this.html.handle.btnCloseTTDisabled = true;

            axios.delete('/api/admin/it/assethandle/'+this.html.handle.inptHandleSeq)
            .then(response => {
                this.getAssetHandle();
                $('#modal-remove-handle').modal('hide');
            });

        },
        getHandleEdit(index){

            $('#modal-holdasset-edit').modal('show');

            this.html.handle.inptArrIndex = index;
            this.html.handle.action = 'update';
            this.html.handle.title = 'แก้ไขประวัติผู้ถือครองทรัพย์สิน';
            this.html.handle.inptHandleSeq = this.data.assethandle[index].it_asst_hw_seq;            
            this.html.handle.inptEmpId = this.data.assethandle[index].it_emp_id;                        
            this.html.handle.inptEmpName = this.data.assethandle[index].it_emp_name_th;                        
            this.html.handle.inptEmpSurname = this.data.assethandle[index].it_emp_surname_th;
            this.html.handle.inptEmpFullName = this.html.handle.inptEmpName+' '+this.html.handle.inptEmpSurname;                                    
            this.html.handle.inptDeptName = this.data.assethandle[index].it_dept_name;                                    
            this.html.handle.inptLocatId = ( this.data.assethandle[index].it_locat_id !== null ? this.data.assethandle[index].it_locat_id : '' );                                   
            this.html.handle.inptLocatName = this.data.assethandle[index].it_locat_name;                              
            this.html.handle.inptHandleRemark = this.data.assethandle[index].it_asst_hw_remark;                              
            this.html.handle.inptHandleStatus = this.data.assethandle[index].it_asst_hw_status; 
            this.html.handle.inptHwStatus = this.data.assethandle[index].it_hw_status;  
            
            var startDate = this.data.assethandle[index].it_asst_hw_start_date,
                endDate = this.data.assethandle[index].it_asst_hw_end_date

            this.html.handle.inptHandleStartDate = ( startDate != '0000-00-00' ? startDate : '' );
            this.html.handle.inptHandleEndDate = ( endDate != '0000-00-00' ? endDate : '' );

            this.html.handle.alertError = false;
            this.html.handle.validLocatId = false;
            this.html.handle.validHandleStatus = false;
            this.html.handle.validHandleStartDate = false;
            this.html.handle.btnSaveDisabled = false;
            this.html.handle.btnCloseDisabled = false;
            this.html.handle.btnTitleCloseDisabled = false;
            this.html.handle.btnSaveTxt = '<i class="fas fa-save"></i> บันทึก';

        },
        setHandleEdit(){

            // if(!this.checkSeessionLogin())
            //     return;

            this.html.handle.validLocatId = ( this.html.handle.inptLocatId == '' ? true : false );
            this.html.handle.validHandleStatus = ( this.html.handle.inptLocatId == '' ? true : false );
            this.html.handle.validHandleStartDate = ( this.html.handle.inptHandleStartDate == '' ? true : false );

            if(
                !this.html.handle.validLocatId &&
                !this.html.handle.validHandleStatus &&
                !this.html.handle.validHandleStartDate 
            ){

                this.html.handle.btnSaveDisabled = true;
                this.html.handle.btnCloseDisabled = true;
                this.html.handle.btnTitleCloseDisabled = true;

                var btnTxt = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
                btnTxt += 'Loading...';

                this.html.handle.btnSaveTxt = btnTxt;

                if( this.html.handle.action == 'create' ){

                    if( 
                        this.data.assethandle.length > 0
                        && this.html.handle.inptHandleStartDate <= this.data.assethandle[this.data.assethandle.length-1].it_asst_hw_start_date 
                    ){

                        this.$swal({
                            icon: 'error',
                            text: 'วันที่เริ่มต้นใช้งานใหม่น้อยกว่าหรือเท่ากับวันที่เริ่มต้นใช้งานใหม่ผู้ของถือครองล่าสุด'
                        });

                        this.html.handle.btnSaveDisabled = false;
                        this.html.handle.btnCloseDisabled = false;
                        this.html.handle.btnTitleCloseDisabled = false;
                        this.html.handle.btnSaveTxt = '<i class="fas fa-save"></i> บันทึก';

                        return;

                    }
                    
                    if(this.data.assethandle.length > 0) var asst_hw_seq = this.data.assethandle[0].it_asst_hw_seq;
                    else var asst_hw_seq = '';

                    axios.post('/api/admin/it/assethandle',{
                        asset_id: this.data.asset.it_hw_seq,
                        asset_status: this.html.handle.inptHwStatus,
                        last_id: asst_hw_seq,
                        emp_id: this.html.handle.inptEmpId,
                        locat_id: this.html.handle.inptLocatId,
                        handle_status: this.html.handle.inptHandleStatus,
                        handle_sdate: this.html.handle.inptHandleStartDate,
                        handle_remark: this.html.handle.inptHandleRemark
                    })
                    .then(response => {
                        if(response.data.status){
                            this.getAssetHandle();
                            this.getAssetHere();
                            $('#modal-holdasset-edit').modal('hide');
                        }
                        else{

                            if(response.data.exception == 'loginExpire')
                                this.checkSeessionLogin(false);                            

                            this.html.handle.alertErrorMsg = '';

                            if(response.data.exception == 'sqlError')
                                this.html.handle.alertErrorMsg = response.data.message;

                            this.html.handle.btnSaveDisabled = false;
                            this.html.handle.btnCloseDisabled = false;
                            this.html.handle.btnTitleCloseDisabled = false;
                            this.html.handle.alertError = true;
                            this.html.handle.btnSaveTxt = '<i class="fas fa-save"></i> บันทึก';

                        }
                    })
                    .catch(response => {
                        this.html.handle.btnSaveDisabled = false;
                        this.html.handle.btnCloseDisabled = false;
                        this.html.handle.btnTitleCloseDisabled = false;
                        this.html.handle.alertError = true;
                        this.html.handle.alertErrorMsg = '';
                        this.html.handle.btnSaveTxt = '<i class="fas fa-save"></i> บันทึก';
                    });

                }
                else{
                    
                    axios.put('/api/admin/it/assethandle/'+this.html.handle.inptHandleSeq,{
                        'locat_id': this.html.handle.inptLocatId,
                        'handle_status': this.html.handle.inptHandleStatus,
                        'start_date': this.html.handle.inptHandleStartDate,
                        'end_date': this.html.handle.inptHandleEndDate,
                        'handle_remark': this.html.handle.inptHandleRemark,
                        'asset_status': this.html.handle.inptHwStatus,
                    })
                    .then(response => {
                        if(response.data.status){
                            this.getAssetHandle();
                            $('#modal-holdasset-edit').modal('hide');
                        }
                        else{

                            if(response.data.exception == 'loginExpire')
                                this.checkSeessionLogin(false);                            

                            this.html.handle.alertErrorMsg = '';

                            if(response.data.exception == 'sqlError')
                                this.html.handle.alertErrorMsg = response.data.message;

                            this.html.handle.btnSaveDisabled = false;
                            this.html.handle.btnCloseDisabled = false;
                            this.html.handle.btnTitleCloseDisabled = false;
                            this.html.handle.alertError = true;
                            this.html.handle.btnSaveTxt = '<i class="fas fa-save"></i> บันทึก';

                        }
                    })
                    .catch(response => {
                        this.html.handle.btnSaveDisabled = false;
                        this.html.handle.btnCloseDisabled = false;
                        this.html.handle.btnTitleCloseDisabled = false;
                        this.html.handle.alertError = true;
                        this.html.handle.alertErrorMsg = '';
                        this.html.handle.btnSaveTxt = '<i class="fas fa-save"></i> บันทึก';
                    });

                }

            }

        },
        searchEmployees(){
            axios.post('/api/admin/it/employee/searchhandle',{ keyword: this.html.handle.searchEmpTxt })
            .then(response => {
                if( response.data.length <= 0 )
                    this.$swal({icon: 'error', text: 'ค้นหาพนักงานไม่พบ'});
                this.data.employees = response.data;
            });
        },
        selectEmployee(index){
            
            if( 
                this.data.assethandle.length > 0
                && (this.data.employees[index].it_emp_id == this.data.assethandle[this.data.assethandle.length-1].it_emp_id 
                && this.data.assethandle[this.data.assethandle.length-1].it_asst_hw_status == 1 )
            ){

                this.$swal({
                    icon: 'error',
                    text: 'ผู้ถือครองใหม่ซ้ำกับผู้ถือครองเดิมที่กำลังถือครองอยู่'
                });

                return;

            }

            $('#modal-holdasset-edit').modal('show');
            $('#modal-holdasset-add').modal('hide');

            this.html.handle.action = 'create';
            this.html.handle.title = 'บันทึกประวัติผู้ถือครองทรัพย์สิน';

            let emp = this.data.employees[index];
            let date = new Date();
            let start_date = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+(date.getDate())).slice(-2);            
            
            this.html.handle.inptEmpId = this.data.employees[index].it_emp_id;
            this.html.handle.inptEmpFullName = this.data.employees[index].it_emp_name_th+' '+this.data.employees[index].it_emp_surname_th;
            this.html.handle.inptDeptName = this.data.employees[index].it_dept_name;
            this.html.handle.inptLocatId = this.data.employees[index].it_locat_id;
            this.html.handle.inptHandleStatus = 1;
            this.html.handle.inptHandleStartDate = start_date;
            this.html.handle.inptHandleEndDate = '';
            this.html.handle.inptHandleRemark = '';
            this.html.handle.btnSaveDisabled = false;
            this.html.handle.btnCloseDisabled = false;
            this.html.handle.btnTitleCloseDisabled = false;

            this.html.handle.btnSaveTxt = '<i class="fas fa-save"></i> บันทึก';
            
        },
        getAssetHandle(){
            axios.get('/api/admin/it/asset/assethandle/'+this.id)
            .then(response => {
                this.data.assethandle = response.data;
            });
        },
        getMasterLocation(){
            axios.get('/api/admin/it/location')
            .then(response => {
                this.data.locations = response.data;
            });
        },
        /** Start Asset method */
        getMasterAssettype(){
            axios.get('/api/admin/it/assettype')
            .then(response => {
                this.data.assettype = response.data;
            });
        },
        getAssetHere(){
            axios.get('/api/admin/it/asset/'+this.id)
            .then(response => {

                this.data.asset = response.data;
                this.html.inpt.assetId = this.data.asset.it_hw_seq;
                this.upload.asset_id = this.data.asset.it_hw_seq;

                if( ['ใช้งาน','ยืมใช้งาน','สำรอง'].indexOf(this.data.asset.status_name) >= 0 )
                    var colorStatus = 'text-success';
                else if( ['ส่งซ่อม'].indexOf(this.data.asset.status_name) >= 0 )
                    var colorStatus = 'text-warning';
                else if( ['ไม่ใช้งาน','ออกจำหน่าย'].indexOf(this.data.asset.status_name) >= 0 )
                    var colorStatus = 'text-danger';

                this.html.str.assetStatus = '<i class="fas fa-circle mr-2 '+colorStatus+'"></i> '+this.data.asset.status_name;       
                this.html.str.assetNumberHeader = this.data.asset.it_hw_number;      
                this.html.str.assetNumber = this.data.asset.it_hw_number;   
                this.html.str.assetName = this.data.asset.it_hw_name;
                this.html.str.assetType = this.data.asset.it_hw_type_name;
                this.html.str.assetGroup = this.data.asset.group_name;  
                this.html.str.assetSerial = this.data.asset.it_hw_serial;
                this.html.str.assetPrice = this.data.asset.it_hw_price;
                this.html.str.assetRemark = this.data.asset.it_hw_remark;
                this.html.str.assetExpire = this.data.asset.expired;
                this.html.str.assetWarrantry = this.data.asset.warrantry;
                this.html.str.assetSdate = (this.data.asset.start_date == '' ? 'ไม่มีข้อมูล' : this.data.asset.start_date);
                this.html.str.assetEdate = (this.data.asset.end_date == '' ? 'ไม่มีข้อมูล' : this.data.asset.end_date);

                this.setShowAssetData('show');

            });
        },
        getAssetFile(){
            axios.get('/api/admin/it/asset/file/'+this.id)
            .then(response => {
                this.data.assetfile = response.data;
            });
        },
        setShowAssetData(action){

            this.html.btn.btnAssetSetUpdateShow = ( action == 'edit' ? true : false );
            this.html.btn.btnAssetSetCancelShow = ( action == 'edit' ? true : false );
            this.html.btn.btnAssetSetUpdateTxt = '<i class="fas fa-save"></i> <span class="ml-2">บันทึก</span>';

            this.html.str.showAssetNumber = ( action == 'show' ? true : false );
            this.html.str.showAssetName = ( action == 'show' ? true : false );
            this.html.str.showAssetStatus = ( action == 'show' ? true : false );
            this.html.str.showAssetType = ( action == 'show' ? true : false );
            this.html.str.showAssetGroup = ( action == 'show' ? true : false );
            this.html.str.showAssetSerial = ( action == 'show' ? true : false );            
            this.html.str.showAssetPrice = ( action == 'show' ? true : false );           
            this.html.str.showAssetRemark = ( action == 'show' ? true : false );          
            this.html.str.showAssetExpire = ( action == 'show' ? true : false );          
            this.html.str.showAssetWarrantry = ( action == 'show' ? true : false );        
            this.html.str.showAssetSdate = ( action == 'show' ? true : false );        
            this.html.str.showAssetEdate = ( action == 'show' ? true : false );

            this.html.inpt.showAssetNumber = ( action == 'show' ? true : false );
            this.html.inpt.showAssetName = ( action == 'edit' ? true : false );
            this.html.inpt.showAssetStatus = ( action == 'edit' ? true : false );
            this.html.inpt.showAssetType = ( action == 'edit' ? true : false );
            this.html.inpt.showAssetGroup = ( action == 'edit' ? true : false );
            this.html.inpt.showAssetSerial = ( action == 'edit' ? true : false );            
            this.html.inpt.showAssetPrice = ( action == 'edit' ? true : false );           
            this.html.inpt.showAssetRemark = ( action == 'edit' ? true : false );         
            this.html.inpt.showAssetExpire = ( action == 'edit' ? true : false );          
            this.html.inpt.showAssetWarrantry = ( action == 'edit' ? true : false );      
            this.html.inpt.showAssetSdate = ( action == 'edit' ? true : false );        
            this.html.inpt.showAssetEdate = ( action == 'edit' ? true : false );

            if( action == 'edit' ){
                this.html.inpt.assetNumber = this.data.asset.it_hw_number;  
                this.html.inpt.assetRemark = this.data.asset.it_hw_remark;                
                this.html.inpt.assetName = this.data.asset.it_hw_name;              
                this.html.inpt.assetStatus = this.data.asset.it_hw_status;
                this.html.inpt.assetType = this.data.asset.it_hw_type_id;              
                this.html.inpt.assetGroup = this.data.asset.it_hw_group;
                this.html.inpt.assetSerial = this.data.asset.it_hw_serial;
                this.html.inpt.assetPrice = this.data.asset.it_hw_price;
                this.html.inpt.assetExpire = this.data.asset.expired;
                this.html.inpt.assetWarrantry = this.data.asset.warrantry;
                this.html.inpt.assetSdate = this.data.asset.start_date;
                this.html.inpt.assetEdate = this.data.asset.end_date;
                this.html.inpt.assetNameInvalid = false;
                this.html.inpt.assetPriceInvalid = false;
                this.html.btn.btnAssetSetUpdateDisabled = false;
                this.html.btn.btnAssetSetCancelDisabled = false;
            }

        },
        setAssetEdit(){

            // if(!this.checkSeessionLogin())
            //     return;

            var strBtnSave = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ระบบกำลังบันทึกข้อมูล...';
            this.html.btn.btnAssetSetUpdateTxt = strBtnSave;
            this.html.btn.btnAssetSetUpdateDisabled = true;
            this.html.btn.btnAssetSetCancelDisabled = true;

            this.html.inpt.assetNameInvalid = ( this.html.inpt.assetName == '' ? true : false );
            this.html.inpt.assetPriceInvalid = ( this.html.inpt.assetPrice == '' ? true : false );

            if( !this.html.inpt.assetNameInvalid && !this.html.inpt.assetPriceInvalid ){

                $('#modal-progress').modal('show');

                var pcontent = '<div class="progress">';
                pcontent += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"';
                pcontent += 'aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';
                pcontent += '</div> <strong>ระบบกำลังบันทึกข้อมูล</strong>';

                this.html.progress.content = pcontent;
                this.html.progress.btnCloseShow = false;

                axios.put('/api/admin/it/asset/'+this.html.inpt.assetId,{
                    asset_number: this.html.inpt.assetNumber,
                    asset_name: this.html.inpt.assetName,
                    asset_type: this.html.inpt.assetType,
                    asset_serial: this.html.inpt.assetSerial,
                    asset_status: this.html.inpt.assetStatus,
                    asset_group: this.html.inpt.assetGroup,
                    asset_remark: this.html.inpt.assetRemark,
                    asset_price: this.html.inpt.assetPrice,
                    asset_expired: this.html.inpt.assetExpire,
                    asset_warrantry: this.html.inpt.assetWarrantry,
                    asset_startdate: this.html.inpt.assetSdate,
                    asset_enddate: this.html.inpt.assetEdate
                })
                .then(response => {
                    if(response.data.status){
                        var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบบันทึกข้อมูลเรียบร้อยแล้ว';
                        this.getAssetHere();
                    }
                    else{   
                        var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';
                        if(response.data.exception == 'sqlError'){                           
                                pcontent += '<br><br><font color="red"><b>[Error]</b></font><br>';
                                pcontent += response.data.message;
                        }
                        else{
                            this.checkSeessionLogin(false);
                        }
                    }
                    this.html.progress.content = pcontent;
                    this.html.progress.btnCloseShow = true;
                    this.html.btn.btnAssetSetUpdateTxt = '<i class="fas fa-save"></i> <span class="ml-2">บันทึก</span>';
                    this.html.btn.btnAssetSetUpdateDisabled = false;
                    this.html.btn.btnAssetSetCancelDisabled = false;
                })
                .catch(response => {
                    var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';
                    this.html.progress.content = pcontent;
                    this.html.progress.btnCloseShow = true;
                });

            }
            else{
                this.html.btn.btnAssetSetUpdateTxt = '<i class="fas fa-save"></i> <span class="ml-2">บันทึก</span>';
                this.html.btn.btnAssetSetUpdateDisabled = false;
                this.html.btn.btnAssetSetCancelDisabled = false;
            }

        },
        onFileChange(e){
            this.file = e.target.files[0];
            this.upload.file_name = this.file.name;
            this.html.upload.inputUploadClassValid = false;
            this.html.upload.inputUploadClassInvalid = false;
            this.html.upload.uploadStatus = '';
            $('#assetUploadFile').next('.custom-file-label').html(this.upload.file_name);
        },
        onFileHandleChange(e){
            this.filehandle = e.target.files[0],
            this.upload.handle_filename = this.filehandle.name;
            this.html.upload.inputHandleClassValid = false;
            this.html.upload.inputHandleClassInvalid = false;
            this.html.upload.uploadHandleStatus = '';
            $('#handleUploadFile').next('.custom-file-label').html(this.upload.handle_filename);
        },
        uploadAssetFile(e){

            // if(!this.checkSeessionLogin())
            //     return;
            
            if( this.file === null ){
                this.html.upload.uploadStatus = '<i class="fas fa-exclamation-triangle text-danger"></i> <strong class="text-danger">กรุณาเลือก File</strong>';
                this.html.upload.inputUploadClassValid = false;
                this.html.upload.inputUploadClassInvalid = true;
                return;
            }
            
            this.html.upload.uploadStatus = '';
            this.html.upload.inputUploadClassValid = false;
            this.html.upload.inputUploadClassInvalid = false;
            this.html.upload.inputUploadDisabled = true;
            this.html.upload.btnUploadDisabled = true;            
            this.html.upload.showProgress = true;
            
            e.preventDefault();

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            let formData = new FormData();
            formData.append('file', this.file);
            
            _.each(this.upload, (value, key) => {
                formData.append(key, value);
            });

            axios.post('/api/admin/it/asset/upload', formData, config)
            .then(response => {
                $('#assetUploadFile').next('.custom-file-label').html('Choose file');
                this.file = null;
                this.html.upload.uploadStatus = '<i class="fas fa-check-circle text-success"></i> <strong class="text-success">Upload file complete.</strong>';
                this.html.upload.inputUploadClassValid = true;
                this.html.upload.inputUploadClassInvalid = false;
                this.html.upload.inputUploadDisabled = false;
                this.html.upload.btnUploadDisabled = false;            
                this.html.upload.showProgress = false;
                this.getAssetFile();
            })
            .catch(response => {                
                this.html.upload.uploadStatus = '<i class="fas fa-exclamation-triangle text-danger"></i> <strong class="text-danger">Upload file fail!</strong>';
                this.html.upload.inputUploadClassValid = false;
                this.html.upload.inputUploadClassInvalid = true;
                this.html.upload.inputUploadDisabled = false;
                this.html.upload.btnUploadDisabled = false;            
                this.html.upload.showProgress = false;
            });

        },
        setRemoveAssetFile(index){

            // if(!this.checkSeessionLogin())
            //     return;

            $('#modal-remove-file').modal('show');

            this.removeFile.index = index;
            this.removeFile.id = this.data.assetfile[index].it_hw_file_id;            
            this.removeFile.file_name = this.data.assetfile[index].it_hw_file_name;
            this.removeFile.file_path = this.data.assetfile[index].it_hw_file_path;
            
            this.removeFile.btnConfirmDisabled = false;
            this.removeFile.btnCloseDisabled = false;
            this.removeFile.btnCloseTTDisabled = false;

            var pcontent = 'คุณต้องการลบ File '+this.removeFile.file_name+' หรือไม่';
            this.removeFile.modelContent = pcontent;

            this.removeFile.btnConfirmTxt = '<i class="fas fa-trash"></i> ยืนยัน';

        },
        removeAssetFile(){

            var strBtnSave = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ระบบกำลังบันทึกข้อมูล...';
            this.removeFile.btnConfirmTxt = strBtnSave;
            this.removeFile.btnConfirmDisabled = true;
            this.removeFile.btnCloseDisabled = true;
            this.removeFile.btnCloseTTDisabled = true;

            axios.post('/api/admin/it/asset/removefile',{
                file_id: this.removeFile.id,
                file_path: this.removeFile.file_path
            })
            .then(response => {

                if(response.data.Status){
                    var strStatus = '<i class="fas fa-check-circle text-success"></i>';
                    strStatus += ' <strong class="text-success">Remove file '+this.removeFile.file_name+' complete.</strong>';
                    this.html.upload.uploadStatus = strStatus;
                    this.getAssetFile();
                }
                else{
                    var strStatus = '<i class="fas fa-exclamation-triangle text-danger"></i>';
                    strStatus += ' <strong class="text-success">Remove file '+this.removeFile.file_name+' Fail!!!</strong>';
                    this.html.upload.uploadStatus = strStatus;
                }

                $('#modal-remove-file').modal('hide');

            })
            .catch(response => {
                var strStatus = '<i class="fas fa-exclamation-triangle text-danger"></i>';
                strStatus += ' <strong class="text-success">Remove file '+this.removeFile.file_name+' Fail!!!</strong>';
                this.html.upload.uploadStatus = strStatus;
                $('#modal-remove-file').modal('hide');
            });

        },
        uploadHandleFile(e){

            // if(!this.checkSeessionLogin())
            //     return;

            if( this.filehandle === null ){
                let status = '<i class="fas fa-exclamation-triangle text-danger"></i> ';
                    status += '<strong class="text-danger">กรุณาเลือก File</strong>';
                this.html.upload.uploadHandleStatus = status;
                this.html.upload.inputHandleClassValid = false;
                this.html.upload.inputHandleClassInvalid = true;
                return;
            }
            else if( this.filehandle.type != 'application/pdf' ){
                let status = '<i class="fas fa-exclamation-triangle text-danger"></i> ';
                    status += '<strong class="text-danger">กรุณาเลือก File PDF เท่านั้น</strong>';
                this.html.upload.uploadHandleStatus = status;
                this.html.upload.inputHandleClassValid = false;
                this.html.upload.inputHandleClassInvalid = true;
                return;
            }

            this.html.upload.uploadHandleStatus = '',
            this.html.upload.inputHandleClassValid = false;
            this.html.upload.inputHandleClassInvalid = false;
            this.html.upload.inputHandleDisabled = true;
            this.html.upload.btnUploadHandleDisabled = true;
            this.html.upload.showHandleProgress = true;

            e.preventDefault();

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            let formData = new FormData();
            formData.append('file', this.filehandle);
            formData.append('file_name', this.upload.handle_filename);
            formData.append('handle_id',this.upload.handle_id);
            formData.append('handle_group',this.upload.handle_type);

            axios.post('/api/admin/it/assethandle/upload', formData, config)
            .then(response => {

                $('#handleUploadFile').next('.custom-file-label').html('Choose file');

                this.filehandle = null;

                let statusRes = '<i class="fas fa-check-circle text-success"></i> ';
                    statusRes += '<strong class="text-success">Upload file complete.</strong>';

                this.html.upload.uploadHandleStatus = statusRes,
                this.html.upload.inputHandleClassValid = true;
                this.html.upload.inputHandleClassInvalid = false;
                this.html.upload.inputHandleDisabled = false;
                this.html.upload.btnUploadHandleDisabled = false;
                this.html.upload.showHandleProgress = false;

                this.getHandleFile();

            })
            .catch(response => {
                let statusRes = '<i class="fas fa-exclamation-triangle text-danger"></i> ';
                    statusRes += '<strong class="text-danger">Upload file fail!</strong>';
                this.html.upload.uploadHandleStatus = statusRes,
                this.html.upload.inputHandleClassValid = false;
                this.html.upload.inputHandleClassInvalid = true;
                this.html.upload.inputHandleDisabled = false;
                this.html.upload.btnUploadHandleDisabled = false;
                this.html.upload.showHandleProgress = false;
            });

        },
        removeHandleFile(index){
            
            // if(!this.checkSeessionLogin())
            //     return;

            this.$swal({
                icon: 'warning',
                title: 'ยืนยันการลบไฟล์ <br>'+this.data.handlepaper[index].assthd_file,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ลบไฟล์',
                denyButtonText: 'ยกเลิก',
            })
            .then((result) => {
                if( result.isConfirmed ){
                    axios.post('/api/admin/it/assethandle/removefile',{
                        file_id: this.data.handlepaper[index].assthd_id,
                        file_path: this.data.handlepaper[index].assthd_path
                    })
                    .then(response => {
                        this.$swal({
                            icon: 'success',
                            title: 'ลบไฟล์เรียบร้อยแล้ว'
                        });
                        this.getHandleFile();
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
        getHandleFile(){
            axios.post('/api/admin/it/assethandle/file',{ assthd_id: this.upload.handle_id })
            .then(response => { this.data.handlepaper = response.data; });
        },
        getHandlePaper(assthdId,empId){

            axios.post('/api/admin/it/assethandle/paper',{
                assthd_id: assthdId,
                emp_id: empId
            })
            .then(response => {

                this.upload.handle_id = assthdId;
                
                let paper_no = response.data.paper_no,
                    paper = response.data.paper;
                
                if( paper_no[0].it_assthd_no == '' || paper_no[0].it_assthd_no === null  ){
                    this.html.str.showPaperHandleNoCreate = true;
                    this.html.str.showPaperHandleNoPrint = false;
                }
                else{
                    this.html.str.showPaperHandleNoCreate = false;
                    this.html.str.showPaperHandleNoPrint = true;
                    this.html.str.paperHandleNo = paper_no[0].it_assthd_no;
                }

                if( paper_no[0].it_asstbr_no == '' || paper_no[0].it_asstbr_no === null  ){
                    this.html.str.showPaperBorrowNoCreate = true;
                    this.html.str.showPaperBorrowNoPrint = false;
                }
                else{
                    this.html.str.showPaperBorrowNoCreate = false;
                    this.html.str.showPaperBorrowNoPrint = true;
                    this.html.str.paperBorrowNo = paper_no[0].it_asstbr_no;
                }

                this.data.handlepaper = paper;
                $('#modal-holdasset-file').modal('show');

            });            

        },
        createHandleFile(){
            axios.post('/api/admin/it/assethandle/create/number/handle',{
                handle_id: this.upload.handle_id
            })
            .then(response => {
                this.html.str.showPaperHandleNoCreate = false;
                this.html.str.showPaperHandleNoPrint = true;
                this.html.str.paperHandleNo = response.data.paper_no;
            });
        },
        createBorrowFile(){

            this.html.upload.uploadHandleStatus = '',
            this.html.upload.inputHandleClassValid = false;
            this.html.upload.inputHandleClassInvalid = false;
            this.html.upload.inputHandleDisabled = false;
            this.html.upload.btnUploadHandleDisabled = false;
            this.html.upload.showHandleProgress = false;

            axios.post('/api/admin/it/assethandle/create/number/borrow',{
                handle_id: this.upload.handle_id
            })
            .then(response => {
                this.html.str.showPaperBorrowNoCreate = false;
                this.html.str.showPaperBorrowNoPrint = true;
                this.html.str.paperBorrowNo = response.data.paper_no;
            });

        }

    }
}
</script>