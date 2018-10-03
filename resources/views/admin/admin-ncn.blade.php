@extends('layouts.app')

@section('content')
    <ncn-admin :role-id={{ Auth::user()->roles->pluck('id') }} :user-id={{Auth::user()->id}}></ncn-admin>
@endsection