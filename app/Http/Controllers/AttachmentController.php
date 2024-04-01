<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
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
}
