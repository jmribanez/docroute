<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRoute;
use App\Models\User;
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

    public function prepare(string $id) {
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $docroute = new DocumentRoute;
        $docroute->document_id = $id;
        $docroute->office_id = Auth::user()->office_id;
        $docroute->user_id = Auth::user()->id;
        $docroute->received_on = date("Y-m-d H:i:s");
        $docroute->action_order = 1;
        $docroute->action = "Draft";
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
            abort('403','Document has not been prepared for routing.');
        $docroute = DocumentRoute::where('document_id',$id)->where('user_id',Auth::user()->id)->whereNotNull('received_on')->first(); 
        if($document->user->id == Auth::user()->id || $docroute != null)
            return redirect('/document/'.$id);
        else {
            $docroute = DocumentRoute::where('document_id',$id)->get();
            $mydocroute = DocumentRoute::where('document_id',$id)->where('user_id',Auth::user()->id)->first();
            $prevactedroute = DocumentRoute::whereNotNull('acted_on')->orderBy('action_order','DESC')->first();
            $prevactedroute??abort('403','Document has not been sent for routing.');
            $myturn = intval($mydocroute->action_order) == (intval($prevactedroute->action_order)+1);
            return view('documentroute.receive')
                ->with('document', $document)
                ->with('docroute', $docroute)
                ->with('mydocroute',$mydocroute)
                ->with('myturn',$myturn);
        }
    }

    public function confirm(string $id) {
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $docroute = DocumentRoute::where('document_id',$id)->first();
        $docroute??abort('403','Document has not been prepared or sent for routing.');
        $docroute = DocumentRoute::where('user_id',Auth::user()->id)->where('document_id',$id)->first();
        $docroute??abort('403','You are not authorized to view this document. Please contact the author or an approver to receive access.');

        $docroute->received_on = date("Y-m-d H:i:s");
        $docroute->update();

        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','The document has been received.');
    }

    public function addRecepients(Request $request) {
        $document_id = $request->document_id;
        $recepients = json_decode($request->recepients);
        $document = Document::find($document_id);
        $document??abort('404','Document does not exist.');
        $hasApproval = false;
        foreach($recepients as $r) {
            $user = User::find($r->id);
            if($user==null)
                continue;
            // Check if user is in the document route
            $docroute = DocumentRoute::where('user_id',$r->id)->where('document_id',$document_id)->first();
            if($docroute != null)
                continue;
            $docroute = DocumentRoute::where('document_id',$document_id)->orderBy('action_order','DESC')->first();
            $action_order = intval($docroute->action_order) + 1;
            $docroute = new DocumentRoute;
            $docroute->document_id = $document_id;
            $docroute->office_id = $user->office->id;
            $docroute->user_id = $user->id;
            $docroute->action_order = $action_order;
            $docroute->action = $r->action;
            if($r->action == 'Approve') {

                $hasApproval = true;
            }
            if($r->action == 'Notify' && $hasApproval == false) {
                $docroute->acted_on = date("Y-m-d H:i:s");
            }
            $docroute->sender_id = Auth::user()->id;
            $docroute->sent_on = date("Y-m-d H:i:s");
            $docroute->save();
        }
        return redirect('/document/'.$document_id)
            ->with('status','success')
            ->with('message','Recepients have been added.');
    }

    public function sendDocument(string $id) {
        $docroute = DocumentRoute::where('document_id',$id)->first();
        $docroute->action = "Sent";
        $docroute->sender_id = Auth::user()->id;
        $docroute->acted_on = date("Y-m-d H:i:s");
        $docroute->sent_on = date("Y-m-d H:i:s");
        $docroute->update();
        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','Document has been sent to recepients.');
    }

    public function approveDocument(Request $request) {
        $mydocroute = DocumentRoute::where('document_id',$request->document_id)->where('user_id',Auth::user()->id)->first();
        $comment = $request->comment;
        $action = $request->action;
        if($action == "Approved") {
            $mydocroute->action = "Approved";
            $mydocroute->acted_on = date("Y-m-d H:i:s");
            $mydocroute->comment = $comment;
            $mydocroute->update();
            // Give a date on notify action after this one.
            $continueroute = DocumentRoute::where('document_id',$request->document_id)->whereNull('acted_on')->orderBy('action_order')->get();
            foreach($continueroute as $cr) {
                if($cr->action == "Approve")
                    break;
                $innerRoute = DocumentRoute::find($cr->id);
                $innerRoute->acted_on = date("Y-m-d H:i:s");
                $innerRoute->update();
            }
            return redirect('/document/'.$request->document_id)
            ->with('status','success')
            ->with('message','Document has been approved.');
        } else {
            $mydocroute->action = "Rejected";
            $mydocroute->acted_on = date("Y-m-d H:i:s");
            $mydocroute->comment = $comment;
            $mydocroute->update();
            return redirect('/document/'.$request->document_id)
            ->with('status','warning')
            ->with('message','Document has been rejected.');
        }       
    }
}
