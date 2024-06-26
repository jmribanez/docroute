@extends('layouts.app')

@section('content')
@include('inc.message')
<div class="container">
    <div class="row">
        <div class="col-sm-9">
            <h1 class="mb-0">{{$document->title}}</h1>
            <p>Created by {{$document->user->name_first . " " . $document->user->name_family . " on " . $document->created_at}}</p>
            <p>To view the document, please click Confirm Receipt to receive and open the document.</p>
            <form action="{{route('documentroute.confirm',$document->id)}}" method="post">
                @csrf
                <input type="submit" class="btn btn-primary" value="Confirm Receipt">
            </form>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Routing</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($docroute as $dr)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="fw-semibold mb-0"><a href="{{route('user.show',$dr->user_id)}}" class="text-body-secondary text-decoration-none">{{$dr->user->name_first . " " . $dr->user->name_family}}</a></p>
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