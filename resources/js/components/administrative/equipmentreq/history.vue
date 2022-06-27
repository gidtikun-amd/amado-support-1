<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-list-ul"></i><span class="ml-2">รายการ ใบเบิกอุปกรณ์สำนักงาน</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-inline mb-3">
                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกเลขที่เอกสาร..." aria-label="Search" 
                                v-model="searchTxt"
                                v-on:keyup.enter="getListReq()"
                            >
                            <button class="btn btn-sm btn-amado my-2 my-sm-0" type="button"
                                v-on:click="getListReq()"
                            >
                                <i class="fas fa-search"></i>
                                <span class="ml-2">ค้นหา</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" v-show="progress.main">
                    <div class="col-md-12 text-center text-primary">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                            role="progressbar" aria-valuenow="100" aria-valuemin="0" 
                            aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <b>กำลังดึงข้อมูล...</b>
                    </div>
                </div>
                <div class="row" v-show="!progress.main">
                    <div class="col-md-12">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>เลขที่เอกสาร</th>
                                    <th>วันที่ร้องขอ</th>
                                    <th>ผู้ร้องขอ</th>
                                    <th>ฝ่าย</th>
                                    <th>สถานะ</th>
                                    <th class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(req,index) in equipreq" :key="index">
                                    <th class="align-middle">{{index+1}}</th>
                                    <td class="align-middle">{{req.no}}</td>
                                    <td class="align-middle">{{req.date}}</td>
                                    <td class="align-middle">{{req.fullnameTh}}</td>
                                    <td class="align-middle">{{req.deptname}}</td>
                                    <td class="align-middle">{{req.strsts}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            v-on:click="getDataReqDetail(index)"
                                        ><i class="fas fa-file-alt mr-1"></i> รายละเอียด</button>
                                        
                                        <button type="button" class="btn btn-danger btn-sm"
                                            :disabled="req.status != 1"
                                            v-on:click="confirmVoidReq(index)"
                                        ><i class="fas fa-ban mr-1"></i> ยกเลิก</button>
                                    </td>
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
                            v-on:click="getDataPaginate('perv')"
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

        <!-- Modal : #modal-req -->
        <div class="modal fade" id="modal-req" data-backdrop="static" data-keyboard="false" 
        tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row m-0">
                            <div class="col-md-12">
                                <p class="mb-0">
                                    <span class="mr-2">สถานะ : </span>

                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" 
                                    aria-hidden="true" v-show="oneeqreq.header.status == 1"></span>
                                    <span class="ml-2" v-show="oneeqreq.header.status == 1">รอดำเนินการ</span>

                                    <i class="fas fa-check text-warning" v-show="oneeqreq.header.status == 2"></i>
                                    <span class="ml-2" v-show="oneeqreq.header.status == 2">กำลังดำเนินการ</span>

                                    <i class="fas fa-shipping-fast text-danger" v-show="oneeqreq.header.status == 3"></i>
                                    <span class="ml-2" v-show="oneeqreq.header.status == 3">คงค้าง</span>

                                    <i class="fas fa-list-ul text-success" v-show="oneeqreq.header.status == 4"></i>
                                    <span class="ml-2" v-show="oneeqreq.header.status == 4">ดำเนินการเรียบร้อย</span>

                                    <i class="fas fa-ban text-danger" v-show="oneeqreq.header.status == 0"></i>
                                    <span class="ml-2" v-show="oneeqreq.header.status == 0">ยกเลิก</span>
                                </p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body mx-4 mt-5">
                        <div class="row">
                            <div class="col-md-4">
                                <img class="logo-nav" :src="'/img/logo-red.png'" alt="">
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-0"><b>ใบเบิกอุปกรณ์สำนักงาน</b></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">เลขที่เอกสาร</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span>{{oneeqreq.header.no}}</span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="mr-1 d-inline-block">วันที่</div>
                                    <div class="bottom-dotted-175 text-center">
                                        <span>{{oneeqreq.header.dmyDate}}</span>
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
                                        <span class="ml-5">{{oneeqreq.header.fullnameTh}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle" rowspan="2" width="7%">ลำดับ</th>
                                            <th class="text-center align-middle" rowspan="2">รายการ</th>                                            
                                            <th class="text-center align-middle" colspan="3" width="25%">จำนวนเบิก</th> 
                                            <th class="text-center align-middle" rowspan="2" width="10%">ได้รับ</th> 
                                            <th class="text-center align-middle" rowspan="2" width="10%">ยังไม่ได้รับ</th> 
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle">จำนวน</th>
                                            <th class="text-center align-middle">หน่วยนับ</th>
                                            <th class="text-center align-middle">Budget</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(dt,index) in oneeqreq.detail" :key="index">
                                            <th class="text-center">{{dt.line}}</th>
                                            <td>{{dt.itName}}</td>
                                            <td class="text-right">{{dt.qty}}</td>
                                            <td class="text-center">{{dt.itUnit}}</td>
                                            <td class="text-right">{{dt.ttbudget}}</td>
                                            <td class="text-right">{{dt.sendto}}</td>
                                            <td class="text-right">{{dt.outstand}}</td>
                                        </tr>
                                    </tbody><tfoot>
                                        <tr>
                                            <td colspan="7">
                                                รวม Budget : <b>{{oneeqreq.header.netbudget | numFormat('0.00')}}</b>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-end m-0">
                            <div class="col-md-4 text-center">
                                <p class="mb-2">ผู้เบิก</p>
                                <p>{{oneeqreq.header.fullnameTh}}</p>
                            </div>
                        </div>
                        <div class="row mt-2" v-if="oneeqreq.header.remark != '' && oneeqreq.header.remark !== null">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">                                        
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="m-0">
                                                    <i class="fas fa-tag mr-2"></i>
                                                    <b>หมายเหตุจาก Admin</b>
                                                </h6>
                                            </div>
                                        </div>
                                        <hr class="mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <form>
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="3" v-html="oneeqreq.header.remark" readonly></textarea>
                                                    </div>
                                                </form>
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
                                    :href="'/pdf/equipreq/'+oneeqreq.header.id" :target="'_blank'"
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
        // $('#modal-req').modal('show');
        this.getListReq();
    },
    data(){
        return{
            searchTxt: '',
            progress: {
                main: false,
            },
            equipreq: [],
            oneeqreq: {
                header: {},
                detail: []
            },
            paginate: {
                url: '',
                path: '',
                firstPageUrl: '',
                lastPageUrl: '',
                nextPageUrl: '',
                pervPageUrl: '',
                currentPage: 0,
                lastPage: 0,
                from: 0,
                to: 0,
                numberPage: [],
                numberPagin: 1,
                list: 25,
                show: true
            }
        }
    },
    methods:{
        getListReq(){
            this.progress.main = true;
            this.paginate.show = false;
            axios.post('/api/ad/equip-req/history',{
                page: 'user',
                search: this.searchTxt,
                paginate: this.paginate.list
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            })
        },
        getDataPaginate(page){

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'perv' ){ this.paginate.url = this.paginate.pervPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            this.progress.main = true;
            this.paginate.show = false;
            
            axios.post(this.paginate.url,{
                page: 'user',
                search: this.searchTxt,
                paginate: this.paginate.list
            })
            .then(response => {
                this.setDataAndPaginate(response.data);
            })
            
        },
        setDataAndPaginate(dataArr){
            this.paginate.url = dataArr.path+'?page=';
            this.paginate.path = dataArr.path;
            this.paginate.firstPageUrl = dataArr.first_page_url;
            this.paginate.lastPageUrl = dataArr.last_page_url;
            this.paginate.nextPageUrl = dataArr.next_page_url;
            this.paginate.pervPageUrl = dataArr.perv_page_url;
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

            this.equipreq = resultData;
            this.paginate.numberPage = resultPageNumber;
            this.progress.main = false;
            this.paginate.show = true;
        },
        getDataReqDetail(index){
            axios.get('/api/ad/equip-req/detail/'+this.equipreq[index].id)
            .then(response => {
                this.oneeqreq = response.data;
                $('#modal-req').modal('show');
            });
        },
        confirmVoidReq(index){

            let title = 'ยืนยันการยกเลิกใบเบิกอุปกรณ์สำนักงาน',
                text = 'ต้องการยกเลิก '+this.equipreq[index].no+' หรือไม่';
            
            this.$swal({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            })
            .then((result) => {
                if(result.isConfirmed){
                    axios.post('/api/ad/equip-req/update/status',{
                        id: this.equipreq[index].id,
                        status: 0,
                        user: this.$session.get('user')
                    })
                    .then(response => {
                        this.$swal({icon: 'success', text: 'ยกเลิกใบเบิกอุปกรณ์สำนักงานเรียบร้อยแล้ว'});
                        this.getDataPaginate(this.paginate.currentPage);
                    })
                    .catch(response => {
                        this.$swal({icon: 'error', text: 'ไม่สามารถยกเลิกใบเบิกอุปกรณ์สำนักงานได้'});
                    });
                }
            })

        }
    }
}
</script>