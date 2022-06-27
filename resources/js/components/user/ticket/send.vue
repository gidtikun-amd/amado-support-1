<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fab fa-wpforms"></i><span class="ml-2">Ticket Form</span>
            </div>
            <div class="card-body p-5">
                <div class="row m-0 mb-3">
                    <div class="col-md-12 text-center mb-2">
                        <img class="logo-nav" src="/img/logo-red.png" alt="">
                    </div>
                    <div class="col-md-12 text-center">
                        <p class="mb-0"><b>TICKET REQUEST</b></p>
                    </div>
                </div>
                <div class="row m-0 mt-3 mb-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6 text-left">
                                        <span><b>เลขที่คำขอ </b><span class="ml-2 text-secondary">Auto Generate</span></span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <span><b>วันที่ </b><span class="ml-2">{{date}}</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <span class="mr-4"><b>ชื่อ-นามสกุล </b><span class="ml-2">{{$session.get('name')}}</span></span>
                                        <span><b>ฝ่าย </b><span class="ml-2">{{$session.get('department')}}</span></span>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="row mb-3">
                                    <div class="col-md-2 my-auto">
                                        <h5 class="card-title m-0"><b>เรื่อง : </b></h5>
                                    </div>
                                    <div class="col-md">
                                        <select class="form-control"
                                        v-model="data.subjectId"
                                        v-bind:class="{'is-invalid': input.invalid.subject}"
                                        :disabled="input.disabled.subject">
                                            <option value='' selected disabled>กรุณาเลือกเรื่อง...</option>
                                            <option v-for="(sbj,index) in subject" :key="index" :value="sbj.it_ticket_subject_id">
                                                {{sbj.it_ticket_subject_name}}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">กรุณาเลือกเรื่อง...!</div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <p class="card-text mb-0"><b>รายละเอียด : </b></p>
                                    </div>
                                    <div class="col-md">
                                        <textarea class="form-control" rows="4"
                                        v-model="data.ticketDetail"
                                        v-bind:class="{'is-invalid': input.invalid.detail}"
                                        :disabled="input.disabled.detail"></textarea>
                                        <div class="invalid-feedback">กรุณาใส่รายละเอียด...!</div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <p class="card-text mb-0"><b>เบอร์ติดต่อกลับ : </b></p>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="เบอร์โทรที่สามารถติดต่อกลับได้หรือเบอร์ภายใน"
                                        v-model="data.ticketCallback"
                                        v-bind:class="{'is-invalid': input.invalid.callback}"
                                        :disabled="input.disabled.callback">
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="row py-2">
                                    <div class="col-auto mt-2"><p class="card-text m-0"><b>ไฟล์ : </b></p></div>
                                    <div class="col">
                                        <form enctype="multipart/form-data">
                                            <div class="form-group col-md-12 p-0 mb-0">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Upload</span>
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" id="ticketUploadFile" class="custom-file-input"
                                                        v-on:change="onFileChange"
                                                        :disabled="input.disabled.file">
                                                        <label for="ticketUploadFile" class="custom-file-label">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row pt-3 mb-0 justify-content-center">
                    <button type="button" class="col-md-3 btn btn-primary mr-3"
                    v-on:click="createTicket()"
                    v-html="input.createBtn.content"
                    :disabled="input.createBtn.disabled">
                        <i class="fas fa-paper-plane"></i><span class="ml-2">ส่งคำขอ Ticket</span>
                    </button>
                </div>

            </div>
        </div>

        <!-- Modal : #modal-ticket-send -->
        <div class="modal fade" id="modal-ticket-send" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-circle text-danger mr-2"></i><span>ยืนยันการส่งคำขอ Ticket </span>
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success">
                            <i class="fas fa-check mr-2"></i> ยืนยัน
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i> ยกเลิก
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
        this.checkSeessionLogin();
        this.getMasterSubjectTicket();

        var today = new Date();
        this.date = today.getFullYear()+'-'+('0'+(today.getMonth()+1)).slice(-2)+'-'+('0'+(today.getDate())).slice(-2);

    },
    data(){
        return{
            date: '',
            data: {
                userId: this.$session.get('user'),
                subjectId: '',
                ticketDetail: '',
                ticketCallback: '',
                fileName: null,
                file: null
            },
            input: {
                disabled: {
                    subject: false,
                    detail: false,
                    callback: false,
                    file: false
                },
                invalid: {
                    subject: false,
                    detail: false,
                    callback: false
                },
                createBtn: {
                    content: '<i class="fas fa-paper-plane"></i><span class="ml-2">ส่งคำขอ Ticket</span>',
                    disabled: false
                }
            },
            subject: {}
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
        getMasterSubjectTicket(){
            axios.get('/api/user/ticket/subject/all')
            .then(response => { this.subject = response.data });
        },
        onFileChange(e){

            this.data.file = e.target.files[0];

            if(this.data.file.type == 'image/jpeg'){
                this.data.fileName = this.data.file.name;
                $('#ticketUploadFile').next('.custom-file-label').html(this.data.fileName);
            }
            else{
                this.$swal({icon: 'error', text: 'กรุณาเลือกรูปนามสกุล .jpg เท่านั้น'});
                this.data.file = null;
                this.data.fileName = null;
                $('#ticketUploadFile').next('.custom-file-label').html('Choose file');
            }

        },
        createTicket(){

            if(!this.checkSeessionLogin()){
                return;
            }

            this.input.invalid.subject = this.data.subjectId == '' ? true : false;
            this.input.invalid.detail = this.data.ticketDetail == '' ? true : false;
            this.input.invalid.callback = this.data.ticketCallback == '' ? true : false;

            if(!this.input.invalid.subject && !this.input.invalid.detail){
                this.$swal({
                    title: 'ยืนยันการส่งคำขอ Ticket',
                    html:
                        '<p style="font-size: 0.9rem; margin: 15px 0 5px 0;"><u><b>ข้อกำหนดและเงื่อนไข</b></u></p>' +
                        '<p style="font-size: 0.9rem;">การดำเนินการอาจทำให้ข้อมูลสูญหาย กรุณา Backup ข้อมูลก่อน</p>',
                    input: 'checkbox',
                    inputValue: 0,
                    inputPlaceholder:
                        'ยอมรับข้อกำหนดและเงื่อนไข',
                    inputValidator: (result) => {
                        return !result && 'คุณต้องยอมรับข้อกำหนดและเงื่อนไขก่อน'
                    },
                    text: 'คุณต้องการเปิด Ticket ของคุณหรือไม่',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if(result.isConfirmed){

                        let btnContent = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                            btnContent += ' กำลังบันทึกข้อมูล...';

                        this.input.disabled.subject = true;
                        this.input.disabled.detail = true;
                        this.input.disabled.callback = true;
                        this.input.disabled.file = true;
                        this.input.createBtn.content = btnContent;
                        this.input.createBtn.disabled = true;

                        const config = {
                            headers: { 'content-type': 'multipart/form-data' }
                        }

                        let formData = new FormData();
                        formData.append('file',this.data.file);
                        formData.append('file_name',this.data.fileName);
                        formData.append('subject_id',this.data.subjectId);
                        formData.append('ticket_detail',this.data.ticketDetail);
                        formData.append('ticket_callback',this.data.ticketCallback);
                        formData.append('user',this.data.userId);

                        axios.post('/api/user/ticket/create',formData,config)
                        .then(response => {
                            if(response.data.status){
                                this.$swal({
                                    title: 'ระบบเปิด Ticker เรียบร้อยแล้ว',
                                    text: 'Ticket เลขที่ '+response.data.paper_no,
                                    icon: 'success',
                                    showCancelButton: true,
                                    cancelButtonColor: '#E5AF47',
                                    cancelButtonText: 'เปิด Ticket อีกครั้ง',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'ไปหน้า ประวัติคำขอ Ticket'
                                }).then((result) => {
                                    if(result.isConfirmed)
                                        window.location.href = 'history';
                                    else if(result.isDismissed){
                                        this.data.subjectId = '';
                                        this.data.ticketDetail = '';
                                        this.data.fileName = null;
                                        this.data.file = null;
                                        this.input.disabled.subject = false;
                                        this.input.disabled.detail = false;
                                        this.input.disabled.callback = false;
                                        this.input.disabled.file = false;
                                        this.input.createBtn.content = '<i class="fas fa-paper-plane"></i><span class="ml-2">ส่งคำขอ Ticket</span>';
                                        this.input.createBtn.disabled = false;
                                        $('#ticketUploadFile').next('.custom-file-label').html('Choose file');
                                    }
                                });
                            }
                            else{
                                this.$swal({
                                    title: response.data.title,
                                    text: response.data.message,
                                    icon: 'error'
                                });
                                this.input.disabled.subject = false;
                                this.input.disabled.detail = false;
                                this.input.disabled.callback = false;
                                this.input.disabled.file = false;
                                this.input.createBtn.content = '<i class="fas fa-paper-plane"></i><span class="ml-2">ส่งคำขอ Ticket</span>';
                                this.input.createBtn.disabled = false;
                            }
                        })
                        .catch(response => {
                            this.$swal({icon: 'error', 'text': 'ไม่สามารถเปิด Ticket ได้'});
                            this.input.disabled.subject = false;
                            this.input.disabled.detail = false;
                            this.input.disabled.callback = false;
                            this.input.disabled.file = false;
                            this.input.createBtn.content = '<i class="fas fa-paper-plane"></i><span class="ml-2">ส่งคำขอ Ticket</span>';
                            this.input.createBtn.disabled = false;
                        });

                    }
                });
            }

        }
    }
}
</script>
