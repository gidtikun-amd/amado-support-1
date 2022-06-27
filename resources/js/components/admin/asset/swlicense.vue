<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-list-ul"></i><span class="ml-2">รายการข้อมูลทรัพย์สิน (Software)</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-inline">
                            <input class="form-control form-control-sm mr-sm-2" type="search" placeholder="กรอกคำค้นหา..." aria-label="Search"
                                v-model="inpt.searchKeyword"
                                v-on:keyup.enter="getSoftware()"
                                :disabled="disabled.inptSearch"
                            >
                            <button class="btn btn-sm btn-amado my-2 my-sm-0" type="button"
                                v-on:click="getSoftware()"
                                v-html="content.btnSearch"
                                :disabled="disabled.btnSearch"
                            >
                                <i class="fas fa-search"></i>
                                <span class="ml-2">ค้นหา</span>
                            </button>
                        </div>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">รหัสทรัพย์สิน</th>
                            <th scope="col">ประเภททรัพย์สิน</th>
                            <th scope="col">ชื่อทรัพย์สิน</th>
                            <th scope="col">สถานะ</th>
                            <th scope="col" class="text-center"><i class="fas fa-ellipsis-h"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(sw,index) in software" :key="index">
                            <th scope="row">{{sw.index}}</th>
                            <td>
                                <a class="text-decoration-none text-primary" 
                                :href="'/admin/asset/'+sw.it_hw_seq" :target="'_blank'">
                                    <i class="fas fa-search mr-1"></i><b>{{sw.it_hw_number}}</b>
                                </a>
                            </td>
                            <td>{{sw.it_hw_type_name}}</td>
                            <td>{{sw.it_hw_name}}</td>
                            <td>{{sw.it_hw_status}}</td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary btn-sm"
                                :href="'/admin/sofware-license/'+sw.it_hw_seq"
                                :target="'_blank'">
                                    <i class="fas fa-list-alt"></i>
                                    <span class="ml-2">รายละเอียด S/W License</span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
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


    </div>
</template>
<script>

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

export default {
    mounted(){
        this.getSoftware();
    },
    data(){
        return{
            inpt:{
                searchKeyword: ''
            },
            content: {
                btnSearch: '<i class="fas fa-search"></i><span class="ml-2">ค้นหา</span>'
            },
            disabled: {
                inptSearch: false,
                btnSearch: false,
            },
            software: {},
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
                show: false
            }
        }
    },
    methods:{
        getSoftware(){

            let btnContent = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>';
                btnContent += 'กำลังดึงข้อมูล....'

            this.content.btnSearch = btnContent;
            this.disabled.inptSearch = true;
            this.disabled.btnSearch = true;
            this.paginate.show = false;

            axios.post('/api/admin/it/softwarelicense/assetsw',{
                keyword: this.inpt.searchKeyword
            })
            .then(response => {
                if(response.data.length <= 0){
                    this.$swal({icon: 'error', text: 'ไม่พบ Software ที่ทำการค้นหา'});
                    this.software = {};
                    this.paginate.show = false;
                    this.content.btnSearch = '<i class="fas fa-search"></i><span class="ml-2">ค้นหา</span>';
                    this.disabled.inptSearch = false;
                    this.disabled.btnSearch = false;
                }
                else 
                    this.setDataAndPaginate(response.data);
            });

        },
        getDataPaginate(page){

            if( page == 'first' ){ this.paginate.url = this.paginate.firstPageUrl; }
            else if( page == 'prev' ){ this.paginate.url = this.paginate.prevPageUrl; }
            else if( page == 'next' ){ this.paginate.url = this.paginate.nextPageUrl; }
            else if( page == 'last' ){ this.paginate.url = this.paginate.lastPageUrl; }
            else{ this.paginate.url = this.paginate.path+'?page='+this.paginate.numberPagin }

            axios.post(this.paginate.url,{
                keyword: this.inpt.searchKeyword
            }).then(response => {this.setDataAndPaginate(response.data);});

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
            this.software = resultData;
            this.content.btnSearch = '<i class="fas fa-search"></i><span class="ml-2">ค้นหา</span>';
            this.disabled.inptSearch = false;
            this.disabled.btnSearch = false;
            this.paginate.show = true;

        }
    }
}
</script>