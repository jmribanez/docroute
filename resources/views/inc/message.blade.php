@if(session('status'))
    <div class="alert alert-{{session('status')}} alert-dismissible fade show" role="alert">
        {{session('message')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif