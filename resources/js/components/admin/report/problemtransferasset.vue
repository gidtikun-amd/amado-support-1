<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-search"></i><span class="ml-2">รายงานสรุปทรัพย์สินที่เกิดขึ้น</span>
            </div>
            <div class="card-body">
                <form class="form-inline">
                    <label class="control-label">ปี/เดือน</label>
                    <date-picker class="mr-2 ml-2" value-type="format" format="YYYY-MM" placeholder="เลือกปี/เดือน" type="month"
                    v-model='DateDefault'
                    v-on:keyup="setReport()"></date-picker>
                    <button type="button" class="btn btn-success my-1 mr-1"
                    v-on:click="setReport()"><i class="fas fa-search"></i> Search</button>              
                    <button type="button" class="btn btn-primary"
                    v-on:click="exportWord()">
                        <i class="far fa-file-word mr-1"></i>
                        <span>Export Word</span>
                    </button>                             
                </form>
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

export default {
    mounted(){
        let date = new Date();
        this.DateDefault = date.getFullYear()+'-'+('0'+(date.getMonth()+1)).slice(-2);
    },
    data(){
        return{
            DateDefault: '',
        }
    },
    methods: {
        setReport(){

            if(this.DateDefault == ''){
                this.$swal({icon: 'error', text: 'กรุณาเลือก ปี/เดือน'});
                return;
            }

            let link = '/pdf/report/transfer-asset/'+this.DateDefault;
            window.open(link,'_blank');

        },
        exportWord(){
            if(this.DateDefault == ''){
                this.$swal({icon: 'error', text: 'กรุณาเลือก ปี/เดือน'});
                return;
            }

            let link = '/word/report/asset-handle?month='+this.DateDefault;
            window.open(link,'_blank');
        }
    }
}
</script>