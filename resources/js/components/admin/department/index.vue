<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการข้อมูลฝ่าย</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-sm btn-success float-right"
                            v-on:click="setModalAction('create','')"
                        >
                            <i class="fas fa-plus"></i>
                            <span class="ml-2">เพิ่มฝ่าย</span>
                        </button>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" width="10%">#</th>
                            <th scope="col">ฝ่าย</th>
                            <th scope="col" class="text-center" width="10%"><i class="fas fa-ellipsis-h"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(dept, index) in departments" :key="index">
                            <th scope="row">{{index+1}}</th>
                            <td>{{dept.it_dept_name}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm"
                                    v-on:click="getDeptFixedId(index)"
                                ><i class="fas fa-pen"></i></button>
                                <button type="button" class="btn btn-danger btn-sm"
                                    v-on:click="setDeleteDeptConfirm(index)"
                                >
                                    <i class="fas fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal : #modal-progress -->
        <div class="modal fade" id="modal-progress" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <i class="fab fa-windows"> System message</i>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            v-show="html.modalPregressBtnClose"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center" v-html="html.modalProgressContent">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-department -->
        <div class="modal fade" id="modal-department" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-html="html.modalActionTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="row m-3 pb-3">
                                <div class="form-group col-md-6">
                                    <label for="uname">ชื่อฝ่าย : </label>
                                    <input type="text" class="form-control" placeholder="กรอกชื่อฝ่าย..." 
                                        v-model="modal.action.dept_name"
                                        v-bind:class="{'is-invalid':html.modalActionVlDeptName}"
                                    >
                                    <div class="invalid-feedback">กรุณากรอกชื่อฝ่าย..!</div>
                                </div>
                            </div>
                            <div class="row justify-content-md-center m-3">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-success w-100"
                                        v-on:click="setDeptSave()"
                                        v-html="html.modalActionTxtBtnSave"
                                        :disabled="html.modalActionDisabledBtnSave"
                                    >
                                        <i class="fas fa-save"></i> บันทึก
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100" data-dismiss="modal"
                                        :disabled="html.modalActionDisabledBtnClose"
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
                        <i class="fas fa-clipboard-check"> ยืนยันการลบข้อมูลฝ่าย</i>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-html="html.modalDeleteContent">
                    </div>
                    <div class="modal-footer">            
                        <button type="button" class="btn btn-primary"
                            v-on:click="setDeleteDepartment()"
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
        this.getAllDepartment();
    },
    data(){
        return{
            html:{
                modalActionTitle: '',
                modalActionTxtBtnSave: '',
                modalActionVlDeptName: false,
                modalActionDisabledBtnSave: false,
                modalActionDisabledBtnClose: false,
                modalDeleteContent: '',
                modalPregressBtnClose: '',
                modalProgressContent: ''
            },
            modal: {
                action: { method: '', dept_id: '', dept_name: '' }
            },
            departments: [],
            actionResponse: []
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
        getAllDepartment(){
            axios.get('/api/admin/it/department')
            .then(response => {
                this.departments = response.data;
            });
        },
        getDeptFixedId(arrIndex){
            axios.get('/api/admin/it/department/'+this.departments[arrIndex].it_dept_id+'/edit')
            .then(response => {
                console.log(response);
                this.setModalAction('edit',response.data);
            });
        },
        setModalAction(method,dataDept){

            $('#modal-department').modal('show');

            this.html.modalActionVlDeptName = false;
            this.html.modalActionDisabledBtnSave = false;
            this.html.modalActionDisabledBtnClose = false;

            this.modal.action.method = method;
            this.modal.action.dept_id = ( method == 'edit' ? dataDept.it_dept_id : '' );
            this.modal.action.dept_name = ( method == 'edit' ? dataDept.it_dept_name : '' );

            if( method == 'create' ){
                this.html.modalActionTxtBtnSave = '<i class="fas fa-save"></i> บันทึก';
                this.html.modalActionTitle = '<i class="fas fa-plus text-success"></i> <span class="ml-2">เพิ่มฝ่าย</span>';
            }
            else{
                this.html.modalActionTxtBtnSave = '<i class="fas fa-save"></i> แก้ไข';
                this.html.modalActionTitle = '<i class="fas fa-pen text-warning"></i> <span class="ml-2">แก้ไขฝ่าย</span>';
            }

        },
        setDeptSave(){

            if(!this.checkSeessionLogin())
                return;

            this.html.modalActionDisabledBtnSave = true;
            this.html.modalActionDisabledBtnClose = true;

            var strBtnSave = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ระบบกำลังบันทึกข้อมูล...';
            this.html.modalActionTxtBtnSave = strBtnSave;

            this.html.modalActionVlDeptName = ( this.modal.action.dept_name == '' ? true : false );

            if( !this.html.modalActionVlDeptName ){

                $('#modal-department').modal('hide');
                $('#modal-progress').modal('show');

                var contentAction = ( this.modal.action == 'create' ? 'ระบบกำลังบันทึกข้อมูล' : 'ระบบกำลังอัพเดทข้อมูล' );
                var pcontent = '<div class="progress">';
                pcontent += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"';
                pcontent += 'aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';
                pcontent += '</div> <strong>'+contentAction+'</strong>';
                
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = false;
                
                if( this.modal.action.method == 'create' )
                    this.setCreateDepartment();
                else 
                    this.setUpateDepartment();
                
            }
            else{

                if( this.modal.action.method == 'create' )
                    this.html.modalActionTxtBtnSave = '<i class="fas fa-save"></i> บันทึก';
                else 
                    this.html.modalActionTxtBtnSave = '<i class="fas fa-save"></i> แก้ไข';
                
                this.html.modalActionDisabledBtnSave = false;
                this.html.modalActionDisabledBtnClose = false;

            }

        },
        setCreateDepartment(){
            axios.post('/api/admin/it/department',{ dept_name: this.modal.action.dept_name })
            .then(response => {
                var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบบันทึกข้อมูลเรียบร้อยแล้ว';
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = true;
                this.getAllDepartment();
            })
            .catch(response => {
                console.log(response);
                var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = true;
            });
        },
        setUpateDepartment(){
            axios.put('/api/admin/it/department/'+this.modal.action.dept_id,{ dept_name: this.modal.action.dept_name })
            .then(response => {
                var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบอัพเดทข้อมูลเรียบร้อยแล้ว';
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = true;
                this.getAllDepartment();
            })
            .catch(response => {
                console.log(response);
                var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถอัพเดทข้อมูลได้';
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = true;
            })
        },
        setDeleteDeptConfirm(arrIndex){

            if(!this.checkSeessionLogin())
                return;

            var pcontent = 'คุณต้องการลบข้อมูลฝ่าย <strong>'+this.departments[arrIndex].it_dept_name+'</strong> หรือไม่';
            this.html.modalDeleteContent = pcontent;
            this.modal.action.dept_id = this.departments[arrIndex].it_dept_id;

            $('#modal-remove').modal('show');

        },
        setDeleteDepartment(){

            $('#modal-remove').modal('hide');

            var pcontent = '<div class="progress">';
            pcontent += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"';
            pcontent += 'aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';
            pcontent += '</div> <strong>ระบบกำลังลบข้อมูล</strong>';
            
            this.html.modalProgressContent = pcontent;
            this.html.modalPregressBtnClose = false;
            $('#modal-progress').modal('show');

            axios.delete('/api/admin/it/department/'+this.modal.action.dept_id)
            .then(response => {

                this.actionResponse = response.data;

                if( this.actionResponse.Status ){
                    var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบลบข้อมูลเรียบร้อยแล้ว';
                    this.html.modalProgressContent = pcontent;
                    this.html.modalPregressBtnClose = true;
                    this.getAllDepartment();
                }
                else{
                    var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ';
                    pcontent += this.actionResponse.ErrMessage;
                    this.html.modalProgressContent = pcontent;
                    this.html.modalPregressBtnClose = true;
                }

            })
            .catch(response => {
                console.log(response);
                var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถลบข้อมูลได้';
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = true;
            });

        }

    }
}
</script>