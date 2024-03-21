{{-- @extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden')) --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="display-1">403</h1>
        <p>{{$exception->getMessage() ?: 'Forbidden'}}</p>
    </div>
@endsection