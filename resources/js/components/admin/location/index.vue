<template>
    <div>
        
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการข้อมูล Location</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <form class="form-inline">
                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกคำค้นหา..." aria-label="Search" id="searchTbTxt"
                                 v-on:keyup="searchTable()"
                            >
                            <button class="btn btn-sm btn-amado my-2 my-sm-0" type="button" disabled>
                                <i class="fas fa-search"></i>
                                <span class="ml-2">ค้นหา</span>
                            </button>
                        </form>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-sm btn-success float-right"
                            v-on:click="setActionModal('create','')"
                        >
                            <i class="fas fa-plus"></i>
                            <span class="ml-2">เพิ่ม Location</span>
                        </button>
                    </div>
                </div>
                <table class="table table-hover" id="tblLocat">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">รหัส Location</th>
                            <th scope="col">คำอธิบาย Location</th>
                            <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(locat, index) in locations" :key="index">
                            <th scope="row">{{index+1}}</th>
                            <td>{{locat.it_locat_id}}</td>
                            <td>{{locat.it_locat_name}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm" 
                                    v-on:click="getLocationEdit(locat.it_locat_id)"
                                ><i class="fas fa-pen"></i></button>
                                <button type="button" class="btn btn-danger btn-sm"
                                    v-on:click="getLocationDeleteConfirm(index)"
                                ><i class="fas fa-minus"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal : #modal-location -->
        <div class="modal fade" id="modal-location">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-html="html.modalActionTitle">
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="row m-3 pb-3">
                                <div class="form-group col-md-4">
                                    <label for="uname">รหัส Location : </label>
                                    <input type="text" class="form-control" placeholder="กรอกรหัส Location..."
                                        v-model="modal.locat_id"
                                        v-bind:class="{'is-invalid':html.modalActionValId}"
                                        :readonly="html.modalActionLocatIdReadonly"
                                    >
                                </div>
                                <div class="invalid-feedback">กรุณากรอกรหัส Location...!</div>
                                <div class="form-group col-md-8">
                                    <label for="uname">คำอธิบาย Location : </label>
                                    <input type="text" class="form-control" placeholder="กรอกคำอธิบาย Location..."
                                        v-model="modal.locat_name"
                                        v-bind:class="{'is-invalid':html.modalActionValName}"
                                    >
                                </div>
                                <div class="invalid-feedback">กรุณากรอกคำอธิบาย Location...!</div>
                            </div>
                            <div class="row justify-content-md-center m-3">
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success w-100"
                                        v-on:click="setLocationSave()"
                                        v-html="html.modalActionBtnSaveTxt"
                                        :disabled="html.modalActionBtnSaveDisabled"
                                    >
                                        <i class="fas fa-save"></i> บันทึก
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-danger w-100"
                                        :disabled="html.modalActionBtnClaseDisabled"
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

        <!-- Modal : #modal-remove -->
        <div class="modal fade" id="modal-remove" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <i class="fas fa-clipboard-check"> ยืนยันการลบข้อมูล Loacation</i>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-html="html.modalDeleteContent">
                    </div>
                    <div class="modal-footer">            
                        <button type="button" class="btn btn-primary"
                            v-on:click="setDeleteLocation()"
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
        this.getAllLocation();
    },
    data(){
        return{
            html: {
                modalActionTitle: '',
                modalActionLocatIdReadonly: false,
                modalActionBtnSaveTxt: '',
                modalActionBtnSaveDisabled: false,
                modalActionBtnClaseDisabled: false,
                modalActionValId: false,
                modalActionValName: false,
                modalPregressBtnClose: false,
                modalProgressContent: '',
                modalDeleteContent: ''
            },
            modal: {
                method: '',
                locat_id: '',
                locat_name: ''
            },
            locations: [],
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
        getAllLocation(){
            axios.get('/api/admin/it/location')
            .then(response => {
                this.locations = response.data;
            });
        },
        getLocationEdit(locatId){
            axios.get('/api/admin/it/location/'+locatId+'/edit')
            .then(response => {
                this.setActionModal('edit',response.data);
            });
        },
        setActionModal(method,arrData){
            
            $('#modal-location').modal('show');

            this.modal.method = method;
            this.html.modalActionLocatIdReadonly = ( method == 'edit' ? true : false );
            this.html.modalActionBtnSaveDisabled = false;
            this.html.modalActionBtnClaseDisabled = false;
            this.html.modalActionValId = false;
            this.html.modalActionValName = false;

            this.modal.locat_id = ( method == 'edit' ? arrData.it_locat_id : '' );
            this.modal.locat_name = ( method == 'edit' ? arrData.it_locat_name : '' );
            
            if( this.modal.method == 'create' ){
                this.html.modalActionTitle = '<i class="fas fa-plus text-success"></i> <span class="ml-2">เพิ่ม Location</span>';
                this.html.modalActionBtnSaveTxt = '<i class="fas fa-save"></i> บันทึก';
            }
            else{
                this.html.modalActionTitle = '<i class="fas fa-pen text-warning"></i> <span class="ml-2">แก้ไข Location</span>';
                this.html.modalActionBtnSaveTxt = '<i class="fas fa-save"></i> แก้ไข';
            }

        },
        setLocationSave(){

            if(!this.checkSeessionLogin())
                return;

            this.html.modalActionBtnSaveDisabled = true;
            this.html.modalActionBtnClaseDisabled = true;

            var strBtnSave = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ระบบกำลังบันทึกข้อมูล...';
            this.html.modalActionBtnSaveTxt = strBtnSave;

            this.html.modalActionValId = ( this.modal.locat_id == '' ? true : false );
            this.html.modalActionValName = ( this.modal.locat_name == '' ? true : false );

            if( !this.html.modalActionValId && !this.html.modalActionValName ){

                $('#modal-location').modal('hide');
                $('#modal-progress').modal('show');

                var contentAction = ( this.modal.action == 'create' ? 'ระบบกำลังบันทึกข้อมูล' : 'ระบบกำลังอัพเดทข้อมูล' );
                var pcontent = '<div class="progress">';
                pcontent += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"';
                pcontent += 'aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';
                pcontent += '</div> <strong>'+contentAction+'</strong>';
                
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = false;

                if( this.modal.method == 'create' )
                    this.setCreateLocation();
                else 
                    this.setUpdateLocation();

            }
            else{

                if( this.modal.method == 'create' )
                    this.html.modalActionBtnSaveTxt = '<i class="fas fa-save"></i> บันทึก';
                else 
                    this.html.modalActionBtnSaveTxt = '<i class="fas fa-save"></i> แก้ไข';
                
                this.html.modalActionBtnSaveDisabled = false;
                this.html.modalActionBtnClaseDisabled = false;

            }

        },
        setCreateLocation(){
            axios.post('/api/admin/it/location',{ 
                locat_id: this.modal.locat_id,
                locat_name: this.modal.locat_name 
            })
            .then(response => {
                var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบบันทึกข้อมูลเรียบร้อยแล้ว';
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = true;
                this.getAllLocation();
            })
            .catch(response => {
                console.log(response);
                var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = true;
            });
        },
        setUpdateLocation(){
            axios.put('/api/admin/it/location/'+this.modal.locat_id,{
                locat_name: this.modal.locat_name
            })
            .then(response => {
                var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบอัพเดทข้อมูลเรียบร้อยแล้ว';
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = true;
                this.getAllLocation();
            })
            .catch(response => {
                console.log(response);
                var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถอัพเดทข้อมูลได้';
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = true;
            });
        },
        getLocationDeleteConfirm(arrIndex){

            if(!this.checkSeessionLogin())
                return;

            var pcontent = 'คุณต้องการลบข้อมูล Loacation <strong>'+this.locations[arrIndex].it_locat_name+'</strong> หรือไม่';
            this.html.modalDeleteContent = pcontent;
            this.modal.locat_id = this.locations[arrIndex].it_locat_id;

            $('#modal-remove').modal('show');

        },
        setDeleteLocation(){

            $('#modal-remove').modal('hide');

            var pcontent = '<div class="progress">';
            pcontent += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"';
            pcontent += 'aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';
            pcontent += '</div> <strong>ระบบกำลังลบข้อมูล</strong>';
            
            this.html.modalProgressContent = pcontent;
            this.html.modalPregressBtnClose = false;
            $('#modal-progress').modal('show');

            axios.delete('/api/admin/it/location/'+this.modal.locat_id)
            .then(response => {

                this.actionResponse = response.data;

                if( this.actionResponse.Status ){
                    var pcontent = '<i class="fas fa-check-circle text-success"></i> ระบบลบข้อมูลเรียบร้อยแล้ว';
                    this.html.modalProgressContent = pcontent;
                    this.html.modalPregressBtnClose = true;
                    this.getAllLocation();
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
        ,
        searchTable(){
            var input, filter, table, trs;

            input = document.getElementById('searchTbTxt');
            filter = input.value.toUpperCase();
            table = document.getElementById('tblLocat');
            trs = table.tBodies[0].getElementsByTagName('tr');

            for(var i = 0; i < trs.length; i++) {

                var tds = trs[i].getElementsByTagName("td");
                trs[i].style.display = "none";

                for(var i2 = 0; i2 < tds.length; i2++){            
                    if(tds[i2].innerHTML.toUpperCase().indexOf(filter) > -1) {                
                        trs[i].style.display = "";
                        continue;      
                    }
                }

            }
        }

    }
}
</script>