<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offices = Office::all();
        return view('office.index')
            ->with('offices', $offices);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $offices = Office::all();
        $users = User::all();
        return view('office.create')
            ->with('offices',$offices)
            ->with('users',$users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $office = new Office;
        $office->office_name = $request->office_name;
        $office->reports_to_office = $request->reports_to_office;
        $office->office_head = $request->office_head;
        $office->office_head_title = $request->office_head_title;
        $office->save();
        return redirect('/office')
            ->with('status', 'success')
            ->with('message', $office->office_name . " has been created.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $office = Office::find($id);
        $reportingFrom = Office::where('reports_to_office', $id)->get();
        $personnel = $office->personnel;
        return view('office.view')
            ->with('office',$office)
            ->with('personnel',$personnel)
            ->with('reportingFrom',$reportingFrom);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $office = Office::find($id);
        $offices = Office::all();
        $users = User::all();
        return view('office.edit')
            ->with('office',$office)
            ->with('offices',$offices)
            ->with('users',$users);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $office = Office::find($id);
        $office->office_name = $request->office_name;
        $office->reports_to_office = $request->reports_to_office;
        $office->office_head = $request->office_head;
        if($request->office_head == "")
            $office->office_head = null;
        $office->office_head_title = $request->office_head_title;
        $office->save();
        return redirect('/office')
            ->with('status', 'success')
            ->with('message', $office->office_name . " has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
