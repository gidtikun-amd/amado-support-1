<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-user-lock"></i><span class="ml-2">กำหนดสิทธิ์ผู้ใช้</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 col-sm-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" ><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="รหัสพนักงาน"
                            v-model="searchId"
                            v-on:keyup.enter="getUserData()">
                        </div>
                    </div>
                </div>
                <div class="row" v-show='progress'>
                    <div class="col-12 text-center text-primary">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <b>ระบบกำลังดึงข้อมูล...</b>
                    </div>
                </div>
                <div class="row" v-show='showError'>
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>คำเตือน</strong><i class="fas fa-exclamation ml-1 mr-1"></i> ไม่พบข้อมูลพนักงานที่กำลังค้นหา
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" v-show='showData'>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <span class="badge badge-pill badge-success mr-2" v-show="user.active == 1">Active</span>
                                        <span class="badge badge-pill badge-danger mr-2" v-show="user.active == 0">Inzctive</span>
                                        <span class="font-weight-bolder">รหัสพนักงาน</span>
                                        <span class="font-weight-bolder text-primary mr-3 ml-2">{{user.id}}</span>
                                        <span class="font-weight-bolder">พนักงาน</span>
                                        <span class="font-weight-bolder text-primary mr-3 ml-2">{{user.fullname}}</span>                                
                                        <span class="font-weight-bolder">ชื่อเล่น</span>
                                        <span class="font-weight-bolder text-primary mr-3 ml-2">{{user.nickname}}</span>                                                                  
                                        <span class="font-weight-bolder">ฝ่าย</span>
                                        <span class="font-weight-bolder text-primary mr-3 ml-2">{{user.dept}}</span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <b>Account:</b>                                                        
                                        <hr style="margin-top: 0px;"> 
                                        <button class="btn btn-primary btn-sm mb-4" v-on:click="getNewData(true,'add','')">
                                            <i class="fas fa-user-plus mr-2"></i> เพิ่ม Account
                                        </button>
                                        <table class="table table-bordered table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center align-middle" width="7%">#</th>
                                                    <th scope="col" class="text-center align-middle">Username</th>
                                                    <!-- <th scope="col" class="text-center align-middle">Password</th> -->
                                                    <th scope="col" class="text-center align-middle" width="10%">Eexpaired</th>
                                                    <th scope="col" class="text-center align-middle" width="10%">Identity</th>
                                                    <th scope="col" class="text-center align-middle" width="10%">Role</th>
                                                    <th scope="col" class="text-center" width="10%"><i class="fas fa-sort-down"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-show="inpt.new">
                                                    <th scope="row" class="text-center align-middle">{{inpt.title}}</th>
                                                    <td class="align-middle">
                                                        <input type="text" class="form-control form-control-sm" 
                                                        v-model="inpt.userId"
                                                        :readonly="inpt.title == 'update'">
                                                    </td>
                                                    <!-- <td class="align-middle"><input type="text" class="form-control form-control-sm" v-model="inpt.password"></td> -->
                                                    <td><date-picker value-type="format" format="YYYY-MM-DD" v-model="inpt.expaire"></date-picker></td>
                                                    <td class="align-middle">
                                                        <select class="form-control form-control-sm" v-model="inpt.identity">
                                                            <option value="user">User</option>
                                                            <option value="admin">Admin</option>
                                                        </select>
                                                    </td>
                                                    <td class="align-middle"><input type="text" class="form-control form-control-sm" v-model="inpt.role"></td>
                                                    <td class="text-center align-middle">
                                                        <button class="btn btn-primary btn-sm"
                                                        v-on:click="takeActionUser()">
                                                            <i class="fas fa-save"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm"
                                                        v-on:click="getNewData(false,'','')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr v-for="(acc,index) in account" :key="index">
                                                    <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                    <td class="align-middle">{{acc.user}}</td>
                                                    <!-- <td class="align-middle">{{acc.password}}</td> -->
                                                    <td class="align-middle">{{acc.password_expaired}}</td>
                                                    <td class="text-center align-middle">{{acc.identity}}</td>
                                                    <td class="align-middle">{{acc.role}}</td>
                                                    <td class="text-center align-middle">
                                                        <button class="btn btn-primary btn-sm"
                                                        v-on:click="getNewData(true,'update',index)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-success btn-sm"
                                                        v-on:click="resetPassword(acc.user)">
                                                            repassword
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

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/th';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

export default {  
    components:{
        DatePicker 
    },  
    mounted(){
        let date = new Date();
        this.inpt.now = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+(date.getDate())).slice(-2);
    },
    data(){
        return{
            inpt:{
                new: false,
                title: 'add',
                method: '',
                now: '',
                empId: '',
                userId: '',
                password: '',
                expaire: '',
                identity: 'user',
                role: ''
            },
            searchId: '',
            progress: false,
            showData: false,
            showError: false,
            user: {},
            account: []
        }
    },
    methods:{
        getNewData(bool,title,index){
            this.inpt.new = bool;
            this.inpt.title = title;
            this.inpt.method = title;
            this.inpt.empId = this.user.id;
            this.inpt.userId = title == 'update' ? this.account[index].user : '';
            this.inpt.password = title == 'update' ? this.account[index].password : '';
            this.inpt.expaire = title == 'update' ? this.account[index].password_expaired : this.inpt.now;
            this.inpt.identity = title == 'update' ? this.account[index].identity : 'user';
            this.inpt.role = title == 'update' ? this.account[index].role : 'user';
        },
        getUserData(){

            if(this.searchId == ''){
                this.$swal({icon: 'error', text: 'กรุณาใส่รหัสพนักงาน'});
                return;
            }

            this.progress = true;
            this.showData = false
            this.showError = false;

            axios.get('/api/admin/it/role/user/'+this.searchId)
            .then(response => {

                this.user = response.data.user;
                this.account = response.data.account;

                if( response.data.user !== null ){
                    this.searchId = ''
                    this.progress = false;
                    this.showData = true;
                    this.showError = false;
                }
                else{     
                    this.user = { active: 0 };               
                    this.progress = false;
                    this.showData = false;
                    this.showError = true;
                }

            })
            .catch(response => {
                this.user = {};
                this.account = [];
                this.progress = false;
                this.showData = false;
                this.showError = true;
            });

        },
        takeActionUser(){

            if(this.inpt.userId == ''){
                this.$swal({icon: 'warning', text: 'กรุณาใส่ Username'});
                return;
            }

            if(this.inpt.expaire == ''){
                this.$swal({icon: 'warning', text: 'กรุณาใส่วันที่ expaire'});
                return;
            }

            if(this.inpt.role == ''){
                this.$swal({icon: 'warning', text: 'กรุณาใส่ Role'});
                return;
            }

            axios.post('/api/admin/it/role/user/takeaction',this.inpt)
            .then(response => {
                this.$swal({icon: 'success', text: 'บันทึกข้อมูลเรียบร้อยแล้ว'});
                this.searchId = this.user.id;
                this.getUserData();
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกข้อมูลได้'});
            });

        },
        resetPassword(user){
            axios.post('/api/admin/it/role/user/reset-password',{userId: user})
            .then(response => {
                this.$swal({icon: 'success', text: 'Reset password success.'});
                this.searchId = this.user.id;
                this.getUserData();
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'Reset password fail.'});
            });
        }
    }
}
</script>