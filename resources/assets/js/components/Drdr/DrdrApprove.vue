<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="row" v-if="drdrs.length">
            <div class="card col-md-12">
                <div class="form-group">
                    <h1 style="color: #888888; text-align: center;">APPROVE DRDR</h1>
                </div>
                <table class="table table-bordered">
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

                <form>
                    <input type="hidden" class="form-control" placeholder="Name" v-if="drdrs.length" v-model="drdrs[0].id">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="selectedAttachment" class="col-sm-3 col-form-label">Download Attachment - Reviewer</label>
                                <div class="col-sm-9">
                                    <select class="form-control form-control-lg" v-if="reviewerAttachments.length" v-model="selectedAttachment" @change="downloadAttachment" id="selectedAttachment">
                                        <option selected disabled> Download Attachment - Reviewer </option>
                                        <option v-for="(reviewerAttachment, r) in reviewerAttachments" :value="reviewerAttachment.id" v-bind:key="r">{{ reviewerAttachment.file_name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="status" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <select v-model="drdr.status" class="form-control form-control-lg"  @change="selectedStatus" id="status">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Disapproved</option>
                                    </select>
                                    <span class="error" v-if="errors.status">{{ errors.status[0] }}</span>
                                </div>   
                            </div>
                        </div>       
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="remarks" class="col-sm-3 col-form-label">Remarks</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" v-model="drdr.remarks" id="remarks" cols="30" rows="10"></textarea>
                                    <span class="error" v-if="errors.remarks">{{ errors.remarks[0] }}</span>
                                </div>   
                            </div>
                        </div>       
                    </div>    
                    <div v-if="show">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="remarks" class="col-sm-3 col-form-label">Effective Date</label>
                                    <div class="col-sm-9">
                                        <datepicker placeholder="Select Date" v-model="drdrs[0].effective_date"></datepicker>
                                        <span class="error" v-if="errors.effective_date">{{ errors.effective_date[0] }}</span>
                                    </div>   
                                </div>
                            </div>       
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="attachments" class="col-sm-3 col-form-label">Attach File</label>
                                    <div class="col-sm-9">
                                        <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange"><br>
                                        <span class="error" v-if="errors.attachments"> {{ errors.attachments[0] }}</span>
                                    </div>   
                                </div>
                            </div>       
                        </div> 
                    </div>
                    <button @click="approvedDrdr(drdrs[0].id, drdr,drdrs[0])" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button>
                </form>
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
import moment from 'moment';
import Datepicker from 'vuejs-datepicker';
import SpinnerLoading from '../SpinnerLoading';
import CxltToastr from 'cxlt-vue2-toastr';
Vue.use(CxltToastr);

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
                copy_number: ' ',
                copy_holder: ' '
            },
            errors: [],
            company_id: '',
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
            var effective_date = moment(drdrs.effective_date).format()
            this.isLoading = true;
            this.prepareFields();
            this.formData.append('id', id);
            this.formData.append('status', drdr.status);
            this.formData.append('remarks', drdr.remarks);
            this.formData.append('effective_date', effective_date);
            axios.post('/drdr-approved',this.formData)
            .then(response => {
                var message = drdr.status == 1 ? 'Approved' :  'Disapproved';
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'DRDR Succesfully '+message,
                    position: 'top right'
                });
                this.resetData();
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                this.isLoading = false;
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
         Datepicker,
         SpinnerLoading
    }
}
</script>
