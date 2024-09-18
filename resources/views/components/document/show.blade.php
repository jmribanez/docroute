<div class="d-flex flex-column">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-0">{{$document->title}}</h3>
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#qrModal"><i class="bi bi-qr-code"></i> QR</button>
    </div>
    <hr>
    <div>
        <span type="button" class="d-inline-block m-1 px-2 rounded-pill text-white bg-secondary fw-normal" data-bs-toggle="modal" data-bs-target="#detailedRoute"><i class="bi bi-list-ul"></i></span>
        @foreach($document->routes as $dr)
        <?php
        $bgtextcolor = "text-secondary-emphasis bg-secondary-subtle";
        switch($dr->action) {
            case 'Signed':
            $bgtextcolor = "text-primary-emphasis bg-success-subtle";
            break;
            case 'Declined':
            $bgtextcolor = "text-danger-emphasis bg-danger-subtle";
            break;
        }
        $icon = '<i class="bi bi-envelope-paper-fill me-1"></i>';
        switch($dr->state) {
            case 'Created':
            $icon = '<i class="bi bi-pencil-fill me-1"></i>';
            break;
            case 'Completed':
            $icon = '<i class="bi bi-check-circle-fill me-1"></i>';
            break;
        }
        ?>
        <span class="d-inline-block m-1 px-2 rounded-pill {{$bgtextcolor}} fw-normal"><abbr class="small text-decoration-none" title="{{$dr->routed_on}}">{!!$icon!!} {{$dr->user->name_first.' '.$dr->user->name_family}}</abbr></span>
        @endforeach
        @if(!$userCanEdit && $isUserInRoute && !$routeIsFinished)
        <span type="button" class="d-inline-block m-1 px-2 rounded-pill text-white bg-primary fw-normal" data-bs-toggle="modal" data-bs-target="#confirmReceiptModal">Receive</span>
        @endif
        {{--<span class="d-inline-block m-1 px-2 rounded-pill text-secondary-emphasis bg-secondary-subtle fw-normal"><abbr class="small text-decoration-none" title="Sample date">Juan Dela Cruz</abbr></span> --}}
    </div>
    <hr>
    @if($isUserInRoute)
    <div class="flex-fill">
        {!!$document->description!!}
    </div>
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
    <div>
        <p>You must receive the document first before being able to read the contents.</p>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmReceiptModal">Receive</button>
    </div>
    @endif
    @if($userCanEdit)
    <hr>
    <div>
        <a href="{{route('document.edit',$document->id)}}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>
        <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#actionModal">Set action</button>
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#finishModal">Finish route</button>
    </div>
    @endif
</div>
<div class="modal fade" id="detailedRoute" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Route details</h1>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    @foreach ($document->routes as $dr)
                    <li class="list-group-item d-flex">
                        <?php 
                        $icon = '<i class="bi bi-envelope-paper-fill me-1"></i>';
                        switch($dr->state) {
                            case 'Created':
                            $icon = '<i class="bi bi-pencil-fill me-1"></i>';
                            break;
                            case 'Completed':
                            $icon = '<i class="bi bi-check-circle-fill me-1"></i>';
                            break;
                        }
                        ?>
                        <div class="pe-1">
                            {!!$icon!!}
                        </div>
                        <div>
                            <p class="fw-semibold mb-0"><a href="{{route('user.show',$dr->user_id)}}" class="text-body-secondary text-decoration-none">{{$dr->user->name_first . " " . $dr->user->name_family}}</a></p>
                            <div>
                                <p class="small mb-0">{{$dr->state}} on {{$dr->routed_on}}</p>
                                @if($dr->action!=null) <p class="small mb-0">{{$dr->action}} on {{$dr->acted_on}}</p>  @endif
                                @if($dr->comment!=null) <p class="small mb-0">Comment: {{$dr->comment}}</p> @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
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
@if($userCanEdit)
<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{route('documentroute.setaction',$document->id)}}" method="post" class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Set action</h1>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="mb-3">
                    <label for="selAction" class="form-label">Action</label>
                    <select name="action" id="selAction" class="form-select">
                        <option disabled selected>Select an option</option>
                        <option value="Signed">Signed</option>
                        <option value="Declined">Declined</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="txtComment" class="form-label">Comments</label>
                    <textarea name="comment" id="txtComment" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" value="Apply" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="finishModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{route('documentroute.finishroute',$document->id)}}" method="post" class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Finish route</h1>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="mb-3">
                    <label for="selAction" class="form-label">Action</label>
                    <select name="action" id="selAction" class="form-select">
                        <option disabled selected>Select an option</option>
                        <option value="Released">Released</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="txtComment" class="form-label">Comments</label>
                    <textarea name="comment" id="txtComment" class="form-control"></textarea>
                </div>
                <p class="mb-3 small">Finishing a document route prevents further editing.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" value="Apply" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@endif