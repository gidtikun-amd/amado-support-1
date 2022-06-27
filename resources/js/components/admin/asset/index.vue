<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการข้อมูลทรัพย์สิน</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-inline">
                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกคำค้นหา..." aria-label="Search"
                                v-model="html.searchKeyword"
                                v-on:keyup.enter="getDataByKeyword()"
                            >
                            <button class="btn btn-sm btn-amado my-2 my-sm-0" type="button"
                                v-on:click="getDataByKeyword()"
                            >
                                <i class="fas fa-search"></i>
                                <span class="ml-2">ค้นหา</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-sm btn-success float-right" v-on:click="setActionModal('create','')">
                            <i class="fas fa-plus"></i>
                            <span class="ml-2">เพิ่มทรัพย์สิน</span>
                        </button>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">รหัสทรัพย์สิน</th>
                            <th scope="col">ประเภททรัพย์สิน</th>
                            <th scope="col">ชื่อทรัพย์สิน</th>
                            <th scope="col">S/N</th>
                            <th scope="col">สถานะ</th>
                            <th scope="col">ประเภทถือครอง</th>
                            <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asst in assets" :key="asst.index">
                            <th scope="row">{{asst.index}}</th>
                            <td>{{asst.it_hw_number}}</td>
                            <td>{{asst.it_hw_type_name}}</td>
                            <td>{{asst.it_hw_name}}</td>
                            <td>{{asst.it_hw_serial}}</td>
                            <td>{{asst.status_name}}</td>
                            <td>{{asst.group_name}}</td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary btn-sm"
                                    :href="'/admin/asset/'+asst.it_hw_seq"
                                >
                                    <i class="fas fa-file-alt"></i>
                                    <span class="ml-2">รายละเอียด</span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="javascript:;"
                            v-on:click="getDataPaginate('first')" 
                        ><i class="fas fa-angle-double-left"></i></a></li>
                        <li class="page-item"><a class="page-link" href="javascript:;" 
                            v-on:click="getDataPaginate('prev')"
                        ><i class="fas fa-angle-left"></i></a></li>
                        <li class="page-item">
                            <select class="page-link page-link-dropdown"
                                v-model="paginate.numberPagin"
                                v-on:change="getDataPaginate('select')"
                            >
                                <option v-for="num in paginate.numberPage" :key="num.number" :value="num.number">
                                    {{num.number}}
                                </option>
                            </select>
                        </li>
                        <li class="page-item"><a class="page-link" href="javascript:;" 
                            v-on:click="getDataPaginate('next')"
                        ><i class="fas fa-angle-right"></i></a></li>
                        <li class="page-item"><a class="page-link" href="javascript:;" 
                            v-on:click="getDataPaginate('last')"
                        ><i class="fas fa-angle-double-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Modal : #modal-asset -->
        <div class="modal fade" id="modal-asset" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-html="html.modalActionTitle">
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="row m-3">
                                <div class="form-group col-md-4">
                                    <label>รหัสทรัพย์สิน : </label>
                                    <input type="text" class="form-control" placeholder="กรอกรหัสทรัพย์สิน..."
                                        v-model="modal.asset_number"
                                        v-bind:class="{'is-invalid':html.modalActionVlAsstNumber}"
                                    >
                                    <div class="invalid-feedback">กรุณากรอกรหัสทรัพย์สิน...!</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>ประเภททรัพย์สิน : </label>
                                    <select class="form-control"
                                        v-model="modal.asset_type"
                                        v-bind:class="{'is-invalid':html.modalActionVlAsstType}"
                                    >
                                        <option value="" disabled selected>กรุณาเลือก...</option>
                                        <option v-for="asstt in assettype" :key="asstt.it_hw_type_id" :value="asstt.it_hw_type_id">
                                            {{asstt.it_hw_type_name}}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือกประเภททรัพย์สิน...!</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>ประเภทถือครอง : </label>
                                    <select class="form-control"
                                        v-model="modal.asset_group"
                                        v-bind:class="{'is-invalid':html.modalActionVlAsstGroup}"
                                    >
                                        <option value="" disabled selected>กรุณาเลือก...</option>
                                        <option value="1">ทรัพย์สินบุคคลถือครอง</option>
                                        <option value="2">ทรัพย์สินส่วนกลางฝ่าย</option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือกประเภทถือครอง...!</div>
                                </div>
                            </div>
                            <hr class="pb-3">
                            <div class="row m-3">
                                <div class="form-group col-md-7">
                                    <label>ชื่อทรัพย์สิน : </label>
                                    <input type="text" class="form-control" placeholder="กรอกชื่อทรัพย์สิน..."
                                        v-model="modal.asset_name"
                                        v-bind:class="{'is-invalid':html.modalActionVlAsstName}"
                                    >
                                    <div class="invalid-feedback">กรุณากรอกชื่อทรัพย์สิน...!</div>
                                </div>
                                <div class="form-group col-md-5">
                                    <label>S/N : </label>
                                    <input type="text" class="form-control" placeholder="กรอก Serial number..."
                                        v-model="modal.asset_serial"
                                    >
                                </div>                                
                                <div class="form-group col-md-4">
                                    <label>ราคา : </label>
                                    <input type="number" class="form-control" placeholder="กรอกราคา..."
                                        v-model="modal.asset_price"
                                        v-bind:class="{'is-invalid':html.modalActionVlAsstPrice}"
                                    >
                                    <div class="invalid-feedback">กรุณากรอกราคาหรือ >= 0 ...!</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Expired Date : </label>
                                    <input type="text" class="form-control" placeholder="yyyy-mm-dd"
                                    v-model="modal.asset_expire">
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label>Warrantry : </label>
                                    <input type="text" class="form-control"                                    
                                    v-model="modal.asset_warrantry">
                                </div>
                                <div class="form-group col-md-7">
                                    <label>หมายเหตุ : </label>
                                    <textarea class="form-control" rows="5" placeholder="กรอกหมายเหตุ..."
                                        v-model="modal.asset_remark"
                                    ></textarea>
                                </div>
                            </div>
                            <hr class="pb-3">
                            <div class="row mb-3 mx-3">
                                <div class="form-group col-md-4">
                                    <label>สถานะ : </label>
                                    <select class="form-control"
                                        v-model="modal.asset_status"                                        
                                    >
                                        <option value="" disabled>กรุณาเลือก...</option>
                                        <option value="1">ใช้งาน</option>
                                        <option value="2" selected>สำรอง</option>
                                        <option value="3">ส่งซ่อม</option>
                                        <option value="4">ออกจำหน่าย</option>
                                        <option value="5">ยืมใช้งาน</option>
                                        <option value="6">ไม่ใช้งาน</option>
                                    </select>
                                </div>
                            </div>
                            <hr class="pb-3">
                            <div class="row justify-content-md-center m-3">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-success w-100"
                                        v-on:click="setAssetSave()"
                                        v-html="html.modalActionBtnSaveTxt"
                                        :disabled="html.modalActionBtnSaveDisabled"
                                    ><i class="fas fa-save"></i> บันทึก</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100" data-dismiss="modal"
                                        :disabled="html.modalActionBtnCloseDisabled"
                                    ><i class="fas fa-ban"></i> ยกเลิก</button>
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
        this.getMasterAssettype();
        this.getDataFirstLoad();
    },
    data(){
        return{
            html: {
                lodaPage: '',
                searchKeyword: '',
                modalActionTitle: '',
                modalActionVlAsstNumber: false,
                modalActionVlAsstType: false,
                modalActionVlAsstGroup: false,
                modalActionVlAsstName: false,
                modalActionVlAsstPrice: false,
                modalActionBtnSaveTxt: '',
                modalActionBtnSaveDisabled: false,
                modalActionBtnCloseDisabled: false,
                modalProgressContent: '',
                modalPregressBtnClose: false
            },
            modal: {
                method: '',
                asset_id: '',
                asset_number: '',
                asset_type: '',
                asset_group: '',
                asset_name: '',
                asset_serial: '',
                asset_price: '',
                asset_remark: '',
                asset_status: '',
                asset_expire: '',
                asset_warrantry: '0 Y'
            },
            paginate: {
                url: '',
                path: '',
                firstPageUrl: '',
                lastPageUrl: '',
                nextPageUrl: '',
                prevPageUrl: '',
                currentPage: 0,
                lastPage: 0,
                from: 0,
                to: 0,
                numberPage: [],
                numberPagin: 1,
            },
            assets: [],
            assettype: []
        }
    },
    methods:{        
        checkSeessionLogin(){
            axios.get('/api/login/check-exist-session')
            .then(response => {
                if(response.data)
                    return true;
                else{
                    this.$swal({
                        title: 'เวลาในการ Login หมดลงแล้ว', text: 'กรุณา Login ใหม่', icon: 'Error',
                        showCancelButton: false, cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6', confirmButtonText: 'ไปยังหน้า Login',
                    })
                    .then((result) => { if(result.isConfirmed){ location.href = '/login'; } });
                    return false;
                }
            })
            .catch(response => {
                this.$swal({
                    title: 'เวลาในการ Login หมดลงแล้ว', text: 'กรุณา Login ใหม่', icon: 'Error',
                    showCancelButton: false, cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6', confirmButtonText: 'ไปยังหน้า Login',
                })
                .then((result) => { if(result.isConfirmed){ location.href = '/login'; } });
                return false;
            });
        },
        getMasterAssettype(){
            axios.get('/api/admin/it/assettype')
            .then(response => {
                this.assettype = response.data;
            });
        },
        getDataFirstLoad(){
            axios.get('/api/admin/it/asset')
            .then(response => {
                this.html.lodaPage = 'unSearch';
                this.setDataAndPaginate(response.data);
            });
        },
        getDataByKeyword(){
            axios.post('/api/admin/it/asset/serchkeyword',{ keyword: this.html.searchKeyword })
            .then(response => {
                this.html.lodaPage = 'inSearch';
                this.setDataAndPaginate(response.data);
            });
        },
        getDataPaginate(page){

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            if( this.html.lodaPage == 'unSearch' )
                axios.get(this.paginate.url).then(response => { this.setDataAndPaginate(response.data) });            
            else
                axios.post(this.paginate.url,{ keyword: this.html.searchKeyword }).then(response => { this.setDataAndPaginate(response.data) });            

        },
        setDataAndPaginate(dataArr){

            this.paginate.url = dataArr.path+'?page=';
            this.paginate.path = dataArr.path;
            this.paginate.firstPageUrl = dataArr.first_page_url;
            this.paginate.lastPageUrl = dataArr.last_page_url;
            this.paginate.nextPageUrl = dataArr.next_page_url;
            this.paginate.prevPageUrl = dataArr.prev_page_url;
            this.paginate.currentPage = dataArr.current_page;
            this.paginate.firstPage = 1;
            this.paginate.lastPage = dataArr.last_page;
            this.paginate.from = dataArr.from;
            this.paginate.to = dataArr.to;          

            var tempData = dataArr.data,
                resultData = [],
                resultPageNumber=  [],
                rowNumber = this.paginate.from;
                
            for(var i = 0; i < tempData.length; i++){
                resultData.push(tempData[i]);
                resultData[i].index = rowNumber++;
            }

            for(var i = this.paginate.firstPage; i <= this.paginate.lastPage; i++){
                resultPageNumber.push({number: i});
            }

            this.paginate.numberPage = resultPageNumber;
            this.assets = resultData;

        },
        setActionModal(method,arrData){

            $('#modal-asset').modal('show');

            this.modal.method = method;
            this.html.modalActionVlAsstNumber = false;
            this.html.modalActionVlAsstType = false;
            this.html.modalActionVlAsstGroup = false;
            this.html.modalActionVlAsstName = false;
            this.html.modalActionVlAsstPrice = false;
            this.html.modalActionBtnSaveDisabled = false;
            this.html.modalActionBtnCloseDisabled = false;
            this.modal.asset_group = 2;
            this.modal.asset_status = 2;
            this.modal.asset_price = 0.00;
            this.html.modalActionTitle = '<i class="fas fa-plus text-success"></i> <span class="ml-2">เพิ่มทรัพย์สิน</span>';
            this.html.modalActionBtnSaveTxt = '<i class="fas fa-save"></i> บันทึก</button>';

        },
        setAssetSave(){

            // if(!this.checkSeessionLogin())
            //     return;

            this.html.modalActionBtnSaveDisabled = true;
            this.html.modalActionBtnCloseDisabled = true;

            var strBtnSave = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ระบบกำลังบันทึกข้อมูล...';
            this.html.modalActionBtnSaveTxt = strBtnSave;

            this.html.modalActionVlAsstNumber = ( this.modal.asset_number == '' ? true : false );
            this.html.modalActionVlAsstType = ( this.modal.asset_type == '' ? true : false );
            this.html.modalActionVlAsstGroup = ( this.modal.asset_group == '' ? true : false );
            this.html.modalActionVlAsstName = ( this.modal.asset_name == '' ? true : false );
            this.html.modalActionVlAsstPrice = ( this.modal.asset_price == '' ? true : false );

            if( 
                !this.html.modalActionVlAsstNumberis &&
                !this.html.modalActionVlAsstType &&
                !this.html.modalActionVlAsstGroup &&
                !this.html.modalActionVlAsstName &&
                !this.html.modalActionVlAsstPrice
            ){

                $('#modal-asset').modal('hide');
                $('#modal-progress').modal('show');

                var contentAction = ( this.modal.action == 'create' ? 'ระบบกำลังบันทึกข้อมูล' : 'ระบบกำลังอัพเดทข้อมูล' );
                var pcontent = '<div class="progress">';
                pcontent += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"';
                pcontent += 'aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>';
                pcontent += '</div> <strong>'+contentAction+'</strong>';
                
                this.html.modalProgressContent = pcontent;
                this.html.modalPregressBtnClose = false;

                this.setCreateAsset();

            }
            else{

                this.html.modalActionBtnSaveTxt = '<i class="fas fa-save"></i> บันทึก</button>';
                this.html.modalActionBtnSaveDisabled = false;
                this.html.modalActionBtnCloseDisabled = false;

            }

        },
        setCreateAsset(){
            axios.post('/api/admin/it/asset',{
                asset_number: this.modal.asset_number,
                asset_type: this.modal.asset_type,
                asset_group: this.modal.asset_group,
                asset_name: this.modal.asset_name,
                asset_serial: this.modal.asset_serial,
                asset_price: this.modal.asset_price,
                asset_remark: this.modal.asset_remark,
                asset_status: this.modal.asset_status,
                asset_expire: this.modal.asset_expire,
                asset_warrantry: this.modal.asset_warrantry
            })
            .then(response => {

                this.html.modalPregressBtnClose = true;

                if(response.data.status) 
                    var pcontent = '<i class="fas fa-check-circle text-success"></i> '+response.data.message;
                else {
                    if(response.data.exception == 'codeExist')
                        var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> '+response.data.message; 
                    else if(response.data.exception == 'sqlError'){
                        var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';
                            pcontent += '<br><br><font color="red"><b>[Error]</b></font><br>';
                            pcontent += response.data.message;
                    }
                    else if(response.data.exception == 'loginExpire'){

                        var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';

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

                    }
                }

                this.html.modalProgressContent = pcontent;

                if(response.data.status) 
                    this.getDataFirstLoad();

            })
            .catch(response => {
                this.html.modalPregressBtnClose = true;
                var pcontent = '<i class="fas fa-exclamation-circle text-danger"></i> ระบบไม่สามารถบันทึกข้อมูลได้';
                this.html.modalProgressContent = pcontent;
            });
        }

    }
}
</script>