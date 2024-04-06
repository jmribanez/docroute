@extends('layouts.app')

@section('content')
<script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#txt_content',
    plugins: 'code table lists',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
  });
</script>
<div class="container">
    <h1 class="mb-3">Edit Template</h1>
    <form action="{{route('template.update',$template->id)}}" method="POST" class="card mb-3" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input type="text" name="name" id="txt_name" class="form-control form-control-lg" placeholder="Template name" value="{{$template->name}}" required>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-lg btn-primary ms-3 d-inline-block">Save</button>
                    <a href="{{route('template.index')}}" type="button" class="btn btn-lg btn-outline-secondary ms-3">Cancel</a>
                </div>
            </div>
            <div class="mb-3">
                <input type="text" name="description" id="txt_description" class="form-control" placeholder="Describe this template." value="{{$template->description}}">
            </div>
            <div class="mb-3">
                <textarea name="content" id="txt_content" cols="30" rows="10" class="form-control" placeholder="Content of this template.">{!!$template->content!!}</textarea>
            </div>
        </div>
        <div class="card-footer p-3 d-flex justify-content-between align-items-center">
            <p class="mb-0">Created on: {{$template->created_at}}</p>
            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
        </div>
    </form>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalBox" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{route('template.destroy',$template->id)}}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h1 class="modal-title fs-5">Confirm Delete</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are about to delete the template named {{$template->name}}.</p>
                <p>Are you sure you want to continue? This template cannot be recovered.</p>
            </div>
            <div class="modal-footer">
                <input type="submit" value="Delete" class="btn btn-danger">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
@endsection