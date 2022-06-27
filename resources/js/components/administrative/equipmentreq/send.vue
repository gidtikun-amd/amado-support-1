<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-clipboard-list"></i><span class="ml-2">ขอเบิกอุปกรณ์สำนักงาน</span>
            </div>
            <div class="card-body p-5">

                <div class="row justify-content-center pb-3" v-show="!active">
                    <div class="col-md-6 d-md-none d-lg-block h-50">
                        <div class="card text-white bg-amado o-hidden h-100">
                            <div class="card-body">
                                <div class="card-body-icon pr-5 pt-4">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div class="mr-5">
                                    <h5>ยังไม่สามารถขอเบิกอุปกรณ์สำนักงานได้</h5>
                                    <p>ขอเบิกได้ในวัน <u>จันทร์ - พุธ</u></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3" v-show="active">
                <!-- <div class="row mt-3 mb-3"> -->
                    <div class="col-md-12">
                        <div class="row m-0 mb-3">
                            <div class="col-md-4">
                                <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>ใบเบิกอุปกรณ์สำนักงาน</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-125 text-center">
                                        {{paper.date}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mt-5 mb-3">
                            <div class="col-md-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ชื่อผู้เบิก</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <div class="bottom-dotted-full">
                                        <span class="ml-5">{{employee.it_emp_name_th}} {{employee.it_emp_surname_th}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-sm float-right mb-2 px-3"
                                data-toggle="modal" data-target="#modal-items"
                                :disabled="lockInput.selectItem">
                                    <i class="fas fa-clipboard-check mr-2"></i>เลือกอุปกรณ์สำนักงาน
                                </button>
                                <table class="table table-bordered table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle" rowspan="2" width="7%">ลำดับ</th>
                                            <th class="text-center align-middle" rowspan="2">รายการ</th>
                                            <th class="text-center align-middle" colspan="3" width="35%">จำนวนเบิก</th>
                                            <th class="text-center align-middle" rowspan="2" width="5%"><i class="fas fa-caret-down"></i></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle">จำนวน</th>
                                            <th class="text-center align-middle">หน่วยนับ</th>
                                            <th class="text-center align-middle">Budget</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(dt,index) in paper.detail" :key="index">
                                            <th class="text-center align-middle">{{index+1}}</th>
                                            <td class="align-middle">{{dt.item}}</td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm" min="1"
                                                v-model="dt.qty"
                                                v-on:change="calBudget(index)"
                                                v-on:keyup="calBudget(index)"
                                                :readonly="lockInput.formQty">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                v-model="dt.unit"
                                                :readonly="/*lockInput.formUnit*/true">
                                            </td>
                                            <td class="text-center align-middle">
                                                {{dt.ttbudget | numFormat('0.00')}}
                                            </td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-danger btn-sm"
                                                v-on:click="unSelectItem(index)"
                                                :disabled="lockInput.formRmLine">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="px-3">
                                                <span class="text-amado"><b>รวม Budget : </b></span>
                                                <span><b>{{paper.netBedget | numFormat('0.00')}}</b></span>

                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row justify-content-center py-3">
                            <button type="button" class="col-md-3 btn btn-primary"
                            v-on:click="sendEquipSeq()"
                            v-html="lockInput.btnSendContent"
                            :disabled="lockInput.btnSend">
                                <i class="fas fa-paper-plane mr-2"></i> ส่งเบิกอุปกรณ์สำนักงาน
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal : #modal-items -->
        <div class="modal fade" id="modal-items" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-clipboard-check text-primary mr-2"></i>เลือกอุปกรณ์สำนักงาน</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="cancleSelectItem()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <b>รายการอุปกรณ์สำนักงาน</b>
                                    </div>
                                    <div class="card-body">
                                        <button type="button" class="btn btn-outline-primary btn-sm mb-1 mr-1"
                                        v-for="(sit,index) in select" :key="index"
                                        v-on:click="unSelectItem(index)">
                                            {{sit.item}}
                                            <i class="fas fa-times-circle ml-2"></i>
                                        </button>

                                        <i class="fas fa-exclamation-triangle text-danger mr-1"
                                        v-show="select.length <= 0"></i>
                                        <b class="text-danger" v-show="select.length <= 0">ไม่มีรายการอุปกรณ์สำนักงาน</b>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm float-right ml-1" data-dismiss="modal"
                                v-on:click="cancleSelectItem()">
                                    <i class="fas fa-times mr-1"></i> ยกเลิก
                                </button>
                                <button type="button" class="btn btn-primary btn-sm float-right" data-dismiss="modal">
                                    <i class="fas fa-check mr-1"></i> ตกลง
                                </button>
                            </div>
                        </div>
                    </div>
                    <div  v-bind:style="style.table" class="scrollbar-custom bg-white">
                        <table class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <td class="text-center" width="10%">เลือก</td>
                                    <td class="text-center">อุปกรณ์สำนักงาน</td>
                                    <td class="text-center" width="10%">หน่วย</td>
                                    <td class="text-center" width="10%">Budget</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="p-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm"
                                            placeholder="ใส่รหัสหรืออุปกรณ์สำนักงาน"
                                            v-model="filter.search"
                                            v-on:keyup="filterItems()"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-filter"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(it,index) in filter.data" :key="index">
                                    <td class="text-center">
                                        <button class="btn btn-outline-primary btn-sm"
                                        v-on:click="selectItem(index)">
                                            <i class="fas fa-hand-pointer mr-1"></i> เลือก
                                        </button>
                                    </td>
                                    <td class="align-middle">{{it.code}} {{it.item}}</td>
                                    <td class="text-center align-middle">{{it.unit}}</td>
                                    <td class="text-center align-middle">{{it.budget}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>

import numeral from 'numeral';
import numFormat from 'vue-filter-number-format';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    mounted(){
        this.checkDayActive();
        this.checkSeessionLogin();
    },
    data(){
        return{
            style: {
                table: {
                    position: 'relative',
                    height: '450px',
                    overflow: 'auto',
                    display: 'block',
                    borderTop: 'thin solid #d8d8d8'
                }
            },
            active: false,
            employee: {},
            items: [],
            select: [],
            paper: {
                emp_id: this.$session.get('userId'),
                netBedget: 0.00,
                date: '',
                detail: [],
            },
            lockInput: {
                selectItem: false,
                formQty: false,
                formUnit: false,
                formRmLine: false,
                btnSendContent: '<i class="fas fa-paper-plane mr-2"></i> ส่งเบิกอุปกรณ์สำนักงาน',
                btnSend: false
            },
            filter: {
                search: '',
                data: []
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
        checkDayActive(){

            const d = new Date(),
                    day = d.getDay();
            this.active = ( day == 1 || day == 2 || day == 3 ) ? true : false;

            if(this.active){
                this.paper.date = d.getFullYear()+'-'+('0'+(d.getMonth()+1)).slice(-2)+'-'+('0'+(d.getDate())).slice(-2);
                this.getDataEmployee();
                this.getItemList();
            }
        },
        getDataEmployee(){
            axios.get('/api/admin/it/employee/'+this.paper.emp_id+'/edit')
            .then(response => {
                this.employee = response.data;
            });
        },
        getItemList(){
            axios.get('/api/ad/items/active')
            .then(response => {
                this.items = response.data;
                this.filter.data = response.data;
            });
        },
        filterItems(){

            if(this.filter.search != ''){

                let temp = [];

                for(var i = 0; i < this.items.length; i++){
                    if(
                        this.items[i].item.search(this.filter.search) > -1
                        || this.items[i].code.search(this.filter.search) > -1
                    )
                        temp.push(this.items[i]);
                }

                this.filter.data = temp;

            }
            else this.filter.data = this.items;

        },
        checkInSelect(index){

            let bool = false;

            for(var i = 0; i < this.select.length; i++)
                if(this.select[i].id == this.filter.data[index].id)
                    bool = true;

            return bool;

        },
        selectItem(index){

            if(this.checkInSelect(index))
                return;

            let select = {
                id: this.filter.data[index].id,
                item: this.filter.data[index].item,
                qty: 1,
                unit: this.filter.data[index].unit,
                budget: this.filter.data[index].budget,
                ttbudget: this.filter.data[index].budget
            };

            this.paper.netBedget += parseFloat(this.filter.data[index].budget);
            this.select.push(select);
            this.paper.detail.push(select);

        },
        calBudget(index){
            this.paper.netBedget -= this.paper.detail[index].ttbudget;
            this.paper.detail[index].ttbudget = this.paper.detail[index].budget * this.paper.detail[index].qty;
            this.paper.netBedget += this.paper.detail[index].ttbudget;
        },
        cancleSelectItem(){
            this.select = [];
            this.paper.detail = [];
        },
        unSelectItem(index){

            let tempDataSelect = [],
                tempDataDetail = [];

            this.paper.netBedget = 0.00;

            for(var i = 0; i< this.select.length; i++){
                if(i != index){
                    tempDataSelect.push(this.select[i]);
                    tempDataDetail.push(this.paper.detail[i]);
                    this.paper.netBedget += parseFloat(this.paper.detail[i].ttbudget);
                }
            }

            this.select = tempDataSelect;
            this.paper.detail = tempDataDetail;

        },
        sendEquipSeq(){

            if(!this.checkSeessionLogin())
                return;

            if(this.paper.detail.length <= 0){
                this.$swal({icon: 'error', text: 'กรุณาเลือกอุปกรณ์สำนักงาน'});
                return;
            }

            for(var i = 0; i < this.paper.detail.length; i++){
                if(this.paper.detail[i].qty <= 0 || this.paper.detail[i].qty == ''){
                   this.$swal({
                       icon: 'error',
                       text: 'กรุณาใส่จำนวนในแถวที่ '+(i+1)
                       });
                    return;
                }
            }


            this.$swal({
                title: 'ยืนยันส่งเบิกอุปกรณ์สำนักงาน',
                text: "กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนส่ง",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if(result.isConfirmed){

                    let btnContent = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
                        btnContent += 'ระบบกำลังบันทึกข้อมูล....';

                    this.lockInput.btnSendContent = btnContent;
                    this.lockInput.selectItem = true;
                    this.lockInput.formQty = true;
                    this.lockInput.formUnit = true;
                    this.lockInput.formRmLine = true;
                    this.lockInput.btnSend = true;

                    axios.post('/api/ad/equip-req/create',{
                        date: this.paper.date,
                        user: this.paper.emp_id,
                        dept: this.$session.get('departmentId'),
                        netbudget: this.paper.netBedget,
                        detail: this.paper.detail
                    })
                    .then(response => {
                        this.responseSuccess(response.data);
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถขอเบิกอุปกรณ์สำนักงานได้'});
                        this.lockInput.btnSendContent = '<i class="fas fa-paper-plane mr-2"></i> ส่งเบิกอุปกรณ์สำนักงาน';
                        this.lockInput.selectItem = false;
                        this.lockInput.formQty = false;
                        this.lockInput.formUnit = false;
                        this.lockInput.formRmLine = false;
                        this.lockInput.btnSend = false;
                    });

                }
            });

        },
        responseSuccess(resp){
            this.$swal({
                title: 'ส่งเบิกอุปกรณ์สำนักงานเรียบร้อยแล้ว',
                text: "เลขที่เอกสาร : "+resp.paper_no,
                icon: 'success',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ไปหน้าประวัติคำขอเบิกอุปกรณ์สำนักงาน',
                denyButtonText: 'ขอเบิกอุปกรณ์สำนักงานอีกครั้ง',
            })
            .then((result) => {
                if(result.isConfirmed){
                    location.href = '/administrative/equipment-req/user/history';
                }
                else{
                    location.reload();
                }
            });
        }
    }
}
</script>
