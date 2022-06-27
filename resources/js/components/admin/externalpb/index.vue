<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการบันทึกปัญหาภายนอก</span>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success btn-sm float-right"
                        v-on:click="setCreateModal()">
                            <i class="fas fa-plus mr-2"></i>บันทึกปัญหาภายนอก
                        </button>
                    </div>
                </div>
                <div class="row mb-3" v-show="content.progress.main">
                    <div class="col-md-12 text-center">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <strong class="text-primary">ระบบกำลังดึงข้อมูล...</strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">เลขที่เอกสาร</th>
                                    <th scope="col">หัวข้อ</th>
                                    <th scope="col">วันที่</th>
                                    <th scope="col">เรื่อง</th>
                                    <th scope="col">ผู้บันทึก</th>
                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list,index) in lexternal.list" :key="index">
                                    <td>{{list.index}}</td>
                                    <td>{{list.no}}</td>
                                    <td>{{list.subject}}</td>
                                    <td>{{list.date}}</td>                                    
                                    <td>{{list.title}}</td>                                   
                                    <td>{{list.user}}</td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-sm"
                                        v-on:click="setDataEdit(index)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                        v-on:click="removeExternalPb(index)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-show="lexternal.list.length <= 0">
                                    <td colspan="6" class="text-center">ไม่มีข้อมูล</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="card-footer small text-muted">                
                <nav aria-label="Page navigation example" v-show="paginate.show">
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

        <!-- Modal : #modal-create -->
        <div class="modal fade" id="modal-create" data-backdrop="static" 
        data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-html="content.title"></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="mr-4"><b>ชื่อ-นามสกุล </b><span class="ml-2">{{dexternal.empName}}</span></span>
                                <span><b>ฝ่าย </b><span class="ml-2">{{this.$session.get('department')}}</span></span>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row mb-3">
                            <div class="col-md-2 my-auto">
                                <h5 class="card-title m-0"><b>หัวข้อ : </b></h5>
                            </div>
                            <div class="col-md">
                                <select class="form-control"
                                v-model="dexternal.subject"
                                :disabled="content.disabled.subject">
                                    <option value="">กรุณาเลือกหัวข้อ</option>
                                    <option value="Innoviz Solutions Co., Ltd.">Innoviz Solutions Co., Ltd.</option>
                                    <option value="บริษัท โกบอล อินโนเวทีฟ โซลูชั่นส์ จำกัด">บริษัท โกบอล อินโนเวทีฟ โซลูชั่นส์ จำกัด</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-2 my-auto">
                                <h5 class="card-title m-0"><b>เรื่อง : </b></h5>
                            </div>
                            <div class="col-md">
                                <input type="text" class="form-control"
                                v-model="dexternal.title"
                                :disabled="content.disabled.title">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <p class="card-text mb-0"><b>รายละเอียด : </b></p>
                            </div>
                            <div class="col-md">
                                <textarea class="form-control" rows="4"
                                v-model="dexternal.detail"
                                :disabled="content.disabled.detail"></textarea>
                            </div>
                        </div>
                        <div class="row pt-3 mb-0 justify-content-center">
                            <button type="button" class="col-md-3 btn btn-primary mr-3"
                            v-html="content.btnSave"
                            :disabled="content.disabled.btnSave"
                            v-on:click="saveExternalPb()">
                                <i class="fas fa-paper-plane"></i>
                                <span class="ml-2">บันทึก</span>
                            </button>
                            <button type="button" class="col-md-3 btn btn-danger mr-3"
                            data-dismiss="modal"
                            :disabled="content.disabled.btnClose">
                                <i class="fas fa-ban"></i>
                                <span class="ml-2">ยกเลิก</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>

import VueSession from 'vue-session'
Vue.use(VueSession);

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

