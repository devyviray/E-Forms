@extends('layouts.app')

@section('content')
    <ncn :role-id={{ Auth::user()->roles->pluck('id') }}></ncn>
@endsection