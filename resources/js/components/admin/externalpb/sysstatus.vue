<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <i class="fas fa-file-signature"></i><span class="ml-2">บันทึกสถานะระบบ</span>
                    </div>
                    <div class="col-6 text-right">
                        <a href="javascript:;" class="text-primary" v-on:click="changeWindow('main',true)">
                            <i class="far fa-window-maximize mr-1"></i>
                        </a>
                        <a href="javascript:;" class="text-primary" v-on:click="changeWindow('main',false)">
                            <i class="fas fa-window-minimize"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body" v-show="content.window.main">
                <div class="row">
                    <div class="col-12">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-1 col-form-label">ปี/เดือน</label>
                                <div class="col-sm-11">
                                    <date-picker value-type="format" format="YYYY-MM" placeholder="เลือกปี/เดือน" type="month"
                                    v-model='dateDefault' :disabled="content.input.mainDateDisabled" readonly="true"></date-picker>
                                    <button type="button" class="btn btn-outline-primary ml-2"
                                    v-on:click="saveStatus()"
                                    v-html="content.btn.mainSaveText"
                                    :disabled="content.btn.mainSaveDisabled">
                                        <i class="far fa-save mr-2"></i>
                                        <span>บันทึก</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger ml-2"
                                    v-on:click="resetData('main')"
                                    :disabled="content.btn.mainCloseDisabled">
                                        <i class="fas fa-sync-alt mr-2"></i>
                                        <span>ยกเลิก</span>
                                    </button>
                                </div>   
                            </div>
                            <hr>
                            <div class="form-group row" v-for="(sys,index) in system" :key="index">
                                <label class="col-sm-4 col-form-label">
                                    {{sys.title}}
                                </label>
                                <div class="col-sm-8">                                    
                                    <a href="javascript:;">
                                        <label class="col-sm-2 col-form-label">
                                            <i class="text-success far fa-check-circle" v-show="sys.success"></i>
                                            <i class="text-danger fas fa-exclamation-circle" v-show="sys.fail"></i>
                                            <span class="text-primary font-weight-bold" v-html="sys.text">สถานะปกติ</span>
                                        </label>
                                    </a>
                                    <button type="button" class="btn btn-primary btn-sm"
                                    v-on:click="changeStatus('main',index)"
                                    :disabled="content.btn.mainChangeStatus">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                </div>                                
                                <div class="col-sm-6" v-show="sys.remark.show">
                                    <textarea class="form-control" rows="3"
                                    v-model="sys.remark.detail"
                                    :disabled="content.input.mainSysStsDetailDisabled"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer small text-muted">   
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <i class="fas fa-file-signature"></i><span class="ml-2">รายละเอียดบันทึกสถานะระบบ</span>
                    </div>
                    <div class="col-6 text-right">
                        <a href="javascript:;" class="text-primary" v-on:click="changeWindow('detail',true)">
                            <i class="far fa-window-maximize mr-1"></i>
                        </a>
                        <a href="javascript:;" class="text-primary" v-on:click="changeWindow('detail',false)">
                            <i class="fas fa-window-minimize"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body" v-show="content.window.detail">
                <div class="row">
                    <div class="col-12">
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-1 col-form-label">ปี/เดือน</label>
                                <div class="col-sm-11">
                                    <date-picker value-type="format" format="YYYY-MM" placeholder="เลือกปี/เดือน" type="month"
                                    v-model='dateDetail' :disabled="content.input.detailDateDisabled" readonly="true"></date-picker>
                                    <button type="button" class="btn btn-outline-success ml-2"
                                    v-on:click="searchSystemStatus()"
                                    v-html="content.btn.detailSearchText"
                                    :disabled="content.btn.detailSearchDisabled">
                                        <i class="fas fa-search mr-2"></i>
                                        <span>ค้นหา</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary ml-2"
                                    v-on:click="updateStatus()"
                                    v-html="content.btn.detailSaveText"
                                    :disabled="content.btn.detailSaveDisabled">
                                        <i class="far fa-save mr-2"></i>
                                        <span>บันทึก</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger ml-2"
                                    v-on:click="resetData('detail')"
                                    :disabled="content.btn.detailCloseDisabled">
                                        <i class="fas fa-sync-alt mr-2"></i>
                                        <span>ยกเลิก</span>
                                    </button>
                                </div>   
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row" v-show="content.alert.detail">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-exclamation-circle"></i> คำเตือน!</strong> ยังไม่มีการบันทึกสถานะระบบในระยะเวลาที่เลือก
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" v-show="content.alert.detailPanel">
                    <div class="col-12">
                        <hr>
                        <form>                             
                            <div class="form-group row" v-for="(detail,index) in detail" :key="index">
                                <label class="col-sm-4 col-form-label">
                                    {{detail.title}}
                                </label>
                                <div class="col-sm-8">                                    
                                    <a href="javascript:;">
                                        <label class="col-sm-2 col-form-label">
                                            <i class="text-success far fa-check-circle" v-show="detail.success"></i>
                                            <i class="text-danger fas fa-exclamation-circle" v-show="detail.fail"></i>
                                            <span class="text-primary font-weight-bold" v-html="detail.text">สถานะปกติ</span>
                                        </label>
                                    </a>
                                    <button type="button" class="btn btn-primary btn-sm"
                                    v-on:click="changeStatus('detail',index)"
                                    :disabled="content.btn.detailChangeStatus">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                </div>                                
                                <div class="col-sm-6" v-show="detail.remark.show">
                                    <textarea class="form-control" rows="3"
                                    v-model="detail.remark.detail"
                                    :disabled="content.input.detailSysStsDetailDisabled"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer small text-muted">   
            </div>
        </div>

    </div>
