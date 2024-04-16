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
            @elseif(($mydocroute->action == 'Notify' && $mydocroute->acted_on != null) || $myturn && $mydocroute->action == 'Approve')
            <p>To view the document, please click Confirm Receipt to receive and open the document.</p>
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
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="fw-semibold mb-0">{{$dr->user->name_first . " " . $dr->user->name_family}}</p>
                                <div>
                                    <p class="small mb-0">{!!($dr->received_on == null)?"<abbr title='Not yet received'><i class='bi bi-envelope-fill'></i></abbr>":"<abbr title='$dr->received_on'><i class='bi bi-envelope-paper'></i></abbr>"!!}</p>
                                </div>
                            </div>
                            @if($dr->action == "Approve")
                            <p class="small mb-0">For approval</p>
                            @elseif(($dr->action == "Approved" || $dr->action == "Rejected") && $dr->acted_on != null)
                            @if($dr->action=="Approved")
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="small mb-0 text-success"><abbr title='{{$dr->acted_on}}'><i class="bi bi-check-circle"></i></abbr> Approved</p>
                                <div>
                                    @if($dr->comment != null)
                                    <abbr title="{{$dr->comment}}"><i class="bi bi-chat-right-text"></i></abbr>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="small mb-0 text-danger"><abbr title='{{$dr->acted_on}}'><i class="bi bi-x-circle"></i></abbr> Rejected</p>
                                <div>
                                    @if($dr->comment != null)
                                    <abbr title="{{$dr->comment}}"><i class="bi bi-chat-right-text"></i></abbr>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection