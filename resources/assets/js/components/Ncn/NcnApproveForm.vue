<template>
    <div>
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div v-if="ncns.length">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <h1 style="color: #888888; text-align: center;">APPROVE NCN</h1>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <td> <strong> Requester: </strong> </td>
                            <td> {{ ncns[0].requester.name }} </td>
                        </tr>
                        <tr>
                            <td> <strong> Position: </strong> </td>
                            <td> {{ ncns[0].requester.position }} </td>
                        </tr> 
                        <tr>
                            <td> <strong> Requester: </strong> </td>
                            <td> {{ ncns[0].requester.name }} </td>
                        </tr> 
                        <tr>
                            <td> <strong> Company: </strong> </td>
                            <td> {{ ncns[0].company.name }} </td>
                        </tr>
                        <tr>
                            <td> <strong> Department: </strong> </td>
                            <td> {{ ncns[0].department.name }} </td>
                        </tr>
                        <tr>
                            <td> <strong> Type of Non-conformity: </strong> </td>
                            <td v-if="ncns[0].non_conformity_types == 1">{{ 'Customer - Returns' }}</td>
                            <td v-if="ncns[0].non_conformity_types == 2">{{ 'Objective not Met' }}</td> 
                            <td v-if="ncns[0].non_conformity_types == 3">{{ 'Process Related' }}</td>
                            <td v-if="ncns[0].non_conformity_types == 4">{{ 'Vendor' }}</td>
                            <td v-if="ncns[0].non_conformity_types == 5">{{ 'Contracted - Service' }}</td>
                            <td v-if="ncns[0].non_conformity_types == 6">{{ 'Others' }}</td>
                        </tr>
                        <tr>
                            <td> <strong> Notification Number: </strong> </td>
                            <td> {{ ncns[0].notification_number }} </td>
                        </tr>
                        <tr>
                            <td> <strong> Recurrence Number: </strong> </td>
                            <td> {{ ncns[0].recurrence_number }} </td>
                        </tr>
                        <tr>
                            <td> <strong> Date of issuance: </strong> </td>
                            <td>{{ moment(ncns[0].issuance_date).format('LL') }} </td>
                        </tr>
                        <tr>
                            <td> <strong> Details of non-conformity: </strong> </td>
                            <td>{{ ncns[0].non_conformity_details }} </td>
                        </tr>
                    </table>

                    <form>
                        <input type="hidden" class="form-control" placeholder="Name" v-model="ncns[0].id">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="selectedAttachment" class="col-sm-3 col-form-label">Download Attachment - Requester</label>
                                    <div class="col-sm-9">
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
                                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <select v-model="ncn.status" class="form-control form-control-lg" @change="selectedStatus" id="status">
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="1">Approved</option>
                                            <option value="2">Disapproved</option>
                                        </select>
                                        <span class="error" v-if="errors.status">{{ errors.status[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="show">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="notified" class="col-sm-3 col-form-label">Notified Person</label>
                                        <div class="col-sm-9">
                                            <select v-model="ncn.notified" class="form-control form-control-lg" id="notified">
                                                <option value="" disabled selected>Select Notified Person</option>
                                                <option v-for="(notified, n) in notifieds"  v-bind:key="n" :value="notified.id">{{ notified.name }}</option>
                                            </select>
                                            <span class="error" v-if="errors.notified">{{ errors.notified[0] }}</span>
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
                                            <span class="error" v-if="errors.attachments">{{ errors.attachments[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="remarks" class="col-sm-3 col-form-label">Remarks</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" v-model="ncn.remarks" id="remarks" cols="30" rows="10"></textarea>
                                        <span class="error" v-if="errors.remarks">{{ errors.remarks[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button @click="approvedNcn(ncns[0].id, ncn)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round float-right mb-4" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Submit</button>
                    </form>
                </div>
            </div>
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
    components:{
        SpinnerLoading
    },
    data(){
        return{
            ncns: [],
            ncn:{
                status: ' ',
                remarks: ' ',
                notified: ' '
            },
            show: false,
            notifieds: [],
            attachments: [],
            formData: new FormData(),
            ncnId: '',
            errors:'',
            selectedAttachment: ' ',
            requesterAttachments: ' ',
            isLoading: false
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
            this.isLoading = true;
            this.prepareFields();
            this.formData.append('id', id);
            this.formData.append('status', ncn.status);
            this.formData.append('notified', ncn.notified);
            this.formData.append('remarks', ncn.remarks);

            axios.post('/ncn-approved', this.formData)  
            .then(response => {
                var message = ncn.status == 1 ? 'Approved' :  'Disapproved';
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'NCN Succesfully '+message,
                    position: 'top right'
                });

                window.location.href = response.data.redirect;

            })
            .catch(error => {
                this.isLoading = false;
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
