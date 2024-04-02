<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
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

    public function download(string $url) {
        $file = Attachment::where('url',$url)->get();
        $file??abort('404','Document does not exist.');
        return Storage::download($url,$file->orig_filename);
    }

    public function delete(string $url) {
        $file = Attachment::where('url',$url)->first();
        $file??abort('404','Document does not exist.');
        $document = $file->document;
        Storage::delete('attachments/'.$url);
        Attachment::where('url',$url)->delete();
        return redirect('/document/'.$document->id)
            ->with('status','success')
            ->with('message', 'Attachment has been deleted from ' . $document->title . '.');
    }
}
