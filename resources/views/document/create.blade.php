@extends('layouts.app')

@section('content')
<script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#txt_description',
    plugins: 'code table lists',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
  });
</script>
<div class="container">
    <h1 class="mb-3">New Document</h1>
    <form action="{{route('document.store')}}" method="POST" class="card" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input type="text" name="title" id="txt_title" class="form-control form-control-lg" placeholder="Title" required>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-lg btn-primary ms-3 d-inline-block">Save</button>
                    <a href="{{route('document.index')}}" type="button" class="btn btn-lg btn-outline-secondary ms-3">Cancel</a>
                </div>
            </div>
            <div class="mb-3">
                <textarea name="description" id="txt_description" cols="30" rows="10" class="form-control">{{$textareacontent??''}}</textarea>
            </div>
            <div class="mb-3">
                <label for="inputfile_attachments">File Attachments</label>
                <input type="file" name="file_attachments[]" id="inputfile_attachments" class="form-control" multiple>
            </div>
        </div>
    </form>
</div>
@endsection