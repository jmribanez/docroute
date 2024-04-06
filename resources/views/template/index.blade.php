@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Templates</h1>
        <a href="{{route('template.create')}}" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill"></i> New</a>
    </div>
    @include('inc.message')
    @if(count($templates)>0)
    <ul class="list-group">
        @foreach ($templates as $template)
        <div class="list-group-item">
            <a href="{{route('template.show',$template->id)}}" class="list-group-item-action stretched-link text-decoration-none z-1">
                <h5 class="mb-0 mt-1">{{$template->name}}</h5>
                <p class="mb-1">{{$template->description}}</p>
            </a>
        </div>
        @endforeach
    </ul>
    @else
    <div class="alert alert-secondary" role="alert">
        There are no templates.
    </div>
    @endif
</div>
@endsection