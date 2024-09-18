<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $shared_documents = null; /* TODO: Replace with docs where owner is in the document route. */
        // $documents = Document::where('user_id',Auth::user()->id)->union($shared_documents)->get();
        $documents = DocumentRoute::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->get();
        // $unread_documents = DocumentRoute::select('documents.*','document_routes.document_id')->where('document_routes.user_id',Auth::user()->id)->whereNotNull('sent_on')->whereNull('received_on')->join('documents','documents.id','=','document_routes.document_id')->get();
        
        // $approvals = DocumentRoute::select('documents.*','document_routes.document_id')->where('document_routes.user_id',Auth::user()->id)->whereNotNull('sent_on')->where('action','Approve')->join('documents','documents.id','=','document_routes.document_id')->get();
        return view('home')
            ->with('documents',$documents);
    }
}
