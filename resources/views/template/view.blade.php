@extends('layouts.app')

@section('content')
<div class="container">
    @include('inc.message')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1>{{$template->name}}</h1>
            <p>{{$template->description}}</p>
        </div>
        <div>
            <a href="{{route('template.edit',$template->id)}}" class="btn btn-outline-secondary">Edit</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body mt-3 p-3">
            {!!$template->content!!}
        </div>
        <div class="card-footer p-3">
            <p class="mb-0">Created on: {{$template->created_at}}</p>
        </div>
    </div>
</div>
@endsection