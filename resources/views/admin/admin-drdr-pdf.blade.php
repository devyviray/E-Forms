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
                <td rowspan="3" width="60px;">
                    <img class="logo-logo" src="{{ asset('img/lfug-logo.png') }}" style="display:block;  width: 60px; height: auto; padding: 0;">
                </td>
                <td colspan="5" >La Filipina Uy Gongco Group of Companies</td>
            </tr>
            <tr>
                <td><strong> Doc No. </strong> </td>
                <td><strong> Rev No. </strong> {{ $drdr[0]->rev_number }} </td>
                <td><strong> Effective Date <strong></td>
                <td colspan="2"> {{  date('F j, Y', strtotime($drdr[0]->effective_date))  }} </td>
            </tr>
            <tr>
                <td colspan="5"> DOCUMENT REVIEW AND DISTRIBUTION REQUEST </td>
            </tr>
        </table>

        <table class="table borderless">
            <tr>
                <td> <strong> Type of Request </strong> </td>
                @if($drdr[0]->request_type == 1)
                    <td> Proposal (For proposed) </td>
                @elseif($drdr[0]->request_type == 2)
                    <td> Revision (For existing document) </td>
                @else
                    <td> Cancellation </td>
                @endif
                <td> <strong> Document Title: </strong> </td>
                <td>  {{ $drdr[0]->document_title }} </td>
                <td> <strong> Rev. No: </strong> </td>
                <td colspan="3"> {{ $drdr[0]->rev_number }} </td>
            </tr>
            <tr>
                <td colspan="7"> <strong> Reason for proposal / change / cancellation: </strong> </td>
            </tr>
            <tr>
                <td colspan="7"> {{ $drdr[0]->reason_request }} </td>
            </tr>
            <tr>
                <td><strong>Requested by:</strong></td>
                <td> {{ $drdr[0]->requester->name }} </td>
                <td><strong>Position:</strong></td>
                <td> {{ $drdr[0]->requester->position }} </td>
                <td><strong> Date: </strong></td>
                <td colspan="2"> {{  date('F j, Y', strtotime($drdr[0]->date_request))  }} </td>
            </tr>
            <tr>
                <td> <strong> Reviewed by: </strong> </td>
                <td> {{ $drdr[0]->reviewer->name }} </td>
                <td> <strong> Position: </strong> </td>
                <td> {{ $drdr[0]->reviewer->position }} </td>
                <td> <strong> Date: </strong> </td>
                @if($drdr[0]->reviewed_date)
                    <td>{{ date('F j, Y', strtotime($drdr[0]->reviewed_date)) }}</td>
                @else
                    <td> </td>
                @endif
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
                <td> <strong> Date: </strong> </td>
                @if($drdr[0]->approver)
                    <td>{{ date('F j, Y', strtotime($drdr[0]->approved_date)) }}</td>
                @else
                    <td></td>
                @endif
            </tr>

        </table>

        <table class="table" width="100%">
            <tr>
                <td class="info"> <strong> Considered Documents in reviewing: </strong> </td>
            </tr>
            <tr>
                <td style="height: 50px;"> {{ $drdr[0]->consider_documents }} </td>
            </tr>
        </table>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td class="info"> <strong> Copy No. </strong></td>
                    <td class="info"> <strong> Copyholder (Department) </strong></td>
                    <td rowspan="1"> 
                        <strong>Effective date: </strong> {{  date('F j, Y', strtotime($drdr[0]->effective_date))  }}  <br/>
                        <strong style="margin-top: 10px;">DRDR NO:</strong>  <br/>
                        <strong style="margin-top: 10px;">Document Title:</strong> {{ $drdr[0]->document_title }} <br/>
                        <strong style="margin-top: 10px;">Document Code:</strong>  <br/>
                        <strong style="margin-top: 10px;">Revision No:</strong> {{ $drdr[0]->rev_number }}
                    </td>
                </tr>
            </thead>
            <tbody>
                @if($drdr[0]->drdrCopyholders)
                    @foreach($drdr[0]->drdrCopyholders as $drdr_copyholder)
                        <tr>
                            <td> {{ $drdr_copyholder->copy_number  }} </td>
                            <td> {{ $drdr_copyholder->copy_holder  }} </td>		
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>  </td>
                        <td> </td>		
                    </tr>
                @endif
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        
        <table class="table" style="border: 0 ! important;">
            <tr>
                <td  style="border: 0 ! important;"><strong> Verified by: </strong></td>
                @if($drdr[0]->distributed)
                    <td colspan="3" style="border: 0 ! important;"> {{ $drdr[0]->distributed->name  }}</td>
                @else
                    <td colspan="3" style="border: 0 ! important;"></td>
                @endif
                <td style="border: 0 ! important;"> <strong> Position: </strong></td>
                @if($drdr[0]->distributed)
                    <td style="border: 0 ! important;"> {{ $drdr[0]->distributed->position  }} </td>
                @else
                    <td style="border: 0 ! important;"></td>
                @endif
                <td  style="border: 0 ! important;"><strong> Date: </strong></td>
                @if($drdr[0]->distributed)
                    <td style="border: 0 ! important;">{{  date('F j, Y', strtotime($drdr[0]->distributed_date))  }}</td>
                @else
                    <td style="border: 0 ! important;"></td>
                @endif

            </tr>
        </table>
        
    </body>
</html>