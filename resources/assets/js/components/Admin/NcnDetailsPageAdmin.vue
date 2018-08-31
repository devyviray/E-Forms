<template>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <a href="http://172.17.2.88/e-forms-test/storage/app/ncnforms/5a0672b643282b2d3cb3a47d6c6aba8d.docx" class="btn btn-primary" download="">  Download Attachement </a>
            <a :href="hrefLink" target="_blank" class="btn btn-primary"> Print as PDF </a> 
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
                    <tr></tr>
                </tbody>
            </table>

            <!-- step 1 -->
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td> <strong> COMPANY: </strong> </td>
                        <td colspan="2"> Mama Tina Pasta Company Inc. </td>
                    </tr>
                    <tr>
                        <td> <strong> DIVISION / DEPARTMENT: </strong> </td>
                        <td colspan="2"> Corporate Quality Assurance- Quality Control Section </td>
                    </tr>
                    <tr>
                        <td colspan="3"> <strong> TYPE OF NON CONFORMITY: </strong> </td>
                    </tr>

                    <tr>
                        <td>
                            <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Customer-returns 				
                        </td>
                        <td>		
                            <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Process-related 				
                        </td>
                        <td>
                            <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Contracted-service 					
                        </td>
                    </tr>
                    <tr>
                        <td>		
                            <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Objectives not met 					
                        </td>
                        <td>
                            <i class="ion-android-checkbox-outline" style="font-weight: bold; font-size: 20px;"></i>  Vendor					
                        </td>
                        <td>
                            <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i> Others:					
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Notification No:</td>
                        <td>
                            <strong></strong>
                        </td>
                        <td>Issued by:</td>
                        <td>
                            <strong>
                                Geraldine Urriza
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Recurrence No:</td>
                        <td> <strong> </strong> </td>
                        <td>Position:</td>
                        <td> <strong>  QA </strong> </td>
                    </tr>
                    <tr>
                        <td>Date of Issuance:</td>
                        <td> <strong> May 22, 2017 </strong></td>
                        <td>Notified Person:</td>
                        <td><strong> Geraldine Urriza</strong></td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="4">
                            <strong> Step 1: Provide details of non-conformity: </strong>
                        </td>	
                    </tr>
                    <tr>
                        <td colspan="4">
                            Upon cleaning of blowthrough sieve last April 19 2017 we haved retrieve  pieces of silicon, brown paper and black foreign material.
                            please see deliveries attached.
                        </td>
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
                        <td> April 26, 2017	</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props:['ncnId'],
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
            axios.get()
            .then(response => { 
                this.ncns = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        }
    },
    computed:{
        hrefLink()
        {
            var base_url = window.location.origin;
            var url = base_url+`/admin/ncn-pdf/${this.ncnId}`;

            return url;
        }
    }
}
</script>
