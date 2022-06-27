<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-search"></i><span class="ml-2">ค้นหารายงานการถือครอง License</span>
            </div>
            <div class="card-body">
                <form :action="'/api/report/it/license/export'" :method="'post'" :target="'_blank'">
                    <div class="row mb-3">
                        <div class="col-auto">
                            <label class="mt-2 ml-2">รหัสพนักงาน</label>
                        </div>
                        <div class="col-xl-3">
                            <input type="text" class="form-control" placeholder="ระบุรหัสพนักงาน..."
                                name="emp_id"
                                v-model="search.emp_id"
                            >
                        </div>
                        <div class="col-auto">
                            <label class="mt-2 ml-2">ฝ่าย</label>
                        </div>
                        <div class="col-xl-3">
                            <select class="custom-select"
                                name="dept_id"
                                v-model="search.dept_id"
                            >
                                <option value="" selected>ทั้งหมด</option>
                                <option v-for="dept in department" :key="dept.it_dept_id" :value="dept.it_dept_id">
                                    {{dept.it_dept_name}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-xl-auto">
                            <label class="mt-2 ml-2">Location</label>
                        </div>
                        <div class="col-xl-5">
                            <select class="custom-select"
                                name="locat_id"
                                v-model="search.locat_id"
                            >
                                <option value="" selected>ทั้งหมด</option>
                                <option v-for="locat in location" :key="locat.it_locat_id" :value="locat.it_locat_id">
                                    {{locat.it_locat_name}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <label class="mt-2 ml-2">รหัสทรัพย์สิน</label>
                        </div>
                        <div class="col-xl-3">
                            <input type="text" class="form-control" placeholder="ระบุรหัสทรัพย์สิน..."
                                name="hw_no"
                                v-model="search.hw_no"
                            >
                        </div>
                        <div class="col-xl-auto">
                            <label class="mt-2 ml-2">ชื่อทรัพย์สิน</label>
                        </div>
                        <div class="col-xl">
                            <input type="text" class="form-control" placeholder="ระบุชื่อทรัพย์สิน..."
                                v-model="search.hw_name"
                            >
                        </div>
                        <!-- <div class="col-xl-auto">
                            <label class="mt-2 ml-2">Serial Number</label>
                        </div>
                        <div class="col-xl">
                            <input type="text" class="form-control" placeholder="ระบุ Serial Number..."
                                name="hw_serial"
                                v-model="search.hw_serial"
                            >
                        </div> -->
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <label class="mt-2 ml-2">สถานะทรัพย์สิน</label>
                        </div>
                        <div class="col-xl">
                            <select class="custom-select"
                                name="hw_status"
                                v-model="search.hw_status"
                            >
                                <option value="" selected>ทั้งหมด</option>
                                <option value="1">ใช้งาน</option>
                                <option value="2">สำรอง</option>
                                <option value="3">ส่งซ่อม</option>
                                <option value="4">ออกจำหน่าย</option>
                                <option value="5">ยืมใช้งาน</option>
                                <option value="6">ไม่ใช้งาน</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="mt-2 ml-2">ประเภททรัพย์สิน</label>
                        </div>
                        <div class="col-xl">
                            <select class="custom-select"
                                name="type_id"
                                v-model="search.type_id"
                            >
                                <option value="" selected>ทั้งหมด</option>
                                <option v-for="types in assettype" :key="types.it_hw_type_id" :value="types.it_hw_type_id">
                                    {{types.it_hw_type_name}}
                                </option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="mt-2 ml-2">ประเภทถือครอง</label>
                        </div>
                        <div class="col-xl">
                            <select class="custom-select"
                                name="hw_group"
                                v-model="search.hw_group"
                            >
                                <option value="" selected>ทั้งหมด</option>
                                <option value="1">ทรัพย์สินบุคคลถือครอง</option>
                                <option value="2">ทรัพย์สินส่วนกลางฝ่าย</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-auto">
                            <label class="mt-2 ml-2">สถานะ License</label>
                        </div>
                        <div class="col-xl-3">
                            <select class="custom-select" name="lc_status">
                                <option value="999">ทั้งหมด</option>
                                <option value="1">ใช้งาน</option>
                                <option value="0">ไม่ใช้งาน</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="mt-2 ml-2">สถานะถือครอง</label>
                        </div>
                        <div class="col-xl-3">
                            <select class="custom-select" name="lc_handle">
                                <option value="999">ทั้งหมด</option>
                                <option value="1">กำลังถือครอง</option>
                                <option value="0">ไม่ได้ถือครอง</option>
                            </select>
                        </div>
                    </div>                    
                    <hr>
                    <div class="row justify-content-md-center mb-3">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success w-100"><i class="fas fa-file-excel mr-2"></i>Export Excel</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>
</template>
<script>
export default {
    mounted(){
        this.getDefaultData();
    },
    data(){
        return{
            department: [],
            location: [],
            assettype: [],
            search: {
                emp_id: '',
                locat_id: '',
                hw_no: '',
                hw_name: '',
                hw_serial: '',
                hw_status: '',
                hw_group: '',
                type_id: '',
                dept_id: ''
            }
        }
    },
    methods: {
        getDefaultData(){

            axios.get('/api/admin/it/department')
            .then(response => {
                this.department = response.data;
            });

            axios.get('/api/admin/it/location')
            .then(response => {
                this.location = response.data;
            });

            axios.get('/api/admin/it/assettype',{
                
            })
            .then(response => {
                this.assettype = response.data
            });

        }

    }
}
</script>