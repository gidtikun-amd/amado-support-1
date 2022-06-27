<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-atlas"></i><span class="ml-2">จักการเพจและเมนู</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-2">
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action" aria-current="true"
                            v-bind:class="{'active': tab.mMenu}"
                            v-on:click="setTabData(1)">
                                Menu list <i class="fas fa-chevron-circle-right float-right"></i>
                            </button>
                            <button type="button" class="list-group-item list-group-item-action"
                            v-bind:class="{'active': tab.mPage}"
                            v-on:click="setTabData(2)">
                                Page list <i class="fas fa-chevron-circle-right float-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12" v-show="tab.pMenu">
                                        <p class="font-weight-bolder">Menu list</p>                                                        
                                        <hr style="margin-top: -15px;">
                                        <button type="button" class="btn btn-primary btn-sm"
                                        v-on:click="getNewMenu(true,'add','')">
                                            <i class="fas fa-plus-circle mr-2"></i> เพิ่ม Menu
                                        </button>
                                        <div class="text-center text-primary mt-2" v-show="tab.progress.pMenu">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                            </div>
                                            <b>ระบบกำลังดึงข้อมูล...</b>
                                        </div>
                                        <table class='table table-hover table-striped table-bordered table-sm mt-3' v-show="!tab.progress.pMenu">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">#</th>
                                                    <th scope="col" class="text-center">menu</th>
                                                    <th scope="col" class="text-center">View</th>
                                                    <th scope="col" class="text-center">Group</th>
                                                    <th scope="col" class="text-center">Type</th>              
                                                    <th scope="col" class="text-center">Host</th>                                              
                                                    <th scope="col" class="text-center" width="10%"><i class="fas fa-caret-down"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="table-info" v-show="menus.new">
                                                    <th scope="row" class="text-center align-middle">{{menus.title}}</th>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" v-model="menus.inpt.name">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" v-model="menus.inpt.view">
                                                    </td>
                                                    <td>
                                                        <select class="form-control form-control-sm"  v-model="menus.inpt.group">
                                                            <option value="admin">admin</option>
                                                            <option value="user">user</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control form-control-sm" v-model="menus.inpt.type">
                                                            <option value="main">เมนูหลัก</option>
                                                            <option value="sub">เมนูย่อย</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control form-control-sm" v-model="menus.inpt.host">
                                                            <option value="" select>ไม่มี</option>
                                                            <option 
                                                            v-for="(mm,index) in menus.data" :key="index" 
                                                            :value="mm.menu_id">
                                                                {{mm.menu_name}}
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary btn-sm"
                                                        v-on:click="saveMenu()">
                                                            <i class="fas fa-save"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm"
                                                        v-on:click="getNewMenu(false,'','')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr v-for="(m,index) in menus.data" :key="index">
                                                    <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                    <td class="align-middle">
                                                        <span>{{m.menu_name}}</span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span>{{m.menu_code}}</span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span>{{m.menu_group}}</span>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <span>{{m.menu_type}}</span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span>{{m.menu_host}}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-secondary btn-sm"
                                                        v-on:click="getNewMenu(true,'update',index)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm"
                                                        v-on:click="removeMenu(index)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-12" v-show="tab.pPage">
                                        <p class="font-weight-bolder">Page list</p>                                                        
                                        <hr style="margin-top: -15px;">
                                        <button type="button" class="btn btn-primary btn-sm"
                                         v-on:click="getNewPage(true,'add','')">
                                            <i class="fas fa-plus-circle mr-2"></i> เพิ่ม Page
                                        </button>
                                        
                                        <div class="text-center text-primary mt-2" v-show="tab.progress.pPage">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                            </div>
                                            <b>ระบบกำลังดึงข้อมูล...</b>
                                        </div>
                                        <table class='table table-hover table-striped table-bordered table-sm mt-3' v-show="!tab.progress.pPage">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">#</th>
                                                    <th scope="col" class="text-center">Page</th>
                                                    <th scope="col" class="text-center">View</th>
                                                    <th scope="col" class="text-center">Url</th>                                            
                                                    <th scope="col" class="text-center" width="10%"><i class="fas fa-caret-down"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="table-info" v-show="pages.new">
                                                    <th scope="row" class="text-center align-middle">{{pages.title}}</th>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" 
                                                        v-model="pages.inpt.name">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" 
                                                        v-model="pages.inpt.view">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" 
                                                        v-model="pages.inpt.url">
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary btn-sm"
                                                        v-on:click="savePage()">
                                                            <i class="fas fa-save"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm"
                                                        v-on:click="getNewPage(false,'','')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr v-for="(p,index) in pages.data" :key="index">
                                                    <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                    <td class="align-middle">{{p.page_name}}</td>
                                                    <td class="align-middle">{{p.page_view}}</td>
                                                    <td class="align-middle">{{p.page_url}}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-secondary btn-sm"
                                                        v-on:click="getNewPage(true,'update',index)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm"
                                                        v-on:click="removePage(index)">
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
</template>
<script>

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

