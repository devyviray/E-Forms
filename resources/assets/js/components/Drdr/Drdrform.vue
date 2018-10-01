<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="col-md-12">
            <div class="row">
                <div class="card col-md-12">
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
                                        <input type="text" class="form-control" placeholder="Document Title" v-model="drdr.document_title" id="document_title" style="height: 100px;">
                                        <span class="error" v-if="errors.document_title">{{ errors.document_title[0] }}</span>
                                    </div>
                                </div>
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="rev_number" class="col-sm-3 col-form-label">Current Rev No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Rev. No." v-model="drdr.rev_number" id="rev_number">
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
                                        <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange"><br>
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
        selectedType()
        {
            var revNumber = document.getElementById('rev_number');
            if(this.drdr.type == 1){
                revNumber.disabled = true;
                this.drdr.rev_number = " "; 
            }else{ revNumber.disabled = false; }
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
            var files = e.target.files || e.dataTransfer.files;

            if(!files.length)
                return;
            
            for (var i = files.length - 1; i >= 0; i--){
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

