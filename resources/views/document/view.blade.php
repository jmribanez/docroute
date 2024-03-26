@extends('layouts.app')

@section('content')
<div class="container">
    @include('inc.message')
    <h1 class="mb-3">{{$document->title}}</h1>
    <div class="row">
        <div class="col-sm-9 mb-3">
            <div class="card">
                <div class="card-body">
                    {!!$document->description!!}
                </div>
            </div>
        </div>
        <div class="col-sm-3 mb-3">
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
