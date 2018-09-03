<template>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <!-- <a href="http://172.17.2.88/e-forms-test/storage/app/" class="btn btn-primary" download=""> Download Attachement - Reviewer</a> -->
            <select class="form-control form-control-lg">
                <option selected disabled> Download Attachment - Reviewer </option>
                <option v-for="(attachment, a) in reviewerAttachments" :value="attachment.id" v-bind:key="a">{{ attachment.file_name }}</option>
            </select>
        
            <a href="http://172.17.2.88/e-forms-test/storage/app/" class="btn btn-primary" download=""> Download Attachement - Approver</a>
            <!-- <a href="http://172.17.2.88/e-forms-test/public/drdrforms/pdf/2678" target="_blank" class="btn btn-primary">Print as PDF</a> -->
            <a :href="hrefLink" target="_blank" class="btn btn-primary">Print as PDF</a>

            <hr>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td rowspan="3" width="10%">
                            <img class="img-responsive" src="http://172.17.2.88/e-forms-test/public/image/lfug-logo.png" style="padding: 10px; width: 100px; height: auto;">
                        </td>
                        <td colspan="4">La Filipina Uy Gongco Group of Companies</td>
                    </tr>
                    <tr>
                        <td> Doc No. <strong> LFQM-F-001 </strong> </td>
                        <td v-if="drdrs.length"> Rev No. <strong> {{ drdrs[0].rev_number }} </strong> </td>
                        <td> Effective Date </td>
                        <td v-if="drdrs.length"> Effective Date: <strong> {{ drdrs[0].effective_date }} </strong></td>
                    </tr>
                    <tr>
                        <td colspan="5"> DOCUMENT REVIEW and DISTRIBUTION REQUEST </td>
                    </tr>
                </tbody>
            </table>

            <!-- step 1 -->
            <em> <h4 style="margin-bottom: 20px;"> <strong>Step 1</strong> Request fills out necessary details. </h4> </em>

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
                        <td colspan="6"> <strong> Reason for proposal / change / cancellation: </strong> </td>
                    </tr>
                    <tr>
                        <td colspan="7" v-if="drdrs.length"> {{ drdrs[0].reason_request }} </td>
                    </tr>

                    <!-- third set -->
                    <tr>
                        <td> <strong> Requested By: </strong> </td>
                        <td v-if="drdrs.length"> {{ drdrs[0].requester.name }}</td>
                        <td> <strong> Position: </strong> </td>
                        <td v-if="drdrs.length"> {{ drdrs[0].position }} </td>
                        <td><strong>Date:</strong></td>
                        <td v-if="drdrs.length"> {{ drdrs[0].date_request }}</td>
                    </tr>
                </tbody>
            </table>
            <!-- table end -->

            <!-- step 2 -->
            <em>
                <h4 style="margin-bottom: 20px;">
                    <strong>Step 2</strong> 
                    Requester obtains approval from reviewer and approver. If requester is also the reviewer or approver, disregard this step and proceed to step 3. 
                </h4>
            </em>

            <table class="table" width="100%">
                <tbody>
                    <tr>
                        <td> <strong> Reviewed By: </strong> </td>
                        <td  v-if="drdrs.length"> {{ drdrs[0].reviewer.name }} </td>
                        <td> <strong> Position:</strong> </td>
                        <td  v-if="drdrs.length"> {{ drdrs[0].reviewer.position }}  </td>
                        <td> <strong> Date: </strong> </td>
                        <td v-if="drdrs.length" > {{ drdrs[0].reviewed_date }}  </td>
                    </tr>

                    <tr>
                        <td> <strong> Approved By: </strong> </td>
                        <td v-if="drdrs.length" >{{ drdrs[0].approver.name }} </td>
                        <td> <strong> Position: </strong></td>
                        <td v-if="drdrs.length"> {{ drdrs[0].approver.position }}</td>
                        <td> <strong> Date: </strong> </td>
                        <td v-if="drdrs.length"> {{ drdrs[0].approved_date }}  </td>
                    </tr>	
                </tbody>
            </table>

            <table class="table" width="100%">
                <tbody>
                    <tr>
                        <td>Considered Docments in reviewing:</td>
                    </tr>
                    <tr>
                        <td> ok </td>
                    </tr>
                </tbody>
            </table>

            <!-- step 3 -->
            <em>
                <h4 style="margin-bottom: 20px;">
                    <strong>Step 3</strong> 
                    Approver defines copyholder and effective date. Requester submits this form to QM with the final draft  <small> (*required attachment: draft of new/ revised document with highlights on changes made).</small>
                </h4>
            </em>

            <!-- step 4 -->
            <em>
                <h4 style="margin-bottom: 20px;">
                    <strong>Step 4</strong> 
                    QM to compare to previous distribution (if any), fill out required data and distribute prior effective date acknowledged by the area document controller, dept. head or supervisor.
                </h4>
            </em>

            <div class="row">
                <div class="col-md-8" style="padding-right: 0 ! important; margin-right: 0 ! important">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Copy No.</td>
                                <td>Copyholder (Department)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> 1</td>
                                <td> QM </td>
                            </tr>
                            <tr>
                                <td> 2 </td>
                                <td> FOOD SAFETY TEAM </td>
                            </tr>
                            <tr>
                                <td> 3 </td>
                                <td> FEEDMILL</td>
                            </tr>
                            <tr>
                                <td> 4</td>
                                <td> SPC </td>
                            </tr>
                            <tr>
                                <td> 5 </td>
                                <td> ADMIN </td>
                            </tr>
                            <tr>
                                <td> 6 </td>
                                <td> QC </td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- end col-md-8 -->

                <div class="col-md-4" style="padding-left: 0 ! important;">
                    <table class="table table-bordered" style="border-left: 0 ! important;">
                        <thead>
                            <tr>
                                <td style="border-left: 0 ! important;">Effective Date: </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border-left: 0 ! important;"> August 03, 2018 </td>
                            </tr>
                            <tr>
                                <td style="border-left: 0 ! important;"> DRDR NO: PFMC-I-08-2018-2678 </td>
                            </tr>
                            <tr>
                                <td style="border-left: 0 ! important;"> Document Title: Master Sanitation Plan - Feed Mill </td>
                            </tr>
                            <tr>
                                <td style="border-left: 0 ! important;"> Document Code: PRP-003-002c.1FD </td>
                            </tr>
                            <tr>
                                <td style="border-left: 0 ! important;"> Revision No: 11 </td>
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

            <p> Yellow stamp with "Obsolete" and use as reference </p>

            <table></table>

            <table width="100%" style="margin-bottom: 100px;">
                <tbody>
                    <tr>
                        <td>Verified by:</td>
                        <td> Cherry Ann Siscar </td>
                        <td>Date:</td>
                        <td> August 03, 2018 </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props:['drdrId'],
    data(){
        return{
            drdrs: [],
            reviewerAttachments: [],
            errors: ''
        }
    },
    created(){
        this.fetchDrdrs();
    },
    methods:{
        fetchDrdrs()
        {
            axios.get(`/drdr-data/${this.drdrId}`)
            .then(response => { 
                this.drdrs = response.data;
                this.fetchUploadedFilesReviewer();
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
    },
    computed:{
        hrefLink()
        {
            var base_url = window.location.origin;
            var url = base_url+`/admin/drdr-pdf/${this.drdrId}`;

            return url;
        }
    }
}
</script>
