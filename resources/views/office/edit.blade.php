@extends('layouts.app')

@section('content')
<div class="container">
    <p class="mb-0">EDITING</p>
    <h1 class="mb-3">{{$office->office_name}}</h1>
    <div class="mb-3">
        <form action="{{route('office.update',$office->id)}}" method="POST" class="card">
        @csrf
        @method('PATCH')
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Details</h3>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="txt_office_name" class="form-label">Office Name</label>
                    <input type="text" name="office_name" id="txt_office_name" class="form-control" value="{{$office->office_name}}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="sel_reportsToOffice" class="form-label">Reports to office</label>
                    <select name="reports_to_office" id="sel_reportsToOffice" class="form-select">
                        <option disabled {{($office->reports_to_office??false)?'':'selected'}}>No office selected</option>
                        @foreach($offices as $o)
                        <option value="{{$o->id}}" {{($office->reports_to_office==$o->id)?'selected':''}}>{{$o->office_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="sel_office_head" class="form-label">Office Head</label>
                    <select name="office_head" id="sel_office_head" class="form-select">
                        <option value="" {{($office->office_head??false)?'':'selected'}}>No user selected</option>
                        @foreach($users as $user)
                        <option value="{{$user->id}}" {{($office->office_head==$user->id)?'selected':''}}>{{$user->name_family . ', ' . $user->name_first}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="txt_office_head_title" class="form-label">Office Head Title</label>
                    <input type="text" name="office_head_title" id="txt_office_head_title" value="{{$office->office_head_title}}" class="form-control">
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection