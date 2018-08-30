@extends('layouts.app')

@section('content')
    <drdr :role-id={{ Auth::user()->roles->pluck('id') }}></drdr>
@endsection