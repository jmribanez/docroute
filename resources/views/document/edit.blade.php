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
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="txtDocType" class="form-label">Document Type</label>
                    <select name="document_type" id="txtDocType" class="form-select" value="{{$document->document_type}}">
                        <option value="Internal" {{($document->document_type=='Internal')?'selected':'';}}>Internal</option>
                        <option value="Incoming" {{($document->document_type=='Incoming')?'selected':'';}}>Incoming</option>
                        <option value="Outgoing" {{($document->document_type=='Outgoing')?'selected':'';}}>Outgoing</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="txtExternalParty" class="form-label">External Party</label>
                    <input type="text" name="external_party" id="txtExternalParty" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label for="inputfile_attachments">Add File Attachments</label>
                <input type="file" name="file_attachments[]" id="inputfile_attachments" class="form-control" accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .jpg, .jpeg, .png" multiple>
            </div>
        </div>
        <div class="card-footer p-3 d-flex justify-content-between align-items-center">
            <p class="mb-0">Created by: {{$document->user->name_first . " " .$document->user->name_family . " (" . $document->user->office->office_name . ")" . " on " . $document->created_at}}</p>
            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
        </div>
    </form>
    <div class="card">
        <div class="card-body">
            <h5>Attachments</h5>
            @if (count($document->attachments)>0)
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
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalBox" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{route('document.destroy',$document->id)}}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h1 class="modal-title fs-5">Confirm Delete</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are about to delete the document titled {{$document->title}} and its attachments.</p>
                <p>Are you sure you want to continue? This document cannot be recovered if <strong>Permanently Delete</strong> is checked.</p>
            </div>
            <div class="modal-footer">
                <div class="form-check me-auto small">
                    <input class="form-check-input" type="checkbox" name="permanentlyDelete" value="true" id="chkPermaDelete">
                    <label class="form-check-label" for="chkPermaDelete">Permanently Delete</label>
                </div>
                <input type="submit" value="Delete" class="btn btn-danger">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
@endsection