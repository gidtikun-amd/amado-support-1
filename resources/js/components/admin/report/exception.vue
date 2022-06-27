<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-clipboard-list"></i><span class="ml-2">รายการ Log Error</span>
            </div>
            <div class="card-body" v-show="progress.show">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                            </div>
                        </div>
                        <strong class="text-primary">กำลังดึงข้อมูล</strong>
                    </div>
                </div>                
            </div>
            <table class="table table-hover table-bordered table-sm">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Module</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Date/Time</th>
                        <th class="text-center">Error</th>
                        <th class="text-center"><i class="fas fa-caret-down"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(list,index) in error" :key="index">
                        <td class="text-center align-middle">{{list.index}}</td>
                        <td class="align-middle">{{list.module}}</td>
                        <td class="align-middle">{{list.action}}</td>
                        <td class="align-middle">{{list.at}}</td>
                        <td class="align-middle">{{list.msgsub}}</td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm"
                            v-on:click="getExceptionDetail(index)">
                                <i class="fas fa-search"></i>
                            </button>
                        </td>
                    </tr>
                    <tr v-show="error.list <= 0">
                        <td class="text-center" colspan="6"><b>ไม่มีข้อมูล</b></td>
                    </tr>
                </tbody>
            </table>
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

        <div class="modal fade" id="modal-exdetail" data-backdrop="static" data-keyboard="false" 
        tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary">
                            <i class="fas fa-folder-open mr-2"></i><span>Exception Detail</span>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-2"><span class="font-weight-bold">Module</span></div>
                            <div class="col-10"><span>{{detail.module}}</span></div>
                        </div>
                        <div class="row">
                            <div class="col-2"><span class="font-weight-bold">Action</span></div>
                            <div class="col-10"><span>{{detail.action}}</span></div>
                        </div>
                        <div class="row">
                            <div class="col-2"><span class="font-weight-bold">Date/Time</span></div>
                            <div class="col-10"><span>{{detail.at}}</span></div>
                        </div>
                        <div class="row">
                            <div class="col-2"><span class="font-weight-bold">Message</span></div>
                            <div class="col-10"><span>{{detail.message}}</span></div>
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

    </div>
</template>
<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/th';
export default {
    mounted(){
        this.getErrorList();
    },
    data(){
        return{
            progress: {
                show: false
            },
            search: {
                today: '',
                module: '',
                action: ''
            },
            error: [],
            detail: {},
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
            }
        }
    },
    methods: {
        getErrorList(){
            this.progress.show = true;
            axios.get('/api/admin/exception/list')
            .then(response => {
                this.setDataAndPaginate(response.data);
            });
        },
        getDataPaginate(page){
            
            this.progress.show = true;
            this.paginate.show = false;

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.get(this.paginate.url)
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

                let msgsub = tempData[i].message.split('(SQL');

                resultData.push(tempData[i]);
                resultData[i].index = rowNumber++;
                resultData[i].msgsub = msgsub[0];

            }

            for(var i = this.paginate.firstPage; i <= this.paginate.lastPage; i++){
                resultPageNumber.push({number: i});
            }

            this.paginate.numberPage = resultPageNumber;
            this.error = resultData;
            
            this.paginate.show = true;
            this.progress.show = false;

        },
        getExceptionDetail(index){
            this.detail = this.error[index];
            $('#modal-exdetail').modal('show');
        }
    }
}
</script>