@extends('layouts.app')

@section('content')
<div class="container">
    @include('inc.message')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1>{{$document->title}}</h1>
        </div>
        <div>
            @if(count($docroute)==0)
            <a href="{{route('document.edit',$document->id)}}" class="btn btn-outline-secondary">Edit</a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-9 mb-3">
            <div class="card">
                <div class="card-body mt-3 p-3">
                    {!!$document->description!!}
                </div>
                @if (count($document->attachments)>0)
                <div class="card-body border-top p-3">
                    <h5>Attachments</h5>
                    <div class="list-group">
                        @foreach ($document->attachments as $attachment)
                            <a href="{{asset('storage/attachments/'.$attachment->url)}}" class="list-group-item list-group-item-action">{{$attachment->orig_filename}}</a>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="card-footer p-3">
                    <p class="mb-0">Created by: {{$document->user->name_first . " " .$document->user->name_family . " (" . $document->user->office->office_name . ")" . " on " . $document->created_at}}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3 mb-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="mb-3">Routing</h3>
                    @if(count($docroute)==0)
                    <form action="{{route('documentroute.send',$document->id)}}" method="post" class="d-grid">
                    @csrf
                    <div class="d-flex align-items-center mb-2">
                        <i class="d-block bi bi-exclamation-circle text-danger fs-5 me-2"></i>
                        <p class="mb-0">Sending a document for routing will disable editing.</p>
                    </div>
                    
                    <input type="submit" class="btn btn-primary" value="Send">
                    </form>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach ($docroute as $dr)
                        <li class="list-group-item">
                            <h5 class="mb-1">{{$dr->user->name_first . " " . $dr->user->name_family}}</h5>
                            <p>{{$dr->received_on??'Not yet received'}}</p>
                        </li>
                        @endforeach
                    </ul>
                    <div class="d-grid">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#routingModal">Send</button>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Approval</h3>
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Franklin Clinton</div>
                                <div class="small">Draft</div>
                            </div>
                            <span class="badge text-primary-emphasis bg-primary-subtle rounded-circle p-2"><i class="bi bi-pencil-fill"></i></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Franklin Clinton</div>
                                <div class="small">Sent</div>
                            </div>
                            <span class="badge text-primary-emphasis bg-primary-subtle rounded-circle p-2"><i class="bi bi-send-fill"></i></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Trevor Philips</div>
                                <div class="small">For Approval</div>
                            </div>
                            <span class="badge text-secondary-emphasis bg-secondary-subtle rounded-circle p-2"><i class="bi bi-clock"></i></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Trevor Philips</div>
                                <div class="small">Approved</div>
                            </div>
                            <span class="badge text-white bg-success rounded-circle p-2"><i class="bi bi-check-lg"></i></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Trevor Philips</div>
                                <div class="small">Rejected</div>
                            </div>
                            <span class="badge text-white bg-danger rounded-circle p-2"><i class="bi bi-x-lg"></i></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Trevor Philips</div>
                                <div class="small">Notified</div>
                            </div>
                            <span class="badge text-primary-emphasis bg-primary-subtle rounded-circle p-2"><i class="bi bi-bell-fill"></i></span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="routingModal" tabindex="-1" aria-labelledby="routingModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Send to recepients</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" id="searchname" class="form-control" placeholder="Find by name" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="findByName()">Search</button>
                </div>
                <div class="list-group mb-3" id="search_list">
                    <div class="list-group-item disabled"><em>Empty</em></div>
                </div>
                <p class="mb-1"><strong>Recepients</strong></p>
                <div class="list-group" id="recepient_list">
                    <div class="list-group-item disabled"><em>Empty</em></div>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{route('documentroute.sendToRecepient')}}" method="post">@csrf <input type="hidden" id="txt_recepients" name="recepients"><input type="hidden" name="document_id" value="{{$document->id}}"><input type="submit" value="Send" class="btn btn-primary"></form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var userList = [];
    var recepientList = [];
    function displaySearchList() {
        document.getElementById('search_list').innerHTML = "";
        if(userList.length>0) {
            for(i=0; i<userList.length; i++) {
                document.getElementById('search_list').innerHTML += "<a href='#' class='list-group-item list-group-item-action' onclick='addToList("+i+")'>" + userList[i].name_first + " " + userList[i].name_family + " (" + userList[i].office_name +")</a>";
            }
        } else {
            document.getElementById('search_list').innerHTML += "<div class='list-group-item disabled'><em>Empty</em></div>";
        }
    }
    function displayRecepientList() {
        document.getElementById('recepient_list').innerHTML = "";
        if(recepientList.length>0) {
            for(i=0; i<recepientList.length; i++) {
                document.getElementById('recepient_list').innerHTML += "<a href='#' class='list-group-item list-group-item-action'>" + recepientList[i].name_first + " " + recepientList[i].name_family + " (" + recepientList[i].office_name +")</a>";
            }
        } else {
            document.getElementById('recepient_list').innerHTML += "<div class='list-group-item disabled'><em>Empty</em></div>";
        }
    }
    function findByName() {
        var searchname = document.getElementById('searchname').value.trim();
        if(searchname == "")
        { alert('The search box is empty.') }
        else {
            $.ajax({
                url: '{{url("findUser") . "/"}}'+searchname,
                type: 'GET',
                success: function(response) {
                    userList = response;
                    displaySearchList();
                }
            });
        }
    }
    function addToList(id) {
        var user = userList[id];
        recepientList.push(user);
        displayRecepientList();
        prepareToSend();
    }
    function removeFromList() {

    }
    function prepareToSend() {
        document.getElementById('txt_recepients').value = JSON.stringify(recepientList);
    }
</script>
@endsection
