<?php

namespace App\Http\Controllers;

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
    }
}
