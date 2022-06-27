<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการข้อมูลพนักงาน</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-inline">
                            <input class="form-control form-control-sm mr-sm-2" type="search" id="searchTbTxt" 
                            placeholder="กรอกคำค้นหา..." aria-label="Search"
                                v-model="search"
                                v-on:keyup.enter="getAllEmployees()"
                            >
                            <button class="btn btn-sm btn-amado my-2 my-sm-0" type="button"
                                v-on:click="getAllEmployees()"
                            >
                                <i class="fas fa-search"></i>
                                <span class="ml-2">ค้นหา</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-sm btn-success float-right" v-on:click="setModelAction('create','')">
                            <i class="fas fa-plus"></i>
                            <span class="ml-2">เพิ่มพนักงาน</span>
                        </button>
                    </div>
                </div>
                <table class="table table-sm table-hover" id="tblEmp">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">รหัสพนักงาน</th>
                            <th scope="col">ชื่อ-สกุล</th>
                            <th scope="col">ชื่อเล่น</th>
                            <th scope="col">เบอร์โทร</th>
                            <th scope="col">อีเมล์</th>                            
                            <th scope="col">ฝ่าย</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(emp,index) in employees" :key="emp.index">
                            <th scope="row">{{emp.index}}</th>
                            <td>{{emp.it_emp_id}}</td>
                            <td>{{emp.it_emp_name_th}} {{emp.it_emp_surname_th}}</td>
                            <td>{{emp.it_emp_nickname_th}}</td>
                            <td>{{emp.it_emp_tel}}</td>
                            <td>{{emp.it_emp_email_amd}}</td>                            
                            <td>{{emp.it_dept_name}}</td>                            
                            <td>{{emp.emp_active}}</td>
                            <td class="text-center">    
                                <button type="button" class="btn btn-secondary btn-sm" v-on:click="setEmpRole(index,emp.it_emp_id)"
                                title="รายการกำหนดสิทธิ์ผู้ใช้">
                                    <i class="fas fa-user-shield"></i>
                                </button>                            
                                <button type="button" class="btn btn-primary btn-sm" v-on:click="getAssetHw(index)"
                                title="รายการถือครองทรัพย์สิน">
                                    <i class="fas fa-user-tag"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" v-on:click="getFixedIdEmployees(emp.it_emp_id)"
                                title="แก้ไขพนักงาน">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" v-on:click="setEmpRemove(index,emp.it_emp_id)"
                                title="ลบพนักงาน">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">                
                <nav aria-label="Page navigation example">
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

        <!-- Modal : #modal-progress -->
        <div class="modal fade" id="modal-progress" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <i class="fab fa-windows"> System message</i>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-show="progress.shwBtnClose"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center" v-html="progress.content">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-employee -->
        <div class="modal fade" id="modal-employee" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-html="modal.title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            :disabled="btn.disabledBtnModalClose"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row m-3">
                                <div class="form-group col-md-3">
                                    <label for="uname">รหัสพนักงาน : </label>
                                    <input type="text" class="form-control" placeholder="กรอกรหัสพนักงาน..."
                                        v-model="modal.emp_id" 
                                        v-bind:class="{'is-invalid':vlField.validEmpId}" 
                                        :readonly="field.empId"
                                    >
                                    <div class="invalid-feedback">กรุณากรอกรหัสพนักงาน...!</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="uname">ฝ่าย : </label>
                                    <select class="form-control" 
                                        v-model="modal.dept_id"
                                        v-bind:class="{'is-invalid':vlField.validDeptId}"
                                    >
                                        <option value="" disabled selected>กรุณาเลือก...</option>
                                        <option v-for="dept in departments" :key="dept.it_dept_id" :value="dept.it_dept_id">
                                            {{dept.it_dept_name}}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือกฝ่าย...!</div>
                                </div>
                            </div>
                            <hr class="pb-3">
                            <div class="row m-3">
                                <div class="form-group col-md-4">
                                    <label for="uname">ชื่อ (ไทย) : </label>
                                    <input type="text" class="form-control" placeholder="กรอกชื่อ..." 
                                        v-model="modal.emp_name_th"
                                        v-bind:class="{'is-invalid':vlField.validEmpNameTh}"
                                    >
                                    <div class="invalid-feedback">กรุณากรอกชื่อ...!</div>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="uname">นามสกุล (ไทย) : </label>
                                    <input type="text" class="form-control" placeholder="กรอกนามสกุล..." 
                                        v-model="modal.emp_surname_th"
                                        v-bind:class="{'is-invalid':vlField.validEmpSurnameTh}"
                                    >
                                    <div class="invalid-feedback">กรุณากรอกนามสกุล..!</div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="uname">ชื่อเล่น (ไทย) : </label>
                                    <input type="text" class="form-control" placeholder="กรอกชื่อเล่น..." v-model="modal.emp_nickname_th">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="uname">name (English) : </label>
                                    <input type="text" class="form-control" placeholder="Enter name..." v-model="modal.emp_name_eng">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="uname">Surname (English) : </label>
                                    <input type="text" class="form-control" placeholder="Enter Surname..." v-model="modal.emp_surname_eng">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="uname">Nickname (English) : </label>
                                    <input type="text" class="form-control" placeholder="Enter Nickname..." v-model="modal.emp_nickname_eng">
                                </div>
                            </div>
                            <div class="row m-3">
                                <div class="form-group col-md-4">
                                    <label for="uname">เพศ : </label>
                                    <select class="form-control" 
                                        v-model="modal.emp_gender"
                                        v-bind:class="{'is-invalid':vlField.validEmpGender}"
                                    >
                                        <option value="" disabled selected>กรุณาเลือก...</option>
                                        <option value="M">ชาย</option>
                                        <option value="F">หญิง</option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือกเพศ..!</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="uname">เบอร์โทรศัพท์ : </label>
                                    <input type="text" class="form-control" placeholder="กรอกเบอร์โทรศัพท์..." v-model="modal.emp_tel">
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="uname">E-Mail Amado : </label>
                                    <input type="email" class="form-control" placeholder="กรอก E-Mail Amado..." v-model="modal.emp_email_amd">
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="uname">E-Mail สำรอง : </label>
                                    <input type="email" class="form-control" placeholder="กรอก E-Mail สำรอง..." v-model="modal.emp_email_second">
                                </div>
                            </div>
                            <div class="row m-3">
                                <div class="form-group col-md-3">
                                    <label>สถานะ</label>
                                    <select class="form-control"
                                        v-model="modal.emp_active"
                                    >
                                        <option value="1" selected>Active</option>
                                        <option value="0" >Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Location</label>
                                    <select class="form-control"
                                        v-model="modal.emp_location"
                                        v-bind:class="{'is-invalid':vlField.validEmpLocation}"
                                    >
                                        <option value="" selected disabled>เลือก Location</option>
                                        <option v-for="locat in location" :key="locat.it_locat_id" :value="locat.it_locat_id" >
                                            {{locat.it_locat_name}}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือก Location..!</div>
                                </div>
                            </div>
                            <div class="row justify-content-md-center m-3">                                
                                <div class="col-md-4" v-show="modal.action == 'edit'">
                                    <button type="button" class="btn btn-primary w-100" 
                                        v-on:click="resetPasswore()"
                                    >
                                        <i class="fas fa-key"></i> Reset password
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-success w-100" 
                                        v-html="btn.save" 
                                        v-on:click="setEmpSave()"
                                        :disabled="btn.disabledBtnModalSave"
                                    >
                                        <i class="fas fa-save"></i> บันทึก
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100" data-dismiss="modal" aria-label="Close"
                                        :disabled="btn.disabledBtnModalClose"
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

        <!-- Modal : #modal-remove -->
        <div class="modal fade" id="modal-remove" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <i class="fas fa-clipboard-check"> ยืนยันการลบข้อมูลพนักงาน</i>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-html="modal.deleteContent">
                    </div>
                    <div class="modal-footer">            
                        <button type="button" class="btn btn-primary"
                            v-on:click="setDeleteEmployee()"
                        >
                            <i class="fas fa-trash"></i> ยืนยัน
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i> ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-asset-hw -->
        <div class="modal fade" id="modal-asset-hw" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fas fa-user-tag text-primary"></i> <span class="ml-2">รายการถือครองทรัพย์สิน</span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-html="modal.assetHwContent">
                    </div>
                    <table class="table table-sm table-hover" id="tblEmp">
                        <thead>
                            <tr>
                                <th class="text-center" colspan="6">
                                    Asset
                                </th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Fixed asset number</th>
                                <th scope="col">ทรัพย์สิน</th>
                                <th scope="col">ประเภท</th>
                                <th scope="col">Location</th>
                                <th scope="col">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="( asst, index ) in assetHardeare" :key="index">
                                <th scope="row" class="text-center">{{index+1}}</th>
                                <td>
                                    <a class="text-decoration-none"
                                    v-bind:href="'/admin/asset/'+asst.it_hw_seq" v-bind:target="'_blank'">
                                    {{asst.it_hw_number}}
                                    <i class="fas fa-search"></i></a>
                                </td>
                                <td>{{asst.it_hw_name}}</td>
                                <td>{{asst.it_hw_type_name}}</td>
                                <td>{{asst.it_locat_name}}</td>
                                <td>{{(asst.it_asst_hw_status == 1 ? 'กำลังถือคลอง' : 'เคยถือครอง' )}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-sm table-hover" id="tblEmp">
                        <thead>
                            <tr>
                                <th class="text-center" colspan="6">
                                    Software License
                                </th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Fixed asset number</th>
                                <th scope="col">Soltware</th>
                                <th scope="col">ประเภท</th>
                                <th scope="col">License</th>
                                <th scope="col">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(lc, index) in licenseHardware" :key="index">
                                <th class="text-center">{{index+1}}</th>
                                <td>
                                    <a class="text-decoration-none"
                                    v-bind:href="'/admin/sofware-license/'+lc.it_hw_seq" v-bind:target="'_blank'">
                                    {{lc.it_hw_number}}
                                    <i class="fas fa-search"></i></a>
                                </td>
                                <td>{{lc.it_hw_name}}</td>
                                <td>{{lc.it_hw_type_name}}</td>
                                <td>{{lc.swl_license}}</td>
                                <td>{{lc.swl_status}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="modal-footer">   
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i> ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-role -->
        <div class="modal fade" id="modal-role" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-white bg-primary">
                        <h4 class="modal-title"><i class="fas fa-user-shield"></i> <span class="ml-2">รายการกำหนดสิทธิ์ผู้ใช้</span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="menus-role-tab" data-toggle="tab" href="javascript:;" role="tab" aria-selected="true"
                                        v-on:click="getRoleContent(1)"
                                    >การเข้าถึงเมนู</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pages-role-tab" data-toggle="tab" href="javascript:;" role="tab" aria-selected="false"
                                        v-on:click="getRoleContent(2)"
                                    >การเข้าถึงเพจและสิทธิ์การใช้งาน</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body tab-content">
                            <div class="tab-pane fade show active">
                                พนักงาน <strong class="mr-3" v-html="role.emp_name">กิตติคุณ สุขสำราญ</strong> 
                                Username: <strong class="mr-3" v-html="role.username">100025</strong>
                            </div>
                            <div class="tab-pane fade mt-3" id="#menus-role"
                                v-bind:class="{
                                    'show': role.menusRoleShowClass,
                                    'active': role.menusRoleActiveClass
                                }"
                            >
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <th class="text-center">#</th>
                                        <th>เมนู</th>
                                        <th>กลุ่มเมนู</th>
                                        <th>ประเภทเมนู</th> 
                                        <th>เมนูหลัก</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(menus,index) in role.list.menu" :key="index">
                                            <th class="text-center">{{index+1}}</th>
                                            <td>{{menus.menu_name}}</td>
                                            <td>{{menus.menu_group}}</td>
                                            <td>{{menus.menu_type}}</td>
                                            <td>{{menus.menu_main}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade  mt-3" id="#pages-role"
                                v-bind:class="{
                                    'show': role.pagesRoleShowClass,
                                    'active': role.pagesRoleActionClass
                                }"
                            >
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Page</th>
                                            <th>Page URL</th>
                                            <th class="text-center">Read</th>
                                            <th class="text-center">Create</th>
                                            <th class="text-center">Update</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(pages,index) in role.list.page" :key="index">
                                            <th class="text-center">{{index+1}}</th>
                                            <td>{{pages.page_name}}</td>
                                            <td>{{pages.page_url}}</td>
                                            <td class="text-center">
                                                <i class="fas fa-check-circle text-success"
                                                    v-show="pages.ac_read == 1"
                                                ></i>
                                                <i class="fas fa-times-circle text-danger"
                                                    v-show="pages.ac_read == 0"
                                                ></i>
                                            </td>
                                            <td class="text-center">
                                                <i class="fas fa-check-circle text-success"
                                                    v-show="pages.ac_create == 1"
                                                ></i>
                                                <i class="fas fa-times-circle text-danger"
                                                    v-show="pages.ac_create == 0"
                                                ></i>
                                            </td>
                                            <td class="text-center">
                                                <i class="fas fa-check-circle text-success"
                                                    v-show="pages.ac_update == 1"
                                                ></i>
                                                <i class="fas fa-times-circle text-danger"
                                                    v-show="pages.ac_update == 0"
                                                ></i>
                                            </td>
                                            <td class="text-center">
                                                <i class="fas fa-check-circle text-success"
                                                    v-show="pages.ac_delete == 1"
                                                ></i>
                                                <i class="fas fa-times-circle text-danger"
                                                    v-show="pages.ac_delete == 0"
                                                ></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">   
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-ban"></i> ยกเลิก
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

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    mounted(){
        this.getStartPage();        
    },
    data(){
        return{
            progress:{
                content: '',
                shwBtnClose: false
            },
            modal: {
                title: '', 
                action: '',
                emp_id: '', dept_id: '',
                emp_name_th: '', emp_surname_th: '', emp_nickname_th: '',  
                emp_name_eng: '', emp_surname_eng: '', emp_nickname_eng: '',
                emp_gender: '', emp_tel: '',
                emp_email_amd: '', emp_email_second: '',
                emp_active: 0, emp_location: '',
                deleteContent: '',
                assetHwContent: ''
            },
            field: {
                searchTableTxt: '',
                deleteEmpId: '',
                empId: false
            },
            vlField: {
                validEmpId: false,
                validDeptId: false,
                validEmpNameTh: false,
                validEmpSurnameTh: false,
                validEmpGender: false,
                validEmpLocation: false
            },            
            btn: {
                save: '',
                disabledBtnModalSave: false,
                disabledBtnModalClose: false
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
            },
            role: {
                menusRoleShowClass: true,
                menusRoleActiveClass: true,
                pagesRoleShowClass: false,
                pagesRoleActionClass: false,
                emp_name: '',
                username: '',
                list: {
                    menu: [],
                    page: []
                }
            },
            search: '',
            departments: [],
            employees: [],
            assetHardeare: [],
            licenseHardware: [],
            location: []
        }
    },
    methods: {
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
        getStartPage(){ 
            this.getAllDepartments();
            this.getAllLocation();
            this.getAllEmployees('startPage');            
        },
        getAllLocation(){
            axios.get('/api/admin/it/location')
            .then(response => {
                this.location = response.data;
            });
        },
        getAllDepartments(){
            axios.get('/api/admin/it/department')
            .then(response => {
                this.departments = response.data;
            });
        },
        getAllEmployees(method){
            axios.post('/api/admin/it/employee/search',{
                keyword: this.search
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            });
        },
        getFixedIdEmployees(empId){
            axios.get('/api/admin/it/employee/'+empId+'/edit')
            .then(response => {
                this.setModelAction('edit',response.data);
            });
        },
        getDataPaginate(page){

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post(this.paginate.url,{
                keyword: this.search
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
            this.employees = resultData;

        },
        getAssetHw(arrIndex){

            $('#modal-asset-hw').modal('show');

            var contentStr = '<strong>รหัสพนักงาน</strong> '+this.employees[arrIndex].it_emp_id;
            contentStr += ' <strong>ชื่อ-นามสกุล</strong> '+this.employees[arrIndex].it_emp_name_th;
            contentStr += ' '+this.employees[arrIndex].it_emp_surname_th;

            this.modal.assetHwContent = contentStr;

            axios.get('/api/admin/it/employee/assethardware/'+this.employees[arrIndex].it_emp_id)
            .then(response => {
                this.assetHardeare = response.data.asset;
                this.licenseHardware = response.data.license;
            });

        },
        setModelAction(method,dataset){

            $('#modal-employee').modal('show');

            this.modal.action = method;
            this.field.empId = ( method == 'create' ? false : true );

            this.vlField.validEmpId = false;
            this.vlField.validDeptId = false;
            this.vlField.validEmpNameTh = false;
            this.vlField.validEmpSurnameTh = false;
            this.vlField.validEmpGender = false;
            this.vlField.validEmpLocation = false;
            this.btn.disabledBtnModalSave = false;
            this.btn.disabledBtnModalClose = false;

            this.modal.emp_id = ( method == 'edit' ? dataset.it_emp_id : '' );
            this.modal.dept_id =  ( method == 'edit' ? dataset.it_dept_id : '' );
            this.modal.emp_name_th = ( method == 'edit' ? dataset.it_emp_name_th : '' );
            this.modal.emp_surname_th = ( method == 'edit' ? dataset.it_emp_surname_th : '' );
            this.modal.emp_nickname_th = ( method == 'edit' ? dataset.it_emp_nickname_th : '' );
            this.modal.emp_name_eng = ( method == 'edit' ? dataset.it_emp_name_eng : '' );
            this.modal.emp_surname_eng = ( method == 'edit' ? dataset.it_emp_surname_eng : '' );
            this.modal.emp_nickname_eng = ( method == 'edit' ? dataset.it_emp_nickname_eng : '' );
            this.modal.emp_gender = ( method == 'edit' ? dataset.it_emp_gender : '' );
            this.modal.emp_tel = ( method == 'edit' ? dataset.it_emp_tel : '' );
            this.modal.emp_email_amd = ( method == 'edit' ? dataset.it_emp_email_amd : '' );
            this.modal.emp_email_second = ( method == 'edit' ? dataset.it_emp_email_second : '' );
            this.modal.emp_active = ( method == 'edit' ? dataset.it_emp_active : 1 );
            this.modal.emp_location = ( method == 'edit' ? dataset.it_locat_id : '' );

            if( method == 'create' ){
                this.btn.save = '<i class="fas fa-save"></i> บันทึก';
                this.modal.title = '<i class="fas fa-plus text-success"></i> <span class="ml-2">เพิ่มพนักงาน</span>';      
            }      
            else{
                this.btn.save = '<i class="fas fa-save"></i> แก้ไข';
                this.modal.title = '<i class="fas fa-pen text-warning"></i> <span class="ml-2">แก้ไขพนักงาน</span>';      
            }      

        },
        setEmpSave(){

            if(!this.checkSeessionLogin())
                return;
            
            this.btn.disabledBtnModalSave = true;
            this.btn.disabledBtnModalClose = true;

            var strBtnSave = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ระบบกำลังบันทึกข้อมูล...';
            this.btn.save =  strBtnSave;

            this.vlField.validEmpId = ( this.modal.emp_id == '' ? true : false );
            this.vlField.validDeptId = ( this.modal.dept_id == '' ? true : false );
            this.vlField.validEmpNameTh = ( this.modal.emp_name_th == '' ? true : false );
            this.vlField.validEmpSurnameTh = ( this.modal.emp_surname_th == '' ? true : false );
            this.vlField.validEmpGender = ( this.modal.emp_gender == '' ? true : false );
            this.vlField.validEmpLocation = ( this.modal.emp_location == '' ? true : false );
            
            if( 
                !this.vlField.validEmpId && 
                !this.vlField.validDeptId && 
                !this.vlField.validEmpNameTh && 
                !this.vlField.validEmpSurnameTh && 
                !this.vlField.validEmpGender && 
                !this.vlField.validEmpLocation  
            ){

                $('#modal-employee').modal('hide');

                var contentAction = ( this.modal.action == 'create' ? 'ระบบกำลังบันทึกข้อมูล' : 'ระบบกำลังอัพเดทข้อมูล' );
                var pcontent = '<div class="progress">';
                pcontent += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"';
                pcontent += 'aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';
                pcontent += '</div> <strong>'+contentAction+'</strong>';
                
                this.progress.content = pcontent;
                this.progress.shwBtnClose = false;
                $('#modal-progress').modal('show');

                if( this.modal.action == 'create' )
                    this.setCreateEmployee();
                else
                    this.setUpdateEmployee();

            }
            else{
                
                if( this.modal.action == 'create' )
                    this.btn.save = '<i class="fas fa-save"></i> บันทึก';   
                else
                    this.btn.save = '<i class="fas fa-save"></i> แก้ไข';     
            
                this.btn.disabledBtnModalSave = false;
                this.btn.disabledBtnModalClose = false;
                
            }
            
        },
        setEmpRemove(arrIndex,empId){

            $('#modal-remove').modal('show');

            var contentStr = 'คุณต้องการลบข้อมูลพนักงาน '+empId;
            contentStr += ' '+this.employees[arrIndex].it_emp_name_th+' '+this.employees[arrIndex].it_emp_surname_th;
            contentStr += ' หรือไม่';

            this.modal.deleteContent = contentStr;
            this.field.deleteEmpId = empId

        },
        setCreateEmployee(){
            axios.post('/api/admin/it/employee',{
                emp_id: this.modal.emp_id, 
                dept_id: this.modal.dept_id,
                emp_name_th: this.modal.emp_name_th, 
                emp_surname_th: this.modal.emp_surname_th, 
                emp_nickname_th: this.modal.emp_nickname_th,  
                emp_name_eng: this.modal.emp_name_eng, 
                emp_surname_eng: this.modal.emp_surname_eng, 
                emp_nickname_eng: this.modal.emp_nickname_eng,
                emp_gender: this.modal.emp_gender, 
                emp_tel: this.modal.emp_tel,
                emp_email_amd: this.modal.emp_email_amd, 
                emp_email_second: this.modal.emp_email_second,
                emp_active: this.modal.emp_active,
                emp_location: this.modal.emp_location
            })
            .then(response => {
                if(response.data.status){
                    var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบบันทึกข้อมูลเรียบร้อยแล้ว';
                    this.getAllEmployees('responseInsert');
                }
                else{
                    if(response.data.exception == 'dataExist')
                        var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> '+response.data.message;
                    else if(response.data.exception == 'sqlError'){
                        var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';
                        pcontent += '<br><br><b><font color="red">[Error]</font></b><br>';
                        pcontent += response.data.message;
                    }
                }                
                this.progress.content = pcontent;
                this.progress.shwBtnClose = true;
            })
            .catch(response => {
                var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';
                this.progress.content = pcontent;
                this.progress.shwBtnClose = true;
            });
        },
        setUpdateEmployee(){
            axios.put('/api/admin/it/employee/'+this.modal.emp_id,{
                dept_id: this.modal.dept_id,
                emp_name_th: this.modal.emp_name_th, 
                emp_surname_th: this.modal.emp_surname_th, 
                emp_nickname_th: this.modal.emp_nickname_th,  
                emp_name_eng: this.modal.emp_name_eng, 
                emp_surname_eng: this.modal.emp_surname_eng, 
                emp_nickname_eng: this.modal.emp_nickname_eng,
                emp_gender: this.modal.emp_gender, 
                emp_tel: this.modal.emp_tel,
                emp_email_amd: this.modal.emp_email_amd, 
                emp_email_second: this.modal.emp_email_second,
                emp_active: this.modal.emp_active,
                emp_location: this.modal.emp_location
            })
            .then(response => {
                if(response.data.status){
                    var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบอัพเดทข้อมูลเรียบร้อยแล้ว';                
                    this.getAllEmployees('responseUpdate');
                }
                else{
                    var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';
                        pcontent += '<br><br><b><font color="red">[Error]</font></b><br>';
                        pcontent += response.data.message;
                }
                this.progress.content = pcontent;
                this.progress.shwBtnClose = true;
            })
            .catch(response => {
                var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถอัพเดทข้อมูลได้';
                this.progress.content = pcontent;
                this.progress.shwBtnClose = true;
            });
        },
        setDeleteEmployee(){

            if(!this.checkSeessionLogin())
                return;

            $('#modal-remove').modal('hide');

            var pcontent = '<div class="progress">';
            pcontent += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"';
            pcontent += 'aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';
            pcontent += '</div> <strong>ระบบกำลังลบข้อมูล</strong>';
            
            this.progress.content = pcontent;
            this.progress.shwBtnClose = false;
            $('#modal-progress').modal('show');

            axios.delete('/api/admin/it/employee/'+this.field.deleteEmpId)
            .then(response => {
                if( response.data.Status ){
                    var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบลบข้อมูลเรียบร้อยแล้ว';
                    this.progress.content = pcontent;
                    this.progress.shwBtnClose = true;
                    this.getAllEmployees('responseDelete');
                }
                else{
                    var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> '+response.data.ErrMessage;
                    this.progress.content = pcontent;
                    this.progress.shwBtnClose = true;
                }
            })
            .catch(response => {
                var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถลบข้อมูลได้';
                this.progress.content = pcontent;
                this.progress.shwBtnClose = true;
            });

        },
        resetPasswore(){

            if(!this.checkSeessionLogin())
                return;
                
            axios.get('/api/admin/it/user/resetpassword/'+this.modal.emp_id)
            .then(response => {
                this.$swal({
                    icon: 'success',
                    title: 'Reset password เรียบร้อยแล้ว',
                });
            })
            .catch(response => {
                this.$swal({
                    icon: 'error',
                    title: 'ไม่สามารถ Reset password ได้!',
                });
            });
        },
        getRoleContent(channel){
            if( channel == 1 ){
                this.role.menusRoleShowClass = true;
                this.role.menusRoleActiveClass = true;
                this.role.pagesRoleShowClass = false;
                this.role.pagesRoleActionClass = false;
            }
            else{
                this.role.menusRoleShowClass = false;
                this.role.menusRoleActiveClass = false;
                this.role.pagesRoleShowClass = true;
                this.role.pagesRoleActionClass = true;
            }
        },
        setEmpRole(index,empId){

            $('#modal-role').modal('show');

            axios.post('/api/admin/role/show/employee',{
                emp_name: empId
            })
            .then(response => {
                this.role.emp_name = response.data.emp_name;
                this.role.username = response.data.user;
                this.role.list.menu = response.data.menus;
                this.role.list.page = response.data.pages;
            });

        }
    }
}
</script>