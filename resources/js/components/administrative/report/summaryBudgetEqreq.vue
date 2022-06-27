<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-clipboard mr-2"></i>รายงานสรุป Budget การเบิกอุปกรณ์สำนักงาน
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form class="form-inline">
                            <label>ปี/เดือน</label>
                            <date-picker class="mr-2 ml-2" value-type="format" format="YYYY-MM" placeholder="เลือกปี/เดือน" type="month"
                            v-model='DateDefault'
                            v-on:keyup="setReport()"></date-picker>
                            <button type="button" class="btn btn-amado my-1"
                            v-on:click="setReport()"><i class="fas fa-search mr-2"></i>ค้นหา</button>
                        </form>
                    </div>
                </div>
                <div class="row" v-show="progress.mainData">
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="row" v-show="!progress.mainData">
                    <div class="col-12">
                        <hr>
                        <form class="form-inline" :action="'/api/ad/report/summary-budget-eqreq/export'" :method="'post'" :target="'_blank'">
                            <input type="hidden" id="date" name="date" :value="DateDefault">
                            <button type="submit" class="btn btn-success mb-3">
                                <i class="fas fa-file-excel mr-2"></i>
                                Export Excel
                            </button>
                        </form>
                        <table class="table table-hover table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col" width="7%">#</th>
                                    <th class="text-center" scope="col">แผนก</th>
                                    <th class="text-center" scope="col" width="15%">ใช้ Budget</th>
                                    <th class="text-center" scope="col" width="7%"><i class="fas fa-ellipsis-h"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list,index) in report" :key="index">
                                    <th class="text-center align-middle" scope="row">{{index+1}}</th>
                                    <td class="align-middle">{{list.name}}</td>
                                    <td class="text-right align-middle">{{list.budget | numFormat('0.00')}}</td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-info btn-sm"
                                        v-on:click="getDetail(index)">
                                            <i class="fas fa-clipboard-list"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="modal-budget-dept" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-clipboard-list text-info mr-2"></i>รายการเบิก
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="font-weight-bold mr-2">แผนก :</span>
                        <span class="font-weight-bold text-primary mr-3">{{detail.deptName}}</span>
                        <span class="font-weight-bold mr-2">Budget รวม :</span>
                        <span class="font-weight-bold text-danger mr-3">{{detail.budget | numFormat('0.00')}}</span>
                    </div>
                    <table class="table table-hover table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col" width="7%">#</th>
                                <th class="text-center" scope="col">อุปกรณ์สำนักงาน</th>
                                <th class="text-center" scope="col">จำนวนเบิก</th>
                                <th class="text-center" scope="col">Budget</th>
                                <th class="text-center" scope="col">Budget รวม</th>
                                <th class="text-center" scope="col">รับแล้ว</th>
                                <th class="text-center" scope="col">ยังไม่ได้รับ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(dt,index) in detail.data" :key="index">
                                <th class="text-center" scope="row">{{index+1}}</th>
                                <td>{{dt.item}}</td>
                                <td class="text-right">{{dt.qty}}</td>
                                <td class="text-right">{{dt.budget}}</td>
                                <td class="text-right">{{dt.ttbudget}}</td>
                                <td class="text-right">{{dt.sendto}}</td>
                                <td class="text-right">{{dt.outstand}}</td>
                            </tr>
                            <tr v-show="detail.data.length <= 0"><td class="text-center font-weight-bold" colspan="7">ไม่มีข้อมูล</td></tr>
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>

import numeral from 'numeral';
import numFormat from 'vue-filter-number-format';

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/th';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

export default {
    mounted(){
        let date = new Date();
        this.DateDefault = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2);
        this.setReport();
    },
    data(){
        return{
            DateDefault: '',
            deptId: '',
            progress: {
                mainData: false
            },
            report: [],
            detail: {
                deptName: '',
                budget: '',
                data: []
            }
        }
    },
    methods: {
        setReport(){

            this.progress.mainData = true;

            axios.post('/api/ad/report/summary-budget-eqreq',{date: this.DateDefault})
            .then(response => {
                this.report = response.data;
                this.progress.mainData = false;
            });

        },
        getDetail(index){
            axios.post('/api/ad/report/summary-budget-eqreq-dept',{
                deptId: this.report[index].id,
                date: this.DateDefault
            })
            .then(response => {
                this.detail.data = response.data;
                this.detail.deptName = this.report[index].name;
                this.detail.budget = this.report[index].budget;
                $('#modal-budget-dept').modal('show');
            });
        }
    }
}
</script>
