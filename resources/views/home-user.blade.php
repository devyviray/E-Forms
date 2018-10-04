@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-3 div-hover">
                    <a href="{{route('drdr')}}">
                    <div class="card ">
                        <div class="card-header text-center">
                            <h5 class="card-title">Document Review & Distribution Request</h5>
                        </div>
                        <hr>
                        <div class="card-body text-center">
                            <img src="{{URL::asset('/img/feedback.png')}}"  width="50%" height="50%">
                        </div>
                        <div class="card-footer mb-4">
                            <hr>
                            <div class="stats text-center">
                                <i class="fa fa-clock-o"></i> Process of requesting for proposal, revision and cancellation of internal documents
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 div-hover">
                <a href="{{route('ddr')}}">
                    <div class="card ">
                        <div class="card-header text-center">
                            <h5 class="card-title">Document Distribution Request</h5>
                        </div>
                        <hr>
                        <div class="card-body text-center">
                            <img src="{{URL::asset('/img/negotiation.png')}}"  width="50%" height="50%">
                        </div>
                        <div class="card-footer mb-4">
                            <hr>
                            <div class="stats text-center">
                                <i class="fa fa-clock-o"></i>Process of requesting for control and distribution of external and internal documents
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 div-hover">
                <a href="{{route('ccir')}}">
                    <div class="card ">
                        <div class="card-header text-center">
                            <h5 class="card-title">Customer Complaint Investigation Report</h5>
                        </div>
                        <hr>
                        <div class="card-body text-center">
                            <img src="{{URL::asset('/img/branding.png')}}"  width="50%" height="50%">
                        </div>
                        <div class="card-footer mb-4">
                            <hr>
                            <div class="stats text-center">
                                <i class="fa fa-clock-o"></i>Process of providing initial investigation for customer complaint
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 div-hover">
                <a href="{{route('ncn')}}">
                    <div class="card ">
                        <div class="card-header text-center">
                            <h5 class="card-title">Non-conformance Notification</h5>
                        </div>
                        <hr>
                        <div class="card-body text-center">
                            <img src="{{URL::asset('/img/service.png')}}"  width="50%" height="50%">
                        </div>
                        <div class="card-footer ">
                            <hr>
                            <div class="stats text-center">
                                <i class="fa fa-clock-o"></i>Issuance of notification to responsible office/person due to non-fulfilment of requirement/s
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

