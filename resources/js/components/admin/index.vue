<template>
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-md-3 d-md-none d-lg-block mb-3 h-50">
                <a href="/admin/ticket/list" class="text-decoration-none">
                    <div class="card text-white bg-primary o-hidden h-100">
                        <div class="card-body shadow-lg">
                            <div class="card-body-icon pr-2 pt-3"><i class="fas fa-ticket-alt"></i></div>
                            <div class="mr-5">
                                Ticket รอดำเนินการ
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="progress.ticketNotDone"></span>
                                <h2 class="mb-0" v-show="!progress.ticketNotDone">{{count.ticketNotDone | numFormat}}</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 d-md-none d-lg-block mb-3 h-50">
                <a href="/admin/jobrequest" class="text-decoration-none">
                    <div class="card text-white bg-success  o-hidden h-100">
                        <div class="card-body shadow-lg">
                            <div class="card-body-icon pr-4 pt-3"><i class="fas fa-clipboard-list"></i></div>
                            <div class="mr-3">
                                Job Request รอดำเนินการ
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="progress.jobreqNotDone"></span>
                                <h2 class="mb-0" v-show="!progress.jobreqNotDone">{{count.jobreqNotDone | numFormat}}</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 d-md-none d-lg-block mb-3 h-50">
                <a href="/admin/itemrequest" class="text-decoration-none">
                    <div class="card text-white bg-info  o-hidden h-100">
                        <div class="card-body shadow-lg">
                            <div class="card-body-icon pr-2 pt-3"><i class="fas fa-boxes"></i></div>
                            <div class="mr-3">
                                Item Request รอดำเนินการ
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="progress.itemreqNotDone"></span>
                                <h2 class="mb-0" v-show="!progress.itemreqNotDone">{{count.itemreqNotDone | numFormat}}</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 d-md-none d-lg-block mb-3 h-50">
                <a href="/admin/employee" class="text-decoration-none">
                    <div class="card text-white bg-danger  o-hidden h-100">
                        <div class="card-body shadow-lg">
                            <div class="card-body-icon pr-2 pt-3"><i class="fas fa-users"></i></div>
                            <div class="mr-5">
                                จำนวนพนักงานทั้งหมด
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="progress.numberEmployees"></span>
                                <h2 class="mb-0" v-show="!progress.numberEmployees">{{count.numberEmployees | numFormat}}</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 row-cols-sm-1 row-cols-xs-1 mt-4">

            <div class="col col-md-6 mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-chart-pie mr-2"></i> <b>สถานะทรัพย์สินทั้งหมด</b>
                    </div>
                    <div class="card-body text-center">
                        <div class="row" v-show="progress.statusAsset">
                            <div class='col-12 text-center text-primary'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <b>กำลังดึงข้อมูล</b>
                            </div>
                        </div>
                        <div class="row" v-show="!progress.statusAsset">
                            <div class='col-12'>
                                <apexchart
                                    v-if="!progress.statusAsset"
                                    type="pie" height="500"
                                    :options="graph.statusAsset.chartOptions"
                                    :series="graph.statusAsset.values"></apexchart>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" v-show="!progress.statusAsset">
                        <button class="btn btn-primary btn-sm float-right" v-on:click="getDataGraphAssetStatus()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col col-md-6 mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="far fa-list-alt mr-2"></i> <b>License software / Program</b>
                    </div>
                    <div class="card-body text-center" v-show="progress.license">
                        <div class="row">
                            <div class='col-12 text-center text-primary'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <b>กำลังดึงข้อมูล</b>
                            </div>
                        </div>
                    </div>
                    <div  v-show="!progress.license" v-bind:style="style.table">
                        <table class='table table-hover table-bordered table-striped table-sm' v-show="!progress.license">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">License software/program</th>
                                    <th scope="col" class="text-center">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                v-show="license.number > 0"
                                v-for="(sw,index) in license.detail" :key="index">
                                    <th scope="row" class="text-center">{{index+1}}</th>
                                    <td>
                                        <a class="text-decoration-none text-primary"
                                        :href="'/admin/sofware-license/'+sw.asset_id"
                                        :target="'_blank'">
                                            <i class="fas fa-search mr-2"></i>{{sw.asset_name}}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        {{sw.clicense}}
                                    </td>
                                </tr>
                                <tr v-show="license.number <= 0">
                                    <td class="text-center" colspan="3">ไม่มีข้อมูล software/program</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer" v-show="!progress.license">
                        <button class="btn btn-primary btn-sm float-right" v-on:click="getDataCountSoftwareLicense()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col col-md-6 mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="far fa-list-alt mr-2"></i> <b>ประเภททรัพย์สิน</b>
                    </div>
                    <div class="card-body text-center" v-show="progress.assetType">
                        <div class="row">
                            <div class='col-12 text-center text-primary'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <b>กำลังดึงข้อมูล</b>
                            </div>
                        </div>
                    </div>
                    <div v-show="!progress.assetType" v-bind:style="style.table">
                        <table class="table table-bordered table-hover table-striped table-sm">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">ประเภททรัพย์สิน</th>
                                    <th scope="col" class="text-center">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(ty,index) in summary.assetType" :key="index">
                                    <th scope="row" class="text-center">{{index+1}}</th>
                                    <td>{{ty.type_name}}</td>
                                    <td class="text-right">{{ty.casset}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer" v-show="!progress.assetType">
                        <button class="btn btn-primary btn-sm float-right" v-on:click="getDataGraphAssetType()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link text-amado" href="javascript:;"
                                v-on:click="changeSummaryTab(1)"
                                v-bind:class="{'active': summary.tab.activeOne}">
                                    <b>สรุปปัญหาที่เกิดขึ้น</b>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-amado" href="javascript:;"
                                v-on:click="changeSummaryTab(2)"
                                v-bind:class="{'active': summary.tab.activeTwo}">
                                    <b>สรุปทรัพย์สินที่เกิดขึ้น</b>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                เดือน <b>{{date.now.thaiFullMonth}}</b> ปี <b>{{date.now.year}}</b>
                            </div>
                        </div>
                        <div class="row mt-2" v-show="progress.assetAndIssue">
                            <div class='col-12 text-center text-primary'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <b>กำลังดึงข้อมูล</b>
                            </div>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered table-sm"
                    v-show="summary.table.tableOne">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">การแจ้งปัญหา</th>
                                <th scope="col" class="text-center">จำนวน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(issue,index) in summary.issue" :key="index">
                                <th scope="row" class="text-center">{{index+1}}</th>
                                <td>{{issue.subject_name}}</td>
                                <td class="text-right">{{issue.cticket}} รายการ</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-hover table-bordered table-sm"
                    v-show="summary.table.tableTwo">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">ทะเบียนทรัพย์สิน</th>
                                <th scope="col" class="text-center">จำนวน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(asset,index) in summary.asset" :key="index">
                                <th scope="row" class="text-center">{{index+1}}</th>
                                <td>{{asset.hw_status}}</td>
                                <td class="text-right">{{asset.c_status}} รายการ</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="card-footer" v-show="!progress.assetAndIssue">
                        <button class="btn btn-primary btn-sm float-right" v-on:click="getDataSummaryAssetIssue()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col col-md-6 mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-chart-line mr-2"></i> <b>ปัญหาที่เกิดขึ้นและทรัพย์สินที่เกิดขึ้นแต่ละเดือน ปี {{date.now.year}}</b>
                    </div>
                    <div class="card-body">
                        <div class="row" v-show="progress.growAssetAndTicket">
                            <div class='col-12 text-center text-primary'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <b>กำลังดึงข้อมูล</b>
                            </div>
                        </div>
                        <div class="row" v-show="!progress.growAssetAndTicket">
                            <div class="col-12 text-center">
                                <apexchart
                                type="line" height="350"
                                v-if="!progress.growAssetAndTicket"
                                :options="graph.growAssetAndTicket.chartOptions"
                                :series="graph.growAssetAndTicket.values"></apexchart>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col col-md-6 mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-chart-bar mr-2"></i> <b>สถานะ Ticket {{date.now.thaiFullMonth}}/{{date.now.year}}</b>
                    </div>
                    <div class="card-body text-center">
                        <div class="row" v-show="progress.statusTicket">
                            <div class='col-12 text-center text-primary'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <b>กำลังดึงข้อมูล</b>
                            </div>
                        </div>
                        <div class="row" v-show="!progress.statusTicket">
                            <div class="col-12 text-center">
                                <apexchart
                                    v-if="!progress.statusTicket"
                                    type="bar" height="350"
                                    :options="graph.statusTicket.chartOptions"
                                    :series="graph.statusTicket.values"></apexchart>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" v-show="!progress.statusTicket">
                        <button class="btn btn-primary btn-sm float-right" v-on:click="getDataGraphTicketStatus()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col col-md-6 mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-chart-bar mr-2"></i> <b>สถานะ Job Request {{date.now.thaiFullMonth}}/{{date.now.year}}</b>
                    </div>
                    <div class="card-body text-center">
                        <div class="row" v-show="progress.statusJobreq">
                            <div class='col-12 text-center text-primary'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <b>กำลังดึงข้อมูล</b>
                            </div>
                        </div>
                        <div class="row" v-show="!progress.statusJobreq">
                            <div class="col-12 text-center">
                                <apexchart
                                    v-if="!progress.statusJobreq"
                                    type="bar" height="350"
                                    :options="graph.statusJobreq.chartOptions"
                                    :series="graph.statusJobreq.values"></apexchart>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" v-show="!progress.statusJobreq">
                        <button class="btn btn-primary btn-sm float-right" v-on:click="getDataGraphTicketStatus()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col col-md-6 mb-4">
                <div class="card">
                    <div class="card-header text-amado">
                        <i class="fas fa-chart-bar mr-2"></i> <b>สถานะ Item Request {{date.now.thaiFullMonth}}/{{date.now.year}}</b>
                    </div>
                    <div class="card-body text-center">
                        <div class="row" v-show="progress.statusItmreq">
                            <div class='col-12 text-center text-primary'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <b>กำลังดึงข้อมูล</b>
                            </div>
                        </div>
                        <div class="row" v-show="!progress.statusItmreq">
                            <div class="col-12 text-center">
                                <apexchart
                                    v-if="!progress.statusItmreq"
                                    type="bar" height="350"
                                    :options="graph.statusItmreq.chartOptions"
                                    :series="graph.statusItmreq.values"></apexchart>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" v-show="!progress.statusItmreq">
                        <button class="btn btn-primary btn-sm float-right" v-on:click="getDataGraphTicketStatus()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
</template>
<script>

import VueSession from 'vue-session';
Vue.use(VueSession);

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import VueApexCharts from 'vue-apexcharts';
Vue.use(VueApexCharts);

export default {
    components: {
        apexchart: VueApexCharts
    },
    mounted(){
        this.countTicketNotDone();
        this.countJobreqNotDone();
        this.counItemreqNotDone();
        this.countNumberOfEmployees();
        this.getDataSummaryAssetIssue();
        this.getDataGraphAssetStatus();
        this.getDataGraphAssetType();
        this.getDataGrowAssetAndTicket();
        this.getDataGraphTicketStatus();
        this.getDataLicenseSoftware();
        this.getDataGraphJobreqStatus();
        this.getDataGraphItmreqStatus();
    },
    data(){
        return{
            style: {
                table: {
                    position: 'relative',
                    height: '370px',
                    overflow: 'auto',
                    display: 'block'
                }
            },
            date: {
                now: {
                    year: this.getNowDate('nowYearOnly'),
                    month: this.getNowDate('nowMonthOnly'),
                    thaiFullMonth: this.getNowDate('nowMonthFullThai')
                }
            },
            progress: {
                ticketNotDone: true,
                jobreqNotDone: true,
                itemreqNotDone: true,
                numberEmployees: true,
                statusAsset: true,
                statusTicket: false,
                statusJobreq: false,
                statusItmreq: false,
                license: true,
                assetAndIssue: true,
                assetType: false,
                growAssetAndTicket: true
            },
            count: {
                ticketNotDone: 0,
                jobreqNotDone: 0,
                itemreqNotDone: 0,
                numberEmployees: 0,
            },
            graph:{
                statusAsset: {
                    values: [],
                    chartOptions: {}
                },
                statusTicket: {
                    values: [],
                    chartOptions: {}
                },
                statusJobreq: {
                    values: [],
                    chartOptions: {}
                },
                statusItmreq: {
                    values: [],
                    chartOptions: {}
                },
                growAssetAndTicket: {
                    values: [],
                    chartOptions: {}
                }
            },
            license:{
                number: 0,
                detail: null
            },
            summary:{
                target: 1,
                tab:{
                    activeOne: true,
                    activeTwo: false
                },
                table:{
                    tableOne: true,
                    tableTwo: false
                },
                asset: [],
                assetType: [],
                issue: []
            }
        }
    },
    methods:{
        checkSession(){
            if(!this.$session.exists() || !this.$session.has('userId')){
                this.$swal({icon: 'error', title: 'Session login expaired!', text: 'กรุณา Login ใหม่'});
                return true;
            }
            else
                return false
        },
        getNowDate(target){

            let date = new Date();

            const month_thai = {
                full: [ "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" ],
                mini: [ "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค." ]
            };

            switch(target){
                case 'nowYearOnly': return date.getFullYear(); break;
                case 'nowMonthOnly': return ('0'+(date.getMonth())).slice(-2); break;
                case 'nowMonthFullThai': return month_thai.full[date.getMonth()]; break;
            }

        },
        dataFormatStatusAsset: function(a, b) {
            if(b) return b;
            return a;
        },
        countTicketNotDone(){
            this.progress.ticketNotDone = true;
            axios.get('/api/admin/it/dashboard/count/ticket-not-done')
            .then(response => {
                this.progress.ticketNotDone = false;
                this.count.ticketNotDone = response.data.count
            });
        },
        countJobreqNotDone(){
            this.progress.jobreqNotDone = true;
            axios.get('/api/admin/it/dashboard/count/jobreq-not-done')
            .then(response => {
                this.progress.jobreqNotDone = false;
                this.count.jobreqNotDone = response.data.count
            });
        },
        counItemreqNotDone(){
            this.progress.itemreqNotDone = true;
            axios.get('/api/admin/it/dashboard/count/itemreq-not-done')
            .then(response => {
                this.progress.itemreqNotDone = false;
                this.count.itemreqNotDone = response.data.count
            });
        },
        countNumberOfEmployees(){
            this.progress.numberEmployees = true;
            axios.get('/api/admin/it/dashboard/count/number-employee')
            .then(response => {
                this.progress.numberEmployees = false;
                this.count.numberEmployees = response.data.count
            });
        },
        getDataGraphAssetStatus(){
            this.progress.statusAsset = true;
            axios.get('/api/admin/it/dashboard/graph/asset-all-status')
            .then(response => {

                this.graph.statusAsset.values =  response.data.values;
                this.graph.statusAsset.chartOptions = {
                    chart: {
                        toolbar: {
                            show: false
                        }
                    },
                    labels: response.data.names,
                    colors: ['#2980B9', '#27AE60', '#EB984E','#EC7063', '#AF7AC5', '#AAB7B8'],
                    responsive:[{
                        options: {
                            chart: {},
                            legend: {position: 'bottom'}
                        }
                    }]
                };

                this.progress.statusAsset = false;
            });
        },
        getDataGraphTicketStatus(){
            this.progress.statusTicket = true;
            axios.get('/api/admin/it/dashboard/graph/ticket-all-status')
            .then(response => {

                let temp_values = [],
                    labels_data = response.data.names,
                    values_data = response.data.values;

                for( var i = 0; i < values_data.length; i++ )
                    temp_values.push({name: labels_data[i], data: values_data[i] });

                this.graph.statusTicket.values = temp_values;

                this.graph.statusTicket.chartOptions = {
                    chart: {
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: { enabled: true },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: { categories: response.data.names },
                    yaxis: { title: { text: 'จำนวน Ticket' } },
                    fill: { opacity: 1 },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                };

                this.progress.statusTicket = false;
            });
        },
        getDataGraphJobreqStatus(){
            this.progress.statusJobreq = true;
            axios.get('/api/admin/it/dashboard/graph/jobreq-all-status')
            .then(response => {

                let temp_values = [],
                    labels_data = response.data.names,
                    values_data = response.data.values;

                for( var i = 0; i < values_data.length; i++ )
                    temp_values.push({name: labels_data[i], data: values_data[i] });

                this.graph.statusJobreq.values = temp_values;

                this.graph.statusJobreq.chartOptions = {
                    chart: {
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: { enabled: true },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: { categories: response.data.names },
                    yaxis: { title: { text: 'จำนวน Job Request' } },
                    fill: { opacity: 1 },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                };

                this.progress.statusJobreq = false;
            });
        },
        getDataGraphItmreqStatus(){
            this.progress.statusItmreq = true;
            axios.get('/api/admin/it/dashboard/graph/itmreq-all-status')
            .then(response => {

                let temp_values = [],
                    labels_data = response.data.names,
                    values_data = response.data.values;

                for( var i = 0; i < values_data.length; i++ )
                    temp_values.push({name: labels_data[i], data: values_data[i] });

                this.graph.statusItmreq.values = temp_values;

                this.graph.statusItmreq.chartOptions = {
                    chart: {
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: { enabled: true },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: { categories: response.data.names },
                    yaxis: { title: { text: 'จำนวน Item Request' } },
                    fill: { opacity: 1 },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                };

                this.progress.statusItmreq = false;
            });
        },
        getDataLicenseSoftware(){
            this.progress.license = true;
            axios.get('/api/admin/it/dashboard/software-license')
            .then(response => {
                this.license.number = response.data.length;
                this.license.detail = response.data;
                this.progress.license = false;
            });
        },
        changeSummaryTab(target){

            this.summary.target = target;
            this.summary.tab.activeOne = target == 1 ? true : false;
            this.summary.tab.activeTwo = target == 2 ? true : false;
            this.summary.table.tableOne = target == 1 ? true : false;
            this.summary.table.tableTwo = target == 2 ? true : false;

        },
        getDataSummaryAssetIssue(){

            this.summary.table.tableOne = false;
            this.summary.table.tableTwo = false;
            this.progress.assetAndIssue = true;

            axios.get('/api/admin/it/dashboard/asset-issue')
            .then(response => {
                this.summary.issue = response.data.issue;
                this.summary.asset = response.data.asset;
                this.summary.table.tableOne = this.summary.target == 1 ? true : false;
                this.summary.table.tableTwo = this.summary.target == 2 ? true : false;
                this.progress.assetAndIssue = false;
            });

        },
        getDataGraphAssetType(){
            this.progress.assetType = true;
            axios.get('/api/admin/it/dashboard/asset-by-type')
            .then(response => {
                this.summary.assetType = response.data;
                this.progress.assetType = false;
            });
        },
        getDataGrowAssetAndTicket(){

            this.progress.growAssetAndTicket = true;

            axios.get('/api/admin/it/dashboard/graph/grow-asset-ticket')
            .then(response => {

                this.graph.growAssetAndTicket.values = response.data.series;
                this.graph.growAssetAndTicket.chartOptions = {
                    chart: {
                        height: 350,
                        type: 'line',
                        zoom: {enabled: true},
                        toolbar: {
                            show: true,
                            tools: {
                                download: false,
                                selection: false,
                                reset: false
                            }
                        }
                    },
                    dataLabels: {enabled: true},
                    stroke: {curve: 'straight'},
                    grid: {
                        row: {
                            colors: ['transparent'],
                            opacity: 0.5
                        }
                    },
                    xaxis: {categories: response.data.category}
                };

                this.progress.growAssetAndTicket = false;

            });

        }
    }
}
</script>
