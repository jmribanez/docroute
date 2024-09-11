@extends('layouts.app')

@section('content')
<div class="container">
    @include('inc.message')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1>{{$document->title}}</h1>
        </div>
        <div>
            @if($userCanEdit)
            <a href="{{route('document.edit',$document->id)}}" class="btn btn-outline-secondary">Edit</a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-9 mb-3">
            <div class="card">
                @if($isUserInRoute)
                    <div class="card-body p-3">
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
                @else
                    <div class="card-body p-3">
                        <p>You must receive the document first before being able to read the contents.</p>
                        <form action="{{route('documentroute.confirm',$document->id)}}" method="post">
                            @csrf
                            <p class="small">By clicking the Receive button, I confirm that I have the physical documents with me.</p>
                            <input type="submit" value="Receive" class="btn btn-primary">
                        </form>
                    </div>
                @endif
                <div class="card-footer p-3">
                    <p class="mb-0">Created by: {{$document->user->name_first . " " .$document->user->name_family . " (" . $document->user->office->office_name . ")" . " on " . $document->created_at}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-1">
                        <h3 class="mb-0">Routing</h3>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($document->routes as $dr)
                        <li class="list-group-item">
                            <p class="fw-semibold mb-0"><a href="{{route('user.show',$dr->user_id)}}" class="text-body-secondary text-decoration-none">{{$dr->user->name_first . " " . $dr->user->name_family}}</a></p>
                            <div>
                                <p class="small mb-0">{{$dr->state}} on {{$dr->routed_on}}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
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
                document.getElementById('recepient_list').innerHTML += "<a href='#' class='list-group-item list-group-item-action'>" + recepientList[i].name_first + " " + recepientList[i].name_family + " (" + recepientList[i].office_name +") - " + recepientList[i].action + "</a>";
            }
        } else {
            document.getElementById('recepient_list').innerHTML += "<div class='list-group-item disabled'><em>Empty</em></div>";
        }
    }
    function clearRecepientList() {
        recepientList = [];
        displayRecepientList();
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
        var sel_action = (document.getElementById('optApprove').checked)?'Approve':'Notify';
        user.action = sel_action;
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
