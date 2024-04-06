@extends('layouts.app')

@section('content')
@include('inc.message')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1>{{$document->title}}</h1>
        </div>
        <div>
            <a href="{{route('template.confirm',$document->id)}}" class="btn btn-outline-secondary">Confirm Receipt</a>
        </div>
        {{ /* TODO: IMPLEMENT A FORM HERE TO CONFIRM RECEIPT OF THE DOCUMENT */}}
    </div>
</div>
@endsection