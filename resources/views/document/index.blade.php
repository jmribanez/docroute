@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Documents</h1>
        <a href="{{route('document.create')}}" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill"></i> New</a>
    </div>
    @include('inc.message')
    @if(count($documents)>0)
    <ul class="list-group">
        
    </ul>
    @else
    <div class="alert alert-secondary" role="alert">
        There are no documents.
    </div>
    @endif
</div>
@endsection