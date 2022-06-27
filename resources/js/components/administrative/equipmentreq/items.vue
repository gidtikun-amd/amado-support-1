<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">จัดการอุปกรณ์สำนักงาน</span>
            </div>
            <div class="card-body">

                <div class="row" v-show="progress.main">
                    <div class="col-md-12 text-center mt-2">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3" v-show="!progress.main">
                    <div class="col-md-12 mb-2">
                        <button class="btn btn-success btn-sm float-right"
                        v-on:click="setAction('create','')">
                            <i class="fas fa-plus mr-2"></i>เพิ่มอุปกรณ์สำนักงาน
                        </button>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" width="7%">#</th>
                                    <th class="text-center" width="15%">รหัส</th>
                                    <th class="text-center">ชื่อ</th>
                                    <th class="text-center" width="10%">หน่วย</th>
                                    <th class="text-center" width="10%">Budget</th>
                                    <th class="text-center" width="10%">สถานะ</th>
                                    <th class="text-center" width="10%"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th>
                                        <input type="search" class="form-control form-control-sm"
                                        placeholder="กรอกรหัส"
                                        v-model="filterBy.id"
                                        v-on:keyup.enter="filterTable('id')">
                                    </th>
                                    <th>
                                        <input type="search" class="form-control form-control-sm"
                                        placeholder="กรอกชื่อ"
                                        v-model="filterBy.name"
                                        v-on:keyup.enter="filterTable('name')">
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        <select class="form-control form-control-sm"
                                        v-model="filterBy.active"
                                        v-on:change="filterTable('active')">
                                            <option value="all" selected>ทั้งหมด</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(it,index) in table" :key="index">
                                    <th class="text-center align-middle">{{index+1}}</th>
                                    <td class="align-middle">{{it.item_code}}</td>
                                    <td class="align-middle">{{it.item_name}}</td>
                                    <td class="align-middle">{{it.item_unit}}</td>
                                    <td class="text-right align-middle">{{it.item_budget}}</td>
                                    <td class="align-middle text-center">
                                        <a href="javascript:;" v-if="it.item_active == 1">
                                            <span class="badge badge-pill badge-success">
                                                <i class="fas fa-check mr-2"></i>Active
                                            </span>
                                        </a>
                                        <a href="javascript:;" v-else-if="it.item_active == 0">
                                            <span class="badge badge-pill badge-danger">
                                                <i class="fas fa-times mr-2"></i>Inactive
                                            </span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm"
                                        v-on:click="setAction('update',index)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                        v-on:click="removeItems(index)">
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

        <!-- Modal : #modal-items -->
        <div class="modal fade" id="modal-items" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" v-html="input.content.title">
                        <h5 class="modal-title"><i class="fas fa-plus text-success mr-2"></i>เพิ่มอุปกรณ์สำนักงาน</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row m-3">
                            <div class="col-12">
                                <form>
                                    <div class="form-row">
                                        <div class="form-group col-4">
                                            <label>รหัส</label>
                                            <input type="text" class="form-control"
                                            v-model="input.code"
                                            v-bind:class="{'is-invalid': input.invalid.code}"
                                            :readonly="input.disabled.code">
                                            <div class="invalid-feedback">กรุณาใส่ รหัส...!</div>
                                        </div>
                                        <div class="form-group col-8">
                                            <label>ชื่อ</label>
                                            <input type="text" class="form-control"
                                            v-model="input.name"
                                            v-bind:class="{'is-invalid': input.invalid.name}"
                                            :readonly="input.disabled.name">
                                            <div class="invalid-feedback">กรุณาใส่ ชื่อ...!</div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-3">
                                            <label>หน่วย</label>
                                            <input type="text" class="form-control"
                                            v-model="input.unit"
                                            v-bind:class="{'is-invalid': input.invalid.unit}"
                                            :readonly="input.disabled.unit">
                                            <div class="invalid-feedback">กรุณาใส่ หน่วย...!</div>
                                        </div>
                                        <div class="form-group col-3">
                                            <label>Budget</label>
                                            <input type="number" class="form-control" min="0"
                                            v-model="input.budget"
                                            v-bind:class="{'is-invalid': input.invalid.budget}"
                                            :readonly="input.disabled.budget">
                                            <div class="invalid-feedback">กรุณาใส่ Budget...!</div>
                                        </div>
                                        <div class="form-group col-3">
                                            <label>สถานะ</label>
                                            <select class="form-control"
                                            v-model="input.active"
                                            :disabled="input.disabled.active">
                                                <option value="1" selected>Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr class="pb-3">
                                    <div class="form-row justify-content-center">
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-primary w-100"
                                            v-on:click="takeActionItem()"
                                            v-html="input.content.btnSave"
                                            :disabled="input.disabled.btnSave">
                                                <i class="far fa-save mr-2"></i>บันทึก
                                            </button>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-secondary w-100" data-dismiss="modal"
                                            :disabled="input.disabled.btnClose"><i class="fas fa-times mr-2"></i>ปิด</button>
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

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    mounted(){
        this.getAllItems();
    },
    data(){
        return{
            progress: {
                main: false,
            },
            input:{
                method: 'add',
                id: '',
                code: '',
                name: '',
                unit: '',
                budget: 0.00,
                active: '1',
                content: {
                    title: '<h5 class="modal-title"><i class="fas fa-plus text-success mr-2"></i>เพิ่มอุปกรณ์สำนักงาน</h5>',
                    btnSave: '<i class="far fa-save mr-2"></i>บันทึก'
                },
                invalid: {
                    code: false,
                    name: false,
                    unit: false,
                    budget: false,
                },
                disabled: {
                    code: false,
                    name: false,
                    unit: false,
                    budget: false,
                    active: false,
                    btnSave: false,
                    btnClose: false
                }
            },
            items: [],
            table: [],
            filterBy:{
                id: '',
                name: '',
                active: 'all'
            }
        }
    },
    methods:{
        getAllItems(){
            this.progress.main = true;
            axios.get('/api/ad/items/list')
            .then(response => {
                this.progress.main = false;
                this.items = response.data;
                this.table = response.data;
            });
        },
        filterTable(target){

            let temp = [];

            for(var i = 0; i < this.items.length; i++){
                if(target == 'id'){
                    if(this.items[i].item_code.search(this.filterBy.id) > -1)
                        temp.push(this.items[i]);
                    else if(this.items[i].item_code == '')
                        temp.push(this.items[i]);
                }
                else if(target == 'name'){
                    if(this.items[i].item_name.search(this.filterBy.name) > -1)
                        temp.push(this.items[i]);
                    else if(this.items[i].item_name == '')
                        temp.push(this.items[i]);
                }
                else if(target == 'active'){
                    if(this.filterBy.active != 'all'){
                        if(this.items[i].item_active == this.filterBy.active)
                            temp.push(this.items[i]);
                    }
                    else
                        temp.push(this.items[i]);
                }
            }

            this.table = temp;

        },
        setAction(method,index){

            this.input.method = method;
            this.input.invalid.code = this.input.invalid.name = this.input.invalid.unit = false;

            if(method == 'create'){
                this.input.content.title = '<h5 class="modal-title"><i class="fas fa-plus text-success mr-2"></i>เพิ่มอุปกรณ์สำนักงาน</h5>';
                this.input.id = this.input.code = this.input.name = this.input.unit = '';
                this.input.active = 1;
            }
            else{
                this.input.content.title = '<h5 class="modal-title"><i class="fas fa-edit text-warning mr-2"></i>แก้ไขอุปกรณ์สำนักงาน</h5>';
                this.input.id = this.table[index].item_id;
                this.input.code = this.table[index].item_code;
                this.input.name = this.table[index].item_name;
                this.input.unit = this.table[index].item_unit;
                this.input.budget = this.table[index].item_budget;
                this.input.active = this.table[index].item_active;
            }

            $('#modal-items').modal('show');

        },
        validateForm(){

            let bool = true;

            if(this.input.code == ''){
                this.input.invalid.code = true;
                bool = false;
            }
            else
                this.input.invalid.code = false;

            if(this.input.name == ''){
                this.input.invalid.name = true;
                bool = false;
            }
            else
                this.input.invalid.name = false;

            if(this.input.unit == ''){
                this.input.invalid.unit = true;
                bool = false;
            }
            else
                this.input.invalid.unit = false;

            return bool;

        },
        takeActionItem(){

            if(!this.validateForm())
                return;

            this.input.disabled.code = true;
            this.input.disabled.name = true;
            this.input.disabled.unit = true;
            this.input.disabled.budget = true;
            this.input.disabled.active = true;
            this.input.disabled.btnSave = true;
            this.input.disabled.btnClose = true;

            let btnContent = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
                btnContent += 'กำลังบันทึกข้อมูล...';

            this.input.content.btnSave = btnContent;

            if(this.input.method == 'create'){
                var api = '/api/ad/items/create',
                    postData = {
                        code: this.input.code,
                        name: this.input.name,
                        unit: this.input.unit,
                        budget: this.input.budget,
                        active: this.input.active
                    };
            }
            else{
                var api = '/api/ad/items/update',
                    postData = {
                        id: this.input.id,
                        code: this.input.code,
                        name: this.input.name,
                        unit: this.input.unit,
                        budget: this.input.budget,
                        active: this.input.active
                    };
            }

            axios.post(api,postData)
            .then(response => {
                this.$swal({icon: 'success', text: 'บันทึกข้อมูลเรียบร้อยแล้ว'});
                this.input.content.btnSave = '<i class="far fa-save mr-2"></i>บันทึก';
                this.filterBy.id = this.filterBy.name = ''
                this.input.disabled.code = this.input.disabled.name = this.input.disabled.unit =  false;
                this.input.disabled.active = this.input.disabled.btnSave = this.input.disabled.btnClose = false;
                this.input.disabled.budget = false;
                $('#modal-items').modal('hide');
                this.getAllItems();
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกข้อมูลได้'});
                this.input.disabled.code = this.input.disabled.name = this.input.disabled.unit =  false;
                this.input.disabled.active = this.input.disabled.btnSave = this.input.disabled.btnClose = false;
            });

        },
        removeItems(index){

            let title = 'ยืนยันการลบข้อมูล',
                message = 'คุณต้องการลบ '+this.table[index].item_code+' '+this.table[index].item_name+' หรือไม่';

            this.$swal({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if(result.isConfirmed){
                    axios.get('/api/ad/items/remvoe/'+this.table[index].item_id)
                    .then(response => {
                        if(response.data.status){
                            this.$swal({icon: 'success', text: 'ลบอุปกรณ์สำนักงานเรียบร้อยแล้ว'});
                            this.getAllItems();
                        }
                        else
                            this.$swal({icon: 'error', text: response.data.message});
                    });
                }
            });

        }
    }
}
</script>
