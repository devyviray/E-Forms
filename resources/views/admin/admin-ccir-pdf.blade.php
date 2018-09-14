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
    <table style="margin-bottom: 30px">
        <tr>
            <td rowspan="3">
                <img class="logo-logo" src="http://172.17.2.88/e-forms-test/public/image/lfug-logo.png" 
                style="display:block;  width: 60px; height: auto; padding: 0; margin: 10px 10px 0 30px;">
            </td>
            <td colspan="5" >La Filipina Uy Gongco Group of Companies</td>
        </tr>
        <tr>
            <td  colspan="5">  Doc No. <strong>LFQM-F-029b</strong> </td>
        </tr>
        <tr>
            <td colspan="5"> CUSTOMER COMPLAINT REPORT </td>
        </tr>
    </table>
    <!-- step 2 -->
    <table style="margin-bottom: 30px">
        <tr>
            <td> <strong> Company: </strong> </td>
            <td colspan="6">{{ $ccir[0]->company->name }} </td>
        </tr>
        <tr>
            <td> <strong> Date of Issuance: </strong> </td>
            <td colspan="6"> {{  date('F j, Y', strtotime($ccir[0]->date_request ))  }} </td>
        </tr>
        <tr>
            <td> <strong> Complainant's Name: </strong> </td>
            <td colspan="6">{{ $ccir[0]->requester->name }} </td>
        </tr>
        <tr>
            <td> <strong> Brand Name: </strong> </td>
            <td colspan="6">{{ $ccir[0]->brand_name }} </td>
        </tr>
        <tr>
            <td> <strong> Affected Quantities </strong> </td>
            <td colspan="6"> {{ $ccir[0]->affected_quantity }} </td>
        </tr>
        <tr>
            <td> <strong> Product Control No </strong> </td>
            <td colspan="6">{{ $ccir[0]->product_control_number }}  </td>
        </tr>
        <tr>
            <td> <strong> Delivery Date </strong> </td>
            <td colspan="6">{{  date('F j, Y', strtotime($ccir[0]->delivery_date ))  }}</td>
        </tr>
        <tr>
            <td> <strong> Quantity of sample </strong> </td>
            <td colspan="6">{{ $ccir[0]->quality_of_sample }} </td>
        </tr>
        <tr>
            <td> <strong> Return Date </strong> </td>
            <td colspan="6">{{  date('F j, Y', strtotime($ccir[0]->returned_date ))  }}</td>
        </tr>
        <tr>
            <td> <strong> Nature of Complaint: </strong> </td>
            @if($ccir[0]->nature_of_complaint == 1)
                <td colspan="6"> Wet/Lumpy </td>
            @elseif($ccir[0]->nature_of_complaint == 2)
                <td colspan="6"> Busted Bag </td>
            @elseif($ccir[0]->nature_of_complaint == 3)
                 <td colspan="6">  Under / Over Weight </td>
            @elseif($ccir[0]->nature_of_complaint == 4)
                <td colspan="6"> Infested  </td>
            @elseif($ccir[0]->nature_of_complaint == 5)
                <td colspan="6"> Dirty Packaging </td>
            @else
                <td colspan="6">  Others  </td> 
            @endif
        </tr>
    </table>
    @if($ccir[0]->verifier)
        <table>
            <tr>
                <td> <strong> Verified by: </strong> </td>
                <td colspan="6">{{ $ccir[0]->verifier->name }} </td>
                <td colspan="6">  {{  date('F j, Y', strtotime($ccir[0]->verified_date ))  }}</td>
            </tr>
        </table>
    @endif
</body>
</html>