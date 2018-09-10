<!DOCTYPE html>
<html>
<head>
	<title>PDF Download</title>
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
	<table class="table table-bordered" width="100%">
		<tr>
			<td rowspan="3">
				<img class="logo-logo" src="{{asset('image/lfug-logo.png')}}" 
				style="display:block;  width: 60px; height: auto; padding: 0; margin: 10px 10px 0 30px;">
			</td>
			<td colspan="4">La Filipina Uy Gongco Group of Companies</td>
		</tr>
		<tr>
			<td> Doc No. <strong>LFQM-F-019</strong> </td>
			<td> Rev No. <strong>02</strong> </td>
			<td> Effective Date </td>
			<td> June 22, 2016 </td>
		</tr>
		<tr>
			<td colspan="4"> NON CONFORMANCE NOTIFICATION </td>
		</tr>
	</table>

	<table class="table"  width="100%">
		<tr>
			<td width="30%" class="info"> <strong> COMPANY: </strong> </td>
			<td colspan="2"> Company name </td>
		</tr>
		<tr>
			<td class="info"> <strong> DIVISION / DEPARTMENT: </strong> </td>
			<td colspan="2"> </td>
		</tr>
	</table>
	
	<!-- step 1 -->
	<table class="table borderless"  width="100%">
		<tr>
			<td colspan="3"> <strong> TYPE OF NON CONFORMITY: </strong> </td>
		</tr>
		<tr>
			<td>
				<img src="{{asset('image/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> item name
			</td>
		</tr>
	</table>

	<table class="table table-bordered" width="100%">
		<tr>
			<td  class="info"> <strong> Notification No: </strong> </td>
			<td> </td>
			<td  class="info"> <strong> Issued by: </strong> </td>
			<td> </td>
		</tr>
		<tr>
			<td  class="info"> <strong> Recurrence No: </strong> </td>
			<td> </td>
			<td  class="info"> <strong> Position: </strong> </td>
			<td> </td>
		</tr>
		<tr>
			<td  class="info"> <strong> Date of Issuance: </strong> </td>
			<td> </td>
			<td  class="info"> <strong> Notified Person: </strong> </td>
			<td> </td>
		</tr>
	</table>

	<table class="table table-bordered" width="100%">
			<tr> 
				<td colspan="4" class="info"> <strong> Details of Non-conformity: </strong> </td>	
			</tr>
			<tr>
				<td colspan="4" style="height:100px; "> </td>
			</tr>
			<tr>
				<td colspan="4" class="info"> <strong> Immediate Action Taken: </strong> </td>	
			</tr>
			<tr>
				<td colspan="4" style="height:100px; "> action taken </td>
			</tr>
			<tr>
				<td class="info"> <strong> Responsible: </strong> </td>
				<td colspan="3"> notified name </td>
			</tr>
			<tr>
				<td class="info"> <strong> Position: </strong> </td>
				<td> </td>
				<td class="info"> <strong> Execution Date: </strong> </td>
				<td> execution date </td>
			</tr>
	</table>

</body>
</html>