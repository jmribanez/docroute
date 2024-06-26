@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Documents</h1>
        <div>
            <div class="btn-group" role="group">
                <a href="{{route('document.create')}}" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill"></i> New</a>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('template.index')}}">From Template</a></li>
                    </ul>
                </div>
            </div>
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
    @if(count($documents)>0 || count($unread_documents)>0)
    <ul class="list-group">
        @foreach ($unread_documents as $document)
        <a href="{{route('document.show',$document->document_id)}}" class="list-group-item list-group-item-action list-group-item-info">
            <h5 class="mb-0 mt-1">{{$document->title}}</h5>
            <p class="mb-1">Created by: {{$document->user->name_first . " " . $document->user->name_family . " on " . $document->created_at}}</p>
        </a>
        @endforeach
        @foreach ($documents as $document)
        <a href="{{route('document.show',$document->id)}}" class="list-group-item list-group-item-action">
            <h5 class="mb-0 mt-1">{{$document->title}}</h5>
            <p class="mb-1">Created by: {{$document->user->name_first . " " . $document->user->name_family . " on " . $document->created_at}}</p>
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