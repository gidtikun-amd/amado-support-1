<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-list-ul"></i><span class="ml-2">รายการ Ticket</span>
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
                                :disabled="disabled.inptSearch"
                                v-on:keyup.enter="getTicketHistory()">
                            </div>
                            <div class="col-auto">
                                <label>รหัสพนักงาน, ชื่อ, นามสกุล</label>
                                <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกรหัสพนักงาน, ชื่อ, นามสกุล..." aria-label="Search"
                                v-model="searchName"
                                :disabled="disabled.inptSearch"
                                v-on:keyup.enter="getTicketHistory()"
                                >
                            </div>
                            <div class="col-auto my-4">
                                <button class="btn btn-sm btn-amado my-2" type="button"
                                :disabled="disabled.btnSearch"
                                v-on:click="getTicketHistory()">
                                    <i class="fas fa-search"></i>
                                    <span class="ml-2">ค้นหา</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-show="panel.progress">
                    <div class="col-md-12 text-center">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <strong class="text-primary">ระบบกำลังดึงข้อมูล...</strong>
                    </div>
                </div>
                <div class="row" v-show="panel.table">
                    <div class="col-md-12">

                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>เลขที่เอกสาร</th>
                                    <th>วันที่เอกสาร</th>
                                    <th>ผู้ร้องขอ</th>
                                    <th>เรื่อง</th>
                                    <th>สถานะ</th>
                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list,index) in ticket.history" :key="index">
                                    <td>{{list.index}}</td>
                                    <td>{{list.ticket_no}}</td>
                                    <td>{{list.ticket_date}}</td>
                                    <td>{{list.emp_name}}</td>
                                    <td>{{list.subject_name}}</td>
                                    <td>{{list.ticket_sts_str}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" 
                                        v-on:click="getTicketDetail(index)"><i class="fas fa-file-alt mr-1"></i> รายละเอียด</button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                        :disabled="list.ticket_sts != 1"
                                        v-on:click="setVoidTicket(list.ticket_id,list.ticket_no)">
                                            <i class="fas fa-ban mr-1"></i> ยกเลิก
                                        </button>
                                    </td>
                                </tr>
                                <tr v-show="ticket.history.length <= 0">
                                    <td class="text-center" colspan="7"><b>ไม่มีรายการ</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="card-footer small text-muted">                
                <nav aria-label="Page navigation example" v-show="panel.paginate">
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


        <!-- Modal : #modal-ticket-detail -->
        <div class="modal fade" id="modal-ticket-detail" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span class="mr-2">สถานะ : </span>

                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"
                                    v-show="ticket.detail.data.ticket_sts == 1"></span>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 1">รออนุมัติ</span>

                                    <span class="spinner-grow spinner-grow-mini text-warning" role="status" aria-hidden="true"
                                    v-show="ticket.detail.data.ticket_sts == 2"></span>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 2">รอรับดำเนินการ</span>

                                    <span class="spinner-border spinner-border-sm text-warning"
                                    v-show="ticket.detail.data.ticket_sts == 3"></span>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 3">กำลังดำเนินการ</span>

                                    <i class="fas fa-list-ul text-success" v-show="ticket.detail.data.ticket_sts == 4"></i>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 4">ดำเนินการเรียบร้อย</span>

                                    <i class="fas fa-ban text-danger" v-show="ticket.detail.data.ticket_sts == 0"></i>
                                    <span class="ml-2" v-show="ticket.detail.data.ticket_sts == 0">ไม่อนุมัติ</span>

                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body m-4">
                        <div class="row m-0 mb-3">
                            <div class="col-md-12 text-center mb-2">
                                <img class="logo-nav" src="/img/logo-red.png" alt="">
                            </div>
                            <div class="col-md-12 text-center">
                                <p class="mb-0"><b>TICKET REQUEST</b></p>
                            </div>
                        </div>

                        <div class="row m-0 mt-3 mb-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <span><b>เลขที่คำขอ </b><span class="ml-2">{{ticket.detail.data.ticket_no}}</span></span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <span><b>วันที่ </b><span class="ml-2">{{ticket.detail.data.ticket_date}}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <span class="mr-4"><b>ชื่อ-นามสกุล </b><span class="ml-2">{{ticket.detail.data.emp_name}}</span></span>
                                                <span><b>ฝ่าย </b><span class="ml-2 mr-4">{{ticket.detail.data.detp_name}}</span></span>
                                                <span><b>ติดต่อกลับ </b><span class="ml-2">{{ticket.detail.data.ticket_callback}}</span></span>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row mb-3">
                                            <div class="col-auto my-auto">
                                                <h5 class="card-title m-0"><b>เรื่อง : </b>{{ticket.detail.data.subject_name}}</h5>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <p class="card-text mb-0"><b>รายละเอียด : </b><span>{{ticket.detail.data.ticket_detail}}</span></p>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">
                                            <div class="col-auto my-auto"><p class="card-text m-0"><b>ไฟล์ : </b></p></div>
                                            <div class="col">
                                                <div class="form-group col-md-12 p-0 mb-0">
                                                    <ul class="list-group">
                                                        <li class="list-group-item py-1" v-for="(file,index) in ticket.detail.file" :key="index">
                                                            <h5 class="d-inline mr-3">#{{index+1}}</h5>
                                                            <span>Url : </span>
                                                            <span class="badge badge-light">
                                                                <a :href="'/'+file.it_ticket_file_path" target="_blank" 
                                                                class="text-decoration-none">{{file.it_ticket_file}}</a></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">
                                            <div class="col text-center" v-show="ticket.detail.data.ticket_sts == 0">
                                                <p class="card-text mb-0"><b><i class="fas fa-exclamation-triangle text-amado mr-2"></i> ไม่อนุมัติ/ยกเลิก</b></p>
                                            </div>
                                            <div class="col text-center" v-show="ticket.detail.data.ticket_sts != 0">
                                                <p class="card-text mb-0"><b>ระดับความสำคัญของ Ticket : </b>
                                                    <span class="badge badge-pill badge-success"
                                                    v-show="ticket.detail.data.ticket_level == 1">Low</span>
                                                    <span class="badge badge-pill badge-warning"
                                                    v-show="ticket.detail.data.ticket_level == 2">Medium</span>
                                                    <span class="badge badge-pill badge-danger"
                                                    v-show="ticket.detail.data.ticket_level == 3">High</span>
                                                </p>
                                            </div>

                                            <div class="col text-center">
                                                <p class="card-text mb-0"><b>ผู้ดำเนินการ : </b><span>{{ticket.detail.data.assign_name}}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mt-3 mb-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fas fa-comment-dots"></i> <b>ข้อความจากผู้ดำเนินการ</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row justify-content-end" v-for="(msg,index) in ticket.message" :key="index">
                                            <div class="col-md-8 mb-2">        
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text">{{msg.msg_text}}</p>
                                                    </div>
                                                    <div class="card-footer text-right">
                                                        <small class="text-muted">โดย {{msg.emp_name}} ({{msg.create_date}})</small>   
                                                    </div>
                                                </div>                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0 justify-content-center p-3">
                            <button type="button" class="col-md-3 btn btn-danger mr-3"
                            v-show="ticket.detail.data.ticket_sts == 1 || ticket.detail.data.ticket_sts == 2"
                            v-on:click="setVoidTicket(ticket.detail.data.ticket_id,ticket.detail.data.ticket_no)">
                                <i class="fas fa-ban"></i><span class="ml-2">ยกเลิก</span>
                            </button>
                            <button type="button" class="col-md-2 btn btn-secondary mr-3" data-dismiss="modal">
                                <i class="fas fa-times"></i><span class="ml-2">ปิด</span>
                            </button>
                        </div>

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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                            role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" 
                            style="width: 100%"></div>
                        </div>
                        <strong class="text-primary">ระบบกำลังบันทึกข้อมูล</strong>
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
        this.getTicketHistory();
    },
    data(){
        return{
            searchText: '',    
            searchName: '',
            disabled: {
                inptSearch: false,
                btnSearch: false
            },
            panel: {
                progress: true,
                table: false,
                paginate: false
            },
            ticket: {
                history: {},
                detail: {
                    data: {},
                    file: {}
                },
                message: {}
            },            
            paginate: {
                method: 'all',
                show: false,
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
        getTicketHistory(){

            this.panel.progress = true;
            this.panel.table = false;
            this.panel.paginate = false;
            this.disabled.inptSearch = true;
            this.disabled.btnSearch = true;

            axios.post('/api/user/ticket/history',{ 
                search: this.searchText,
                searchEmp: this.searchName,
            })
            .then(response => {this.setDataAndPaginate(response.data)});

        },
        getDataPaginate(page){

            this.panel.progress = true;
            this.panel.table = false;
            this.panel.paginate = false;
            this.disabled.inptSearch = true;
            this.disabled.btnSearch = true;

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }
            
            if( this.paginate.url == '' || this.paginate.url === null ){
                this.panel.progress = false;
                this.panel.table = true;
                this.panel.paginate = true;
                this.disabled.inptSearch = false;
                this.disabled.btnSearch = false;
                return;
            }

            axios.post(this.paginate.url,{
                search: this.searchText,
                searchEmp: this.searchName,
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
            this.ticket.history = resultData;
            this.panel.progress = false;
            this.panel.table = true;
            this.panel.paginate = true;
            this.disabled.inptSearch = false;
            this.disabled.btnSearch = false;

        },
        getTicketDetail(index){
            axios.get('/api/user/ticket/history/detail/'+this.ticket.history[index].ticket_id)
            .then(response => {

                this.ticket.detail.data = response.data.detail;
                this.ticket.detail.file = response.data.files;

                if( this.ticket.detail.data.ticket_sts == 0 && this.ticket.detail.data.assign_name == 'ไม่มีผู้ดำเนินการ' )
                    this.ticket.detail.data.assign_name = this.ticket.detail.data.emp_name;
                
                this.getTicketMessage(this.ticket.history[index].ticket_id);

                $('#modal-ticket-detail').modal('show');
            });
        },
        setVoidTicket(id,no){
            this.$swal({
                title: 'ยืนยันการยกเลิก Ticket',
                text: 'ต้องการยกเลิก Ticket '+no+' หรือไม่',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed){

                    axios.post('/api/user/ticket/void',{
                        ticket_id: id,
                        status: 0,
                        user: this.$session.get('user')
                    })
                    .then(response => {
                        this.$swal({ text: 'ยกเลิก Ticket '+no+' เรียบร้อลแล้ว', icon: 'success' });
                        this.getTicketHistory();
                    })
                    .catch(response => {
                        this.$swal({ text: 'ไม่สามารถยกเลิก Ticket '+no+' ได้', icon: 'error' });
                    });

                }
            });
        },
        getTicketMessage(ticketId){
            axios.get('/api/user/ticket/message/list/'+ticketId)
            .then(response => { this.ticket.message = response.data });
        }
    }
}
</script>