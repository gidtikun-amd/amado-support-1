<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-search"></i><span class="ml-2">รายงานเอกสารยังไม่ได้อัพโหลด</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">   
                    <div class="col-12">
                        <form class="form-inline" 
                        :action="'/api/report/it/nonup-paper/export'" 
                        :method="'post'" :target="'_blank'">
                            <div class="form-group">
                                <label class="control-label">ประเภทเอกสาร</label>
                                <select class="form-control form-control-sm mr-2 ml-2" 
                                name="TypePaper" v-model="TypePaper">
                                    <option value="1">Job Request</option>
                                    <option value="2">Item Request</option>
                                    <option value="3">Memo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">สถานะ</label>
                                <select class="form-control form-control-sm mr-2 ml-2" 
                                name="StatusPaper" v-model="StatusPaper">
                                    <option value="99">ทั้งหมด</option>
                                    <option value="2">กำลังดำเนินการ</option>
                                    <option value="3" selected>ดำเนินการเรียบร้อยแล้ว</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">ช่วงวันที่</label>
                                <div class="input-group input-group-sm mr-2 ml-2">
                                    <input type="date" class="form-control"
                                    name="DateFrom" v-model='DateFrom'>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">ถึง</span>
                                    </div>
                                    <input type="date" class="form-control"
                                    name="DateTo" v-model='DateTo'>
                                </div>     
                            </div>    
                            <button type="submit" class="btn btn-sm btn-primary my-1">
                                <i class="fas fa-file-excel mr-2"></i>Export Excel
                            </button>               
                        </form>
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
    },
    data(){
        return{
            TypePaper: 1,
            StatusPaper: 3,
            DateFrom: '',
            DateTo: ''
        }
    },
    methods: {
        getReport(){
        }
    }
}
</script>