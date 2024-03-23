<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function create()
    {
        return view('document.create');
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
        
        // create a document route, place it as the office of the user
        // create a document approval route, use JavaScript to create a JSON object

        return redirect('/document')
            ->with('status','success')
            ->with('message', 'Document titled ' . $document->title . ' has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }
}
