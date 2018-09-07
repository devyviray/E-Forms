<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <form v-if="drdrs.length">
                    <div class="form-group">
                       <select v-model="drdrs[0].request_type" class="form-control form-control-lg" @change="selectedType()">
                           <option value="" disabled selected>Select Type</option>
                           <option value="1">Proposal (For proposed)</option>
                           <option value="2">Revision (For existing document)</option>
                           <option value="3">Cancellation</option>
                       </select>
                        <span v-if="errors.type">{{ errors.type }}</span>
                    </div>
                    <div class="form-group">
                         <label for="rev_number">Current Rev No.</label>
                        <input type="textrev_number" class="form-control" placeholder="Rev. No." v-if="drdrs.length" v-model="drdrs[0].rev_number" id="rev_number">
                        <span v-if="errors.rev_number">{{ errors.rev_number }}</span>
                    </div>
                    <div class="form-group">
                        <datepicker placeholder="Select Effective Date" v-if="drdrs.length" v-model="drdrs[0].effective_date"></datepicker>
                        <span v-if="errors.effective_date">{{ errors.effective_date }}</span>
                    </div>
                    <div class="form-group">
                       <select v-model="drdrs[0].company.id" class="form-control form-control-lg" @change="fetchReviewers(drdrs[0].company.id)">
                           <option value="" disabled selected>Select Company</option>
                           <option v-for="(company, c) in companies" :value="company.id" v-bind:key="c">{{ company.name + ' - ' + company.address }}</option>
                       </select>
                        <span v-if="errors.company">{{ errors.company }}</span>
                    </div>
                    <div class="form-group">
                        <label for="document_title">Document title</label>
                        <input type="text" class="form-control" placeholder="Document Title" v-if="drdrs.length" v-model="drdrs[0].document_title" id="document_title">
                        <span v-if="errors.document_title">{{ errors.document_title }}</span>
                    </div>
                    <div class="form-group">
                        <label for="reason_request">Reason</label>
                        <textarea class="form-control" v-if="drdrs.length" v-model="drdrs[0].reason_request" id="reason_request" cols="30" rows="10" placeholder="Reason"></textarea>
                        <span v-if="errors.reason_request">{{ errors.reason_request }}</span>
                    </div>
                    <div class="form-group">
                        <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange">
                    </div>
                    <div class="form-group">
                        <select v-model="drdrs[0].reviewer.id" class="form-control form-control-lg">
                            <option value="" disabled selected>Select Reviewer</option>
                            <option v-for="(reviewer, r) in reviewers" v-bind:key="r" :value="reviewer.id">{{ reviewer.name }}</option>
                        </select>
                    </div>
                    <button @click="updateDrdr(drdrs[0])" type="button" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

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
            drdr_id: '',
            attachments: [],
            formData: new FormData(),
            companies: [],
            company:{
                id: '',
                name: '',
                address: ''
            },
            company_id: ' ',
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
        }
    },
    created(){
        this.fetchDrdrs();
        this.fetchCompanies();
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
            }
        },
        resetData(){
          this.formData = new FormData();
          this.attachments = [];  
        },
        fetchDrdrs(){
            var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];
            this.drdr_id = id;
            
            axios.get(`/drdr-data/${id}`)
            .then(response => {
                this.drdrs = response.data;
                this.company_id = this.drdrs[0].company.id;
                this.fetchReviewers(this.company_id);
            })
            .catch(error =>{
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
        updateDrdr(drdrs){
            this.prepareFields();
            this.formData.append('type', drdrs.request_type);
            this.formData.append('document_title', drdrs.document_title);
            this.formData.append('rev_number', drdrs.rev_number);
            this.formData.append('reason_request', drdrs.reason_request);
            this.formData.append('company_id', drdrs.company.id);
            this.formData.append('reviewer_id', drdrs.reviewer.id);
            this.formData.append('effective_date', drdrs.effective_date);
            axios.post(`/drdr/${this.drdr_id}`,this.formData)
            .then(response => {
                this.resetData();
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        }
    },
    components: {
         Datepicker
    }
}
</script>

