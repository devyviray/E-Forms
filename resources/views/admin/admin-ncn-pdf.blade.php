<!DOCTYPE html>
<html>
<head>
	<title>PDF Download</title>
		<link rel="stylesheet" href="{{ asset('/css/bootstrap.3.3.7.css') }}" />
        <style>
			body {
				font-family: 'Miriam Libre', sans-serif;
				font-size: 70%;
			}
			h1,h2,h3,h4,h5,h6{
				font-family: 'Miriam Libre', sans-serif;
			}
			table, th, td {
				border: 0.50px solid black ! important;
			}
			.borderless, .borderless th, .borderless td {
				border: 0 ! important;
				font-size: 12px;
			}
    	</style>

</head>
<body>
	<table class="table table-bordered">
		<tr>
			<td rowspan="3"> <img class="logo-logo" src="{{asset('img/lfug-logo.png')}}" style="display:block;  width: 60px; height: auto; padding: 0; margin: 10px 10px 0 30px;"> </td>
			<td colspan="4">La Filipina Uy Gongco Group of Companies</td>
		</tr>
		    <tr>
				<td> <strong> Doc No. </strong> LFQM-F-019  </td>
				<td><strong>  Rev No. </strong>  02 </td>
				<td colspan="3"><strong> Effective Date: </strong>June 26, 2016 </td>
			</tr>
			<tr>
				<td colspan="5"> NON-CONFORMANCE NOTIFICATION</td>
			</tr>
	</table>

	<table class="table">
		<tr>
			<td width="30%" class="info"> <strong> COMPANY: </strong> </td>
			<td colspan="2"> {{ $ncn[0]->company->name }} </td>
		</tr>
		<tr>
			<td class="info"> <strong> DIVISION / DEPARTMENT: </strong> </td>
			<td colspan="2">  {{ $ncn[0]->department->name }} </td>
		</tr>
	</table>
	
	<table class="table borderless">
		<tr>
			<td colspan="3"> <strong> TYPE OF NON CONFORMITY: </strong> </td>
		</tr>
		<tr>	
			<td>
				@if($ncn[0]->non_conformity_types ==1)
					<img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Customer-returns
				@else
					<img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Customer-returns
				@endif
			</td>
			<td>
				@if($ncn[0]->non_conformity_types ==2)
					<img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Process-related
				@else
					<img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Process-related
				@endif
			</td>
			<td>
				@if($ncn[0]->non_conformity_types ==3)
					<img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Contracted-service
				@else
					<img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Contracted-service
				@endif
			</td>
		</tr>
		<tr>
			<td>
				@if($ncn[0]->non_conformity_types ==4)
					<img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Objectives not met
				@else
					<img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Objectives not met
				@endif
			</td>
			<td>
				@if($ncn[0]->non_conformity_types ==5)
					<img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Vendor
				@else
					<img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Vendor
				@endif
			</td>
			<td>
				@if($ncn[0]->non_conformity_types ==6)
					<img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Others
				@else
					<img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Others
				@endif
			</td>
		</tr>
	</table>

	<table class="table table-bordered">
		<tr>
			<td  class="info"> <strong> Notification No: </strong> </td>
			<td> {{ $ncn[0]->notification_number }}  </td>
			<td  class="info"> <strong> Issued by: </strong> </td>
			<td> {{ $ncn[0]->requester->name }} </td>
		</tr>
		<tr>
			<td  class="info"> <strong> Recurrence No: </strong> </td>
			<td> {{ $ncn[0]->recurrence_number }}</td>
			<td  class="info"> <strong> Position: </strong> </td>
			<td> {{ $ncn[0]->requester->position }} </td>
		</tr>
		<tr>
			<td  class="info"> <strong> Date of Issuance: </strong> </td>
			<td> {{  date('F j, Y', strtotime($ncn[0]->issuance_date))  }} </td>
			<td  class="info"> <strong> Notified Person: </strong> </td>
			@if($ncn[0]->notified)
				<td>{{ $ncn[0]->notified->name }}</td>
			@else
				<td></td>
			@endif
		</tr>
	</table>

	<table class="table table-bordered">
		<tr>
			<td colspan="4" class="info"> <strong> Details of Non-conformity: </strong> </td>
		</tr>
		<tr>
			<td colspan="4" style="height:100px; "> {{ $ncn[0]->non_conformity_details }} </td>
		</tr>
		<tr>
			<td colspan="4" class="info"> <strong> Immediate Action Taken: </strong> </td>	
		</tr>
		<tr>
			<td colspan="4" style="height:100px; "> {{ $ncn[0]->action_taken }} </td>
		</tr>
		<tr>
			<td class="info"> <strong> Responsible: </strong> </td>
			@if($ncn[0]->notified)
				<td colspan="3"> {{ $ncn[0]->notified->name }} </td>
			@else
				<td colspan="3"> </td>
			@endif
		</tr>
		<tr>
			<td class="info"> <strong> Position: </strong> </td>
			@if($ncn[0]->notified)
				<td> {{ $ncn[0]->notified->position }} </td>
			@else
				<td> </td>
			@endif
			<td class="info"> <strong> Execution Date: </strong> </td>
			@if($ncn[0]->action_date)
				<td> {{  date('F j, Y', strtotime($ncn[0]->action_date))  }} </td>
			@else
				<td>  </td>
			@endif
		</tr>
	</table>

</body>
</html>