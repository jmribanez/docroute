@extends('layouts.app')

@section('content')
<div class="container">
    @include('inc.message')
    <h1 class="mb-3">{{$document->title}}</h1>
    <div class="row">
        <div class="col-sm-9 mb-3">
            <div class="card">
                <div class="card-body">
                    {{$document->description}}
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
                                <div class="fw-bold">Michael De Santa</div>
                                <div class="small">Draft</div>
                            </div>
                            <span class="badge text-bg-primary rounded-circle p-2"><i class="bi bi-pencil-fill"></i></span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
