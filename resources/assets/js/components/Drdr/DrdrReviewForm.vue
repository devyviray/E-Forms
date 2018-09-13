<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <span>Company</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="drdrs.length">{{ drdrs[0].company.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Type of Request</span>
                    </div>
                    <div class="col-md-6" v-if="drdrs.length">
                        <span v-if="drdrs[0].request_type == 1">{{ 'Proposal (For proposed)' }}</span>
                        <span v-else-if="drdrs[0].request_type == 2">{{ 'Revision (For existing document)' }}</span>
                        <span v-else-if="drdrs[0].request_type == 3">{{ 'Cancellation' }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Document title</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="drdrs.length">{{ drdrs[0].document_title }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Reasong of request</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="drdrs.length">{{ drdrs[0].reason_request }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Request by</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="drdrs.length">{{ drdrs[0].requester.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Date of Request</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="drdrs.length">{{ drdrs[0].date_request }}</span>
                    </div>
                </div>
                <form>
                    <input type="hidden" class="form-control" placeholder="Name" v-if="drdrs.length" v-model="drdrs[0].id">
                    <div class="form-group">
                        <select class="form-control form-control-lg" v-model="selectedAttachment" @change="downloadAttachment">
                            <option selected disabled> Download Attachment - Requester </option>
                            <option v-for="(requesterAttachment, re) in requesterAttachments" :value="requesterAttachment.id" v-bind:key="re">{{ requesterAttachment.file_name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                       <select v-model="drdr.status" class="form-control form-control-lg" @change="selectedStatus">
                           <option value="" disabled selected>Select Status</option>
                           <option value="1">Approved</option>
                           <option value="2">Disapproved</option>
                       </select>
                        <span v-if="errors.status">{{ errors.status }}</span>
                    </div>
                    <div v-if="show">
                        <div class="form-group">
                            <label for="consider_documents">Consider documents in reviewing</label>
                            <textarea class="form-control" v-model="drdr.consider_documents" id="consider_documents" cols="30" rows="10"></textarea>
                            <span v-if="errors.consider_documents">{{ errors.consider_documents }}</span>
                        </div>
                        <div class="form-group">
                        <select v-model="approver.id" class="form-control form-control-lg">
                            <option value="" disabled selected>Select Approver</option>
                            <option v-for="(approver, a) in approvers" :value="approver.id" v-bind:key="a">{{ approver.name }}</option>
                        </select>
                            <span v-if="errors.approver">{{ errors.approver }}</span>
                        </div>
                        <div class="form-group">
                            <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="reason_request">Remarks</label>
                        <textarea class="form-control" v-model="drdr.remarks" id="remarks" cols="30" rows="10"></textarea>
                        <span v-if="errors.remarks">{{ errors.remarks }}</span>
                    </div>
                    <button @click="reviewedDrdr(drdrs[0].id, drdr, approver)" type="button" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>


<script>

import { eventBus } from '../../app.js';

export default {
    data(){
        return{
            drdrs: [],
            requesterAttachments: [],
            selectedAttachment: '',
            drdr:{
                consider_documents: ' ',
                status: ' ',
                remarks: ' ',   
            },
            errors: [],
            company_id: '',
            approvers: [],
            approver:{
                id:'',
                name: ''
            },
            attachments: [],
            formData: new FormData(),
            show: false,
        }
    },
    created(){
        this.fetchDrdr(); 
         
    },
    methods:{
        fetchDrdr(){
            var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];
            
            axios.get(`/drdr-data/${id}`)
            .then(response => {
                this.drdrs = response.data;
                this.company_id = this.drdrs[0].company.id;
                this.fetchApprover(this.company_id);
                this.fetchUploadedFilesRequester();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        fetchApprover(company_id)
        {
            axios.get(`/drdr-approver/${company_id}`)
            .then(response => {
                this.approvers = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        fetchUploadedFilesRequester()
        {
            axios.get('/drdr-requester-attachments/'+this.drdrs[0].id+'/'+this.drdrs[0].requester_id)
            .then(response => {
                this.requesterAttachments = response.data;
            })
            .catch(error => {
              this.errors = error.response.data.errors;
            })
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
        prepareFields(){
            if(this.attachments.length > 0){
                for(var i = 0; i < this.attachments.length; i++){
                    let attachment = this.attachments[i];
                    this.formData.append('attachments[]', attachment);
                }
            } 
        },
        reviewedDrdr(id,drdr, approver)
        {   
            this.prepareFields();
            this.formData.append('id', id);
            this.formData.append('consider_documents', drdr.consider_documents);
            this.formData.append('status', drdr.status);
            this.formData.append('remarks', drdr.remarks);
            this.formData.append('approver_id', approver.id)
            axios.post('/drdr-reviewed',this.formData)
            .then(response => {
                this.resetData();
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        selectedStatus()
        {
            this.drdr.status == 1 ? this.show = true : this.show = false;
        },
        downloadAttachment()
        {
            var base_url = window.location.origin;
            window.location = base_url+`/download-attachment/${this.selectedAttachment}`;
        },
        
    }
}
</script>
