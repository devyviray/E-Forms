<template>
    <div id="page-content-wrapper">
        <div class="container-fluid">
             <div class="row">
                <div class="col-md-3">
                    <label for="reviewerAttachment">  Download Attachment - Requester </label>
                     <select class="form-control" v-model="selectedAttachment" @change="downloadAttachment" id="reviewerAttachment">
                        <option selected disabled> Download Attachment - Requester </option>
                        <option v-for="(requesterAttachment, r) in requesterAttachments" :value="requesterAttachment.id" v-bind:key="r">{{ requesterAttachment.file_name }}</option>
                    </select>
                </div>
                <div class="col-md-3" v-if="approverAttachments.length">
                    <label for="approverAttachment">  Download Attachment - Approver </label>
                    <select class="form-control" v-model="selectedAttachment" @change="downloadAttachment" id="approverAttachment">
                        <option selected disabled> Download Attachment - Approver </option>
                        <option v-for="(approverAttachment, a) in approverAttachments" :value="approverAttachment.id" v-bind:key="a">{{ approverAttachment.file_name }}</option>
                    </select>
                </div>
                <div class="col-md-3" v-if="notifiedAttachments.length">
                    <label for="approverAttachment">  Download Attachment - Notified </label>
                    <select class="form-control" v-model="selectedAttachment" @change="downloadAttachment" id="norifiedAttachment">
                        <option selected disabled> Download Attachment - Notified </option>
                        <option v-for="(notifiedAttachment, n) in notifiedAttachments" :value="notifiedAttachment.id" v-bind:key="n">{{ notifiedAttachment.file_name }}</option>
                    </select>
                </div>
                <div class="col-md-3" style="margin-top: 29px">
 
                </div>
            </div>
            <hr>
            NON-CONFORMANCE NOTIFICATION
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td rowspan="3" width="10%">
                            <img class="img-responsive" :src="logo" style="padding: 10px; width: 100px; height: auto;">
                        </td>
                        <td colspan="4">La Filipina Uy Gongco Group of Companies</td>
                    </tr>
                    <tr>
                        <td> <strong> Doc No. </strong> LFQM-F-019  </td>
                        <td><strong>  Rev No. </strong>  02 </td>
                        <td><strong> Effective Date: </strong>June 26, 2016 </td>
                    </tr>
                    <tr>
                        <td colspan="5"> NON-CONFORMANCE NOTIFICATION</td>
                    </tr>
                </tbody>
            </table>

            <!-- step 1 -->
            <table class="table table-bordered">
                <tbody>
                    <tr> 
                        <td> <strong> COMPANY: </strong> </td>
                        <td colspan="2" v-if="ncns.length">{{ ncns[0].company.name }}</td>
                    </tr>
                    <tr>
                        <td> <strong> DIVISION / DEPARTMENT: </strong> </td> 
                        <td colspan="2" v-if="ncns.length"> {{ ncns[0].department.name }}</td>
                    </tr>
                    <tr v-if="ncns.length">
                        <td> <strong> TYPE OF NON CONFORMITY: </strong> </td>
                        <td v-if="ncns[0].non_conformity_types == 1">Customer - Returns</td>
                        <td v-else-if="ncns[0].non_conformity_types == 2">Objective not Met</td>
                        <td v-else-if="ncns[0].non_conformity_types == 3">Process Related</td>
                        <td v-else-if="ncns[0].non_conformity_types == 4">Vendor</td>
                        <td v-else-if="ncns[0].non_conformity_types == 5">Contracted - Service</td>
                        <td colspan="2"  v-else>Others:</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td><strong>Notification No:</strong></td>
                        <td v-if="ncns.length">{{ ncns[0].notification_number }}</td>
                        <td> <strong> Issued by: </strong></td>
                        <td v-if="ncns.length">{{ ncns[0].requester.name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Recurrence No: </strong></td>
                        <td  v-if="ncns.length">{{ ncns[0].recurrence_number }} </td>
                        <td><strong> Position: </strong></td>
                        <td v-if="ncns.length"> {{ ncns[0].requester.position }} </td>
                    </tr>
                    <tr>
                        <td><strong> Date of Issuance:  </strong></td>
                        <td v-if="ncns.length">{{ moment(ncns[0].issuance_date).format('LL') }} </td>
                        <td> <strong>  Notified Person: </strong> </td>
                        <td v-if="ncns.length && ncns[0].notified">{{ ncns[0].notified.name }} </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="4"> <strong> Step 1: Provide details of non-conformity: </strong> </td>	
                    </tr>
                    <tr> 
                        <td colspan="4" v-if="ncns.length"> {{ ncns[0].non_conformity_details }} </td>
                    </tr>
                    <tr>
                        <td colspan="4"> <strong> Step 2: Immediate Action Taken: </strong> </td>	
                    </tr>
                    <tr>
                        <td colspan="4" v-if="ncns.length"> {{ ncns[0].action_taken }} </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    data(){
        return{
            ncns: [],
            requesterAttachments: [],
            approverAttachments: [],
            notifiedAttachments: [],
            selectedAttachment: '',
            errors: ''
        }
    },
    created(){
        this.fetchNcns();
    },
    methods:{
        moment,
        fetchNcns()
        {
            var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];

            axios.get(`/ncn-data/${id}`)
            .then(response => { 
                this.ncns = response.data;
                this.fetchUploadedFilesRequester();
                this.fetchUploadedFilesApprover();
                this.fetchUploadedFilesNotified();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
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
        fetchUploadedFilesApprover()
        {
            axios.get('/ncn-approver-attachments/'+this.ncns[0].id+'/'+this.ncns[0].approver_id)
            .then(response => {
                this.approverAttachments = response.data;
            })
            .catch(error => {
              this.errors = error.response.data.errors;
            })
        },
        fetchUploadedFilesNotified(){

            axios.get('/ncn-notified-attachments/'+this.ncns[0].id+'/'+this.ncns[0].notified_id)
            .then(response => {
                this.notifiedAttachments = response.data;
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
    },
    computed:{
        logo(){
            var base_url = window.location.origin;
            var url = base_url+'/img/lfug-logo.png';

            return url;
        }
    }
}
</script>

