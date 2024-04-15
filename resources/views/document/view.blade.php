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
        <div class="col-md-9 mb-3">
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
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h3 class="mb-0">Routing</h3>
                        @if($mydocroute != null)
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#routingModal">Add</button>
                        @endif
                    </div>
                    @if(count($docroute)==0)
                    <form action="{{route('documentroute.prepare',$document->id)}}" method="post" class="d-grid">
                    @csrf
                    <div class="d-flex align-items-center mb-2">
                        <i class="d-block bi bi-exclamation-circle text-danger fs-5 me-2"></i>
                        <p class="mb-0">Preparing a document for routing will disable editing.</p>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Prepare">
                    </form>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach ($docroute as $dr)
                        <li class="list-group-item">
                            <p class="fw-bold mb-0">{{$dr->user->name_first . " " . $dr->user->name_family}}</p>
                            <p class="small mb-0">{{$dr->action??'Not yet received'}}</p>
                            @if(!empty($dr->comment))
                            <p class="small mb-0"><q>{{$dr->comment}}</q></p>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @if($docroute[0]->sent_on == null)
                    <div class="d-grid mt-3">
                        <form action="{{route('documentroute.sendDocument',$document->id)}}" class="d-grid" method="post">@csrf <input type="submit" class="btn btn-primary" value="Send"></form>
                    </div>
                    @endif
                    @endif
                    @if($myturn != null && $mydocroute != null)
                    @if($mydocroute->action == "Approve" && $myturn)
                    <div class="border-top mt-2 pt-2">
                        <p class="fw-bold mb-2">Approver options</p>
                        <textarea id="txtapprovalcomment" cols="30" rows="2" placeholder="Approval or Rejection comment" class="form-control mb-2"></textarea>
                        <div class="d-flex justify-content-evenly">
                            <form action="{{route('documentroute.approveDocument')}}" class="d-inline-block" method="post">@csrf <input type="hidden" name="document_id" value="{{$document->id}}"><input type="hidden" name="action" value="Approved"><input type="hidden" id="txtCommentA" name="comment"><input type="submit" class="btn btn-primary" onclick="copyComment()" value="Approve"></form>
                            <form action="{{route('documentroute.approveDocument')}}" class="d-inline-block" method="post">@csrf <input type="hidden" name="document_id" value="{{$document->id}}"><input type="hidden" name="action" value="Rejected"><input type="hidden" id="txtCommentB" name="comment"><input type="submit" class="btn btn-danger" onclick="copyComment()" value="Reject"></form>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="routingModal" tabindex="-1" aria-labelledby="routingModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add recepients</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" id="searchname" class="form-control" placeholder="Find by name" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="findByName()">Search</button>
                </div>
                <div class="mb-3">
                    <span class="d-inlineblock pe-2">Action:</span>
                    <input type="radio" class="btn-check" name="optAction" id="optNotify" autocomplete="off" checked>
                    <label class="btn" for="optNotify">Notify</label>
                    <input type="radio" class="btn-check" name="optAction" id="optApprove" autocomplete="off">
                    <label class="btn" for="optApprove">Approve</label>
                </div>
                <div class="list-group mb-3" id="search_list">
                    <div class="list-group-item disabled"><em>Empty</em></div>
                </div>
                <p class="mb-1"><strong>Selected Recepients</strong></p>
                <div class="list-group mb-3" id="recepient_list">
                    <div class="list-group-item disabled"><em>Empty</em></div>
                </div>
                <p class="small mb-0">Note: New recepients are added at the end of the list.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-danger me-auto" onclick="clearRecepientList()">Clear selected</button>
                <form action="{{route('documentroute.addRecepients')}}" method="post">@csrf <input type="hidden" id="txt_recepients" name="recepients"><input type="hidden" name="document_id" value="{{$document->id}}"><input type="submit" value="Add" class="btn btn-primary"></form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearRecepientList()">Close</button>
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
    @if($myturn != null && $mydocroute != null)
    @if($mydocroute->action == "Approve" && $myturn)
    function copyComment() {
        document.getElementById('txtCommentA').value = document.getElementById('txtapprovalcomment').value;
        document.getElementById('txtCommentB').value = document.getElementById('txtapprovalcomment').value;
    }
    @endif
    @endif
</script>
@endsection
