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
        <table style="margin-bottom:15px">
            <tr>
                <td rowspan="1">
                    <img class="logo-logo" src="http://172.17.2.88/e-forms-test/public/image/lfug-logo.png" 
                        style="display:block;  width: 60px; height: auto; padding: 0;">
                </td>
                <td colspan="5">La Filipina Uy Gongco Group of Companies</td>
            </tr>
            <tr>
                <td><strong> Doc No.</strong> </td>
                <td></td>
                <td><strong>Rev No. </strong> </td>
                <td></td>
                <td><strong> Effective Date </strong></td>
                <td> {{  date('F j, Y', strtotime($drdr[0]->effective_date))  }}</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align:center"> DOCUMENT REVIEW AND DISTRIBUTION REQUEST </td>
            </tr>
        </table>
        <table style="margin-bottom: 5px">
            <tr>
                <td> <strong> Document Title: </strong> </td>
                <td>{{ $drdr[0]->document_title }}</td>
                <td> <strong> Rev. No: </strong> </td>
                <td>{{ $drdr[0]->rev_number }}</td>
            </tr>
            <tr>
                <td colspan="4"> <strong> Reason for proposal / change / cancellation: </strong> {{ $drdr[0]->reason_request }} </td>
            </tr>
        </table>
        
        <table style="margin-bottom:5px">
            <tr>
                <td><strong>Requested by:</strong></td>
                <td> {{ $drdr[0]->requester->name }} </td>
                <td><strong>Position:</strong></td>
                <td>  {{ $drdr[0]->requester->position }} </td>
                <td><strong>Date:</strong></td>
                <td>{{  date('F j, Y', strtotime($drdr[0]->date_request))  }}</td>
            </tr>
            <tr>
                <td> <strong> Reviewed by: </strong> </td>
                <td> {{ $drdr[0]->reviewer->name }} </td>
                <td> <strong> Position: </strong> </td>
                <td>{{ $drdr[0]->reviewer->position }} </td>
                <td> <strong> Date: </strong> </td>
                <td>{{  date('F j, Y', strtotime($drdr[0]->reviewed_date))  }}</td>
            </tr>
            <tr>
                <td> <strong> Approved by: </strong> </td>
                @if($drdr[0]->approver)
                <td> {{ $drdr[0]->approver->name }} </td>
                @else
                <td></td>
                @endif
                <td> <strong> Position: </strong> </td>
                @if($drdr[0]->approver)
                <td> {{ $drdr[0]->approver->position }} </td>
                @else
                <td></td>
                @endif
                <td><strong> Date: </strong> </td>
                @if($drdr[0]->approver)
                <td>{{  date('F j, Y', strtotime($drdr[0]->approved_date))  }}</td>
                @else
                <td></td>
                @endif
            </tr>
        </table>

        <table style="margin-bottom: 5px">
            <tr>
                <td class="info"> <strong> Considered Documents in reviewing: </strong> </td>
            </tr>
            <tr> 
                <td style="height: 50px;"> {{ $drdr[0]->consider_documents }}  </td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td colspan="3"><strong>Copy No. </strong> {{ $drdr[0]->copy_number  }} </td>
                <td colspan="3"><strong>Copyholder</strong>  {{ $drdr[0]->copy_holder  }}  </td>
            </tr>
            <tr>
                <td><strong>Verified by:</strong></td>
                @if($drdr[0]->distributed)
                <td> {{ $drdr[0]->distributed->name  }}</td>
                @else
                <td></td>
                @endif
                <td><strong>Position:</strong></td>
                @if($drdr[0]->distributed)
                <td> {{ $drdr[0]->distributed->position  }} </td>
                @else
                <td></td>
                @endif
                <td><strong>Date:</strong></td>
                @if($drdr[0]->distributed)
                <td>{{  date('F j, Y', strtotime($drdr[0]->distributed_date))  }}</td>
                @else
                <td></td>
                @endif
            </tr>
        </table>
    </body>
</html>