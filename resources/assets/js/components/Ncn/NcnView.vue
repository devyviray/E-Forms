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
                        <td> Doc No. <strong>LFQM-F-019</strong> </td>
                        <td> Rev No. <strong>02</strong> </td>
                        <td> Effective Date </td>
                        <td> June 22, 2016 </td>
                    </tr>
                    <tr> </tr>
                </tbody>
            </table>

            <!-- step 1 -->
            <table class="table table-bordered">
                <tbody>
                    <tr> 
                        <td> <strong> COMPANY: </strong> </td>
                        <td colspan="2" v-if="ncns.length"> <strong> {{ ncns[0].company.name }} </strong> </td>
                    </tr>
                    <tr>
                        <td> <strong> DIVISION / DEPARTMENT: </strong> </td> 
                        <td colspan="2" v-if="ncns.length"> <strong> {{ ncns[0].department.name }} </strong> </td>
                    </tr>
                    <tr>
                        <td colspan="3"> <strong> TYPE OF NON CONFORMITY: </strong> </td>
                    </tr>
                    <tr>
                        <td> <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Customer-returns  </td>
                        <td> <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Process-related  </td>
                        <td> <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Contracted-service </td>		
                    </tr>
                    <tr>  
                        <td> <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Objectives not met</td>
                        <td> <i class="ion-android-checkbox-outline" style="font-weight: bold; font-size: 20px;"></i>  Vendor </td>	
                        <td> <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i> Others:</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Notification No:</td>
                        <td v-if="ncns.length"> <strong> {{ ncns[0].notification_number }} </strong> </td>
                        <td>Issued by:</td>
                        <td v-if="ncns.length"> <strong> {{ ncns[0].requester.name }} </strong> </td>
                    </tr>
                    <tr>
                        <td>Recurrence No:</td>
                        <td  v-if="ncns.length"> <strong> {{ ncns[0].recurrence_number }} </strong> </td>
                        <td>Position:</td>
                        <td> <strong> QA </strong> </td>
                    </tr>
                    <tr>
                        <td>Date of Issuance:</td>
                        <td v-if="ncns.length"> <strong>{{ ncns[0].issuance_date }} </strong> </td>
                        <td>Notified Person:</td>
                        <td> <strong> {{ ncns[0].notified.name }} </strong> </td>
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
                        <td colspan="4"> Forwarded to QM dated 26 April 2017 for CAR completion </td>
                    </tr>
                    <tr>
                        <td>Responsible:</td>
                        <td> Cheryl Calipes </td>
                        <td>Execution Date:</td>
                        <td> April 26, 2017 </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
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

