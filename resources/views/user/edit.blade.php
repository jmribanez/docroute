@extends('layouts.app')

@section('content')
<div class="container">
    <p class="mb-0">EDITING</p>
    <h1 class="mb-3">{{$user->name_family . ", " . $user->name_first}}</h1>
    <div class="row">
        <div class="col-sm-9 mb-3">
            <form class="card" action="{{route('user.update',$user->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-sm-4 mb-3">
                            <h3 class="mb-3">Photo</h3>
                            <img src="{{($user->user_photo_url!=null)?asset('storage/user_photos/'.$user->user_photo_url):Storage::url('static/images/usernophoto.jpg')}}" alt="User Photo" class="img-fluid img-thumbnail">
                            <div class="mt-3">
                                <label for="file_userphoto" class="form-label">Upload a new photo</label>
                                <input class="form-control" type="file" id="file_userphoto" name="user_photo">
                            </div>
                        </div>
                        <div class="col-sm-8 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3>Details</h3>
                                <div>
                                    <button type="submit" class="btn btn-primary me-2"><i class="bi bi-check-lg"></i> Save</button>
                                    <a href="{{route('user.show',$user->id)}}" class="btn btn-outline-secondary">Cancel</a>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="txt_name_family" class="col-form-label">Family Name</label>
                                </div>
                                <div class="col-sm-9">
                                    <input id="txt_name_family" name="name_family" type="text" value="{{$user->name_family}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="txt_name_first" class="col-form-label">First Name</label>
                                </div>
                                <div class="col-sm-9">
                                    <input id="txt_name_first" name="name_first" type="text" value="{{$user->name_first}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="txt_email" class="col-form-label">Email</label>
                                </div>
                                <div class="col-sm-9">
                                    <input id="txt_email" name="email" type="email" value="{{$user->email}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-sm-3">
                                    <label for="sel_office" class="col-form-label">Office</label>
                                </div>
                                <div class="col-sm-9">
                                    <select name="office" id="sel_office" class="form-select">
                                        <option value="" {{($user->office_id??false)?'':'selected'}}>No office selected</option>
                                        @foreach($offices as $office)
                                        <option value="{{$office->id}}" {{($user->office_id==$office->id)?'selected':''}}>{{$office->office_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-3 mb-3">
            <form class="card" action="{{route('user.update',$user->id)}}" method="POST">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <h3 class="mb-3">Security</h3>
                    <label class="mb-0" for="selectAccessLevel">Access Level</label>
                    <div class="input-group mb-3">
                        <select class="form-select" id="selectAccessLevel" name="accessLevel">
                          <option value="Administrator" {{($user->hasRole('Administrator'))?'selected':''}}>Administrator</option>
                          <option value="Standard" {{($user->hasRole('Administrator'))?'':'selected'}}>Standard</option>
                        </select>
                        <input class="btn btn-outline-secondary" type="submit" value="Set">
                    </div>
                    <hr>
                    <div class="d-grid gap-2 mb-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalReset">Reset password</button>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDelete">Delete account</button>
                    </div>  
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalDelete" tabindex="-1">
    <form class="modal-dialog" action="{{route('user.destroy',$user->id)}}" method="POST">
        <div class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the user {{$user->name_first . " " . $user->name_family}}?</p>
                <p>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-danger" value="Delete">
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="modalReset" tabindex="-1">
    <form action="{{route('user.update',$user->id)}}" method="POST" class="modal-dialog" onsubmit="confirmPassword()">
        <div class="modal-content">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h5 class="modal-title">Password Reset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Resetting a user's password removes their access until they are informed of their new password.</p>
                <p>To continue, type in a new password for {{$user->name_first . " " . $user->name_family}}.</p>
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-4">
                        <label for="txt_newpassword" class="col-form-label">New Password: </label>
                    </div>
                    <div class="col-8">
                        <input type="password" name="newpassword" id="txt_newpassword" class="form-control" required>
                    </div>
                </div>
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-4">
                        <label for="txt_confirmnewpassword" class="col-form-label">Confirm Password: </label>
                    </div>
                    <div class="col-8">
                        <input type="password" name="confirmnewpassword" id="txt_confirmnewpassword" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Reset">
            </div>
        </div>
    </form>
</div>
<script>
    function confirmPassword() {
        p1 = document.getElementById('txt_newpassword').value;
        p2 = document.getElementById('txt_confirmnewpassword').value;
        if(p1==p2) {
            return true;
        }
        alert("Password does not match its confirmation.");
        return false;
    }
</script>
@endsection