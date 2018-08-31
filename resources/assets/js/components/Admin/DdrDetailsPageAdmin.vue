<template>
    <div id="page-content-wrapper">
        <div class="container-fluid">
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
                        <td> Doc No. <strong>LFQM-F-002</strong> </td>
                        <td> Rev No. <strong>03</strong> </td>
                        <td> Effective Date </td>
                        <td> February 27, 2017</td>
                    </tr>
                    <tr>
                        <td colspan="5"> DOCUMENT DISTRIBUTION REQUEST </td>
                    </tr>
                </tbody>
            </table>

            <!-- step 1 -->
            <em>
                <h4>
                    <strong>Step 1:</strong> Requester defines reason for distribution and required date of distribution.
                </h4>
            </em>

            <table class="table table-bordered">
                <tbody>
                    <tr> <td rowspan="4"> Reason for distribution:</td> </tr>
                    <tr>
                        <td colspan="2">
                            <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Relevant external doc. (controll copy)
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <i class="ion-android-checkbox-outline-blank" style="font-weight: bold; font-size: 20px;"></i>  Customer request (uncontrolled copy)
                        </td>
                    </tr>
                    <tr>
                        <td> <strong> Others: </strong> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <strong> Date Neeeded: </strong>  </td>
                        <td colspan="2"> April 12, 2017 </td>
                    </tr>
                </tbody>
            </table>

            <!-- step 2 -->
            <em>
                <h4>
                    <strong>Step 2:</strong>
                    Requester fills out columns with asterisk, obtains approval from department head and 
                    submits to DC (Together with orignal and photocopoies of the external document).
                </h4>
            </em>

            <!-- step 2 -->
            <em>
            <h4>
                <strong>Step 3:</strong>
                DC distributes base on defined copyholder and signs below.
            </h4>
            </em>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Document Title</th>
                        <th>Control Code</th>
                        <th>Rev No.</th>
                        <th>Copy No.</th>
                        <th>Copy holder</th>
                        <th>Received by:</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td> La Fil Pasta Flour </td>
                            <td> 00 </td>
                            <td> 00 </td>
                            <td> 00 </td>
                            <td> R&amp;D </td>
                            <td></td>
                            <td> April 12, 2017 </td>
                        </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="info" colspan="6">
                            <em> <strong> Total number of obsolete copy retrieved </strong> </em>
                        </td>
                    </tr>
                    <tr>
                        <td> <strong> Requested by:  <strong> </strong></strong> </td>
                        <td> Kim Alejo </td>
                        <td> <strong> Approved by: </strong> </td>
                        <td> Roc Cañares </td>
                        <td> <strong> Distributed by: </strong> </td>
                        <td> Michelle Paje </td>
                    </tr>

                    <tr>
                        <td> <strong> Position:  <strong> </strong></strong></td>
                        <td> R&amp;D Assistant </td>
                        <td> <strong> Position: </strong> </td>
                        <td> R&amp;D Manager </td>
                        <td> <strong> Position: </strong></td>
                        <td> Management Representative Supervisor </td>
                    </tr>

                    <tr>
                        <td> <strong>Date </strong> </td>
                        <td>April 12, 2017</td>
                        <td> <strong> Date </strong> </td>
                        <td> April 12, 2017	</td>
                        <td> <strong> Date </strong> </td>
                        <td> July 26, 2017 </td>
                    </tr>
                </tbody>
            </table>

            <p> <small> Disclaimer for uncrolled document: </small> </p>

            <p><small></small></p>

            <ul>
                <small>
                    <li>Documents are issued as requested and shall not be used for any other purpose</li>
                    <li>
                    No Part of the document may be reproduced or utilized in any form, electronic or mechanical, including photocopying without written permission to the QM Document Controller.
                    </li>
                </small>
            </ul>
            <small></small>
            <p></p>
        </div>
    </div>
</template>

<script>
export default {
    props:['ddrId'],
    data(){
        return{
            ddrs: [],
            errors: ''
        }
    },
    created(){
        this.fetchDdrs();
    },
    methods:{
        fetchDdrs()
        {
            axios.get()
            .then(response => { 
                this.ddrs = response.data;
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
            var url = base_url+`/admin/ddr-pdf/${this.ddrId}`;

            return url;
        }
    }
}
</script>

