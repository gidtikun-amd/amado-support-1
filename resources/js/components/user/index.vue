<template>
    <div>

        <div class="row">
            <div class="col-md-3 d-md-none d-lg-block mb-3 h-50">
                <a href="/user/ticket/survey" class="text-decoration-none"><div class="card text-white bg-amado o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon pr-4 pt-3"><i class="fas fa-clipboard-list"></i></div>
                        <div class="mr-5">Ticket ยังไม่ได้ประเมิน <h1 class="mb-0" v-html="count_survey_didnothing">0</h1></div>
                    </div>
                </div></a>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-chart-pie mr-2"></i> <b>ภาพรวมรายการร้องขอ ปี {{year}}</b>
                    </div>
                    <div class="card-body text-center">
                        <apexchart type="donut"
                            v-if="graph.allCount.PieStatus"
                            :options="graph.allCount.PieChartOptions"
                            :series="graph.allCount.PieSeries">
                        </apexchart>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-chart-pie mr-2"></i> <b>ภาพรวมรายการร้องขอรายเดือน ปี {{year}}</b>
                    </div>
                    <div class="card-body text-center">
                        <apexchart type="bar" height="305"
                            v-if="graph.allCount.BarStatus"
                            :options="graph.allCount.BarChartOptions"
                            :series="graph.allCount.BarSeries">
                        </apexchart>
                    </div>
                </div>
            </div>

        </div>

        <div class="row row-cols-1 row-cols-md-2">

            <div class="col mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-ticket-alt"></i><span class="ml-2"><b>Ticket</b></span>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-danger active m-2">
                            รอดำเนินการ <span class="badge badge-pill badge-light"><b>{{ticket.count_wait}}</b></span>
                        </button>
                        <button type="button" class="btn btn-warning active m-2">
                            กำลังดำเนินการ <span class="badge badge-pill badge-light"><b>{{ticket.count_inprogress}}</b></span>
                        </button>
                        <button type="button" class="btn btn-success active m-2">
                            เสร็จสิ้นแล้ว <span class="badge badge-pill badge-light"><b>{{ticket.count_success}}</b></span>
                        </button>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Ticket</th>
                                <th scope="col" class="text-center">การแจ้งปัญหา</th>
                                <th scope="col" class="text-center">วันที่เอกสาร</th>
                                <th scope="col" class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in ticket.ticket_list" :key="index">
                                <th scope="row">
                                    <a class="text-decoration-none" :href="'/user/history_jobrequest'">{{list.ticket_no}}</a>
                                </th>
                                <td>{{list.subject_name}}</td>
                                <td>{{list.ticket_date}}</td>
                                <td>
                                    <span class="spinner-grow spinner-grow-mini text-danger"
                                        v-show="list.ticket_sts == 1"
                                    ></span>
                                    <span class="ml-1" v-show="list.ticket_sts == 1">รออนุมัติ</span>

                                    <span class="spinner-grow spinner-grow-mini text-danger"
                                        v-show="list.ticket_sts == 2"
                                    ></span>
                                    <span class="ml-1" v-show="list.ticket_sts == 2">รอดำเนินการ</span>

                                    <div class="spinner-border spinner-border-sm text-warning"
                                        v-show="list.ticket_sts == 3"
                                    ></div>
                                    <span class="ml-1" v-show="list.ticket_sts == 3">กำลังดำเนินการ</span>

                                    <i class="fas fa-check text-success" v-show="list.ticket_sts == 4"></i>
                                    <span class="ml-2" v-show="list.ticket_sts == 4">ดำเนินการเรียบร้อย</span>
                                </td>
                            </tr>
                            <tr v-if="ticket.ticket_list.length <= 0" ><td scope="row" colspan="4" class="text-center"><b>ไม่มีรายการ</b></td></tr>
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <a :href="'/user/ticket/history'" class="btn btn-sm btn-success float-right" title="ทั้งหมด"><i class="fas fa-list-alt"></i></a>
                        <button type="button" class="btn btn-sm btn-primary float-right mr-1" title="ดึงข้อมูลใหม่"
                            v-on:click="getDefaultTicket()"><i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </div>

            <div class="col mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-clipboard-list"></i><span class="ml-2"><b>Job Request</b></span>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-danger active m-2">
                            รอดำเนินการ <span class="badge badge-pill badge-light"><b>{{jobreq.count_wait}}</b></span>
                        </button>
                        <button type="button" class="btn btn-warning active m-2">
                            กำลังดำเนินการ <span class="badge badge-pill badge-light"><b>{{jobreq.count_inprogress}}</b></span>
                        </button>
                        <button type="button" class="btn btn-success active m-2">
                            เสร็จสิ้นแล้ว <span class="badge badge-pill badge-light"><b>{{jobreq.count_success}}</b></span>
                        </button>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Job Request</th>
                                <th scope="col" class="text-center">วัตถุประสงค์</th>
                                <th scope="col" class="text-center">วันที่เอกสาร</th>
                                <th scope="col" class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in jobreq.jobreq_list" :key="index">
                                <th scope="row">
                                    <a class="text-decoration-none" :href="'/user/history_jobrequest'">{{list.jobreq_no}}</a>
                                </th>
                                <td>{{list.jobreq_obj_str}} <font v-if="list.jobreq_obj == 7">{{'('+list.jobreq_other+')'}}</font></td>
                                <td>{{list.jobreq_date}}</td>
                                <td>
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"
                                        v-show="list.jobreq_status == 1"
                                    ></span>
                                    <span class="ml-1" v-show="list.jobreq_status == 1">รอดำเนินการ</span>

                                    <div class="spinner-border spinner-border-sm text-warning"
                                        v-show="list.jobreq_status == 2"
                                    ></div>
                                    <span class="ml-1" v-show="list.jobreq_status == 2">กำลังดำเนินการ</span>

                                    <i class="fas fa-check text-success" v-show="list.jobreq_status == 3"></i>
                                    <span class="ml-2" v-show="list.jobreq_status == 3">ดำเนินการเรียบร้อย</span>
                                </td>
                            </tr>
                            <tr v-if="jobreq.jobreq_list <= 0" ><td scope="row" colspan="4" class="text-center"><b>ไม่มีรายการ</b></td></tr>
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <a :href="'/user/history_jobrequest'" class="btn btn-sm btn-success float-right" title="ทั้งหมด"><i class="fas fa-list-alt"></i></a>
                        <button type="button" class="btn btn-sm btn-primary float-right mr-1" title="ดึงข้อมูลใหม่"
                            v-on:click="getDefaultJobreq()"><i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </div>

            <div class="col mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-boxes"></i><span class="ml-2"><b>Item Request</b></span>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-danger active m-2">
                            รอดำเนินการ <span class="badge badge-pill badge-light"><b>{{itemreq.count_wait}}</b></span>
                        </button>
                        <button type="button" class="btn btn-warning active m-2">
                            กำลังดำเนินการ <span class="badge badge-pill badge-light"><b>{{itemreq.count_inprogress}}</b></span>
                        </button>
                        <button type="button" class="btn btn-success active m-2">
                            เสร็จสิ้นแล้ว <span class="badge badge-pill badge-light"><b>{{itemreq.count_success}}</b></span>
                        </button>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Item Request</th>
                                <th scope="col" class="text-center">วัตถุประสงค์</th>
                                <th scope="col" class="text-center">วันที่เอกสาร</th>
                                <th scope="col" class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in itemreq.itemreq_list" :key="index">
                                <th scope="row">
                                    <a class="text-decoration-none" :href="'/user/history_jobrequest'">{{list.itmreq_no}}</a>
                                </th>
                                <td>{{list.itmreq_obj}}</td>
                                <td>{{list.itmreq_date}}</td>
                                <td>
                                    <span class="spinner-grow spinner-grow-mini text-danger" role="status" aria-hidden="true"
                                        v-show="list.itmreq_status == 1"
                                    ></span>
                                    <span class="ml-1" v-show="list.itmreq_status == 1">รอดำเนินการ</span>

                                    <div class="spinner-border spinner-border-sm text-warning"
                                        v-show="list.itmreq_status == 2"
                                    ></div>
                                    <span class="ml-1" v-show="list.itmreq_status == 2">กำลังดำเนินการ</span>

                                    <i class="fas fa-check text-success" v-show="list.itmreq_status == 3"></i>
                                    <span class="ml-2" v-show="list.itmreq_status == 3">ดำเนินการเรียบร้อย</span>
                                </td>
                            </tr>
                            <tr v-if="itemreq.itemreq_list <= 0" ><td scope="row" colspan="4" class="text-center"><b>ไม่มีรายการ</b></td></tr>
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <a :href="'/user/history_itemrequest'" class="btn btn-sm btn-success float-right" title="ทั้งหมด"><i class="fas fa-list-alt"></i></a>
                        <button type="button" class="btn btn-sm btn-primary float-right mr-1" title="ดึงข้อมูลใหม่"
                            v-on:click="getDefaultItemreq()"><i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>
