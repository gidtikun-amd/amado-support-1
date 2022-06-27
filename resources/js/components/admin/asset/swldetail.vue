<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-file-alt"></i><span class="ml-2">รายละเอียด S/W License</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 py-3">
                        <h4>รหัสทรัพย์สิน : 
                            <a class="text-decoration-none text-primary" 
                            :href="'/admin/asset/'+id" :target="'_blank'">
                                <i class="fas fa-search"></i>
                                {{software.it_hw_number}}
                            </a>
                        </h4>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12"><h5>รายละเอียดทรัพย์สิน</h5></div>
                        </div>
                        <div class="row mb-3 mx-3">
                            <div class="col-12">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">ชื่อทรัพย์สิน :</span>
                                    <span v-html="software.it_hw_name"></span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">สถานะ :</span>
                                    <span v-html="content.assetStatus"></span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">ประเภททรัพย์สิน :</span>
                                    <span v-html="software.it_hw_type_name"></span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">ประเภทถือครอง :</span>
                                    <span v-html="software.group_name"></span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">Serial Number :</span>
                                    <span v-html="software.it_hw_serial"></span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">ราคา :</span>
                                    <span v-html="software.it_hw_price"></span>
                                    <span class="ml-1">บาท</span>
                                </p>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">Expired Date :</span>
                                    <span v-html="software.expired"></span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">Warrantry :</span>
                                    <span v-html="software.warrantry"></span>
                                </p>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">วันที่เริ่มใช้งาน :</span>
                                    <span v-html="software.start_date"></span>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="mb-1 form-inline">
                                    <span class="mr-2">วันที่หมดอายุ :</span>
                                    <span v-html="software.end_date"></span>
                                </p>
                            </div>
                            <div class="col-12">
                                <p class="mb-1">
                                    <span class="mr-2">หมายเหตุ :</span>
                                    <span v-html="software.it_hw_remark"></span>
                                </p>
                            </div>
                        </div>                        
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:;"
                                v-bind:class="{'active': content.tabLicense}"
                                v-on:click="setPanel(1)">
                                    <i class="fas fa-list-alt"></i><span class="ml-2">รายการ License</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:;"
                                v-bind:class="{'active': content.tabHandle}"
                                v-on:click="setPanel(2)">
                                    <i class="fas fa-user-tag"></i><span class="ml-2">ผู้ถือ License</span>
                                </a>
                            </li>
                        </ul>  
                    </div>
                    <div class="card-body">

                        <div class="row" v-show="content.panelLicense">                            
                            <div class="col-12 mb-3">
                                <button type="button" class="btn btn-primary" v-on:click="toAddLicense()">
                                    <i class="fas fa-plus-square mr-2"></i>เพิ่ม License 
                                </button>
                            </div>
                            <div class="col-12">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center" width="5%">#</th>
                                            <th scope="col" class="text-center">License</th>
                                            <th scope="col" class="text-center" width="7%">
                                                Volumes/<font color="red">Used</font>
                                            </th>
                                            <th scope="col" class="text-center" width="7%">สถานะ</th>
                                            <th scope="col" class="text-center" width="10%"><i class="fas fa-sort-down"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr 
                                        v-show="license.length > 0"
                                        v-for="(list,index) in license" :key="index">
                                            <th scope="row" class="text-center">{{index+1}}</th>
                                            <td>{{list.swl_license}}</td>
                                            <td class="text-center">
                                                {{list.swl_volumes}}
                                                /<font color="red"><strong>{{list.swl_used}}</strong></font>
                                            </td>
                                            <td>{{list.swl_status}}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-warning btn-sm"
                                                v-on:click="toUpdateLicense(index)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                v-on:click="setDeleteLicense(index)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="license.length <= 0">
                                            <td class="text-center" colspan="4">ไม่มีข้อมูลผู้ถือ License</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row" v-show="content.panelHandle">
                            <div class="col-12 mb-3">
                                <form class="form-inline">
                                    <div class="form-group mr-2">
                                        <select class="form-control" 
                                        v-model="input.handle.licenseId"
                                        v-on:change="getListLicenseHandle()">
                                            <option v-for="(lc,index) in license" :key="index" :value="lc.swl_id">
                                                {{lc.swl_license}}
                                            </option>
                                        </select>
                                    </div>
                                    <button type="button" class='btn btn-primary' 
                                    :disabled="!license.length > 0"
                                    v-on:click="toAddLicenseHandle()">
                                        <i class="fas fa-user-plus mr-2"></i> เพิ่มผู้ถือครอง
                                    </button>
                                </form>
                            </div>
                            <div class="col-12 text-center text-primary" v-show="content.panelHandleProgress">
                                <div class="progress">
                                     <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" 
                                     style="width: 100%"></div>
                                </div>
                                <b>ระบบกำลังดึงข้อมูล</b>
                            </div>
                            <div class="col-12" v-show="content.panelHandleTable">
                                <table class="table table-hover table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col">รหัสพนักงาน</th>
                                            <th scope="col">ชื่อ-นามสกุล</th>                                            
                                            <th scope="col">แผนก</th>
                                            <th scope="col">Location</th>
                                            <th scope="col" class="text-center">สถานะ</th>
                                            <th scope="col">วันที่เริ่มต้นใช้งาน</th>                                            
                                            <th scope="col">วันที่สิ้นสุดใช้งาน</th>
                                            <th scope="col" class="text-center" width="10%"><i class="fas fa-sort-down"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                        v-show="handle.length > 0"
                                        v-for="(lshd,index) in handle" :key="index">
                                            <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                            <td class="align-middle">{{lshd.emp_id}}</td>
                                            <td class="align-middle">{{lshd.emp_name}}</td>
                                            <td class="align-middle">{{lshd.dept_name}}</td>
                                            <td class="align-middle">{{lshd.locat_name}}</td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-pill badge-tbl-custom badge-success"
                                                v-if="lshd.handle_status == 1">ถือครอง</span>
                                                <span class="badge badge-pill badge-tbl-custom badge-danger"
                                                v-else>สิ้นสุด</span>
                                            </td>
                                            <td class="align-middle">{{lshd.handle_sdate}}</td>
                                            <td class="align-middle">{{lshd.handle_edate}}</td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-warning btn-sm"
                                                v-on:click="toUpdateLicenseHandle(index)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                v-on:click="setDeleteLicenseHandle(index)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-show="handle.length <= 0">
                                            <td class="text-center" colspan="9">ไม่มีข้อมูลการถือครอง License</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- Modal : #modal-license -->
        <div class="modal fade" id="modal-license" data-backdrop="static" data-keyboard="false" 
        tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <i class="fas fa-tags text-primary"> {{input.license.content.title}}</i> 
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><strong>ทรัพย์สิน</strong></label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control-plaintext" :value="software.it_hw_name" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><strong>License</strong></label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" 
                                            v-show="input.license.method == 'add'"
                                            v-model="input.license.text"
                                            :disabled="input.license.disabled.text"
                                            v-bind:class="{'is-invalid': input.license.valid.text}">
                                            <div class="invalid-feedback">กรุณาใส่ License....!</div>
                                            <input type="text" class="form-control-plaintext" 
                                            v-show="input.license.method == 'update'"
                                            :value="input.license.text" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><strong>Volumes</strong></label>
                                        <div class="col-sm-2">                                            
                                            <!-- v-show="input.license.method == 'add'" -->
                                            <input type="number" class="form-control" 
                                            v-model="input.license.volume"
                                            :disabled="input.license.disabled.volume"
                                            v-bind:class="{'is-invalid': input.license.valid.volume}">                                            
                                            <div class="invalid-feedback">กรุณาใส่ Volumes....!</div>
                                            <!-- <input type="text" class="form-control-plaintext" 
                                            v-show="input.license.method == 'update'"
                                            :value="input.license.volume" readonly> -->
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><strong>สถานะ</strong></label>
                                        <div class="col-sm-3">
                                            <select class="form-control" 
                                            v-model="input.license.status"
                                            :disabled="input.license.disabled.status">
                                                <option value="1">ใช้งาน</option>
                                                <option value="0">ไม่ใช้งาน</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr class="pb-3">
                                    <div class="row justify-content-md-center m-3">
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary btn-block"
                                            v-on:click="setSoftwareLicense()"
                                            v-html="input.license.content.btnSave"
                                            :disabled="input.license.disabled.btnSave">
                                                <i class="fas fa-save"></i> บันทึก
                                            </button>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-block" data-dismiss="modal"
                                            :disabled="input.license.disabled.btnClose">
                                                <i class="fas fa-ban"></i> ยกเลิก
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-handle -->
        <div class="modal fade" id="modal-handle" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary">
                            <i class="fas fa-user-edit"></i>
                            <span class="ml-2">บันทึกผู้ถือครอง License</span>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row m-3">
                                <div class="form-group col-md-5">
                                    <label for="uname">รหัสผู้ถือครอง : </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" 
                                        v-model="input.handle.searchEmp"
                                        v-on:keyup.enter="searchEmployee()"
                                        v-bind:class="{'is-invalid': input.handle.valid.empId}"
                                        :disabled="input.handle.disabled.searchEmp">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="button"
                                            v-on:click="searchEmployee()"
                                            :disabled="input.handle.disabled.btnSearch">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">กรุณาใส่ รหัสผู้ถือครอง...!</div>
                                    </div>
                                </div>
                                <div class="form-group col-md-7">
                                    <label for="uname">ผู้ถือครอง : </label>
                                    <input type="text" class="form-control" 
                                    v-model="input.handle.empName" 
                                    :readonly="true">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="uname">ฝ่าย : </label>
                                    <input type="text" class="form-control" 
                                    v-model="input.handle.deptName" 
                                    :readonly="true">
                                </div>
                            </div>
                            <hr class="pb-3">
                            <div class="row m-3">
                                <div class="form-group col-md-7">
                                    <label for="uname">Location : </label>
                                    <select class="form-control" 
                                    v-model="input.handle.location"
                                    v-bind:class="{'is-invalid': input.handle.valid.location}"
                                    :disabled="input.handle.disabled.location">
                                        <option value="" disabled selected>กรุณาเลือก...</option>
                                        <option v-for="locat in location" 
                                        :key="locat.it_locat_id" 
                                        :value="locat.it_locat_id">
                                            {{locat.it_locat_name}}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือก Location...!</div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="uname">สถานะถือครอง	 : </label>
                                    <select class="form-control" 
                                    v-model="input.handle.status"
                                    v-bind:class="{'is-invalid': input.handle.valid.status}"
                                    :disabled="input.handle.disabled.status">
                                        <option value="" disabled selected>กรุณาเลือก...</option>
                                        <option value="1">ถือครอง</option>
                                        <option value="2">สิ้นสุด</option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือก สถานะถือครอง...!</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="uname">วันที่เริ่มต้นใช้งาน : </label>
                                    <date-picker value-type="format" format="YYYY-MM-DD" 
                                    v-model="input.handle.sdate"
                                    v-bind:class="{'is-invalid': input.handle.valid.sdate}"
                                    :disabled="input.handle.disabled.sdate"></date-picker>
                                    <div class="invalid-feedback">กรุณาใส่ วันที่เริ่มต้นใช้งาน...!</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="uname">วันที่สิ้นสุดใช้งาน : </label>
                                    <date-picker value-type="format" format="YYYY-MM-DD" 
                                    v-model="input.handle.edate"
                                    v-bind:class="{'is-invalid': input.handle.valid.edate}"
                                    :disabled="input.handle.disabled.edate"></date-picker>
                                    <div class="invalid-feedback">กรุณาใส่ วันที่เริ่มต้นใช้งาน...!</div>
                                </div>
                            </div>
                            <div class="row justify-content-md-center m-3">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-success w-100"
                                    v-on:click="saveLicenseHandle()"
                                    v-html="input.handle.content.btnSave"
                                    :disabled="input.handle.disabled.btnSave">
                                        <i class="fas fa-save"></i> บันทึก
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-danger w-100" 
                                    data-dismiss="modal" aria-label="Close"
                                    :disabled="input.handle.disabled.btnClose">
                                        <i class="fas fa-ban"></i> ยกเลิก
                                    </button>
                                </div>
                            </div>
                        </form>
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
        this.getMasterLocation();
        this.getAssetHere();
        this.getSoftwareLicense();
    },
    data(){
        return{
            content: {
                tabLicense: true,
                tabHandle: false,
                panelLicense: true,
                panelHandle: false,
                panelHandleProgress: false,
                panelHandleTable: false,
                assetStatus: ''
            },
            input: {
                license: {
                    method: '',
                    licenseId: '',
                    text: '',
                    volume: 1,
                    status: 1,
                    content: {
                        title: '',
                        btnSave: '<i class="fas fa-save"></i> บันทึก'
                    },
                    valid: {
                        text: false,
                        volume: false
                    },
                    disabled: {
                        text: false,
                        volume: false,
                        status: false,
                        btnSave: false,
                        btnClose: false
                    }
                },
                handle: {
                    method: '',
                    handleId: '',
                    licenseId: '',
                    searchEmp: '',
                    empName: '',
                    deptName: '',
                    location: '',
                    status: '',
                    sdate: '',
                    edate: '',
                    disabled: {
                        btnSearch: false,
                        searchEmp: false,
                        location: false,
                        status: false,
                        sdate: false,
                        edate: false,
                        btnSave: true,
                        btnClose: false
                    },
                    valid:{
                        empId: false,
                        location: false,
                        status: false,
                        edate: false,
                        sdate: false
                    },
                    content: {
                        btnSave: '<i class="fas fa-save"></i> บันทึก'
                    }
                }
            },
            location: {},
            software: {},
            license: {},
            handle: []            
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
        getToday(){
            let date = new Date();
            return date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+(date.getDate())).slice(-2);
        },
        getMasterLocation(){
            axios.get('/api/admin/it/location')
            .then(response => {
                this.location = response.data;
            });
        },
        setPanel(panel){
            this.content.tabLicense = this.content.panelLicense = panel == 1 ? true : false;
            this.content.tabHandle = this.content.panelHandle = panel == 2 ? true: false;
            if(panel == 2) this.getListLicenseHandle();
        },
        getAssetHere(){
            axios.get('/api/admin/it/asset/'+this.id)
            .then(response => {

                this.software = response.data;

                if( ['ใช้งาน','ยืมใช้งาน','สำรอง'].indexOf(this.software.status_name) >= 0 )
                    var colorStatus = 'text-success';
                else if( ['ส่งซ่อม'].indexOf(this.software.status_name) >= 0 )
                    var colorStatus = 'text-warning';
                else if( ['ไม่ใช้งาน','ออกจำหน่าย'].indexOf(this.software.status_name) >= 0 )
                    var colorStatus = 'text-danger';

                this.content.assetStatus = '<i class="fas fa-circle mr-2 '+colorStatus+'"></i> '+this.software.status_name;     
                
            });
        },
        getSoftwareLicense(){
            axios.get('/api/admin/it/softwarelicense/list/'+this.id)
            .then(response => {
                this.license = response.data;
                if(this.license.length > 0)
                    this.input.handle.licenseId = this.license[0].swl_id;
            });
        },
        resetAddLicense(){
            this.input.license.valid.text = false;
            this.input.license.valid.volume = false;
            this.input.license.disabled.text = false;
            this.input.license.disabled.volume = false;
            this.input.license.disabled.status = false;
            this.input.license.disabled.btnSave = false;            
            this.input.license.disabled.btnClose = false;
            this.input.license.content.btnSave = '<i class="fas fa-save"></i> บันทึก';

        },
        toAddLicense(){
            this.input.license.content.title = 'เพิ่ม License';
            this.input.license.method = 'add';
            this.input.license.text = '';
            this.input.license.volume = 1;
            this.input.license.status = 1;
            this.resetAddLicense();
            $('#modal-license').modal('show');
        },
        toUpdateLicense(index){
            this.input.license.content.title = 'แก้ไข License';
            this.input.license.method = 'update';
            this.input.license.licenseId = this.license[index].swl_id;
            this.input.license.text = this.license[index].swl_license;
            this.input.license.volume = this.license[index].swl_volumes;
            this.input.license.status = this.license[index].swl_status;
            this.resetAddLicense();
            $('#modal-license').modal('show');
        },
        setSoftwareLicense(){

            // if(!this.checkSeessionLogin())
            //     return;

            this.input.license.valid.text =  this.input.license.text == '' ? true : false;
            this.input.license.valid.volume = this.input.license.volume == '' || this.input.license.volume <= 0 ? true : false;

            if(!this.input.license.valid.text && !this.input.license.valid.volume){

                this.input.license.disabled.text = true;
                this.input.license.disabled.volume = true;
                this.input.license.disabled.status = true;
                this.input.license.disabled.btnSave = true;
                this.input.license.disabled.btnClose = true;

                let btnContent = '<span class="spinner-border spinner-border-sm mr-3" role="status" aria-hidden="true"></span>';
                    btnContent += 'กำลังบันทึกข้อมูล';

                this.input.license.content.btnSave = btnContent;

                if(this.input.license.method == 'add'){
                    var link = '/api/admin/it/softwarelicense/save',
                        postData = {
                            hw_id: this.id,
                            license: this.input.license.text,
                            volumes: this.input.license.volume,
                            status: this.input.license.status
                        };
                }
                else{
                    var link = '/api/admin/it/softwarelicense/update',
                        postData = {
                            id: this.input.license.licenseId,
                            license: this.input.license.text,
                            volumes: this.input.license.volume,
                            status: this.input.license.status
                        };
                }

                axios.post(link,postData)
                .then(response => {
                    this.$swal({icon: 'success', text: 'บันทึก License เรียบร้อยแล้ว'});
                    this.getSoftwareLicense();
                    this.resetAddLicense();
                    $('#modal-license').modal('hide');
                })
                .catch(response => {
                    this.$swal({icon: 'error', text: 'ไม่สามารถบันทึก License ได้'});
                    this.input.license.disabled.text = false;
                    this.input.license.disabled.volume = false;
                    this.input.license.disabled.status = false;
                    this.input.license.disabled.btnSave = false;
                    this.input.license.disabled.btnClose = false;
                    this.input.license.content.btnSave = '<i class="fas fa-save"></i> บันทึก';
                });

            }

        },
        setDeleteLicense(index){

            // if(!this.checkSeessionLogin())
            //     return;

            this.$swal({
                title: 'ยืนยันการลบ License',
                text: 'ต้องการลบ License '+this.license[index].swl_license+' หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ลบ License',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed){
                    axios.get('/api/admin/it/softwarelicense/delete/'+this.license[index].swl_id)
                    .then(response => {
                        this.$swal({icon: 'success', text: 'ลบ License เรียบร้อยแล้ว'});
                        this.getSoftwareLicense();
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถลบ License ได้'});
                    });
                }
            });
        },
        toAddLicenseHandle(){          
            this.input.handle.method = 'create';
            this.input.handle.disabled.searchEmp = false;
            this.input.handle.disabled.btnSearch = false;
            this.input.handle.disabled.btnSave = false;
            this.input.handle.handleId = '';
            this.input.handle.searchEmp = '';
            this.input.handle.empName = '';
            this.input.handle.deptName = '';
            this.input.handle.location = '';
            this.input.handle.status = '';
            this.input.handle.sdate = '';
            this.input.handle.edate = ''; 
            $('#modal-handle').modal('show');
        },
        toUpdateLicenseHandle(index){

            const handle = this.handle[index];

            this.input.handle.method = 'update';
            this.input.handle.disabled.searchEmp = true;
            this.input.handle.disabled.btnSearch = true;
            this.input.handle.disabled.btnSave = false;

            this.input.handle.handleId = handle.handle_id;
            this.input.handle.searchEmp = handle.emp_id;
            this.input.handle.empName = handle.emp_name;
            this.input.handle.deptName = handle.dept_name;
            this.input.handle.location = handle.locat_id;
            this.input.handle.status = handle.handle_status;
            this.input.handle.sdate = handle.handle_sdate;
            this.input.handle.edate = handle.handle_edate !== null ? handle.handle_edate : ''; 

            $('#modal-handle').modal('show');

        },
        getListLicenseHandle(){

            if(this.license.length > 0){
                this.content.panelHandleProgress = true;
                this.content.panelHandleTable = false;

                axios.get('/api/admin/it/softwarelicense/handle/list/'+this.input.handle.licenseId)
                .then(response => {
                    this.content.panelHandleProgress = false;
                    this.content.panelHandleTable = true;
                    this.handle = response.data;
                });
            }


        },
        searchEmployee(){
            
            if(this.input.handle.searchEmp == ''){
                this.$swal({icon: 'error', text: 'กรุณาใส่รหัสผู้ถือครอง'});
                return;
            }

            axios.get('/api/admin/it/employee/search/fixed/'+this.input.handle.searchEmp)
            .then(response => {
                if(Object.keys(response.data).length == 0){
                    this.$swal({icon: 'error', text: 'ไม่พบข้อมูลผู้ถือครอง'});
                    this.input.handle.empName = '';
                    this.input.handle.deptName = '';
                    this.input.handle.location = '';
                    this.input.handle.status = '1';
                    this.input.handle.sdate = '';
                    this.input.handle.disabled.btnSave = true;
                }
                else{
                    this.input.handle.empName = response.data.it_emp_name_th;
                    this.input.handle.deptName = response.data.it_dept_name;
                    this.input.handle.location = response.data.it_locat_id;
                    this.input.handle.status = 1;
                    this.input.handle.sdate = this.getToday();
                    this.input.handle.disabled.btnSave = false;
                }
            });

        },
        saveLicenseHandle(){

            // if(!this.checkSeessionLogin())
            //     return;

            this.input.handle.valid.empId = this.input.searchEmp == '' ? true : false;
            this.input.handle.valid.location = this.input.lication == '' ? true : false;
            this.input.handle.valid.status = this.input.handle.status == '' ? true: false;
            this.input.handle.valid.sdate = this.input.handle.sdate == '' ? true : false;

            if(this.input.handle.status == 2)
                this.input.handle.valid.edate = this.input.handle.edate == '' ? true : false;
            
            if(
                !this.input.handle.valid.empId
                && !this.input.handle.valid.location
                && !this.input.handle.valid.status
                && !this.input.handle.valid.sdate
                && !this.input.handle.valid.edate
            ){

                this.input.handle.disabled.searchEmp = true;
                this.input.handle.disabled.btnSearch = true;
                this.input.handle.disabled.location = true;
                this.input.handle.disabled.status = true;
                this.input.handle.disabled.sdate = true;
                this.input.handle.disabled.edate = true;
                this.input.handle.disabled.btnSave = true;
                this.input.handle.disabled.btnClose = true;

                let btnContent = '<span class="spinner-border spinner-border-sm mr-3" role="status" aria-hidden="true"></span>';
                    btnContent += 'กำลังบีนทึกข้อมูล....';
                
                this.input.handle.content.btnSave = btnContent;

                if(this.input.handle.method == 'create'){
                    var link = '/api/admin/it/softwarelicense/handle/save',
                        postData = {
                            license_id: this.input.handle.licenseId,
                            emp_id: this.input.handle.searchEmp,
                            locat_id: this.input.handle.location,
                            handle_status: this.input.handle.status,
                            handle_sdate: this.input.handle.sdate,
                            handle_edate: this.input.handle.edate,
                            user: this.$session.get('user')
                        };
                }
                else{
                    var link = '/api/admin/it/softwarelicense/handle/update',
                        postData = {
                            handle_id: this.input.handle.handleId,
                            locat_id: this.input.handle.location,
                            handle_status: this.input.handle.status,
                            handle_sdate: this.input.handle.sdate,
                            handle_edate: this.input.handle.edate,
                            user: this.$session.get('user')
                        };
                }
                
                axios.post(link,postData)
                .then(response => {
                    if(response.data.status){
                        this.$swal({icon: 'success', text: response.data.msg});
                        $('#modal-handle').modal('hide');
                        this.clearFormHandle('success');
                        this.getListLicenseHandle();
                    }
                    else{
                        this.$swal({icon: 'error', text: response.data.msg});
                        this.clearFormHandle('fail');
                    }
                })
                .catch(response => {
                    this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกผู้ถือครอง License ได้'});
                    this.clearFormHandle('fail');
                });

            }
        },
        clearFormHandle(method){

            if(method == 'create'){
                this.input.handle.licenseId ='';
                this.input.handle.searchEmp = '';
                this.input.handle.empName = '';
                this.input.handle.deptName = '';
                this.input.handle.location = '';
                this.input.handle.status = '';
                this.input.handle.sdate = '';
                this.input.handle.edate = '';
            }

            this.input.handle.valid.empId = false;
            this.input.handle.valid.location = false;
            this.input.handle.valid.status = false;
            this.input.handle.valid.sdate = false;
            this.input.handle.valid.edate = false;
            this.input.handle.disabled.searchEmp = false;
            this.input.handle.disabled.btnSearch = false;
            this.input.handle.disabled.location = false;
            this.input.handle.disabled.status = false;
            this.input.handle.disabled.sdate = false;
            this.input.handle.disabled.edate = false;
            this.input.handle.disabled.btnSave = false;
            this.input.handle.disabled.btnClose = false;
            this.input.handle.content.btnSave = '<i class="fas fa-save"></i> บันทึก';

        },
        setDeleteLicenseHandle(index){

            // if(!this.checkSeessionLogin())
            //     return;
                
            this.$swal({
                title: 'ยืนยันการลบผู้ถือครอง',
                text: "ต้องการลบผู้ถือครอง "+this.handle[index].emp_name+" หรือไม่",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ลบข้อมูล',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.get('/api/admin/it/softwarelicense/handle/delete/'+this.handle[index].handle_id)
                    .then(response => {
                        this.$swal({icon: 'success', text: 'ลบผู้ถือครองเรียบร้อยแล้ว'});
                        this.getListLicenseHandle();
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถลบผู้ถือครองได้'});
                    });
                }
            })
        }
    }
}
</script>