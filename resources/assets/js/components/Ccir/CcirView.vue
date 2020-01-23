<template>
    <div id="page-content-wrapper">
        <div class="container-fluid">
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
                    <td><strong>Complainant:</strong></td>
                    <td colspan="6" v-if="ccirs.length"> {{ ccirs[0].complainant }} </td>
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
                    <td colspan="6" v-if="ccirs[0].nature_of_complaint == 6">{{ ccirs[0].others }} </td>
                </tr>
                <tr>
                    <td><strong>Other Details:</strong></td>
                    <td colspan="6" v-if="ccirs.length"> {{ ccirs[0].other_details }} </td>
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
    data(){
        return{
            ccirs: [],
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
            var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];

            axios.get(`/ccir-data/${id}`)
            .then(response => { 
                this.ccirs = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        }
        
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

