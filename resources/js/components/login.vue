<template>
    <div class="h-100">
        <div class="container-fluid h-100">
            <div class="row align-items-center h-100">
                <div class="col-xl-6 col-md-10 col-sm-12 mx-auto">
                    <div class="card box-login justify-content-center">
                        <div class="card-body p-0">
                            <div class="row m-0">

                                <div class="col-md-6 col-12 p-0">
                                    <form class="row m-0 my-5">
                                        <div class="col-12 p-0 text-center d-md-none d-block">
                                            <img class="logo-login" :src="'img/logo-sm-color.png'" alt="">
                                            <h4 class="text-amado mt-3 font-poppins">AMADO SUPPORT</h4>
                                            <p class="pb-3"><i class="fas fa-book mr-2"></i><a href="/files/user_manual.pdf" target="_blank" class="text-black">User Manual</a></p>
                                            <hr style="width: 90%">
                                        </div>
                                        <div class="col-md-12 p-0 text-center my-3">
                                            <h3 class="font-poppins text-amado-blue">Sign In</h3>
                                        </div>
                                        <div class="col-md-12 px-3 text-center"
                                             v-show="alertFail"
                                        >
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <i class="fas fa-exclamation-triangle mr-2"></i><strong>ไม่สามารถเข้าสู่ระบบได้ กรุณาตรวจสอบข้อมูลให้ถูกต้อง</strong>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12 p-0 px-5 mt-3">
                                            <div class="input-group form-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user text-amado-blue"></i></span>
                                                </div>
                                                <input type="text" class="form-control rounded-right" placeholder="Username"
                                                       v-model="username"
                                                       v-bind:class="{'is-invalid':invalid.username}"
                                                       v-on:keyup.enter="login()"
                                                >
                                                <div class="invalid-feedback">
                                                    Please enter username.
                                                </div>
                                            </div>
                                            <div class="input-group form-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-key text-amado-blue"></i></span>
                                                </div>
                                                <input type="password" class="form-control rounded-right" placeholder="Password"
                                                       v-model="password"
                                                       v-bind:class="{'is-invalid':invalid.password}"
                                                       v-on:keyup.enter="login()"
                                                >
                                                <div class="invalid-feedback">
                                                    Please enter password.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 p-0 text-center">
                                            <button type="button" class="btn btn-amado my-3"
                                                    v-on:click="login()"
                                                    v-html="btnTxt"
                                                    :disabled="btnDisabled"
                                            >
                                                <i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6 col-12 p-0 d-none d-md-block">
                                    <div class="bg-login-card h-100 rounded-r">
                                        <div class="row m-0 align-items-center h-100 mx-auto">
                                            <div class="col-12 p-0 text-center">
                                                <img class="logo-login" :src="'img/logo-sm-white.png'" alt="">
                                                <h4 class="text-white mt-3 font-poppins">AMADO SUPPORT</h4>
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

import VueSession from 'vue-session'
Vue.use(VueSession);

export default {
    mounted(){
        var today = new Date();
        var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        this.date = date;
    },
    data(){
        return{
            username: '',
            password: '',
            invalid: {
                username: false,
                password: false
            },
            btnTxt: '<i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ',
            btnDisabled: false,
            alertFail: false,
            date: ''
        }
    },
    methods: {
        login(){

            this.invalid.username = ( this.username == '' ? true : false );
            this.invalid.password = ( this.password == '' ? true : false );
            this.alertFail = false;

            if( !this.invalid.username && !this.invalid.password ){

                this.btnTxt = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังเข้าสู่ระบบ...';
                this.btnDisabled = true;

                axios.post('/api/login/checkin',{
                    username: this.username,
                    password: this.password
                })
                .then(response => {
                    if(response.data.Status){

                        this.$session.start();
                        this.$session.set('user',response.data.session.user);
                        this.$session.set('userId',response.data.session.userId);
                        this.$session.set('name',response.data.session.name);
                        this.$session.set('departmentId',response.data.session.departmentId);
                        this.$session.set('department',response.data.session.department);
                        this.$session.set('role',response.data.session.role);
                        this.$session.set('identity',response.data.session.identity);
                        this.$session.set('isLogin',response.data.session.isLogin);
                        this.$session.set('_token',response.data.session._token);

                        if( this.date == response.data.session.password_expaired )
                            window.location.href = '/change-password'
                        else{

                            if( window.location.pathname == '/' || window.location.pathname == '/login' ){

                                if( this.$session.get('identity') == 'admin' )
                                    window.location.href = '/admin';
                                else if( this.$session.get('identity') == 'user' )
                                    window.location.href = '/user';
                            }
                            else{
                                window.location.href = window.location.pathname;
                            }

                        }

                    }
                    else{
                        this.invalid.username = false;
                        this.invalid.password = false;
                        this.alertFail = true;
                        this.btnDisabled = false;
                        this.btnTxt = '<i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ';
                    }
                })
                .catch(response => {
                    this.invalid.username = false;
                    this.invalid.password = false;
                    this.alertFail = true;
                    this.btnDisabled = false;
                    this.btnTxt = '<i class="fas fa-sign-in-alt mr-2"></i> เข้าสู่ระบบ';
                });

            }

        }
    }
}
</script>
