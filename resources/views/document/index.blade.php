@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Documents</h1>
        <div>
            <a href="{{route('document.create')}}" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill"></i> New</a>
            @can('list all documents')
            @if(!$showAll)
            <a href="?all=1" class="btn btn-outline-secondary ms-1">Show All</a>
            @else
            <a href="{{route('document.index')}}" class="btn btn-outline-secondary ms-1">Show Mine</a>
            @endif
            @endcan
        </div>
    </div>
    @include('inc.message')
    @if(count($documents)>0)
    <ul class="list-group">
        @foreach ($documents as $document)
        <a href="{{route('document.show',$document->id)}}" class="list-group-item list-group-item-action">
            <h5 class="mb-0 mt-1">{{$document->title}}</h5>
            <p class="mb-1">Created by: {{$document->user->name_first . " " . $document->user->name_family}}</p>
        </a>
        @endforeach
    </ul>
    @else
    <div class="alert alert-secondary" role="alert">
        There are no documents.
    </div>
    @endif
</div>
@endsection