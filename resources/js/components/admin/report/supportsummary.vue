<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-search"></i><span class="ml-2">รายงานสรุปผลการ Support</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">   
                    <div class="col-12">
                        <form class="form-inline">
                            <label class="control-label">ช่วงวันที่</label>
                            <date-picker class="mr-2 ml-2" value-type="format" format="YYYY-MM-DD" range placeholder="เลือกช่วงวันที่" type="date"
                            v-model='DateDefault'
                            v-on:keyup="setReport()"></date-picker>
                            <button type="button" class="btn btn-primary my-1"
                            v-on:click="setReport()"><i class="fas fa-search"></i> Search</button>                        
                        </form>
                    </div>
                </div>
                <div class="row" v-show="panel.progress">
                    <div class="col-12 text-center text-primary">
                        <div class="progress mb-1">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span><b>กำลังดึงข้อมูล</b>
                    </div>
                </div>
                <div class="row" v-show="panel.detail">
                    <div class="col-12">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center align-middle">#</th>
                                    <th class="text-center align-middle">ชื่อ-นามสกุล</th>
                                    <th class="text-center align-middle">จำนวน Ticket<br>(ยังไม่ได้ปิด)</th>
                                    <th class="text-center align-middle">จำนวน Ticket<br>(ปิดแล้ว)</th>
                                    <th class="text-center align-middle">จำนวน Ticket<br>(ประเมินแล้ว)</th>
                                    <th class="text-center align-middle">คะแนนประเมิน</th>                                    
                                    <th class="text-center align-middle">คะแนนโดยรวม</th>
                                    <th class="text-center align-middle">คะแนนรวม</th>
                                    <th class="text-center align-middle">ค่าเฉลี่ย<br>(คะแนนรวม/ประเมินแล้ว)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(list,index) in report" :key="index">
                                    <th scope="row" class="text-center">{{index+1}}</th>
                                    <td>{{list.emp_name}}</td>
                                    <td class="text-right">{{list.unsuccess | numFormat}}</td>
                                    <td class="text-right">{{list.success | numFormat}}</td>
                                    <td class="text-right">{{list.cticket | numFormat}}</td>
                                    <td class="text-right">{{list.sscore | numFormat}}</td>
                                    <td class="text-right">{{list.sova | numFormat}}</td>
                                    <td class="text-right">{{(parseInt(list.sova)+parseInt(list.sscore)) | numFormat}}</td>
                                    <th class="text-right">{{((parseFloat(list.sova)+parseFloat(list.sscore))/parseFloat(list.cticket)) | numFormat('0.00')}}</th>
                                </tr>
                                <tr v-show="report.length <= 0">
                                    <td colspan="9" class="text-center">ไม่มีข้อมูล</td>
                                </tr>
                            </tbody>
                        </table>
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

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import numeral from 'numeral';
import numFormat from 'vue-filter-number-format';
 
Vue.filter('numFormat', numFormat(numeral));

export default {
    mounted(){
        let date = new Date();
        this.DateDefault = [
            date.getFullYear()+'-'+('0'+(date.getMonth())).slice(-2)+'-'+('0'+(date.getDate())).slice(-2),
            date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2)+'-'+('0'+(date.getDate())).slice(-2)
        ];
    },
    data(){
        return{
            panel: {
                progress: false,
                detail: false
            },
            DateDefault: [],
            report: []
        }
    },
    methods: {
        setReport(){

            if(this.DateDefault[0] === null || this.DateDefault[1] === null){
                this.$swal({icon: 'error', text: 'กรุณาเลือกช่วงวันที่'});
                return;
            }
            
            this.panel.progress = true;
            this.panel.detail = false;

            axios.post('/api/admin/it/report/summary-support',{
                sdate: this.DateDefault[0],
                edate: this.DateDefault[1]
            })
            .then(response => { 
                this.panel.progress = false;
                this.panel.detail = true;
                this.report = response.data; 
            });

        }
    }
}
</script>