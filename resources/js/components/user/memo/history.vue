<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-list-ul"></i><span class="ml-2">รายการ Memo</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="col-auto">
                                <label>เลขที่เอกสาร</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" 
                                placeholder="กรอกเลขที่เอกสาร..." aria-label="Search" 
                                    v-model="searchText"
                                    v-on:keyup.enter="getListMemo()"
                                >
                            </div>
                                <div class="col-auto">
                                    <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                    <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                        v-model="searchName"
                                        v-on:keyup.enter="getListMemo()"
                                    >
                            </div>
                            <div class="col-auto my-4">
                                <button class="btn btn-sm btn-amado my-2" type="button"
                                    v-on:click="getListMemo()"
                                >
                                    <i class="fas fa-search"></i>
                                    <span class="ml-2">ค้นหา</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-show="progress.main">
                    <div class="col-md-12 text-center text-primary">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                            role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" 
                            style="width: 100%"></div>
                        </div>
                        <b>กำลังดึงข้อมูล....</b>
                    </div>
                </div>
                <div class="row" v-show="!progress.main">
                    <div class="col-md-12">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th>เลขที่เอกสาร</th>
                                    <th>วันที่เอกสาร</th>
                                    <th>ผู้ร้องขอ</th>
                                    <th>จุดประสงค์</th>
                                    <th>สถานะ</th>
                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(m,index) in memo" :key="index">
                                    <th scope="row" class="text-center">{{m.index}}</th>
                                    <td>{{m.no}}</td>
                                    <td>{{m.date}}</td>
                                    <td>{{m.empName}} ({{m.empId}})</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 350px;" data-toggle="tooltip" :title="m.object">
                                            {{m.object}}
                                        </span>
                                    </td>
                                    <td>{{m.stsstr}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" 
                                        data-toggle="modal" data-target="#modal-itemreq"
                                            v-on:click="showDetailMemo(index)"
                                        >
                                            <i class="fas fa-file-alt mr-1"></i> รายละเอียด
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            :disabled="m.status != 1" 
                                            v-on:click="voidMemo(index)"
                                        ><i class="fas fa-ban mr-1"></i> ยกเลิก</button>
                                    </td>
                                </tr>
                                <tr v-show="memo.length <= 0">
                                    <td class="text-center" colspan="7"><b>ไม่มีรายการ</b></td>
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
                                    v-show="mmDetail.header.status == 1"></span>
                                    <span class="ml-2" v-show="mmDetail.header.status == 1">รอดำเนินการ</span>
                                    <i class="fas fa-check text-warning" v-show="mmDetail.header.status == 2"></i>
                                    <span class="ml-2" v-show="mmDetail.header.status == 2">กำลังดำเนินการ</span>
                                    <i class="fas fa-list-ul text-success" v-show="mmDetail.header.status == 3"></i>
                                    <span class="ml-2" v-show="mmDetail.header.status == 3">ดำเนินการเรียบร้อย</span>
                                    <i class="fas fa-ban text-danger" v-show="mmDetail.header.status == 0"></i>
                                    <span class="ml-2" v-show="mmDetail.header.status == 0">ยกเลิก</span>
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
                                    <div class="bottom-dotted-125 text-center">
                                        <span>{{mmDetail.header.no}}</span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-125 text-center">
                                        <span>{{mmDetail.header.date}}</span>
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
                                        <span class="ml-5">{{mmDetail.header.efullnameTh}}</span>
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
                                        <span class="ml-5">{{mmDetail.header.efullnameEn}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">แผนก</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{mmDetail.header.deptName}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">ตำแหน่ง</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{mmDetail.header.position}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <div class="mr-1 d-inline-block">เบอร์โทรศัพท์</div>
                                    <div class="bottom-dotted-225 text-center">
                                        <span>{{mmDetail.header.tel}}</span>
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
                                        <span class="ml-5">{{mmDetail.header.object}}</span>
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
                                        <tr v-for="(d,index) in mmDetail.detail" :key="index">
                                            <th scope="row" class="text-center" width="7%">{{index+1}}</th>
                                            <td>{{d.detail}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row justify-content-end m-0">
                            <div class="col-md-4 text-center">
                                <p class="mb-2">ผู้แจ้งขอดำเนินการ</p>
                                <p>{{mmDetail.header.efullnameTh}}</p>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <p class="mb-2">หมายเหตุ</p>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <p>{{mmDetail.header.remark}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                                <i class="fas fa-clipboard-check"></i>
                                                <span class="font-weight-bold mr-3">สถานะการอนุมัติ :</span>
                                                <i class="fas fa-exclamation-circle mr-2 text-primary"
                                                v-show="mmDetail.header.apprsts == 0"></i>
                                                <span class="font-weight-bold text-primary"
                                                v-show="mmDetail.header.apprsts == 0">ยังไม่ได้อนุมัติ</span>                                                
                                                <i class="fas fa-check-circle mr-2 text-success"
                                                v-show="mmDetail.header.apprsts == 1"></i>
                                                <span class="font-weight-bold text-success"
                                                v-show="mmDetail.header.apprsts == 1">อนุมัติแล้ว</span>
                                                <i class="fas fa-times-circle mr-2 text-danger"
                                                v-show="mmDetail.header.apprsts == 2"></i>
                                                <span class="font-weight-bold text-danger"
                                                v-show="mmDetail.header.apprsts == 2">ไม่อนุมัติ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="row mt-2" v-show="mmDetail.header.status == 2 || mmDetail.header.status == 3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 align-middle">
                                                <i class="fas fa-user-tag"></i>
                                                <span class="font-weight-bold">ผู้ดำเนินการ :</span>
                                                <span class="font-weight-bold text-primary">{{mmDetail.header.assign_name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="m-0">
                                                    <i class="fas fa-folder-open mr-2"></i>
                                                    <b>เอกสาร Job Request</b>
                                                </h6>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">                                            
                                            <div class="col-12" v-show="files.progress">
                                                <div class="progress my-3">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" 
                                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                                </div>
                                            </div>
                                            <div class="col-12" v-show="!files.progress">
                                                <label>รายการเอกสาร : </label>
                                                <ul class="list-group">
                                                    <li class="list-group-item py-1"
                                                        v-for="(list,index) in files.data" :key="index"
                                                    >
                                                        <strong>#{{index+1}}</strong> File : 
                                                        <a class="text-decoration-none text-primary ml-3" target="_blank"                                         
                                                            :href="list.mm_file_path"
                                                        >{{list.mm_file_name}}</a>
                                                    </li>
                                                    <li class="list-group-item text-center py-1"
                                                    v-show="files.data.length <= 0"><b>ไม่มีไฟล์เอกสาร</b></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="pb-3">
                        <div class="row justify-content-center pb-3">
                            <div class="col-md-4">
                                <a type="button" class="btn btn-primary w-100"
                                    :href="'/pdf/memo/'+mmDetail.header.id" :target="'_bland'"
                                ><i class="fas fa-print mr-1"></i> พิมพ์</a>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal"><i class="fas fa-times mr-1"></i> ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-void-remark -->
        <div class="modal fade" id="modal-void-remark" data-backdrop="static" data-keyboard="false" 
        tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" v-html="cancel.title">
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <textarea class="form-control" rows="3" v-model="cancel.remark"></textarea>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-5">
                                <button type="button" class="btn btn-primary w-100" v-on:click="setVoid()">
                                    <i class="fas fa-paper-plane mr-2"></i> ส่งยกเลิก Memo
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i> ปิด
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    mounted(){
        this.getListMemo();
    },
    data(){
        return{
            searchText: '',   
            searchName: '',
            progress: {
                main: true
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
                list: 25,
                show: false
            },
            memo: [],
            mmDetail: {
              header: {},
              detail: []  
            },
            cancel:{
                title: '',
                id: '',
                no: '',
                remark: ''
            },
            files:{
                progress: false,
                data: []
            }
        }
    },
    methods:{
        getListMemo(){
            this.progress.main = true;
            this.paginate.show = false;

            axios.post('/api/user/memo/history',{
                text: this.searchText,
                searchEmp: this.searchName,
                paginate: this.paginate.list
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            });

        },
        getDataPaginate(page){

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }
            
            if( this.paginate.url === null || this.paginate.url === undefined )
                return;
            
            axios.post(this.paginate.url,{
                text: this.searchText,
                searchEmp: this.searchName,
                paginate: this.paginate.list
            })
            .then(response => { this.setDataAndPaginate(response.data) }); 

        },
        setDataAndPaginate(dataArr){

            this.paginate.url = dataArr.path;
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

            if(dataArr.data.length > 0){

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

                this.memo = resultData
                this.paginate.numberPage = resultPageNumber;

                this.progress.main = false;
                this.paginate.show = true;

            }
            else{
                this.memo = [];
                this.progress.main = false;
                this.paginate.show = false;
            }

        },
        showDetailMemo(index){

            axios.get('/api/user/memo/detail/'+this.memo[index].id)
            .then(response => {
                this.mmDetail.header = response.data.header;
                this.mmDetail.detail = response.data.detail;
                $('#modal-memo').modal('show');
                this.getMemoFile(this.memo[index].id);
            });

        },
        getMemoFiles(mmId){

            this.files.progress = true;

            axios.get('/api/admin/it/it-memo/files/list/'+mmId)
            .then(response => {
                this.files.data = response.data
                this.files.progress = false;
            });

        },
        voidMemo(index){
            this.$swal({
                icon: 'warning',
                title: 'ยืนยันการยกเลิก Memo',
                text: 'ต้องการยกเลิก '+this.memo[index].no+' หรือไม่',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ยืนยัน',
                denyButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if(result.isConfirmed){
                    this.cancel.title = 'ใส่หมายเหตุการยกเลิก '+this.memo[index].no;
                    this.cancel.id = this.memo[index].id;
                    this.cancel.no = this.memo[index].no;
                    this.cancel.remark = '';
                    $('#modal-void-remark').modal('show');
                }
            });
        },
        setVoid(){

            if(this.cancel.remark == ''){
                this.$swal({icon: 'error', text: 'กรุณาใส่หมายเหตุการยกเลิก'});
                return;
            }

            axios.post('/api/user/memo/void',{
                id: this.cancel.id,
                no: this.cancel.no,
                status: 0,
                remark: this.cancel.remark
            })
            .then(response => {
                if(response.data.status == false){
                    if(response.data.error == 'login.fail'){
                        $('#modal-void-remark').modal('hide');
                         this.$swal({
                            title: 'เวลาในการ Login หมดลงแล้ว', text: 'กรุณา Login ใหม่', icon: 'Error',
                            showCancelButton: false, cancelButtonColor: '#d33',
                            confirmButtonColor: '#3085d6', confirmButtonText: 'ไปยังหน้า Login',
                        })
                        .then((result) => { if(result.isConfirmed){ location.href = '/login'; } });
                    }
                    else if(response.data.error == 'page.action'){
                        $('#modal-void-remark').modal('hide');
                        this.$swal({icon: 'success', text: 'ยกเลิก Memo เรียบร้อยแล้ว'});
                        this.getDataPaginate(this.paginate.currentPage);
                    }
                }
                else{
                    $('#modal-void-remark').modal('hide');
                    this.$swal({icon: 'success', text: 'ยกเลิก Memo เรียบร้อยแล้ว'});
                    this.getDataPaginate(this.paginate.currentPage);
                }
            })
            .catch(response => {
                this.$swal({icon: 'error', text: 'ไม่สามารถยกเลิก Memo ได้'});
            });

        }
    }
}
</script>