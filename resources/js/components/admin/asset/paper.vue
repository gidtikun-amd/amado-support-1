<template>
    <div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-search"></i><span class="ml-2">ค้นหาเอกสารทรัพย์สิน</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-inline">
                            <input class="form-control form-control-sm mr-sm-2" size="40" type="search" 
                            placeholder="กรอกเลขที่เอกสารหรือเลขพรัพย์สิน..." aria-label="Search"
                                v-model="search.text"
                                :readonly="search.textReadonly"
                                v-on:keyup.enter="searchPaper()"
                            >
                            <select class="form-control form-control-sm mr-sm-2"
                                v-model="search.type"
                                :disabled="search.typeDisabled"
                                v-on:keyup.enter="searchPaper()"
                            >
                                <option value="" selected>ประเภทเอกสารทั้งหมด</option>
                                <option value="1">ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน</option>
                                <option value="2">ใบยืม - คืนอุปกรณ์คอมพิวเตอร</option>
                            </select>
                            <button class="btn btn-sm btn-primary my-2 my-sm-0" type="button"
                                v-html="search.btnSearchContent"
                                :disabled="search.btnSearchDisabled"
                                v-on:click="searchPaper()"
                            >
                                <i class="fas fa-search"></i>
                                <span class="ml-2">ค้นหา</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" v-show="page.panelData">
                    <div class="col-12">
                        <table class="table table-hover table-sm">
                            <thead>
                                <th class="text-center">#</th>
                                <th>เลขที่เอกสาร</th>
                                <th>ประเภทเอกสาร</th>
                                <th>รหัสทรัพย์สิน</th>
                                <th>ทรัพย์สิน</th>
                                <th>ผู้ถือครอง</th>                                
                                <th>สถานะ</th>
                                <th>กลุ่มทรัพย์สิน</th>                                 
                                <th class="text-center"><i class="fas fa-sort-down"></i></th> 
                            </thead>
                            <tbody>
                                <tr v-for="(asst,index) in list" :key="index">
                                    <th class="text-center">{{index+1}}</th>
                                    <td>
                                        <a class="text-decoration-none" 
                                            :href="asst.paper_ty == 1 ? '../../pdf/asset/handle/'+asst.handle_id : '../../pdf/asset/borrow/'+asst.handle_id"
                                            :target="'_blank'"
                                        >
                                            <strong>{{asst.paper_no}}</strong> <i class="fas fa-search"></i>
                                        </a>
                                    </td>
                                    <td>{{asst.paper_type}}</td>
                                    <td>
                                        <a class="text-decoration-none" 
                                            :href="'../asset/'+asst.asset_id"
                                            :target="'_blank'"
                                        >
                                            <strong>{{asst.asset_no}}</strong> <i class="fas fa-search"></i>
                                        </a>
                                    </td>
                                    <td>{{asst.asset_name}}</td>
                                    <td>{{asst.emp_name}}</td>
                                    <td>
                                        <strong class=""
                                            v-bind:class="asst.asset_sts == 1 ? 'text-success' : 'text-danger'"
                                        >{{asst.status_str}}</strong>                                        
                                    </td>
                                    <td>{{asst.group_str}}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            v-on:click="getHandlePaper(asst.handle_id,asst.emp_id)"
                                            title="จัดการเอกสาร"
                                        >
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal : #modal-holdasset-file -->
        <div class="modal fade" id="modal-holdasset-file"  data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <i class="fas fa-file-pdf text-primary"></i> <span class="ml-2">เอกสารประวัติผู้ถือครองทรัพย์สิน</span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="row m-3 pb-3">
                            <div class="col-12">
                                <ul class="list-group">                                    
                                    <li class="list-group-item py-1">
                                        ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน : 
                                        <a class="text-decoration-none" 
                                            :href="'../../pdf/asset/handle/'+upload.handle_id" 
                                            :target="'_blank'"
                                            v-show="upload.showPaperHandleNoPrint"
                                        > 
                                            <i class="fas fa-print mr-2 ml-2"></i> 
                                            <strong class="text-primary" v-html="upload.paperHandleNo"></strong>
                                        </a>
                                        <a class="text-decoration-none" href="javascript:;" 
                                            v-show="upload.showPaperHandleNoCreate"
                                            v-on:click="createHandleFile()"
                                        > 
                                            <i class="fas fa-file-medical mr-2 ml-2"></i> 
                                            <strong class="text-primary">สร้างเอกสาร</strong>
                                        </a>
                                    </li>                                   
                                    <li class="list-group-item py-1">
                                        ใบยืม - คืนอุปกรณ์คอมพิวเตอร : 
                                        <a class="text-decoration-none" 
                                            :href="'../../pdf/asset/borrow/'+upload.handle_id" 
                                            :target="'_blank'"
                                            v-show="upload.showPaperBorrowNoPrint"
                                        > 
                                            <i class="fas fa-print mr-2 ml-2"></i> 
                                            <strong class="text-primary" v-html="upload.paperBorrowNo"></strong>
                                        </a>
                                        <a class="text-decoration-none" href="javascript:;" 
                                            v-show="upload.showPaperBorrowNoCreate"
                                            v-on:click="createBorrowFile()"
                                        > 
                                            <i class="fas fa-file-medical mr-2 ml-2"></i> 
                                            <strong class="text-primary">สร้างเอกสาร</strong>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row m-3 pb-3">
                            <div class="col-12">
                                <form enctype="multipart/form-data">
                                    <div class='form-group'>
                                        <label>Upload ไฟล์ : </label> <label v-html="upload.uploadHandleStatus"></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="handleUploadFile" aria-describedby="btnHandleUploadFile" accept=".pdf"
                                                    v-on:change="onFileHandleChange"
                                                    v-bind:class="{
                                                        'is-valid': upload.inputHandleClassValid,
                                                        'is-invalid': upload.inputHandleClassInvalid
                                                    }"
                                                    :disabled="upload.inputHandleDisabled"
                                                >
                                                <label class="custom-file-label" for="handleUploadFile" >Choose file</label>
                                            </div>
                                            <select class="custom-select"
                                                v-model="upload.handle_type"
                                            >
                                                <option value="1">ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน</option>
                                                <option value="2">ใบยืม - คืนอุปกรณ์คอมพิวเตอร์</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-secondary" id="btnHandleUploadFile"
                                                    @click.prevent="uploadHandleFile" 
                                                    :disabled="upload.btnUploadHandleDisabled"
                                                >
                                                    <i class="fas fa-cloud-upload-alt"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="progress my-3" v-show="upload.showHandleProgress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" 
                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>รายการเอกสาร : </label>
                                <ul class="list-group">
                                    <li class="list-group-item py-1" 
                                        v-for="(hdf,index) in upload.handlepaper" :key="index"
                                    >
                                        <h5 class="d-inline mr-3">#{{index+1}}</h5>
                                        <span class="mr-3 text-primary"
                                            v-show="hdf.assthd_file_group == 1"
                                        >ใบรับมอบทรัพย์สิน/ใบโอนทรัพย์สิน</span>
                                        <span class="mr-3 text-primary"
                                            v-show="hdf.assthd_file_group == 2"
                                        >ใบยืม - คืนอุปกรณ์คอมพิวเตอร์</span>
                                        <span>Url : </span>
                                        <span class="badge badge-light">
                                            <a class="text-decoration-none" 
                                            v-bind:href="hdf.assthd_path" v-bind:target="'_blank'">{{hdf.assthd_file}}</a>
                                        </span>
                                        <a href="javascript:;" class="float-right"
                                            v-on:click="removeHandleFile(index)"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="pb-3">
                        <div class="row justify-content-md-center m-3">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal"
                                    :disabled="upload.btnAddCloseDisabled"
                                ><i class="fas fa-ban"></i> ยกเลิก</button>
                            </div>
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

