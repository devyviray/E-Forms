<template>
    <div id="page-content-wrapper">
        <div class="container-fluid" v-if="drdrs.length">
            <div class="row">
                <div class="col-md-3">
                    <label for="requesterAttachment"> Download Attachment - Requester</label>
                    <select class="form-control" v-model="selectedAttachment" @change="downloadAttachment" id="reviewerAttachment">
                        <option selected disabled> Download Attachment - Requester </option>
                        <option v-for="(requesterAttachment, re) in requesterAttachments" :value="requesterAttachment.id" v-bind:key="re">{{ requesterAttachment.file_name }}</option>
                    </select>
                </div>
                <div class="col-md-3" v-if="reviewerAttachments.length">
                    <label for="reviewerAttachment">Download Attachment - Reviewer</label>
                    <select class="form-control"  v-model="selectedAttachment" @change="downloadAttachment" id="reviewerAttachment">
                        <option selected disabled> Download Attachment - Reviewer </option>
                        <option v-for="(reviewerAttachment, r) in reviewerAttachments" :value="reviewerAttachment.id" v-bind:key="r">{{ reviewerAttachment.file_name }}</option>
                    </select>
                </div>
                <div class="col-md-3"  v-if="approverAttachments.length" >
                    <label for="reviewerAttachment">Download Attachment - Approver</label>
                    <select class="form-control" v-model="selectedAttachment" @change="downloadAttachment" id="approverAttachment">
                        <option selected disabled> Download Attachment - Approver </option>
                        <option v-for="(approverAttachment, a) in approverAttachments" :value="approverAttachment.id" v-bind:key="a">{{ approverAttachment.file_name }}</option>
                    </select>
                </div>
                <div class="col-md-3 mt-4">
                    <a :href="hrefLink" target="_blank" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Print as PDF</a>  
                </div>
            </div>

            <hr>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td rowspan="3" width="10%">
                            <img class="img-responsive" :src="logo" style="padding: 10px; width: 100px; height: auto;">
                        </td>
                        <td colspan="4">La Filipina Uy Gongco Group of Companies</td>
                    </tr>
                    <tr>
                        <td> <strong>  Doc No. </strong>  LFQM-F-001 </td>
                        <td v-if="drdrs.length"><strong>  Rev No. </strong>  04 </td>
                        <td v-if="drdrs.length"><strong> Effective Date: </strong>February 27, 2017 </td>
                    </tr>
                    <tr>
                        <td colspan="5"> DOCUMENT REVIEW and DISTRIBUTION REQUEST </td>
                    </tr>
                </tbody>
            </table>

            <!-- step 1 -->
            <em> <h4 style="margin-bottom: 20px; font-weight: bolder;">  Step 1: Submission of request  </h4> </em>

            <!-- table start -->
            <table class="table" width="100%">
                <!-- first set -->
                <tbody>
                    <tr>
                        <td> <strong> Existing Document </strong> </td>
                        <td> <strong> Document Title: </strong> </td>
                        <td v-if="drdrs.length">{{ drdrs[0].document_title }}</td>
                        <td> <strong> Rev. No: </strong> </td>
                        <td colspan="3" v-if="drdrs.length"> {{ drdrs[0].rev_number }} </td>
                    </tr>

                    <!-- second set -->
                    <tr>
                        <td> <strong> Reason of Request: </strong> </td>
                        <td colspan="5" v-if="drdrs.length"> {{ drdrs[0].reason_request }} </td>
                    </tr>
                    <!-- third set -->
                    <tr>
                        <td> <strong> Requested By: </strong> </td>
                        <td v-if="drdrs.length"> {{ drdrs[0].requester.name }}</td>
                        <td> <strong> Position: </strong> &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;
                            {{ drdrs[0].requester.position }}
                        </td>
                        <td v-if="drdrs.length">  </td>
                        <td><strong>Date:</strong></td>
                        <td v-if="drdrs.length"> {{ moment(drdrs[0].date_request).format('LL') }} </td>
                    </tr>
                </tbody>
            </table>
            <!-- table end -->

            <!-- step 2 -->
            <em> <h4 style="margin-bottom: 20px; font-weight: bold;"> Step 2: Review of request </h4> </em>

            <table class="table" width="100%">
                <tbody>
                    <tr>
                        <td> <strong> Reviewed By: </strong> </td>
                        <td  v-if="drdrs.length"> 
                            {{ drdrs[0].reviewer.name }} <br>
                            <span style="color: red" v-if="drdrs[0].status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="drdrs[0].status == 5"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                        </td>
                        <td> <strong> Position:</strong> </td>
                        <td  v-if="drdrs.length"> {{ drdrs[0].reviewer.position }}  </td>
                        <td> <strong> Date: </strong> </td>
                        <td v-if="drdrs.length && drdrs[0].reviewed_date && drdrs[0].status != 2"> {{ moment(drdrs[0].reviewed_date).format('LL') }} </td>
                        <td v-if="drdrs.length && drdrs[0].disapproved_date && drdrs[0].status == 5"> {{ moment(drdrs[0].disapproved_date).format('LL') }} </td>
                    </tr>

                    <tr v-if="drdrs.length && drdrs[0].approver">
                        <td> <strong> Approved By: </strong> </td>
                        <td v-if="drdrs.length" >
                            {{ drdrs[0].approver.name }} <br>
                            <span style="color: red" v-if="drdrs[0].status == 3"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="drdrs[0].status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span> 
                        </td>
                        <td> <strong> Position: </strong></td>
                        <td v-if="drdrs.length"> {{ drdrs[0].approver.position }}</td>
                        <td> <strong> Date: </strong> </td>
                        <td v-if="drdrs.length && drdrs[0].approved_date && drdrs[0].status != 3"> {{ moment(drdrs[0].approved_date).format('LL') }} </td>
                        <td v-if="drdrs.length && drdrs[0].disapproved_date && drdrs[0].status == 6"> {{ moment(drdrs[0].disapproved_date).format('LL') }} </td>
                    </tr>	
                </tbody>
            </table>

            <table class="table" width="100%">
                <tbody>
                    <tr>
                        <td>Considered Docments in reviewing:</td>
                    </tr>
                    <tr>
                        <td>{{ drdrs[0].consider_documents }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- step 3 -->
            <em> <h4 style="margin-bottom: 20px; font-weight: bold;"> Step 3: Approval of request </h4> </em>

            <!-- step 4 -->
            <em> <h4 style="margin-bottom: 20px; font-weight: bold;"> Step 4: Control and distribution </h4> </em>

            <div class="row">
                <div class="col-md-8" style="padding-right: 0 ! important; margin-right: 0 ! important">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Copy No.</td>
                                <td>Copyholder (Department)</td>
                            </tr>
                        </thead>
                        <tbody v-if="drdrs[0].drdr_copyholders">
                            <tr v-for="(drdr, d) in drdrs[0].drdr_copyholders" v-bind:key="d">
                                <td> {{ drdr.copy_number }} </td>
                                <td> {{ drdr.copy_holder }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- end col-md-8 -->

                <div class="col-md-4" style="padding-left: 0 ! important;">
                    <table class="table table-bordered" style="border-left: 0 ! important;">
                        <thead>
                            <tr>
                                <td style="border-left: 0 ! important;">Effective Date: {{ moment(drdrs[0].effective_date).format('LL') }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td v-if="drdrs[0].drdr_no" style="border-left: 0 ! important;"> DRDR NO:  {{ drdrs[0].drdr_no }} </td>
                                <td v-else style="border-left: 0 ! important;"> DRDR NO:  </td>
                            </tr>
                            <tr>
                                <td style="border-left: 0 ! important;"> Document Title: {{ drdrs[0].document_title }} </td>
                            </tr>
                            <tr>
                                <td v-if="drdrs[0].document_code" style="border-left: 0 ! important;"> Document Code: {{ drdrs[0].document_code }} </td>
                                <td v-else style="border-left: 0 ! important;"> Document Code:  </td>
                            </tr>
                            <tr>
                                <td style="border-left: 0 ! important;"> Revision No: {{ drdrs[0].rev_number }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- step 5 -->
            <em>
                <h4 style="margin-bottom: 20px;">
                    <strong>Step 5</strong> (if applicable) QM marks disposition on obsolete copies.
                </h4>
            </em>

            <p> Red Stamp with “Obsolete” and use as reference. </p>

            <table></table>

            <table width="100%" style="margin-bottom: 100px;">
                <tbody>
                    <tr>
                        <td><strong> Verified by: </strong></td>
                        <td v-if="drdrs[0].distributed"> {{ drdrs[0].distributed.name }} </td>
                        <td><strong >Date: </strong></td>
                        <td v-if="drdrs[0].distributed">  {{ moment(drdrs[0].distributed_date).format('LL') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props:['drdrId'],
    data(){
        return{
            drdrs: [],
            requesterAttachments: [],
            reviewerAttachments: [],
            approverAttachments: [],
            selectedAttachment: ' ',
            errors: ''
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
