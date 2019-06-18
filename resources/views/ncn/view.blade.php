@extends('layouts.app')

@section('content')
    <ncn-view :user-role="{{ Auth::user()->roles[0]->id }}"></ncn-view>
@endsection