export default {
    mounted(){

    },
    data(){
        return{
            page:{
                panelData: false
            },
            search:{
                text: '',
                textReadonly: false,
                type: '',
                typeDisabled: false,
                btnSearchContent: '<i class="fas fa-search"></i><span class="ml-2">ค้นหา</span>',
                btnSearchDisabled: false
            },
            upload:{
                handle_id: '',
                handle_filename: '',
                handle_type: 1,
                paperHandleNo: '',
                showPaperHandleNoPrint: false,
                showPaperHandleNoCreate: false,
                paperBorrowNo: '',
                showPaperBorrowNoPrint: false,
                showPaperBorrowNoCreate: false,
                uploadHandleStatus: '',
                inputHandleClassValid: false,
                inputHandleClassInvalid: false,
                inputHandleDisabled: false,
                btnUploadHandleDisabled: false,
                showHandleProgress: false,
                btnAddCloseTxt: '',
                btnAddCloseDisabled: false,
                handlepaper: []
            },
            filehandle: null,
            list: []
        }
    },
    methods:{
        searchPaper(){

            if( !this.validateSearch() )
                return;

            this.page.panelData = false;
            this.search.textReadonly = true;
            this.search.typeDisabled = true;
            this.search.btnSearchDisabled = true;

            let btnContent = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
            this.search.btnSearchContent = btnContent;

            axios.post('/api/admin/it/asset/paper',{
                text: this.search.text,
                type: this.search.type
            })
            .then(response => {
                this.page.panelData = true;
                this.search.textReadonly = false;
                this.search.typeDisabled = false;
                this.search.btnSearchDisabled = false;
                btnContent = '<i class="fas fa-search"></i><span class="ml-2">ค้นหา</span>';
                this.search.btnSearchContent = btnContent;
                this.list = response.data;
            })
            .catch(response => {
                this.page.panelData = false;
                this.search.textReadonly = false;
                this.search.typeDisabled = false;
                this.search.btnSearchDisabled = false;
                btnContent = '<i class="fas fa-search"></i><span class="ml-2">ค้นหา</span>';
                this.search.btnSearchContent = btnContent;
                this.$swal({
                    icon: 'error',
                    title: 'ไม่สามารถค้นหาได้',
                    text: 'ระบบไม่สามารถค้นหาเอกสารได้ กรุณาตรวจสอบข้อมูลในการค้นหา..!'
                })
            });

        },
        validateSearch(){
            if( this.search.text == "" ){

                this.$swal({
                    icon: 'error',
                    title: 'ไม่สามารถค้นหาได้',
                    text: 'กรุณากรอกกรอกเลขที่เอกสารหรือเลขพรัพย์สิน..!'
                });

                return false;

            }
            else 
                return true;
        },
        createHandleFile(){
            axios.post('/api/admin/it/assethandle/create/number/handle',{
                handle_id: this.upload.handle_id
            })
            .then(response => {
                this.upload.showPaperHandleNoCreate = false;
                this.upload.showPaperHandleNoPrint = true;
                this.upload.paperHandleNo = response.data.paper_no;
            });
        },
        createBorrowFile(){
            axios.post('/api/admin/it/assethandle/create/number/borrow',{
                handle_id: this.upload.handle_id
            })
            .then(response => {
                this.upload.showPaperBorrowNoCreate = false;
                this.upload.showPaperBorrowNoPrint = true;
                this.upload.paperBorrowNo = response.data.paper_no;
            });
        },
        onFileHandleChange(e){
            this.filehandle = e.target.files[0],
            this.upload.handle_filename = this.filehandle.name;
            this.upload.inputHandleClassValid = false;
            this.upload.inputHandleClassInvalid = false;
            this.upload.uploadHandleStatus = '';
            $('#handleUploadFile').next('.custom-file-label').html(this.upload.handle_filename);
        },
        getHandlePaper(assthdId,empId){

            axios.post('/api/admin/it/assethandle/paper',{
                assthd_id: assthdId,
                emp_id: empId
            })
            .then(response => {

                this.upload.handle_id = assthdId;
                
                let paper_no = response.data.paper_no,
                    paper = response.data.paper;
                
                if( paper_no[0].it_assthd_no == '' || paper_no[0].it_assthd_no === null  ){
                    this.upload.showPaperHandleNoCreate = true;
                    this.upload.showPaperHandleNoPrint = false;
                }
                else{
                    this.upload.showPaperHandleNoCreate = false;
                    this.upload.showPaperHandleNoPrint = true;
                    this.upload.paperHandleNo = paper_no[0].it_assthd_no;
                }

                if( paper_no[0].it_asstbr_no == '' || paper_no[0].it_asstbr_no === null  ){
                    this.upload.showPaperBorrowNoCreate = true;
                    this.upload.showPaperBorrowNoPrint = false;
                }
                else{
                    this.upload.showPaperBorrowNoCreate = false;
                    this.upload.showPaperBorrowNoPrint = true;
                    this.upload.paperBorrowNo = paper_no[0].it_asstbr_no;
                }

                this.upload.handlepaper = paper;
                $('#modal-holdasset-file').modal('show');

            });            

        },
        getHandleFile(){
            axios.post('/api/admin/it/assethandle/file',{ assthd_id: this.upload.handle_id })
            .then(response => { this.upload.handlepaper = response.data; });
        },
        uploadHandleFile(e){

            if( this.filehandle === null ){
                let status = '<i class="fas fa-exclamation-triangle text-danger"></i> ';
                    status += '<strong class="text-danger">กรุณาเลือก File</strong>';
                this.upload.uploadHandleStatus = status;
                this.upload.inputHandleClassValid = false;
                this.upload.inputHandleClassInvalid = true;
                return;
            }
            else if( this.filehandle.type != 'application/pdf' ){
                let status = '<i class="fas fa-exclamation-triangle text-danger"></i> ';
                    status += '<strong class="text-danger">กรุณาเลือก File PDF เท่านั้น</strong>';
                this.html.upload.uploadHandleStatus = status;
                this.html.upload.inputHandleClassValid = false;
                this.html.upload.inputHandleClassInvalid = true;
                return;
            }

            this.upload.uploadHandleStatus = '',
            this.upload.inputHandleClassValid = false;
            this.upload.inputHandleClassInvalid = false;
            this.upload.inputHandleDisabled = true;
            this.upload.btnUploadHandleDisabled = true;
            this.upload.showHandleProgress = true;

            e.preventDefault();

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            let formData = new FormData();
            formData.append('file', this.filehandle);
            formData.append('file_name', this.upload.handle_filename);
            formData.append('handle_id',this.upload.handle_id);
            formData.append('handle_group',this.upload.handle_type);

            axios.post('/api/admin/it/assethandle/upload', formData, config)
            .then(response => {

                $('#handleUploadFile').next('.custom-file-label').html('Choose file');

                this.filehandle = null;

                let statusRes = '<i class="fas fa-check-circle text-success"></i> ';
                    statusRes += '<strong class="text-success">Upload file complete.</strong>';

                this.upload.uploadHandleStatus = statusRes,
                this.upload.inputHandleClassValid = true;
                this.upload.inputHandleClassInvalid = false;
                this.upload.inputHandleDisabled = false;
                this.upload.btnUploadHandleDisabled = false;
                this.upload.showHandleProgress = false;

                this.getHandleFile();

            })
            .catch(response => {
                let statusRes = '<i class="fas fa-exclamation-triangle text-danger"></i> ';
                    statusRes += '<strong class="text-danger">Upload file fail!</strong>';
                this.upload.uploadHandleStatus = statusRes,
                this.upload.inputHandleClassValid = false;
                this.upload.inputHandleClassInvalid = true;
                this.upload.inputHandleDisabled = false;
                this.upload.btnUploadHandleDisabled = false;
                this.upload.showHandleProgress = false;
            });

        },
        removeHandleFile(index){
            this.$swal({
                icon: 'warning',
                title: 'ยืนยันการลบไฟล์ <br>'+this.upload.handlepaper[index].assthd_file,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'ลบไฟล์',
                denyButtonText: 'ยกเลิก',
            })
            .then((result) => {
                if( result.isConfirmed ){
                    axios.post('/api/admin/it/assethandle/removefile',{
                        file_id: this.upload.handlepaper[index].assthd_id,
                        file_path: this.upload.handlepaper[index].assthd_path
                    })
                    .then(response => {
                        this.$swal({
                            icon: 'success',
                            title: 'ลบไฟล์เรียบร้อยแล้ว'
                        });
                        this.getHandleFile();
                    })
                    .catch(response => {
                        this.$swal({
                            icon: 'error',
                            title: 'ไม่สามารถลบไฟล์ได้'
                        });
                    });
                }
            });
        }
    }
}
</script>