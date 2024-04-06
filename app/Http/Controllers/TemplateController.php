<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = Template::all();
        return view('template.index')
            ->with('templates',$templates);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('template.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $template = new Template;
        $template->name = $request->name;
        $template->description = $request->description;
        $template->content = $request->content;
        $template->save();

        return redirect('/template')
            ->with('status','success')
            ->with('message','Template named ' . $template->name . ' has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $template = Template::find($id);
        $template??abort('404','Template does not exist.');
        return view('template.view')
            ->with('template',$template);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = Template::find($id);
        $template??abort('404','Template does not exist.');
        return view('template.edit')
            ->with('template',$template);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $template = Template::find($id);
        $template??abort('404','Template does not exist.');
        $template->name = $request->name;
        $template->description = $request->description;
        $template->content = $request->content;
        $template->update();

        return redirect('/template/'.$template->id)
            ->with('status','success')
            ->with('message','Template named ' . $template->name . ' has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $template = Template::find($id);
        $template??abort('404','Template does not exist.');
        $template->delete();

        return redirect('/template')
            ->with('status','success')
            ->with('message','Template named ' . $template->name . ' has been deleted.');
    }

    // TODO: LINK THE TEMPLATE TO MAKING A NEW DOCUMENT
}
