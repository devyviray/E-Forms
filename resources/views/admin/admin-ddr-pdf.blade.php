<!DOCTYPE html>
<html>
<head>
	<title>PDF Download</title>
	<style>
		table {
			width:100%;
		}
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		th, td {
			padding: 15px;
			text-align: left;
		}
		table#t01 tr:nth-child(even) {
			background-color: #eee;
		}
		table#t01 tr:nth-child(odd) {
		background-color: #fff;	
		}
		table#t01 th {
			background-color: black;
			color: white;
		}
	</style>
</head>
<body>

	<table style="margin-bottom:10px">
		<tr>
			<td rowspan="3">
				<img class="logo-logo" src="http://172.17.2.88/e-forms-test/public/image/lfug-logo.png" 
				style="display:block;  width: 60px; height: auto; padding: 0; margin: 10px 10px 0 30px;">
			</td>
			<td colspan="5" >La Filipina Uy Gongco Group of Companies</td>
		</tr>
		<tr>
			<td><strong>Doc No.</strong> </td>
			<td><strong>Rev No.</strong> </td>
			<td> Effective Date </td>
			<td colspan="2"> {{  date('F j, Y', strtotime($ddr[0]->effective_date))  }} </td>
		</tr>
		<tr>
			<td colspan="5"> DOCUMENT DISTRIBUTION REQUEST </td>
		</tr>
	</table>

	<table style="margin-bottom:5px">
		<tr>
			<td> <strong> Reason for distribution: </strong> </td>
			@if($ddr[0]->reason_of_distribution == 1)
				<td>Relevant External Document (controlled copy)</td>
			@elseif($ddr[0]->reason_of_distribution == 2)
				<td>Customer Request (uncontrolled copy)</td>
			@else
			<td>Others (please specify)</td>
			@endif
			<td> <strong> Date Neeeded: </strong> </td> 
			<td>{{  date('F j, Y', strtotime($ddr[0]->date_needed))  }}</td>
		</tr>
	</table>

	<table style="margin-bottom:5px">
		<thead>
			<tr>
				<th>Document Title</th>
				<th>Control Code</th>
				<th>Rev No.</th>
				<th>Copy No.</th>
				<th>Copy holder</th>
			</tr>
		</thead>
		<tbody>
			@foreach($ddr[0]->ddrLists as $ddrlist )
				<tr>
					<td> {{ $ddrlist->document_title }} </td>
					<td> {{ $ddrlist->control_code }} </td>
					<td> {{ $ddrlist->rev_number }}</td>
					<td> {{ $ddrlist->copy_number }} </td>
					<td> {{ $ddrlist->copy_holder }} </td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<table style="margin-bottom:5px">
		<tr>
			<td colspan="6">
				<em> <strong> Total number of obsolete copy retrieved: {{ count($ddr[0]->ddrLists) }} </strong> </em>
			</td>
		</tr>
		<tr>
			<td> <strong> Requested by:  <strong></td>
			<td> {{ $ddr[0]->requester->name }} </td>
			<td> <strong> Approved by: </strong> </td>
			<td> {{ $ddr[0]->approver->name }} </td>
			<td> <strong> Distributed by: </strong> </td>
			@if($ddr[0]->distributed)
			<td> {{ $ddr[0]->distributed->name }} </td>
			@else
			<td></td>
			@endif
		</tr>
		<tr>
			<td> <strong> Position:  <strong> </td>
			<td>  {{ $ddr[0]->requester->position }} </td>
			<td> <strong> Position: </strong> </td>
			<td>  {{ $ddr[0]->approver->position }} </td>
			<td> <strong> Position: </strong> </td>
			@if($ddr[0]->distributed)
			<td> {{ $ddr[0]->distributed->position }} </td>
			@else
			<td></td>
			@endif
		</tr>
		<tr>
			<td> <strong> Date </strong> </td>
			<td>{{  date('F j, Y', strtotime($ddr[0]->date_request))  }}</td>
			<td> <strong> Date </strong> </td>
			@if($ddr[0]->approved_date)
			<td>{{  date('F j, Y', strtotime($ddr[0]->approved_date))  }}</td>
			@else
			<td></td>
			@endif
			<td> <strong> Date </strong> </td>
			@if($ddr[0]->distributed_date)
			<td>{{  date('F j, Y', strtotime($ddr[0]->distributed_date))  }}</td>
			@else
			<td></td>
			@endif
		</tr>
	</table>

	<p> <small> Disclaimer for uncontrolled document: </small> </p>
	<p> 
		<small>
			<ul>
				<li>Documents are issued as requested and shall not be used for any other purpose</li>
				<li>
				No Part of the document may be reproduced or utilized in any form, electronic or mechanical, including photocopying without written permission to the QM Document Controller.
				</li>
			</ul>
		</small>
	</p>
</body>
</html>