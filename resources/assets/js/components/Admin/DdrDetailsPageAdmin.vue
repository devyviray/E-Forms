<template>
    <div id="page-content-wrapper">
        <spinner-loading v-if="isLoading"></spinner-loading>
        <div class="container-fluid" v-if="ddrs.length">          
            <a v-if="ddrs[0].status == 4" href="javascript:void(0)"  @click="getDdrId(ddrs[0].id)" data-toggle="modal" data-target="#distributedDdrModal" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));"> Mark As Distributed </a> 
            <a :href="hrefLink" target="_blank" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));"> Print as PDF </a> 
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
                        <td> <strong> Doc No. </strong> LFQM-F-002 </td>
                        <td><strong>  Rev No. </strong>  03 </td>
                        <td><strong> Effective Date: </strong>February 27, 2017 </td>
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
            		<tr>
						<td > <strong> Reason for distribution: </strong> </td>
                        <td v-if="ddrs[0].reason_of_distribution == 1">
							 Relevant external doc. (controlled copy)
						</td>
						<td v-if="ddrs[0].reason_of_distribution == 2">
							 Customer request (uncontrolled copy)			
						</td>
						<td v-if="ddrs[0].reason_of_distribution == 3"> Others: {{ ddrs[0].others }}</td>
					</tr>	
					<tr>
						<td> <strong> Date Neeeded: </strong> </td>
						<td>{{ moment(ddrs[0].date_needed).format('LL') }}</td>
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
                        <th><strong>Document Title</strong></th>
                        <th><strong>Control Code</strong></th>
                        <th><strong>Rev No.</strong></th>
                        <th><strong>Copy No.</strong></th>
                        <th><strong>Copy holder</strong></th>
                    </tr>
                </thead>
                <tbody v-if="ddrs.length">
                        <tr v-for="(ddr, d) in ddrs[0].ddr_lists" v-bind:key="d">
                            <td> {{ ddr.document_title }} </td>
                            <td> {{ ddr.control_code }} </td>
                            <td> {{ ddr.rev_number }} </td>
                            <td> {{ ddr.copy_number }} </td>
                            <td> {{ ddr.copy_holder }} </td>
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
                        <td v-if="ddrs.length"> {{ ddrs[0].requester.name }} </td>
                        <td> <strong> Approved by: </strong> </td>
                        <td v-if="ddrs.length"> 
                            {{ ddrs[0].approver.name }}<br>
                            <span style="color: red" v-if="ddrs[0].status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ddrs[0].status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
                         </td>
                        <td> <strong> Distributed by: </strong> </td>
                        <td v-if="ddrs.length && ddrs[0].distributed"> {{ ddrs[0].distributed.name }}  </td>
                    </tr>

                    <tr>
                        <td> <strong> Position:  <strong> </strong></strong></td>
                        <td v-if="ddrs.length"> {{ ddrs[0].requester.position }} </td>
                        <td> <strong> Position: </strong> </td>
                        <td v-if="ddrs.length"> {{ ddrs[0].approver.position }} </td>
                        <td> <strong> Position: </strong></td>
                        <td v-if="ddrs.length && ddrs[0].distributed"> {{ ddrs[0].distributed.position }} </td>
                    </tr>

                    <tr>
                        <td> <strong>Date </strong> </td>
                        <td v-if="ddrs.length">{{ moment(ddrs[0].date_request).format('LL') }}</td>
                        <td> <strong> Date </strong> </td>
						<td v-if="ddrs.length && ddrs[0].status == 6"> {{ moment(ddrs[0].dispproved_date).format('LL') }} </td>
                        <td v-else-if="ddrs.length && ddrs[0].status != 6  && ddrs[0].status != 2"> {{ moment(ddrs[0].approved_date).format('LL') }} </td>
                        <td v-else>  </td>
                        <td> <strong> Date </strong> </td>
                        <td v-if="ddrs.length && ddrs[0].distributed"> {{ moment(ddrs[0].distributed_date).format('LL') }} </td>
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
          <!-- Mark as distributed Modal -->
        <div  class="modal fade" id="distributedDdrModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel">Mark as distributed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>   
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" placeholder="Id" v-model="selected_id">
                    <div class="form-group">
                        <span> Are you sure to mark this document as distributed?</span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default btn-round btn-fill" data-dismiss="modal">Close</button>
                <button @click="distributeDdr(selected_id)" type="button" class="hidden-xs btn btn-new btn-wd btn-neutral btn-round" style=" background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));">Save</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import SpinnerLoading from '../SpinnerLoading';

export default {
    components:{
        SpinnerLoading
    },
    props:['ddrId'],
    data(){
        return{
            ddrs: [],
            errors: '',
            selected_id:'',
            isLoading: false
        }
    },
    created(){
        this.fetchDdrs();
    },
    methods:{
        moment,
        fetchDdrs()
        {
            axios.get(`/ddr-data/${this.ddrId}`)
            .then(response => { 
                this.ddrs = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        getDdrId(id)
        {
            this.selected_id = id;
        },
        distributeDdr(id){
            $('#distributedDdrModal').modal('hide');
            this.isLoading = true;
            axios.post('/admin/ddr-distributed', { 
                'id': id
            })
            .then(response=> {
                this.isLoading = false;
                this.$toast.success({
                    title:'SUCCESS',
                    message:'DRDR Succesfully Distributed',
                    position: 'top right'
                });

                this.selected_id = '';
                location.reload();
            })
            .catch(error => {
                this.isLoading = false; 
                this.errors = response.data.errors;
            })
        },
    },
    computed:{
        hrefLink()
        {
            var base_url = window.location.origin;
            var url = base_url+`/admin/ddr-pdf/${this.ddrId}`;

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

