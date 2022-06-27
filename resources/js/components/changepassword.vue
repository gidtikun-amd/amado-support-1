<template>
    <div>

        <div class="container-fluid h-100">
            <div class="row align-items-center h-100 mt-5">
                <div class="col-lg-4 col-md-6 col-sm-8 mx-auto mt-5">
                    <div class="card justify-content-center">
                        <div class="card-body">
                            <form class="row">
                                <div class="col-md-12 text-center my-3">
                                    <img class="logo-nav" :src="'img/logo-red.png'" alt="">
                                </div>
                                <div class="col-md-12 px-5 mt-1">
                                    <hr>
                                    <div class="form-group">
                                        <label>รหัสผ่านเดิม</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-clock"></i></span>
                                            </div>
                                            <input type="password" class="form-control" placeholder="รหัสผ่านเดิม"
                                                v-model="password.old"
                                                v-bind:class="{'is-invalid':valid.old}"
                                            >
                                            <div class="invalid-feedback"
                                                v-show="valid.show.old1"
                                            >
                                                กรุณากรอกรหัสผ่านเดิม...!
                                            </div>
                                            <div class="invalid-feedback"
                                                v-show="valid.show.old2"
                                            >
                                                รหัสผ่านเดิมไม่ถูกต้อง...!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>รหัสผ่านใหม่</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-plus"></i></span>
                                            </div>
                                            <input type="password" class="form-control" placeholder="รหัสผ่านใหม่"
                                                v-model="password.new"
                                                v-bind:class="{'is-invalid':valid.new}"
                                            >
                                            <div class="invalid-feedback"
                                                v-show="valid.show.new1"
                                            >
                                                กรุณากรอกรหัสผ่านใหม่...!
                                            </div>
                                            <div class="invalid-feedback"
                                                v-show="valid.show.new2"                                            
                                            >
                                                รหัสผ่านเดิมซ้ำกับรหัสผ่านใหม่...!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>ยืนยันรหัสผ่าน</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                            </div>
                                            <input type="password" class="form-control" placeholder="ยืนยันรหัสผ่าน"
                                                v-model="password.confirm"
                                                v-bind:class="{'is-invalid':valid.confirm}"
                                            >
                                            <div class="invalid-feedback"
                                                v-show="valid.show.confirm1"
                                            >
                                                กรุณากรอกยืนยันรหัสผ่าน...!
                                            </div>
                                            <div class="invalid-feedback"
                                                v-show="valid.show.confirm2"
                                            >
                                                ยืนยันรหัสผ่านไม่ถูกต้อง...!
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-amado my-3"
                                        v-on:click="setChangePassword()"
                                    >
                                        <i class="fas fa-user-edit mr-2"></i> เปลี่ยนรหัสผ่าน
                                    </button>
                                </div>
                            </form>
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
        this.user = this.$session.get('user');
    },
    data(){
        return{
            user: '',
            password: {
                old: '',
                new: '',
                confirm: ''
            },
            valid: {
                old: false,
                new: false,
                confirm: false,
                show: {
                    old1: false,
                    old2: false,
                    new1: false,
                    new2: false,
                    confirm1: false,
                    confirm2: false
                }
            }
        }
    },
    methods: {
        setChangePassword(){

            this.valid.old = ( this.password.old == '' ? true : false );
            this.valid.show.old1 = ( this.password.old == '' ? true : false );
            this.valid.show.old2 = false;

            if( this.password.new == '' ? true : false ){
                this.valid.new = true;
                this.valid.show.new1 = true;
                this.valid.show.new2 = false;
            }
            else if( this.password.new == this.password.old ){
                this.valid.new = true;
                this.valid.show.new1 = false;
                this.valid.show.new2 = true;
            }
            else{
                this.valid.new = false;
                this.valid.show.new1 = false;
                this.valid.show.new2 = false;
            }

            if( this.password.confirm == '' ? true : false ){
                this.valid.confirm = true;
                this.valid.show.confirm1 = true;
                this.valid.show.confirm2 = false;
            }
            else if( this.password.new != this.password.confirm ){
                this.valid.confirm = true;
                this.valid.show.confirm1 = false;
                this.valid.show.confirm2 = true;
            }
            else{
                this.valid.confirm = false;
                this.valid.show.confirm1 = false;
                this.valid.show.confirm2 = false;
            }

            if( 
                !this.valid.old &&
                !this.valid.new &&
                !this.valid.confirm
            ){
                axios.post('/api/login/changepassword',{
                    username: this.user,
                    pass_old: this.password.old,
                    pass_new: this.password.new
                })
                .then(response => {

                    let resData = response.data;

                    if( resData.Status ){
                        if( this.$session.get('identity') == 'admin' )
                            window.location.href = '/admin';
                        else 
                            window.location.href = '/user'
                    }
                    else{
                        this.valid.old = true;
                        this.valid.show.old2 = true;
                    }

                })
                .catch(response => {
                    this.$swal({
                        icon: 'error',
                        title: 'ไม่สามารถเปลี่ยนรหัสผ่านได้!',
                    });
                });
            }

        }
    }
}
</script>