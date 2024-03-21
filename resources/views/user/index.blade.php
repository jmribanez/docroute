@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Users</h1>
        <a href="{{route('user.create')}}" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> New</a>
    </div>
    @include('inc.message')
    <ul class="list-group">
        @foreach($users as $user)
        <a href="{{route('user.show',$user->id)}}" class="list-group-item list-group-item-action"><h5 class="mb-0 mt-1">{{$user->name_family . ", " . $user->name_first}}</h5>
        <p class="mb-1">{{($user->hasRole('Administrator'))?'Administrator':'Standard'}}, {{!empty($user->office_id)?$user->office->office_name:'No office'}}</p>
        </a>
        @endforeach
    </ul>
</div>

@endsection