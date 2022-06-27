<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fab fa-wpforms"></i><span class="ml-2">Memo Form</span>
            </div>
            <div class="card-body p-5">

                <div class="row mb-3">
                    <div class="col-md-12">

                        <div class="row m-0 mb-3">
                            <div class="col-md-4">
                                <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>MEMO</b></p>
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
                                        v-model="inpt.field.date"
                                        readonly>
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
                                        <span class="ml-5">{{emp.fullnameTh}}</span>
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
                                        <span class="ml-5">{{emp.fullnameEng}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">แผนก</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{emp.department}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span><input type="text" class="form-control" placeholder="กรุณาระบุตำแหน่งงาน..."
                                        v-model='inpt.field.position'
                                        :readonly="inpt.readonly.position"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">เบอร์โทรศัพท์</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span><input type="text" class="form-control" placeholder="กรุณาระบุเบอร์โทรศัพท์..."
                                        v-model="inpt.field.tel"
                                        :readonly="inpt.readonly.tel"></span>
                                    </div>
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
                                    <input type="text" class="form-control bottom-dotted-full" maxlength="255"
                                    v-model="inpt.field.object"
                                    :readonly="inpt.readonly.object">
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-sm float-right mb-3"
                                v-on:click="addLineDetail()"
                                :disabled="inpt.disabled.addLine">
                                    <i class="fas fa-plus-circle mr-2"></i> เพิ่มรายการ
                                </button>
                                <table class="table table-hover table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan="3" class="text-center">รายละเอียด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(dt,index) in inpt.field.detail" :key="index">
                                            <th scope="row" class="text-center align-middle" width="7%">{{index+1}}</th>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" maxlength="255"
                                                v-model="dt.text"
                                                :readonly="inpt.readonly.detail">
                                            </td>
                                            <td class="text-center align-middle" width="7%">
                                                <button class="btn btn-danger btn-sm"
                                                v-on:click="removeLineDetail(index)"
                                                :disabled="inpt.disabled.removeLine">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr class="mb-3">
                        <div class="row justify-content-center py-3">
                            <button type="button" class="col-md-3 btn btn-primary"
                            v-on:click="saveMemo()"
                            :disabled="inpt.disabled.save">
                                <i class="fas fa-paper-plane mr-2"></i> ส่ง Memo
                            </button>
                        </div>

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
        this.getDate();
        this.getDataEmployee();
    },
    data(){
        return{
            emp: {
                id: this.$session.get('userId'),
                fullnameTh: '',
                fullnameEng: '',
                department: ''
            },
            inpt: {
                field: {
                    empId: this.$session.get('userId'),
                    date: '',
                    position: '',
                    tel: '',
                    object: '',
                    remark: '',
                    detail: [
                        { text: '' }
                    ]
                },
                readonly: {
                    position: false,
                    tel: false,
                    object: false,
                    remark: false,
                    detail: false
                },
                disabled: {
                    addLine: false,
                    removeLine: false,
                    save: false
                }
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
        getDate(){
            let date = new Date();
            this.inpt.field.date = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+(date.getDate())).slice(-2);
        },
        checkSession(){
            if(!this.$session.exists() || !this.$session.has('userId')){
                this.$swal({icon: 'error', title: 'Session login expaired!', text: 'กรุณา Login ใหม่'});
                return true;
            }
            else
                return false
        },
        getDataEmployee(){

            if(this.checkSession())
                return;

            axios.get('/api/admin/it/employee/'+this.emp.id+'/edit')
            .then(response => {
                this.emp.fullnameTh = response.data.it_emp_name_th+' '+response.data.it_emp_surname_th;
                this.emp.fullnameEng =  response.data.it_emp_name_eng+' '+response.data.it_emp_surname_eng;
                this.emp.department = response.data.it_dept_name;
                this.inpt.field.tel = response.data.it_emp_tel;
            });

        },
        addLineDetail(){
            this.inpt.field.detail.push({text: ''});
        },
        removeLineDetail(index){

            let tempData = [];

            for(var i = 0; i < this.inpt.field.detail.length; i++)
                if(i != index)
                    tempData.push(this.inpt.field.detail[i]);

            this.inpt.field.detail = tempData;

        },
        validateForm(){

            if(this.inpt.field.position == ''){
                this.$swal({icon: 'warning', text: 'กรุณาใส่ตำแหน่ง'});
                return false;
            }
            else if(this.inpt.field.tel == ''){
                this.$swal({icon: 'warning', text: 'กรุณาใส่เบอร์โทรศัพท์'});
                return false;
            }
            else if(this.inpt.field.object == ''){
                this.$swal({icon: 'warning', text: 'กรุณาใส่จุดประสงค์ในการดำเนินการ'});
                return false;
            }

            if(this.inpt.field.detail.length > 0){

                for(let i = 0; i < this.inpt.field.detail.length; i++){
                    if(this.inpt.field.detail[i].text == ''){
                        this.$swal({icon: 'warning', text: 'กรุณาใส่รายละเอียดแถวที่ '+(i+1)});
                        return false;
                    }
                }

            }
            else{
                this.$swal({icon: 'warning', text: 'กรุณาใส่รายละเอียด'});
                return false;
            }

            return true;

        },
        saveMemo(){

            if(!this.checkSeessionLogin())
                return;

            if(!this.validateForm())
                return;

            this.$swal({
                title: 'ยืนการส่ง Memo',
                text: "คุณต้องการส่ง Memo ในตอนนี้หรือไม่!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {

                    this.inpt.readonly.position = true;
                    this.inpt.readonly.tel = true;
                    this.inpt.readonly.object = true;
                    this.inpt.readonly.remark = true;
                    this.inpt.readonly.detail = true;
                    this.inpt.disabled.addLine = true;
                    this.inpt.disabled.removeLine = true;
                    this.inpt.disabled.save = true;

                    axios.post('/api/user/memo/create',this.inpt.field)
                    .then(response => {
                        if(response.data.Status){
                            this.$swal({
                                title: 'บันทึก Memo เรียบร้อยแล้ว',
                                text: "MEMO No. "+response.data.paper_no,
                                icon: 'success',
                                showDenyButton: true, showCancelButton: false,
                                confirmButtonColor: '#3085d6', confirmButtonText: 'ไปหน้าประวัติคำขอ Memo',
                                denyButtonText: 'เปิด Memo อีกครั้ง'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.href = '/user/memo/history';
                                }
                                else{
                                    this.inpt.field.object = '';
                                    this.inpt.field.remark = '';
                                    this.inpt.field.detail = [{text: ''}]
                                    this.inpt.readonly.position = false;
                                    this.inpt.readonly.tel = false;
                                    this.inpt.readonly.object = false;
                                    this.inpt.readonly.remark = false;
                                    this.inpt.readonly.detail = false;
                                    this.inpt.disabled.addLine = false;
                                    this.inpt.disabled.removeLine = false;
                                    this.inpt.disabled.save = false;
                                }
                            });
                        }
                        else{

                            var errormsg = '';

                            if(response.data.exception == 'sqlError')
                                errormsg = '[Error] '+response.data.message;

                            this.$swal({icon: 'error', title: 'ไม่สามารถบันทึก Memo ได้', text: errormsg});
                            this.inpt.readonly.position = false;
                            this.inpt.readonly.tel = false;
                            this.inpt.readonly.object = false;
                            this.inpt.readonly.remark = false;
                            this.inpt.readonly.detail = false;
                            this.inpt.disabled.addLine = false;
                            this.inpt.disabled.removeLine = false;
                            this.inpt.disabled.save = false;

                        }
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', title: 'ไม่สามารถบันทึก Memo ได้'});
                        this.inpt.readonly.position = false;
                        this.inpt.readonly.tel = false;
                        this.inpt.readonly.object = false;
                        this.inpt.readonly.remark = false;
                        this.inpt.readonly.detail = false;
                        this.inpt.disabled.addLine = false;
                        this.inpt.disabled.removeLine = false;
                        this.inpt.disabled.save = false;
                    });

                }
            });

        }
    }
}
</script>
