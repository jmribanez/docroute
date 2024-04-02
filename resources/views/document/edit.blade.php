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
    <h1 class="mb-3">Edit Document</h1>
    <form action="{{route('document.update',$document->id)}}" method="POST" class="card mb-3" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input type="text" name="title" id="txt_title" class="form-control form-control-lg" placeholder="Title" value="{{$document->title}}" required>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-lg btn-primary ms-3 d-inline-block">Save</button>
                    <a href="{{route('document.show',$document->id)}}" type="button" class="btn btn-lg btn-outline-secondary ms-3">Cancel</a>
                </div>
            </div>
            <div class="mb-3">
                <textarea name="description" id="txt_description" cols="30" rows="10" class="form-control">{!! $document->description !!}</textarea>
            </div>
            <div class="mb-3">
                <label for="inputfile_attachments">Add File Attachments</label>
                <input type="file" name="file_attachments[]" id="inputfile_attachments" class="form-control" multiple>
            </div>
        </div>
        <div class="card-footer p-3">
            <p class="mb-0">Created by: {{$document->user->name_first . " " .$document->user->name_family . " (" . $document->user->office->office_name . ")" . " on " . $document->created_at}}</p>
        </div>
    </form>
    <div class="card">
        <div class="card-body">
            @if (count($document->attachments)>0)
            <h5>Attachments</h5>
            <div class="list-group">
                @foreach ($document->attachments as $attachment)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{asset('storage/attachments/'.$attachment->url)}}" class="list-group-item-action stretched-link text-decoration-none z-1">{{$attachment->orig_filename}}</a>
                        <div class="z-1">
                            <form action="{{route('attachment.delete',$attachment->url)}}" method="post">@csrf <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                        </div>
                    </div>
                    
                @endforeach
            </div>
            @else
            <p>There are no attachments.</p>
            @endif
        </div>
    </div>
</div>
@endsection