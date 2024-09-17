<div>
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-0">{{$document->title}}</h3>
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#qrModal">QR</button>
    </div>
    <hr>
    <div>
        @foreach($document->routes as $dr)
        <span class="d-inline-block m-1 px-2 rounded-pill text-secondary-emphasis bg-secondary-subtle fw-normal"><abbr class="small text-decoration-none" title="{{$dr->routed_on}}">{{$dr->user->name_first.' '.$dr->user->name_family}}</abbr></span>
        @endforeach
        @if(!$userCanEdit && $isUserInRoute)
        <span type="button" class="d-inline-block m-1 px-2 rounded-pill text-white bg-primary fw-normal" data-bs-toggle="modal" data-bs-target="#confirmReceiptModal">Receive</span>
        @endif
        {{--<span class="d-inline-block m-1 px-2 rounded-pill text-secondary-emphasis bg-secondary-subtle fw-normal"><abbr class="small text-decoration-none" title="Sample date">Juan Dela Cruz</abbr></span> --}}
    </div>
    <hr>
    @if($isUserInRoute)
        {!!$document->description!!}
        @if (count($document->attachments)>0)
            <hr>
            <h5>Attachments</h5>
            <div class="list-group">
                @foreach ($document->attachments as $attachment)
                    <a href="{{asset('storage/attachments/'.$attachment->url)}}" target="_blank" class="list-group-item list-group-item-action">{{$attachment->orig_filename}}</a>
                @endforeach
            </div>
        @endif
    @else
        <p>You must receive the document first before being able to read the contents.</p>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmReceiptModal">Receive</button>
    @endif
    @if($userCanEdit)
    <hr>
    <div>
        <a href="{{route('document.edit',$document->id)}}" class="btn btn-sm btn-outline-secondary">Edit</a>
    </div>
    @endif
</div>
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">QR Code</h1>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                {!! QrCode::size(300)->generate(route('document.show',$document->id)) !!}
            </div>
            <div class="modal-footer">
                <a href="{{route('document.printqr',$document->id)}}" class="btn btn-primary">Print Preview</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@if(!$userCanEdit)
<div class="modal fade" id="confirmReceiptModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{route('documentroute.confirm',$document->id)}}" class="modal-content" method="POST">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Confirm receipt</h1>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @csrf
                <p>By clicking the Receive button, I confirm that I have the physical documents with me.</p>
                <p>I know that my name will be added in the list of personnel who have these documents.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" value="Confirm" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@endif