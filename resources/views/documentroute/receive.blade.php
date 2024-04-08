@extends('layouts.app')

@section('content')
@include('inc.message')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-0">{{$document->title}}</h1>
            <p>Created by {{$document->user->name_first . " " . $document->user->name_family . " on " . $document->created_at}}</p>
            <p>To view the document, please click Confirm Receipt to acknowledge that it is with you.</p>
        </div>
        <div>
            <form action="{{route('documentroute.confirm',$document->id)}}" method="post">
            @csrf
            <input type="submit" class="btn btn-primary" value="Confirm Receipt">
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Approval</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Routing</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($docroute as $dr)
                        <li class="list-group-item">
                            <h5 class="mb-1">{{$dr->user->name_first . " " . $dr->user->name_family}}</h5>
                            <p>{{$dr->received_on}}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection