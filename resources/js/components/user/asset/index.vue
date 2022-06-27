<template>
    <div>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-tags"></i><span class="ml-2">การถือครองทรัพย์สิน</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-box"></i>
                                <span class="font-weight-bold ml-2">ทรัพย์สิน</span>
                            </div>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col">Fixed asset number</th>
                                        <th scope="col">ทรัพย์สิน</th>
                                        <th scope="col">ประเภท</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>                                    
                                    <tr v-for="( asst, index ) in asset" :key="index">
                                        <th scope="row" class="text-center">{{index+1}}</th>
                                        <td>{{asst.it_hw_number}}</td>
                                        <td>{{asst.it_hw_name}}</td>
                                        <td>{{asst.it_hw_type_name}}</td>
                                        <td>{{asst.it_locat_name}}</td>
                                        <td>{{(asst.it_asst_hw_status == 1 ? 'กำลังถือคลอง' : 'เคยถือครอง' )}}</td>
                                    </tr>
                                    <tr v-show="asset.length <= 0">
                                        <td class="text-center" colspan="6"><b>ไม่มีข้อมูล</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-id-badge"></i>
                                <span class="font-weight-bold ml-2">License</span>
                            </div>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col">หรัพย์สิน</th>
                                        <th scope="col">ชื่อทรัพย์สิน</th>
                                        <th scope="col">License</th>
                                        <th scope="col">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(lc,index) in license" :key="index">
                                        <th scope="row" class="text-center">{{index+1}}</th>
                                        <td>{{lc.hw_number}}</td>
                                        <td>{{lc.hw_name}}</td>
                                        <td>{{lc.swl_license}}</td>
                                        <td>{{lc.status}}</td>
                                    </tr>
                                    <tr v-show="license.length <= 0">
                                        <td class="text-center" colspan="6"><b>ไม่มีข้อมูล</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import VueSession from 'vue-session';
Vue.use(VueSession);

export default {
    mounted(){
        this.getAsset();
        this.getLicense();
    },
    data(){
        return{
            asset: [],
            license: []
        }
    },
    methods:{
        getAsset(){
             axios.get('/api/admin/it/employee/assethardware/'+this.$session.get('userId'))
            .then(response => {
                this.asset = response.data;
            });
        },
        getLicense(){
            axios.get('/api/admin/it/employee/software-license/'+this.$session.get('userId'))
            .then(response => {
                this.license = response.data;
            });
        }
    }
}
</script>