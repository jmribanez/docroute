@extends('layouts.app')

@section('content')
@include('inc.message')
<div class="container">
    <div class="row">
        <div class="col-sm-9">
            <h1 class="mb-0">{{$document->title}}</h1>
            <p>Created by {{$document->user->name_first . " " . $document->user->name_family . " on " . $document->created_at}}</p>
            @if($docroute[0]->sent_on == null)
            <p>The document has not yet been sent for routing.</p>
            @elseif($mydocroute == null)
            <p>You are not authorized to view this document. Please contact the author or an approver to receive access.</p>
            @elseif($mydocroute->action == 'Notify' && $mydocroute->acted_on != null)
            <p>To view the document, please click Confirm Receipt to acknowledge that it is with you.</p>
            <form action="{{route('documentroute.confirm',$document->id)}}" method="post">
                @csrf
                <input type="submit" class="btn btn-primary" value="Confirm Receipt">
                </form>
            @else
            <p>The document has not yet gone through all needed approvers.</p>
            @endif


        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Routing</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($docroute as $dr)
                        <li class="list-group-item">
                            <p class="fw-bold mb-0">{{$dr->user->name_first . " " . $dr->user->name_family}}</h5>
                            <p class="small mb-0">{{$dr->action}}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection