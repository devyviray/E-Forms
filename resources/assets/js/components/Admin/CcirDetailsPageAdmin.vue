<template>
    <div id="page-content-wrapper">
        <div class="container-fluid">
             <select class="" v-model="selectedAttachment" @change="downloadAttachment">
                <option selected disabled> Download Attachment - Requester </option>
                <option v-for="(requesterAttachment, r) in requesterAttachments" :value="requesterAttachment.id" v-bind:key="r">{{ requesterAttachment.file_name }}</option>
            </select>

            <select class="" v-model="selectedAttachment" @change="downloadAttachment">
                <option selected disabled> Download Attachment - Verifier </option>
                <option v-for="(verifierAttachment, a) in verifierAttachments" :value="verifierAttachment.id" v-bind:key="a">{{ verifierAttachment.file_name }}</option>
            </select>
        <a :href="hrefLink" target="_blank" class="btn btn-primary"> Print as PDF </a> 
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
                    <td>  Doc No. <strong>LFQM-F-029b</strong> </td>
                    <td> Rev No. <strong>00</strong> </td>
                    <td> Effective Date </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5"> CUSTOMER COMPLAINT REPORT</td>
                </tr>
            </tbody>
        </table>

        <!-- step 2 -->
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="7"> <h4> <strong> Step: 1 </strong> </h4> </td>
                </tr>
                <tr>
                    <td>Issuer:</td>
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <td>Date of Issuance:</td>
                    <td colspan="6"> April 12 2017 </td>
                </tr>
                <tr>
                    <td>Brand Name:</td>
                    <td colspan="6" v-if="ccirs.length"> {{ ccirs[0].brand_name }} </td>
                </tr>
                <tr>
                    <td>Affected Quantities</td>
                    <td colspan="6" v-if="ccirs.length"> {{ ccirs[0].affected_quantity }}  </td>
                </tr>
                <tr>
                    <td>Product Control No</td>
                    <td colspan="2" v-if="ccirs.length"> {{ ccirs[0].product_control_number }} </td>
                    <td>Delivery Date</td>
                    <td colspan="3" v-if="ccirs.length"> {{ ccirs[0].delivery_date }}  </td>
                </tr>
                <tr>
                    <td>Quantity of sample</td>
                    <td colspan="2" v-if="ccirs.length">{{ ccirs[0].quality_of_sample }}  </td>
                    <td>Return Date</td>
                    <td colspan="3" v-if="ccirs.length"> {{ ccirs[0].returned_date }}   </td>
                </tr>
                <tr>
                    <td rowspan="3">Nature of Complaint</td>
                    <td>Wet/Lumpy</td>
                    <td></td>
                    <td>Busted Bag</td>
                    <td></td>
                    <td>Under / Over Weight</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Infested</td>
                    <td> </td>
                    <td>Dirty Packaging</td>
                    <td></td>
                    <td>Others</td>
                    <td> <i class="ion-checkmark-round" style="color: green;"></i> </td>
                </tr>
                <tr>
                    <td>For Other please specify:</td>
                    <td colspan="5"> quality problem </td>
                </tr>
                <tr>
                    <td> Other Details:<br> </td>
                    <td colspan="6" v-if="ccirs.length"> {{ ccirs[0].other_details }} </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td width="50%">Prepared By:</td>
                    <td>Cheryl Calipes </td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
</template>


<script>
export default {
    props:['ccirId'],
    data(){
        return{
            ccirs: [],
            selectedAttachment: '',
            verifierAttachments:[],
            requesterAttachments: [],
            errors: ''
        }
    },
    created(){
        this.fetchCcirs();
    },
    methods:{
        fetchCcirs()
        {
            axios.get(`/ccir-data/${this.ccirId}`)
            .then(response => { 
                this.ccirs = response.data;
                this.fetchUploadedFilesRequester();
                this.fetchUploadedFilesVerifier();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        fetchUploadedFilesRequester()
        {
            axios.get('/ccir-requester-attachments/'+this.ccirs[0].id+'/'+this.ccirs[0].requester_id)
            .then(response => {
                this.requesterAttachments = response.data;
            })
            .catch(error => {
              this.errors = error.response.data.errors;
            })  
        },
        fetchUploadedFilesVerifier()
        {
            axios.get('/ccir-verifier-attachments/'+this.ccirs[0].id+'/'+this.ccirs[0].verifier_id)
            .then(response => {
                this.verifierAttachments = response.data;
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
            var url = base_url+`/admin/ccir-pdf/${this.ccirId}`;

            return url;
        }
    }
}
</script>

