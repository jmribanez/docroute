{{-- @extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found')) --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="display-1">404</h1>
        <p>{{$exception->getMessage() ?: 'Not Found.'}}</p>
    </div>
@endsection