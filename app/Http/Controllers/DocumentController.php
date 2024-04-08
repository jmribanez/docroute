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
        $documents = Document::where('user_id',Auth::user()->id)->get();
        if($showAll && Auth::user()->can('list all documents'))
            $documents = Document::all();
        return view('document.index')
            ->with('documents',$documents)
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
        // Check if document is being opened by owner or someone who has already viewed it.
        // If it has not yet been opeend by the guest, redirect to receive/doc_id
        $docroute = DocumentRoute::where('document_id',$id)->where('user_id',Auth::user()->id)->first(); 
        if($document->user->id != Auth::user()->id && $docroute == null) {
            return redirect('/receive/'.$id);
        }
        $docroute = DocumentRoute::where('document_id',$id)->get();
        return view('document.view')
            ->with('document',$document)
            ->with('docroute',$docroute);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
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
