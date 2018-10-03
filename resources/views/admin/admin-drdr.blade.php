@extends('layouts.app')

@section('content')
    <drdr-admin :role-id={{ Auth::user()->roles->pluck('id') }} :user-id={{Auth::user()->id}}></drdr-admin>
@endsection