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
    <h1 class="mb-3">New Template</h1>
    <form action="{{route('template.store')}}" method="POST" class="card" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input type="text" name="name" id="txt_name" class="form-control form-control-lg" placeholder="Template name" required>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-lg btn-primary ms-3 d-inline-block">Save</button>
                    <a href="{{route('template.index')}}" type="button" class="btn btn-lg btn-outline-secondary ms-3">Cancel</a>
                </div>
            </div>
            <div class="mb-3">
                <input type="text" name="description" id="txt_description" class="form-control" placeholder="Describe this template." required>
            </div>
            <div class="mb-3">
                <textarea name="content" id="txt_content" cols="30" rows="10" class="form-control" placeholder="Content of this template."></textarea>
            </div>
        </div>
    </form>
</div>
@endsection