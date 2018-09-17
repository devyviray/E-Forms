<template>
      <div v-if="ncns.length">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <span>Requester</span>
                    </div>
                    <div class="col-md-6">
                        <span>{{ ncns[0].requester.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Position</span>
                    </div>
                    <div class="col-md-6">
                        <span>{{ ncns[0].requester.position }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Company</span>
                    </div>
                    <div class="col-md-6">
                        <span>{{ ncns[0].company.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Department</span>
                    </div>
                    <div class="col-md-6">
                        <span>{{ ncns[0].department.name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Type of Non-conformity:</span>
                    </div>
                    <div class="col-md-6">
                        <span v-if="ncns[0].non_conformity_types == 1">{{ 'Customer - Returns' }}</span>
                        <span v-if="ncns[0].non_conformity_types == 2">{{ 'Objective not Met' }}</span> 
                        <span v-if="ncns[0].non_conformity_types == 3">{{ 'Project Related' }}</span>
                        <span v-if="ncns[0].non_conformity_types == 4">{{ 'Vendor' }}</span>
                        <span v-if="ncns[0].non_conformity_types == 5">{{ 'Contracted - Service' }}</span>
                        <span v-if="ncns[0].non_conformity_types == 6">{{ 'Others' }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Notification Number:</span>
                    </div>
                    <div class="col-md-">
                        <span>{{ ncns[0].notification_number }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span> Recurrence No:</span>
                    </div>
                    <div class="col-md-">
                        <span>{{ ncns[0].recurrence_number }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Date of issuance:</span>
                    </div>
                    <div class="col-md-">
                        <span>{{ moment(ncns[0].issuance_date).format('LL') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span> Details of non-conformity:</span>
                    </div>
                    <div class="col-md-">
                        <span>{{ ncns[0].non_conformity_details }}</span>
                    </div>
                </div>
                <form>
                    <input type="hidden" class="form-control" placeholder="Name" v-model="ncns[0].id">
                    <div class="form-group">
                        <select class="form-control form-control-lg" v-model="selectedAttachment" @change="downloadAttachment">
                            <option selected disabled> Download Attachment - Requester </option>
                            <option v-for="(requesterAttachment, re) in requesterAttachments" :value="requesterAttachment.id" v-bind:key="re">{{ requesterAttachment.file_name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                       <label for="status">Select status</label>
                       <select v-model="ncn.status" class="form-control form-control-lg" @change="selectedStatus" id="status">
                           <option value="" disabled selected>Select Status</option>
                           <option value="1">Approved</option>
                           <option value="2">Disapproved</option>
                       </select>
                        <span v-if="errors.status">{{ errors.status }}</span>
                    </div>
                    <div v-if="show">
                        <div class="form-group">
                            <label for="notified">Notified Person</label>
                            <select v-model="ncn.notified" class="form-control form-control-lg" id="notified">
                                <option value="" disabled selected>Select Notified Person</option>
                                <option v-for="(notified, n) in notifieds"  v-bind:key="n" :value="notified.id">{{ notified.name }}</option>
                            </select>
                            <span v-if="errors.status">{{ errors.status }}</span>
                        </div>
                        <div class="form-group">
                            <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange">
                            <span v-if="errors.attachments">{{ errors.attachments }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason_request">Remarks</label>
                        <textarea class="form-control" v-model="ncn.remarks" id="remarks" cols="30" rows="10"></textarea>
                        <span v-if="errors.remarks">{{ errors.remarks }}</span>
                    </div>
                    <button @click="approvedNcn(ncns[0].id, ncn)" type="button" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    data(){
        return{
            ncns: [],
            ncn:{
                status: '',
                remarks: ''
            },
            show: false,
            notifieds: [],
            attachments: [],
            formData: new FormData(),
            ncnId: '',
            errors:'',
            selectedAttachment: ' ',
            requesterAttachments: ' '
        }
    },
    created(){
        this.fetchNcn();
    },
    methods:{
        moment,
       fetchNcn(){
            var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];
            
            this.ncnId = id;
            
            axios.get(`/ncn-data/${id}`)
            .then(response => {
                this.ncns = response.data;
                this.fetchUploadedFilesRequester();
            })
            .catch(error =>{
                this.errors = error.response.data.errors;
            });
        },
        approvedNcn(id,ncn)
        {
            this.prepareFields();
            this.formData.append('id', id);
            this.formData.append('status', ncn.status);
            this.formData.append('notified', ncn.notified);
            this.formData.append('remarks', ncn.remarks);

            axios.post('/ncn-approved', this.formData)  
            .then(response => {
                var redirect = response.data.redirect;
                window.location.href = redirect;

            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        fetchNotified(id,department)
        {
            axios.get(`/ncns-notified/${id}/${department}`)
            .then(response => {
                this.notifieds = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        selectedStatus(){
            if(this.ncn.status == 1)
            {
                this.show = true;
                this.fetchNotified(this.ncns[0].company_id, this.ncns[0].department_id);
            }else{
                this.show = false;
            }
        },
        fetchUploadedFilesRequester()
        {
            axios.get('/ncn-requester-attachments/'+this.ncns[0].id+'/'+this.ncns[0].requester_id)
            .then(response => {
                this.requesterAttachments = response.data;
            })
            .catch(error => {
              this.errors = error.response.data.errors;
            })
        },
        downloadAttachment()
        {
            var base_url = window.location.origin;
            window.location = base_url+`/download-attachment/${this.selectedAttachment}`;
        },
        uploadFileChange(e){
            var files = e.target.files || e.dataTransfer.files;

            if(!files.length)
                return;
            
            for (var i = files.length - 1; i >= 0; i--){
                this.attachments.push(files[i]);
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
        
    },
}
</script>
