@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Home</h1>
    <div class="row">
        <div class="col-sm-6">
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h3>Documents</h3>
                    <a href="{{route('document.create')}}" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill"></i> New</a>
                </div>
                <div class="p-3">
                    @if(count($documents)>0)
                    <ul class="list-group">
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
                    <h3>Routing</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3>Approvals</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
