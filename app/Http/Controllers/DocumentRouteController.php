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

    public function receive(string $id) {
        // This function receives the document_id
        // It will call a collection of records with this document_id
        // It will append the present date and time, user, and their present office to the new record.

        // Check if the document is being opened by the owner or someone who already opened it
        $document = Document::find($id);
        $docroute = DocumentRoute::where('document_id',$id)->andWhere('user_id',Auth::user()->id)->first(); 
        $document??abort('404','Document does not exist.');
        if($document->user->id == Auth::user()->id || count($docroute) > 0)
            return redirect('/document/'.$id);
        
    }
}
