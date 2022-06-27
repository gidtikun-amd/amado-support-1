<template>
    <div>
        
        <div class="card mb-3">
            <div class="card-header">
                <i class="fab fa-wpforms"></i><span class="ml-2">Job Request Form</span>
            </div>
            <div class="card-body p-5">

                <div class="row mb-3">
                    <div class="col-md-12">

                        <div class="row m-0 mb-3">
                            <div class="col-md-4">
                            <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>JOB REQUEST FORM</b></p>
                                <p><b>AMADO GROUP</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">เลขที่เอกสาร</div>
                                    <div class="bottom-dotted-125 text-center">
                                        <input type="text" class="form-control" value="Auto Generate" readonly>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-125 text-center">
                                        <input type="text" class="form-control" v-model="paper.date" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อ-นามสกุล (T)</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <div class="bottom-dotted-full">
                                        <span class="ml-5" v-html="employee.emp_fullnameTh">นาย พงศกร เหล่านิยมไทย</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อ-นามสกุล (E)</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <div class="bottom-dotted-full">
                                        <span class="ml-5" v-html="employee.emp_fullnameEng">Mr. Pongsakorn Laoniyomthai</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">แผนก</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span v-html="employee.emp_department">IT</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span><input type="text" class="form-control" placeholder="กรุณาระบุตำแหน่งงาน..." 
                                            v-model="paper.position"
                                            v-bind:class="{'is-invalid': validation.position}"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">เบอร์โทรศัพท์</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span><input type="text" class="form-control" placeholder="กรุณาระบุเบอร์โทรศัพท์..." readonly 
                                            v-model="employee.emp_tel"
                                            v-bind:class="{'is-invalid': validation.tel}"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">
                                    จุดประสงค์การดำเนินการ
                                    <span class="text-danger" v-show="validation.obj"><i class="fas fa-exclamation-circle"></i>  กรุณาระบุจุดประสงค์</span>
                                </p> 
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_1" name="optobj" value="1"
                                                v-model="paper.obj"
                                            >
                                            <label class="custom-control-label text-black" for="opt_1">สร้างผู้ใช้ใหม่</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_2" name="optobj" value="2"
                                                v-model="paper.obj"
                                            >
                                            <label class="custom-control-label text-black" for="opt_2">แก้ไขผู้ใช้งาน</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_3" name="optobj" value="3"
                                                v-model="paper.obj"
                                            >
                                            <label class="custom-control-label text-black" for="opt_3">ลบผู้ใช้งาน</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_4" name="optobj" value="4"
                                                v-model="paper.obj"
                                            >
                                            <label class="custom-control-label text-black" for="opt_4">พัฒนาใหม่</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_5" name="optobj" value="5"
                                                v-model="paper.obj"
                                            >
                                            <label class="custom-control-label text-black" for="opt_5">ซื้อ/ติดตั้งอุปกรณ์</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_6" name="optobj" value="6"
                                                v-model="paper.obj"
                                            >
                                            <label class="custom-control-label text-black" for="opt_6">สร้างอีเมลใหม่</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" class="custom-control-input" id="opt_7" name="optobj" value="7"
                                                        v-model="paper.obj"
                                                    >
                                                    <label class="custom-control-label text-black" for="opt_7">อื่น ๆ : </label>
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <textarea class="form-control mt-2 w-100" rows="5" id="comment" placeholder="กรุณาระบุข้อความอื่น ๆ ..."
                                                    v-model="paper.other"
                                                    :disabled="paper.obj != '7' ? true : false"
                                                ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">
                                    รายการที่ร้องขอดังนี้ 
                                    <span class="text-danger" v-show="validation.list"><i class="fas fa-exclamation-circle"></i> กรุณาระบุรายละเอียดให้ครบถ้วน</span></p>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success float-right mb-3"
                                    v-on:click="addListRow()"
                                    :disabled="btn.btnAddRowsDisabled"
                                ><i class="fas fa-plus mr-2"></i> เพิ่มรายการที่ร้องขอ</button>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">ลำดับ</th>
                                                    <th class="text-center">รายละเอียด</th>
                                                    <th class="text-center">จำนวน</th>
                                                    <th class="text-center">หน่วย</th>
                                                    <th class="text-center" width="5%"><i class="fas fa-sort-down"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(inlist, index) in paper.list" :key="index">
                                                    <th class="w-5 text-center align-middle">{{index+1}}</th>
                                                    <td class="ml-5">
                                                        <input type="text" class="form-control" placeholder="กรุณาระบุรายละเอียด..." v-model="inlist.detail">
                                                    </td>
                                                    <td class="w-10 text-center">
                                                        <input type="number" class="form-control text-center" value="1" v-model="inlist.qty">
                                                    </td>
                                                    <td class="w-10 text-center">
                                                        <input type="text" class="form-control text-center" placeholder="ระบุหน่วย..." v-model="inlist.unit">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger"
                                                        v-on:click="removeListRow(index)">
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
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">ความต้องการ</p>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <textarea class="form-control w-100" rows="5" id="comment" placeholder="กรุณาระบุความต้องการ..."
                                            v-model="paper.demand"
                                            v-bind:class="{'is-invalid':validation.demand}"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">ประโยชน์ / เหตุผลที่ขอ</p>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <textarea class="form-control w-100" rows="5" id="comment" placeholder="กรุณาระบุประโยชน์ / เหตุผลที่ขอ..."
                                            v-model="paper.reason"
                                            v-bind:class="{'is-invalid': validation.reason}"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">แนบไฟล์</p>
                            </div>
                            <div class="col-md-12">
                                <form enctype="multipart/form-data" multiple="multiple">
                                    <div class='form-group'>
                                        <!-- <div class="input-group"> -->
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="jobreqUploadFile" 
                                                aria-describedby="btnJobreqUploadFile"
                                                v-on:change="onFileChange">
                                                <label class="custom-file-label" for="jobreqUploadFile" >Choose file</label>
                                            </div>
                                            <!-- <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-secondary" id="btnJobreqUploadFile"
                                                @click.prevent="uploadJobreqFile"
                                                :disabled="btn.btnUploadFile">
                                                    <i class="fas fa-cloud-upload-alt"></i> Upload
                                                </button>
                                            </div>
                                        </div> -->
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <ul class="list-group">
                                    <li class="list-group-item py-1"
                                        v-for="(list,index) in paper.files" :key="index"
                                    >
                                        <b>#{{index+1}}</b> : {{list.file_name}}
                                        <a href="javascript:;" class="float-right"
                                            v-on:click="removeJobreqFile(index)"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row justify-content-center py-3">
                            <button type="button" class="col-md-3 btn btn-primary"
                                v-on:click="confirmRequest()"
                                v-html="btn.btnSaveTxt"
                                :disabled="btn.btnSaveDisabled"
                            >
                                <i class="fas fa-paper-plane mr-2"></i> ส่ง Job Request
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        <!-- Modal : #modal_cfm_sendjobreq -->
        <div class="modal fade" id="modal_cfm_sendjobreq">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fas fa-exclamation-circle text-danger mr-2"></i>Alert</h4>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">ยืนยันการส่ง Job Request หรือไม่ ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success"
                            v-on:click="setRequestSave()"
                        ><i class="fas fa-check mr-2"></i>ยืนยัน</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal_resp_sendjobreq -->
        <div class="modal fade" id="modal_resp_sendjobreq">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-html="response.title"><i class="fas fa-clipboard-check text-success mr-2"></i>System response</h4>
                    </div>
                    <div class="modal-body" v-html="response.content">
                        <p class="mb-0">
                            เลขที่เอกสาร <strong v-html="response.paper_id"></strong><br>
                            ระบบกำลังนำทางไปหน้าประวัติคำขอ Job Request 
                        </p>
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

        var today = new Date();
        var date = today.getFullYear()+'-'+('0'+(today.getMonth()+1)).slice(-2)+'-'+('0'+(today.getDate())).slice(-2);
        this.paper.date = date;

        this.getDataEmployee();

    },
    data(){
        return{
            employee: {
                emp_id: this.$session.get('userId'),
                emp_fullnameTh: '',
                emp_fullnameEng: '',
                emp_department: '',
                emp_tel: ''
            },
            paper: {
                date: '',
                position: '',
                obj: '',
                other: '',
                list: [
                    { detail: '', qty: 1, unit: '' }
                ],
                demand: '',
                reason: '',
                files: [],
                temps: {}
            },
            btn: {
                btnAddRowsDisabled: false,
                btnSaveTxt: '<i class="fas fa-paper-plane mr-2"></i> ส่ง Job Request',
                btnSaveDisabled: false,
                btnUploadFile: true
            },
            validation: {
                position: false,
                tel: false,
                obj: false,
                list: false,
                demand: false,
                reason: false
            },
            response: {
                paper_id: '',
                title: '',
                content: ''
            }
        }
    },
    methods:{
        alertLogin(){  
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
        },
        getDataEmployee(){
            axios.get('/api/admin/it/employee/'+this.employee.emp_id+'/edit')
            .then(response => {
                this.employee.emp_fullnameTh = response.data.it_emp_name_th+' '+response.data.it_emp_surname_th;
                this.employee.emp_fullnameEng =  response.data.it_emp_name_eng+' '+response.data.it_emp_surname_eng;
                this.employee.emp_department = response.data.it_dept_name;
                this.employee.emp_tel = response.data.it_emp_tel;
            });
        },
        addListRow(){
            this.paper.list.push({ detail: '', qty: 1, unit: '' })
        },
        removeListRow(index){

            let tempdata = [];

            for(var i = 0; i < this.paper.list.length; i++)
                if(i != index)
                    tempdata.push(this.paper.list[i]);

            this.paper.list = tempdata;

        },
        onFileChange(e){

            this.paper.temps = e.target.files[0];
            // $('#jobreqUploadFile').next('.custom-file-label').html(this.paper.temps.name);            
            // this.btn.btnUploadFile = false;

            this.paper.files.push({
                file_name: this.paper.temps.name,
                file_data: this.paper.temps
            });
            
            $('#jobreqUploadFile').next('.custom-file-label').html('Choose file');
            // this.btn.btnUploadFile = true;

        },
        uploadJobreqFile(e){

            e.preventDefault();
            
            this.paper.files.push({
                file_name: this.paper.temps.name,
                file_data: this.paper.temps
            });
            
            $('#jobreqUploadFile').next('.custom-file-label').html('Choose file');
            this.btn.btnUploadFile = true;

        },
        removeJobreqFile(index){

            var temp = this.paper.files;
            this.paper.files = [];

            for(var i = 0; i < temp.length; i++){
                if(i != index)
                    this.paper.files.push(temp[i]);
            }

        },
        confirmRequest(){

            this.validation.position = ( this.paper.position == '' ? true : false );
            this.validation.tel = ( this.employee.emp_tel == '' ? true : false );
            this.validation.obj = ( this.paper.obj == '' ? true : false );
            this.validation.demand = ( this.paper.demand == '' ? true : false );
            this.validation.reason = ( this.paper.reason == '' ? true : false );

            let checkList = false;

            for(var i = 0; i < this.paper.list.length; i++){

                if( this.paper.list[i].detail == '' )
                    checkList = true;
                else if( this.paper.list[i].qty == '' || this.paper.list[i].qty <= 0 )
                    checkList = true;
                else if( this.paper.list[i].unit == '' )
                    checkList = true;

                if( checkList )
                    break;

            }

            this.validation.list = checkList;

            if( 
                !this.validation.position &&
                !this.validation.tel &&
                !this.validation.obj &&
                !this.validation.demand &&
                !this.validation.reason &&
                !this.validation.list
            ){
                $('#modal_cfm_sendjobreq').modal('show');
            }
            
        },
        setRequestSave(){

            let str = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ระบบกำลังบันทึกข้อมูล...';

            this.btn.btnSaveTxt = str;
            this.btn.btnSaveDisabled = true;
            this.btn.btnAddRowsDisabled = true;

            $('#modal_cfm_sendjobreq').modal('hide');

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            var formData = new FormData();
            formData.append('emp_id',this.employee.emp_id);
            formData.append('position',this.paper.position);
            formData.append('tel',this.employee.emp_tel);
            formData.append('obj',this.paper.obj);
            formData.append('other',this.paper.other);
            formData.append('demand',this.paper.demand);
            formData.append('reason',this.paper.reason);
            formData.append('date',this.paper.date);            
            // formData.append('list',this.paper.list);

            for(var i = 0; i < this.paper.list.length; i++){
                formData.append('list['+i+'][0]',this.paper.list[i].detail);
                formData.append('list['+i+'][1]',this.paper.list[i].qty);
                formData.append('list['+i+'][2]',this.paper.list[i].unit);
            }
            
            for(var i = 0; i < this.paper.files.length; i++){
                formData.append('files['+i+'][0]',this.paper.files[i].file_name);
                formData.append('files['+i+'][1]',this.paper.files[i].file_data);
            }

            // for (var pair of formData.entries()) {
            //     console.log(pair[0]+ ', ' + pair[1]); 
            // }
            
            // var postData = {
            //     emp_id: this.employee.emp_id,
            //     position: this.paper.position,
            //     tel: this.employee.emp_tel,
            //     obj: this.paper.obj,
            //     other: this.paper.other,
            //     demand: this.paper.demand,
            //     reason: this.paper.reason,
            //     list: this.paper.list,
            //     date: this.paper.date
            //     // ,formData
            // }
            
            axios.post('/api/admin/it/jobrequest'
            ,formData
            // ,postData
            ,config
            )
            .then(response => {

                var respData = response.data;

                if( respData.Status ){
                    var title = '<i class="fas fa-clipboard-check text-success mr-2"></i>System response';
                    var strBtn = '<i class="fas fa-paper-plane mr-2"></i> ส่ง Job Request';
                    var strContent = '<p class="mb-0">เลขที่เอกสาร <strong>'+respData.paper_id+'</strong><br>';
                        strContent += 'ระบบกำลังนำทางไปหน้าประวัติคำขอ Job Request</p>';   
                    setTimeout(function(){ window.location.href = '/user/history_jobrequest' }, 3000);
                }
                else{
                    var title = '<i class="fas fa-exclamation-circle text-danger mr-2"></i>System response</h4>';
                    var strBtn = '<i class="fas fa-paper-plane mr-2"></i> ส่ง Job Request';
                    var strContent = '<center><i class="fas fa-exclamation-circle text-danger"></i> ไม่สามารถบันทึกคำขอ Job Request ได้';

                    if( respData.exception == 'sqlError' ){
                        strContent += '<br><br><b><font color="red">[Error]</font></b><br>';
                        strContent += respData.message;
                    }
                    else if( respData.exception == 'login.fail' ){
                        strContent += '<br><br><b><font color="red">[Login Fail!!!]</font></b><br>';
                        strContent += 'Login agin please.';
                        this.alertLogin();
                    }
                    

                    strContent += '</center>';

                }

                this.response.title = title;     
                this.response.content = strContent;           
                this.btn.btnSaveTxt = strBtn;
                this.btn.btnSaveDisabled = false;
                this.btn.btnAddRowsDisabled = false;
                $('#modal_resp_sendjobreq').modal('show');

            })
            .catch(response => {
                var title = '<i class="fas fa-exclamation-circle text-danger mr-2"></i>System response</h4>';
                var strBtn = '<i class="fas fa-paper-plane mr-2"></i> ส่ง Job Request';
                this.response.title = title;
                this.response.content = '<p class="mb-0">ไม่สามารถบันทึกคำขอ Job Request ได้</p>';
                this.btn.btnSaveTxt = strBtn;
                this.btn.btnSaveDisabled = false;
                this.btn.btnAddRowsDisabled = false;
                $('#modal_resp_sendjobreq').modal('show');
            });

        }
    }
}
</script>