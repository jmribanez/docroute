@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">New Office</h1>
    <div class="mb-3">
        <form action="{{route('office.store')}}" method="POST" class="card">
        @csrf
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Details</h3>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="txt_office_name" class="form-label">Office Name</label>
                    <input type="text" name="office_name" id="txt_office_name" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="sel_reportsToOffice" class="form-label">Reports to office</label>
                    <select name="reports_to_office" id="sel_reportsToOffice" class="form-select" required>
                        <option disabled selected></option>
                        @foreach ($offices as $office)
                        <option value="{{$office->id}}">{{$office->office_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="sel_office_head" class="form-label">Office Head</label>
                    <select name="office_head" id="sel_office_head" class="form-select">
                        <option disabled selected>No user selected</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="txt_office_head_title" class="form-label">Office Head Title</label>
                    <input type="text" name="office_head_title" id="txt_office_head_title" class="form-control" required>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection