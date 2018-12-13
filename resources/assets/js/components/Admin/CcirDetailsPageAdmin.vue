<template>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <label for="requeterAttachment"> Download Attachment - Requester </label>
                    <select class="form-control" v-model="selectedAttachment" @change="downloadAttachment">
                        <option selected disabled> Download Attachment - Requester </option>
                        <option v-for="(requesterAttachment, r) in requesterAttachments" :value="requesterAttachment.id" v-bind:key="r">{{ requesterAttachment.file_name }}</option>
                    </select>
                </div>
                <div class="col-md-3" style="margin-top: 29px">
                     <a :href="hrefLink" target="_blank" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));"> Print as PDF </a> 
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3"></div>
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
                    <td> <strong> Doc No. </strong> LFQM-F-029b </td>
                    <td><strong>  Rev No. </strong>  00 </td>
                    <td colspan="3"><strong> Effective Date: </strong>February 25, 2017 </td>
                </tr>
                <tr>
                    <td colspan="5"> CUSTOMER COMPLAINT INVESTIGATION REPORT</td>
                </tr>
            </tbody>
        </table>

        <!-- step 2 -->
        <table class="table table-bordered" v-if="ccirs.length">
            <tbody>
                <tr>
                    <td colspan="7"> <h4> <strong> Step: 1 </strong> </h4> </td>
                </tr>
                <tr>
                    <td><strong>Issuer:</strong></td>
                    <td colspan="6" v-if="ccirs.length"> {{ ccirs[0].requester.name }} </td>
                </tr>
                <tr>
                    <td><strong>Date of Issuance:</strong></td>
                    <td colspan="6">{{ moment(ccirs[0].date_request).format('LL') }} </td>
                </tr>
                <tr>
                    <td><strong>Commodity:</strong></td>
                    <td colspan="6" v-if="ccirs.length"> {{ ccirs[0].commodity }} </td>
                </tr>
                <tr>
                    <td><strong>Affected Quantities</strong></td>
                    <td colspan="6" v-if="ccirs.length"> {{ ccirs[0].affected_quantity }}  </td>
                </tr>
                <tr>
                    <td><strong>Product Control No</strong></td>
                    <td colspan="6" v-if="ccirs.length"> {{ ccirs[0].product_control_number }} </td>
                </tr>
                <tr>
                    <td><strong>Delivery Date</strong></td>
                    <td colspan="6" v-if="ccirs.length">{{ moment(ccirs[0].delivery_date).format('LL') }}</td>
                </tr>
                <tr>
                    <td><strong>Quantity of sample</strong></td>
                    <td colspan="6" v-if="ccirs.length">{{ ccirs[0].quantity_of_sample }}  </td>
                </tr>
                <tr>
                    <td><strong>Return Date</strong></td> 
                    <td colspan="6" v-if="ccirs.length && ccirs[0].returned_date">{{ moment(ccirs[0].returned_date).format('LL') }}</td>
                    <td colspan="6" v-else></td>
                </tr>
                <tr>
                    <td><strong>Nature of Complaint</strong></td>
                    <td colspan="6" v-if="ccirs[0].nature_of_complaint == 1">Wet/Lumpy</td>
                    <td colspan="6" v-if="ccirs[0].nature_of_complaint == 2">Busted Bag</td>
                    <td colspan="6" v-if="ccirs[0].nature_of_complaint == 3">Under / Over Weight</td>
                    <td colspan="6" v-if="ccirs[0].nature_of_complaint == 4">Infestation</td>
                    <td colspan="6" v-if="ccirs[0].nature_of_complaint == 5">Dirty Packaging</td>
                    <td colspan="6" v-if="ccirs[0].nature_of_complaint == 6">{{ ccirs[0].others }}</td>
                </tr>
                <tr v-if="ccirs[0].verifier">
                    <td> <strong> Verified By: </strong></td>
                    <td colspan="6"> {{ ccirs[0].verifier.name }} </td>
                </tr>
            </tbody>
        </table>

        </div>
    </div>
</template>


<script>
import moment from 'moment';
export default {
    props:['ccirId'],
    data(){
        return{
            ccirs: [],
            selectedAttachment: '',
            requesterAttachments: [],
            errors: ''
        }
    },
    created(){
        this.fetchCcirs();
    },
    methods:{
        moment,
        fetchCcirs()
        {
            axios.get(`/ccir-data/${this.ccirId}`)
            .then(response => { 
                this.ccirs = response.data;
                this.fetchUploadedFilesRequester();
                this.fetchUploadedFilesVerifier();
            })
            .catch(error => {
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
        },
        logo(){
            var base_url = window.location.origin;
            var url = base_url+'/img/lfug-logo.png';

            return url;
        }
    }
}
</script>

