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
                                    <div class="col-md-12 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_1" name="optobj" value="1"
                                                v-model="paper.obj"
                                            >
                                            <label class="custom-control-label text-black" for="opt_1">ซ่อมแซม</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="opt_2" name="optobj" value="2"
                                                v-model="paper.obj"
                                            >
                                            <label class="custom-control-label text-black" for="opt_2">ซื้อ/ติดตั้งอุปกรณ์</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" class="custom-control-input" id="opt_3" name="optobj" value="7"
                                                        v-model="paper.obj"
                                                    >
                                                    <label class="custom-control-label text-black" for="opt_3">อื่น ๆ : </label>
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
                                                        <input type="number" class="form-control text-center" value="1" v-model="inlist.qty" min="1">
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
        this.checkSeessionLogin();

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
                reason: ''
            },
            btn: {
                btnAddRowsDisabled: false,
                btnSaveTxt: '<i class="fas fa-paper-plane mr-2"></i> ส่ง Job Request',
                btnSaveDisabled: false
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
        confirmRequest(){

            this.validation.position = ( this.paper.position == '' ? true : false );
            this.validation.tel = ( this.employee.emp_tel == '' ? true : false );
            this.validation.obj = ( this.paper.obj == '' ? true : false );
            this.validation.demand = ( this.paper.demand == '' ? true : false );
            this.validation.reason = ( this.paper.reason == '' ? true : false );

            let checkList = false;

            if(this.paper.list.length > 0){

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
            }
            else
                checkList = true;

            this.validation.list = checkList;

            if(this.paper.position == ''){
                this.$swal({icon: 'error', text: 'กรุณาระบุ ตำแหน่ง'});
            }
            else if(this.paper.obj == ''){
                this.$swal({icon: 'error', text: 'กรุณาระบุ จุดประสงค์การดำเนินการ'});
            }
            else if(this.paper.demand == ''){
                this.$swal({icon: 'error', text: 'กรุณาระบุ ความต้องการ'});
            }
            else if(this.paper.reason == ''){
                this.$swal({icon: 'error', text: 'กรุณาระบุ ประโยชน์ / เหตุผลที่ขอ'});
            }
            else if(this.validation.list){
                this.$swal({icon: 'error', text: 'กรุณาระบุรายละเอียดให้ครบถ้วน'});
            }

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

            if(!this.checkSeessionLogin())
                return;

            let str = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ระบบกำลังบันทึกข้อมูล...';

            this.btn.btnSaveTxt = str;
            this.btn.btnSaveDisabled = true;
            this.btn.btnAddRowsDisabled = true;

            $('#modal_cfm_sendjobreq').modal('hide');

            axios.post('/api/ad/job-req/create',{
                emp_id: this.employee.emp_id,
                position: this.paper.position,
                tel: this.employee.emp_tel,
                obj: this.paper.obj,
                other: this.paper.other,
                demand: this.paper.demand,
                reason: this.paper.reason,
                list: this.paper.list,
                date: this.paper.date
            })
            .then(response => {

                let respData = response.data;

                if( respData.Status ){

                    let title = '<i class="fas fa-clipboard-check text-success mr-2"></i>System response';
                    let strContent = '<p class="mb-0">เลขที่เอกสาร <strong>'+respData.paper_id+'</strong><br>';
                    strContent += 'ระบบกำลังนำทางไปหน้าประวัติคำขอ Job Request</p>';
                    let strBtn = '<i class="fas fa-paper-plane mr-2"></i> ส่ง Job Request';

                    this.response.title = title;
                    this.response.content = strContent;
                    this.btn.btnSaveTxt = strBtn;
                    this.btn.btnSaveDisabled = false;
                    this.btn.btnAddRowsDisabled = false;
                    $('#modal_resp_sendjobreq').modal('show');

                    setTimeout(function(){ window.location.href = '/administrative/job-req/user/history' }, 3000);

                }
                else{
                    let title = '<i class="fas fa-exclamation-circle text-danger mr-2"></i>System response</h4>';
                    let strBtn = '<i class="fas fa-paper-plane mr-2"></i> ส่ง Job Request';
                    this.response.title = title;
                    this.response.content = '<p class="mb-0">ไม่สามารถบันทึกคำขอ Job Request ได้</p>';
                    this.btn.btnSaveTxt = strBtn;
                    this.btn.btnSaveDisabled = false;
                    this.btn.btnAddRowsDisabled = false;
                    $('#modal_resp_sendjobreq').modal('show');
                }

            })
            .catch(response => {
                let title = '<i class="fas fa-exclamation-circle text-danger mr-2"></i>System response</h4>';
                let strBtn = '<i class="fas fa-paper-plane mr-2"></i> ส่ง Job Request';
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
