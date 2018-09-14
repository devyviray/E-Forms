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
        <table style="margin-bottom:30px">
            <tr>
                <td rowspan="4">
                    <img class="logo-logo" src="http://172.17.2.88/e-forms-test/public/image/lfug-logo.png" 
                        style="display:block;  width: 60px; height: auto; padding: 0;">
                </td>
            <td colspan="5">La Filipina Uy Gongco Group of Companies</td>
            </tr>
            <tr>
                <td><strong> Doc No.</strong> </td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td><strong>Rev No. </strong> </td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td><strong> Effective Date </strong></td>
                <td colspan="4"> {{ $drdr[0]->effective_date }}</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align:center"> DOCUMENT REVIEW AND DISTRIBUTION REQUEST </td>
            </tr>
        </table>

        <table>
            <tr>
                <td> <strong> Document Title: </strong> </td>
                <td colspan="2">  </td>
                <td> <strong> Rev. No: </strong> </td>
                <td colspan="3">{{ $drdr[0]->rev_number }}</td>
            </tr>
            <tr>
                <td colspan="7"> <strong> Reason for proposal / change / cancellation: </strong> {{ $drdr[0]->reason_request }} </td>
            </tr>
            <tr>
                <td><strong>Requested by:</strong></td>
                <td> {{ $drdr[0]->requester->name }} </td>
                <td><strong>Position:</strong></td>
                <td>  {{ $drdr[0]->requester->position }} </td>
                <td><strong>Date:</strong></td>
                <td colspan="2"> {{ $drdr[0]->date_request }} </td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td width="15%"> <strong> Reviewed by: </strong> </td>
                <td> {{ $drdr[0]->reviewer->name }} </td>
                <td width="10%"> <strong> Position: </strong> </td>
                <td>  {{ $drdr[0]->reviewer->position }} </td>
                <td width="10%"> <strong> Date: </strong> </td>
                <td> {{ $drdr[0]->reviewed_date }} </td>
            </tr>
            <tr>
                <td> <strong> Approved by: </strong> </td>
                <td> {{ $drdr[0]->approver->name }} </td>
                <td> <strong> Position: </strong> </td>
                <td> {{ $drdr[0]->approver->position }} </td>
                <td>,<strong> Date: </strong> </td>
                <td> {{ $drdr[0]->approved_date }} </td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="info"> <strong> Considered Documents in reviewing: </strong> </td>
            </tr>
            <tr> 
                <td style="height: 50px;"> {{ $drdr[0]->consider_documents }}  </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <td>Copy No.  {{ $drdr[0]->copy_number  }} </td>
                    <td>Copyholder  {{ $drdr[0]->copy_holder  }}  </td>
                    <td rowspan=""> 
                        <strong>Effective date: </strong> <br/>
                        <strong style="margin-top: 10px;">DRDR NO:</strong> <br/>
                        <strong style="margin-top: 10px;">Document Title: {{ $drdr[0]->document_title  }}</strong> <br/>
                        <strong style="margin-top: 10px;">Document Code:</strong> <br/>
                        <strong style="margin-top: 10px;">Revision No: {{ $drdr[0]->rev_number  }}</strong>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> </td>
                    <td> </td>		
                </tr>
            </tbody>
        </table>
        
        <table>
            <tr>
                <td>Verified by:</td>
                <td colspan="3"> </td>
            </tr>
            <tr>
                <td>Position:</td>
                <td > </td>
                <td>Date:</td>
                <td> </td>
            </tr>
        </table>
    </body>
</html>