<template>
    <div id="page-content-wrapper">
         <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="container-fluid" v-if="drdrs.length">
            <div class="card-header mb-3">
                <h1 class="card-title text-center" style="color: #888888;">VERIFY DRDR</h1>   
            </div>
            <hr>
            <table class="table table-bordered mb-4">
                <tr>
                    <td> <strong> Company: </strong> </td>
                    <td> {{ drdrs[0].company.name }} </td>
                </tr>
                <tr>
                    <td> <strong> Type of Request: </strong> </td>
                    <td>
                        <span v-if="drdrs[0].request_type == 1">{{ 'Proposal (For proposed)' }}</span>
                        <span v-else-if="drdrs[0].request_type == 2">{{ 'Revision (For existing document)' }}</span>
                        <span v-else-if="drdrs[0].request_type == 3">{{ 'Cancellation' }}</span>
                    </td>
                </tr>
                <tr>
                    <td> <strong> Document title: </strong> </td>
                    <td> {{ drdrs[0].document_title }} </td>
                </tr>
                <tr>
                    <td> <strong> Reasong of Request: </strong> </td>
                    <td> {{ drdrs[0].reason_request }} </td>
                </tr>
                <tr>
                    <td> <strong> Request By: </strong> </td>
                    <td> {{ drdrs[0].requester.name }} </td>
                </tr>
                <tr>
                    <td> <strong>Date of Request </strong> </td>
                    <td> {{ moment(drdrs[0].date_request).format('LL') }} </td>
                </tr>
            </table>
            
              <div class="row mb-4">
                <div class="col-md-4">
                    <label for="requesterAttachment"> Download Attachment - Requester</label>
                    <select class="form-control" v-model="selectedAttachment" @change="downloadAttachment" id="reviewerAttachment">
                        <option selected disabled> Download Attachment - Requester </option>
                        <option v-for="(requesterAttachment, re) in requesterAttachments" :value="requesterAttachment.id" v-bind:key="re">{{ requesterAttachment.file_name }}</option>
                    </select>
                </div>
                <div class="col-md-4" v-if="reviewerAttachments.length">
                    <label for="reviewerAttachment">Download Attachment - Reviewer</label>
                    <select class="form-control"  v-model="selectedAttachment" @change="downloadAttachment" id="reviewerAttachment">
                        <option selected disabled> Download Attachment - Reviewer </option>
                        <option v-for="(reviewerAttachment, r) in reviewerAttachments" :value="reviewerAttachment.id" v-bind:key="r">{{ reviewerAttachment.file_name }}</option>
                    </select>
                </div>
                <div class="col-md-4"  v-if="approverAttachments.length" >
                    <label for="reviewerAttachment">Download Attachment - Approver</label>
                    <select class="form-control" v-model="selectedAttachment" @change="downloadAttachment" id="approverAttachment">
                        <option selected disabled> Download Attachment - Approver </option>
                        <option v-for="(approverAttachment, a) in approverAttachments" :value="approverAttachment.id" v-bind:key="a">{{ approverAttachment.file_name }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <table class="table table-hover table-striped">
                    <thead>
                        <th>Copy No.</th>
                        <th>Copy Holder</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <tr v-for="(drdrcopyholder, d) in drdrcopyholders" v-bind:key="d">
                            <td>
                                <input type="text" class="form-control" placeholder="Copy No." v-model="drdrcopyholder.copy_number">
                                <span class="error" v-if="errors['drdrcopyholders.'+d+'.copy_number']">This field is required</span>
                            </td>
                            <td>
                                <input type="text" class="form-control" placeholder="Copy Holder" v-model="drdrcopyholder.copy_holder">
                                <span class="error" v-if="errors['drdrcopyholders.'+d+'.copy_holder']">This field is required</span>
                            </td>
                            <td>
                                <i @click="addRow(d)" class="material-icons mr-2" style="font-size:40px;color:green;cursor:pointer">add_circle_outline</i>
                                <i @click="deleteRow(d)" class="material-icons ml-2" style="font-size:40px;color:red;cursor:pointer">delete_forever</i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="drdr_number" class="col-sm-3 col-form-label">DRDR NO</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="" v-model="drdr.drdr_number" id="drdr_number">
                            <span class="error" v-if="errors.drdr_number">{{ errors.drdr_number[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="document_code" class="col-sm-3 col-form-label">Document Code</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="" v-model="drdr.document_code" id="">
                            <span class="error" v-if="errors.document_code">{{ errors.document_code[0] }}</span>
                        </div>
                    </div>
                </div> 
            </div>  
            <button @click="distributeDrdr(drdr, drdrcopyholders)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button> 
        </div>
    </div>
</template>
<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>

<script>
import moment from 'moment';
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);

export default {
    props:['drdrId'],
    components:{
        SpinnerLoading
    },
    data(){
        return{
            drdrs: [],
            requesterAttachments: [],
            reviewerAttachments: [],
            approverAttachments: [],
            selectedAttachment: ' ',
            errors: '',
            isLoading: false,
            drdrcopyholders: [{
                copy_number: '',
                copy_holder: ''
            }],
            drdr:{
                drdr_number: '',
                document_code: ''
            }
        }
    },
    created(){
        this.fetchDrdrs();
    },
    methods:{
        moment,
        fetchDrdrs()
        {
            axios.get(`/drdr-data/${this.drdrId}`)
            .then(response => { 
                this.drdrs = response.data;
                this.fetchUploadedFilesRequester();
                this.fetchUploadedFilesReviewer();
                this.fetchUploadedFilesApprover();
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
        fetchUploadedFilesApprover()
        {
            axios.get('/drdr-approver-attachments/'+this.drdrs[0].id+'/'+this.drdrs[0].approver_id)
            .then(response => {
                this.approverAttachments = response.data;
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
        addRow(d){
            this.drdrcopyholders.splice(d + 1, 0,{
                copy_number: '',
                copy_holder: ''
            })
            // this.drdrcopyholders.push({
            //     copy_number: '',
            //     copy_holder: ''
            // })
        },
        deleteRow(index) {
            this.drdrcopyholders.length < 2 ? alert('Unable to delete all row') : this.drdrcopyholders.splice(index,1);
        },
        distributeDrdr(drdr, drdrcopyholders){
            this.isLoading = true;
            axios.post('/admin/drdr-distributed', { 
                'id': this.drdrId,
                'drdr_number': drdr.drdr_number,
                'document_code': drdr.document_code,
                'drdrcopyholders': drdrcopyholders
            })
            .then(response=> {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'DRDR Succesfully Verified',
                    position: 'top right'
                });
                this.selected_id = '';
                window.location.href =  window.location.origin+'/admin/drdr-details/'+response.data.id;
            })
            .catch(error => { 
                this.isLoading = false;
                this.errors = error.response.data.errors;
            })
        },
        
    },
    computed:{
        hrefLink()
        {
            var base_url = window.location.origin;
            var url = base_url+`/admin/drdr-pdf/${this.drdrId}`;

            return url;
        },
        logo(){
            var base_url = window.location.origin;
            var url = base_url+'/img/lfug-logo.png';

            return url;
        }
    }
}
</script>
