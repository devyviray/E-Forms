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
                        <select class="form-control form-control-lg" v-if="reviewerAttachments.length" v-model="selectedAttachment" @change="downloadAttachment">
                            <option selected disabled> Download Attachment - Reviewer </option>
                            <option v-for="(reviewerAttachment, r) in reviewerAttachments" :value="reviewerAttachment.id" v-bind:key="r">{{ reviewerAttachment.file_name }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                       <select v-model="drdr.status" class="form-control form-control-lg"  @change="selectedStatus">
                           <option value="" disabled selected>Select Status</option>
                           <option value="1">Approved</option>
                           <option value="2">Disapproved</option>
                       </select>
                        <span v-if="errors.status">{{ errors.status }}</span>
                    </div>
                    <div class="form-group">
                        <label for="reason_request">Remarks</label>
                        <textarea class="form-control" v-model="drdr.remarks" id="remarks" cols="30" rows="10"></textarea>
                        <span v-if="errors.remarks">{{ errors.remarks }}</span>
                    </div>
                    <div v-if="show">
                        <div class="form-group" v-if="drdrs.length">
                            <datepicker placeholder="Select Effective Date" v-model="drdrs[0].effective_date"></datepicker>
                            <span v-if="errors.effective_date">{{ errors.effective_date }}</span>
                        </div>
                        <div class="form-group">
                            <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange">
                        </div>
                        <div class="form-group">
                            <label for="copy_number">Copy number</label>
                            <input type="text" class="form-control" placeholder="Copy number" v-model="drdr.copy_number" id="copy_number">
                            <span v-if="errors.copy_number">{{ errors.copy_number }}</span>
                        </div>
                        <div class="form-group">
                            <label for="copy_holder">Copy holder</label>
                            <input type="text" class="form-control" placeholder="Copy holder" v-model="drdr.copy_holder" id="copy_holder">
                            <span v-if="errors.copy_holder">{{ errors.copy_holder }}</span>
                        </div>
                    </div>
                    <button @click="approvedDrdr(drdrs[0].id, drdr,drdrs[0])" type="button" class="btn btn-primary">Submit</button>
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
            reviewerAttachments: [],
            selectedAttachment: '',
            drdr:{
                consider_documents: '',
                status: '',
                remarks: '',
            },
            errors: [],
            company_id: '',
            attachments: [],
            formData: new FormData(),
            show: false
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
                this.fetchUploadedFilesReviewer();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        fetchUploadedFilesReviewer()
        {
            axios.get('/drdr-reviewer-attachments/'+this.drdrs[0].id+'/'+this.drdrs[0].reviewer_id)
            .then(response => {
                this.reviewerAttachments = response.data;
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
        approvedDrdr(id,drdr,drdrs)
        {   
            this.prepareFields();
            this.formData.append('id', id);
            this.formData.append('status', drdr.status);
            this.formData.append('remarks', drdr.remarks);
            this.formData.append('effective_date', drdrs.effective_date);
            this.formData.append('copy_number', drdr.copy_number);
            this.formData.append('copy_holder', drdr.copy_holder);
            axios.post('/drdr-approved',this.formData)
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
    },
    components: {
         Datepicker
    }
}
</script>
