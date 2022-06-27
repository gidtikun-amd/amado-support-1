<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-clipboard mr-2"></i>รายงานสรุปการเบิกอุปกรณ์สำนักงาน
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form class="form-inline">
                            <label>แผนก</label>
                            <select class="form-control mr-3 ml-2" v-model="deptId">
                                <option value="" select>ทั้งหมด</option>
                                <option v-for="(dept,index) in departments" :key="index" :value="dept.it_dept_id">
                                    {{dept.it_dept_name}}
                                </option>
                            </select>
                            <label>ปี/เดือน</label>
                            <date-picker class="mr-2 ml-2" value-type="format" format="YYYY-MM-DD" range placeholder="เลือกช่วงวันที่" type="date"
                            v-model='DateDefault'
                            v-on:keyup.enter="setReport()"></date-picker>
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
                        <form class="form-inline" :action="'/api/ad/report/summary-eqreq/export'" :method="'post'" :target="'_blank'">
                            <input type="hidden" id="date_from" name="date_from" :value="DateDefault[0]">
                            <input type="hidden" id="date_to" name="date_to" :value="DateDefault[1]">
                            <button type="submit" class="btn btn-success mb-3">
                                <i class="fas fa-file-excel mr-2"></i>
                                Export Excel
                            </button>
                        </form>
                        <table class="table table-hover table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col" width="7%">#</th>
                                    <th class="text-center" scope="col">อุปกรณ์สำนักงาน</th>
                                    <th class="text-center" scope="col" width="10%">จำนวนเบิก</th>
                                    <th class="text-center" scope="col" width="10%">รับแล้ว</th>
                                    <th class="text-center" scope="col" width="10%">ยังไม่ได้รับ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list,index) in report" :key="index">
                                    <th class="text-center" scope="row">{{index+1}}</th>
                                    <td>{{list.code}} {{list.name}}</td>
                                    <td class="text-right">{{list.qty}} {{list.unit}}</td>
                                    <td class="text-right">{{list.sendto}} {{list.unit}}</td>
                                    <td class="text-right">{{list.outstand}} {{list.unit}}</td>
                                </tr>
                                <tr v-show="report.length <= 0"><td class="text-center" colspan="5"><b>ไม่มีข้อมูล</b></td></tr>
                            </tbody>
                        </table>
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
        this.DateDefault = [
            date.getFullYear()+'-'+('0'+(date.getMonth())).slice(-2)+'-'+('0'+(date.getDate())).slice(-2),
            date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+(date.getDate())).slice(-2)
        ];
        this.getDepartment();
        this.setReport();
    },
    data(){
        return{
            DateDefault: [],
            deptId: '',
            progress: {
                mainData: false
            },
            departments: [],
            report: []
        }
    },
    methods: {
        getDepartment(){
            axios.get('/api/admin/it/department')
            .then(response => { this.departments = response.data });
        },
        setReport(){

            this.progress.mainData = true;

            axios.post('/api/ad/report/summary-eqreq',{
                deptId: this.deptId,
                date_from: this.DateDefault[0],
                date_to: this.DateDefault[1]
            })
            .then(response => {
                this.report = response.data;
                this.progress.mainData = false;
            });

        }
    }
}
</script>