export default {
    mounted(){
        this.setTabData(1);
    },
    data(){
        return{
            tab: {
                mMenu: true,
                mPage: false,
                pMenu: true,
                pPage: false,
                progress: {
                    pMenu: false,
                    pPage: false,
                }
            },
            menus: {
                new: false,
                inpt:{
                    id: '',
                    name: '',
                    view: '',
                    group: 'admin',
                    type: 'main',
                    host: ''
                },
                method: '',
                title: 'add',
                data: []
            },
            pages: {
                new: false,
                inpt: {
                    id: '',
                    name: '',
                    view: '',
                    url: ''
                },
                method: '',
                title: 'add',
                data: [],
            }
        }
    },
    methods:{
        setTabData(menu){
            this.tab.mMenu = menu == 1 ? true : false;
            this.tab.mPage = menu == 2 ? true : false;
            this.tab.pMenu = menu == 1 ? true : false;
            this.tab.pPage = menu == 2 ? true : false;
            if(menu == 1) this.getMenuList();
            else if(menu == 2) this.getPageList();
        },
        getNewMenu(bool,title,index){
            this.menus.new = bool;
            this.menus.title = title;
            this.menus.method = title;
            this.menus.inpt.id = title == 'update' ? this.menus.data[index].menu_id : '';
            this.menus.inpt.name = title == 'update' ? this.menus.data[index].menu_name : '';
            this.menus.inpt.view = title == 'update' ? this.menus.data[index].menu_code : '';
            this.menus.inpt.group = title == 'update' ? this.menus.data[index].menu_group : 'admin';
            this.menus.inpt.type = title == 'update' ? this.menus.data[index].menu_types : 'main';
            this.menus.inpt.host = title == 'update' ? this.menus.data[index].menu_hosts : '';
            if(title == 'update') window.scrollTo(0, 0);
        },
        getMenuList(){
            this.tab.progress.pMenu = true;
            axios.get('/api/admin/it/role/menus/list')
            .then(response => {
                this.menus.data = response.data;
                this.tab.progress.pMenu = false;
            });
        },
        saveMenu(){

            if(this.menus.inpt.name == ''){
                this.$swal({icon: 'warning', text: 'กรุณากรอกชื่อเมนู'});
                return;
            }

            if(this.menus.inpt.view == ''){
                this.$swal({icon: 'warning', text: 'กรุณากรอก view'});
                return;
            }

            const linkSave = '/api/admin/it/role/menus/save',
                    linkUpdate = '/api/admin/it/role/menus/update';
            
            let api = this.menus.method == 'add' ? linkSave : linkUpdate;

            axios.post(api,this.menus.inpt)
            .then(response => {
                this.$swal({icon: 'success', text: 'บันทึกข้อมูลเรียบร้อยแล้ว'});
                this.menus.new = false;
                this.getMenuList();
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกข้อมูลได้'});
            });

        },
        removeMenu(index){
            this.$swal({
                title: 'ยืนยันการลบเมนู',
                text: "ต้องการการลบเมนู "+this.menus.data[index].menu_name+' หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed){
                    axios.get('/api/admin/it/role/menus/delete/'+this.menus.data[index].menu_id)
                    .then(response => {
                        this.$swal({icon: 'success', text: 'ลบเมนู '+this.menus.data[index].menu_name+' เรียบร้อยแล้ว'});
                        this.getMenuList();
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถลบเมนู '+this.menus.data[index].menu_name+' ได้'});
                    });
                }
            });
        },
        getNewPage(bool,title,index){
            this.pages.new = bool;
            this.pages.title = title;
            this.pages.method = title;
            this.pages.inpt.id = title == 'update' ? this.pages.data[index].page_id : '';
            this.pages.inpt.name = title == 'update' ? this.pages.data[index].page_name : '';
            this.pages.inpt.view = title == 'update' ? this.pages.data[index].page_view : '';
            this.pages.inpt.url = title == 'update' ? this.pages.data[index].page_url : '';
            if(title == 'update') window.scrollTo(0, 0);
        },
        getPageList(){
            this.tab.progress.pPage = true;
            axios.get('/api/admin/it/role/pages/list')
            .then(response => {
                this.pages.data = response.data;
                this.tab.progress.pPage = false;
            });
        },
        savePage(){

            if(this.pages.inpt.name == ''){
                this.$swal({icon: 'warning', text: 'กรุณากรอกชื่อเพจ'});
                return;
            }

            if(this.pages.inpt.view == ''){
                this.$swal({icon: 'warning', text: 'กรุณากรอกเพจ View'});
                return;
            }

            if(this.pages.inpt.view == ''){
                this.$swal({icon: 'warning', text: 'กรุณากรอกเพจ URL'});
                return;
            }

            const linkSave = '/api/admin/it/role/pages/save',
                    linkUpdate = '/api/admin/it/role/pages/update';
            
            let api = this.pages.method == 'add' ? linkSave : linkUpdate;

            axios.post(api,this.pages.inpt)
            .then(response => {
                this.$swal({icon: 'success', text: 'บันทึกข้อมูลเรียบร้อยแล้ว'});
                this.pages.new = false;
                this.getPageList();
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกข้อมูลได้'});
            });

        },
        removePage(index){
            this.$swal({
                title: 'ยืนยันการลบเพจ',
                text: "ต้องการการลบเพจ "+this.pages.data[index].page_name+' หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed){
                    axios.get('/api/admin/it/role/pages/delete/'+this.pages.data[index].page_id)
                    .then(response => {
                        this.$swal({icon: 'success', text: 'ลบเพจ '+this.pages.data[index].page_name+' เรียบร้อยแล้ว'});
                        this.getPageList();
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถลบเพจ '+this.pages.data[index].page_name+' ได้'});
                    });
                }
            });
        }
    }
}
</script>