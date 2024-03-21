@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Offices</h1>
        <a href="{{route('office.create')}}" class="btn btn-primary"><i class="bi bi-briefcase-fill"></i> New</a>
    </div>
    @include('inc.message')
    <ul class="list-group">
        @foreach($offices as $office)
        <a href="{{route('office.show',$office->id)}}" class="list-group-item list-group-item-action"><h5 class="mb-0 mt-1">{{$office->office_name}}</h5>
        <p class="mb-1">{!!is_null($office->office_head)?'<em>No head assigned</em>':$office->officeHead->name_family . ", " . $office->officeHead->name_first!!}</p>
        </a>
        @endforeach
    </ul>
</div>
@endsection