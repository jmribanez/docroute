@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">New Document</h1>
    <form action="" method="POST" class="card">
        @csrf
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input type="text" name="title" id="txt_title" class="form-control form-control-lg" placeholder="Title" required>
                <button type="submit" class="btn btn-lg btn-outline-secondary ms-3"><i class="bi bi-trash"></i></button>
                <button type="submit" class="btn btn-lg btn-primary ms-3"><i class="bi bi-file-earmark-check-fill"></i> Save</button>
            </div>
            <div class="mb-3">
                <textarea name="description" id="txt_description" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="inputfile_attachments">File Attachments</label>
                <input type="file" name="file_attachments" id="inputfile_attachments" class="form-control" multiple>
            </div>
        </div>
    </form>
</div>
@endsection