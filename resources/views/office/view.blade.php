@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column">
            <h1>{{$office->office_name}}</h1>
            <p class="mb-0">{!!empty($office->office_head)?'<em>No head assigned</em>':$office->officeHead->name_first . " " . $office->officeHead->name_family . " <i class='bi bi-dot'></i> " . $office->office_head_title!!}</p>
        </div>
        <a href="{{route('office.edit',$office->id)}}" class="btn btn-outline-secondary">Edit</a>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <h3>Personnel assigned</h3>
            <div class="list-group p-3">
                @if(count($personnel) > 0)
                @foreach($personnel as $person)
                <a href="{{route('user.show',$person->id)}}" class="list-group-item list-group-item-action">{{$person->name_family . ", " . $person->name_first}}</a>
                @endforeach
                @endif
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Reports to</h3>
            <div class="list-group p-3">
                @if ($office->id == $office->reportsToOffice->id)
                <p class="list-group-item list-group-item-secondary"><em>This does not report to a higher office.</em></p>
                @else
                <a href="{{route('office.show',$office->reportsToOffice->id)}}" class="list-group-item list-group-item-action">{{$office->reportsToOffice->office_name}}</a>
                @endif
            </div>
            <h3>Reporting offices</h3>
            <div class="list-group p-3">
                @if(count($reportingFrom) > 0)
                @foreach($reportingFrom as $subOffice)
                <a href="{{route('office.show',$subOffice->id)}}" class="list-group-item list-group-item-action">{{$subOffice->office_name}}</a>
                @endforeach
                @else
                <p class="list-group-item list-group-item-secondary"><em>No offices reporting to this office.</em></p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection