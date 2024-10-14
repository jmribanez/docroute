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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-3">New Document</h1>
            <form name="mainform" action="{{route('document.store')}}" method="POST" class="card" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <input type="text" name="title" id="txt_title" class="form-control form-control-lg" placeholder="Title" required>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-lg btn-primary ms-3 d-inline-block">Save</button>
                            <a href="{{route('home')}}" type="button" class="btn btn-lg btn-outline-secondary ms-3">Cancel</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea name="description" id="txt_description" cols="30" rows="10" class="form-control">{{$textareacontent??''}}</textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="txtDocType" class="form-label">Document Type</label>
                            <select name="document_type" id="txtDocType" class="form-select">
                                <option value="Internal">Internal</option>
                                <option value="Incoming">Incoming</option>
                                <option value="Outgoing">Outgoing</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="txtExternalParty" class="form-label">External Party</label>
                            <input type="text" name="external_party" id="txtExternalParty" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="inputfile_attachments">File Attachments</label>
                        <input type="file" name="file_attachments[]" id="inputfile_attachments" class="form-control" accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .jpg, .jpeg, .png" multiple>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <h3 class="mb-3 mt-3">AI Assistance</h3>
            <div class="card">
                <div class="card-body" id="chatMessages">
                    
                </div>
                <form class="card-footer d-flex flex-column py-3" id="chatform" name="chatform" method="POST" action="{{route('chat')}}">
                    @csrf
                    <textarea name="prompt" id="txtprompt" cols="30" rows="2" class="form-control" placeholder="Ask a question"></textarea>
                    <input type="submit" value="Send" class="btn btn-sm btn-primary mt-3">
                    {{-- <button class="btn btn-sm btn-primary mt-3" onclick="callChat()">Send</button> --}}
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    conversation = [];
    conversation.push({
        'from': 'Assistant',
        'message': 'What can I help you with?',
    })
    function updateChat() {
        document.getElementById('chatMessages').innerHTML = "";
        for(var i=0; i<conversation.length; i++) {
            document.getElementById('chatMessages').innerHTML += '<div class="alert alert-light"><strong class="small">' + conversation[i].from + '</strong><br>' + conversation[i].message + '</div>';
        }
    }
    function makeBold(sometext) {
        var bold = /\*\*(.*?)\*\*/gm;
        var newtext = sometext.replace(bold, '<strong>$1</strong>');            
        return newtext;
    }
    updateChat();
    $('#chatform').submit(function(e) {
        conversation.push({
            'from': 'User',
            'message': $('#txtprompt').val(),
        });
        updateChat();
        e.preventDefault();
        const data = {
            prompt: $('#txtprompt').val(),
            _token: '{{ csrf_token() }}',
        };
        document.getElementById('txtprompt').value = "";
        $.ajax({
            type: 'POST',
            url: '{{route("chat")}}',
            data: JSON.stringify(data),
            contentType: 'application/json',
        }).done((data) => {
            conversation.push({
                'from': 'Assistant',
                'message': makeBold(data.choices[0].message.content),
            });
            updateChat();
        }).fail((err) => {
            console.error(err);
        });
    });
</script>
@endsection