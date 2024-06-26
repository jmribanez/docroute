<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Document;
use App\Models\DocumentRoute;
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
        $this->middleware('auth');
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
        $shared_documents = DocumentRoute::select('documents.*')->where('document_routes.user_id',Auth::user()->id)->whereNotNull('sent_on')->whereNotNull('received_on')->join('documents','documents.id','=','document_routes.document_id');
        $documents = Document::where('user_id',Auth::user()->id)->union($shared_documents)->get();
        $unread_documents = DocumentRoute::select('documents.*','document_routes.document_id')->where('document_routes.user_id',Auth::user()->id)->whereNotNull('sent_on')->whereNull('received_on')->join('documents','documents.id','=','document_routes.document_id')->get();
        
        if($showAll && Auth::user()->can('list all documents'))
            $documents = Document::all();
        return view('document.index')
            ->with('documents',$documents)
            ->with('unread_documents',$unread_documents)
            ->with('showAll',$showAll);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $textareacontent = null;
        if($request->t != null) {
            $template = Template::find($request->t);
            $template??abort('404','Template does not exist.');
            $textareacontent = $template->content;
        }
        return view('document.create')
            ->with('textareacontent',$textareacontent);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $length = 6;
        $document = new Document;
        $document->id = substr(bin2hex(random_bytes(ceil($length/2))),0,$length);
        $document->title = $request->title;
        $document->description = $request->description;
        $document->user_id = Auth::user()->id;
        $document->save();

        // check if it has attachments.
        if($request->hasFile('file_attachments')) {
            $allowedFileExtension = ['pdf', 'jpg', 'jpeg', 'png'];
            $files = $request->file('file_attachments');
            foreach($files as $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                $check = in_array($extension, $allowedFileExtension);
                if($check) {
                    $newFileName = $document->id . "_" . substr(bin2hex(random_bytes(ceil($length/2))),0,$length);
                    $document->attachments()->create([
                        'orig_filename' => $filename,
                        'url' => $newFileName . "." . $extension
                    ]);
                    $file->storeAs('attachments',$newFileName . "." . $extension);
                }
            }
        }
        
        // create a document route, place it as the office of the user
        // create a document approval route, use JavaScript to create a JSON object

        return redirect('/document/'.$document->id)
            ->with('status','success')
            ->with('message', 'Document titled ' . $document->title . ' has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $docroute = DocumentRoute::where('document_id',$id)->get();
        if(count($docroute)>0)
            return redirect('document/'.$id)
                ->with('status','info')
                ->with('message','Prepared documents are no longer editable.');
        return view('document.edit')
            ->with('document',$document);
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
        $document->update();

        // check if it has attachments.
        if($request->hasFile('file_attachments')) {
            $allowedFileExtension = ['pdf', 'jpg', 'jpeg', 'png'];
            $files = $request->file('file_attachments');
            foreach($files as $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                $check = in_array($extension, $allowedFileExtension);
                if($check) {
                    $newFileName = $document->id . "_" . substr(bin2hex(random_bytes(ceil($length/2))),0,$length);
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
        //if($request->permanentlyDelete == "true")
            $document->forceDelete();
        //else
        //    $document->delete();
        return redirect('/document')
            ->with('status','success')
            ->with('message', 'Document titled ' . $document->title . ' has been deleted.');
    }
}