</template>
<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/th';

import VueSession from 'vue-session'
Vue.use(VueSession);

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

export default {
    mounted(){
        this.getData();
    },
    data(){
        return{
            dateDefault: '',
            dateDetail: '',
            method: 'create',
            id: '',
            system: [
                {
                    title: 'การสำรองข้อมูล',
                    success: true, fail: false, text: 'สถานะปกติ',
                    remark: { show: false, detail: '' }
                },
                {
                    title: 'เครื่องคอมพิวเตอร์แม่เครือข่ายและอุปกรณ์เครือข่าย',
                    success: true, fail: false, text: 'สถานะปกติ',
                    remark: { show: false, detail: '' }
                },
                {
                    title: 'ระบบเครือข่ายภายในสำนักงาน',
                    success: true, fail: false, text: 'สถานะปกติ',
                    remark: { show: false, detail: '' }
                },
            ],
            detail: [
                {
                    title: 'การสำรองข้อมูล',
                    success: true, fail: false, text: 'สถานะปกติ',
                    remark: { show: false, detail: '' }
                },
                {
                    title: 'เครื่องคอมพิวเตอร์แม่เครือข่ายและอุปกรณ์เครือข่าย',
                    success: true, fail: false, text: 'สถานะปกติ',
                    remark: { show: false, detail: '' }
                },
                {
                    title: 'ระบบเครือข่ายภายในสำนักงาน',
                    success: true, fail: false, text: 'สถานะปกติ',
                    remark: { show: false, detail: '' }
                },
            ],
            content: {
                window: {
                    main: true,
                    detail: true,
                },
                alert: {
                    detail: false,
                    detailPanel: false
                },
                input: {
                    mainDateDisabled: false,
                    mainSysStsDetailDisabled: false,                    
                    detailDateDisabled: false,
                    detailSysStsDetailDisabled: false,
                },
                btn: {
                    mainSaveText: '<i class="far fa-save mr-2"></i><span>บันทึก</span>',
                    mainSaveDisabled: false,
                    mainCloseDisabled: false,
                    mainChangeStatus: false,
                    detailSearchText: '<i class="fas fa-search mr-2"></i><span>ค้นหา</span>',
                    detailSearchDisabled: false,
                    detailSaveText: '<i class="far fa-save mr-2"></i><span>บันทึก</span>',
                    detailSaveDisabled: false,
                    detailCloseDisabled: false,
                    detailChangeStatus: false,
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
        getData(){
            let date = new Date();
            let tempData = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2);
            this.dateDefault = tempData;
            this.dateDetail = tempData;
        },
        changeWindow(blank,bool){
            if(blank == 'main') this.content.window.main = bool;
            else if(blank == 'detail') this.content.window.detail = bool;
        },
        resetData(target){
            if(target == 'main'){
                this.system = [
                    {title: 'การสำรองข้อมูล', success: true, fail: false, text: 'สถานะปกติ', remark: { show: false, detail: '' }},
                    {title: 'เครื่องคอมพิวเตอร์แม่เครือข่ายและอุปกรณ์เครือข่าย', success: true, fail: false, text: 'สถานะปกติ', remark: { show: false, detail: '' }},
                    {title: 'ระบบเครือข่ายภายในสำนักงาน', success: true, fail: false, text: 'สถานะปกติ', remark: { show: false, detail: '' }},
                ]
            }
            else if(blank == 'detail'){                
                this.detail = [
                    {title: 'การสำรองข้อมูล', success: true, fail: false, text: 'สถานะปกติ', remark: { show: false, detail: '' }},
                    {title: 'เครื่องคอมพิวเตอร์แม่เครือข่ายและอุปกรณ์เครือข่าย', success: true, fail: false, text: 'สถานะปกติ', remark: { show: false, detail: '' }},
                    {title: 'ระบบเครือข่ายภายในสำนักงาน', success: true, fail: false, text: 'สถานะปกติ', remark: { show: false, detail: '' }},
                ]
            }
        },
        changeStatus(blank,index){
            if(blank == 'main'){
                this.system[index].success = this.system[index].success == true ? false : true;
                this.system[index].fail = this.system[index].fail == true ? false : true;
                this.system[index].text = this.system[index].success == true ? 'สถานะปกติ' : 'สถานะผิดปกติ';
                if(this.system[index].fail) this.system[index].remark = { show: true, detail: '' };
                else this.system[index].remark = { show: false, detail: '' };
            }
            else if(blank == 'detail'){
                this.detail[index].success = this.detail[index].success == true ? false : true;
                this.detail[index].fail = this.detail[index].fail == true ? false : true;
                this.detail[index].text = this.detail[index].success == true ? 'สถานะปกติ' : 'สถานะผิดปกติ';
                if(this.detail[index].fail) this.detail[index].remark = { show: true, detail: '' };
                else this.detail[index].remark = { show: false, detail: '' };
            }
        },
        saveStatus(){

            this.content.input.mainDateDisabled = true;
            this.content.btn.mainSaveDisabled = true;
            this.content.btn.mainCloseDisabled = true;
            this.content.btn.mainChangeStatus = true;
            this.content.input.mainSysStsDetailDisabled = true;

            let contentBtn = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
                contentBtn += 'Saving...';

            this.content.btn.mainSaveText = contentBtn;

            axios.post('/api/admin/it/external-pb/system-status/save',{
                date: this.dateDefault,
                user: this.$session.get('userId'),
                system: this.system
            })
            .then(response => {

                if(response.data.status){
                    this.$swal({icon: 'success', text: response.data.message});
                    this.resetData('main');
                }
                else
                    this.$swal({icon: 'error', text: response.data.message});                

                this.content.input.mainDateDisabled = false;
                this.content.btn.mainSaveDisabled = false;
                this.content.btn.mainCloseDisabled = false;
                this.content.btn.mainChangeStatus = false;
                this.content.input.mainSysStsDetailDisabled = false;
                this.content.btn.mainSaveText = '<i class="far fa-save mr-2"></i><span>บันทึก</span>';

            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกสถานะระบบได้'});
                this.content.input.mainDateDisabled = false;
                this.content.btn.mainSaveDisabled = false;
                this.content.btn.mainCloseDisabled = false;
                this.content.btn.mainChangeStatus = false;
                this.content.input.mainSysStsDetailDisabled = false;
                this.content.btn.mainSaveText = '<i class="far fa-save mr-2"></i><span>บันทึก</span>';
            });

        },
        searchSystemStatus(){

            this.content.btn.detailSearchDisabled = true;
            this.content.input.detailDateDisabled = true;
            this.content.btn.detailSaveDisabled = true;
            this.content.btn.detailCloseDisabled = true;
            this.content.btn.detailSearchDisabled = false;
            this.content.alert.detail = false;
            this.content.alert.detailPanel = false;

            let contentBtn = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
                contentBtn += 'Searching...';

            this.content.btn.detailSearchText = contentBtn;

            axios.post('/api/admin/it/external-pb/system-status/detail',{
                date: this.dateDetail
            })
            .then(response => {

                let data = response.data;

                if(data.length > 0){
                    
                    this.detail = [];

                    for(var i = 0; i < data.length; i++){

                        this.detail.push({
                            id: data[i].id,
                            title: data[i].title,
                            success: data[i].status == 1 ? true : false,
                            fail: data[i].status == 0 ? true : false,
                            text: data[i].status == 1 ? 'สถานะปกติ' : 'สถานะผิดปกติ',
                            remark: {
                                show: data[i].status == 0 ? true : false,
                                detail: data[i].status == 0 ? data[i].remark : ''
                            }
                        });
                        
                    }
                    
                    this.content.alert.detail = false;
                    this.content.alert.detailPanel = true;

                }
                else{
                    this.content.alert.detail = true;
                    this.content.alert.detailPanel = false;
                }

                this.content.btn.detailSearchDisabled = false;
                this.content.input.detailDateDisabled = false;
                this.content.btn.detailSaveDisabled = false;
                this.content.btn.detailCloseDisabled = false;
                this.content.btn.detailSearchDisabled = false;
                this.content.btn.detailSearchText = '<i class="fas fa-search mr-2"></i><span>ค้นหา</span>';
                
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ระบบไม่สามารถค้นหาข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'});
                this.content.btn.detailSearchDisabled = false;
                this.content.input.detailDateDisabled = false;
                this.content.btn.detailSaveDisabled = false;
                this.content.btn.detailCloseDisabled = false;
                this.content.btn.detailSearchDisabled = false;
                this.content.alert.detail = false;
                this.content.alert.detailPanel = false;
                this.content.btn.detailSearchText = '<i class="fas fa-search mr-2"></i><span>ค้นหา</span>';
            });

        },
        updateStatus(){

            this.content.btn.detailSearchDisabled = true;
            this.content.input.detailDateDisabled = true;
            this.content.btn.detailSaveDisabled = true;
            this.content.btn.detailCloseDisabled = true;
            this.content.btn.detailSearchDisabled = false;
            this.content.alert.detail = false;
            this.content.alert.detailPanel = false;

            let contentBtn = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
                contentBtn += 'Updating...';

            this.content.btn.detailSaveText = contentBtn;

            axios.post('/api/admin/it/external-pb/system-status/update',{
                user: this.$session.get('userId'),
                detail: this.detail
            })
            .then(response => {
                this.$swal({icon: 'success', text: 'ปรับปรุงบันทึกสถานะระบบเรียบร้อยแล้ว'});
                this.content.btn.detailSearchDisabled = false;
                this.content.input.detailDateDisabled = false;
                this.content.btn.detailSaveDisabled = false;
                this.content.btn.detailCloseDisabled = false;
                this.content.btn.detailSearchDisabled = false;
                this.content.alert.detail = false;
                this.content.alert.detailPanel = true;
                this.content.btn.detailSaveText = '<i class="far fa-save mr-2"></i><span>บันทึก</span>';
            })
            .catch(respponse => {
                this.$swal({icon: 'error', text: 'ไม่สามารถปรับปรุงบันทึกสถานะระบบได้'});
                this.content.btn.detailSearchDisabled = false;
                this.content.input.detailDateDisabled = false;
                this.content.btn.detailSaveDisabled = false;
                this.content.btn.detailCloseDisabled = false;
                this.content.btn.detailSearchDisabled = false;
                this.content.alert.detail = false;
                this.content.alert.detailPanel = true;
                this.content.btn.detailSaveText = '<i class="far fa-save mr-2"></i><span>บันทึก</span>';
            });

        }
    }
}
</script>