<template>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <hr>
            NON CONFORMANCE NOTIFICATION
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td rowspan="3" width="10%">
                            <img class="img-responsive" src="http://172.17.2.88/e-forms-test/public/image/lfug-logo.png" style="padding: 10px; width: 100px; height: auto;">
                        </td>
                        <td colspan="4">La Filipina Uy Gongco Group of Companies</td>
                    </tr>
                    <tr>
                        <td><strong>Doc No.</strong> LFQM-F-019 </td>
                    </tr>
                    <tr> </tr>
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
                        <td v-if="ncns[0].non_conformity_types == 1">Customer-returns</td>
                        <td v-else-if="ncns[0].non_conformity_types == 2">Process-related</td>
                        <td v-else-if="ncns[0].non_conformity_types == 3">Contracted-service</td>
                        <td v-else-if="ncns[0].non_conformity_types == 4">Objectives not met</td>
                        <td v-else-if="ncns[0].non_conformity_types == 5">Vendor</td>
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
                        <td v-if="ncns.length">{{ ncns[0].notified.name }} </td>
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
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        }
    }
}
</script>

