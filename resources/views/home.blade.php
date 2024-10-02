@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Home</h1>
    <div class="row">
        <div class="col-sm-6">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="mb-0">Recent Documents</h3>
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
                        @if(count($documents)>0)
                        <ul class="list-group">
                            @foreach ($documents as $document)
                            <a href="{{route('document.show',$document->document->id)}}" class="list-group-item list-group-item-action {{$document->document->id==($selectedDocument->id??0)?'bg-info-subtle':''}}">
                                <p class="mb-0 fw-bold">{{$document->document->title}}</p>
                                <p class="mb-0 small">{{$document->document->routes->last()->action??$document->document->routes->last()->state}} by: {{$document->document->routes->last()->user->name_first . " " . $document->document->routes->last()->user->name_family . " on " . $document->document->routes->last()->routed_on}}</p>
                            </a>
                            @endforeach
                        </ul>
                        {{-- <div class="my-1">
                            {!!$documents->links()!!}
                        </div> --}}
                        @else
                        <div class="alert alert-secondary" role="alert">
                            There are no documents.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="mb-2">Notifications</h3>
                    <div class="p-3">
                        @if(count($notifications)>0)
                        <ul class="list-group">
                            @foreach ($notifications as $notification)
                            <a href="{{route('document.show',$notification->document->id)}}" class="list-group-item list-group-item-action">
                                <p class="mb-0 fw-bold">{{$notification->document->title}}</p>
                                <p class="mb-0 small">Sent by {{$notification->sender->name_first}} {{$notification->sender->name_family}} on {{$notification->created_at}}</p>
                            </a>
                            @endforeach
                        </ul>
                        @else
                        <div class="alert alert-secondary" role="alert">
                            There are no notifications.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
