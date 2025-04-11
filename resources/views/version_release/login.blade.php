@extends('layouts.new')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<version-release
    :role-id="{{ Auth::check() && Auth::user()->roles->isNotEmpty() ? Auth::user()->roles->first()->id : 0 }}" 
    :user-id="{{ Auth::check() ? Auth::user()->id : 0 }}">
</version-release>

<div class="mt-4 h3 px-2 py-4">
    <a class="text-primary" href="{{ url('/login') }}"><i class="fas fa-chevron-left mr-2"></i>Home</a>
</div>
@endsection