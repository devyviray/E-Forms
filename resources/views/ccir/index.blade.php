@extends('layouts.app')

@section('content')
    <ccir :role-id={{ Auth::user()->roles->pluck('id') }}></ccir>
@endsection