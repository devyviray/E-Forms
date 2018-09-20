<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="row">
            <div class="card col-md-12" v-if="drdrs.length">
                <div class="form-group">
                    <h1>REVIEW DRDR</h1>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <td> <strong> Company: </strong> </td>
                        <td> {{ drdrs[0].company.name }} </td>
                    </tr>
                    <tr>
                        <td> <strong> Type of Request </strong> </td>
                        <td v-if="drdrs[0].request_type == 1"> {{ 'Proposal (For proposed)' }} </td>
                        <td v-else-if="drdrs[0].request_type == 2">{{ 'Revision (For existing document)' }} </td>
                        <td v-else-if="drdrs[0].request_type == 3"> {{ 'Cancellation' }} </td>
                    </tr>
                    <tr>
                        <td> <strong> Document Title </strong> </td>
                        <td> {{ drdrs[0].document_title }} </td>
                    </tr>
                    <tr>
                        <td> <strong> Reasong of Request </strong> </td>
                        <td> {{ drdrs[0].reason_request }} </td>
                    </tr>
                    <tr>
                        <td> <strong> Request by </strong> </td>
                        <td> {{ drdrs[0].requester.name  }} </td>
                    </tr>
                    <tr>
                        <td> <strong>Date of Request </strong> </td>
                        <td> {{ moment(drdrs[0].date_request).format('LL') }} </td>
                    </tr>
                </table>

                <form>
                    <input type="hidden" class="form-control" placeholder="Name" v-if="drdrs.length" v-model="drdrs[0].id">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="selectedAttachment" class="col-sm-2 col-form-label">Download Attachment - Requester</label>
                                <div class="col-sm-10">
                                    <select class="form-control form-control-lg" v-model="selectedAttachment" @change="downloadAttachment" id="selectedAttachment">
                                        <option selected disabled> Download Attachment - Requester </option>
                                        <option v-for="(requesterAttachment, re) in requesterAttachments" :value="requesterAttachment.id" v-bind:key="re">{{ requesterAttachment.file_name }}</option>
                                    </select>
                                </div>   
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <select v-model="drdr.status" class="form-control form-control-lg" @change="selectedStatus" id="status">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Disapproved</option>
                                    </select>
                                    <span v-if="errors.status">{{ errors.status }}</span>
                                </div>   
                            </div>
                        </div>
                    </div>
                    <div v-if="show">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="consider_documents" class="col-sm-2 col-form-label">Consider documents in reviewing</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" v-model="drdr.consider_documents" id="consider_documents" cols="30" rows="10"></textarea>
                                        <span v-if="errors.consider_documents">{{ errors.consider_documents }}</span>
                                    </div>
                                </div>     
                            </div>  
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="approver" class="col-sm-2 col-form-label">Approver</label>
                                    <div class="col-sm-10">
                                        <select v-model="approver.id" class="form-control form-control-lg" id="approver">
                                            <option value="" disabled selected>Select Approver</option>
                                            <option v-for="(approver, a) in approvers" :value="approver.id" v-bind:key="a">{{ approver.name }}</option>
                                        </select>
                                        <span v-if="errors.approver">{{ errors.approver }}</span>
                                    </div>
                                </div>
                            </div>   
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="attachments" class="col-sm-2 col-form-label">Attach File</label>
                                    <div class="col-sm-10">
                                        <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange">
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="company" class="col-sm-2 col-form-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" v-model="drdr.remarks" id="remarks" cols="30" rows="10"></textarea>
                                    <span v-if="errors.remarks">{{ errors.remarks }}</span>
                                </div>
                            </div>
                        </div>   
                    </div>
                    <button @click="reviewedDrdr(drdrs[0].id, drdr, approver)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button>
                </form>

            </div>
        </div>
    </div>
</template>


<script>
import moment from 'moment';
import SpinnerLoading from '../SpinnerLoading';

export default {
    components:{
        SpinnerLoading
    },
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
            isLoading: false
        }
    },
    created(){
        this.fetchDrdr(); 
         
    },
    methods:{
        moment,
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
            this.isLoading = true;
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
