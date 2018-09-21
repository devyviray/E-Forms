@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-3 div-hover">
                    <a href="{{route('drdr')}}">
                    <div class="card ">
                        <div class="card-header ">
                            <h4 class="card-title">Document Review & Distribution Request</h4>
                        </div>
                        <hr>
                        <div class="card-body text-center">
                            <img src="{{URL::asset('/img/feedback.png')}}"  width="50%" height="50%">
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-clock-o"></i> Is a process of requesting for propose, revise and cancel of internal documents.
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 div-hover">
                <a href="{{route('ddr')}}">
                    <div class="card ">
                        <div class="card-header mb-4">
                            <h4 class="card-title">Document Distribution Request</h4>
                        </div>
                        <hr>
                        <div class="card-body text-center">
                            <img src="{{URL::asset('/img/negotiation.png')}}"  width="50%" height="50%">
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-clock-o"></i>Is a process of requesting of control and uncontrol copy of internal or external document
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 div-hover">
                <a href="{{route('ccir')}}">
                    <div class="card ">
                        <div class="card-header ">
                            <h4 class="card-title">Customer Complaint Information Report</h4>
                        </div>
                        <hr>
                        <div class="card-body text-center">
                            <img src="{{URL::asset('/img/branding.png')}}"  width="50%" height="50%">
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-clock-o"></i>  Is the process of providing initial investigation for customer complaint
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 div-hover">
                <a href="{{route('ncn')}}">
                    <div class="card ">
                        <div class="card-header mb-4">
                            <h4 class="card-title">Non-conformance Notification</h4>
                        </div>
                        <hr>
                        <div class="card-body text-center">
                            <img src="{{URL::asset('/img/service.png')}}"  width="50%" height="50%">
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-clock-o"></i>Is the issuance of notice to responsible office / person due to non-fulfillment of a requirement/s.
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

