<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-user-shield"></i><span class="ml-2">การกำหนดสิทธิ์เข้าถึงการใช้งาน</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:;"
                                        v-bind:class="{'active': tab.menuOne}"
                                        v-on:click="setDataTab(1)">
                                            <i class="fas fa-caret-square-down"></i> <b>Menu</b>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:;"
                                        v-bind:class="{'active': tab.menuTwo}"
                                        v-on:click="setDataTab(2)">
                                            <i class="fab fa-chrome"></i> <b>Page</b>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="row" v-show="progress.main">
                                    <div class="col-12 text-center text-primary">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                        </div>
                                        <b>ระบบกำลังดึงข้อมูล...</b>
                                    </div>
                                </div>
                                <div class="row" v-show="!progress.main && tab.panelMenus">
                                    <div class="col-12">
                                        <div class="accordion" id="accordionRole">
                                            <div class="card" v-for="(m,index) in menus" :key="index">
                                                <div class="card-header" :id="'heading'+index">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                        data-toggle="collapse" aria-expanded="true"
                                                        :data-target="'#collapse'+index"
                                                        :aria-controls="'collapse'+index"
                                                        v-on:click="getMenuRole(index,m.menu_id)">
                                                            Menu #{{index+1}} <b>{{m.menu_name}}</b>
                                                            <i class="fas fa-ellipsis-h float-right"></i>
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div :id="'collapse'+index" class="collapse" :aria-labelledby="'heading'+index" data-parent="#accordionRole"
                                                v-bind:class="{'show': index == 0}">
                                                    <div class="card-body">
                                                        <div class="row mb-4">
                                                            <div class="col-12">
                                                                <span class="mr-2 font-weight-bolder">เมนู</span>
                                                                <b class="text-primary">{{m.menu_name}}</b>
                                                                <span class="ml-2 mr-2 font-weight-bolder">กลุ่มเมนู</span>
                                                                <b class="text-primary">{{m.menu_group}}</b>
                                                                <span class="ml-2 mr-2 font-weight-bolder">ประเภทเมนู</span>
                                                                <b class="text-primary">{{m.menu_type}}</b>
                                                                <span class="ml-2 mr-2 font-weight-bolder">เมนูหลัก</span>
                                                                <b class="text-primary">{{m.menu_host}}</b>
                                                            </div>
                                                        </div>
                                                        <p class="font-weight-bolder">สิทธิ์เข้าถึง</p>
                                                        <hr style="margin-top: -15px;">
                                                        <div class="row mb-2">
                                                            <div class="col-12">
                                                                <button type="button" class="btn btn-primary btn-sm"
                                                                v-on:click="addMenuRole()">
                                                                    <i class="fas fa-plus-circle mr-2"></i> เพิ่มสิทธิ์เข้าถึง
                                                                </button>
                                                            </div>
                                                            <div class="col-12 text-center text-primary mt-2" v-show="progress.menusRole">
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                    role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                                                    aria-valuemax="100" style="width: 100%"></div>
                                                                </div>
                                                                <b>ระบบกำลังดึงข้อมูล...</b>
                                                            </div>
                                                        </div>
                                                        <div class="row" v-show="!progress.menusRole">
                                                            <div class="col-md-3 col-sm-12 col-xs-12 mb-2"
                                                            v-for="(rm,index) in menusRole" :key="index">


                                                                <div class="input-group input-group-sm">

                                                                    <div class="input-group-prepend" v-show="rm.inactive == 0">
                                                                        <span class="input-group-text font-weight-bolder">
                                                                            {{rm.check_type}}
                                                                        </span>
                                                                    </div>

                                                                    <select class="custom-select rounded-left"
                                                                            v-show="rm.inactive != 0"
                                                                            v-model="rm.check_type">
                                                                        <option value="identity" selected>Identity</option>
                                                                        <option value="role">Role</option>
                                                                        <option value="department">Department</option>
                                                                        <option value="fixed">Fixed user</option>
                                                                    </select>

                                                                    <input type="text" class="form-control font-weight-bolder rounded-0"
                                                                           v-model="rm.check_value"
                                                                           :readonly="rm.inactive == 0">

                                                                    <div class="input-group-prepend">
                                                                        <button class="btn btn-outline-primary rounded-right" type="button"
                                                                                v-show="rm.inactive != 0"
                                                                                v-on:click="saveMenuRole(index)">
                                                                            <i class="fas fa-save"></i>
                                                                        </button>
                                                                        <button class="btn btn-outline-danger rounded-right" type="button"
                                                                                v-show="rm.inactive == 0"
                                                                                v-on:click="removeMenuRole(index)">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </div>

                                                                </div>





