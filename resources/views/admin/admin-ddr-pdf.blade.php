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
			<td rowspan="3">
				<img class="logo-logo" src="{{asset('img/lfug-logo.png')}}"  style="display:block;  width: 60px; height: auto; padding: 0; margin: 10px 10px 0 30px;">
			</td>
			<td colspan="5" >La Filipina Uy Gongco Group of Companies</td>
		</tr>
		<tr>
			<td> <strong> Doc No. </strong> LFQM-F-002 </td>
			<td><strong>  Rev No. </strong>  03 </td>
			<td  colspan="3"><strong> Effective Date: </strong>February 27, 2017 </td>colspan="2"> </td>
		</tr>
		<tr>
			<td colspan="5"> DOCUMENT DISTRIBUTION REQUEST </td>
		</tr>
	</table>

	<table class="table borderless">
		<tr>
			<td rowspan="3"> <strong> Reason for distribution: </strong> </td>
			@if($ddr[0]->reason_of_distribution == 1)
				<td colspan="2"> <img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Relevant external doc. (controlled copy) </td>
			@else
				<td colspan="2"> <img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Relevant external doc. (controlled copy) </td>
			@endif
		</tr>
		<tr>
			@if($ddr[0]->reason_of_distribution == 2)
				<td colspan="3"> <img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Customer request (uncontrolled copy) </td>
			@else
				<td colspan="3"> <img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Customer request (uncontrolled copy) </td>
			@endif
		</tr>
		<tr>
			@if($ddr[0]->reason_of_distribution == 3)
				<td colspan="3"> <img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Others: </td>
			@else
				<td colspan="3"> <img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> Others: </td>
			@endif
		</tr>
		<tr>
			<td> <strong> Date Neeeded: </strong>  </td>
			<td colspan="3"> {{  date('F j, Y', strtotime($ddr[0]->date_needed))  }} </td>
		</tr>
	</table>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th  class="info">Document Title</th>
				<th  class="info">Control Code</th>
				<th  class="info">Rev No.</th>
				<th  class="info">Copy No.</th>
				<th  class="info">Copy holder</th>
				<th  class="info">Received by:</th>
				<th  class="info">Date</th>
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
					<td></td>
					<td></td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<table class="table" style="border: 0 ! important;">
		<tr>
			<td class="info" colspan="6" style="border: 0 ! important;"> <em> <strong> Total number of obsolete copy retrieved </strong> </em> </td>
		</tr>
		<tr>
			<td style="border: 0 ! important;"> <strong> Requested by: <strong> </td>
			<td style="border: 0 ! important;">{{ $ddr[0]->requester->name }}</td>
			<td style="border: 0 ! important;"> <strong> Approved by: </strong> </td>
			<td style="border: 0 ! important;"> {{ $ddr[0]->approver->name }} </td>
			<td style="border: 0 ! important;"> <strong> Distributed by: </strong> </td>
			@if($ddr[0]->distributed)
				<td style="border: 0 ! important;"> {{ $ddr[0]->distributed->name }} </td>
			@else
				<td style="border: 0 ! important;"></td>
			@endif
		</tr>
		<tr>
			<td style="border: 0 ! important;"> <strong> Position: <strong> </td>
			<td style="border: 0 ! important;"> {{ $ddr[0]->requester->position }} </td>
			<td style="border: 0 ! important;"> <strong> Position: </strong> </td>
			<td style="border: 0 ! important;"> {{ $ddr[0]->approver->position }} </td>
			<td style="border: 0 ! important;"> <strong> Position: </strong> </td>
			@if($ddr[0]->distributed)
				<td style="border: 0 ! important;"> {{ $ddr[0]->distributed->position }} </td>
			@else
				<td style="border: 0 ! important;"></td>
			@endif
		</tr>
		<tr>
			<td style="border: 0 ! important;"> <strong> Date </strong> </td>
			<td style="border: 0 ! important;">{{  date('F j, Y', strtotime($ddr[0]->date_request))  }}</td>
			<td style="border: 0 ! important;"> <strong> Date </strong> </td>
			@if($ddr[0]->distributed_date)
				<td style="border: 0 ! important;"> {{  date('F j, Y', strtotime($ddr[0]->approved_date))  }} </td>
			@else
				<td style="border: 0 ! important;"></td>
			@endif
			<td style="border: 0 ! important;"> <strong> Date </strong> </td>
			@if($ddr[0]->distributed_date)
				<td style="border: 0 ! important;">{{  date('F j, Y', strtotime($ddr[0]->distributed_date))  }}</td>
			@else
				<td style="border: 0 ! important;"></td>
			@endif
		</tr>
	</table>

	<p>
		<small> Disclaimer for uncrolled document: </small>
	</p>

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