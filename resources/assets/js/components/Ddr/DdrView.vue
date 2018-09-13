<template>
	<div id="page-content-wrapper">
		<div class="container-fluid">
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
					</tr>
					<tr>
						<td colspan="5"> DOCUMENT DISTRIBUTION REQUEST </td>
					</tr>
				</tbody>
			</table>

			<!-- step 1 -->
			<em>
				<h4>
					<strong>Step 1:</strong>
					Requester defines reason for distribution and required date of distribution.
				</h4>
			</em>

			<table class="table table-bordered">
				<tbody>
					<tr>
						<td> <strong> Reason for distribution: </strong> </td>
						<td v-if="ddrs[0].reason_of_distribution == 1">
							 Relevant external doc. (controll copy)
						</td>
						<td v-if="ddrs[0].reason_of_distribution == 2">
							 Customer request (uncontrolled copy)			
						</td>
						<td v-if="ddrs[0].reason_of_distribution == 3"> Others: </td>
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
						<th><strong>Document Title </strong></th>
						<th><strong> Control Code </strong></th>
						<th><strong> Rev No. </strong></th>
						<th><strong> Copy No. </strong></th>
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
							<em> <strong> Total number of obsolete copy retrieved: </strong> </em>
						</td>
					</tr>
					<tr>
						<td> <strong> Requested by: <strong> </strong></strong></td>
						<td v-if="ddrs.length"> {{ ddrs[0].requester.name }} </td>
						<td> <strong> Approved by: </strong> </td>
						<td v-if="ddrs.length"> 
							{{ ddrs[0].approver.name }}<br>
							<span style="color: red" v-if="ddrs[0].status == 2"> NOT YET APPROVED </span>
                            <span style="color: red" v-else-if="ddrs[0].status == 6"> DISAPPROVED </span>
                            <span style="color: green" v-else> APPROVED </span>
						</td>
						<td> <strong> Distributed by: </strong> </td>
						<td v-if="ddrs.length && ddrs[0].distributed"> {{ ddrs[0].distributed.name }} </td>
					</tr>
					<tr>
						<td> <strong> Position: <strong> </strong></strong></td>
						<td v-if="ddrs.length"> {{ ddrs[0].requester.position }} </td>
						<td> <strong> Position: </strong> </td>
						<td v-if="ddrs.length"> {{ ddrs[0].approver.position }} </td>
						<td> <strong> Position: </strong> </td>
						<td v-if="ddrs.length && ddrs[0].distributed"> {{ ddrs[0].distributed.position }}</td>
					</tr>
					<tr>
						<td> <strong> Date: </strong> </td>
						<td v-if="ddrs.length">{{ moment(ddrs[0].date_request).format('LL') }}</td>
						<td> <strong> Date: </strong> </td>
						<td v-if="ddrs.length && ddrs[0].status == 6"> {{ moment(ddrs[0].dispproved_date).format('LL') }} </td>
						<td v-else> {{ moment(ddrs[0].approved_date).format('LL') }} </td>
						<td> <strong> Date: </strong> </td>
						<td v-if="ddrs.length && ddrs[0].distributed">{{ moment(ddrs[0].distributed_date).format('LL') }} </td>
					</tr>
				</tbody>
			</table>

			<p> <small> Disclaimer for uncrolled document:</small> </p>
			<p> <small> </small></p>
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
import moment from 'moment';
export default {
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
		moment,
        fetchDdrs()
        {
			var url = window.location.href;
            var id = url.match(/[^\/]+$/)[0];

            axios.get(`/ddr-data/${id}`)
            .then(response => { 
                this.ddrs = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        }
    }
}
</script>
