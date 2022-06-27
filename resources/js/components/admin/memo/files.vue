<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการยังไม่ได้อัพโหลดเอกสาร</span>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="col-auto">
                                <label>เลขที่เอกสาร</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกเลขที่เอกสาร..." aria-label="Search"
                                    v-model="searchTxt"
                                    v-on:keyup.enter="getDataPaginate(paginate.currentPage)"
                                >
                            </div>
                            <div class="col-auto">
                                <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                    v-model="searchName"
                                    v-on:keyup.enter="getDataPaginate('first')"
                                >
                            </div>
                            <div class="col-auto">
                                <label>สานะ</label>
                                <select class="form-control form-control-sm"
                                v-model="searchStatus"
                                v-on:keyup.enter="getDataPaginate('first')">
                                    <option value="99">ทั้งหมด</option>
                                    <option value="2">กำลังดำเนินการ</option>
                                    <option value="3">ดำเนินการเรียบร้อยแล้ว</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label>ปี/เดือน/วัน</label>
                                <div class="input-group input-group-sm">
                                    <input type="date" class="form-control"
                                    v-model='searchDateFrom'>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">ถึง</span>
                                    </div>
                                    <input type="date" class="form-control"
                                    v-model='searchDateTo'>
                                </div>
                            </div>
                            <div class="col-auto my-4">
                                <button class="btn btn-sm btn-amado my-2" type="button"
                                    v-on:click="getDataPaginate(paginate.currentPage)"
                                >
                                    <i class="fas fa-search"></i>
                                    <span class="ml-2">ค้นหา</span>
                                </button>
                                <button class="btn btn-sm btn-primary ml-2" type="button"
                                    v-on:click="getDataPaginate('first')"
                                >
                                    <i class="fas fa-sync-alt mr-2"></i> ดึงข้อมูลใหม่
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center mt-2" v-show="progress.show">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading....</div>
                        </div>
                    </div>
                    <div class="col-md-12" v-show="!progress.show">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">เลขที่คำขอ</th>
                                    <th scope="col">วันที่สร้าง</th>
                                    <th scope="col">ชื่อ</th>
                                    <th scope="col">ฝ่าย</th>
                                    <th scope="col">จุดประสงค์</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">ผู้ดำเนินการ</th>
                                    <th scope="col">สถานะเอกสาร</th>
                                    <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(a,index) in memo" :key="index">
                                    <th scope="row">{{a.index}}</th>
                                    <td>{{a.no}}</td>
                                    <td>{{a.date}}</td>
                                    <td>{{a.efullnameTh}}</td>
                                    <td>{{a.deptname}}</td>
                                    <td>{{a.object}}</td>
                                    <td>{{a.apprstsstr}}</td>
                                    <td>{{a.assign_name}}</td>
                                    <td>{{a.sfiles}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm mb-1"
                                            v-on:click="getMemoDetail(a.id)">
                                        <i class="fas fa-file-alt mr-2"></i>ดูรายละเอียด
                                        </button>
                                        <a class="btn btn-success btn-sm mb-1"
                                           :href="'/pdf/memo/'+a.id"
                                           :target="'_blank'">
                                            <i class="fas fa-print mr-2"></i>Print
                                        </a>
                                        <button type="button" class="btn btn-secondary btn-sm mb-1"
                                                v-on:click="modalMemoFile(index)">
                                            <i class="far fa-folder-open mr-2"></i>เอกสาร
                                        </button>
                                    </td>
                                </tr>
                                <tr v-show="memo.length <= 0"><td class="text-center" colspan="10"><b>ไม่มีข้อมูล</b></td></tr>
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

        <!-- Modal : #modal-memo-file -->
        <div class="modal fade" id="modal-memo-file" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary">
                            <i class="fas fa-folder-open mr-2"></i><span>เอกสาร Memo</span>
                        </h5>
                    </div>
                    <div class="modal-body p-5">
                        <div class="row">
                            <div class="col-12">
                                <form enctype="multipart/form-data">
                                    <div class='form-group'>
                                        <label>Upload ไฟล์ : </label> <label v-html="files.upload.status"></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="memoUploadFile" aria-describedby="btnMemoUploadFile"  accept=".pdf"
                                                v-on:change="onFileChange"
                                                v-bind:class="{
                                                    'is-void': files.upload.classInptValid,
                                                    'is-invalid': files.upload.classInptInvalid
                                                }"
                                                :disabled="files.upload.inptDisabled"
                                                >
                                                <label class="custom-file-label" for="jobreqUploadFile" >Choose file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-secondary" id="btnMemoUploadFile"
                                                @click.prevent="uploadItmreqFile"
                                                :disabled="files.upload.btnUploadDisabled"
                                                >
                                                    <i class="fas fa-cloud-upload-alt"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="progress my-3" v-show="files.upload.progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>รายการเอกสาร : </label>
                                <ul class="list-group">
                                    <li class="list-group-item py-1" v-for="(file,index) in files.list" :key="index">
                                        <strong>#{{index+1}}</strong> File :
                                        <a class="text-decoration-none text-primary ml-3"
                                        :href="file.mm_file_path"
                                        :target="'_blank'">
                                            {{file.mm_file_name}}
                                        </a>
                                        <a href="javascript:;" class="float-right"
                                        v-on:click="removeMemoFile(index)">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>ออก
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-memo -->
        <div class="modal fade" id="modal-memo" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span class="mr-2">สถานะ : </span>
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"
                                    v-show="data.header.apprsts == 0"></span>
                                    <span class="ml-2" v-show="data.header.apprsts == 0">รออนุมัติ</span>
                                    <i class="fas fa-check text-warning" v-show="data.header.status == 2"></i>
                                    <span class="ml-2" v-show="data.header.status == 2">กำลังดำเนินการ</span>
                                    <i class="fas fa-list-ul text-success" v-show="data.header.status == 3"></i>
                                    <span class="ml-2" v-show="data.header.status == 3">ดำเนินการเรียบร้อย</span>
                                    <i class="fas fa-ban text-danger" v-show="data.header.apprsts == 2 || data.header.status == 0"></i>
                                    <span class="ml-2" v-show="data.header.apprsts == 2 || data.header.status == 0">ไม่อนุมัติ</span>
                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mx-4 mt-5">
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
                                    <div class="bottom-dotted-175 text-center">
                                        <span>{{data.header.no}}</span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span>{{data.header.date}}</span>
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
                                        <span class="ml-5">{{data.header.efullnameTh}}</span>
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
                                        <span class="ml-5">{{data.header.efullnameEn}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">แผนก</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{data.header.deptname}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{data.header.position}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">เบอร์โทรศัพท์</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{data.header.tel}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-auto">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">จุดประสงค์ในการดำเนินการ</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-2">
                                    <div class="bottom-dotted-full">
                                        <span class="ml-5">{{data.header.object}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3 mt-3">
                            <div class="col-md-12">
                                <table class="table table-hover table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan="2" class="text-center">รายละเอียด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(md,index) in data.detail" :key="index">
                                            <th scope="row" class="text-center" width="7%">{{index+1}}</th>
                                            <td>{{md.detail}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-end m-0">
                            <div class="col-md-4 text-center">
                                <p class="mb-2">ผู้แจ้งขอดำเนินการ</p>
                                <p>{{data.header.efullnameTh}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning text-center" role="alert" 
                                v-show="data.header.status == 2">
                                    <h5><i class="fas fa-check mr-2"></i> รายการ Memo กำลังดำเนินการ</h5>
                                </div>
                                <div class="alert alert-success text-center" role="alert" 
                                v-show="data.header.status == 3">
                                    <h5><i class="fas fa-check mr-2"></i> รายการ Memo เสร็จเรียบร้อยแล้ว</h5>
                                </div>
                                <div class="alert alert-danger text-center" role="alert" 
                                v-show="data.header.apprsts == 2">
                                    <h5><i class="fas fa-times mr-2"></i> รายการ Memo ไม่อนุมัติ</h5>
                                </div>
                                หมายเหตุ
                                <textarea class="form-control" rows="3"
                                v-show="data.header.apprsts == 2"
                                v-html="data.header.remark"
                                readonly>
                                </textarea>
                            </div>
                        </div>

                        <div class="row mt-2" v-show="data.header.status == 2 || data.header.status == 3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 align-middle">
                                                <i class="fas fa-user-tag"></i>
                                                <span class="font-weight-bold">ผู้ดำเนินการ :</span>
                                                <span class="font-weight-bold text-primary">{{data.header.assign_name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                             <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="font-weight-bold">บันทึกการ</span>
                                                <hr style="margin-top: 0px;">
                                                <ul class="list-group">
                                                    <li class="list-group-item py-1" v-for="(l,index) in data.log" :key="index">
                                                        <span class="mr-3">{{index+1}})</span>
                                                        <span class="font-weight-bold text-primary">{{l.action}}</span>
                                                        <span class="font-weight-bold ml-2 mr-2">:</span>
                                                        <span class="font-weight-bold text-primary">
                                                            {{l.fullname}} ({{l.created_at}})
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
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
        this.getMemoList();
    },
    data(){
        return{
            searchTxt: '',
            searchName: '',
            searchDateFrom: '',
            searchDateTo: '',
            searchStatus: 3,
            progress: {
                show: false,
            },
            button:{
                search:{
                    disabled: false,
                },
                redata:{
                    disabled: false,
                }
            },
            memo: [],
            data: {
                header: {},
                detail: [],
                log: {},
            },
            files:{
                list: [],
                index: '',
                file_name: '',
                file: null,
                upload: {
                    progress: false,
                    status: '',
                    classInptValid: false,
                    classInptInvalid: false,
                    inptDisabled: false,
                    btnUploadDisabled: false
                }
            },
            paginate: {
                method: '',
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
                list: 25,
                show: false
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
        getMemoList(){

            this.progress.show = true;
            this.button.search.disabled = true;
            this.button.redata.disabled = true;         
            this.paginate.show = false;

            axios.post('/api/admin/it/it-memo/non-upload-file',{
                search: this.searchTxt,
                searchEmp: this.searchName,
                searchDateFrom: this.searchDateFrom,
                searchDateTo: this.searchDateTo,
                searchStatus: this.searchStatus,
                paginate: this.paginate.list
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            });

        },
        getMemoDetail(id){
            axios.get('/api/admin/it/it-memo/detail/'+id)
            .then(response => {
                this.data.header = response.data.header;
                this.data.detail = response.data.detail;
                this.data.log = response.data.log;
                $('#modal-memo').modal('show');
            });
        },
        getDataPaginate(page){

            this.progress.show = true;
            this.button.search.disabled = true;
            this.button.redata.disabled = true;         
            this.paginate.show = false;

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post('/api/admin/it/it-memo/non-upload-file',{
                search: this.searchTxt,
                searchEmp: this.searchName,
                searchDateFrom: this.searchDateFrom,
                searchDateTo: this.searchDateTo,
                searchStatus: this.searchStatus,
                paginate: this.paginate.list
            })
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
            this.memo = resultData;
            this.progress.show = false;
            this.button.search.disabled = false;
            this.button.redata.disabled = false;
            this.paginate.show = true;

        },
        modalMemoFile(index){
            this.files.index = index;
            $('#modal-memo-file').modal('show');
            this.getMemoFiles();
        },
        getMemoFiles(){
            axios.get('/api/admin/it/it-memo/files/list/'+this.memo[this.files.index].id)
            .then(response => {this.files.list = response.data});
        },
        onFileChange(e){
            this.files.file = e.target.files[0];
            this.files.file_name = this.files.file.name;
            this.files.upload.status = ''
            this.files.upload.classInptValid = false;
            this.files.upload.classInptInvalid = false;
            this.files.upload.inptDisabled = false;
            this.files.upload.btnUploadDisabled = false;
            $('#memoUploadFile').next('.custom-file-label').html(this.files.file_name);
        },
        uploadItmreqFile(e){

            if(!this.checkSeessionLogin())
                return;

            if( this.files.file === null ){
                let sts_fail = '<i class="fas fa-exclamation-triangle text-danger mr-2"></i>';
                    sts_fail += '<strong class="text-danger">กรุณาเลือก File</strong>';
                this.files.upload.status = sts_fail;
                this.files.upload.classInptValid = false;
                this.files.upload.classInptInvalid = true;
                return;
            }
            else if( this.files.file.type != 'application/pdf' ){
                let sts_fail = '<i class="fas fa-exclamation-triangle text-danger mr-2"></i>';
                    sts_fail += '<strong class="text-danger">กรุณาเลือก File PDF เท่านั้น</strong>';
                this.files.upload.status = sts_fail;
                this.files.upload.classInptValid = false;
                this.files.upload.classInptInvalid = true;
                return;
            }

            this.files.upload.status = ''
            this.files.upload.classInptValid = false;
            this.files.upload.classInptInvalid = false;
            this.files.upload.inptDisabled = true;
            this.files.upload.btnUploadDisabled = true;
            this.files.upload.progress = true;

            e.preventDefault();

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            let formData = new FormData();
            formData.append('id',this.memo[this.files.index].id);
            formData.append('file_name',this.files.file_name);
            formData.append('file',this.files.file);

            axios.post('/api/admin/it/it-memo/files/upload',formData,config)
            .then(response => {
                $('#memoUploadFile').next('.custom-file-label').html('Choose file');
                this.files.file = null;
                let sts_sc = '<i class="fas fa-check-circle text-success mr-3"></i>';
                    sts_sc += '<strong class="text-success">Upload file complete.</strong>';
                this.files.upload.status = sts_sc;
                this.files.upload.progress = false;
                this.files.upload.classInptValid = true;
                this.files.upload.classInptInvalid = false;
                this.files.upload.inptDisabled = false;
                this.files.upload.btnUploadDisabled = false;
                this.getMemoFiles();
            })
            .catch(response => {
                //this.files.file == null;
                let sts_fl = '<i class="fas fa-exclamation-triangle text-danger mr-3"></i>';
                    sts_fl += '<strong class="text-danger">Upload file fail!</strong>';
                this.files.upload.status = sts_fl;
                this.files.upload.progress = false;
                this.files.upload.classInptValid = false;
                this.files.upload.classInptInvalid = true;
                this.files.upload.inptDisabled = false;
                this.files.upload.btnUploadDisabled = false;
            });

        },
        removeMemoFile(index){

            if(!this.checkSeessionLogin())
                return;

            this.$swal({
                icon: 'warning',
                text: 'ยืนยันการลบไฟล์ '+this.files.list[index].mm_file_name,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ลบไฟล์',
                denyButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if( result.isConfirmed ){
                    axios.post('/api/admin/it/it-memo/files/remove',{
                        file_id: this.files.list[index].mm_file_id,
                        file_path: this.files.list[index].mm_file_path
                    })
                    .then(response => {
                        this.$swal({
                            icon: 'success',
                            title: 'ลบไฟล์เรียบร้อยแล้ว'
                        });
                        this.getMemoFiles();
                    })
                    .catch(response => {
                        this.$swal({
                            icon: 'error',
                            title: 'ไม่สามารถลบไฟล์ได้'
                        });
                    });
                }
            });
        }
    }
}
</script>
