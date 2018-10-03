@extends('layouts.app')

@section('content')
    <ddr-admin :role-id={{ Auth::user()->roles->pluck('id') }} :user-id={{Auth::user()->id}}></ddr-admin>
@endsection