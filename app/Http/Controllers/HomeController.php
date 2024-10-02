<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRoute;
use App\Models\Notification;
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $shared_documents = null;
        // $documents = Document::where('user_id',Auth::user()->id)->union($shared_documents)->get();
        // $documents = DocumentRoute::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->get();
        $documents = $this->castToDocumentRoute(DocumentRoute::getFirst5DocumentsByUser(Auth::user()->id));
        // $unread_documents = DocumentRoute::select('documents.*','document_routes.document_id')->where('document_routes.user_id',Auth::user()->id)->whereNotNull('sent_on')->whereNull('received_on')->join('documents','documents.id','=','document_routes.document_id')->get();
        // $approvals = DocumentRoute::select('documents.*','document_routes.document_id')->where('document_routes.user_id',Auth::user()->id)->whereNotNull('sent_on')->where('action','Approve')->join('documents','documents.id','=','document_routes.document_id')->get();
        $notifications = Notification::where('receiver_id',Auth::user()->id)->orderBy('created_at')->get();
        return view('home')
            ->with('documents',$documents)
            ->with('notifications',$notifications);
    }
}
