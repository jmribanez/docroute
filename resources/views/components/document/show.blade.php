<div>
    <h3>{{$document->title}}</h3>
    <hr>
    <div>
        @foreach($document->routes as $dr)
        <span class="p-2 rounded-pill text-secondary-emphasis bg-secondary-subtle fw-normal">{{$dr->user->name_first.' '.$dr->user->name_family}}</span>
        @endforeach
    </div>
    <hr>
    @if($isUserInRoute)
        {!!$document->description!!}
        @if (count($document->attachments)>0)
            <hr>
            <h5>Attachments</h5>
            <div class="list-group">
                @foreach ($document->attachments as $attachment)
                    <a href="{{asset('storage/attachments/'.$attachment->url)}}" class="list-group-item list-group-item-action">{{$attachment->orig_filename}}</a>
                @endforeach
            </div>
        @endif
    @else
        <p>You must receive the document first before being able to read the contents.</p>
        <form action="{{route('documentroute.confirm',$document->id)}}" method="post">
            @csrf
            <p class="small">By clicking the Receive button, I confirm that I have the physical documents with me.</p>
            <input type="submit" value="Receive" class="btn btn-primary">
        </form>
    @endif
</div>