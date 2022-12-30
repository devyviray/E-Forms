<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="card col-md-12">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <form>
                        <div class="form-group">
                            <h1 style="color: #888888; text-align: center;">ADD DRDR</h1>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="request_type" class="col-sm-3 col-form-label">Type of request</label>
                                    <div class="col-sm-9">
                                        <select v-model="drdr.type" class="form-control form-control-lg" @change="selectedType()" id="request_type">
                                            <option value="" disabled selected>Select Type</option>
                                            <option value="1">Proposal (For proposed)</option>
                                            <option value="2">Revision (For existing document)</option>
                                            <option value="3">Cancellation</option>
                                        </select>
                                        <span class="error" v-if="errors.type">{{ errors.type[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="reason_request" class="col-sm-3 col-form-label">Reason</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" v-model="drdr.reason_request" id="reason_request" cols="30" rows="10" placeholder="Reason"></textarea>
                                        <span class="error" v-if="errors.reason_request">{{ errors.reason_request[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="document_title" class="col-sm-3 col-form-label">Document title</label>
                                    <div class="col-sm-9">
                                        <textarea  class="form-control" placeholder="Document Title" v-model="drdr.document_title" id="document_title" style="height: 100px;"></textarea>
                                        <span class="error" v-if="errors.document_title">{{ errors.document_title[0] }}</span>
                                    </div>
                                </div>
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="rev_number" class="col-sm-3 col-form-label">Current Rev No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" @keypress="isNumber($event)" class="form-control" placeholder="Rev. No." v-model="drdr.rev_number" id="rev_number">
                                        <span class="error" v-if="errors.rev_number">{{ errors.rev_number[0] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="effective_date" class="col-sm-3 col-form-label">Effective date</label>
                                    <div class="col-sm-9">
                                        <datepicker  placeholder="Select Effective Date" v-model="drdr.effective_date" id="effective_date" :disabledDates="disabledDates"></datepicker>
                                        <span class="error" v-if="errors.effective_date">{{ errors.effective_date[0] }}</span>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="effective_date" class="col-sm-3 col-form-label">Company</label>
                                    <div class="col-sm-9">
                                        <select v-model="company.id" class="form-control form-control-lg" id="company">
                                            <option value="" disabled selected>Select Company</option>
                                            <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name }}</option>
                                        </select>
                                        <span class="error" v-if="errors.company_id">{{ errors.company_id[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="effective_date" class="col-sm-3 col-form-label">Location</label>
                                    <div class="col-sm-9">
                                        <select v-model="company.location" class="form-control form-control-lg" @change="fetchReviewers(company.location)"  id="company">
                                            <option value="" disabled selected>Select  Company Location</option>
                                            <option v-for="(loc, c) in selectedLocation" :value="loc.id" v-bind:key="c">{{ loc.address }}</option>
                                        </select>
                                        <span class="error" v-if="errors.company_location">{{ errors.company_location[0] }}</span>
                                    </div>
                                </div>
                            </div>      
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="attachments" class="col-sm-3 col-form-label">Attach File</label>
                                    <div class="col-sm-9">
                                        <input type="file" multiple="multiple" ref="attachments" id="attachments" placeholder="Attach file" @change="uploadFileChange" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx"><br>
                                        <span class="error" v-if="errors.attachments">{{ errors.attachments[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="reviewer" class="col-sm-3 col-form-label">Reviewer</label>
                                    <div class="col-sm-9">
                                        <select v-model="reviewer.id" class="form-control form-control-lg" id="reviewer">
                                            <option value="" disabled selected>Select Reviewer</option>
                                            <option v-for="(reviewer, r) in reviewers" v-bind:key="r" :value="reviewer.id">{{ reviewer.name }}</option>
                                        </select>
                                        <span class="error" v-if="errors.reviewer_id">{{ errors.reviewer_id[0] }}</span>
                                    </div>
                                </div>
                            </div>    
                        </div>        
                        <button @click="addDrdr(drdr,company,reviewer)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button>
                    </form>
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <th>Type of Document</th>
                            <th>Proposing Authority</th>
                            <th>Reviewing Authority</th>
                            <th class="d-block">Approving Authority</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>FSMS / HACCP / QMS Manual</td>
                                <td>Management Representative or Team Member</td>
                                <td>Regional TQM Manager or Assistant Team Leader or equivalent position</td>
                                <td class="d-block">VP for Corporate QA or Team Leader</td>
                            </tr>
                            <tr>
                                <td>PRP programs / GMP Manual</td>
                                <td>MR Supervisor or User of the program</td>
                                <td>Regional QM Manager or Department head</td>
                                <td class="d-block">QM Manager or equivalent position</td>
                            </tr>
                            <tr>
                                <td>CMP Manual</td>
                                <td>Chief Security Officer</td>
                                <td>Safety Coordinator</td>
                                <td class="d-block">VP for group Admin and General Services</td>
                            </tr>
                            <tr>
                                <td>KPI</td>
                                <td>Manager or equivalent position</td>
                                <td>Vice President or equivalent position</td>
                                <td class="d-block">President or equivalent position</td>
                            </tr>
                            <tr>
                                <td>Policy</td>
                                <td>Supervisor or equivalent position</td>
                                <td>Administrator or Department Head</td>
                                <td class="d-block">Vice President or equivalent position</td>
                            </tr>
                            <tr>
                                <td>Procedure</td>
                                <td>MR or Supervisor</td>
                                <td>Manager</td>
                                <td class="d-block">Section Head or Department Head</td>
                            </tr>
                            <tr>
                                <td>Guidelines</td>
                                <td>Immediate User</td>
                                <td>Supervisor or equivalent position</td>
                                <td class="d-block">Manager or equivalent position</td>
                            </tr>
                            <tr>
                                <td>Work Instruction</td>
                                <td>Immediate User</td>
                                <td>Supervisor</td>
                                <td class="d-block">Department Head or equivalent position</td>
                            </tr>
                            <tr>
                                <td>Forms</td>
                                <td>Immediate User</td>
                                <td>Supervisor</td>
                                <td class="d-block">Manager</td>
                            </tr>
                            <tr>
                                <td>Operator Standard Work</td>
                                <td>Technical Group/Supervisor</td>
                                <td>Manager</td>
                                <td class="d-block">Vice President or equivalent position</td>
                            </tr>
                            <tr>
                                <td>Externally generated documented</td>
                                <td>End-user</td>
                                <td>None</td>
                                <td class="d-block">Department Head</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .vdp-datepicker  input{
        background-color: #FFFFFF;
        border: 1px solid #E3E3E3;
        border-radius: 4px;
        color: #565656;
        padding: 8px 12px;
        height: 40px;
        -webkit-box-shadow: none;
        box-shadow: none;
        display: block;
        width: 100%;
        line-height: 1.5;   
    }
</style>

<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>
<script>
import Datepicker from 'vuejs-datepicker';
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);

export default {
    data(){
        return{
            drdrs: [],
            drdr: { 
                id: '',
                type: '',
                document_title: '',
                rev_number: '',
                reviewer_id: '',
                approver_id: '',
                company_id: '',
                reason_request: '',
                effective_date: '',
            },
            attachments: [],
            formData: new FormData(),
            companies: [],
            locations: [],
            company:{
                id: '',
                name: '',
                address: '',
                location: ''
            },
            reviewers: [],
            reviewer: {
                id: '',
                name: '',
                email: '',
                department_id: ''
            },
            keywords: '',
            errors: '',
            currentPage: 0,
           itemsPerPage: 10,
           isLoading: false,
           disabledDates: {
                to: new Date(Date.now() - 8640000)
            },
            fileSize: 0,
            maximumSize: 5000000,
        }
    },
    created(){
        this.fetchCompanies();
        this.fetchLocations();
    },
    computed: {
        selectedLocation() {
            var findCompany = this.companies.find(item => item.id === this.company.id)
            if(findCompany) {
                return this.locations.filter(item => {
                    return item.name === findCompany.name
                })
            }
        }
    },

    methods: {
        isNumber(evt)
        {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : event.keyCode
            if((charCode > 31 && (charCode < 48 || charCode > 57))){
              evt.preventDefault();
            }
            return true;
        },
        selectedType()
        {
            var revNumber = document.getElementById('rev_number');
            if(this.drdr.type == 1){
                revNumber.disabled = true;
                this.drdr.rev_number = "N/A"; 
            }else{
                this.drdr.rev_number = " "; 
                revNumber.disabled = false;
            }
        },
        prepareFields(){
            if(this.attachments.length > 0){
                for(var i = 0; i < this.attachments.length; i++){
                    let attachment = this.attachments[i];
                    this.formData.append('attachments[]', attachment);
                }
            } 
        },
        uploadFileChange(e){
            this.attachments = [];  
            var files = e.target.files || e.dataTransfer.files;
            if(!files.length)
                return;
            // File extension checking(Word,Excel and Powerpoint only)
            for (var i = files.length - 1; i >= 0; i--){
                let file_path = files[i].name;
                let allowed_extensions = /(\.xlsx|\.xls|\.doc|\.docx|\.ppt|\.pptx)$/i;
                if(!allowed_extensions.exec(file_path)){
                    this.attachments = [];
                    this.$refs.attachments.value = null;

                     this.$toast.error({
                        title:'ERROR',
                        message:'Please upload file having extensions .xlsx/.xls/.doc/.docx/.ppt/.pptx only.',
                        position: 'top right'
                    });

                    return false;
                }
                this.attachments.push(files[i]);
                this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
            }
            if(this.fileSize > 5){
                alert('File size exceeds 5 MB');
                document.getElementById('attachments').value = "";
                this.attachments = [];
                this.fileSize = 0;
            }

        },
        resetData(){
          this.formData = new FormData();
          this.attachments = [];  
        },
        fetchLocations() {
            axios.get('/companyLocation')
            .then(response => {
                this.locations = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        fetchCompanies(){
            axios.get('/companies')
            .then(response => {
                this.companies = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        fetchReviewers(company){
            axios.get(`/getReviewer/${company}`)
            .then(response => {
                this.reviewers = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        addDrdr(drdr,company,reviewer){
            this.isLoading = true;
            this.errors = [];
            this.prepareFields();
            this.formData.append('type', drdr.type);
            this.formData.append('document_title', drdr.document_title);
            this.formData.append('rev_number', drdr.rev_number);
            this.formData.append('reason_request', drdr.reason_request);
            this.formData.append('company_id', company.location);
            this.formData.append('company_location', company.location);
            this.formData.append('reviewer_id', reviewer.id);
            this.formData.append('effective_date', drdr.effective_date);
            axios.post('/drdr',this.formData)
            .then(response => {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'DRDR Succesfully Added',
                    position: 'top right'
                });
                this.resetData();
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.isLoading = false;
                this.attachments = [];
                this.errors = error.response.data.errors;
            });
        }
    },
    components: {
         Datepicker,
         SpinnerLoading
    }
}
</script>

