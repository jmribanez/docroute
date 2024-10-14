<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRoute;
use App\Models\Notification;
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

    /**
     * NOTE: Sep 11
     * This function will no longer be used.
     */
    public function prepare(string $id) {
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $docroute = new DocumentRoute;
        $docroute->document_id = $id;
        $docroute->office_id = Auth::user()->office_id;
        $docroute->user_id = Auth::user()->id;
        $docroute->action_order = 1;
        $docroute->action = "Draft";
        $docroute->save();
        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','Recepients can now be added.');
    }

    public function receive(string $id) {
        // Check if document exists.
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');

        // Check if document is being opened here by owner or by recepient who has already received.
        $docroute = DocumentRoute::where('document_id',$id)->where('user_id',Auth::user()->id)->whereNotNull('sent_on')->whereNotNull('received_on')->first(); 
        if($document->user_id == Auth::user()->id || $docroute != null) {
            return redirect('/document/'.$id);
        }

        // Check if document is being opened by authorized recepient (they've been sent the document).
        // The document wouldn't be listed with them if it was not sent to them.
        $mydocroute = DocumentRoute::where('document_id',$id)->where('user_id',Auth::user()->id)->whereNotNull('sent_on')->first();
        $mydocroute??abort('403','You are not authorized to view the document. Please contact the author for access.');
        
        $docroute = DocumentRoute::where('document_id',$id)->get();
        $myturn = $mydocroute->action == "Approve" && $mydocroute->received_on != null;
        return view('documentroute.receive')
            ->with('document', $document)
            ->with('docroute', $docroute)
            ->with('mydocroute',$mydocroute)
            ->with('myturn',$myturn);
    }

    /**
     * NOTE: Sep 11
     * This function will no longer be used.
     */
    public function confirm(string $id) {
        /*
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        // $docroute = DocumentRoute::where('document_id',$id)->first();
        // $docroute??abort('403','Document has not been prepared or sent for routing.');
        $docroute = DocumentRoute::where('user_id',Auth::user()->id)->where('document_id',$id)->whereNotNull('sent_on')->first();
        $docroute??abort('403','You are not authorized to view this document. Please contact the author for access.');

        $docroute->received_on = date("Y-m-d H:i:s");
        $docroute->update();

        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','The document has been received.');
        */
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $docroute = new DocumentRoute;
        $docroute->document_id = $id;
        $docroute->office_id = Auth::user()->office_id;
        $docroute->user_id = Auth::user()->id;
        $docroute->routed_on = date("Y-m-d H:i:s");
        $docroute->state = "Received";
        $docroute->save();
        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','Document has been received.');
    }

    /**
     * METHOD: POST
     * NOTE: Sep 11, 2024
     * This function will no longer be used.
     * 
     * NOTE: Oct 2, 2024
     * This function will be used again but will be modified to work with the new
     * Notification model.
     */
    public function addRecepients(Request $request) {
        $document_id = $request->document_id;
        $comment = $request->comment;
        $recepients = json_decode($request->recepients);
        $sender_id = Auth::user()->id;
        $document = Document::find($document_id);
        $document??abort('404','Document does not exist.');
        foreach($recepients as $r) {
            $user = User::find($r->id);
            if($user==null)
                continue;
            // Check if user is in the document route
            // $docroute = DocumentRoute::where('user_id',$r->id)->where('document_id',$document_id)->first();
            // if($docroute != null)
            //     continue;
            // $docroute = DocumentRoute::where('document_id',$document_id)->orderBy('action_order','DESC')->first();
            // $action_order = intval($docroute->action_order) + 1;
            // $docroute = new DocumentRoute;
            // $docroute->document_id = $document_id;
            // $docroute->office_id = $user->office->id;
            // $docroute->user_id = $user->id;
            // $docroute->action_order = $action_order;
            // $docroute->action = $r->action;
            // $docroute->save();
            $notification = new Notification();
            $notification->document_id = $document_id;
            $notification->sender_id = $sender_id;
            $notification->receiver_id = $r->id;
            $notification->action = $r->action;
            $notification->comment = $comment;
            $notification->save();
        }

        // $firstroute = DocumentRoute::where('document_id',$document_id)->first();
        // if($firstroute->sent_on != null) {
        //     $docroutes = DocumentRoute::where('document_id',$document->id)->whereNull('sent_on')->orderBy('action_order')->get();
        //     foreach($docroutes as $dr) {
        //         $dr->sent_on = date("Y-m-d H:i:s");
        //         $dr->sender_id = Auth::user()->id;
        //         $dr->update();
        //         if($dr->action == "Approve")
        //             break;
        //     }
        // }

        return redirect('/document/'.$document_id)
            ->with('status','success')
            ->with('message','Recepients have been notified.');
    }

    /**
     * NOTE: Sep 11, 2024
     * This function will no longer be used.
     */
    public function sendDocument(string $id) {
        $docroute = DocumentRoute::where('document_id',$id)->orderBy('action_order')->first();
        $docroute->action = "Sent";
        $docroute->received_on = date("Y-m-d H:i:s");
        $docroute->update();

        $docroutes = DocumentRoute::where('document_id',$id)->whereNull('sent_on')->orderBy('action_order')->get();
        foreach($docroutes as $dr) {
            $dr->sent_on = date("Y-m-d H:i:s");
            $dr->sender_id = Auth::user()->id;
            $dr->update();
            if($dr->action == "Approve")
                break;
        }
        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','Document has been sent to recepients.');
    }

    /**
     * NOTE: Sep 11, 2024
     * This function will no longer be used.
     */
    public function approveDocument(Request $request) {
        $mydocroute = DocumentRoute::where('document_id',$request->document_id)->where('user_id',Auth::user()->id)->whereNotNull('sent_on')->first();
        $comment = $request->comment;
        $action = $request->action;
        if($action == "Approved") {
            $mydocroute->action = "Approved";
            $mydocroute->acted_on = date("Y-m-d H:i:s");
            $mydocroute->comment = $comment;
            $mydocroute->update();
       
            $continueroute = DocumentRoute::where('document_id',$request->document_id)->whereNull('sent_on')->orderBy('action_order')->get();
            foreach($continueroute as $cr) {
                $innerRoute = DocumentRoute::find($cr->id);
                $innerRoute->sent_on = date("Y-m-d H:i:s");
                $innerRoute->sender_id = Auth::user()->id;
                $innerRoute->update();
                if($cr->action == "Approve")
                    break;
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

    /**
     * NOTE: Sep 11, 2024
     * This function will no longer be used.
     */
    public function resetRoute(string $id) {
        $docroutes = DocumentRoute::where('document_id',$id)->get();
        foreach($docroutes as $dr) {
            $dr->delete();
        }
        return redirect('/document/'.$id)
            ->with('status','warning')
            ->with('message','Routes have been reset');
    }

    public function setAction(Request $request, string $id) {
        $userCanEdit = DocumentRoute::where('document_id',$id)->orderBy('routed_on','desc')->first()->user_id == Auth::user()->id?true:false;
        if(!$userCanEdit) {
            abort('403','Action not permitted for this user.');
        }
        $documentRoute = DocumentRoute::where('document_id',$id)->orderBy('routed_on','desc')->first();
        $documentRoute->action = $request->action;
        $documentRoute->acted_on = date("Y-m-d H:i:s");
        $documentRoute->comment = $request->comment;
        $documentRoute->update();

        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','Action has been saved.');
    }

    public function finishRoute(Request $request, string $id) {
        $userCanEdit = DocumentRoute::where('document_id',$id)->orderBy('routed_on','desc')->first()->user_id == Auth::user()->id?true:false;
        if(!$userCanEdit) {
            abort('403','Action not permitted for this user.');
        }
        $document = Document::find($id);
        $document??abort('404','Document does not exist.');
        $docroute = new DocumentRoute;
        $docroute->document_id = $id;
        $docroute->office_id = Auth::user()->office_id;
        $docroute->user_id = Auth::user()->id;
        $docroute->routed_on = date("Y-m-d H:i:s");
        $docroute->state = 'Completed';
        $docroute->action = $request->action;
        $docroute->acted_on = date("Y-m-d H:i:s");
        $docroute->comment = $request->comment;
        $docroute->save();
        $notifications = Notification::where('document_id',$id)->get();
        foreach($notifications as $n) {
            $n->delete();
        }
        return redirect('/document/'.$id)
            ->with('status','success')
            ->with('message','Route has been completed.');
    }

    public function dismissNotification(Request $request) {
        $notification = Notification::find($request->notification_id);
        if($notification == null)
            abort('404', 'Notification does not exist.');
        $notification->dismissed_on = date("Y-m-d H:i:s");
        $notification->update();
        return redirect()->route('home');
    }
}
