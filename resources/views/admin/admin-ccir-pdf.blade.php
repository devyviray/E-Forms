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
                <td> <strong> Doc No. </strong> LFQM-F-029b </td>
                <td><strong>  Rev No. </strong>  00 </td>
                <td colspan="3"><strong> Effective Date: </strong>February 25, 2017 </td>
            </tr>
            <tr>
                <td colspan="5"> CUSTOMER COMPLAINT INVESTIGATION REPORT </td>
            </tr>
        </table>

        <table class="table table-bordered">
            <tr>
                <td class="info"> <strong> Company: </strong> </td>
                <td colspan="6"> {{ $ccir[0]->company->name }} </td>
            </tr>
            <tr>
                <td class="info"> <strong> Date of Issuance: </strong> </td>
                <td colspan="6"> {{  date('F j, Y', strtotime($ccir[0]->date_request ))  }} </td>
            </tr>
            <tr>
                <td class="info"> <strong> Complainant's Name: </strong> </td>
                <td colspan="6"> {{ $ccir[0]->complainant }} </td>
            </tr>
            <tr>
                <td class="info"> <strong> Commodity: </strong> </td>
                <td colspan="6"> {{ $ccir[0]->commodity }} </td>
            </tr>
            <tr>
                <td class="info"> <strong> Affected Quantities </strong> </td>
                <td colspan="6"> {{ $ccir[0]->affected_quantity }} </td>
            </tr>
            <tr>
                <td class="info"> <strong> Product Control No </strong> </td>
                <td colspan="2"> {{ $ccir[0]->product_control_number }} </td>
                <td class="info"> <strong> Delivery Date </strong> </td>
                <td colspan="3"> {{  date('F j, Y', strtotime($ccir[0]->delivery_date ))  }} </td>
            </tr>
            <tr>
                <td class="info"> <strong> Quantity of sample </strong> </td>
                <td colspan="2"> {{ $ccir[0]->quantity_of_sample }} </td>
                <td class="info"> <strong> Return Date </strong> </td>
                @if($ccir[0]->returned_date)
                    <td colspan="3"> {{  date('F j, Y', strtotime($ccir[0]->returned_date ))  }} </td>
                @else
                    <td colspan="3">  </td>
                @endif
            </tr>

            <tr>
                <td rowspan="3" class="info"> <strong> Nature of Complaint: </strong> </td>
                <td class="info"> <strong> Wet/Lumpy </strong> </td>
                @if($ccir[0]->nature_of_complaint == 1)
                    <td> <img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @else
                    <td> <img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @endif
                <td class="info"> <strong> Busted Bag </strong> </td>
                @if($ccir[0]->nature_of_complaint == 2)
                    <td> <img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @else
                    <td> <img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @endif
                <td class="info"> <strong> Under / Over Weight </strong> </td>
                @if($ccir[0]->nature_of_complaint == 3)
                    <td> <img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @else
                    <td> <img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @endif
            </tr>
            <tr>
                <td class="info"> <strong> Infestation </strong> </td>
                @if($ccir[0]->nature_of_complaint == 4)
                    <td> <img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @else
                    <td> <img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @endif
                <td class="info"> <strong> Dirty Packaging </strong> </td>
                @if($ccir[0]->nature_of_complaint == 5)
                    <td> <img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @else
                    <td> <img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @endif
                <td class="info"> <strong> Others </strong> </td>
                @if($ccir[0]->nature_of_complaint == 6)
                    <td> <img src="{{asset('img/checked.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @else
                    <td> <img src="{{asset('img/uncheck.png')}}" style="width: auto; height: 20px; margin-left: 8px;"> </td>
                @endif
            </tr>
            <tr>
                <td class="info"> <strong> For others, please specify </strong> </td>
                <td colspan="5"> </td>
            </tr>
            <tr>
                <td class="info"> <strong> Other Details: </strong> </td>
                <td colspan="6"> {{ $ccir[0]->other_details }} </td>
            </tr>
        </table>
        @if($ccir[0]->verifier)
            <table class="table table-bordered">
                <tr>
                    <td width="23%" class="info"> <strong> Verified By: </strong> </td>
                    <td colspan="3"> {{ $ccir[0]->verifier->name }} </td>
                </tr>
                <tr>
                    <td class="info"> <strong> Position: </strong> </td>
                    <td> {{ $ccir[0]->verifier->position }} </td>
                    <td class="info"> <strong> Date: </strong> </td>
                    <td> {{  date('F j, Y', strtotime($ccir[0]->verified_date ))  }}</td>
                </tr>
            </table>
        @endif

    </body>
</html>