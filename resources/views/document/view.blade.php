@extends('layouts.app')

@section('content')
<div class="container">
    @include('inc.message')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1>{{$document->title}}</h1>
        </div>
        <div>
            <a href="{{route('document.edit',$document->id)}}" class="btn btn-outline-secondary">Edit</a>
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
@endsection