<script>

import VueApexCharts from 'vue-apexcharts';
Vue.use(VueApexCharts);

export default {
    components: {
        apexchart: VueApexCharts
    },
    mounted(){
        const intDate = new Date();
        this.year = intDate.getFullYear();
        this.getCountSurveyDidNotThine();
        this.getPieAllData();
        this.getBarAllDataPerMonth();
        this.getDefaultJobreq();
        this.getDefaultItemreq();
        this.getDefaultTicket();
    },
    data(){
        return{
            year: '',
            count_survey_didnothing: 0,
            jobreq: {
                panel_progress: true,
                panel_data: false,
                count_wait: '0',
                count_inprogress: '0',
                count_success: '0',
                jobreq_list: []
            },
            itemreq: {
                panel_progress: true,
                panel_data: false,
                count_wait: '0',
                count_inprogress: '0',
                count_success: '0',
                itemreq_list: []
            },
            ticket: {
                panel_progress: true,
                panel_data: false,
                count_wait: '0',
                count_inprogress: '0',
                count_success: '0',
                ticket_list: []
            },
            graph: {
                allCount: {
                    PieStatus: false,
                    PieSeries: [],
                    PieChartOptions: {},
                    BarStatus: false,
                    BarSeries: [],
                    BarChartOptions: {}
                }
            }
        }
    },
    methods:{
        getBarAllDataPerMonth(){

            this.graph.allCount.BarStatus = false;

            axios.get('/api/user/dashboard/charts/bar-all-per-month')
            .then(response => {

                this.graph.allCount.BarSeries = response.data.serise;
                this.graph.allCount.BarChartOptions = {
                    chart: {
                        type: 'bar',
                        toolbar: {
                            show: true,
                            tools: { download: false, reset: false }
                        }
                    },
                    plotOptions: {
                        bar: {horizontal: false, columnWidth: '90%', endingShape: 'rounded'},
                    },
                    dataLabels: {enabled: true},
                    stroke: {show: true, width: 2, colors: ['transparent']},
                    xaxis: {categories: response.data.category},
                    yaxis: {title: {text: 'จำนวน/เดือน'}},
                    fill: {opacity: 1},
                    tooltip: {y: {formatter: function(val){return val}}},
                }

                this.graph.allCount.BarStatus = true;

            });

        },
        getPieAllData(){

            this.graph.allCount.PieStatus = false;

            axios.get('/api/user/dashboard/charts/pie-all')
            .then(response => {

                this.graph.allCount.PieSeries = response.data.series;
                this.graph.allCount.PieChartOptions = {
                    chart: {type: 'donut'},
                    labels: response.data.labels,
                    responsive: [{
                        options: {
                            chart: {},
                            legend: {position: 'bottom'}
                        }
                    }]
                }

                this.graph.allCount.PieStatus = true;

            });

        },
        getCountSurveyDidNotThine(){
            axios.get('/api/user/ticket/survey/didnothing')
            .then(response => {
                this.count_survey_didnothing = response.data.count;
            });
        },
        getDefaultJobreq(){

            this.jobreq.panel_progress = true;
            this.jobreq.panel_data = false;

            axios.get('/api/user/dashboard/list-jobreq')
            .then(response => {
                this.jobreq.panel_progress = false;
                this.jobreq.panel_data = true;
                this.jobreq.count_wait = response.data.count_wait;
                this.jobreq.count_inprogress = response.data.count_inprogress;
                this.jobreq.count_success = response.data.count_success;
                this.jobreq.jobreq_list = response.data.data;
            });

        },
        getDefaultItemreq(){

            this.itemreq.panel_progress = true;
            this.itemreq.panel_data = false;

            axios.get('/api/user/dashboard/list-itemreq')
            .then(response => {
                this.itemreq.panel_progress = false;
                this.itemreq.panel_data = true;
                this.itemreq.count_wait = response.data.count_wait;
                this.itemreq.count_inprogress = response.data.count_inprogress;
                this.itemreq.count_success = response.data.count_success;
                this.itemreq.itemreq_list = response.data.data;
            });

        },
        getDefaultTicket(){

            this.ticket.panel_progress = true;
            this.ticket.panel_data = false;

            axios.get('/api/user/dashboard/list-ticket')
            .then(response => {
                this.ticket.panel_progress = false;
                this.ticket.panel_data = true;
                this.ticket.count_wait = response.data.count_wait;
                this.ticket.count_inprogress = response.data.count_inprogress;
                this.ticket.count_success = response.data.count_success;
                this.ticket.ticket_list = response.data.data;
            });

        }
    }
}
</script>
