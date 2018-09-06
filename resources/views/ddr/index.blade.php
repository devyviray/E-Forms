
@extends('layouts.app')

@section('content')
    <ddr :role-id={{ Auth::user()->roles->pluck('id') }}></ddr>
@endsection