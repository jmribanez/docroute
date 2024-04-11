<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRoute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DocumentRouteController extends Controller
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

    public function send(string $id) {
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $docroute = new DocumentRoute;
        $docroute->document_id = $id;
        $docroute->office_id = Auth::user()->office_id;
        $docroute->user_id = Auth::user()->id;
        $docroute->received_on = date("Y-m-d H:i:s");
        $docroute->save();
        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','The document can now be routed.');
    }

    public function receive(string $id) {
        // This function receives the document_id
        // It will call a collection of records with this document_id
        // It will append the present date and time, user, and their present office to the new record.

        // Check if the document is being opened by the owner or someone who already received it
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        // Check if the document has already been sent for routing (if there is already an entry for document_id)
        $docroute = DocumentRoute::where('document_id',$id)->get();
        if(count($docroute) == 0)
        abort('403','Document has not been sent for routing.');
        
        $docroute = DocumentRoute::where('document_id',$id)->where('user_id',Auth::user()->id)->whereNotNull('received_on')->first(); 
        if($document->user->id == Auth::user()->id && $docroute != null)
            return redirect('/document/'.$id);
        else {
            $docroute = DocumentRoute::where('document_id',$id)->get();
            return view('documentroute.receive')
                ->with('document', $document)
                ->with('docroute', $docroute);
        }
    }

    public function confirm(string $id) {
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $docroute = DocumentRoute::where('document_id',$id)->first();
        $docroute??abort('403','Document has not been sent for routing.');

        $docroute = DocumentRoute::where('user_id',Auth::user()->id)->where('document_id',$id)->first();
        if($docroute == null) {
            $docroute = new DocumentRoute;
            $docroute->document_id = $id;
            $docroute->office_id = Auth::user()->office_id;
            $docroute->user_id = Auth::user()->id;
            $docroute->received_on = date("Y-m-d H:i:s");
            $docroute->save();
        } else {
            $docroute->received_on = date("Y-m-d H:i:s");
            $docroute->update();
        }
        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','The document has been received.');
    }
}