<!--                                                                <div class="input-group input-group-sm">-->
<!--                                                                    <div class="input-group-prepend" v-show="rm.inactive == 0">-->
<!--                                                                        <span class="input-group-text font-weight-bolder">-->
<!--                                                                            {{rm.check_type}}-->
<!--                                                                        </span>-->
<!--                                                                    </div>-->
<!--                                                                    <select class="custom-select"-->
<!--                                                                    v-show="rm.inactive != 0"-->
<!--                                                                    v-model="rm.check_type">-->
<!--                                                                        <option value="identity" selected>Identity</option>-->
<!--                                                                        <option value="role">Role</option>-->
<!--                                                                        <option value="department">Department</option>-->
<!--                                                                        <option value="fixed">Fixed user</option>-->
<!--                                                                    </select>-->
<!--                                                                    <input type="text" class="form-control font-weight-bolder rounded-0"-->
<!--                                                                    v-model="rm.check_value"-->
<!--                                                                    :readonly="rm.inactive == 0">-->
<!--                                                                    <div class="input-group-append">-->
<!--                                                                        <button class="btn btn-outline-primary" type="button"-->
<!--                                                                        v-show="rm.inactive != 0"-->
<!--                                                                        v-on:click="saveMenuRole(index)">-->
<!--                                                                            <i class="fas fa-save"></i>-->
<!--                                                                        </button>-->
<!--                                                                        <button class="btn btn-outline-danger" type="button"-->
<!--                                                                         v-show="rm.inactive == 0"-->
<!--                                                                         v-on:click="removeMenuRole(index)">-->
<!--                                                                            <i class="fas fa-trash"></i>-->
<!--                                                                        </button>-->
<!--                                                                    </div>-->
<!--                                                                </div>-->


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-show="!progress.main && tab.panelPages">
                                    <div class="col-12">
                                        <div class="accordion" id="accordionPRole">
                                            <div class="card" v-for="(p,index) in pages" :key="index">
                                                <div class="card-header" :id="'headingP'+index">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                        data-toggle="collapse" aria-expanded="true"
                                                        :data-target="'#collapseP'+index"
                                                        :aria-controls="'collapseP'+index"
                                                        v-on:click="getPageRole(index,p.page_id)">
                                                            Page #{{index+1}} <b>{{p.page_name}}</b>
                                                            <i class="fas fa-ellipsis-h float-right"></i>
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div :id="'collapseP'+index" class="collapse" :aria-labelledby="'headingP'+index"
                                                data-parent="#accordionPRole" v-bind:class="{'show': index == 0}">
                                                    <div class="card-body">
                                                        <div class="row mb-4">
                                                            <div class="col-12">
                                                                <span class="mr-2 font-weight-bolder">Page</span>
                                                                <b class="text-primary">{{p.page_name}}</b>
                                                                <span class="ml-2 mr-2 font-weight-bolder">Route</span>
                                                                <b class="text-primary">{{p.page_url}}</b>
                                                            </div>
                                                        </div>
                                                        <b>สิทธิ์เข้าถึง</b>
                                                        <hr style="margin-top: 0px;">
                                                        <button type="button" class="btn btn-primary btn-sm mb-2"
                                                        v-on:click="addPageRole()">
                                                            <i class="fas fa-plus-circle mr-2"></i> เพิ่มสิทธิ์เข้าถึง
                                                        </button>
                                                        <div class="text-center text-primary" v-show="progress.pagesRole">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                                            </div>
                                                            <b>ระบบกำลังดึงข้อมูล...</b>
                                                        </div>
                                                        <table class="table table-bordered table-hover table-sm" v-show="!progress.pagesRole">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" class="text-center">#</th>
                                                                    <th scope="col" class="text-center">กลุ่มสิทธิ์</th>
                                                                    <th scope="col" class="text-center">สิทธิ์</th>
                                                                    <th scope="col" class="text-center">Read</th>
                                                                    <th scope="col" class="text-center">Create</th>
                                                                    <th scope="col" class="text-center">Update</th>
                                                                    <th scope="col" class="text-center">Delete</th>
                                                                    <th scope="col" class="text-center"><i class="fas fa-sort-down"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(rp,index) in pagesRole" :key="index">
                                                                    <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                                    <td class="align-middle">
                                                                        <span v-show="rp.active == 0">{{rp.check_type}}</span>
                                                                        <select class="form-control form-control-sm"
                                                                        v-show="rp.active != 0"
                                                                        v-model="rp.check_type">
                                                                            <option value="identity" selected>Identity</option>
                                                                            <option value="role">Role</option>
                                                                            <option value="department">Department</option>
                                                                            <option value="fixed">Fixed user</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <span v-show="rp.active == 0">{{rp.check_value}}</span>
                                                                        <input type="text" class="form-control form-control-sm"
                                                                        v-show="rp.active != 0"
                                                                        v-model="rp.check_value">
                                                                    </td>
                                                                    <td class="text-center align-middle">
                                                                        <a href="javascript:;" class="text-success text-decoration-none"
                                                                        v-show="rp.ac_read == 1 && rp.active == 0"
                                                                        v-on:click="changeActionPageRole(index,'read',0)">
                                                                        <i class="fas fa-check-circle"></i> <b>Active</b></a>
                                                                        <a href="javascript:;" class="text-danger text-decoration-none"
                                                                        v-show="rp.ac_read == 0 && rp.active == 0"
                                                                        v-on:click="changeActionPageRole(index,'read',1)">
                                                                        <i class="fas fa-times-circle"></i> <b>Inactive</b></a>
                                                                        <select class="form-control form-control-sm"
                                                                        v-show="rp.active != 0"
                                                                        v-model="rp.ac_read">
                                                                            <option value="1">Active</option>
                                                                            <option value="0">Inactive</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center align-middle">
                                                                        <a href="javascript:;" class="text-success text-decoration-none"
                                                                        v-show="rp.ac_create == 1 && rp.active == 0"
                                                                        v-on:click="changeActionPageRole(index,'create',0)">
                                                                        <i class="fas fa-check-circle"></i> <b>Active</b></a>
                                                                        <a href="javascript:;" class="text-danger text-decoration-none"
                                                                        v-show="rp.ac_create == 0 && rp.active == 0"
                                                                        v-on:click="changeActionPageRole(index,'create',1)">
                                                                        <i class="fas fa-times-circle"></i> <b>Inactive</b></a>
                                                                        <select class="form-control form-control-sm"
                                                                        v-show="rp.active != 0"
                                                                        v-model="rp.ac_create">
                                                                            <option value="1">Active</option>
                                                                            <option value="0">Inactive</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center align-middle">
                                                                        <a href="javascript:;" class="text-success text-decoration-none"
                                                                        v-show="rp.ac_update == 1 && rp.active == 0"
                                                                        v-on:click="changeActionPageRole(index,'update',0)">
                                                                        <i class="fas fa-check-circle"></i> <b>Active</b></a>
                                                                        <a href="javascript:;" class="text-danger text-decoration-none"
                                                                        v-show="rp.ac_update == 0 && rp.active == 0"
                                                                        v-on:click="changeActionPageRole(index,'update',1)">
                                                                        <i class="fas fa-times-circle"></i> <b>Inactive</b></a>
                                                                        <select class="form-control form-control-sm"
                                                                        v-show="rp.active != 0"
                                                                        v-model="rp.ac_update">
                                                                            <option value="1">Active</option>
                                                                            <option value="0">Inactive</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center align-middle">
                                                                        <a href="javascript:;" class="text-success text-decoration-none"
                                                                        v-show="rp.ac_delete == 1 && rp.active == 0"
                                                                        v-on:click="changeActionPageRole(index,'delete',0)">
                                                                        <i class="fas fa-check-circle"></i> <b>Active</b></a>
                                                                        <a href="javascript:;" class="text-danger text-decoration-none"
                                                                        v-show="rp.ac_delete == 0 && rp.active == 0"
                                                                        v-on:click="changeActionPageRole(index,'delete',1)">
                                                                        <i class="fas fa-times-circle"></i> <b>Inactive</b></a>
                                                                        <select class="form-control form-control-sm"
                                                                        v-show="rp.active != 0"
                                                                        v-model="rp.ac_delete">
                                                                            <option value="1">Active</option>
                                                                            <option value="0">Inactive</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button class="btn btn-primary btn-sm"
                                                                        v-show="rp.active == 1"
                                                                        v-on:click="savePageRole(index)">
                                                                            <i class="fas fa-save"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger btn-sm"
                                                                        v-show="rp.active == 1"
                                                                        v-on:click="removeNewPageRole(index)">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                        <button class="btn btn-primary btn-sm"
                                                                        v-show="rp.active == 0"
                                                                        v-on:click="updateActionPageRole(index)">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger btn-sm"
                                                                        v-show="rp.active == 0"
                                                                        v-on:click="removePageRole(index)">
                                                                            <i class="fas fa-trash"></i>
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
        this.setDataTab(1);
    },
    data(){
        return{
            tab: {
                menuOne: true,
                menuTwo: false,
                panelMenus: true,
                panelPages: false
            },
            progress: {
                main: false,
                menusRole: false,
                pagesRole: false
            },
            menus: {},
            menusRole: {},
            menusIndex: 0,
            pages: {},
            pagesRole: {},
            pagesIndex: 0
        }
    },
    methods:{
        setDataTab(menu){

            this.tab.menuOne = menu == 1 ? true : false;
            this.tab.menuTwo = menu == 2 ? true : false;
            this.tab.panelMenus = menu == 1 ? true : false;
            this.tab.panelPages = menu == 2 ? true : false;

            if(menu == 1)
                this.getMenus();
            else
                this.getPages();

        },
        setProgress(target,bool){
            if(target == 'main')
                this.progress.main = bool;
            else if(target == 'menusRole')
                this.progress.menusRole = bool;
            else if(target == 'pagesRole')
                this.progress.pagesRole = bool;
        },
        getMenus(){
            this.setProgress('main',true);
            axios.get('/api/admin/it/role/menus/list')
            .then(response => {
                this.menus = response.data;
                this.setProgress('main',false);
                this.getMenuRole(0,this.menus[0].menu_id);
            });
        },
        getMenuRole(index,menuId){
            this.menusIndex = index;
            this.setProgress('menusRole',true);
            axios.get('/api/admin/it/role/menus/role/list/'+menuId)
            .then(response => {
                this.menusRole = response.data;
                this.setProgress('menusRole',false);
            });
        },
        addMenuRole(){
            this.menusRole.push({
                check_type: 'identity',
                check_value: '',
                inactive: 1
            });
        },
        saveMenuRole(index){

            if(this.menusRole[index].check_value == ''){
                this.$swal({icon: 'warning', text: 'กรุณาใส่สิทธิ์เข้าถึง'});
                return;
            }

            axios.post('/api/admin/it/role/menus/role/save',{
                id: this.menus[this.menusIndex].menu_id,
                type: this.menusRole[index].check_type,
                value: this.menusRole[index].check_value
            })
            .then(response => {
                if(response.data.status){
                    this.$swal({icon: 'success', text: 'บันทึกสิทธิ์เข้าถึงเรีนยร้อยแล้ว'});
                    this.getMenuRole(this.menusIndex,this.menus[this.menusIndex].menu_id)
                }
                else
                    this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกสิทธิ์เข้าถึงได้'});
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกสิทธิ์เข้าถึงได้'});
            });
        },
        removeMenuRole(index){
            this.$swal({
                title: 'ยืนยันการลบสิทธิ์เข้าถึง',
                text: "ต้องการการลบสิทธิ์เข้าถึง "+this.menusRole[index].check_value+' เมนู '+this.menus[this.menusIndex].menu_name+' หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed){
                    axios.post('/api/admin/it/role/menus/role/remove',{
                        id: this.menus[this.menusIndex].menu_id,
                        type: this.menusRole[index].check_type,
                        value: this.menusRole[index].check_value
                    })
                    .then(response => {
                        if(response.data.status){
                            this.$swal({icon: 'success', text: 'ลบสิทธิ์เข้าถึงเรีนยร้อยแล้ว'});
                            this.getMenuRole(this.menusIndex,this.menus[this.menusIndex].menu_id);
                        }
                        else
                            this.$swal({icon: 'error', text: 'ไม่สามารถลบสิทธิ์เข้าถึงได้'});
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถลบสิทธิ์เข้าถึงได้'});
                    });
                }
            });
        },
        getPages(){
            this.setProgress('main',true);
            axios.get('/api/admin/it/role/pages/list')
            .then(response => {
                this.pages = response.data;
                this.setProgress('main',false);
                this.getPageRole(0,this.pages[0].page_id);
            });
        },
        getPageRole(index,pageId){
            this.pagesIndex = index;
            this.setProgress('pagesRole',true);
            axios.get('/api/admin/it/role/pages/role/list/'+pageId)
            .then(response => {
                this.pagesRole = response.data;
                this.setProgress('pagesRole',false);
            });
        },
        savePageRole(index){
            axios.post('/api/admin/it/role/pages/role/save',{
                id: this.pages[this.pagesIndex].page_id,
                type: this.pagesRole[index].check_type,
                value: this.pagesRole[index].check_value,
                read: this.pagesRole[index].ac_read,
                create: this.pagesRole[index].ac_create,
                update: this.pagesRole[index].ac_update,
                delete: this.pagesRole[index].ac_delete,
            })
            .then(response => {
                this.$swal({icon: 'success', text: 'บันทึกสิทธิ์เข้าถึงเรีนยร้อยแล้ว'});
                this.getPageRole(this.pagesIndex,this.pages[this.pagesIndex].page_id);
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกสิทธิ์เข้าถึงได้'});
            });
        },
        removePageRole(index){
            this.$swal({
                title: 'ยืนยันการลบสิทธิ์เข้าถึง',
                text: "ต้องการการลบสิทธิ์เข้าถึง "+this.pagesRole[index].check_value+' Page '+this.pages[this.pagesIndex].page_name+' หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed){
                    axios.post('/api/admin/it/role/pages/role/remove',{
                        id: this.pages[this.pagesIndex].page_id,
                        type: this.pagesRole[index].check_type,
                        value: this.pagesRole[index].check_value
                    })
                    .then(response => {
                        this.$swal({icon: 'success', text: 'ลบสิทธิ์เข้าถึงเรีนยร้อยแล้ว'});
                        this.getPageRole(this.pagesIndex,this.pages[this.pagesIndex].page_id);
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกสิทธิ์เข้าถึงได้'});
                    });
                }
            });
        },
        addPageRole(){
            this.pagesRole.push({
                check_type: 'identity',
                check_value: '',
                ac_read: 1,
                ac_create: 1,
                ac_update: 1,
                ac_delete: 1,
                active: 1
            });
        },
        removeNewPageRole(index){

            var dataOld = this.pagesRole,
                dataTemp = [];

            for(var i = 0; i < dataOld.length; i++){
                if(i != index)
                    dataTemp.push(dataOld[i]);
            }

            this.pagesRole = dataTemp;
        },
        changeActionPageRole(index,ac,value){
            if(ac == 'read') this.pagesRole[index].ac_read = value;
            else if(ac == 'create') this.pagesRole[index].ac_create = value;
            else if(ac == 'update') this.pagesRole[index].ac_update = value;
            else if(ac == 'delete') this.pagesRole[index].ac_delete = value;
        },
        updateActionPageRole(index){
            axios.post('/api/admin/it/role/pages/role/update',{
                id: this.pages[this.pagesIndex].page_id,
                type: this.pagesRole[index].check_type,
                value: this.pagesRole[index].check_value,
                read: this.pagesRole[index].ac_read,
                create: this.pagesRole[index].ac_create,
                update: this.pagesRole[index].ac_update,
                delete: this.pagesRole[index].ac_delete,
            })
            .then(response => {
                this.$swal({icon: 'success', text: 'อัพเดทสิทธิ์เข้าถึงเรีนยร้อยแล้ว'});
                this.getPageRole(this.pagesIndex,this.pages[this.pagesIndex].page_id);
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถอัพเดทสิทธิ์เข้าถึงได้'});
            });
        }
    }
}
</script>
