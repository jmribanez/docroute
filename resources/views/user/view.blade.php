@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">{{$user->name_family . ", " . $user->name_first}}</h1>
    <div class="row">
        <div class="col-sm-9 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <h3 class="mb-3">Photo</h3>
                            <img src="{{Storage::url('static/images/usernophoto.jpg')}}" alt="User Photo" class="img-fluid img-thumbnail">
                            <div class="mt-3">
                                <label for="file_userphoto" class="form-label">Upload a new photo</label>
                                <input class="form-control" type="file" id="file_userphoto">
                            </div>
                        </div>
                        <div class="col-sm-8 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3>Details</h3>
                                <a href="{{route('user.edit',$user->id)}}" type="button" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-fill"></i> Edit</a>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3"><p>Family Name:</p></div>
                                <div class="col-sm-9"><p>{{$user->name_family}}</p></div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3"><p>First Name:</p></div>
                                <div class="col-sm-9"><p>{{$user->name_first}}</p></div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3"><p>Email:</p></div>
                                <div class="col-sm-9"><p>{{$user->email}}</p></div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3"><p>Office:</p></div>
                                <div class="col-sm-9"><p>{{$user->office->office_name??'No office assigned'}}</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 mb-3">

        </div>
    </div>
</div>
@endsection