<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fab fa-wpforms"></i><span class="ml-2">Item Request Form</span>
            </div>
            <div class="card-body p-5">

                <div class="row mb-3">
                    <div class="col-md-12">

                        <div class="row m-0 mb-3">
                            <div class="col-md-4">
                            <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>ITEM REQUEST FORM</b></p>
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
                                        <input type="text" class="form-control"
                                            v-model="paper.header.today"
                                        readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อ-นามสกุล</div>
                                    <div class="bottom-dotted-650 ">
                                        <span class="ml-5" v-html="paper.header.emp_fullname">กมลชนก ขุนทอง</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <input type="text" class="form-control bottom-dotted-290 text-center" v-model="paper.header.emp_position">
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3">
                            <div class="col-md-auto">
                                <div class="mt-2">
                                    <div class="mr-1 d-inline-block">จุดประสงค์ในการดำเนินการ</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <input type="text" class="form-control bottom-dotted-full" v-model="paper.header.itmreq_obj">
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-success float-right mb-3"
                                            v-on:click="addLineDetail()"
                                        ><i class="fas fa-plus mr-2"></i> เพิ่มรายการ Item</button>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">ลำดับ<br>(No.)</th>
                                                    <th class="text-center">บาร์โค้ด<br>(Barcode)</th>
                                                    <th class="text-center">ชื่อสินค้า<br>(Item)</th>
                                                    <th class="text-center">ดำเนินการ<br>(Operation)</th>
                                                    <th class="text-center">โปรโมชั่น<br>วันเริ่ม - สิ้นสุด</th>
                                                    <th class="text-center">โปรแกรม<br>(Application)</th>
                                                    <th class="text-center"><i class="fas fa-sort-down"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(inlist,index) in paper.detail" :key="index">
                                                    <td class="w-5 text-center">{{index+1}}</td>
                                                    <td class="ml-5 text-center">
                                                        <input type="text" class="form-control" placeholder="กรุณาระบุบาร์โค้ด..."
                                                            v-model="inlist.barcode"
                                                        >
                                                    </td>
                                                    <td class="w-30">
                                                        <!-- <input type="text" class="form-control" placeholder="กรุณาระบุชื่อสินค้า..."
                                                            v-model="inlist.item"
                                                        > -->
                                                        <textarea class="form-control" rows="3" placeholder="กรุณาระบุชื่อสินค้า..." 
                                                        v-model="inlist.item"></textarea>
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <select class="form-control" v-model="inlist.action">
                                                                    <option value="1">สร้าง</option>
                                                                    <option value="2">ยกเลิก</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <date-picker value-type="format" format="YYYY-MM-DD" placeholder="วันที่เริ่มต้น"
                                                            v-model="inlist.prom_sdate"
                                                        ></date-picker>
                                                        <p class="mb-1 mt-1">ถึงวันที่</p>
                                                        <date-picker value-type="format" format="YYYY-MM-DD" placeholder="วันที่สิ้นสุด"
                                                            v-model="inlist.prom_edate"
                                                        ></date-picker>
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <select class="form-control" v-model="inlist.app">
                                                                    <option value="1">AMADO</option>
                                                                    <option value="2">Kiosk</option>
                                                                    <option value="3">Dynamics AX</option>
                                                                    <option value="4">Sale Force</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger"
                                                        v-on:click="removeLineDetail(index)">
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

                        <hr class="mb-3">

                        <div class="row justify-content-center py-3">
                            <button type="button" class="col-md-3 btn btn-primary" 
                                v-on:click="setSaveItmreq()"
                            >
                                <i class="fas fa-paper-plane mr-2"></i> ส่ง Item Request
                            </button>
                        </div>

                    </div>
                </div>

            </div>

        </div>


        <!-- Modal : #modal_cfm_senditemreq -->
        <div class="modal fade" id="modal_cfm_senditemreq" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fas fa-exclamation-circle text-danger mr-2"></i>Alert</h4>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">ยืนยันการส่ง Item Request หรือไม่ ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success"
                            v-on:click="callApiInstItemReq()"
                        >
                            <i class="fas fa-check mr-2"></i>ยืนยัน
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal_resp_senditemreq -->
        <div class="modal fade" id="modal_resp_senditemreq" >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-html="progress.title"><i class="fas fa-clipboard-check text-success mr-2"></i>System response</h4>
                    </div>
                    <div class="modal-body" v-html="progress.content">
                        <p class="mb-0">
                            เลขที่เอกสาร <strong></strong><br>
                            ระบบกำลังนำทางไปหน้าประวัติคำขอ Item Request 
                        </p>
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

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    mounted(){        
        this.get_today();
        this.getDataEmployee();
    },
    data(){
        return{
            paper: {
                header: {
                    today: '',
                    emp_id: this.$session.get('userId'),
                    emp_fullname: '',
                    emp_position: '',
                    itmreq_obj: ''
                },
                detail: [
                    { barcode: '', item: '', action: '1', prom_sdate: '', prom_edate: '', app: '1' }
                ]
            },
            progress: {
                title: '',
                content: ''
            },
            employee: {}
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
        get_today(){
            let date = new Date();
            this.paper.header.today = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+(date.getDate())).slice(-2);
        },
        getDataEmployee(){
            axios.get('/api/admin/it/employee/'+this.paper.header.emp_id+'/edit')
            .then(response => {
                this.employee = response.data;
                this.paper.header.emp_fullname = this.employee.it_emp_name_th+' '+this.employee.it_emp_surname_th;
            });
        },
        addLineDetail(){
            this.paper.detail.push({ barcode: '', item: '', action: '1', prom_sdate: '', prom_edate: '', app: '1' });
        },
        removeLineDetail(index){

            let tempdata = [];

            for(var i = 0; i < this.paper.detail.length; i++)
                if(i != index)
                    tempdata.push(this.paper.detail[i]);

            this.paper.detail = tempdata;

        },
        setSaveItmreq(){            

            if(!this.checkSeessionLogin())
                return;

            if( this.paper.header.emp_position == '' ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกตำแหน่ง' });
                return;
            }

            if( this.paper.header.itmreq_obj == '' ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกจุดประสงค์ในการดำเนินการ' });
                return;
            }

            if(this.paper.detail.length <= 0){
                this.$swal({ icon: 'error', text: 'กรุณาเพิ่มรายการ Item' });
                return;
            }

            let check_detail = false,
                check_date = false,
                line = 1;

            for( var i = 0; i < this.paper.detail.length; i++ ){
                if(
                    this.paper.detail[i].item == ''
                    || this.paper.detail[i].action == ''
                    || this.paper.detail[i].prom_sdate == ''
                    || this.paper.detail[i].prom_edate == ''
                    || this.paper.detail[i].app == ''
                ){
                    check_detail = true;
                    line = i+1;
                    break;
                }
                else if( this.paper.detail[i].prom_sdate > this.paper.detail[i].prom_edate ){
                    check_date = true;
                    line = i+1;
                    break;
                }
            }

            if( check_detail ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกข้อมูลรายการ Item แถวที่ '+line+' ให้ครบถ้วน' });
                return;
            }
            else if( check_date ){
                this.$swal({ icon: 'error', text: 'กรุณากรอกข้อมูลโปรโมชั่น วันเริ่ม - สิ้นสุด Item แถวที่ '+line+' ให้ถูกต้อง' });
                return;
            }

            $('#modal_cfm_senditemreq').modal('show');

        },
        callApiInstItemReq(){

            $('#modal_cfm_senditemreq').modal('hide');
            $('#modal_resp_senditemreq').modal('show');

            var title = '<i class="fas fa-clipboard-check text-success mr-2"></i>System response';

            var content = '<div class="progress">';
                content += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" '; 
                content += 'aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';                
                content += '</div>';
                content += '<div class="text-center text-primary"><strong> ระบบกำลังบันทึกข้อมูล</strong></div>';
        
            this.progress.title = title;
            this.progress.content = content;

            axios.post('/api/admin/it/itemrequest',this.paper)
            .then(response => {

                let respData = response.data;

                if( respData.Status ){
                    content = '<p class="mb-0">เลขที่เอกสาร <strong>'+respData.paper_id+'</strong><br>';
                    content += 'ระบบกำลังนำทางไปหน้าประวัติคำขอ Job Request</p>';
                    this.progress.content = content;
                    setTimeout(function(){ window.location.href = '/user/history_itemrequest' }, 3000);
                }
                else{
                    title = '<i class="fas fa-exclamation-circle text-danger mr-2"></i>System response</h4>';
                    content = '<center><i class="fas fa-exclamation-circle text-danger"></i> ไม่สามารถบันทึกคำขอ Job Request ได้';

                    if( respData.exception == 'sqlError' ){
                        content += '<br><br><b><font color="red">[Error]</font></b><br>';
                        content += respData.message;
                    }

                    content += '</center>';

                    this.progress.title = title;
                    this.progress.content = content;
                    // setTimeout(function(){ $('#modal_resp_senditemreq').modal('hide'); }, 3000);
                }

            })
            .catch(response => {
                title = '<i class="fas fa-exclamation-circle text-danger mr-2"></i>System response</h4>';
                content = '<p class="mb-0">ไม่สามารถบันทึกคำขอ Job Request ได้</p>';
                this.progress.title = title;
                this.progress.content = content;
                setTimeout(function(){ $('#modal_resp_senditemreq').modal('hide'); }, 3000);
            });

        }
    }
}
</script>