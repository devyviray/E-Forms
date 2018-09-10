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
    </style>
</head>
<body>
    <table class="table table-bordered" width="100%">
            <tr>
                <td rowspan="3">
                    <img class="logo-logo" src="{{asset('image/lfug-logo.png')}}" 
                    style="display:block;  width: 60px; height: auto; padding: 0; margin: 10px 10px 0 30px;">
                </td>
                <td colspan="5" >La Filipina Uy Gongco Group of Companies</td>
            </tr>
            <tr>
                <td>  Doc No. <strong>LFQM-F-029b</strong> </td>
                <td> Rev No. <strong>00</strong> </td>
                <td> Effective Date </td>
                <td colspan="2">{{ $ccir[0]->effective_date }}</td>
            </tr>
            <tr>
                <td colspan="5"> CUSTOMER COMPLAINT REPORT </td>
            </tr>
    </table>
    <!-- step 2 -->
    <table class="table table-bordered" width="100%">
        <tr>
            <td class="info"> <strong> Company: </strong> </td>
            <td colspan="6">{{ $ccir[0]->company->name }} </td>
        </tr>
        <tr>
            <td class="info"> <strong> Date of Issuance: </strong> </td>
            <td colspan="6"> </td>
        </tr>
        <tr>
            <td class="info"> <strong> Complainant's Name: </strong> </td>
            <td colspan="6">{{ $ccir[0]->requester->name }} </td>
        </tr>
        <tr>
            <td class="info"> <strong> Brand Name: </strong> </td>
            <td colspan="6">{{ $ccir[0]->brand_name }} </td>
        </tr>
        <tr>
            <td class="info"> <strong> Affected Quantities </strong> </td>
            <td colspan="6"> {{ $ccir[0]->affected_quantity }}     </td>
        </tr>
        <tr>
            <td class="info"> <strong> Product Control No </strong> </td>
            <td colspan="2">{{ $ccir[0]->product_control_number }}  </td>
            <td class="info"> <strong> Delivery Date </strong> </td>
            <td colspan="3"> {{ $ccir[0]->delivery_date }} </td>
        </tr>
        <tr>
            <td class="info"> <strong> Quantity of sample </strong> </td>
            <td colspan="2">{{ $ccir[0]->quality_of_sample }} </td>
            <td class="info"> <strong> Return Date </strong> </td>
            <td colspan="3">{{ $ccir[0]->returned_date }} </td>
        </tr>
        <tr>
            <td rowspan="3" class="info"> <strong> Nature of Complaint: </strong> </td>
            <td class="info"> <strong> Wet/Lumpy </strong> </td>
            <td>
                <img src="{{asset('image/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;">
            </td>
            <td class="info"> <strong> Busted Bag </strong> </td>
            <td>
                <img src="{{asset('image/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;">
            </td>
            <td class="info"> <strong> Under / Over Weight </strong> </td>
            <td>
                <img src="{{asset('image/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;">
            </td>
        </tr>
        <tr>
            <td class="info"> <strong> Infested </strong>
            </td>
            <td>
                <img src="{{asset('image/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;">
            </td>
            <td class="info"> <strong> Dirty Packaging </strong>
            </td>
            <td>
                <img src="{{asset('image/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;">
            </td>
            <td class="info"> <strong> Others </strong> </td>
            <td>
                <img src="{{asset('image/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;">
            </td>
        </tr>
        <tr>
            <td class="info"> <strong> For others, please specify </strong> </td>
            <td colspan="5"> </td>
        </tr>
            <tr> <td class="info"> <strong>Other Details: </strong> </td>
            <td colspan="6"> {{ $ccir[0]->other_details }} </td>
        </tr>
    </table>
    <table class="table table-bordered" width="100%">
        <tr>
            <td width="23%" class="info"> <strong> Prepared By: </strong> </td>
            <td colspan="3">	 </td>
        </tr>
        <tr>
            <td class="info"> <strong> Position: </strong> </td>
            <td> </td>
            <td class="info"> <strong> Date: </strong> </td>
            <td> </td>
        </tr>
    </table>
</body>
</html>