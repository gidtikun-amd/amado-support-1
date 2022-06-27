<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">จัดการเรื่องการแจ้ง Ticket</span>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#modal-subject">
                            <i class="fas fa-plus mr-2"></i>เพิ่มเรื่องการแจ้ง
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" width="7%">#</th>
                                    <th scope="col" class="text-left">เพิ่มเรื่องการแจ้ง</th>
                                    <th scope="col" class="text-center" width="10%">สถานะ</th>
                                    <th scope="col" class="text-center" width="10%"><i class="fas fa-sort-down"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(sub,index) in subject" :key="index">
                                    <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                    <td class="align-middle">{{sub.it_ticket_subject_name}}</td>
                                    <td class="text-center align-middle">
                                        <a href="javascript:;" v-if="sub.it_ticket_subject_active == 1"
                                        v-on:click="updateStatusSubject(index,0)">
                                            <span class="badge badge-pill badge-tbl-custom badge-success">Active</span>
                                        </a>
                                        <a href="javascript:;" v-else
                                        v-on:click="updateStatusSubject(index,1)">
                                            <span class="badge badge-pill badge-tbl-custom badge-danger">Inactive</span>
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">
                                        <!-- <button type="button" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button> -->
                                        <button type="button" class="btn btn-danger btn-sm"
                                        v-on:click="removeSubject(index)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-footer small text-muted">
            </div>

        </div>

        <!-- Modal : #modal-subject -->
        <div class="modal fade" id="modal-subject" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus mr-2 text-success"></i><span>เพิ่มเรื่องการแจ้ง</span>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><strong>เรื่องการแจ้ง</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control"
                                            v-model="input.text"
                                            v-bind:class="{'is-invalid': input.invalid.text}"
                                            :disabled="input.disabled.text">
                                            <div class="invalid-feedback">กรุณาใส่ เรื่องการแจ้ง....!</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><strong>Active status</strong></label>
                                        <div class="col-sm-4">
                                            <select class="form-control"
                                            v-model="input.status"
                                            v-bind:class="{'is-invalid': input.invalid.status}"
                                            :disabled="input.disabled.status">
                                                <option value="" selected disabled>เลือก</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <div class="invalid-feedback">กรุณาเลือก Active status....!</div>
                                        </div>
                                    </div>
                                    <hr class="pb-1">
                                    <div class="row justify-content-md-center m-1">
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary btn-block"
                                            v-on:click="saveSubject()"
                                            v-html="input.content.btnSave"
                                            :disabled="input.disabled.btnSave">
                                                <i class="fas fa-save mr-2"></i>บันทึก
                                            </button>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-block" data-dismiss="modal"
                                            :disabled="input.disabled.btnClose">
                                                <i class="fas fa-ban mr-2"></i>ยกเลิก
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
        this.getSubjectAll();
    },
    data(){
        return{
            input: {
                text: '',
                status: '',
                invalid: {
                    text: false,
                    status: false
                },
                disabled: {
                    text: false,
                    status: false,
                    btnSave: false,
                    btnClose: false
                },
                content: {
                    btnSave: '<i class="fas fa-save mr-2"></i>บันทึก'
                }
            },
            subject: []
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
        getSubjectAll(){
            axios.get('/api/admin/it/ticket/subject/all')
            .then(response => {this.subject = response.data});
        },
        saveSubject(){

            if(!this.checkSeessionLogin())
                return;

            this.input.invalid.text = this.input.text == '' ? true : false;
            this.input.invalid.status = this.input.status == '' ? true : false;

            if(!this.input.invalid.text && !this.input.invalid.status){

                let btnContent = '<span class="spinner-border spinner-border-sm mr-3" role="status" aria-hidden="true"></span>';
                    btnContent += 'กำลังบันทึกข้อมูล';

                this.input.content.btnSave = btnContent;
                this.input.disabled.text = true;
                this.input.disabled.status = true;
                this.input.disabled.btnSave = true;
                this.input.disabled.btnClose = true;

                axios.post('/api/admin/it/ticket/subject/save',{
                    subject_text: this.input.text,
                    subject_active: this.input.status
                })
                .then(response => {
                    this.$swal({icon: 'success', text: 'บันทึกเรื่องการแจ้งเรียบร้อยแล้ว'});
                    this.input.content.btnSave = '<i class="fas fa-save mr-2"></i>บันทึก';
                    this.input.text = '';
                    this.input.status = '';
                    this.input.disabled.text = false;
                    this.input.disabled.status = false;
                    this.input.disabled.btnSave = false;
                    this.input.disabled.btnClose = false;
                    $('#modal-subject').modal('hide');
                    this.getSubjectAll();
                })
                .catch(response => {
                    this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกเรื่องการแจ้งได้'});
                })

            }

        },
        updateStatusSubject(index,status){

            if(!this.checkSeessionLogin())
                return;

            axios.post('/api/admin/it/ticket/subject/update/active',{
                subject_id: this.subject[index].it_ticket_subject_id,
                subject_active: status
            })
            .then(response => {this.subject[index].it_ticket_subject_active = status});
        },
        removeSubject(index){

            if(!this.checkSeessionLogin())
                return;

            this.$swal({
                title: 'ยืนยันการลบเรื่องการแจ้ง',
                text: 'ต้องการลบเรื่องการแจ้ง '+this.subject[index].it_ticket_subject_name+' หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed){
                    axios.get('/api/admin/it/ticket/subject/delete/'+this.subject[index].it_ticket_subject_id)
                    .then(response => {
                        if(response.data.status){
                            this.$swal({icon: 'success', text: 'ลบเรื่องการแจ้งเรียบร้อยแล้ว'});
                            this.getSubjectAll();
                        }
                        else
                            this.$swal({icon: 'error', text: response.data.message});
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถลบเรื่องการแจ้งได้'});
                    });
                }
            });
        }
    }
}
</script>