export default {
    mounted(){
        this.getListExternalPd();
    },
    data(){
        return{
            content:{
                title: '<i class="fas fa-plus mr-2 text-success"></i><span>บันทึกปัญหาภายนอก</span>',
                btnSave: '<i class="fas fa-paper-plane"></i><span class="ml-2">บันทึก</span>',
                progress: {
                    main: false,
                },
                disabled: {
                    subject: false,
                    title: false,
                    detail: false,
                    btnSave: false,
                    btnClose: false,
                }
            },
            lexternal:{
                list: [],
                once: {}
            },
            dexternal:{
                userId: this.$session.get('user'),
                empName: this.$session.get('name'),
                id: '',
                subject: '',
                title: '',
                detail: '',
            },
            paginate: {
                method: 'create',
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
                list: 25
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
        getListExternalPd(){
            
            this.content.progress.main = true;
            this.paginate.show = false;

            axios.post('/api/admin/it/external-pb/list')
            .then(response => {
                this.setDataAndPaginate(response.data);
            });

        },
        getDataPaginate(page){

            this.content.progress.main = true;
            this.paginate.show = false;

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post(this.paginate.url)
            .then(response => {
                this.setDataAndPaginate(response.data);
            });

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
            this.lexternal.list = resultData;
            this.paginate.show = true;
            this.content.progress.main = false;

        },
        setCreateModal(){
            this.content.title = '<i class="fas fa-plus mr-2 text-success"></i><span>บันทึกปัญหาภายนอก</span>';            
            this.paginate.method = 'create';            
            this.dexternal.id = '';
            this.dexternal.subject = '';
            this.dexternal.title = '';
            this.dexternal.detail = '';
            $('#modal-create').modal('show');
        },
        setDataEdit(index){
            this.content.title = '<i class="fas fa-edit mr-2 text-primary"></i><span>แก้ไขบันทึกปัญหาภายนอก</span>';
            this.paginate.method = 'update';  
            this.dexternal.empName = this.lexternal.list[index].emp_fullname;
            this.dexternal.id = this.lexternal.list[index].id;
            this.dexternal.subject = this.lexternal.list[index].subject;
            this.dexternal.title = this.lexternal.list[index].title;
            this.dexternal.detail = this.lexternal.list[index].detail;
            $('#modal-create').modal('show');
        },
        saveExternalPb(){

            if(this.dexternal.subject == ''){
                this.$swal({icon: 'error', text: 'กรุณาเลือกหัวข้อ'});
                return;
            }

            if(this.dexternal.title == ''){
                this.$swal({icon: 'error', text: 'กรุณากรอกเรื่อง'});
                return;
            }

            if(this.dexternal.detail == ''){
                this.$swal({icon: 'error', text: 'กรุณากรอกรายละเอียด'});
                return;
            }

            this.content.disabled.subject = true;
            this.content.disabled.title = true;
            this.content.disabled.detail = true;
            this.content.disabled.btnSave = true;
            this.content.disabled.btnClose = true;

            let btnContent = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
            btnContent += 'กำลังบักทึกข้อมูล';

            this.content.btnSave = btnContent;

            if(this.paginate.method == 'create')
                var link = '/api/admin/it/external-pb/save';
            else if(this.paginate.method == 'update')
                var link = '/api/admin/it/external-pb/update';

            axios.post(link,this.dexternal)
            .then(response => {
                this.$swal({icon: 'success', text: 'บันทึกข้อมูลเรียบร้อยแล้ว'});
                this.resetForm();
                this.getListExternalPd();
                $('#modal-create').modal('hide');
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถบันทึกข้อมูลได้'});
                this.resetForm();
            });

        },
        resetForm(){
            this.content.disabled.subject = false;
            this.content.disabled.title = false;
            this.content.disabled.detail = false;
            this.content.disabled.btnSave = false;
            this.content.disabled.btnClose = false;
            this.content.btnSave = '<i class="fas fa-paper-plane"></i><span class="ml-2">บันทึก</span>'
        },
        removeExternalPb(index){

            let title = '',
                message = '';

            title  = 'ยืนยันการลบข้อมูล';
            message = 'คุณต้องการลบ '+this.lexternal.list[index].no+' หรือไม่';

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
                    axios.get('/api/admin/it/external-pb/delete/'+this.lexternal.list[index].id)
                    .then(response => {                        
                        this.$swal({icon: 'error', text: 'ลบบันทึกปัญหาภายนอกเรียบร้อยแล้ว'});
                        this.getListExternalPd();
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถลบบันทึกปัญหาภายนอกได้'});
                    });
                }
            });

        }
    }
}
</script>