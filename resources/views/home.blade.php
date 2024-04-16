@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Home</h1>
    <div class="row">
        <div class="col-sm-6">
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Documents</h3>
                    <div class="btn-group" role="group">
                        <a href="{{route('document.create')}}" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill"></i> New</a>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('template.index')}}">From Template</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="p-3">
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
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="mb-3">Approvals</h3>
                    <div class="p-3">
                        @if (count($approvals)>0)
                        @else
                        <div class="alert alert-secondary" role="alert">
                            There are no documents for approval.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
