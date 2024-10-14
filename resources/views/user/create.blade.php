@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">New user</h1>
    <div class="row">
        <div class="col-sm-9 mb-3">
            <form class="card" action="{{route('user.store')}}" method="POST" onsubmit="return confirmPassword(event)" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-sm-4 mb-3">
                            <h3 class="mb-3">Photo</h3>
                            <img src="{{Storage::url('static/images/usernophoto.jpg')}}" alt="User Photo" class="img-fluid img-thumbnail">
                            <div class="mt-3">
                                <label for="file_userphoto" class="form-label">Upload a new photo</label>
                                <input class="form-control" type="file" id="file_userphoto" name="user_photo">
                            </div>
                        </div>
                        <div class="col-sm-8 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3>Details</h3>
                                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check-lg"></i> Save</button>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="txt_name_family" class="col-form-label">Family Name</label>
                                </div>
                                <div class="col-sm-9">
                                    <input id="txt_name_family" name="name_family" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="txt_name_first" class="col-form-label">First Name</label>
                                </div>
                                <div class="col-sm-9">
                                    <input id="txt_name_first" name="name_first" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="txt_email" class="col-form-label">Email</label>
                                </div>
                                <div class="col-sm-9">
                                    <input id="txt_email" name="email" type="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="sel_office" class="col-form-label">Office</label>
                                </div>
                                <div class="col-sm-9">
                                    <select name="office" id="sel_office" class="form-select">
                                        <option disabled selected>No office selected</option>
                                        @foreach($offices as $office)
                                        <option value="{{$office->id}}">{{$office->office_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="txt_password" class="col-form-label">Password</label>
                                </div>
                                <div class="col-sm-9">
                                    <input id="txt_password" name="password" type="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="txt_conpassword" class="col-form-label">Confirm Password</label>
                                </div>
                                <div class="col-sm-9">
                                    <input id="txt_conpassword" name="confirmpassword" type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Security</h3>
                    <label class="mb-0" for="selectAccessLevel">Access Level</label>
                    <div class="input-group">
                        <select class="form-select" id="selectAccessLevel" name="accesslevel" disabled>
                          <option value="Administrator">Administrator</option>
                          <option value="Standard" selected>Standard</option>
                        </select>
                        <button class="btn btn-outline-secondary" type="button" disabled>Set</button>
                    </div>
                    <p class="small mt-0"><em>By default, all new users are created with a Standard account which can be changed later.</em></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmPassword(event) {
        p1 = document.getElementById('txt_password').value;
        p2 = document.getElementById('txt_conpassword').value;
        if(p1==p2) {
            return true;
        } else {
            event.preventDefault();
            alert("Password does not match its confirmation.");
            return false;
        }
    }
</script>
@endsection