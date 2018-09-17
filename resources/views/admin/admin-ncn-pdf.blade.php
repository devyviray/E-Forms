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
	<table style="margin-bottom: 15px">
		<tr>
			<td rowspan="3">
				<img class="logo-logo" src="{{asset('image/lfug-logo.png')}}" 
				style="display:block;  width: 60px; height: auto; padding: 0; margin: 10px 10px 0 30px;">
			</td>
			<td colspan="4">La Filipina Uy Gongco Group of Companies</td>
		</tr>
		<tr>
			<td><strong> Doc No. </strong></td>
			<td><strong> Rev No. </strong> </td>
			<td><strong> Effective Date </strong></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="4"> NON CONFORMANCE NOTIFICATION </td>
		</tr>
	</table>

	<table style="margin-bottom: 5px">
		<tr>
			<td> <strong> COMPANY: </strong> </td>
			<td> {{ $ncn[0]->company->name }} </td>
			<td> <strong> DIVISION / DEPARTMENT: </strong> </td>
			<td> {{ $ncn[0]->department->name }}</td>
		</tr>
	</table>
	
	<!-- step 1 -->
	<table style="margin-bottom: 5px">
		<tr>
			<td> <strong> TYPE OF NON CONFORMITY: </strong> </td>
			@if($ncn[0]->non_conformity_types ==1)
			<td>Customer-returns</td>
			@elseif($ncn[0]->non_conformity_types ==2)
			<td>Process-related</td>
			@elseif($ncn[0]->non_conformity_types ==3)
			<td>Contracted-service</td>
			@elseif($ncn[0]->non_conformity_types ==4)
			<td>Objectives not met</td>
			@elseif($ncn[0]->non_conformity_types ==5)
			<td>Vendor</td>
			@else
			<td>Others</td>
			@endif
		</tr>
	</table>

	<table style="margin-bottom: 5px">
		<tr>
			<td colspan="2"> <strong> Notification No: </strong> </td>
			<td>  {{ $ncn[0]->notification_number }} </td>
			<td colspan="2"> <strong> Recurrence No: </strong> </td>
			<td> {{ $ncn[0]->recurrence_number }}</td>
		</tr>
		<tr>
			<td> <strong> Issued by: </strong> </td>
			<td> {{ $ncn[0]->requester->name }} </td>
			<td> <strong> Position: </strong> </td>
			<td> {{ $ncn[0]->requester->position }} </td>
			<td> <strong> Date of Issuance: </strong> </td>
			<td>{{  date('F j, Y', strtotime($ncn[0]->issuance_date))  }}</td>
		</tr>
		<tr>
		</tr>
	</table>

	<table style="margin-bottom: 5px">
		<tr> 
			<td colspan="6"> <strong> Details of Non-conformity: </strong> </td>	
		</tr>
		<tr>
			<td colspan="6" style="height:100px; "> {{ $ncn[0]->non_conformity_details }} </td>
		</tr>
		<tr>
			<td colspan="6"> <strong> Immediate Action Taken: </strong> </td>	
		</tr>
		<tr>
			<td colspan="6" style="height:100px; ">{{ $ncn[0]->action_taken }}</td>
		</tr>
		<tr>
			<td> <strong> Responsible: </strong> </td>
			@if($ncn[0]->notified)
			<td>{{ $ncn[0]->notified->name }}</td>
			@else
			<td></td>
			@endif
			<td> <strong> Position: </strong> </td>
			@if($ncn[0]->notified)
			<td>{{ $ncn[0]->notified->position }}</td>
			@else
			<td></td>
			@endif
			<td> <strong> Execution Date: </strong> </td>
			<td>{{  date('F j, Y', strtotime($ncn[0]->action_date))  }}</td>
		</tr>
	</table>

</body>
</html>