<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Document;
use App\Models\DocumentRoute;
use App\Models\Notification;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DocumentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
        // 9/11: Commented as the edit must be viewable publicly if and only if external_party is not null
    }

    private function castToDocumentRoute($record) {
        $documents = array();
        foreach($record as $r) {
            $d = new DocumentRoute();
            $d->id = $r->id;
            $d->document_id = $r->document_id;
            $d->office_id = $r->office_id;
            $d->user_id = $r->user_id;
            $d->routed_on = $r->routed_on;
            $d->state = $r->state;
            $d->action = $r->action;
            $d->acted_on = $r->acted_on;
            $d->comment = $r->comment;
            array_push($documents, $d);
        }
        return $documents;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /**
         * Note: Have a can('see-own-docs') and can('see-all-docs')
         * You get the idea.
         */
        $showAll = $request->all=='1'?true:false;
        //$shared_documents = null; /* TODO: Update this with documents that don't belong to you but you are in the route. */
        $user = Auth::user();
        // $documents = DocumentRoute::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->paginate(10);
        $documents = $this->castToDocumentRoute(DocumentRoute::getDocumentByUser(Auth::user()->id));
        // $documents = DocumentRoute::all()->unique('document_id');
        // $unread_documents = DocumentRoute::select('documents.*','document_routes.document_id')->where('document_routes.user_id',Auth::user()->id)->whereNotNull('sent_on')->whereNull('received_on')->join('documents','documents.id','=','document_routes.document_id')->get();
        $selectedDocument = null;
        if($showAll && Auth::user()->can('list all documents'))
            $documents = $this->castToDocumentRoute(DocumentRoute::getAllDocuments());
        return view('document.index')
            ->with('documents',$documents)
            ->with('selectedDocument',$selectedDocument)
            ->with('showAll',$showAll)
            ->with('mode','index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $showAll = $request->all=='1'?true:false;
        $user = Auth::user();
        $documents = DocumentRoute::getDocumentByUser(Auth::user()->id);
        if($showAll && Auth::user()->can('list all documents'))
            $documents = DocumentRoute::getAllDocuments();
        $textareacontent = null;
        if($request->t != null) {
            $template = Template::find($request->t);
            $template??abort('404','Template does not exist.');
            $textareacontent = $template->content;
        }
        return view('document.create')
            ->with('documents',$documents)
            ->with('textareacontent',$textareacontent)
            ->with('showAll',$showAll)
            ->with('mode','create');
    }

    private function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';

        // Generate random characters until the string reaches desired length
        for ($i = 0; $i < $length; $i++) {
            $random_index = random_int(0, $characters_length - 1);
            $random_string .= $characters[$random_index];
        }
        return $random_string;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $length = 6;
        $document = new Document;
        $document->id = $this->generateRandomString(6);
        $document->title = $request->title;
        $document->description = $request->description;
        $document->user_id = Auth::user()->id;
        // 9/11: Added the following 2 lines based on updated specifications
        $document->document_type = $request->document_type??'Internal';
        $document->external_party = empty(trim($request->external_party))?null:$request->external_party;
        $document->save();

        // check if it has attachments.
        if($request->hasFile('file_attachments')) {
            $allowedFileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png'];
            $files = $request->file('file_attachments');
            foreach($files as $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                $check = in_array($extension, $allowedFileExtension);
                if($check) {
                    $newFileName = $document->id . "_" . $this->generateRandomString(6);
                    $document->attachments()->create([
                        'orig_filename' => $filename,
                        'url' => $newFileName . "." . $extension
                    ]);
                    $file->storeAs('attachments',$newFileName . "." . $extension);
                }
            }
        }
        
        // create a document route, with the office_id as the present office_id of the user
        $documentRoute = new DocumentRoute();
        $documentRoute->document_id = $document->id;
        $documentRoute->office_id = Auth::user()->office_id;
        $documentRoute->user_id = Auth::user()->id;
        $documentRoute->routed_on = date("Y-m-d H:i:s");
        $documentRoute->state = 'Created';
        $documentRoute->save();

        return redirect('/document/'.$document->id)
            ->with('status','success')
            ->with('message', 'Document titled ' . $document->title . ' has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        /**
         * NOTE: September 11, 2024
         * Old Code. Needs replacement based on feedback.
         * 
         */
        /* Replaced as of September 11
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        // Check if document is being opened by owner
        // Recepient needs to have sent_on and received_on to view document
        $mydocroute = DocumentRoute::where('document_id',$id)->where('user_id',Auth::user()->id)->whereNotNull('sent_on')->whereNotNull('received_on')->first(); 
        if($document->user_id != Auth::user()->id && $mydocroute == null) {
            return redirect('/receive/'.$id);
        }

        $docroute = DocumentRoute::where('document_id',$id)->get();
        $rejects = DocumentRoute::where('document_id',$id)->where('action','Rejected')->get();
        $hasreject = count($rejects)>0;
        $myturn = false;
        if($mydocroute != null)
            $myturn = $mydocroute->action == "Approve";
        return view('document.view')
            ->with('document',$document)
            ->with('docroute',$docroute)
            ->with('mydocroute',$mydocroute)
            ->with('myturn',$myturn)
            ->with('hasreject',$hasreject);
        */
        
        // check if user is in route
        // check if user can edit. Rule is current user only except when state is 'released'
        if(Auth::guest()) {
            $selectedDocument = Document::find($id);
            $isUserInRoute = false;
            $userCanEdit = false;
            $routeIsFinished = DocumentRoute::where('document_id',$id)->orderBy('routed_on','desc')->first()->state == 'Completed'?true:false;
            return view('document.view')
                ->with('document',$selectedDocument)
                ->with('isUserInRoute',$isUserInRoute)
                ->with('userCanEdit',$userCanEdit)
                ->with('routeIsFinished',$routeIsFinished);
        } else {
            $showAll = $request->all=='1'?true:false;
            $user = Auth::user();
            $documents = $this->castToDocumentRoute(DocumentRoute::getDocumentByUser(Auth::user()->id));
            if($showAll && Auth::user()->can('list all documents'))
                $documents = $this->castToDocumentRoute(DocumentRoute::getAllDocuments());

            $selectedDocument = Document::find($id);
            $selectedDocument??abort('404','Document does not exist.');
            $isUserInRoute = DocumentRoute::where('document_id',$id)->where('user_id',Auth::user()->id)->count()>0?true:false;
            $userCanEdit = (DocumentRoute::where('document_id',$id)->orderBy('routed_on','desc')->first()->user_id == Auth::user()->id && DocumentRoute::where('document_id',$id)->orderBy('routed_on','desc')->first()->state != 'Completed')?true:false;
            $routeIsFinished = DocumentRoute::where('document_id',$id)->where('state','Completed')->count() > 0?true:false;
            return view('document.index')
            ->with('documents',$documents)
            ->with('showAll',$showAll)
            ->with('selectedDocument',$selectedDocument)
            ->with('isUserInRoute',$isUserInRoute)
            ->with('userCanEdit',$userCanEdit)
            ->with('routeIsFinished',$routeIsFinished)
            ->with('mode','show');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        /**
         * NOTE: Sept 11, 2024
         * This is old complicated code.
         */
        /*
        $this->middleware('auth');
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $docroute = DocumentRoute::where('document_id',$id)->get();
        if(count($docroute)>0)
            return redirect('document/'.$id)
                ->with('status','info')
                ->with('message','Prepared documents are no longer editable.');
        return view('document.edit')
            ->with('document',$document);
        */
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        // check if user is in route
        // check if user can edit. Rule is current user only except when state is 'released'
        $isUserInRoute = DocumentRoute::where('document_id',$id)->where('user_id',Auth::user()->id)->count()>0?true:false;
        $userCanEdit = (DocumentRoute::where('document_id',$id)->orderBy('routed_on','desc')->first()->user_id == Auth::user()->id && DocumentRoute::where('document_id',$id)->orderBy('routed_on','desc')->first()->state != 'Completed')?true:false;
        if(!$isUserInRoute || !$userCanEdit)
            return redirect()->route('document.show',$id);
        return view('document.edit')
            ->with('document',$document)
            ->with('isUserInRoute',$isUserInRoute)
            ->with('userCanEdit',$userCanEdit)
            ->with('mode','edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $length = 6;
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $document->title = $request->title;
        $document->description = $request->description;
        $document->document_type = $request->document_type;
        $document->external_party = empty(trim($request->external_party))?null:$request->external_party;
        $document->update();

        // check if it has attachments.
        if($request->hasFile('file_attachments')) {
            $allowedFileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png'];
            $files = $request->file('file_attachments');
            foreach($files as $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                $check = in_array($extension, $allowedFileExtension);
                if($check) {
                    $newFileName = $document->id . "_" . $this->generateRandomString(6);
                    $document->attachments()->create([
                        'orig_filename' => $filename,
                        'url' => $newFileName . "." . $extension
                    ]);
                    $file->storeAs('attachments',$newFileName . "." . $extension);
                }
            }
        }

        return redirect('/document/'.$document->id)
            ->with('status','success')
            ->with('message', 'Document titled ' . $document->title . ' has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $attachments = $document->attachments;
        if(count($attachments) > 0) {
            foreach($attachments as $attachment) {
                Storage::delete('attachments/'.$attachment->url);
                $attachment->delete();
            }
        }
        $documentRoutes = DocumentRoute::where('document_id',$id)->get();
        $documentRoutes->delete();
        $notifications = Notification::where('document_id',$id)->get();
        $notifications->delete();
        //if($request->permanentlyDelete == "true")
            $document->forceDelete();
        //else
        //    $document->delete();
        return redirect('/document')
            ->with('status','success')
            ->with('message', 'Document titled ' . $document->title . ' has been deleted.');
    }

    public function printQR(string $id) {
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        return view('document.printqr')
            ->with('document',$document);
    }
}
